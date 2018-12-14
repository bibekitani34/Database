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
        <title>Courses</title>
        <link href="css/style.css" type="text/css" rel="stylesheet"/>
        <link rel="shortcut icon" href="img/logo.png"/>
    </head>
    <body>
        <div id="header">
            <h1>Courses</h1>
            <img src="img/exit.png" onClick="location.replace('exec/logout');" width="50px" height="50px">
        </div>
        <nav> 
            <ul id="top">
                <li class="tops"><a href="home">Recent</a></li>
                <li class="tops"><a>Students</a>
                    <ul>
                       <li class="others"><a href="countries">Countries</a></li>
                       <li class="others"><a>Majors and Minors</a></li>
                       <li class="others"><a href="years">Academic Years</a></li>
                    </ul>
                </li>
                <li class="tops"><a>Applications</a>
                    <ul>
                       <li class="others"><a href="ongoing">OnGoing</a></li>
                       <li class="others"><a href="documents">Files</a></li>
                    </ul>
                </li>
                <li class="tops"><a href="new">New</a></li>
            </ul>
        </nav>
        <div id="ser">
            <div id="left-bar">
                <h3>Undergraduate</h3>
                <hr><br>
                <?php
                
                $fch = $con->query("SELECT courses.id, courses.name, courses.type, courses.clas, COUNT(1) AS num FROM general INNER JOIN enrolled ON enrolled.uid = general.id INNER JOIN stcourse ON stcourse.stid = enrolled.stdid INNER JOIN courses ON courses.id = stcourse.course GROUP BY courses.name ORDER BY courses.clas, courses.name DESC");
                
                $enrl = false;
                while($r = $fch->fetch()){
                    if(!$enrl && $r["clas"] != "U"){
                        echo '<br><h3>Graduate<h3><hr><br>';
                        $enrl = true;
                    }
                    $t = $r["type"] == "J" ? "M" : "m";
                    echo '<div class="liist"><p class="llist" onClick="disCourse(\''.$r["id"].'\');"><b>'.$t.'</b> '.$r["name"].' ('.$r["num"].')</p></div>';
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
    <script type="text/javascript" src="js/course.js"></script>
</html>