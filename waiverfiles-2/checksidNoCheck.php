<?php

// load configuration data from waiver_config.ini
$ini_array = parse_ini_file("waiver_config.ini");
$studentFileToWrite  = $ini_array["studentAnswers"];
$questionFolder = $ini_array["questionFolder"];
$totalNoOfQuestions = $ini_array["totalNoOfQuestions"];
$totalNoOfCodeQuestions = $ini_array["totalNoOfCodeQuestions"];
$subFolder = $ini_array["subFolder"];

// GET DATA FROM THE WEB FORM
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$email = $_POST["email"];
$sid   = $_POST["sid"];

// FUN STUFF - DIFFERENT HELLO PICTURE
$rand = rand(1,7);

$studentFile = $studentFileToWrite.$lname."_".$fname."_".$sid.".txt";

/* CHECK IF OKAY TO TAKE QUIZ */
$okayTest = 0;
$usedTest = 0;
$returnValue = 0;

$sid = trim($sid);

/*
try {
	$okayIDfile = fopen("okayStudentIDs.txt", "r");
	while (!feof($okayIDfile)) {
		$testid = trim(fgets($okayIDfile));
		if ($DEBUG) {
			print("<ul><li>From okayFile checking id = ".$testid."</li>");
			print("<li>Comparing *** COMPARE TO SIDs id = ".$sid."</li></ul>");
		}
		if (strcmp($testid, $sid) == 0) {
			if ($DEBUG) {
				print("<ul><li> String compare of testid and sid <h1>match</h1>.</li></ul>") ;
			}
			$okayTest = 1;
		} 
	}
	fclose($okayIDfile);
	
	$triedIDfile = fopen("alreadyUsedStudentIDs.txt", "r");	
	if ($DEBUG) {
		print("<li>Checking already used ID file: ".$triedIDfile."</li>");
		}
	while (!feof($triedIDfile)) {
		$testid = trim(fgets($triedIDfile));
		if ($DEBUG) {
			print("<ul><li>Data from already-used ID file: ".$testid."</li>");
			print("<li>Comparing to submitted sid: ".$sid."</li></ul>");
		}	
		if (strcmp($testid, $sid) == 0) {
			if ($DEBUG) {
				print("<ul><li>String compare of submitted SID and file data match.  Cannot take exam.</li></ul>");
			}
			$usedTest = 1;
		}
	}
	fclose($triedIDfile);
	
	// UPDATE THE SUBMITTED IDS - no retakes
	file_put_contents("alreadyUsedStudentIDs.txt", $sid."\n", FILE_APPEND | LOCK_EX);
	
	// THE KEY HERE ...
	if ($okayTest == 1 && $usedTest == 0) {
		$returnValue = 1;
	}
	
} catch(Exception $e) {
	print("Sorry, the ID files may not be available.");
	print("Error message ".$e->getMessage());
}

*/
$returnValue = 1;
// IF OKAY TO TAKE WAIVER ... 
if ($returnValue == 1) {
	file_put_contents($studentFile, $lname." ".$fname." ".$sid." ".$email."\n", FILE_APPEND | LOCK_EX );
	// randomize the question numbers and save them in line 1 of this file.
	$numbers = range(1, $ini_array["totalNoOfQuestions"], 1);
	shuffle($numbers);
	$temp = implode(",",$numbers);
	file_put_contents($studentFile, $temp."\n", FILE_APPEND | LOCK_EX );
	print('<!DOCTYPE html><html lang="en"> <head>   <title>start waiver.</title>   <meta charset="utf-8">   <meta name="viewport" content="width=device-width, initial-scale=1">   <link rel="stylesheet" type="text/css" href="finalcss.css"> </head> <body> <img width="200wv" src="images/berkeleyischool-logo.svg" /><br /><p><i>Welcome, ');
	print($fname.':</i>');
	print("<p>Each of these has 
	a 60-second timer.  After the multiple choice questions the rest of the waiver exam consists 
	of code submissions.");
	print("<table><tr><td width=\"15%\">");
	echo ('<img height="70px" src="images/waiving'.$rand.'.png"/>');
	print("</td><td><i>Good luck!</i><br /><font size='-2' font color=\"#cddaff\">	¡La mejor de las suertes! &bull; 好好儿考! &bull;   शुभकामनाएं! &bull;   Bon courage! &bull;   Chúc bạn may mắn! &bull;  !حظ جيد بالامتحان، سوف تنجح  </td></tr></table>");

	echo '
	<form method="post" action="f3b.php">
	<input type="hidden" name="qno" id="qno" value="0">
	<input type="hidden" name="studentFile" id="studentFile" value="'.$studentFile.'">
	<input type="hidden" name="sid" id="sid" value="'.$sid.'">
	<div width="400px">
	When you&rsquo;re ready to start, <input type="submit" name="submit" value="Let&rsquo;s go.">
	</div>
	</form>
	<hr />
	<div id="footer">June 25, 2020.  page generated <span id="datetimestamp"></span></div>
	<script>document.getElementById("datetimestamp").innerHTML = new Date();</script></body></html>';
} else {
	echo "<script>window.location.replace('nogood.html');</script>";
}

?>