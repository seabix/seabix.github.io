<?php
/* this version is updated Jun 26 - to fulfil UCB needs.
GB - gb@bix.digital    benoit@fas.harvard.edu
*/
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
echo file_get_contents("header.txt");

/* **************** from parseArrayTest.php ********************* */
$git = array(0);
$cmdLine = array(1,2,9);
$language = array(3,4,7,12,13,14,15,17,18,19,21,22,23,24,25,27,28,29,30);
$oop = array(5,6,11); 
$dataanalysis = array(8,20,32,33); 
$algorithms = array(10,16,25,31,34);
$numpy = array(35,36,37,38,39,40); 
$pandas = array(41,42,43,44,45);


$lines = file($studentFile);
$studentNameAndContact = trim($lines[0]);
$exploded_studentNameAndContact = explode(",", $studentNameAndContact);

$studentFileRaw = trim($lines[1]);
$exploded_studentFileRaw = explode(",", $studentFileRaw);

$studentFileRaw = trim($lines[2]);
$exploded_studentAnswersFileRaw = explode(",", $studentFileRaw);

$answerKeyFile = "answerKey.txt";
$lines = file($answerKeyFile);
$answerKeyLine = trim($lines[0]);
$explodedanswerKeyLine = explode(",", $answerKeyLine);


$git = array(0);
$cmdLine = array(1,2,9);
$language = array(3,4,7,12,13,14,15,17,18,19,21,22,23,24,25,27,28,29,30);
$oop = array(5,6,11); 
$dataanalysis = array(8,20,32,33); 
$algorithms = array(10,16,25,31,34);
$numpy = array(35,36,37,38,39,40); 
$pandas = array(41,42,43,44,45);

$axisa = 0;
$axisb = 0;
$axisc = 0;
$axisd = 0;
$axise = 0;
$axisf = 0;
$axisg = 0;
$axish = 0;
$wrong = 0;
$right = 0;

for ($j = 0; $j < count($exploded_studentFileRaw); $j++) {
	$valueToFind = $exploded_studentFileRaw[$j];

	if ($explodedanswerKeyLine[$valueToFind] == $exploded_studentAnswersFileRaw[$valueToFind]) {
	
		if (in_array($valueToFind, $git)) { 
			$axisa++;
			$right++;
		}
		if (in_array($valueToFind, $cmdLine)) { 
			$axisb++;
			$right++;
		}
		if (in_array($valueToFind, $language)) { 
			$axisc++;$right++;
		}
		if (in_array($valueToFind, $oop)) { 
			$axisd++; 
			$right++;
		}
		if (in_array($valueToFind, $dataanalysis)) { 
			$axise++;
			$right++;
		}
		if (in_array($valueToFind, $algorithms)) { 
			$axisf++;
			$right++;
		}
		if (in_array($valueToFind, $numpy)) { 
			$axisg++;
			$right++;
		}
		if (in_array($valueToFind, $pandas)) { 
			$axish++;
			$right++;
		}
	} else {
		$wrong++;
	}
}

print("Waiver scores for <strong>$exploded_studentNameAndContact[1] $exploded_studentNameAndContact[0]</strong>  $exploded_studentNameAndContact[3]");
print("<br />Number correct $right.  Marked incorrect $wrong.");
print("<br />By theme clusters: a: $axisa b: $axisb c: $axisc d: $axisd d: $axise e: $axisf  g: $axisg <hr />");

$axisa = round($axisa/count($git),2);
$axisb = round($axisb/count($cmdLine),2);
$axisc = round($axisc/count($language),2);
$axisd = round($axisd/count($dataanalysis),2);
$axise = round($axise/count($algorithms),2);
$axisf = round($axisf/count($numpy),2);
$axisg = round($axisg/count($pandas),2);

/* stuf from radar chart */
echo '
<!-- D3.js -->
<script src="d3.min.js" charset="utf-8"></script>
<style>
	/* body { font-family: "Open Sans", sans-serif;
		font-size: 11px; font-weight: 300;
		fill: #242424;  text-align: center;
		text-shadow: 0 1px 0 #fff, 1px 0 0 #fff, -1px 0 0 #fff, 0 -1px 0 #fff;
		cursor: default; }
	*/
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
				{axis:"Numpy",value:'.$axisg.'},
				{axis:"Pandas",value:'.$axish.'}
			  ],
			   [//target scores

 				{axis:"Git and Github",value: 1.00},
 				{axis:"Command line and; General Computing",value: .90},
 				{axis:"Python Language and Syntax",value: .90},
 				{axis:"Algorithm Analysis",value: .80},
 				{axis:"Object Oriented Programming",value: .80},
 				{axis:"Data Analysis",value: .85},
 				{axis:"Numpy",value: .80},
 				{axis:"Pandas",value: 1.00}

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

/* MAIL TO ucb person and student.*/
if ($emailTo != "") {
	$msg2 = "Good day.  Student ".$fname." ".$lname." email: ".$email." SID: ".$sid.
	" completed an assessment.  Here are the scores: ".$msg." from file ".$userFileName;
	// student services or director below
	mail($emailTo, " Assessment ".$fname." ".$lname, $msg2);
	// student email below
	mail($email, " Assessment ".$fname." ".$lname, $msg2);
} else {
	echo "The program director email isn&rsquo;t assigned so no email will be sent.";
}

echo file_get_contents("footer.txt");
?>