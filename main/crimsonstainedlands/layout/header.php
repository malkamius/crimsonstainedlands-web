<!DOCTYPE html>
<html>
<head>    
    <title>CrimsonStainedLands<?php if(isset($title) && $title != "") echo " - " . $title; ?></title>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="/css/main.css">

     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    
    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="/js/mudcolor.js"></script>
</head>
<body>
    <div style="text-align: center;">
    <header>
        <div>
			<h1 class="pageheader">The Crimson Stained Lands</h1>
        </div>
    </header>
    <ul class="navigation">
        <li class="navigation">
            <a href="index.php"><button>Home</button></a>
        </li>   
        <li class="navigation">
            <a href="index.php?action=who"><button>Who's Online</button></a>
        </li>     
        <li class="navigation">
            <a href="area_list.php"><button>Area List</button></a>
        </li>   
        <li class="navigation">
            <a href="index.php?action=help"><button>Help Files</button></a>
        </li>
        <li class="navigation">
            <a href="world_map_viewer.php"><button>World Map</button></a>
        </li>
        <li class="navigation">
            <a href="client.php"><button>Play Now!</button></a>
        </li>
    </ul>
    </div>
    <div class="main-content">
