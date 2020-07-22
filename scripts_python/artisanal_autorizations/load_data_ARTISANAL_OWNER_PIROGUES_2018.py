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

file_csv = './artisanal_OWNER_PIROGUE_2018.csv_out'
file_t_nationality = './artisanal_T_NATIONALITY.csv_out'

with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next() 

 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None 
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None
 
  last_name = peche_sql.title(lines[0])
  first_name = peche_sql.title(lines[1])
  t_nationality = peche_sql.findID(lines[2],file_t_nationality,1)

  if lines[3] == None:
   t_card = 99
  else:
   t_card = lines[3] 

  idcard = peche_sql.stripList(lines[4],[' ','.',':'])
  address = lines[5]
  tel = peche_sql.stripList(lines[6],[' ','.',':','-'])

  query = "SELECT * FROM artisanal.owner WHERE first_name = %s AND last_name = %s AND username = 'jmensa'"
  cur.execute(query, (first_name, last_name))
  result = cur.fetchall()

  if len(result) != 0:
#   print result[0][:]
   query = "UPDATE artisanal.owner SET first_name = %s, last_name = %s, t_nationality = %s, t_card = %s, idcard = %s, address = %s WHERE id = %s"
   cur.execute(query,(first_name, last_name, t_nationality, t_card, idcard, address, result[0][0]))
   id_owner = result[0][0]
  else:
   query = "INSERT INTO artisanal.owner (username, first_name, last_name, t_nationality, t_card, idcard, telephone, address) VALUES (%s, %s, %s, %s, %s, %s, %s, %s) RETURNING id"
   cur.execute(query,('jmensa',first_name,last_name,t_nationality,t_card,idcard,tel,address))
   id_owner = cur.fetchall()[0][0]

# PIROGUE

  name = lines[7]
  immatriculation = lines[8]
  
  if lines[9] == 'bois':
   t_pirogue = '0'
  elif lines[9] == 'plastique':
   t_pirogue = '1'
  else:
   t_pirogue = None

  query = "SELECT * FROM artisanal.pirogue WHERE immatriculation = %s AND username = 'jmensa'"
  cur.execute(query, (immatriculation,))
  result = cur.fetchall()
  
  if len(result) != 0:
#   print result[0][:]
   query = "UPDATE artisanal.pirogue SET name = %s, t_pirogue = %s, id_owner = %s WHERE id = %s"
   cur.execute(query,(name, t_pirogue, id_owner, result[0][0]))
  else:
   query = "INSERT INTO artisanal.pirogue (username, name, immatriculation, t_pirogue, id_owner) VALUES (%s, %s, %s, %s, %s)" 
   cur.execute(query,('jmensa', name, immatriculation, t_pirogue, id_owner))

conn.commit()

cur.close()
conn.close()
