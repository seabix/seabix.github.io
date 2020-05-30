<?php

// load configuration data from waiver_config.ini
$ini_array = parse_ini_file("waiver_config.ini");
$studentFileToWrite  = $ini_array["studentAnswers"];
$questionFolder = $ini_array["questionFolder"];
$totalNoOfQuestions = $ini_array["totalNoOfQuestions"];
$totalNoOfCodeQuestions = $ini_array["totalNoOfCodeQuestions"];

/* q43 = data analysis with pandas */
/* q47 = last numpy question */
/* q48 = matplotlib */
/* q49 = matplotlib */
/* q50 = big class question */

echo file_get_contents("header.txt");
$studentFile = $_POST["studentFile"];  // ALWAYS PASS THIS KEY 
$sid = $_POST["sid"];

// ADDED May 22, 2020
echo '<script>
function showButton() {
	var a = document.forms["testForm"]["democode"].value;
	document.getElementById("democode").readOnly = "true";
	if (a != null) {
		var b = document.getElementById("nextbutton");
		b.style.display = "block";
		document.getElementById("testThisCode").style.display = "none";
	} else {
		return false;
	}
}
</script>';
// IF nothing (for testing) automatically set to x + 1 (e.g., 32 + 1 = 33 first code question)
$qno = $_POST['qno'];
if (($qno == "") || (!(isset($qno)))) {
	$qno = (int)$totalNoOfQuestions;
}
$qno = $qno + 1;

$qfile = $questionFolder."q".$qno.".txt";  # the question file to use

echo "<body>";

print("<br /><font size='-2'>For debugging: studentFile ".$studentFile." qno = ".$qno);
print(" Total # of questions: ". ( intval($totalNoOfQuestions) + intval($totalNoOfCodeQuestions)) ."</font>");
if ($qno <= 50) { 
	echo "<p class='center'><strong>Part 2</strong>: Code Sample Assessment.</h2>";
	echo "<br /><font size='-2' color='red'>Note: use <u>spaces</u>, not tabs, in your script.<br /></font>";
	echo '<div class="center">
	<form class="coding" name="testForm" method="post" action="../cgi-bin/checkValues.py" target="_blank">';
	echo file_get_contents($questionFolder."q".$qno.".txt");

	/* SPECIAL ARRANGEMENTS  TO CERTAIN QUESTIONS - Numpy and Pandas */	
	if ($qno >= 38 and $qno < 43) { # numpy, pandas, and OO pandemic question.
	echo "<p>&nbsp;</p><textarea id='democode' name='democode' rows='30' cols='70' required>
import numpy as np
np.random.seed(25)
ar = np.random.randn(1000)
ar = ar * 100
ar = ar.astype('int8')
ar = ar.reshape((200, 5))

</textarea><br />";
	} else {
		# The non-numpy, pandas, and non-pandemic question.
		echo "<br /><textarea id='democode' name='democode' rows='20' cols='80' required></textarea><br />";
	}
	echo "<input type='hidden' name='qno' id='qno' value='".$qno."'>
		<input type='hidden' id='sid' name='sid' value='".$sid."'>
		<input type='hidden' id='studentFile' name='studentFile' value='".$studentFile."'>
		<button id='testThisCode' name='testThisCode' type='submit' onclick=\"showButton()\">
			<img width='45px' src='gears.png'/><br />Test this code.
		</button>
		</form>";
		
	echo "<p>
		<form name='testForm' action='buildCodeQuestions.php' method='post' target='_self'>
		<input type='hidden' id='qno' name='qno' value='".$qno."'>
		<input type='hidden' id='sid' name='sid' value='".$sid."'>
		<input type='hidden' id='studentFile' name='studentFile' value='".$studentFile."'>";
	echo "<button style='display:none;' id='nextbutton' class='nextbutton' type='submit'>&#8674; Continue to the next question.</button></form></p></div>";

		
} else {
	echo "<p>Congratulations.  Next you&rsquo;ll see a radar plot of expected score levels and 
	your scores.</p>
		<form action='results-radarchart.php' method='post' target='_self'>
		<input type='hidden' id='studentFile' name='studentFile' value='".$studentFile."'>
		<input type='hidden' id='sid' name='sid' value='".$sid."'>
		<button class='nextbutton' type='submit'>&#8674; All done!</button></form></p></div>";
}
echo file_get_contents("footer.txt");

?>