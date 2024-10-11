<?php 
    include_once("controllers/PageController.php"); 
    $Page->Title = "Who List";
    $Page->Start();
?>

<h2>Current Who List</h1>
<div id="who_list" style="text-align: center;"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>

    function appendData(data) {
        const mainContainer = document.getElementById("who_list");
        mainContainer.innerHTML = ''; // Clear previous content

        let updated = null;
        
        if (!data || !data.Players || !Array.isArray(data.Players)) {
            mainContainer.innerHTML = "<p>No data available.</p>";
        }
        else if (data.Players.length === 0) {
            mainContainer.innerHTML = "<p>No one online.</p>";
        } else {
            const table = document.createElement("table");
            table.innerHTML = '<tr><th>Level</th><th>Race</th><th>Class</th><th>Name &amp; Title</th></tr>';
            const colorizer = new MUDTextColorizer();
            let playercount = 0;

            data.Players.forEach(item => {
                if (item.Name && item.Level && item.Race && item.Title) {
                    playercount++;
                    const tr = document.createElement("tr");
                    if (!item.Title.startsWith(",")) {
                        item.Title = " " + item.Title;
                    }
                    tr.innerHTML = `<td>${escapeHTML(item.Level)}</td><td>${escapeHTML(item.Race)}</td><td>[${escapeHTML(item.Class)}]</td><td style='text-align: left'>${escapeHTML(item.Flags)}${item.Flags != ""? " " : ""}${escapeHTML(item.Name)}${colorizer.ColorText(escapeHTML(item.Title))}</td>`;
                    table.appendChild(tr);
                }
            });

            mainContainer.appendChild(table);

            // if(playercount > 0)
            // {
            //     const playercount_div = document.createElement("div");
            //     playercount_div.style.margin = "15px";
            //     playercount_div.style.textAlign = "center";
            //     playercount_div.innerHTML = "<p>" + playercount + " player" + (playercount > 1? "s " : " ") + "online.</p>";
            //     mainContainer.appendChild(playercount_div);
            // }
        }

        // Create the updated element with the current date/time
        //updated = createUpdatedElement(new Date().toLocaleString());
        //mainContainer.appendChild(updated);
    }

    function createUpdatedElement(updatedTime) {
        const updated = document.createElement("div");
        updated.style.margin = "15px";
        updated.style.textAlign = "center";
        updated.innerHTML = `<span>Last Updated: ${updatedTime}</span>`;
        return updated;
    }

    function fetchWhoList() {
        const host = window.location.hostname;
        const port = 4003;
        const url = `${window.location.protocol}//${host}:${port}/who`;
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                appendData(data);
            },
            error: function(err) {
                $('#who_list').html("<p>Error fetching data.</p>");
            }
        });
    }

    // Initial fetch
    fetchWhoList();

    // Fetch every 10 seconds
    setInterval(fetchWhoList, 10000);
</script>
<?php
    $Page->End();
?>