import psycopg2
import numpy as np
import csv
import peche_sql
import os

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

# read CSV data to be uploaded

file_t_fleet = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/trawlers_T_FLEET.csv_out'

query = "SELECT id, navire FROM vms.navire;"

cur.execute(query)
vms_navire = np.asarray(cur.fetchall())

for i in range(len(vms_navire[:,1])):
 vms_navire[i,1] = vms_navire[i,1].replace('\xc3\xaa','e').replace(' ','').title()

years = ['2016','2017','2018']

for year in years:

 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/'+year+'/'
 filename = 'trawlers_ROUTE.csv_out'

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
    t_fleet = str(peche_sql.findID(lines[0],file_t_fleet,1))
   else:
    t_fleet = None
   # print date

   if lines[1] != None:
    navire = lines[1]
   # print date

   id_navire = vms_navire[vms_navire[:,1] == lines[1].replace('\xc3\xaa','e').replace(' ','').title(),0][0]

   if lines[2] != None:
    maree = lines[2]
   # print date
  
   if lines[3] != None:
    date = lines[3]
   # print date

   if lines[4] != None:
    lance = lines[4]
   # print date

   if lines[5] != None:
    h_d = lines[5]
   # print time

   if lines[6] != None:
    h_f = lines[6]
   # print time
  
   if lines[8] != None:
    lat_d = lines[8]
    if lines[7] < 0: lat_d = -1*lat_d
 
   if lines[10] != None:
    lon_d = lines[10]
    if lines[9] < 0: lon_d = -1*lon_d
 
   if lines[12] != None:
    lat_f = lines[12]
    if lines[11] < 0: lat_f = -1*lat_f
  
   if lines[14] != None:
    lon_f = lines[14]
    if lines[13] < 0: lon_f = -1*lon_f
 
   if lines[15] != None:
    depth_d = lines[15]
   # print time
 
   if lines[16] != None:
    depth_f = lines[16]
   # print time
 
   if lines[17] != None:
    speed = lines[17]
   # print time
 
   if lines[18] != None:
    reject = lines[18]
   # print time
 
   if lines[19] != None:
    sample = lines[19]
   # print time
      
   if lon_d != '' and lon_f != '' and lat_d != '' and lat_f != '':
 
    ## Execute a command: this creates a new table
    query = "INSERT INTO trawlers.route (username, id_navire, maree, t_fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment, location_d, location_f) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s, %s, %s, %s, ST_GeomFromText('POINT(%s %s)',4326), ST_GeomFromText('POINT(%s %s)',4326));"
   #   
#    print query
    cur.execute(query,('jmensa',id_navire, maree, t_fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample,' ',float(lon_d), float(lat_d), float(lon_f), float(lat_f)))
    conn.commit()
   
cur.close()
conn.close()
