#!/Library/Frameworks/Python.framework/Versions/3.8/bin/python3 # -*- coding: UTF-8 -*-
import cgitb, cgi, sys, io, subprocess, traceback, os, stat
import datetime
from datetime import datetime
""" This is checkValues.py used only for the coding test questions, numpy, pandas """
""" The last question of the waiver is the Pandemic Question - the Board and Solver classes 
are provided by the student.  The script adds the require import statement and asserts 
and other code Paul wrote to execute and test the students' input.
"""

print("Content-Type: text/html\n\n")					# cgi requires 2 \n

""" GET THE DATA """
form = cgi.FieldStorage()
z = form.getvalue('democode')
username = form.getvalue("username")
qno = form.getvalue("qno")

''' START THE PAGE '''
print('''<!DOCTYPE html><head><title>Numpy or Pandas Questions</title>''')

print('''<script>function closemyself() {
	window.opener=self;
	window.close();  //self.close();
}
</script>''')


print('''<style>body { padding: 20px;font-family: "Avenir Next", Helvetica, Raleway, sans-serif; }</style>''')
print('''</head><body onLoad="setTimeout('closemyself()',4000);">''')

""" ------------------------------------------------------------------------------ """
""" THIS SECTION CREATES THE FILE FOR RUNNING STUDENT SUBMISSION """
""" IF THE QUESTION IS THE PANDEMIC QUESTION, THEN PREPARE A DIFFERENT FILE FOR TESTING """

''' GET THE QUESTION '''
""" see the backup file g2-a.backups? """
# start the file because optNo isn't there yet.
fname = "studentcode/"+username+"-qno.py"
print("<font color='red'>qno = ", qno, "</font>")

if qno == "17":  # was 8
	addline = "from nose.tools import assert_equal"
	fname = "studentcode/"+username+qno+"_final.py"
	""" build the students final test script """
	try: 
		print("<ol><li>Building the final solver and board test file.</li>")
		f = open(fname, "a")
		f.write(addline)
		f.write("\n\n")
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
		print("Sorry, the file (",fname,") could not be created.")
	finally:
		f.close()
else:
	try: 
		f = open(fname, "w")
		f.write(z)
	except:
		print("Sorry, the file (",fname,") could not be created.")
	finally:
		f.close()

startTime = datetime.now()

""" ------------------------------- END OF FILE CREATION ----------------------- """


