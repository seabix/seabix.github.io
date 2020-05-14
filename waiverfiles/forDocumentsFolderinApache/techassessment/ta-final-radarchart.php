<?php
/* Final version - March 31, 2020 - needs to be tested by others */
/* parts are over-coded, I know.  just to get the stuff done and in testing.*/
define("USERNAMEVALUE", 0);
define("EMAILVALUE", 1);
define("SIDVALUE", 2);

/* get the cookie value */
echo file_get_contents("ta-new-top.txt");
$x = $_COOKIE["name"];
$fname = $_COOKIE["fname"];
$lname = $_COOKIE["lname"];
$email = $_COOKIE["email"];
$x = $_COOKIE["userFileName"];
$studentFileName = "studentcode/".$x.".txt";


// LOAD STUDENT ANSWERS
$studentDataArray = explode("\n", file_get_contents($studentFileName));
// load ANSWER KEY
$answerKeyArray   = explode("\n", file_get_contents("ta-ucb-answers.txt"));


echo "<h2>Assessment for ".$fname." ".$lname."<hr /></h2>";
echo "<p>Key: the yellow area is the department performance level. The red area reflects your scores.</p>";
echo "<a href='mailto:".$email."'>Email</a>.  |  Loading data from ".$studentFileName;
// load STUDENT ANSWERS

/* COMPARE submitted answers to stored correct answer numbers */
/* skills are plotted for now on 8 axes.
BELOW are the actual #s, for the arrays need to shift down by 1 for 0-indexing
*/
$axisa = 0;		# Git and Github (1 question)  #1
$axisb = 0;		# Command line and General Computing (3 qs), 2,3,4, 10
$axisc = 0;		# Python Language and Syntax (17 qs), 5, 8, 13, 14  16, 18 19, 20, 23, 24, 25, 26, 28,2930, 31, 32, 33
$axisd = 0;		# Algorithm Analysis (4 questions) , 11 , 15 17 27, 
				# UPDATED MAY 13 - add the coding equestions
$axise = 0;		# Object Oriented Programming (3 qs + more in coding),  6, 7, 12 
$axisf = 0;		# Data Analysis (2 qs + more in coding). <br />  9, 21, 22
$axisg = 0;		# Part 2 questions:  34-38
$axish = 0;		# part 3 questions = 39,40,41,42,43,44 and final 45machine learning?  whatever

/* ARRAYS FOR THE QUESTION #s TO COMPARE FOR AXIS CATEGORIES */
$git = array(0);
$cmdLine = array(1, 2, 3, 9);
$language = array(4,7,12,13,15,17,18,19,22,23,24,25,27,28,29,30,31,32);
$algorithms = array(10,14,16,25);
$oop = array(5,6,11);
$dataanalysis = array(8,20,21);
$part2 = array(33, 34, 35, 36, 37);
$part3 = array(38,39,40,41,42,43,44);
$totalNumberOfQuestions = sizeof($git)+sizeof($cmdLine)+sizeof($language)+sizeof($algorithms)+
sizeof($oop)+sizeof($dataanalysis)+sizeof($part2)+sizeof($part3);

$sizeOfArray = $totalNumberOfQuestions;

for ($x = 0; $x < $sizeOfArray+1; $x++) {
	$a = $answerKeyArray[$x];
	$b = $studentDataArray[$x];
	if ($a == $b) {
		echo " - hit. ";
		// find out what category
		if (in_array($x, $git)) {
			$axisa++;
			echo $axisa;
		}
		elseif (in_array($x, $cmdLine)) { 
			$axisb++;
			echo $axisb;
		} elseif (in_array($x, $language)) { 
			$axisc++;
			echo $axisc;
		} elseif (in_array($x, $algorithms)) { 
			$axisd++;
			echo $axisd;
		} elseif (in_array($x, $oop)) { 
			$axise++;
			echo $axise;
		} elseif (in_array($x, $dataanalysis)) { 
			$axisf++;
			echo $axisf;
		} elseif (in_array($x, $part2)) { 
			$axisg++;
			echo $axisg;
		} elseif (in_array($x, $part3)) { 
			$axish++;
			echo $axish;
		}
	}
}

/* turn into percent of total */
$axisa = $axisa/$totalNumberOfQuestions;
$axisb = $axisb/$totalNumberOfQuestions;
$axisc = $axisc/$totalNumberOfQuestions;
$axisd = $axisd/$totalNumberOfQuestions;
$axise = $axise/$totalNumberOfQuestions;
$axisf = $axisf/$totalNumberOfQuestions;
$axisg = $axisg/$totalNumberOfQuestions;
$axish = $axish/$totalNumberOfQuestions;

/* JAVASCRIPT NEEDED FOR MAKING THE RADAR CHART */

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
 				{axis:"Command line and; General Computing",value:0.90},
 				{axis:"Python Language and Syntax",value:0.90},
 				{axis:"Algorithm Analysis",value:0.80},
 				{axis:"Object Oriented Programming",value:0.80},
 				{axis:"Data Analysis",value:0.85},
 				{axis:"Part 2 coding",value:0.80},
 				{axis:"Final coding",value:1.00}
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

$correct = round(($axisa*10+$axisb*10+$axisc*10+$axisd*10+$axise*10+$axisf*10+$axisg*10+$axish*10));
echo "<h2>".$msg."</h2>";

// MAIL TO ucb person and student.
if (file_exists("ta-ucb-programManagerEmail.txt")) { 
	$programMngt = file_get_contents("ta-ucb-programManagerEmail.txt");
	$msg2 = "Good day.  Student ".$fname." ".$lname." email: ".$email." SID: ".$sid.
	" completed an assessment.  Here are the scores: ".$msg." from file ".$userFileName;
	mail($programMngt, " Assessment ".$fname." ".$lname, $msg2);
	mail($email, " Assessment ".$fname." ".$lname, $msg2);
} else {
	echo "The program director email file isn&rsquo;t available so no email will be sent.";
}

echo '<hr /><div>UC Berkeley :: MIDs Program :: Test Version :: May 13, 2020.  <span id="datetime"></span></div>';
echo '<script>document.getElementById("datetime").innerHTML = "<br />Page generated on "+new Date();</script>';

echo '</body></html>';
?>