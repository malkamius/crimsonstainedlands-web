<?php 
    include("controllers/PageController.php");
    $Page->Title = "Page Not Found";
    $Page->Start();
?>
<div style="text-align: center;margin:50px;">
<span>There was an error attempting to locate the page specified.</span>
</div>
<?php
    $Page->End();
?>
