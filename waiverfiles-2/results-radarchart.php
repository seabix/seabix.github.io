<?php
/* **************** REVISED VERSION ****************** */
/* both answer key and student answers must be stored in a single line */
/* updated because of randomization - may 29, 2020 */

$ini_array = parse_ini_file("waiver_config.ini");
$studentFileToWrite  = $ini_array["studentAnswers"];
$questionFolder = $ini_array["questionFolder"];
$totalNoOfQuestions = $ini_array["totalNoOfQuestions"];
$answerKey = $ini_array["answerKey"];
$subFolder = $ini_array["subFolder"];
$emailTo = $ini_array["emailTo"];

// GET THE STUDENT'S INFO
$studentFile = $_POST["studentFile"];
$sid = $_POST["sid"];

/* read file to get line for array */
# FOR TESTING ONLY: 

$answerKey = "answerKey.txt";

/* ************************************************* */
$axisa = 0;		# Git and Github (1 question)  #1
$axisb = 0;		# Command line and General Computing (3 qs), 2,3,4, 10
$axisc = 0;		# Python Language and Syntax (17 qs), 5, 8, 13, 14  16, 18 19, 20, 23, 24, 25, 26, 28,2930, 31, 32, 33
$axisd = 0;		# Algorithm Analysis (4 questions) , 11 , 15 17 27, 
				# UPDATED MAY 13 - add the coding equestions
$axise = 0;		# Object Oriented Programming (3 qs + more in coding),  6, 7, 12 
$axisf = 0;		# Data Analysis (2 qs + more in coding). <br />  9, 21, 22
$axisg = 0;		# Part 2 questions:  34-38
$axish = 0;		# part 3 questions = 39,40,41,42,43,44 and final 45machine learning?  whatever

$wrong = 0;
$right = 0;

/* ARRAYS FOR THE QUESTION #s TO COMPARE FOR AXIS CATEGORIES */
$git = array(0);
$cmdLine = array(1, 2, 3, 9);
$language = array(4,7,12,13,15,17,18,19,22,23,24,25,27,28,29,30,31,32);
$algorithms = array(10,14,16,25);
$oop = array(5,6,11, 50);
$dataanalysis = array(8,20,21,45, 46,47,48,49);
$part2 = array(33, 34, 35, 36, 37);
$part3 = array(38,39,40,41,42,43,44);

// get the data from the file.

$lines = file($answerKey);
$xanswerKeyTemp = trim($lines[0]);

// get the students' answer key - a single line  [on line 3]
$lines = file($studentFile);
$xrandomizedQuestionNumbers = trim($lines[1]);
$xstudentsAnswers = trim($lines[2]);

$randomizedQuestions = explode(",",$xrandomizedQuestionNumbers);
$studentAnswers = explode(",",$xstudentsAnswers);
$answerKey = explode(",",$xanswerKeyTemp);

// GET THE SCORE FOR THE RUNTIME - alas only in tempruntime.txt

for ($i = 0; $i < count($answerKey); $i++) {
	$randomizedQuestionNo = $randomizedQuestions[$i];

	/* RAW COUNTS */
	if ($answerKey[$randomizedQuestionNo] == $studentAnswers[$randomizedQuestionNo]) {
		$right += 1;
		// find out what category
		if (in_array($i, $git)) {
			$axisa +=1;
		}
		elseif (in_array($i, $cmdLine)) { 
			$axisb += 1;
		} elseif (in_array($i, $language)) { // language
			$axisc += 1;
		} elseif (in_array($i, $algorithms)) { 
			$axisd += 1;
		} elseif (in_array($i, $oop)) { 
			$axise += 1;
		} elseif (in_array($i, $dataanalysis)) { 
			$axisf += 1;
		} elseif (in_array($i, $part2)) { 
			$axisg += 1;
		} elseif (in_array($i, $part3)) { 
			$axish += 1;
		}
	} else {
		$wrong += 1;
	}
}

echo file_get_contents("header.txt");

/* TAILOR THE OUTPUT TO THE STUDENT FOR PRINTING */
// get the data from line 1 (line[0] of the studentFile parameter:
// e.g., Joe Trader 1234 trader@joe.com
$lines = file($studentFile);
$studentInfo = trim($lines[0]);

$getName = explode(" ", $studentInfo);
echo "<h2>Assessment for ".$getName[1]." ".$getName[0]."</h2> (SID: ".$getName[2].")<hr />";
echo "<p>Key: the yellow area is the department performance level. The red area reflects your scores.</p>";
echo "<a href='mailto:".$getName[3]."'>Student Email</a>";
//".  |  Loading data from ".$studentFile;

/* JAVASCRIPT NEEDED FOR MAKING THE RADAR CHART */

/* ARRAYS FOR THE QUESTION #s TO COMPARE FOR AXIS CATEGORIES */
$git = array(0);
$cmdLine = array(1, 2, 3, 9);
$language = array(4,7,12,13,15,17,18,19,22,23,24,25,27,28,29,30,31,32);
$algorithms = array(10,14,16,25);
$oop = array(5,6,11, 50);
$dataanalysis = array(8,20,21,45, 46,47,48,49);
$part2 = array(33, 34, 35, 36, 37);
$part3 = array(38,39,40,41,42,43,44);

