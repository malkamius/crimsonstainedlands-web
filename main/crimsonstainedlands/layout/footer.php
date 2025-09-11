<!-- views/footer.php -->
    </div>
<?php
if(!isset($NoFooter) || !$NoFooter)
{
?>
    <script>
        function createUpdatedElement(updatedTime) {
            const updated = document.createElement("div");
            updated.style.margin = "15px";
            updated.style.textAlign = "center";
            updated.innerHTML = `<span>Last Updated: ${updatedTime}</span>`;
            return updated;
        }
        function fetchPlayerCount() {
        const host = window.location.hostname;
        const port = 4003;
        const url = `${window.location.protocol}//${host}:${port}/who`;
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const mainContainer = document.getElementById("player_count");
                mainContainer.innerHTML = ''; // Clear previous content

                let updated = null;
                
                if (!data || !data.Players || !Array.isArray(data.Players)) {
                    mainContainer.innerHTML = "<p>No data available.</p>";
                }
                else if (data.Players.length === 0) {
                    mainContainer.innerHTML = "<p>No one online.</p>";
                } else {
                    playercount = data.Players.length;
                    mainContainer.innerHTML = "<p>" + playercount + " player" + (playercount > 1? "s " : " ") + "online.</p>";
                }
                updated = createUpdatedElement(new Date().toLocaleString());
                mainContainer.appendChild(updated);
            },
            error: function(err) {
                $('#player_count').html("<p>Error connecting to MUD.</p>");
            }
        });
    }

    // Initial fetch
    $(document).ready(function () {
        fetchPlayerCount();
        // Fetch every 10 seconds
        setInterval(fetchPlayerCount, 10000);
     });


    
    </script>
    <div id="player_count" style="text-align: center;margin: 50px;"></div>

    <div style="text-align: center;margin: 50px;">
        <span>Connect to the Crimson Stained Lands at server crimsonstainedlands.net port 4000 <br> 
        ( ip address:port &nbsp;&nbsp;64.224.71.222:4000 &nbsp;&nbsp;)</span>
    </div>
<?php
}
?>
    <footer style="margin: 15px; text-align: center;">
        
        <span>© Crimson Stained Lands - 2024</span>
        
    </footer>
</body>
</html>