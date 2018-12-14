<?php

session_start();

if(!isset($_SESSION["user"])){
    ob_start();
	header("Location: index");
	ob_end_flush();
	exit();
}

require 'exec/misc.php';
require_once 'exec/config.php';

$zone = $_SESSION['time'];

?>
<!DOCTYPE html>
<html>
    <head>
        <title>New</title>
        <link href="css/style.css" type="text/css" rel="stylesheet"/>
        <link rel="shortcut icon" href="img/logo.png"/>
    </head>
    <body>
        <div id="header">
            <h1>New</h1>
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
                       <li class="others"><a href="ongoing">OnGoing</a></li>
                       <li class="others"><a href="documents">Files</a></li>
                    </ul>
                </li>
                <li class="tops"><a>New</a></li>
            </ul>
        </nav>
        <div id="res">
            <div id="mid">
                <div id="f-side" class="flx">
                    <h3>Fill In | It's Quick and Easy</h3>
                    <div id="nmsg" class="msg"></div>
                    <form>
                        <table class="nwe">
                            <col width="30%">
                            <col width="65%">
                            <col width="5%">
                            <tr>
                                <td class="tp">First Name</td>
                                <td><input id="fname" type="text"></td>
                                <td>*</td>
                            </tr>
                            <tr>
                                <td class="tp">Middle Name</td>
                                <td><input id="mname" type="text"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="tp">Last Name</td>
                                <td><input id="lname" type="text"></td>
                                <td>*</td>
                            </tr>
                            <tr>
                                <td class="tp">Preferred Name</td>
                                <td><input id="pname" type="text"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="tp">Gender</td>
                                <td><select id="gender">
                                    <option selected disabled>Select A Gender</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select></td>
                                <td>*</td>
                            </tr>
                            <tr>
                                <td class="tp">Date of Birth</td>
                                <td><input id="dob" type="date"></td>
                                <td>*</td>
                            </tr>
                            <tr>
                                <td class="tp">Phone Number</td>
                                <td><input id="phone" value="(+0) " type="text"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="tp">E-mail</td>
                                <td><input id="email" type="text"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="tp">Country</td>
                            <?php
                            
                            $fcouns = $con->query("SELECT id, name, code FROM countries ORDER BY name");
                                echo '
                                <td><select id="country" onchange="fCoun();">
                                    <option selected disabled>Select A Country</option>';
                                
                                while ($ct = $fcouns->fetch()){
                	                if($ct["name"] == $r["coun"]) echo '
                	                <option selected ';
                	                else echo '
                	                <option ';
                	                echo 'value="'.$ct["id"].'">'.$ct["name"].' (+'.$ct["code"].')</option>';
                	            }
                                echo '</select></td>';
                            ?>
                                <td>*</td>
                            </tr>
                            <tr>
                                <td class="tp">State/Province</td>
                                <td><div class="autocomplete"><input id="state" type="text"></div></td>
                                <td>*</td>
                            </tr>
                            <tr>
                                <td class="tp">City/Town</td>
                                <td><div class="autocomplete"><input id="city" type="text"></div></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="tp">ZIP Code</td>
                                <td><input id="zip" type="text"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="tp">Address</td>
                                <td><input id="address" id="address" type="text"></td>
                                <td></td>
                            </tr>
                        </table>
                        <div align="center"><div class="btn-out btn-hv" onClick="add();">Add</div></div>
                    </form>
                </div>
                <div id="b-side" class="flx" align="center">
                    <!--<div id="b-in">-->
                    <!--    <p>For Claflin<br>-By Claflin</p>-->
                    <!--</div>-->
                    <div>
                        <img src="img/claflin.png">
                        <h2>Claflin International Students</h2>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript" src="js/new.js"></script>
</html>