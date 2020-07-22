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
query = "SELECT id, navire, beacon FROM vms.navire;"

cur.execute(query)
vms_navire = np.asarray(cur.fetchall())

path = os.getenv('HOME')+'/Google_Drive/Gabon_Bleu/_database/CSV_data/thon/'

filename = 'thon_ENTREESORTIE.csv_out'

with open(path+filename, 'rU') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next()

 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] is not None:
    lines[i] = lines[i].replace('\xc2\xa0','')
   if lines[i] == '': lines[i]=None
   if lines[i] == '\n': lines[i]=None
   if lines[i] == '-9999': lines[i]=None
   if lines[i] == 'NA': lines[i]=None
   if lines[i] == 'NaN': lines[i]=None
   if lines[i] == '#REF!': lines[i]=None
   if lines[i] == 'No data': lines[i]=None
   if lines[i] == 'NO DATA': lines[i]=None

  if len(vms_navire[vms_navire[:,1] == lines[0].replace('\xc3\xaa','e').strip().title(),0]) > 0:
   id_navire = vms_navire[vms_navire[:,1] == lines[0].replace('\xc3\xaa','e').strip().title(),0][0]
  else:
   id_navire = None
   print 'id_navire', lines[0]

  eez = lines[1]
  date_e = lines[2]
  heure_e = lines[3]

  if lines[10] == 'E':
   entree = True
  elif lines[10] == 'S':
   entree = False

  YFT = lines[11]
  BET = lines[12]
  SKJ = lines[13]
  FRI  = lines[14]

  remarques = lines[16]

  if lines[4] != None:
   deg = float(lines[4])
   if lines[5] is not None:
    min = float(lines[5])
   else:
    min = 0
   lat = deg+min/60.
   if lines[6] == 'S': lat = -1*lat
  else:
   deg = np.nan 
   min = np.nan
   lat = np.nan
 
  if lines[7] != None:
   deg = float(lines[7])
   if lines[8] is not None:
    min = float(lines[8])
   else:
    min = 0
   lon = deg+min/60.
   if lines[9] == 'O': lon = -1*lon
  else:
   deg = np.nan
   min = np.nan 
   lon = np.nan

   ## Execute a command: this creates a new table

  ''' ENTREE/SORTIE '''
 
  if str(lat) != 'nan' and str(lon) != 'nan':

   query = "INSERT INTO thon.entreesortie (username, id_navire, date_e, heure_e, eez, entree, YFT, BET, SKJ, FRI, remarques, location) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,ST_GeomFromText('POINT(%s %s)',4326));"
   print query
   cur.execute(query,('jmensa', id_navire, date_e, heure_e, eez, entree, YFT, BET, SKJ, FRI, remarques, lon, lat))

conn.commit()
cur.close()
conn.close()
