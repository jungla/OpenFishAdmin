import csv
import numpy as np

file_cp = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/species_captures.csv'
file_sp = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/seiners/seiners_T_ESPECE.csv'

lines_sp = []

with open(file_sp, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 for line in spamreader:
  lines_sp.append(line)


with open(file_cp, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 for line in spamreader:
  lines_cp = line

lines_cp = np.asarray(lines_cp)
lines_sp = np.asarray(lines_sp)

f = open('/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/species_captures_code.csv','w')

for species in lines_cp:
 #print species.strip()
 code = lines_sp[np.where(lines_sp[:,1] == species.strip()),0][0]
 f.write(code[0]+', ')
 print code

f.close()
