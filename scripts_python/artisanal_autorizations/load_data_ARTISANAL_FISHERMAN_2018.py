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

file_csv = './artisanal_FISHERMAN_2018.csv_out'
file_t_nationality = './artisanal_T_NATIONALITY.csv_out'

with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next()

 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None 
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None

  if lines[1] is not None: 
   last_name = peche_sql.title(lines[2].strip())
  else:
   last_name = None 

  if lines[2] is not None: 
   first_name = peche_sql.title(lines[3].strip())
  else:
   first_name = None 

  dob = peche_sql.mdy2ymd(lines[5])
  t_nationality = peche_sql.findID(lines[4],file_t_nationality,1)
 
  idcard = peche_sql.stripList(lines[7],[' ','.',':'])

  if lines[6] == None:
   t_card = 99
  else:
   t_card = lines[6]

  tel = peche_sql.stripList(lines[10],[' ','.',',',':','-'])
  address = lines[9]

  query = "SELECT * FROM artisanal.fisherman WHERE first_name = %s AND last_name = %s AND username = 'jmensa'"
  cur.execute(query, (first_name, last_name))
  result = cur.fetchall()

  if len(result) != 0:
   print result[0][:]
   query = "UPDATE artisanal.fisherman SET first_name = %s, last_name = %s, t_nationality = %s, t_card = %s, idcard = %s, address = %s WHERE id = %s"
   cur.execute(query,(first_name, last_name, t_nationality, t_card, idcard, address, result[0][0]))
  else:
   query = "INSERT INTO artisanal.fisherman (username, first_name, last_name, t_nationality, t_card, idcard, telephone, address) VALUES (%s, %s, %s, %s, %s, %s, %s,%s)"
   cur.execute(query,('jmensa',first_name,last_name,t_nationality,t_card,idcard,tel,address))                                              

conn.commit()

cur.close()
conn.close()
