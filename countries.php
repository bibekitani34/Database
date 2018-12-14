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
        <title>Countries</title>
        <link href="css/style.css" type="text/css" rel="stylesheet"/>
        <link rel="shortcut icon" href="img/logo.png"/>
    </head>
    <body>
        <div id="header">
            <h1>Countries</h1>
            <img src="img/exit.png" onClick="location.replace('exec/logout');" width="50px" height="50px">
        </div>
        <nav> 
            <ul id="top">
                <li class="tops"><a href="home">Recent</a></li>
                <li class="tops"><a>Students</a>
                    <ul>
                       <li class="others"><a>Countries</a></li>
                       <li class="others"><a href="courses">Majors and Minors</a></li>
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
                <h3>By Population</h3>
                <hr><br>
                <?php
                
                $fch = $con->query("SELECT countries.id, countries.name, COUNT(1) AS num FROM general INNER JOIN location ON general.loc = location.id INNER JOIN provinces ON location.prov = provinces.id INNER JOIN countries ON countries.id = provinces.countries GROUP by countries.name ORDER BY num DESC, name");
                
                while($r = $fch->fetch()){
                    echo '<div class="liist"><p class="llist" onClick="disCoun(\''.$r["id"].'\');">'.$r["name"].' ('.$r["num"].')</p></div>';
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
    <script type="text/javascript" src="js/couns.js"></script>
</html>