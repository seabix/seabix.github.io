#!/Library/Frameworks/Python.framework/Versions/3.8/bin/python3 # -*- coding: UTF-8 -*-
import cgitb, cgi, sys, io, subprocess, traceback, os, stat
import datetime
from datetime import datetime

DEBUG = False

exe_path = r'/Library/Frameworks/Python.framework/Versions/3.8/bin/python3'

''' ************************************************************ 
UPDATED:  May 27, 2020 - the final question (50) is the Board/Solver question
used to be #18.  Now changed.  make sure to pass $studentFile = $_POST["studentFile"]
************************************************************ '''    
""" This is checkValues.py used only for the coding test questions, numpy, pandas """
""" The last question of the waiver is the Pandemic Question - the Board and Solver classes 
are provided by the student.  The script adds the require import statement and asserts 
and other code Paul wrote to execute and test the students' input.
May 22, 2020  """

# GET THE RUN-TIME INI DATA using python.
print("Content-Type: text/html\n\n")	# cgi requires 2 \n
# HTML HEADER
f = open("header-code.txt", "r")
print(f.read())
f.close()

""" GET THE DATA FROM THE SUBMITTED FORM """
form = cgi.FieldStorage()
z = form.getvalue('democode')
qno = form.getvalue("qno")
sid = form.getvalue("sid")
studentFile = form.getvalue("studentFile")

scoreToSave = "-"   # this is replaced by the output of the code run.
''' START THE PAGE '''

print('''<script>function closemyself() {
	window.opener=self;
	window.close();
}
</script>''')

print('''<style>body { padding: 20px;font-family: "Avenir Next", Helvetica, Raleway, sans-serif; }</style>''')
print('''</head><body onLoad="setTimeout('closemyself()',3000);">''')

""" ------------------------------------------------------------------------------ """
""" THIS SECTION CREATES THE FILE FOR RUNNING STUDENT SUBMISSION """
""" IF THE QUESTION IS THE PANDEMIC QUESTION, THEN PREPARE A DIFFERENT FILE FOR TESTING """

''' GET THE QUESTION '''
studentCodeToTest = "studentcode/"+sid+"-"+qno+".py"

if DEBUG:
	print("<br />Values from form:")
	print("<ol><li>Question#: (variable name is qno) = ", qno, "</li>")
	print("<li>Student ID: (sid) = ", sid, "</li>")
	print("<li>Answers are stored in (studentFile) = ", studentFile, "</li>")
	print("<li>Code Sample for testing saved in (studentCodeToTest) = ", studentCodeToTest, "</li>")
	print("<li>Submitted code in (z) is ", z, "</li></ol>")
	
if qno == "50": # THE LAST QUESTION DOES THIS ... 17":  
	studentCodeToTest = "studentcode/"+sid+"_final.py" 
	""" build the students final test script """
	try: 
		print("<ol><li>Building the final solver and board test file.</li>")
		f = open(studentCodeToTest, "a")
		f.write("from nose.tools import assert_equal\n\n")
		# add solve part
		print("<li>Adding student supplied Board and Solver class.</li>")
		f.write(z)
		f.write("\n\n")
		print("<li>Integrating reference board.</li>")
		with open("reference_board.txt", "r") as reader:
			for line in reader:
				f.write(line)
		print("<li>File complete.  Now testing.</li></ol>")
	except:
		print("Sorry, the file (",studentCodeToTest,") could not be created.")
	finally:
		f.close()
else:
	try:
		print("<ol><li>Building your file for testing. ",studentCodeToTest,"</li>") 
		f = open(studentCodeToTest, "w")
		f.write(z)
	except:
		print("Sorry, the file (",studentCodeToTest,") could not be created.")
	finally:
		f.close()

startTime = datetime.now()

""" ------------------------------- END OF FILE CREATION ----------------------- """

print("<li>Testing your script.</li>")
print("<li>Answers stored in ",studentFile,"</li>")
# test the code
''' Notice the fully articulated path; most references show relative '''

