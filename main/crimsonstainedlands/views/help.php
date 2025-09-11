<?php
// config/aragond_database.php
class CSLDatabase {
    private $host = "localhost";
    private $db_name = "csl";
    private $username = "csl";
    private $password = "csl";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

function get_help_entries($conn, $filter, $page, $page_size, $starts_with = "") : string {
    $page = intval($page);
    $offset = ($page - 1) * $page_size;

    // if($starts_with == "")
    //     // Prepare SQL with filtering and pagination
    //     $sql = "SELECT keywords, last_updated, last_updated_by, text
    //             FROM helps 
    //             WHERE keywords LIKE CONCAT('%', :filter, '%')
    //             ORDER BY REPLACE(keywords, '''', '')
    //             LIMIT :pagesize OFFSET :offset";
    // else
    // {
    //     $sql = "SELECT keywords, last_updated, last_updated_by, text
    //     FROM helps 
    //     WHERE (keywords LIKE CONCAT('%', :filter, '%'))
    //     AND (keywords LIKE CONCAT('%''', :starts_with, '%') OR keywords LIKE CONCAT(:starts_with, '%'))
    //     ORDER BY REPLACE(keywords, '''', '')
    //     LIMIT :pagesize OFFSET :offset";

    // }
    $sql = "SELECT * FROM csl.helps
WHERE keywords REGEXP CONCAT(
    '(^|[[:space:]])(', 
    '''', regex_escape(:filter), '[^'']*''', 
    '|', 
    regex_escape(:filter),
    '[^[[:space:]]|$]*',
    '([[:space:]]|$)'
    ')'
)
AND (:filter = '' OR keywords NOT REGEXP CONCAT(
    '''[^'']*[[:space:]]', regex_escape(:filter), '[^'']*''' 
))
AND keywords REGEXP CONCAT(
    '(^|[[:space:]])(', 
    '''', regex_escape(:starts_with), '[^'']*''', 
    '|',  -- OR
    regex_escape(:starts_with),
    '[^[[:space:]]|$]*',
    '([[:space:]]|$)'
    ')'
)
AND (:starts_with = '' OR keywords NOT REGEXP CONCAT(
    '''[^'']*[[:space:]]', regex_escape(:starts_with), '[^'']*''' 
))
ORDER BY REPLACE(keywords, '''', '')
LIMIT :pagesize OFFSET :offset;";

    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die(json_encode(["error" => "Prepare failed: " . $this->conn->errorInfo()[2]]));
    }
    // Bind parameters
    $filterParam = $filter;//"%" . $filter . "%";
    //if($starts_with != "")
    //{
        //$starts_with = $starts_with . "%";
        $stmt->bindParam(':starts_with', $starts_with, PDO::PARAM_STR);
    //}
    $stmt->bindParam(':filter', $filterParam, PDO::PARAM_STR);
    $stmt->bindParam(':pagesize', $page_size, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    //echo($sql . "\n<br>" . $filterParam . "\n<br>" . $starts_with);

    if (!$stmt->execute()) {
        die(json_encode(["error" => "Execute failed: " . $stmt->errorInfo()[2]]));
    }

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    if($starts_with == "")
    {
        $countSql = "SELECT COUNT(*) as total FROM helps WHERE keywords LIKE CONCAT('%', :filter, '%')";
    }
    else
    {
        $countSql = "SELECT COUNT(*) as total FROM helps WHERE keywords LIKE CONCAT('%', :filter, '%') AND (keywords LIKE CONCAT('%''', :starts_with, '%') OR keywords LIKE CONCAT('%', :starts_with, '%'))";
    }
    $countSql = "SELECT count(1) FROM csl.helps
