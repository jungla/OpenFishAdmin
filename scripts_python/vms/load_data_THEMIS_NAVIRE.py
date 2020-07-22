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

path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/themis/'
filename = 'themis_NAVIRE.csv_out'

print path+filename

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

  if lines[0] is None:
   navire = None
  else:
   navire = lines[0].title()

  if lines[1] is None:
   flag = None
  else:
   flag  = lines[1]

  if lines[2] is None:
   owners = None
  else:
   owners  = lines[2].replace('(100.0)','').title()

  if lines[3] is None:
   fullname = None
  else:
   fullname  = lines[3].title()

  if lines[4] is None:
   radio = None
  else:
   radio  = lines[4]

  if lines[5] is None:
   registration = None
  else:
   registration  = lines[5]

  if lines[6] is None:
   registration_ext = None
  else:
   registration_ext  = lines[6]

  if lines[7] is None:
   registration_int = None
  else:
   registration_int  = lines[7]

  if lines[8] is None:
   registration_qrt = None
  else:
   registration_qrt  = lines[8]

  if lines[9] is None:
   mobile = None
  else:
   mobile  = lines[9]

  if lines[10] is None:
   mmsi= None
  else:
   mmsi  = lines[10]

  if lines[11] is None:
   imo= None
  else:
   imo  = lines[11]

  if lines[12] is None:
   port= None
  else:
   port  = lines[12]   

  if lines[13] is None:
   active= None
  else:
   active = lines[13]  

  if lines[14] is None:
   beacon = None
  else:
   beacon  = lines[14]

  if lines[15] is None:
   satellite = None
  else:
   satellite  = lines[15]

  if lines[16] is None:
   unknown = None
  else:
   unknown  = lines[16]

  if lines[17] is None:
   t_navire = None
  else:
   t_navire = lines[17]


  ## Execute a command: this creates a new table

  query = "INSERT INTO themis.navire (username, navire, flag, owners, fullname, radio, registration, registration_ext, registration_int, registration_qrt, mobile, mmsi, imo, port, active, beacon, satellite, unknown, t_navire) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);"
   
  #print query
  cur.execute(query,('jmensa', navire, flag, owners, fullname, radio, registration, registration_ext, registration_int, registration_qrt, mobile, mmsi, imo, port, active, beacon, satellite, unknown, t_navire))
  conn.commit()

cur.close()
conn.close()
