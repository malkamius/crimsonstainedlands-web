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
        float: right;
        margin-bottom: 10px;
    }

    #areasTable_filter input {
    color: white;
    background-color: #333; /* Optional: dark background for better contrast */
}
</style>
<table id="areasTable" class="display">
    <thead>
        <tr>
            <th>Name</th>
            <th>Minimum Level</th>
            <th>Maximum Level</th>
            <th>Credits</th>
            <th>Map</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        <!-- Table rows will be inserted here by JavaScript -->
    </tbody>
</table>

<script>
    // Initialize the MUDTextColorizer
    const colorizer = new MUDTextColorizer();

    // Load the areas data
    fetch('areas.json')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('tableBody');
            
            data.areas.forEach(area => {
                const row = tableBody.insertRow();
                // Process the area name with escapeHTML and colorizer.ColorText
                row.insertCell(0).innerHTML = area.name; // Use innerHTML to render the HTML
                row.insertCell(1).textContent = area.minimumlevel;
                row.insertCell(2).textContent = area.maximumlevel;
                row.insertCell(3).innerHTML = escapeHTML(area.credits).replaceAll("{{", "{");
                
                const mapCell = row.insertCell(4);
                const mapLink = document.createElement('a');
                mapLink.href = `maps/${area.name}.png`;
                mapLink.textContent = 'View Map';
                mapLink.target = '_blank'; // Open in a new tab
                mapCell.appendChild(mapLink);
            });

            // Initialize DataTable
            $('#areasTable').DataTable({
                "order": [[0, "asc"]],  // Sort by name initially
                "columnDefs": [
                    { "type": "num", "targets": [1, 2] },  // Ensure level columns are sorted numerically
                    { "type": "html", "targets": [0, 3] }     // Use HTML-aware sorting for the name column
                ],
                "ordering": true,  // Enable sorting
                "paging": false,   // Disable pagination
                "info": false      // Hide information display
            });
        })
        .catch(error => console.error('Error loading areas data:', error));
</script>
<?php 
    $Page->End();
?>