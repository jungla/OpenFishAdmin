import psycopg2
import numpy as np
import csv
import peche_sql

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

file_csv = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_CARTE.csv_out'


with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next()

 for lines in spamreader:
 
  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None 
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None
 
  id_fisherman = peche_sql.findUUID('artisanal.fisherman','id_temp',lines[0],conn)

  payment = lines[5]
  receipt = lines[4]

  if receipt != '' or payment != '':
   paid = 'TRUE'

  date_v = peche_sql.mdy2ymd(lines[6])
  id_license = peche_sql.findUUID('artisanal.license','id_temp',lines[9],conn)
  id_temp = lines[8]

  query = "INSERT INTO artisanal.carte (username, id_fisherman, paid, date_v, id_license, active) VALUES (%s, %s, %s, %s, %s, %s)"
  print query
  cur.execute(query,('jmensa', id_fisherman, paid, date_v, id_license, 'TRUE'))

conn.commit()

cur.close()
conn.close()
