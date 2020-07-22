import csv
import datetime
import numpy as np
import psycopg2
import glob
import os

import fnmatch

filenames = []
for root, dirnames, files in os.walk(os.environ['HOME']+'/themis/_sync/'):
    for filename in fnmatch.filter(files, '*.dat'):
        filenames.append(os.path.join(root, filename))

#filenames = glob.glob(os.environ['HOME']+'/vms._sync/**/*dat')

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
## Open a cursor to perform database operations
cur = conn.cursor()

''' load list of navires '''

query = "SELECT id, navire, beacon, other_names FROM vms.navire;"
cur.execute(query)
vms_navire = np.asarray(cur.fetchall())

for i in range(len(vms_navire)):
 vms_navire[i,1] = vms_navire[i,1].replace(' ','').title()
 if  vms_navire[i,3] is None: vms_navire[i,3] = ''

''' goes over list of files in folder '''

for filename in filenames:
 #print filename
 with open(filename, 'rb') as csvfile:
  spamreader = csv.reader(csvfile, delimiter='=', quotechar='|')
 
  lines = []
 
  for new_p in spamreader:
   lines.append(new_p)

  if len(lines) > 0:

   ''' if navire does not exist creates a new one and reload list of navires '''

   navire = lines[0][1].replace('\xc3\xaa','e').replace(' ','').title()

   ''' check if name of navire exists '''
   if len(vms_navire[vms_navire[:,1] == navire,0]): 
    ids = vms_navire[vms_navire[:,1] == navire,0][0] 
   else:
    ''' if not in regular name, check if in other_names '''
    if any(navire in s for s in vms_navire[:,3]):
     ids = vms_navire[[navire in s for s in vms_navire[:,3]],0][0]
 
    ''' if not in other_names either, then add new vessel name '''
    else:
     cur.execute("INSERT INTO vms.navire (navire) VALUES ('"+lines[0][1].strip()+"') RETURNING id")
     ids = cur.fetchall()[0][0]

     # reload list of navires
     query = "SELECT id, navire, beacon FROM vms.navire;"
     cur.execute(query)
     vms_navire = np.asarray(cur.fetchall())
     for i in range(len(vms_navire)):
      vms_navire[i,1] = vms_navire[i,1].replace(' ','').title()

     print lines[0][1]
   
   # for each new_p in file, read from DB last entry for each pirogue and decide if lines need to be added
   query = "SELECT * FROM vms.positions WHERE id_navire = '"+ids+"' AND date_p = '"+datetime.datetime.strptime(lines[1][1],' %d/%m/%Y %H:%M GMT').isoformat()+"';"
   cur.execute(query)
   old_p = cur.fetchall()
 
   ''' if new position add record '''
 
   if len(old_p) == 0: 
 #  if len(old_p) == 0: 
    query = "INSERT INTO vms.positions (username,id_navire,date_p,location) VALUES (%s, %s, %s, ST_GeomFromText('POINT(%s %s)',4326))"
    cur.execute(query,('jmensa',ids,datetime.datetime.strptime(lines[1][1],' %d/%m/%Y %H:%M GMT'), float(lines[3][1]), float(lines[2][1])))
    #os.system('mv -f '+filename+' '+path_old)
   #else:
    #print 'old:',old_p[0][4]
    #print 'new:',datetime.datetime.strptime(lines[1][1],' %d/%m/%Y %H:%M GMT')
    #os.system('mv -f '+filename+' '+path_old)

  
  #else: 
   #os.system('mv -f '+filename+' '+path_old)
   #new_p[1],new_p[2], new_p[3], new_p[4]

conn.commit()
cur.close()
conn.close()
