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
        <title>OnGoing</title>
        <link href="css/style.css" type="text/css" rel="stylesheet"/>
        <link rel="shortcut icon" href="img/logo.png"/>
    </head>
    <body>
        <div id="header">
            <h1>OnGoing</h1>
            <img src="img/exit.png" onClick="location.replace('exec/logout');" width="50px" height="50px">
        </div>
        <nav> 
            <ul id="top">
                <li class="tops"><a href="home">Recent</a></li>
                <li class="tops"><a>Students</a>
                    <ul>
                       <li class="others"><a href="countries">Countries</a></li>
                       <li class="others"><a href="courses">Majors and Minors</a></li>
                       <li class="others"><a href="years">Academic Years</a></li>
                    </ul>
                </li>
                <li class="tops"><a>Applications</a>
                    <ul>
                       <li class="others"><a>OnGoing</a></li>
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
                
                $fch = $con->query("SELECT application.class, application.whn, application.yr, COUNT(1) AS num FROM application WHERE (SELECT COUNT(1) FROM enrolled WHERE enrolled.uid = application.gid) = 0 GROUP by CONCAT(application.whn, '-', application.yr) ORDER BY yr DESC, whn DESC");
                
                $apl = false;
                while($r = $fch->fetch()){
                    if(!$apl && $r["class"]){
                        echo '
                        <br><br><h3>Graduate</h3>
                        <hr><br>';
                        $apl = true;
                    }
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
                    echo '<div class="liist"><p class="llist" onClick="disGoing(\''.$r["whn"].'\', \''.$r["yr"].'\');">'.$sea.' '.$r["yr"].' ('.$r["num"].')</p></div>';
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
    <script type="text/javascript" src="js/ongoing.js"></script>
</html>