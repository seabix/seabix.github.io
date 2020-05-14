<?php

	/* GET THE FILE NAME CONSISTENTLY BY READING THE COOKIE */
	define("USERNAMEVALUE", 0);
	define("EMAILVALUE", 1);
	define("SIDVALUE", 2);
	/* get the cookie value */
	$x = $_COOKIE["name"];
	$studentFileName = "studentcode/".$x.".txt";

	$totalNoOfQuestions = 33;
	$lname = $_POST["ln"];
	$lname = $_POST["fn"];
	$sid = $_POST["sid"];
	$email = $_POST["email"];
	$qno = $_POST["qno"];
	$isLogin = $_POST["isLogin"];
	$qnovar = $_POST["qnovar"];
	$testquiz = $_POST["testquiz"];
if ($isLogin == 't') {
	/* added the likeTheQuestion option for testing whether people like the question */
	$tq = $_POST["noOfQuestions"];
	$email = $_POST["email"];
	#echo "<h1>".$lname." fname = ".$fname." ID ".$sid." ".$email."</h1>";
	$person = "studentcode/".$lname."-".$fname."-".$sid.".txt";
	#echo "Debug: ".$lname." ".$fname." ".$sid." ".$qno." ".$person;
} else {
	$qno = $_POST["qno"];
	$qnovar = $_POST["qnovar"];
	$currentAnswer = $_POST[$qnovar];
	$person = $_POST["person"];
	$testquiz = $_POST["testquiz"];
	#echo $studentFileName;
	#chmod($studentFileName, 0777);
	file_put_contents($studentFileName, $currentAnswer."\n", FILE_APPEND);
}
$qno = $qno+1;
$qnovar = "q".(strval($qno));

// If we've finished all the multiple choice questions, store the answer and 
// switch to the coding section.

if ($qno > $totalNoOfQuestions) {
	echo '<script>window.location.href = "f.php";</script>';
}
else {
	// file name: ta2-new.php
	// updated given new questions
	echo file_get_contents("ta-new-top.txt");
	echo "<font size='-2'><i>Your answers will be stored in ".$studentFileName."</i></font>";
#	echo $fileToUse;
	
	echo '<div style="color: cornflowerblue;">Python Assessment.</div>';
	echo 'Question '.$qno.' of <span id="totalNoOfQuestions"></span>';
	echo '<div id="theQuestionBox"><div id="theQuestionToAsk"></div>';
		
	echo '<form method="post" action="ta2-new.php" id="formid" name="formname">';
	echo '<input type="hidden" name="qnovar" id="qnovar" value="'.$qnovar.'"/>';
	echo '<input type="hidden" name="qno" id="qno" value="'.$qno.'"/>';
	echo '<span id="theQuestionToAsk"></span>';
	
	$questionFile = "codingquestions/q".$qno.".txt";
	
	echo file_get_contents($questionFile);
	# <script>loadDoc('.$qno. ');</script>';
	
	echo '<script>document.getElementById("qno").innerHTML = "'.$qno.'";</script>';
	echo '<input type="hidden" name="person" id="person" value="'.$person.'">';
	
	echo file_get_contents("ta-new-bottom.txt");
}
?>