WHERE keywords REGEXP CONCAT(
    '(^|[[:space:]])(', 
    '''', regex_escape(:filter), '[^'']*''', 
    '|', 
    regex_escape(:filter),
    '[^[[:space:]]|$]*',
    '([[:space:]]|$)'
    ')'
)
AND (:filter = '' OR keywords NOT REGEXP CONCAT(
    '''[^'']*[[:space:]]', regex_escape(:filter), '[^'']*''' 
))
AND keywords REGEXP CONCAT(
    '(^|[[:space:]])(', 
    '''', regex_escape(:starts_with), '[^'']*''', 
    '|',  -- OR
    regex_escape(:starts_with),
    '[^[[:space:]]|$]*',
    '([[:space:]]|$)'
    ')'
)
AND (:starts_with = '' OR keywords NOT REGEXP CONCAT(
    '''[^'']*[[:space:]]', regex_escape(:starts_with), '[^'']*''' 
))";
    //die($countSql);
    $countStmt = $conn->prepare($countSql);
    $countStmt->bindParam(':filter', $filterParam, PDO::PARAM_STR);
    //if($starts_with != "")
    $countStmt->bindParam(':starts_with', $starts_with, PDO::PARAM_STR);
    $countStmt->execute();
    $total = $countStmt->fetchColumn();
    $countStmt->closeCursor();
    $response = [
        "data" => $data,
        "pagination" => [
            "currentPage" => $page,
            "totalPages" => ceil($total / $page_size),
            "totalItems" => $total
        ]
    ];
    //print_r($response);
    return (json_encode($response));
}
    if(isset($_REQUEST['json']) && $_REQUEST['json'] = 1)
    {
        $database = new CSLDatabase();
        $db = $database->getConnection();
        $filter = "";
        $page = 1;
        $page_size = 10;
        $starts_with = "";
        if(isset($_REQUEST['filter']))
        {
            $filter = $_REQUEST['filter'];
        }
        if(isset($_REQUEST['page']))
        {
            $page = $_REQUEST['page'];
        }
        if(isset($_REQUEST['starts_with']))
        {
            $starts_with = $_REQUEST['starts_with'];
        }
        $help_entries = get_help_entries($db, $filter, $page, $page_size, $starts_with);

        die($help_entries);
    }
?>
<?php 
    include 'controllers/PageController.php';
    $Page->Title = "Helpfiles";
    $Page->Start();
?>
<div class="container" style="text-align: center;">
    <h1>Help Files</h1>
    <div style="margin: 15px">
        <label for="filter">Filter: </label>
        <input id="filter" />
        <button id="applyFilter">Apply</button>
    </div>
    <div id="tableContainer"></div>
    <div id="pagination"></div>
    <div id="alphabetLinks" class="alphabet-links"></div>
</div>


<script>
    let startsWith = "";
