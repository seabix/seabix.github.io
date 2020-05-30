<?php

/* A note to the reader. PHP acts very weird at times.  Had a statement like x = 0 ... 
if (x > 0) do something, but no evaluation would work.  tested type, etc., and still 
crazy stuff.  same is the case here with the true and false values.  nope.  wtf?
so used 0|1 and that's okay (for now!)  hate writing poor or amateurish code but don't 
see any way around it here. -gb  May 30, 2020 */
$DEBUG = false;
// load configuration data from waiver_config.ini
$ini_array = parse_ini_file("waiver_config.ini");
$studentFileToWrite  = $ini_array["studentAnswers"];
$questionFolder = $ini_array["questionFolder"];
$totalNoOfQuestions = $ini_array["totalNoOfQuestions"];
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
	#print("<h1>VALUES: okayTest ".$okayTest." usedTest = ".$usedTest." * RETURN VALUE = ".$returnValue."</h1>");
	
} catch(Exception $e) {
	print("Sorry, the ID files may not be available.");
	print("Error message ".$e->getMessage());
}


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
	print("<p>The waiver consists of randomized multiple choice questions and code-completion questions.  Multiple choice questions are timed (60 seconds).  If the timer expires the site will automatically advance to next question.  <br />Code-completion questions are not timed - read the instructions carefully.</p>");
	print("<p>Once you start, you're commited to finishing the waiver in one session.  Do <u>not</u> attempt to revisit a question (using the back or history button) else your waiver is void.</p>");
	print("<table>
		<tr><td width=\"15%\">");
			echo ('<img height="70px" src="images/waiving'.$rand.'.png"/>');
	print("</td>
			<td><i>Good luck!</i><br /><font size='-2' font color=\"#cddaff\">
			¡La mejor de las suertes! &bull; 好好儿考! &bull;   शुभकामनाएं! &bull;   Bon courage! &bull;   Chúc bạn may mắn! &bull;  !حظ جيد بالامتحان، سوف تنجح  
		</td></tr></table>");

	echo '
	<form method="post" action="buildQuestions.php">
	<input type="hidden" name="qno" id="qno" value="0">
	<input type="hidden" name="studentFile" id="studentFile" value="'.$studentFile.'">
	<input type="hidden" name="sid" id="sid" value="'.$sid.'">
	<div width="400px">
	When you&rsquo;re ready to start, <input type="submit" name="submit" value="Let&rsquo;s go.">
	</div>
	</form>
	<hr />
	<div id="footer">as of may 25, 2020.  page generated <span id="datetimestamp"></span></div>
	<script>document.getElementById("datetimestamp").innerHTML = new Date();</script></body></html>';
} else {
	echo "<script>window.location.replace('nogood.html');</script>";
}

?>