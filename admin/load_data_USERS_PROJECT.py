import psycopg2
import numpy as np
import csv
import peche_sql
import sys

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
cur = conn.cursor()

# read CSV data to be uploaded

path = sys.argv[1]
file = sys.argv[2]
filename = path+file+'.csv'

def findUUID_user(nickname,conn):
 if nickname is not None: 
  cur = conn.cursor()
  query = "SELECT id FROM users.users WHERE nickname='"+nickname+"' "
  #print query
  cur.execute(query)
  result = cur.fetchall()
  if len(result) == 0:
   return result
  else: 
   return result[0][0]
 else:       
  result = None
 return result



with open(filename, 'rt') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 header = next(spamreader, None)


 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] == '': lines[i]=None
   if lines[i] == '\n': lines[i]=None
   if lines[i] == '-9999': lines[i]=None
   if lines[i] == 'NA': lines[i]=None
   if lines[i] == 'NaN': lines[i]=None
   if lines[i] == '#VALUE!': lines[i]=None

  if lines[0] != None:
   id_user = findUUID_user(lines[0],conn)
  # print date

  if lines[1] != None:
   t_project = lines[1]
  # print date

  if lines[2] != None:
   t_role = lines[2]
  # print date

  if lines[3] != None:
   active = lines[3]
  # print date

  ## Execute a command: this creates a new table
  query = "INSERT INTO users.project (username, id_user, t_project, t_role, active) VALUES (%s,%s,%s,%s,%s);"
 #   
  print(query)
  cur.execute(query,('jmensa', id_user, t_project, t_role, active)) 
  conn.commit()
  
cur.close()
conn.close()