/* this is unbelievably stupid but necessary - even tho we SEE the above vars are 
arrays, php says they aren't.  wtf?  so have to hard code instead of getting the count */
$noOfGit = count($git);
$noOfCmdLine = 4;
$noOfLanguage = 18;
$noOfAlgorithnms = 4;
$noOfOOP = 4;
$noOfDataAnalysis = 8;
$noOfPart2 = 5;
$noOfPart3 = 7;

	/* believe it or not, using != 0 does not work. so ... neophyte need to code this way. */
	if ($axisa == 0) { 
	} else {
		$axisa = round(($axisa/$noOfGit),2);
	}

	if ($axisb == 0) { // noOfCmdLine
	} else {
		$axisb = round( ($axisb/$noOfCmdLine), 2);
	}
	if ($axisc == 0) {	
	} else {
		$axisc = round( ($axisc/$noOfLanguage), 2);
	}
	if ($axisd == 0) { 
	} else {
		$axisd = round( ($axisd/$noOfAlgorithms), 2);
	}
	if ($axise == 0) { 
	} else {
		$axise = round( ($axise/$noOfOOP), 2);
	}
	if ($axisf == 0) { 
	} else {
		$axisf = round( ($axisf/$noOfDataAnalysis), 2);
	}
	if ($axisg == 0) {
	} else {
		$axisg = round( ($axisg/$noOfPart2), 2);
	}
	if ($axish == 0) { 
	} else {
		$axish = round( ($axish/$noOfPart3), 2);
	}

echo '
<!-- D3.js -->
<script src="d3.min.js" charset="utf-8"></script>
<style>
	body { font-family: "Open Sans", sans-serif;
		font-size: 11px; font-weight: 300;
		fill: #242424;  text-align: center;
		text-shadow: 0 1px 0 #fff, 1px 0 0 #fff, -1px 0 0 #fff, 0 -1px 0 #fff;
		cursor: default; }
	.legend { font-family: "Raleway", sans-serif; fill: #333333; }
	.tooltip { fill: #333333; }
</style>
<div class="radarChart"></div>

<script src="radarChart.js"></script>
<script>
	var margin = {top: 100, right: 100, bottom: 100, left: 100},
		width = Math.min(700, window.innerWidth - 10) - margin.left - margin.right,
		height = Math.min(width, window.innerHeight - margin.top - margin.bottom - 20);
	var data = [
			  [//student scores
				{axis:"Git and Github",value:'.$axisa.'},
				{axis:"Command line and General Computing",value:'.$axisb.'},
				{axis:"Python Language and; Syntax",value:'.$axisc.'},
				{axis:"Algorithm Analysis",value:'.$axisd.'},
				{axis:"Object Oriented Programming",value:'.$axise.'},
				{axis:"Data Analysis",value:'.$axisf.'},
				{axis:"Part 2 coding",value:'.$axisg.'},
				{axis:"Final coding",value:'.$axish.'}
			  ],
			   [//target scores

 				{axis:"Git and Github",value: 1.00},
 				{axis:"Command line and; General Computing",value: .90},
 				{axis:"Python Language and Syntax",value: .90},
 				{axis:"Algorithm Analysis",value: .80},
 				{axis:"Object Oriented Programming",value: .80},
 				{axis:"Data Analysis",value: .85},
 				{axis:"Part 2 coding",value: .80},
 				{axis:"Final coding",value: 1.00}

 			  ]
			];
	/* draw the chart */
	var color = d3.scale.ordinal()
		.range(["#CC333F","#EDC951","#00A0B0"]);
	var radarChartOptions = {
	  w: width,
	  h: height,
	  margin: margin,
	  maxValue: 1.0,
	  levels: 5,
	  roundStrokes: true,
	  color: color
	};
	//Call function to draw the Radar chart
	RadarChart(".radarChart", data, radarChartOptions);
</script>';

print("<p>Overall Right: ".$right. " Wrong: ".$wrong.".  ");
$lines = file("../CGI-Executables/studentcode/tempruntime.txt");
$score = trim($lines[0]);

print("Score on Solver/Board run [tba] ".$score."</p>");

/* MAIL TO ucb person and student.*/
// if (file_exists("ta-ucb-programManagerEmail.txt")) { 
//	$programMngt = file_get_contents("ta-ucb-programManagerEmail.txt");
if ($emailTo != "") {
	$msg2 = "Good day.  Student ".$fname." ".$lname." email: ".$email." SID: ".$sid.
	" completed an assessment.  Here are the scores: ".$msg." from file ".$userFileName;
	mail($programMngt, " Assessment ".$fname." ".$lname, $msg2);
	mail($email, " Assessment ".$fname." ".$lname, $msg2);
} else {
	echo "The program director email isn&rsquo;t assigned so no email will be sent.";
}
#echo "EMAIL TO: ".$emailTo." msg is ".$msg2;

echo file_get_contents("footer.txt");
?>
