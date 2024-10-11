<?php 
    include_once 'controllers/PageController.php';
    $Page->Title = "Online Client";
    $Page->Start();
?>
    <div class="console">
        <div id="output"></div>
        <input type="text" id="input" placeholder="Enter command..." value="/connect">
        <script lang="javascript">
            document.getElementById("input").select();
        </script>
        <script type="module" src="js/app.js"></script>
    </div>
<?php 
	$Page->End();
?>