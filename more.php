<?php

require_once 'exec/config.php';
require 'exec/misc.php';

$id = $_GET["id"];
$status = $_GET["status"];

$sli = $con->prepare("SELECT general.pic, general.fname, general.mname, general.lname, general.pname, general.gender, general.dob, general.email, general.phone, location.address, location.ct, provinces.name AS prov, provinces.countries AS cid, location.zip, countries.name AS coun, countries.code FROM general INNER JOIN location ON general.loc = location.id INNER JOIN provinces ON location.prov = provinces.id INNER JOIN countries ON provinces.countries = countries.id AND general.id = ?;");
$sli->execute(array($id));
$r = $sli->fetch();

if($id == null || $r == null || ($status != "A" && $status != "S")){
    header("Location: home");
    exit();
}

session_start();

$zone = $_SESSION['time'];

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Details</title>
        <link href="css/style.css" type="text/css" rel="stylesheet"/>
        <link rel="shortcut icon" href="img/logo.png"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.css" />
    </head>
    <body>
        <div id="header">
            <h1><?php 
        
        if($r["mname"] == "" && $r["pname"] == ""){ 
            $nm = $r["fname"].' '.$r["lname"];
            echo $nm;
	    }else if($r["pname"] == ""){ 
	        $nm = $r["fname"].' '.$r["mname"].' '.$r["lname"];
	        echo $nm;
	    }else if($r["pname"] != "" && $r["mname"] == ""){ 
	        echo $r["fname"].' '.$r["lname"].' <span id="prefe">('.$r["pname"].')</span>';
	        $nm = $r["fname"].' '.$r["lname"].' ('.$r["pname"].')';
	    }else {
	        echo $r["fname"].' '.$r["mname"].' '.$r["lname"].' <span id="prefe">('.$r["pname"].')</span>';
	        $nm = $r["fname"].' '.$r["mname"].' '.$r["lname"].' ('.$r["pname"].')';
	    }
        
        ?></h1>
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
                <li class="tops"><a href="new">New</a></li>
            </ul>
        </nav>
        
        <div id="res">
            <div id="con">
                <div class="box">
                    <h2>Personal Information</h2>
            <?php
            
            $r["gender"] = $r["gender"] == "M" ? "Male" : "Female";
            
            echo '<table class="mre">
                <col style="width:20%">
                <col style="width:40%">
                <col style="width:40%">
                <tr>
                    <th>Type</th>
                    <th>Data</th>
                    <th>Update</th>
                </tr>
                <tr>
                    <td class="tp">Profile Image</td>
                    <td class="withtop">';
                    if($r["pic"] != "") echo '<a href="img/profile/'.$r["pic"].'" data-fancybox data-caption="'.$nm.'"><img src="img/profile/'.$r["pic"].'" width="100%"></a><img onClick="delImg(\'pic\');" class="top-right" src="img/delete.png" width="25px">';
                    else echo '<a href="img/person.png" data-fancybox data-caption="'.$nm.'"><img src="img/person.png" width="80%"></a>';
                    echo '</td>
                    <td align="center">
                        <form id="uPic" method="POST" action="exec/uimg.php">
                        <input type="file" accept="image/*" name="profile" id="profile" onchange="$(\'#uPic\').trigger(\'submit\');">
                        <div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuein="0" aria-valuemax="100"></div></form>
                        <div id="loader-icon" style="display: none"><img src="img/wedges.gif"></div>
                        <div class="mainbar" id="pmainbar">
                            <div id="myBar" class="innerbar" style="width:0%">0%</div>
                        </div></td>
                </tr>
                <tr>
                    <td class="tp">First name</td>
                    <td id="fname">'.$r["fname"].'</td>
                    <td><input id="cfname" type="text" placeholder="Update First name" onkeyup="uP(event, \''.$id.'\', \'fname\', \'First name\', \'1\');"></td>
                </tr>
                <tr>
                    <td class="tp">Middle name</td>
                    <td id="mname">'.$r["mname"].'</td>
                    <td><input id="cmname" type="text" placeholder="Update Middle name" onkeyup="uP(event, \''.$id.'\', \'mname\', \'Middle Name\', \'1\');"></td>
                </tr>
                <tr>
                    <td class="tp">Last name</td>
                    <td id="lname">'.$r["lname"].'</td>
                    <td><input id="clname" type="text" placeholder="Update Last name" onkeyup="uP(event, \''.$id.'\', \'lname\', \'Last Name\', \'1\');"></td>
                </tr>
                <tr>
                    <td class="tp">Preferred name</td>
                    <td id="pname">'.$r["pname"].'</td>
                    <td><input id="cpname" type="text" placeholder="Update Preferred name" onkeyup="uP(event, \''.$id.'\', \'pname\', \'Preferred Name\', \'1\');"></td>
                </tr>
                <tr>
                    <td class="tp">Gender</td>
                    <td id="gender">'.$r["gender"].'</td>
                    <td><select id="cgender" onchange="cData(\''.$id.'\', \'gender\', \'1\')">
            			    <option value="Male">Male</option>
            			    <option value="Female">Female</option>
        			    </select></td>
                </tr>
                <tr>
                    <td class="tp">Date of Birth</td>
                    <td id="dob">'.readDate($r["dob"]).'</td>
                    <td><input id="cdob" type="date" onchange="cData(\''.$id.'\', \'dob\', \'1\')"></td>
                </tr>
                <tr>
                    <td class="tp">Phone Number</td>
                    <td id="phone">'.$r["phone"].'</td>
                    <td><input id="cphone" type="phone" value="(+'.$r["code"].') " placeholder="Update Phone Number" onkeyup="uP(event, \''.$id.'\', \'phone\', \'Phone Number\', \'1\');"></td>
                </tr>
                <tr>
                    <td class="tp">E-mail</td>
                    <td id="email">'.$r["email"].'</td>
                    <td><input id="cemail" type="email" placeholder="Update E-mail Address" onkeyup="uP(event, \''.$id.'\', \'email\', \'Email Address\', \'1\');"></td>
                </tr>
                <tr>
                    <td class="tp">Address</td>
                    <td id="address">'.$r["address"].'</td>
                    <td><input id="caddress" type="text" placeholder="Update Address" onkeyup="uP(event, \''.$id.'\', \'address\', \'Address\', \'1\');"></td>
                </tr>
                <tr>
                    <td class="tp">City/Town</td>
                    <td id="ct">'.$r["ct"].'</td>
                    <td><div class="autocomplete"><input id="cct" type="text" placeholder="Update City/Town" onkeyup="uP(event, \''.$id.'\', \'ct\', \'City/Town\', \'1\');"></div></td>
                </tr>
                <tr>
                    <td class="tp">ZIP Code</td>
                    <td id="zip">'.$r["zip"].'</td>
                    <td><input id="czip" type="number" onkeyup="uP(event, \''.$id.'\', \'zip\', \'ZIP Code\', \'1\');"></td>
                </tr>
                <tr>
                    <td class="tp">State/Province</td>
                    <td id="prov">'.$r["prov"].'</td>
                    <!--<td><div class="autocomplete"><input id="cprovince" type="text" placeholder="Update Province/State" onkeyup="uP(event, \''.$id.'\', \'province\', \'Province/State\', \'1\');"></div></td>-->
                    <td><select id="cprov" onchange="cData(\''.$id.'\', \'prov\', \'1\')">';
                    
                $fp = $con->prepare("SELECT id, name FROM provinces WHERE countries = ? GROUP BY name ORDER BY name");
                $fp->execute(array($r["cid"]));
                while($ac = $fp->fetch()){
                    if($ac["name"] == $r["prov"]) echo '
                    <option selected ';
                    else echo '
                    <option ';
                    echo 'value="'.$ac["id"].'">'.$ac["name"].'</option>';
                    ++$i;
                }
                
                echo '</select></td>
                </tr>
                <tr>
                    <td class="tp">Country</td>
                    <td>'.$r["coun"].'</td>
                    <td><select id="ccountry" onchange="cData(\''.$id.'\', \'country\', \'1\');">';
                    
                    $fcouns = $con->query("SELECT id, name, code FROM countries ORDER BY name");
                    
                    while ($ct = $fcouns->fetch()){
    	                if($ct["name"] == $r["coun"]) echo '
    	                <option selected ';
    	                else echo '
    	                <option ';
    	                echo 'value="'.$ct["id"].'">'.$ct["name"].' (+'.$ct["code"].')</option>';
    	            }
                    
                    echo '</select></td>
                </tr>
            </table><br>
            <h2>Family Information</h2>
            <table>
                <col style="width:20%">
                <col style="width:60%">
                <col style="width:20%">
                <tr>
                    <th>Relation</th>
                    <th>Name</th>
                    <th>Option</th>
                </tr>';
            
            $fparents = $con->prepare("SELECT id, rel, title, fname, mname, lname FROM family WHERE gid = ?;");
            $fparents->execute(array($id));
            $funm = "";
            while($x = $fparents->fetch()){
                echo '<tr id="REL_'.$x["id"].'"><td class="tp">';
                switch($x["rel"]){
                    case 0: echo 'Mother';
                        break;
                    case 1: echo "Father";
                        break;
                    case 2: echo "Guardian";
                        break;
                    case 3: echo "Brother";
                        break;
                    case 4: echo "Sister";
                        break;
                    case 5: echo "Son";
                        break;
                    case 6: echo "Daughter";
                        break;
                    case 7: echo "Other";
                        break;
                    default: echo "Unknown";
                }
                echo '</td><td>';
                switch($x["title"]){
                    case 0: $funm = "Dr. ";
                        break;
                    case 1: $funm = "Miss ";
                        break;
                    case 2: $funm = "Mr. ";
                        break;
                    case 3: $funm = "Mrs. ";
                        break;
                    case 4: $funm = "Ms. ";
                        break;
                    case 5: $funm = "R. ";
                        break;
                    case 6: $funm = "Rev. ";
                        break;
                }
                $funm .= $x["fname"].' ';
                $funm .= $x["mname"] == "" ? $x["lname"] : $x["mname"].' '.$x["lname"];
                echo $funm.'</td><td><div class="ml b-form" onClick="expand(\''.$x["id"].'\', \''.$funm.'\');">Expand</div></td></tr>';
            }
            
            echo '
                <tr>
                    <td class="tp">New</td>
                    <td><b>Relative</b></td>
                    <td><div onClick="newRelative(\'Y\');" class="al b-form">Add</div></d>
                </tr>
            </table>
            </div>
            <div class="extra"></div>
            <div class="box">
                <h2>Current Status</h2>
                <table class="mre">
                    <col style="width:20%">
                    <col style="width:40%">
                    <col style="width:40%">
                    <tr>
                        <th>Type</th>
                        <th>Data</th>
                        <th>Update</th>
                    </tr>
                    <tr>';
                    
            $cs = null;
                
            $yrs = "";
            for($o = 2000; $o <= 2021; $o++){
                $yrs .= '
                <option value="'.$o.'">'.$o.'</option>';
            }
            
            $allowExtra = false;
            
            if($status == "S"){
                $cs = $con->prepare("SELECT enrolled.stdid, enrolled.enro, enrolled.phone, enrolled.email, enrolled.clas, enrolled.whn, enrolled.yr FROM enrolled WHERE enrolled.uid = ? ORDER BY enrolled.enro DESC, enrolled.id DESC");
                $cs->execute(array($id));
                $csc = $cs->fetch();
                
                if($cs->rowCount() == 0){
                    echo '
                        <td class="tp">Status</td>
                        <td>Not Admitted</td>
                        <td><select onchange="location.replace(\'more?id='.$id.'&status=A\');"><option>Student</option><option>Applicant</option></select></td>
                    </tr></table><br><h2>Admit</h2><div id="adms" class="msg"></div><table class="nwe">
                        <col width="20%">
                        <col width="40%">
                        <col width="40%">
                    <tr>
                        <td class="tp">Student ID</td>
                        <td><input id="nstid" type="number"></td>
                        <td>Required</td>
                    </tr>
                    <tr>
                        <td class="tp">Degree</td>
                        <td><select id="ndegree">
                            <option selected disabled>Select Degree Program</option>
                            <option value="U">Undergraduate</option>
                            <option value="G">Graduate</option>
                            </select></td>
                        <td>Required</td>
                    </tr>
                    <tr>
                        <td class="tp">Season</td>
                        <td><select id="nseason">
                            <option selected disabled>Select Academic Season</option>
                            <option value="0">Spring</option><option value="1">Summer</option>
                            <option value="2">Fall</option></select></td>
                        <td>Required</td>
                    </tr>
                    <tr><td class="tp">Year</td><td><select id="nyear"><option selected disabled>--</option>';
                    
                    echo $yrs;
                    echo '</select></td><td>Required</td></tr>
                    </table>
                    <div align="center"><div class="btn-out btn-hv ftop" onClick="admit();">Admit</div></div>';
                }else if($cs->rowCount() == 1){
                    
                    echo '
                        <td class="tp">Status</td>
                        <td>Student</td>
                        <td><select onchange="location.replace(\'more?id='.$id.'&status=A\');"><option>Student</option><option>Applicant</option></select></td>
                    </tr>
                    <tr>
            			<td class="tp">Degree</td>
            			<td id="clas">';
            			
            		echo $csc["clas"] == "U" ? "Undergraduate" : "Graduate";
            		echo '</td><td><select id="cclas" onchange="cData(\''.$id.'.'.$csc["stdid"].'\', \'clas\', \'2\')">
                			    <option ';
                    echo $csc["clas"] == "U" ? 'selected value="U">Undergraduate</option><option value="G">Graduate' : 'value="U">Undergraduate</option><option selected value="G">Graduate';
            		echo '</option></select></td></tr>
                    <tr>
                        <td class="tp">Season</td>
                        <td>';
                        switch($csc["whn"]){
                            case 0: echo 'Spring';
                                break;
                            case 1: echo 'Summer';
                                break;
                            case 2: echo 'Fall';
                                break;
                        }
                        echo '</td>
                        <td><select id="cwhn" onchange="cData(\''.$id.'.'.$csc["stdid"].'\', \'whn\', \'2\');">
                            <option selected disabled>Select Academic Season</option>
                            <option value="0">Spring</option><option value="1">Summer</option>
                            <option value="2">Fall</option></select></td>
                    </tr>
                    <tr>
                        <td class="tp">Year</td>
                        <td>'.$csc["yr"].'</td>
                        <td><select id="cyr" onchange="cData(\''.$id.'.'.$csc["stdid"].'\', \'yr\', \'2\');"><option selected disabled>--</option>'.$yrs.'</select></td>
                    </tr>
                    <tr>
                        <td class="tp">Student ID</td>
                        <td id="stdid">'.$csc["stdid"].'</td>
                        <td><input id="cstdid" type="number" onkeyup="uP(event, \''.$id.'.'.$csc["stdid"].'\', \'stdid\', \'Student ID\', \'2\');"></td>
                    </tr>
                    <tr>
                        <td class="tp">Enrolled</td>
                        <td id="enro">';
                        
                    echo $csc["enro"] == "Y" ? "Yes" : "No";
                    
                    echo '</td><td><select id="cenro" onchange="cData(\''.$id.'.'.$csc["stdid"].'\', \'enro\', \'2\')"><option ';
                    
                    echo $csc["enro"] == "Y" ? 'selected value="Yes">Yes</option><option value="No">No' : 'selected value="No">No</option><option value="Yes">Yes';
                    echo '</option>
            		    </select></td></tr>
            	    <tr>
            	        <td class="tp">Phone Number</tp>
            	        <td id="dphone">'.$csc["phone"].'</td>
            	        <td><input id="ccphone" type="phone" onkeyup="uP(event, \''.$id.'.'.$csc["stdid"].'\', \'phone\', \'Student Phone Number\', \'2\');" value="(+1) "></td>
            	    </tr>
            	    <tr>
            	        <td class="tp">E-mail</tp>
            	        <td id="demail">'.$csc["email"].'</td>
            	        <td><input id="ccemail" type="email" onkeyup="uP(event, \''.$id.'.'.$csc["stdid"].'\', \'email\', \'Student E-mail\', \'2\');"></td>
            	    </tr>';
            			   
            	    $fch = $con->prepare("SELECT courses.id, courses.name, courses.type, courses.dg FROM courses WHERE courses.clas = ? ORDER BY courses.name;");
            	    $fch->execute(array($csc["clas"]));
            	    
            	    //FETCH COURSES
            	    $fcs = $con->prepare("SELECT courses.id, courses.name, courses.type, courses.dg FROM stcourse INNER JOIN courses ON stcourse.stid = ? AND stcourse.course = courses.id AND courses.clas = ?;");
            	    $fcs->execute(array($csc["stdid"], $csc["clas"]));
            	    
            	    while($b = $fcs->fetch()){
            	        echo '<tr id="COU_'.$b["id"].'"><td class="tp">';
            	        echo $b["type"] == "J" ? "Major" : "Minor";
            	        echo '</td><td>'.$b["name"];
            	        if($b["dg"] != "") echo ' - '.$b["dg"]; 
            	        echo '</td><td><div class="dl b-form" onClick="adcourse(\''.$csc["stdid"].'\', \''.$b["id"].'\', \'1\');">Delete</div></td></tr>';
            	    }
            	    
            	    echo '<tr><td class="tp">New Course</td><td><select id="ncourse">
            	        <option selected disabled>Please Select A Course</option>';
            	    while($c = $fch->fetch()){
            	        echo '
            	        <option value="'.$c["id"].'">(';
            	        echo $c["type"] == "J" ? "Major" : "Minor";
            	        echo ') '.$c["name"];
            	        echo $c["dg"] == "" ? '</option>' : ' - '.$c["dg"].'</option>';
            	    }
            	    echo '</select></td><td><div class="al b-form" onClick="adcourse(\''.$csc["stdid"].'\', \'\', \'2\');">Add</div></td></tr>';
            	    
                    $allowExtra = true;
                }else{
                    //PROBABLY DOES NOT EXIST
                }
            }else{
                $sltapp = $con->prepare("SELECT type, class, whn, yr, board, course FROM application WHERE gid = ?");
                $sltapp->execute(array($id));
                if($sltapp->rowCount() == 1){
                    $fap = $sltapp->fetch();
                    echo '
                        <td class="tp">Status</td>
                        <td>Applicant</td>
                        <td><select onchange="location.replace(\'more?id='.$id.'&status=S\');"><option>Student</option><option selected>Applicant</option></select></td>
                    </tr>
                    <tr>
                        <td class="tp">Number</td>
                        <td>'.$id.'</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="tp">Applying As</td>
                        <td>';
                    if($fap["type"] == 'F') echo 'Freshman';
                    else if($fap["type"] == 'T') echo 'Transfer';
                    echo '</td>
                        <td><select id="aptype" onchange="aData(\'type\', \'Update Application?\')">
                            <option selected disabled>Please Select Application</option>
                            <option value="F">Freshman</option>
                            <option value="T">Transfer</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td class="tp">Degree</td>
                        <td>';
                    if($fap["class"] == 'U') echo 'Undergraduate';
                    else if($fap["class"] == 'G') echo 'Graduate';
                    echo '</td>
                        <td><select id="apclass" onchange="aData(\'class\', \'Update Degree?\')">
                            <option selected disabled>Please Select Degree</option>
                            <option value="U">Undergraduate</option>
                            <option value="G">Graduate</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td class="tp">Season</td>
                        <td>';
                        switch($fap["whn"]){
                            case '0': echo 'Spring';
                                break;
                            case '1': echo 'Summer';
                                break;
                            case '2': echo 'Fall';
                                break;
                        }
                        echo '</td>
                        <td><select id="apwhn" onchange="aData(\'whn\', \'Update Season?\')">
                                <option selected disabled>Select Academic Season</option>
                                <option value="0">Spring</option><option value="1">Summer</option>
                                <option value="2">Fall</option></select></td>
                    </tr>
                    <tr>
                        <td class="tp">Year</td>
                        <td>'.$fap["yr"].'</td>
                        <td><select id="apyr" onchange="aData(\'yr\', \'Update Year?\')"><option selected disabled>--</option>'.$yrs.'</select></td>
                    </tr>
                    <tr>
                        <td class="tp">Course</td>
                        <td>';
                    $curs = "";
                    $fmc = $con->prepare("SELECT id, name FROM courses WHERE type = 'J' AND clas = ? ORDER BY name;");
                    $fmc->execute(array($fap["class"]));
                    while($cm = $fmc->fetch()){
                        if($fap["course"] == $cm["id"]) echo $cm["name"];
                        $curs .= '<option value="'.$cm["id"].'">'.$cm["name"].'</option>';
                    }
                        echo '</td>
                        <td><select id="apcourse" onchange="aData(\'course\', \'Update Course?\')"><option selected disabled>Please Select a Course</option>'.$curs.'</select></td>
                    </tr>
                    <tr>
                        <td class="tp">Board</td>
                        <td>';
                        if($fap["board"] == 'Y') echo 'Yes';
                        else if($fap["board"] == 'N') echo 'No';
                        echo '</td>
                        <td><select id="apboard" onchange="aData(\'board\', \'Update Boarding?\')"><option selected disabled>--</option><option value="Y">Yes</option><option value="N">No</option></select></td>
                    </tr>';
                    
                    $sltSch = $con->prepare('SELECT id, type, name FROM school WHERE gid = ?;');
                    $sltSch->execute(array($id));
                    
                    while($v = $sltSch->fetch()){
                        echo '<tr id="SCH_'.$v["id"].'"><td class="tp">';
                        switch($v["type"]){
                            case "H": echo 'High School';
                                break;
                            case "U": echo 'University';
                                break;
                            default: echo 'School';
                        }
                        echo '</td><td>'.$v["name"].'</td><td><div class="ml b-form" onClick="expandSchool(\''.$v["id"].'\');">Expand</div></td></tr>';
                    }
                    
                    echo '<tr>
        	            <td class="tp">New</td>
        	            <td><b>School</b></td>
        	            <td><div onClick="newSchool();" class="al b-form">Add</div></td>
        	        </tr>';
                    
                    //TRANSCRIPT, PASSPORT, FAMILY, COURSE, DOCUMENTS, PRE-SCHOOL, TESTS
                }else{
                    //MORE THAN 1 APPLICATION
                }
                
                $allowExtra = true;
            }
            
            if($allowExtra){
                
                $freps = $con->prepare("SELECT id, role, title, fname, mname, lname FROM rep WHERE gid = ?;");
        	    $freps->execute(array($id));
        	    while($rp = $freps->fetch()){
        	        echo '<tr id="REP_'.$rp["id"].'"><td class="tp">';
        	        switch($rp["role"]){
        	            case 'C': echo 'Counselor';
        	                break;
        	            case 'T': echo 'Teacher';
        	                break;
        	            case 'O': echo 'Other';
        	                break;
        	            default: echo 'Unknown';
        	        }
        	        echo '</td><td>';
        	        switch($rp["title"]){
                        case 0: $funm = "Dr. ";
                            break;
                        case 1: $funm = "Miss ";
                            break;
                        case 2: $funm = "Mr. ";
                            break;
                        case 3: $funm = "Mrs. ";
                            break;
                        case 4: $funm = "Ms. ";
                            break;
                        case 5: $funm = "R. ";
                            break;
                        case 6: $funm = "Rev. ";
                            break;
                    }
                    $funm .= $rp["fname"].' ';
                    $funm .= $rp["mname"] == "" ? $rp["lname"] : $rp["mname"].' '.$rp["lname"];
        	        echo $funm.'</td><td><div class="ml b-form" onClick="expandRep(\''.$rp["id"].'\');">Expand</div></td></tr>';
        	    }
        	    
                echo '<tr id="N_R">
        	            <td class="tp">New</td>
        	            <td><b>Rep</b></td>
        	            <td><div onClick="newRep();" class="al b-form">Add</div></td>
        	        </tr>';
        	        
        	    $ffiles = $con->prepare("SELECT documents.id, documents.name, (SELECT COUNT(1) FROM docs WHERE docs.did = documents.id) AS num FROM documents WHERE documents.gid = ? AND type = ?;");
        	    $ffiles->execute(array($id, $status));
        	    while($fil = $ffiles->fetch()){
        	        echo '<tr id="DOC_'.$fil["id"].'"><td class="tp">'.$fil["name"].'</td><td>'.$fil["num"].' ';
        	        echo $fil["num"] == 1 ? 'File' : 'Files';
        	        echo '</td><td><div class="ml b-form" onClick="expandFile(\''.$fil["id"].'\');">Expand</div></td></tr>';
        	    }
        	        echo '<tr id="N_F">
        	            <td class="tp">New</td>
        	            <td><b>File</b></td>
        	            <td><div onClick="newDocument();" class="al b-form">Add</div></td>
        	        </tr>
        	    </table>';
            }
            
            if($cs != null){
                if($cs->rowCount() == 0){
                    //USER ISN'T YET ACCEPTED
                }else if($cs->rowCount() == 1){
                    //
                }else{
                    //USER HAS ENROLLED MULTIPLE TIMES
                }
            }
            
            ?>
                    
                    <div id="expand"></div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript" src="js/more.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.js"></script>
    <script type="text/javascript">
        
        var main_id = '<?php echo $id ?>';
        var type = '<?php echo $status ?>';
        var provinces = [<?php 
            $fp = $con->prepare("SELECT name FROM provinces WHERE countries = ? GROUP BY name ORDER BY name");
            $fp->execute(array($r["cid"]));
            $nm = $fp->rowCount();
            $i = 1;
            while($ac = $fp->fetch()){
                echo '"'.$ac["name"].'"';
                if($i != $nm) echo ", ";
                ++$i;
            }
        ?>];
        
        var cities = [<?php
            $fc = $con->prepare("SELECT location.ct FROM location INNER JOIN provinces ON location.prov = provinces.id INNER JOIN countries ON provinces.countries = countries.id AND countries.id = ? GROUP BY location.ct ORDER BY location.ct;");
            $fc->execute(array($r["cid"]));
            $nm = $fc->rowCount();
            $i = 1;
            while($cs = $fc->fetch()){
                echo '"'.$cs["ct"].'"';
                if($i != $nm) echo ", ";
                ++$i;
            }
        ?>];
        
        // autocomplete(document.getElementById("cprovince"), provinces);
        autocomplete(document.getElementById("cct"), cities);
        
        $("#uPic").submit(function(e){
            e.preventDefault();
            if($("#profile").val() && confirm("Update Profile Image?")){
                var formData = new FormData($(this)[0]);
                formData.append('id', '<?php echo $id; ?>');
                $("#loader-icon").show();
                $("#pmainbar").show();
                $.ajax({
                    url: "exec/uimg.php",
                    type: "POST",
                    data: formData,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = Math.round(evt.loaded / evt.total * 10000) / 100;
                                $("#myBar").width(percentComplete+"%");
                                $("#myBar").text(percentComplete+' %');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (msg) {
                        if(msg.includes("Successfully")) location.reload();
                        else {
                            $("#loader-icon").hide();
                            $("#pmainbar").hide();
                            alert(msg);
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        });
    </script>
</html>