scoreToSave = ""
with open(studentCodeToTest, "r") as test_file:
	stdin = test_file.read().strip()
	p = subprocess.run([exe_path], input=stdin, stdout=subprocess.PIPE, universal_newlines=True)
	out = p.stdout.strip()
	err = p.stderr
	returnCode = p.returncode
	
	print("<strong>studentFile = ", studentFile, "</strong>")

	print("<li>Python interpreter provided the following codes for question ",qno)
	print("<ul><li>Error codes: ",err,"</li>")
	print("<li>Return code: ", returnCode, "</li>")
	print("<li>Output note: ", out, "</li></ul></li>")
	
	if returnCode == 0:					# use the returnCode for right/wrong
		scoreToSave = '0'
		#print("<p>Your code snippet seems to run okay.</p>")
	else:
		print("<p>This snippet <span style='color:red'>did not</span> run okay.  Any code > 0 means an error: ", returnCode, "</p>")
		scoreToSave = "X"	
	
	''' run the code; capture status (0 = ok; 1 = failure) '''
	print("<br />Testing numpy, pandas, and OO questions.")
	if stdin == out:					# check output is okay
		print("<li>[Question ",qno, "]<h2>Code Output: ")
		print(out + "</h2></li>")
	else:
		""" CONFIRM THE EXECUTION OF THE SNIPPET """
		print("<li>[Question ",qno, "] Code Output: ")
		print("The code generated: <blockquote><code>" + out + "</code></blockquote></li></ol>")
		
		""" SOME TESTS RETURN A VALUE TO BE CHECKED """
		if (qno == "37"):  # numpy  # WAS 5
			print("Question 37. min/max/median")
			if out.find("128") > 0 and out.find('-127') > 0 and out.find('-0.791') > 0:
				print("Correct. &#128076;&#127999;")
			else:
				scoreToSave = "x"
				print("Sorry, at least one value was incorrect.  &#129318;&#127996;")
		elif (qno == "38"):  # WAS 6
			print("Question 6. Value should be 21")
			if out.find("21")  > 0: # == "21":
				print("Correct. &#128076;&#127998;")
			else:
				scoreToSave = "x"
				print("&#129318;&#127998;")
		elif (qno == "39"): # WAS 7
			print("Question 39. Value should be 506.")
			if out.find("506") > 0: # == "506":
				print("Correct. &#128076;&#127995;")
			else:
				scoreToSave = "x"
				print("&#129318;&#127995;")
		elif (qno == "40"):  # WAS 8
			print("Question 40. Value should be 748.")
			if out.find("748") > 0: # == "748":
				print("Correct.&#128076;&#127997;")
			else:
				scoreToSave = "x"
				print("&#129318;&#127999;")
		elif (qno == "41"):  # was 9
			print("Question 14. Value should be (199, 4)")
			if out.find("199") > 0: #  == "(199, 4)":
				print("Correct.&#128079;&#127995;")
			else:
				scoreToSave = "x"
				print("&#129318;&#127995;")
		elif (qno == "42"): # pandas  was 10
			print("Question 42. Value should be 8")
			if out.find("8") > 0: # == "8":
				print("Correct. &#128079;&#127996;")
			else:
				scoreToSave = "x"
				print("&#129318;&#127995;")
		elif (qno == "43"):  # was 11
			print("Question 43.  Value should be 46685")
			if out.find("46685"):
				print("Correct. &#128079;&#127997;")
			else:
				scoreToSave = "x"
				print("&#129318;&#127995;")
		elif (qno == "44"):  # 12
			print("Question 44.  Value should be 3963411")
			if out.find("3963411") > 0:
				print("Correct.&#128079;&#127998;")
			else:
				scoreToSave = "x"
				print("&#129318;&#127995;")
		elif (qno == "45"): 
			print("Question 45.  Value should be 5538774")
			if out.find("5538774"):
				print("Correct. &#128079;&#127999;")
			else:
				scoreToSave = "x"
				print("&#129318;&#127995;")
		elif (qno == "46"):
			print("Question 46. Value should be 123.")
			print("<blockquote>Out = ",out,"</blockquote>")
			if out.find("123") > 0:
				print("Correct. &#129305;&#127996;")
			else:
				scoreToSave = "x"
				print("&#129318;&#127997;")
		else: # QUESTION 15 and 16 are mapping questions - run or not; 17 is the OO Pandemic
			print("<blockquote>Out = ",out,"</blockquote>")
			if out == 0:
				print("Code submission run successfully. &#11088; &#x1F389;")
			else:
				scoreToSave = "x"
				print("&#128581;&#127995;")
		""" END CHECK INDIVIDUAL ANSWERS """
print("</ol>")	
exectime = datetime.now() - startTime
print("<p>Runtime: ",exectime, "</p>")

""" JUST FOR PAULS OO QUESTION """
if qno == "50":
	runtime_score = "studentcode/"+sid+"_runtimescore.txt"
	with open(runtime_score) as f:
		scoreToSave = f.readline()
	print("Score to Save: ", scoreToSave)

""" MAKE SURE IT'S THE RIGHT FOLDER! """
fileToWriteTest = "../Documents/ROB/"+studentFile
print("Attempting to save the score to <font color='red'>",fileToWriteTest,"</font>")

""" SAVE THE SCORE FROM THE TEST TO THE STUDENT'S FILE """
try:
	x = open(fileToWriteTest, "a")
	if DEBUG:
		x.write(qno+" - "+scoreToSave + ",")
	else:
		x.write(scoreToSave + ",")
	x.close()
	print("<br /><i>Score saved to your file.</i>")
except:
	print("Sorry, the answer could not be saved to your score file. <font color='red'>", fileToWriteTest, '</font>')
	print("<br />Please stop your assessment and contact the administrator.  Thanks.")

''' go back to the original page '''
print("<p>If this window does not close automatically in 3 seconds, please close it and continue with your assessment.</p>")

# HTML FOOTER
f = open("footer-code.txt", "r")
print(f.read())
f.close()
