<?php
// load configuration data from waiver_config.ini
$ini_array = parse_ini_file("waiver_config.ini");
$studentFileToWrite  = $ini_array["studentAnswers"];
$questionFolder = $ini_array["questionFolder"];
$totalNoOfQuestions = $ini_array["totalNoOfQuestions"];

/* ***************************************************** 
This script is called recursively.  After a successful logging in, the 
qno (question no) field is set to 0. So if the value is 0 don't collect anything 
just present the first question.  The value of qno is incremented and weirdly 
reconverted back to an int in order to increment the value, e.g., qno = 1.
Meanwhle ... the total # of questions (from the ini file) were shuffled and 
stored on line 1 (zero-indexing, so line 2 otherwise) of the student's own 
file.  That file is named lastname_firstname_sid.txt.  Line 1 looks like
3, 4, 1, 5, 2 ... and so on.  These values are the order in which the questions are pre-
sented; and the value is concatenated to "q"+value+".txt" to create q3.txt. 
Question files reside in the folder identified in the ini file as "questionFolder".
--- // FIRST CAPTURE THE STUDENT RESPONSE
// SAVE IT
// THEN INCREMENT the QNO FOR THE # in the QUESTION ARRAY, e.g., 
// qno = 1, 2, 3, 4
// but the order of the values in array might be 
// arrayOfQuestionNumbers[3, 2, 4, 1]

*********************************************************** */
/* vars to catch */
$qno = (int)$_POST["qno"];
$studentFile = $_POST["studentFile"];  // INDIVIDUAL STUDENT'S FILE
$sid = $_POST["sid"];

// PRINT HTML HEADER
echo file_get_contents("header.txt");

/* **********************    CAPTURE RESPONSE 
                             SAVE TO STUDENT'S OWN FILE ********************* */

if ($qno != "0") {  // weird but everying on the net is a string
	$answerForQuestionNo = $_POST["answerForQuestionNo"];
	$answerToSave = $_POST[$answerForQuestionNo];
	#print("<br />answerForQuestionNo - ".$answerForQuestionNo); // e.g., answer for q4
	# echo "<font color='violet'>".$answerToSave.'</font><br />';
	// file_put_contents($studentFile, $answerToSave."\n", FILE_APPEND | LOCK_EX );
	// ORIGINAL ABOVE USED \n - REPLACED TO HAVE JUST COMMA ... 
	file_put_contents($studentFile, $answerToSave.",", FILE_APPEND | LOCK_EX );
	// SAVE THAT STUDENT'S ANSWERS TO HIS/HER OWN FILE.
}
// *********************** END CAPTURE RESPONSE ***************************** */

/* IF THE value in qno <= Total Number of Questions from ini file 
   e.g., $totalNoOfQuestions = $ini_array["totalNoOfQuestions"]
   then ask the question.
   ELSE having stored the student response above, go to the next part of the quiz.
*/
// EACH STUDENT GETS A RANDOMIZED SET OF QUESTIONS TO ANSWER.
// STORED IN THE $studentFile for that person - on line 1 (with zero indexing)

	$lines = file($studentFile);
	$arrayOfQuestionNumbers = trim($lines[1]);

	$pieces = explode(",",$arrayOfQuestionNumbers);
	
	
	
	#print("<h1>line 60: ". $studentFile. "</h1>");
	
	
	
(int)$qno = (int)$qno + 1;
if ((int)$qno < count($pieces)) { /* }(int)$totalNoOfQuestions-1 ) { */
#	(int)$qno = (int)$qno + 1;
#	print("[PIECES: ".count($pieces)." and qno NOW <u>".$qno."</u>]<font size='-2'>Question ".$qno. " of ".$totalNoOfQuestions."</font>");
	$lines = file($studentFile);
	$arrayOfQuestionNumbers = trim($lines[1]);

	print('
	<form method="post" name="myForm" id="myForm" action="buildQuestions.php">
	<input type="hidden" name="studentFile" id="studentFile" value="'.$studentFile.'">');
	echo file_get_contents( $questionFolder."q".$pieces[$qno].".txt" );
	// PREPARE FOR SENDING RESPONSE - increment to get next question.
	echo '<input type="hidden" name="qno" id="qno" value="'.$qno.'">
	<input type="hidden" name="answerForQuestionNo" id="answerForQuestionNo" value="q'.$pieces[$qno].'">
	<input type="hidden" name="sid" id="sid" value="'.$sid.'">
	<input type="hidden" name="studentFile" id="studentFile" value="'.$studentFile.'">
	<input type="submit" id="submitbutton" name="submitbutton" value="Send my answer."></form>';
	echo file_get_contents("timer.txt");

}
else {
	// FUN STUFF - DIFFERENT HELLO PICTURE
	$rand = rand(1,7);
	print("<p>This concludes the multiple choice section.</p>
	<p>Next we&rsquo;ll start the code completion samples.  Here you&rsquo;ll be asked 
	a variety of coding questions. <img style='float:right' height=\"125px\" src=\"images/computerguy".$rand.".png\"/>
	The questions here are <i>not timed</i>; be sure to 
	read the directions carefully and review your code before submitting.  Use spaces, 
	not tabs for indenting.</p>
	
	<p>Review your code very carefully.  When you're ready, you'll press the submit button.  The submission is 
	executed using Python 3.x</p>");
	#<p>sid = ".$sid." and studentFile = ".$studentFile. "</p>
	print("<form method=\"post\" action=\"buildCodeQuestions.php\">  <!-- changed from f.php to buildCodeQuestions.php -->
	<input type=\"hidden\" name=\"sid\" id=\"sid\" value='".$sid."'>
	<input type=\"hidden\" name=\"studentFile\" id=\"studentFile\" value='".$studentFile."'>
	<input type=\"submit\" name=\"submit\" value=\"Let&rsquo;s begin.\"></form>");
}
	echo file_get_contents("footer.txt");
?>