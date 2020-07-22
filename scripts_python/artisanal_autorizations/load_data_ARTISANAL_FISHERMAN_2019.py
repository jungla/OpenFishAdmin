import psycopg2
import numpy as np
import peche_sql
import csv
import re

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

file_csv = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_FISHERMAN_2019.csv_out'
file_t_nationality = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_NATIONALITY.csv_out'

with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next()

 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None 
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None

  immatriculation = lines[0]

  if lines[1] is not None: 
   last_name = peche_sql.title(lines[1].strip())
  else:
   last_name = None 

  if lines[2] is not None: 
   first_name = peche_sql.title(lines[2].strip())
  else:
   first_name = None 

  t_nationality = peche_sql.findID(lines[3],file_t_nationality,1)
 
  idcard = peche_sql.stripList(lines[4],[' ','.',':'])
  ycard = peche_sql.stripList(lines[5],[' ','.',':'])

  dob = lines[6]
 
  date_v = '01-01-2019'

  # FISHERMAN

  query = "SELECT * FROM artisanal.fisherman WHERE UPPER(first_name) = UPPER(%s) AND UPPER(last_name) = UPPER(%s) AND username = 'jmensa'"
  cur.execute(query, (first_name, last_name))
  result = cur.fetchall()

  if len(result) != 0:
   id_fisherman = result[0][0]
   query = "UPDATE artisanal.fisherman SET first_name = %s, last_name = %s, t_nationality = %s, idcard = %s WHERE id = %s"
   cur.execute(query,(first_name, last_name, t_nationality, idcard, result[0][0]))
  else:
   query = "INSERT INTO artisanal.fisherman (username, first_name, last_name, t_nationality, idcard) VALUES (%s, %s, %s, %s,%s) RETURNING id"
   cur.execute(query,('jmensa',first_name,last_name,t_nationality,idcard))                                              
   id_fisherman = cur.fetchall()[0][0]

  # LICENSE (has to exist before entering CARTE)

  query = "SELECT * FROM artisanal.license LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.license.id_pirogue WHERE immatriculation = %s "
  cur.execute(query, (immatriculation,))
  result = cur.fetchall()

  if len(result) != 0:  
   id_license = result[0][0]
   query = "SELECT * FROM artisanal.carte WHERE id_fisherman = %s AND id_license = %s"
   cur.execute(query, (id_fisherman,id_license))
   result = cur.fetchall()
 
   if len(result) != 0:
    query = "UPDATE artisanal.carte SET date_v = %s, id_fisherman = %s, id_license = %s WHERE id = %s"
    cur.execute(query,(date_v, id_fisherman, id_license, result[0][0]))
   else:
    query = "INSERT INTO artisanal.carte (username, date_v, id_fisherman, id_license) VALUES (%s, %s, %s, %s)"
    cur.execute(query,('jmensa',date_v, id_fisherman, id_license))                                 
  else:
   print 'no license for pirogue: '+immatriculation 
conn.commit()

cur.close()
conn.close()
