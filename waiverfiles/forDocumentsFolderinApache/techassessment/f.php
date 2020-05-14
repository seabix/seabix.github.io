<?php
/* this file is called by ... ? */

/* get the previously set "user" cookie */
$fname = $_COOKIE["fname"];
$lname = $_COOKIE["lname"];
$email = $_COOKIE["email"];
$userFileName = $_COOKIE["userFileName"];

$lastquestionnumber = 17;
/* ------------- new ------------------------- */
define("USERNAMEVALUE", 0);
define("EMAILVALUE", 1);
define("SIDVALUE", 2);
/* get the cookie value */
$x = $_COOKIE["name"];
$studentFileName = "studentcode/".$x.".txt";
	
/* --------------------------------------------- */

/* NOTE: code questions *MUST* be stored in a folder called
  codingquestions2.  Each question must be in its own file, e.g., 
  ta-ucb-pythonquestionX.txt - replace X with the question #, 
  e.g., ta-ucb-pythonquestion2.txt
  ---------
  The script below will count the # of files in the folder (codingquestions2)
  and use that value as the total number of questions to present.
*/

// get the # of question files in the folder:
# __DIR__
$dirToReach = __DIR__ . "/codingquestions2/";
$fi = new FilesystemIterator($dirToReach, FilesystemIterator::SKIP_DOTS);
$totalNoOfQuestions = iterator_count($fi);

echo file_get_contents("ta-new-top.txt");

echo "<meta charset='utf-8'><style>
body { padding-top: 120px;
font-family: 'Avenir Next', 'Open Sans', Raleway, sans-serif;
font-size: 16px;
}
i { font-family: Palatino, serif; }
.nextbutton {
	background-color: cornflowerblue;
	color: black;
	font-size: 16px;
}
.center {
	font-size: 12px;
  margin: auto;
  width: 70%;
  /* border: 3px solid cornflowerblue; */
  padding: 10px;
}
textarea { font-family: Courier, mono;
	font-size:16px;
}
body { 
	padding-top: 10px;
}
.studentnamered { color: maroon; }
#democode { font-family: 'Courier', monospaced;
	font-size:10px;
	 -moz-tab-size: 16;
	 tab-size: 16;
}
</style>";

$qno = $_GET['qno'];
if (($qno == "") || (!(isset($qno)))) {
	$qno = 0;
}
$qno = $qno + 1;

$qfile = "ta-ucb-pythonquestion".$qno.".txt";

echo "<body>";

if ($qno < 18) {
	echo "<p class='center'>Part 2: Code Sample Assessment for <span class='studentnamered'>".$x."</span></h2>"; // fname." ".$lname.": </span></h2>
	echo "<br /><font color='red'>Note: use <u>spaces</u>, not tabs, in your script.<br /></font>";
	echo "<font size='-2'>reading file ".$qfile."</font></p>";
	
	if ($qno > 4 and $qno <= $lastquestionnumber) { # numpy, pandas, and OO pandemic question.
		echo '<div class="center"><form method="get" action="../cgi-bin/checkValues.py" target="_blank">';
	} else {
		echo '<div class="center"><form method="get" action="../cgi-bin/g2-a.py" target="_blank">';
	}
	
	echo '<input type="hidden" name="username" id="username" value="'.$x.'">';
	echo "<p><hr /><i>Question ".$qno.":</i><br /> <div id='question'></div>";
		
	if ($qno > 4 and $qno < 10) { # numpy, pandas, and OO pandemic question.
	echo "<textarea id='democode' name='democode' rows='30' cols='70' required>
import numpy as np
np.random.seed(25)
ar = np.random.randn(1000)
ar = ar * 100
ar = ar.astype('int8')
ar = ar.reshape((200, 5))

</textarea>";
	} else {
		echo "<textarea id='democode' name='democode' rows='20' cols='70' required></textarea>";
	}
	echo "<input type='hidden' name='qno' id='qno' value='".$qno."'>
		<br />
		<button id='testThisCode' type='submit'>&#8634; First, test this code</button>
		</form>
		<hr />";

	
	echo "<p><font size='-2'>qno ".$qno." of ".$totalNoOfQuestions."</font> 
		<form action='f.php' method='get' target='_self'>
		<input type='hidden' id='qno' name='qno' value='".$qno."'>";
	echo "<button class='nextbutton' type='submit'>&#8674; Then Continue to the next question.</button></form></p></div>";
	
	echo "<script>
	function loadDoc(fileToGet) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById('question').innerHTML = this.responseText;
				this.responseText;
			}
		};
		xhttp.open('GET', fileToGet, true);
		xhttp.send();
	}";
	
	$qfile = "codingquestions2/ta-ucb-pythonquestion".$qno.".txt";
	
	echo "loadDoc('".$qfile."');</script>";
	echo '<hr /><div id="countDown"></div></form></div>
	
	<!-- LOAD THE QUESTION -->
	<div id="footer">
		<p>&nbsp;</p>
		UC Berkeley :: MIDs Program :: Test Version :: May 11, 2020.  <span id="datetime"></span>
	</div>
	
	<script>
		// document.getElementById("testo").innerHTML = ""; // this is the timer countdown
		var recordAttempt = false;
		var CCOUNT = 60;  /* NUMBER OF SECONDS PER QUESTION */
		var runningCount = 0; /* Student # of seconds to complete */
	    var t, count;
		var total = 33;
		document.getElementById("totalNoOfQuestions").innerHTML = total + " of the multiple choice section";
		// Set the date we are counting down to
		var countDownDate = /* new Date("Jan 5, 2021 15:37:25").getTime(); */
			new Date().getTime() + (1000 * 60);
	
		// Update the count down every 1 second
		var x = setInterval(function() {
		var now = new Date().getTime();
		var distance = countDownDate - now;
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);
		document.getElementById("countDown").innerHTML = "&#9200; "+seconds + " s";  
		if (seconds < 11) { 
	  		document.getElementById("countDown").style.backgroundColor = "hsl(14, 100%, 53%)";
		}  
		if (distance < 0) {
	    	clearInterval(x);
	    	document.getElementById("countDown").innerHTML = "Expired";
	    	/* SHOW THE NEXT  PAGE ONLY IF THERE ARE MORE QUESTIONS. ELSE GO TO CODE SECTION. */
	    	qno++;
	    	if (qno < total) { 
		    	window.location.href = "q"+qno.toString()+".txt";
		    }
	  	}
		}, 1000);
	
		document.getElementById("datetime").innerHTML = "Page generated on "+new Date()+".";
		document.getElementById("qno").innerHTML = qno;
	</script>';
	
} else {
	echo "<p>Congratulations.  Next you'll see a radar plot of expected score levels and 
	your scores.</p>
		<form action='ta-final-radarchart.php' method='get' target='_self'>
		<button class='nextbutton' type='submit'>&#8674; All done!</button></form></p></div>";
}
echo '</body></html>';
?>