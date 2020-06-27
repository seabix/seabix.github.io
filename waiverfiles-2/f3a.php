<?php
// load configuration data from waiver_config.ini
$ini_array = parse_ini_file("waiver_config.ini");
$studentFileToWrite  = $ini_array["studentAnswers"];
$questionFolder = $ini_array["questionFolder"];
$totalNoOfQuestions = $ini_array["totalNoOfQuestions"];
$codeQuestionsFolder = $ini_array["codeQuestionsFolder"];
$subfolder = $ini_array["subfolder"];

/* vars to catch */
//$qno = (int)$_POST["qno"];
$studentFile = $_POST["studentFile"];  // INDIVIDUAL STUDENT'S FILE
//$studentFile = "studentAnswers/Cutie_Chase_93.txt";
$sid = $_POST["sid"];


echo file_get_contents("header.txt");
// capture number and read the data
$sentNumber = $_POST["qno"];
$lines = file($studentFile);
$arrayOfQuestionNumbers = trim($lines[1]);
$random = explode(",",$arrayOfQuestionNumbers);

//$random = array(5,6,7,8);
$startNumpy = count($random)+1;
$startPandas = $startNumpy + 1;
$qno = $sentNumber+1;
$numpy = 36; // count($random)+1; // 9
$panda = 37; // count($random)+2; // 10
$lastNumber = count($random) + 2;

print("<style>.debug { font-size:12px; color:red; }</style>");

print("<div class='debug'>Processing the answer. Saving to $questionFolder"."q".$pieces[$qno].".txt");

if ($sentNumber != "") {
	print("Answer: sentNumber = $sentNumber");
	
	if ($sentNumber <= 36) {
	$j = 1;
	foreach ($_POST as $key => $value) {
	    $j += 1;
	    if ($j == 3) { 
	    	$answerToSave = $value;
	    }
	}
	if ($answerToSave == "") { 
		$answerToSave = 'no answer';
	}
	} else { 
		if ($qno == 36) {
			$q1a = $_POST["q1a"];
			$q1b = $_POST["q1b"];
			$q1c = $_POST["q1c"];
			$q1d = $_POST["q1d"];
			$q1e = $_POST["q1e"];
			$q1f = $_POST["q1f"];
			$q1g = $_POST["q1g"];
			print("<h1>qno $qno - answers?");
			print("q1a = $q1a</h1>");
			
		}
		if ($qno == 37) {
			$q2a = $_POST["2a"];
			$q2b = $_POST["2b"];
			$q2c = $_POST["2c"];
			$q2d = $_POST["2d"];
			$q2e = $_POST["2e"];
			print("<h1>37 37 37 37 qno $qno - answers?");
			print("q2a = $q2a</h1>");
		}
	}
	
	
	print(" – Sent Answer = $answerToSave ");
	print("– Student file name $studentFile</div>");
	// file_put_contents($studentFile, $answerToSave.",", FILE_APPEND | LOCK_EX );
	print("<hr/>");
}

// show the page
// this is the randomized stuff
print("<h1 style='color:red'>$qno - lastnum = $lastNumber</h1>");

if ($sentNumber <= 36) { // }$lastNumber) {  // <=?
	print("<p>INSIDE THE IF sentno < 35 - value of sentNo is <font color='green'>$sentNumber</font>");


	if ($qno <= count($random)) {
		print("--> Inside the loop for RANDOM qno = $qno and random count =".count($random)."</p>");
		print("--> 64 sent number is = $sentNumber");
		print("<form method='post' action='f3a.php'>");
		print("qno = $qno ");
		$tempQno = $qno - 1;
		print("<br />tempQno = $tempQno - ");
		print("and rand # is $random[$tempQno]");
		print(" - <input type='hidden' name='qno', id='qno' value='".$qno."'>");
	
		print("<p>Page would appear here: q$random[$tempQno].txt</p>");
		
		$fileToLoad = $questionFolder."q".$random[$tempQno].".txt";
		print($fileToLoad);
		$fileToLoad = $questionFolder."q".$random[$tempQno].".txt";
		echo file_get_contents($fileToLoad);
	
		echo file_get_contents("timer.txt");
		print('<input type="hidden" name="sid" id="sid" value="'.$sid.'">');
		print('<input type="hidden" name="studentFile" id="studentFile" value="'.$studentFile.'">');
		// print("<p><input spellcheck='false' type='text' name='test' id='test' width='5'></p>");
		print("<br /><input type='submit' value='Send my answer.'>");
		print("</form>");
	
	}
	// this is the non-randomized
	else {
		print("<hr /><h1>after else statement: this is after: sentNumber = $sentNumber</h1>");
		print("here the non-random shit should appear.");
		print("<h1>what is sentNumber = $sentNumber?</h1>");
		print("<p>what is startNumpy = $startNumpy? - what is sentNumber = $sentNumber?</p>");
		print("<p>what is <u>qno</u>? = $qno?</p>");
		
		$fileToLoad = $questionFolder."q".$random[$tempQno].".txt";
				
		if ($sentNumber == 35) {
			print("35 is the sent Number - add 1 to next question.");
		}
		if ($sentNumber == 36) {
			print("36 Is this panda what the fuck?");
		}

		if ($sentNumber == $numpy) { 
			print("NUMPY q is $numpy ");
		}
		if ($sentNumber == $panda) { 
			print("Panda q is $panda");
		}
		print("<form method='post' action='f3a.php'>");
		print("<br /><input type='hidden' name='qno', id='qno' value='".$qno."'>");
		print("<br />qno is '".$qno."'");
		
		print("<p>Page would appear here: q$qno.txt</p>");
		
		$fileToLoad = $questionFolder."q$qno.txt";
		echo file_get_contents($fileToLoad);
		
		print('<input type="hidden" name="sid" id="sid" value="'.$sid.'">');
		print('<input type="hidden" name="studentFile" id="studentFile" value="'.$studentFile.'">');
		print("<br /><input type='submit' value='confirmed idiot.'>");
		print("</form>");

	}
} else {
	print("This completes the draft waiver for Python");
	print("<form method='post' action='results-radarchart.php'><input type='submit' value='Okay' name='submit'></form>");

}



echo file_get_contents("footer.txt");
?>