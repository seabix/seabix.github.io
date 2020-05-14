#!/Library/Frameworks/Python.framework/Versions/3.8/bin/python3 # -*- coding: UTF-8 -*-
import cgitb, cgi, sys, io, subprocess, traceback, os, stat
import datetime
from datetime import datetime


""" GET THE DATA """
form = cgi.FieldStorage()
z = form.getvalue('democode')
username = form.getvalue("username")
qno = form.getvalue("qno")

""" this file is in CGI-Executables.  there's a score card in Documents called studentcode, too. """
#codeToTestFile = "studentcode/"+username+".py"
if qno == "17":
	codeToTestFile = "studentcode/"+username+qno+"_board.py"
#elif qno == "9":
#	codeToTestFile = "studentcode/"+username+qno+"_solver.py"
	#elif qno == "10":
	#	codeToTestFileFinal = "studentcode/"+username+qno+"boardsolvertest"+qno+".py"
else:
	codeToTestFile = "studentcode/"+username+"-qno"+qno+".py"
	

""" REQUIRED FOR html page for display """
print("Content-Type: text/html\n\n")					# cgi requires 2 \n


print("<!DOCTYPE html><html><head><meta charset=\"utf-8\"><title>Waiver Questions</title>")
print('''<script>function closemyself() {
	window.opener=self;
	window.close();  //self.close();
}
</script>''')

print("<style>body { padding:12px; font-family:'Avenir Next',Raleway,sans-serif; font-size:14px;}</style></head>")
print("<body onLoad=\"setTimeout('closemyself()',3500);\">")

''' write end-users code to disk and run '''
#print(htmlheaderstring)


print("<blockquote>(Question "+qno+")<br />Code: <code> " + z + "</code>.<hr/>Saving your answer to <font color='red'>",codeToTestFile,"</font></blockquote>")

""" IF BOARD, SOLVER, or FINAL QUESTION qno8 9 10 - MUST ADD instance for running student Class """
""" ONLY FOR THE TEST QUESTION OF RUNNING THIS ... wtf? """

""" WHAT A PAIN IN THE ASS ... SAVE BOARD TO 8, SOLVER TO 9, THEN INTEGREATE INTO NEW 
FILE FOR TESTING USING THE REFERENCE_BOARD - 10 """

""" see the backup file g2-a.backups? """
if qno == "17":  # was 8
	# get the previously submitted solver and  board and test
	addline = "from nose.tools import assert_equal"
	
	#s = "studentcode/"+username+"9_solver.py"
	codeToTestFile = "studentcode/"+username+qno+"_final.py"

	""" build the students final test script """
	try: 
		print("<ol><li>Building the final solver and board test file.</li>")
		f = open(codeToTestFile, "a")
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
		print("Sorry, the file (",codeToTestFile,") could not be created.")
	finally:
		f.close()
else:
	try: 
		f = open(codeToTestFile, "w")
		f.write(z)
	except:
		print("Sorry, the file (",codeToTestFile,") could not be created.")
	finally:
		f.close()

startTime = datetime.now()

''' Notice the fully articulated path; most references show relative '''
exe_path = r'/Library/Frameworks/Python.framework/Versions/3.8/bin/python3'
scoreToSave = ""
with open(codeToTestFile, "r") as test_file:
	stdin = test_file.read().strip()
	p = subprocess.run([exe_path], input=stdin, stdout=subprocess.PIPE, universal_newlines=True)
	out = p.stdout.strip()
	err = p.stderr
	returnCode = p.returncode

	''' run the code; capture status (0 = ok; 1 = failure) '''
	if stdin == out:									# check output is okay
		print("<p>OK" + out+"</p>")
	else:
		print("<p>Script output: <code>" + out + "</code></p>")

	exectime = datetime.now() - startTime

	if returnCode == 0:									# use the returnCode for right/wrong
		scoreToSave = "0"
		print("<p>The code snippet ran successfully.</p>")
		print("<p>Run time was ", exectime, "</p>")
	else:
		scoreToSave = "x"
		print("<p>This snippet <span style='color:red'>did not</span> run. Any code > 0 means an error: ", returnCode)
		print("<p>Runtime was ",exectime,"</p>")
		
		
		
''' Save the response to the appropriate file '''
#x = "../Documents/techassessment/studentcode/"+username+".txt"

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
if qno == "8":
	with open('studentcode/tempruntime.txt') as f:
		scoreToSave = f.readline()

print("Score to Save: ", scoreToSave)

try:
	# os.chmod(abspath, stat.S_IRUSR | stat.S_IWGRP | stat.S_IWOTH)
	file1 = open(x, "a")
	file1.write(scoreToSave + "\n")
	file1.close()
except:
	print("Sorry, the answer could not be saved to your score file. ", x)
	print("Please stop your assessment and contact the administrator.  Thanks.")

''' go back to the original page '''

print("<p>If this page does not close automatically in 2 seconds, please close it and continue with your assessment.</p>")

print("</body></html>")