print("<li>Testing your script.</li>")
# test the code
''' Notice the fully articulated path; most references show relative '''
exe_path = r'/Library/Frameworks/Python.framework/Versions/3.8/bin/python3'
scoreToSave = ""
with open(fname, "r") as test_file:
	stdin = test_file.read().strip()
	p = subprocess.run([exe_path], input=stdin, stdout=subprocess.PIPE, universal_newlines=True)
	out = p.stdout.strip()
	err = p.stderr
	returnCode = p.returncode

	
	''' run the code; capture status (0 = ok; 1 = failure) '''
	if stdin == out:					# check output is okay
		print("<li>[Question ",qno, "] Code Output: ")
		print("OK" + out + "</li></ol>")
	else:
		""" CONFIRM THE EXECUTION OF THE SNIPPET """
		print("<li>[Question ",qno, "] Code Output: ")
		print("Your code generated: <blockquote><code>" + out + "</code></blockquote></li></ol>")
		
		""" SOME TESTS RETURN A VALUE TO BE CHECKED """
		if (qno == "5"):  # numpy
			print("Question 5. min/max/median")
			if out.find("128") > 0 and out.find('-127') > 0 and out.find('-0.791') > 0:
				print("Correct. &#128076;&#127999;")
			else:
				print("Sorry, at least one value was incorrect.  &#129318;&#127996;")
		elif (qno == "6"):
			print("Question 6. Value should be 21")
			if out.find("21")  > 0: # == "21":
				print("Correct. &#128076;&#127998;")
			else:
				print("&#129318;&#127998;")
		elif (qno == "7"):
			print("Question 7. Value should be 506.")
			if out.find("506") > 0: # == "506":
				print("Correct. &#128076;&#127995;")
			else:
				print("&#129318;&#127995;")
		elif (qno == "8"):
			print("Question 8. Value should be 748.")
			if out.find("748") > 0: # == "748":
				print("Correct.&#128076;&#127997;")
			else:
				print("&#129318;&#127999;")
		elif (qno == "9"):
			print("Question 9. Value should be (199, 4)")
			if out.find("199") > 0: #  == "(199, 4)":
				print("Correct.&#128079;&#127995;")
			else:
				print("&#129318;&#127995;")
		elif (qno == "10"): # pandas
			print("Question 10. Value should be 8")
			if out.find("8") > 0: # == "8":
				print("Correct. &#128079;&#127996;")
			else:
				print("&#129318;&#127995;")
		elif (qno == "11"): 
			print("Question 11.  Value should be 46685")
			if out.find("46685"):
				print("Correct. &#128079;&#127997;")
			else:
				print("&#129318;&#127995;")
		elif (qno == "12"): 
			print("Question 12.  Value should be 3963411")
			if out.find("3963411") > 0:
				print("Correct.&#128079;&#127998;")
			else:
				print("&#129318;&#127995;")
		elif (qno == "13"): 
			print("Question 13.  Value should be 5538774")
			if out.find("5538774"):
				print("Correct. &#128079;&#127999;")
			else:
				print("&#129318;&#127995;")
		elif (qno == "14"):
			print("Question 14. Value should be 123")
			if out.find("123") > 0:
				print("Correct. &#129305;&#127996;")
			else:
				print("&#129318;&#127997;")
		else: # QUESTION 15 and 16 are mapping questions - run or not; 17 is the OO Pandemic
			if out == 0:
				print("Code submission run successfully. &#11088; &#x1F389;")
			else:
				print("&#128581;&#127995;")
		""" END CHECK INDIVIDUAL ANSWERS """
		
	exectime = datetime.now() - startTime
	
	if returnCode == 0:					# use the returnCode for right/wrong
		scoreToSave = '0'
		print("<p>Your code snippet seems to run okay.</p>")
		print("<p>Runtime was ",exectime, "</p>")
	else:
		print("<p>This snippet <span style='color:red'>did not</span> run okay.  Any code > 0 means an error: ", returnCode, "</p>")
		scoreToSave = "X"
		print("<p>Runtime was ",exectime,"</p>")



""" RECORD THE SCORE AS BEFORE """
''' Save the response to the appropriate file '''
x = "../Documents/techassessment/studentcode/"+username+".txt"
""" Just for debugging 
fullfilepath = os.path.dirname(x) + "/"+x
abspath = os.path.abspath(x)
print("fullpath: ",fullfilepath)
print("<br /><br />")
print("abs path:", abspath)
print("<br /><br />")
print("file to write to: ", x,"<br /><br />")
"""

""" JUST FOR THE OO QUESTION """

if qno == "17":
	runtime_score = "studentcode/"+username+"_runtimescore.txt"
	with open(runtime_score) as f:
		scoreToSave = f.readline()
	print("Score to Save: ", scoreToSave)

# be sure to capture ALL the answers in this check code area. Moved Left

try:
	# os.chmod(abspath, stat.S_IRUSR | stat.S_IWGRP | stat.S_IWOTH)
	file1 = open(x, "a")
	file1.write("#"+qno+" "+scoreToSave + "\n")
	file1.close()
except:
	print("Sorry, the answer could not be saved to your score file. ", x)
	print("Please stop your assessment and contact the administrator.  Thanks.")

''' go back to the original page '''
print("<p>If this page does not close automatically in 2 seconds, please close it and continue with your assessment.</p>")
print("</body></html>")
