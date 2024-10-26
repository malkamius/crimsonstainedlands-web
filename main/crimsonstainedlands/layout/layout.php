<?php 
include_once("header.php");
?>
<?php if (isset($content)) echo $content; ?>
<?php 
if(!isset($NoFooter) || !$NoFooter)
{
    include_once("footer.php");
}
?>