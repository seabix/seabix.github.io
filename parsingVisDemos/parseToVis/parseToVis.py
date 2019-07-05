#counter TEST
''' Demo script and program, gbenoit@berkeley.edu
Tue Mar 5, 2019
Version: 0.1
NOTE: JUNK FILE FOR IMPROVING - LOTS OF
OPPORTUNITIES TO IMPROVE SPEED ...numpy?
---------------------------
NOTE that there are many plug-ins availble for
counting (parsing) texts.  And certainly better
libraries (nltk, regex, and others) for parsing.
MySQL has one, too:
https://dev.mysql.com/doc/refman/5.7/en/writing-full-text-plugins.html; and there's lots of projects, like TIKA
For python, let's experiment by using the collection
library and count token frequencies.  Then
we'll test different relevancy algorithms.
---------------------------
'''
import sys, os, webbrowser, string
from pathlib import Path
from string import punctuation
from collections import Counter
from collections import deque

''' Using global vars here to demo ... would be better
    not to use them in this type of program '''
tokens = []
count = 0
tf = {}
idfFinal = {}
noOfDocs = 0
fileNames = []

def welcomeMessage():
	print("-"*60)
	print("\n\n\nWelcome. This version is programmed to read 3 files.")
	'''
	print("Welcome to the parser test.  Enter any number of .txt files to be evaluated; results in a .json file of terms and idf-tf weights for relevancy ranking ... and then visualized in a browser.")
	print("\tYou passed ", len(sys.argv)-1, " documents to be processed.")
	fileNamesToTest =  sys.argv[1:]
	print("* ", fileNamesToTest)
	if len(sys.argv) == 1:
		print("\tYou must enter at least one file name.")
		sys.exit(0)
	print("-"*60)
	'''

def stopwords(s):
	''' accepts a token and returns items not in the stop list.
	'''
	stop = ['a','an','the','with','but','[',']']
	if s in stop:
		return False
	else:
		return True

def calcIDFTF(types):
	for item in types.items():
		#print("{}\t{}".format(*item))
		''' nls ow create tf and idf'''
		tf.update([ (item[0], item[1]/count) ])
		tfFinal = item[1]/count
		idf = item[1]/noOfDocs
		idfFinal.update([ (item[0], tfFinal * idf) ])
		print(tfFinal * idf)

def wrapUpMessage(n,d):
	#print(tf)
	print("\nUsing pprint to show terms + idf*tf weights:")
	#pprint.pprint(idfFinal)
	with open("resultsFile.json", "w") as file:
		file.write(json.dumps(idfFinal))
	print("\nAll done!\n\tTotal # of tokens: ",n)
	print("\tTotal # of documents: ",d,"\n\n")
''' --------------- START THE PROGRAM ---------------- '''
welcomeMessage()

''' --------------- PROCESS THE FILES ----------------
adding a value to increase the #s for vis '''
incrementer = 20

fileF = open(r"architecture-chinese.txt", "r", encoding="utf-8-sig")
fileG = open(r"architecture-newEngland.txt", "r", encoding="utf-8-sig")
fileH = open(r"architecture.txt", "r", encoding="utf-8-sig")

wordcount1 = Counter(fileF.read().split())
wordcount2 = Counter(fileG.read().split())
wordcount3 = Counter(fileH.read().split())

val1 = 1
val2 = 1
val3 = 1

# -------------------------
print("TEST list of terms")
words = list()
for key in wordcount1:
	key = key.translate(string.punctuation)
	words.append(key.lower())

for key in wordcount2:
	if key not in words:
		key = key.translate(string.punctuation)
		words.append(key.lower())

for key in wordcount3:
	if key not in words:
		key = key.translate(string.punctuation)
		words.append(key.lower())

print("--- LIST --- ")
for key in sorted(words):
	print(key)
# ------------------------

finalList = list()

for key in sorted(words, key=str.lower):
	#value = words[key]
	#print(key.upper(), " : ", value)
	print("Processing: ",key)

	#key = key.lower()

	if wordcount1.get(key) is not None:
		#print("d1 value is ",wordcount1.get(key)," ", sep="")
		val1 = wordcount1.get(key) * incrementer
	else:
		val1 = 5

	if wordcount2.get(key) is not None:
		#print("d2 value is ",wordcount2.get(key)," ",sep="")
		val2 = wordcount2.get(key) * incrementer
	else:
		val2 = 5

	if wordcount3.get(key) is not None:
		#print("d3 value is ",wordcount3.get(key))
		val3 = wordcount3.get(key) * incrementer
	else:
		val3 = 5

	#print(key," 1: ",val1," 2:",val2," 3:",val3)

	print("\tappending to finalList")
	finalList.append(key.lower()+",")
	finalList.append(str(val1)+",")
	finalList.append(str(val2)+",")
	finalList.append(str(val3)+"\n")

print("Creating string to save")
stringToSaveToFile = ""
for i in range(0, len(finalList)):
	stringToSaveToFile += str(finalList[i])

print(stringToSaveToFile)

print("Writing the data to a csv file.")
fo = open("dataset1.csv","wt")
fo.write("department,i,ii,iii\n")
fo.write(stringToSaveToFile)
fo.close()

#if(os.path.exists('/this/is/a/dir')
try:
	with open('dataset1.csv') as file:
		print("The output file has been created.")
		# pass
except IOError as e:
	print("Sorry, unable to access the file :(" )

print("Calling webpage (off)")


print("_"*60)
print(" D O N E ")
''' works great if not using https or d3
webbrowser.open('http://'+os.path.realpath('triangleScatterplot1.html')) '''

webbrowser.open('http://127.0.0.1/ir-test/triangleScatterplot1.html')
