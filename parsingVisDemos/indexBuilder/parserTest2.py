import io
import os
#from os import path
import sys

''' see https://www.geeksforgeeks.org/get-synonymsantonyms-nltk-wordnet-python/ '''
#fileNameToUse = sys.argv[1]

from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
#word_tokenize accepts a string as an input, not a file.

#print("Args = " + sys.argv[0], sys.argv[1] )

stop_words = set(stopwords.words('english'))

file1 = open("test.txt")
line = file1.read() # Use this to read file content as a stream:
words = line.split()
for r in words:
	print("testing: ", r)
	if not r in stop_words:
		appendFile = open('filteredtext.txt','a')
		appendFile.write(" "+r)
		appendFile.close()
		
if os.path.exists("filteredtext.txt"):
	print("The output filtered file is ready to go.")
	# os.remove("demofile.txt")
else:
	print("The file does not exist")
