<?php
// index.php

error_reporting(E_ALL);

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);

$action = isset($_GET['action']) ? $_GET['action'] : '';

session_start();
//require_once 'controllers/UserController.php';
//require_once 'controllers/MessageController.php';


//$userController = new UserController();
//$messageController = new MessageController();
function is_jpeg(&$pict)
{
    return (bin2hex($pict[0]) == 'ff' && bin2hex($pict[1]) == 'd8');
}

function is_png(&$pict)
{
    return (bin2hex($pict[0]) == '89' && $pict[1] == 'P' && $pict[2] == 'N' && $pict[3] == 'G');
}


switch ($action) {
	case "who":
		include("views/who.php");
		break;
	case "help":
		include("views/help.php");
		break;
	default:        
		$pattern = '/([^\/]+)\.(php|json)$/';
        if (preg_match($pattern, $action, $matches) && file_exists("static/" . $matches[1] . "." . $matches[2]))
        {
            $file = "static/" . $matches[1] . "." . $matches[2];
			if(file_exists($file))
			{
				if ($matches[2] === 'json') {
					header('Content-Type: application/json');
					readfile($file);
				} else {
					include($file);
				}
			}
			else
				include("404.php");
        }
		else if($action == "" || $action == "/" || $action == "index" || $action == "index.php")
		{
			include("views/main.php");
		}
		else
		{
			include("404.php");
		}
		
		
		break;
}




?>