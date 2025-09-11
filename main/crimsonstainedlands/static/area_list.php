<?php 
    include 'controllers/PageController.php';
    $Page->Title = "Maps";
    $Page->Start();
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<style>
    table.dataTable tbody tr td {
        color: white !important;
    }
    /* Style for header of sorted column */
    table.dataTable thead .sorting_asc,
    table.dataTable thead .sorting_desc {
        background-color: #444 !important; /* Dark grey background */
        color: white !important; /* White text */
    }
    
    /* Style for data cells in sorted column */
    table.dataTable tbody td.sorting_1 {
        background-color: #444 !important; /* Dark grey background */
        color: white !important; /* White text */
    }
    
    /* Style for hover state of header */
    table.dataTable thead th:hover {
        background-color: #555 !important; /* Slightly lighter grey for hover */
    }
    
    /* Ensure other text remains visible */
    table.dataTable thead th,
    table.dataTable tbody td {
        color: white; /* Default text color */
    }

    /* Style for hover state of rows */
    table.dataTable tbody tr:hover {
        background-color: #f5f5f5 !important; /* Light grey background for row hover */
    }

    /* Ensure text remains visible when hovering over the sorted column */
    table.dataTable tbody tr:hover td.sorting_1 {
        background-color: #555 !important; /* Slightly lighter grey for sorted column hover */
        color: white !important;
    }

    /* Style for links in the sorted column */
    table.dataTable tbody td.sorting_1 a {
        color: #add8e6 !important; /* Light blue color for links */
    }

    /* Style for links on hover in the sorted column */
    table.dataTable tbody td.sorting_1 a:hover {
        color: #ffffff !important; /* White color for links on hover */
        text-decoration: underline;
    }

    /* Hide the "Show X entries" dropdown */
    .dataTables_length {
        display: none;
    }

    /* Adjust the search box position */
    .dataTables_filter {
        text-align: center;
        margin-bottom: 10px;
    }

    #areasTable_filter input {
    color: white;
    background-color: #333; /* Optional: dark background for better contrast */
}
</style>
<style>
    /* Styles for the modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        
        background-color: rgba(0,0,0,0.7);
    }

    .modal-content {
        background-color: #222;
        color: white;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 5px;
        max-height: 60%;
    }
    .modal-scroll {
        overflow-y: scroll;
        max-height: 200px;
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #fff;
        text-decoration: none;
        cursor: pointer;
    }

    #areasTable tbody tr {
        cursor: pointer;
    }
</style>
<style>
    .connections-list {
        text-align: center;

        list-style-type: disc;
        padding-left: 20px;
        margin: 20px;
        /*width: 200px; */
    }
    .listitem {
        display: block;
    }
    .map-image {
        max-width: 300px;
        max-height: 200px;
        width: 100%;
        height: auto;
        object-fit: cover;
        object-position: center;
        margin:20px;
    }
</style>
<table id="areasTable" class="display">
    <thead>
        <tr>
            <th>Name</th>
            <th>Minimum Level</th>
            <th>Maximum Level</th>
            <th>Credits</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        <!-- Table rows will be inserted here by JavaScript -->
    </tbody>
</table>
<div id="areaModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="areaName"></h2>
        <div id="areaDetails"></div>
    </div>
</div>
<script>
    let areasData; // To store the full areas data
    // Initialize the MUDTextColorizer
    const colorizer = new MUDTextColorizer();

    // Load the areas data
    fetch('areas.json')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('tableBody');
            areasData = data.Areas;
            data.Areas.forEach(area => {
                const row = tableBody.insertRow();
                // Process the area name with escapeHTML and colorizer.ColorText
                row.insertCell(0).innerHTML = area.Name; // Use innerHTML to render the HTML
                row.insertCell(1).textContent = area.MinimumLevel;
                row.insertCell(2).textContent = area.MaximumLevel;
                row.insertCell(3).innerHTML = escapeHTML(area.Credits);
                row.addEventListener('click', () => showAreaDetails(area));
                // const mapCell = row.insertCell(4);
                // const mapLink = document.createElement('a');
                // mapLink.href = `/maps/${area.Name}.png`;
                // mapLink.textContent = 'View Map';
                // mapLink.target = '_blank'; // Open in a new tab
                // mapCell.appendChild(mapLink);
            });

            // Initialize DataTable
            const table = $('#areasTable').DataTable({
                "order": [[0, "asc"]],  // Sort by name initially
                "columnDefs": [
                    { "type": "num", "targets": [1, 2] },  // Ensure level columns are sorted numerically
                    { "type": "html", "targets": [0, 3] }     // Use HTML-aware sorting for the name column
                ],
                "ordering": true,  // Enable sorting
                "paging": false,   // Disable pagination
                "info": false,      // Hide information display
                "autoWidth": true,
                "scrollX": true
            });
            adjustColumnWidths(table);
        })
        .catch(error => console.error('Error loading areas data:', error));
    function adjustColumnWidths(table) {
        table.columns().every(function() {
            const column = this;
            const header = $(column.header());
            const maxWidth = Math.max(
                header.width(),
                column.nodes().toArray().reduce((max, cell) => Math.max(max, $(cell).width()), 0)
            );
            header.width(maxWidth);
            column.nodes().toArray().forEach(cell => $(cell).width(maxWidth));
        });
        table.draw();
    }

    // Adjust column widths on window resize
    $(window).resize(function() {
        const table = $('#areasTable').DataTable();
        adjustColumnWidths(table);
    });
        // Function to show area details in modal
    function showAreaDetails(area) {
        const modal = document.getElementById('areaModal');
        const areaName = document.getElementById('areaName');
        const areaDetails = document.getElementById('areaDetails');
        const safeMapName = area.MapName
        .replace(/&/g, '&amp;')
        .replace(/'/g, '&#39;')
        .replace(/"/g, '&quot;');
        areaName.innerHTML = colorizer.ColorText(area.Name);
        
        let detailsHTML = `
            <p><strong>Credits:</strong> ${escapeHTML(area.Credits)}</p>
            <p><strong>Level Range:</strong> ${area.MinimumLevel} - ${area.MaximumLevel}</p>
            <p><strong>Vnum Range:</strong> ${area.MinimumVnum} - ${area.MaximumVnum}</p>
            <p><strong>Last Edited By:</strong> ${escapeHTML(area.LastEditedBy || 'N/A')}</p>
            <p><strong>Connections:</strong></p>
            <div class="modal-scroll">
            <ul class="connections-list">
                ${area.Connections.map(conn => `<li class="listitem"><a href="#" onclick="showConnectedArea('${escapeHTML(conn)}'); return false;">${escapeHTML(conn)}</a></li>`).join('')}
            </ul>
            </div>
            <a href='/maps/${safeMapName}.png' target="_blank"><img class='map-image' src='/maps/${safeMapName}.png' alt="Area Map"></a>
        `;

        areaDetails.innerHTML = detailsHTML;
        modal.style.display = "block";
    }

    

    // Function to show details of a connected area
    function showConnectedArea(areaName) {
        const connectedArea = areasData.find(area => area.Name === areaName);
        if (connectedArea) {
            showAreaDetails(connectedArea);
        } else {
            console.error(`Area "${areaName}" not found in the data.`);
        }
    }

    // Close modal when clicking on <span> (x)
    document.querySelector('.close').onclick = function() {
        document.getElementById('areaModal').style.display = "none";
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('areaModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
<?php 
    $Page->End();
?>