import csv
import datetime
import numpy as np
import psycopg2
import glob
import os

filenames = glob.glob(os.environ['HOME']+'/themis/_sync/*dat')
path_old = os.environ['HOME']+'/themis/_sync/old/'

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
## Open a cursor to perform database operations
cur = conn.cursor()

# list of names. We can start with a given list of names...

# thon mature retenue tale (for comparison)
query = "SELECT id, navire, beacon FROM themis.navire;"

cur.execute(query)
themis_navire = np.asarray(cur.fetchall())

for filename in filenames:
 print filename
 with open(filename, 'rb') as csvfile:
  spamreader = csv.reader(csvfile, delimiter='=', quotechar='|')
 
  lines = []
 
  for new_p in spamreader:
   lines.append(new_p)

  if len(lines) > 0:
   if 'M_' not in lines[0][1].strip().title():
    ids = themis_navire[themis_navire[:,1] == lines[0][1].strip().title(),0][0]
  
    # for each new_p in file, read from DB last entry for each pirogue and decide if lines need to be added
    query = "SELECT * FROM themis.positions WHERE id_navire = '"+ids+"' ORDER BY date_p DESC LIMIT 1"
    cur.execute(query)
    old_p = cur.fetchall()
  
    # if new pirogue add record
  
    if len(old_p) == 0 or old_p[0][4] < datetime.datetime.strptime(lines[1][1],' %d/%m/%Y %H:%M GMT'):
  #  if len(old_p) == 0: 
     query = "INSERT INTO themis.positions (username,id_navire,date_p,location) VALUES (%s, %s, %s, ST_GeomFromText('POINT(%s %s)',4326))"
     cur.execute(query,('jmensa',ids,datetime.datetime.strptime(lines[1][1],' %d/%m/%Y %H:%M GMT'), float(lines[3][1]), float(lines[2][1])))
     print query
     os.system('mv -f '+filename+' '+path_old)
    else:
     print 'old:',old_p[0][4]
     print 'new:',datetime.datetime.strptime(lines[1][1],' %d/%m/%Y %H:%M GMT')
     os.system('mv -f '+filename+' '+path_old)
 
   else: 
    os.system('mv -f '+filename+' '+path_old)
    #new_p[1],new_p[2], new_p[3], new_p[4]
  
conn.commit()
cur.close()
conn.close()