$(document).ready(function() {
    let currentPage = 1;
    let totalPages = 1;
    let filterTimer;
    let currentFilter = '';

    function loadHelpFiles(page = 1, filter = '', starts_with = '', pushState = true) {
        $.ajax({
            url: '/index.php?action=help',
            method: 'GET',
            data: { json: 1, page: page, filter: filter, starts_with: starts_with },
            dataType: 'json',
            success: function(response) {
                displayTable(response.data);
                displayPagination(response.pagination);
                currentPage = response.pagination.currentPage;
                totalPages = response.pagination.totalPages;
                currentFilter = filter;
                startsWith = starts_with;
                if (pushState) {
                    updateURL(true);
                }
                updateAlphabetLinks();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching help files:", error);
                $('#tableContainer').html('<p>Error loading help files. Please try again later.</p>');
            }
        });
    }

    function updateURL(pushState = false) {
        let url = new URL(window.location);
        
        url.searchParams.set('page', currentPage);
        
        if (currentFilter) {
            url.searchParams.set('filter', currentFilter);
        } else {
            url.searchParams.delete('filter');
        }
        
        if (startsWith) {
            url.searchParams.set('starts_with', startsWith);
        } else {
            url.searchParams.delete('starts_with');
        }
        
        if (pushState) {
            window.history.pushState({ page: currentPage, filter: currentFilter, starts_with: startsWith }, '', url);
        } else {
            window.history.replaceState({ page: currentPage, filter: currentFilter, starts_with: startsWith }, '', url);
        }
    }

    function displayTable(data) {
        const colorizer = new MUDTextColorizer();
        let tableHtml = '<table border="1" style="width: 100%;"><tr><th>Keywords</th><th>Last Edit</th><th>Last Edited By</th><th>Text</th></tr>';
        data.forEach(function(item, index) {
            let firstLine = item.text.split('\n')[0];
            let restOfText = item.text.split('\n').slice(1).join('\n');
            if(restOfText != "")
                more = `<span class="ellipsis" data-index="${index}">(more)</span>`;
            else 
            more = "";
            tableHtml += `<tr>
                <td style="width: 150px;">${colorizer.ColorText(escapeHTML(item.keywords))}</td>
                <td>${escapeHTML(item.last_updated)}</td>
                <td>${escapeHTML(item.last_updated_by)}</td>
                <td>
                    <div class="text-content" style="text-align: left;">
                        ${more}
                        <span class="first-line">${colorizer.ColorText(escapeHTML(firstLine).replaceAll(' ', '&nbsp;'))}</span>
                        <span class="full-text" style="display:none;"><br>${colorizer.ColorText(escapeHTML(restOfText).replaceAll(' ', '&nbsp;')).replaceAll('\n', '<br>')}</span>
                    </div>
                </td>
            </tr>`;
        });
        tableHtml += '</table>';
        $('#tableContainer').html(tableHtml);

        $('.ellipsis').click(function() {
            let index = $(this).data('index');
            let fullTextSpan = $(this).siblings('.full-text');
            if (fullTextSpan.is(':visible')) {
                fullTextSpan.hide();
                $(this).text('(more) ');
            } else {
                fullTextSpan.show();
                $(this).html('(less) <br>');
            }
        });
    }

    function displayPagination(pagination) {
        currentPage = pagination.currentPage;
        let paginationHtml = '<div class="pagination">';
        paginationHtml += '<span class="pageNav" data-page="1">&lt;&lt;</span> ';
        paginationHtml += '<span class="pageNav" data-page="' + (currentPage > 1 ? currentPage - 1 : 1) + '">Previous</span> ';
        
        let startPage = Math.max(1, pagination.currentPage - 2);
        let endPage = Math.min(pagination.totalPages, startPage + 4);
        
        for (let i = startPage; i <= endPage; i++) {
            paginationHtml += `<span class="pageNav${i === pagination.currentPage ? ' current' : ''}" data-page="${i}">${i}</span> `;
        }
        
        paginationHtml += '<span class="pageNav" data-page="' + (currentPage < totalPages ? currentPage + 1 : totalPages) + '">Next</span> ';
        paginationHtml += `<span class="pageNav" data-page="${pagination.totalPages}">&gt;&gt;</span>`;
        paginationHtml += '</div>';
        paginationHtml += '<div class="pagination"><span>' + pagination.totalPages + ' total page(s)</span></div>';
        $('#pagination').html(paginationHtml);
    }

    function applyFilter() {
        let filterValue = $('#filter').val();
        loadHelpFiles(1, filterValue, startsWith);
    }

    $('#applyFilter').click(applyFilter);

    function createAlphabetLinks() {
        let alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let linksHtml = '<span class="alphaNav clear-filter" data-letter="">*</span> ';
        for (let i = 0; i < alphabet.length; i++) {
            linksHtml += `<span class="alphaNav" data-letter="${alphabet[i]}">${alphabet[i]}</span> `;
        }
        $('#alphabetLinks').html(linksHtml);
    }

    function updateAlphabetLinks() {
        $('.alphaNav').removeClass('active');
        if (startsWith === '') {
            $('.alphaNav.clear-filter').addClass('active');
        } else {
            $(`.alphaNav[data-letter="${startsWith}"]`).addClass('active');
        }
    }

    $('#applyFilter').click(applyFilter);

     $('#filter').on('input', function() {
        clearTimeout(filterTimer);
        filterTimer = setTimeout(applyFilter, 300);
    });

    $(document).on('click', '.pageNav', function() {
        let page = $(this).data('page');
        loadHelpFiles(page, currentFilter, startsWith);
    });

    $(document).on('click', '.alphaNav', function(e) {
        e.preventDefault();
        let letter = $(this).data('letter');
        startsWith = letter;
        if ($(this).hasClass('clear-filter')) {
            //$('#filter').val('');
            startsWith = '';
        }
        loadHelpFiles(1, $('#filter').val(), startsWith);
    });

    function loadInitialState() {
        let url = new URL(window.location);
        let page = url.searchParams.get('page') || 1;
        let filter = url.searchParams.get('filter') || '';
        let starts_with = url.searchParams.get('starts_with') || '';
        $('#filter').val(filter);
        loadHelpFiles(page, filter, starts_with, false);
    }

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        if (event.state) {
            currentPage = event.state.page;
            currentFilter = event.state.filter;
            startsWith = event.state.starts_with;
            $('#filter').val(currentFilter);
            loadHelpFiles(currentPage, currentFilter, startsWith, false);
        } else {
            loadInitialState();
        }
    });

    // Initial load
    createAlphabetLinks();
    loadInitialState();

   
    // Handle browser back/forward buttons
    window.onpopstate = function(event) {
        loadInitialState();
    };

});
</script>

<style>
.pagination { margin-top: 20px; }
.pageNav { cursor: pointer; padding: 5px 10px; border: 1px solid #ddd; margin: 0 2px; }
.alphaNav { 
    cursor: pointer;
    text-decoration: none;
    padding: 5px 10px;
    margin: 0 2px;
    border: 1px solid #ddd;
    color: #007bff;
}
.pageNav.current, .alphaNav.active {
    background-color: #007bff;
    color: white;
}
.ellipsis { cursor: pointer; color: red; }
.full-text { display: none; }
.text-content { font-family: 'Courier New', Courier, monospace; }
#tableContainer {
    width: 70vw;
}
</style>
<?php 
    $Page->End();
?>