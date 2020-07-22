import psycopg2
import numpy as np
import csv
import peche_sql

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

file_csv = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_LICENSE.csv_out'

file_t_license = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_LICENSE.csv_out'
file_t_site = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_SITE.csv_out'
file_t_gear = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_GEAR.csv_out'
file_pirogue = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_PIROGUE.csv_out'


with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next()
 for lines in spamreader:
 
  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None 
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None
 
  license = lines[1]
  date_v = peche_sql.mdy2ymd(lines[2])
  t_license = peche_sql.findID(lines[4],file_t_license,1)
  payment = lines[6]
  receipt = lines[5]
  t_site = peche_sql.findID(lines[8],file_t_site,0)
  engine_brand = lines[9]
  engine_cv = lines[10]
  t_gear = peche_sql.findID(lines[11],file_t_gear,1)

  if lines[13] is not None:
   id_pirogue = peche_sql.findUUID('artisanal.pirogue','id_temp',lines[13],conn)
  else:
   id_pirogue = None

  id_temp = lines[14]

  if license is not None:
   query = "INSERT INTO artisanal.license (username, license, date_v, t_license, payment, receipt, t_site, engine_brand, engine_cv, t_gear, id_pirogue, id_temp, active) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
   print query
   cur.execute(query,('jmensa', license, date_v, t_license, payment, receipt, t_site, engine_brand, engine_cv, t_gear, id_pirogue, id_temp, 'TRUE'))
  else:
   query = "INSERT INTO artisanal.license (username, date_v, t_license, payment, receipt, t_site, engine_brand, engine_cv, t_gear, id_pirogue, id_temp, active) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
   print query
   cur.execute(query,('jmensa', date_v, t_license, payment, receipt, t_site, engine_brand, engine_cv, t_gear, id_pirogue, id_temp, 'TRUE'))

conn.commit()

cur.close()
conn.close()
