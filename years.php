<?php

session_start();

if(!isset($_SESSION["user"])){
    ob_start();
	header("Location: index");
	ob_end_flush();
	exit();
}

require_once 'exec/config.php';
require 'exec/misc.php';

$zone = $_SESSION['time'];

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Years</title>
        <link href="css/style.css" type="text/css" rel="stylesheet"/>
        <link rel="shortcut icon" href="img/logo.png"/>
    </head>
    <body>
        <div id="header">
            <h1>Academic Years</h1>
            <img src="img/exit.png" onClick="location.replace('exec/logout');" width="50px" height="50px">
        </div>
        <nav> 
            <ul id="top">
                <li class="tops"><a href="home">Recent</a></li>
                <li class="tops"><a>Students</a>
                    <ul>
                       <li class="others"><a href="countries">Countries</a></li>
                       <li class="others"><a href="courses">Majors and Minors</a></li>
                       <li class="others"><a>Academic Years</a></li>
                       <li class="others"><a href="documents">Files</a></li>
                    </ul>
                </li>
                <li class="tops"><a>Applications</a>
                    <ul>
                       <li class="others"><a href="ongoing">OnGoing</a></li>
                       <li class="others"><a href="complete">Completed</a></li>
                    </ul>
                </li>
                <li class="tops"><a href="new">New</a></li>
            </ul>
        </nav>
        <div id="ser">
            <div id="left-bar">
                <h3>By Recent</h3>
                <hr><br>
                <?php
                
                $fch = $con->query("SELECT enrolled.whn, enrolled.yr, COUNT(1) AS num FROM enrolled GROUP by CONCAT(enrolled.whn, '-', enrolled.yr) ORDER BY yr DESC, whn DESC");
                
                while($r = $fch->fetch()){
                    $sea = "";
                    switch($r["whn"]){
                        case 0:
                            $sea = "Spring";
                            break;
                        case 1:
                            $sea = "Summer";
                            break;
                        case 2:
                            $sea = "Fall";
                            break;
                    }
                    echo '<div class="liist"><p class="llist" onClick="disYear(\''.$r["whn"].'.'.$r["yr"].'\');">'.$sea.' '.$r["yr"].' ('.$r["num"].')</p></div>';
                }
                
                ?>
            </div>
            <div id="right-bar">
                <input id="search" type="text" placeholder="Search here...">
                <span id="btn-search" onClick="search();">Find</span><span id="status"></span>
                <div id="dis"></div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript" src="js/students.js"></script>
    <script type="text/javascript" src="js/years.js"></script>
</html>