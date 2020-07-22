import csv
import datetime
import numpy as np
import psycopg2
import glob
import os

filenames = glob.glob(os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/themis/Position_new_20*.csv')

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
## Open a cursor to perform database operations
cur = conn.cursor()

# list of names. We can start with a given list of names...

# thon mature retenue tale (for comparison)
query = "SELECT id, navire, beacon FROM themis.navire;"

cur.execute(query)
themis_navire = np.asarray(cur.fetchall())

for i in range(len(themis_navire)):
 themis_navire[i,1] = themis_navire[i,1].replace(' ','').title()

for filename in filenames:
 print filename
 with open(filename, 'rb') as csvfile:
  spamreader = csv.reader(csvfile, delimiter=';', quotechar='|')
  spamreader.next()
 
  date_t = [] 
  name_t = []
  lon_t = []
  lat_t = []

  for lines in spamreader:
   if lines[0][:2] != 'M_':
    ids = themis_navire[themis_navire[:,1] == lines[0].replace('\xc3\xaa','e').replace(' ','').title(),0][0]
 
    if ids == '': 
     print lines[0]
    # if new pirogue add record
 
    query = "INSERT INTO themis.positions (username,id_navire,date_p,speed,location) VALUES (%s, %s, %s, %s, ST_GeomFromText('POINT(%s %s)',4326))"
    cur.execute(query,('jmensa',ids,datetime.datetime.strptime(lines[2],'%m/%d/%Y %H:%M:%S'), float(lines[7]), float(lines[5]), float(lines[4])))
 
conn.commit()
#   print query

# process old saved files
#
#filenames = glob.glob(os.environ['HOME']+'/themis/_sync/old/*dat')
#
#for filename in filenames:
# #print filename
# with open(filename, 'rb') as csvfile:
#  spamreader = csv.reader(csvfile, delimiter='=', quotechar='|')
#
#  date_t = []
#  name_t = []
#  lon_t = []
#  lat_t = []
#
#  lines = []
#
#  for new_p in spamreader:
#   lines.append(new_p)
#
#  if len(lines) > 0:
#   if 'M_' not in lines[0][1].replace('\xc3\xaa','e').replace(' ','').title():
#    ids = themis_navire[themis_navire[:,1] == lines[0][1].replace('\xc3\xaa','e').replace(' ','').title(),0][0]
# 
#    query = "INSERT INTO themis.positions (username,id_navire,date_p,location) VALUES (%s, %s, %s, ST_GeomFromText('POINT(%s %s)',4326))"
#    cur.execute(query,('jmensa',ids,datetime.datetime.strptime(lines[1][1],' %d/%m/%Y %H:%M GMT'), float(lines[3][1]), float(lines[2][1])))
#    #os.system('rm '+filename)   
#    #print query
#
#conn.commit()
#
#cur.close()
#conn.close()
