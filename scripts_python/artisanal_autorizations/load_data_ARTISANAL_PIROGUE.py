import psycopg2
import numpy as np
import csv
import peche_sql
import re

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

file_csv = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_PIROGUE.csv_out'

file_owner = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_OWNER.csv_out'


with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')

 spamreader.next()

 for lines in spamreader:
 
  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None 
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None
 
  name = lines[0]
#  if lines[1] is not None:
#   immatriculation = peche_sql.stripList(lines[1].strip(),[' ','.',',',':'])
#   im_s = re.split('(\d+)',immatriculation)
#   immatriculation = im_s[0]+'. '+im_s[1]
#   print lines[1]
#   print immatriculation
  immatriculation = lines[1]

  if lines[2] == 'Bois':
   t_pirogue = '0'
  else: #plastique
   t_pirogue = '1'
  
  length = lines[3]

  id_owner = peche_sql.findUUID('artisanal.owner','id_temp',lines[6],conn) 
  
  id_temp = lines[5]

  query = "INSERT INTO artisanal.pirogue (username, name, immatriculation, t_pirogue, length, id_owner, id_temp) VALUES (%s, %s, %s, %s, %s, %s, %s)"
  print query
  cur.execute(query,('jmensa', name, immatriculation, t_pirogue, length, id_owner, id_temp))

conn.commit()

cur.close()
conn.close()
