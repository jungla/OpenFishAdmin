import psycopg2
import numpy as np
import csv
import peche_sql

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

# read CSV data to be uploaded

import random

s = "abcdefghijklmnopqrstuvwxyz01234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ"
passlen = 8

path = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/users/'
filename = 'users_USERS.csv_out'

with open(path+filename, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next()

 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] == '': lines[i]=None
   if lines[i] == '\n': lines[i]=None
   if lines[i] == '-9999': lines[i]=None
   if lines[i] == 'NA': lines[i]=None
   if lines[i] == 'NaN': lines[i]=None
   if lines[i] == '#VALUE!': lines[i]=None

  if lines[0] != None:
   first_name = lines[0]
  # print date

  if lines[1] != None:
   last_name  = lines[1]
  # print date

  if lines[2] != None:
   nickname = lines[2]
  # print date

  if lines[3] == None:
   password =  "".join(random.sample(s, passlen))
  else:
   password =  lines[3]

  if lines[4] != None:
   email = lines[4]
  # print date

  ## Execute a command: this creates a new table
  query = "INSERT INTO users.users (username, first_name, last_name, nickname, password, email) VALUES (%s,%s,%s,%s,%s,%s);"
 #   
  print query
  cur.execute(query,('jmensa', first_name, last_name, nickname, password, email)) 
  conn.commit()
  
cur.close()
conn.close()
