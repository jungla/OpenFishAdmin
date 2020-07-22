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

query = "SELECT id, navire FROM vms.navire;"

cur.execute(query)
vms_navire = np.asarray(cur.fetchall())

for i in range(len(vms_navire[:,1])):
 vms_navire[i,1] = vms_navire[i,1].replace('\xc3\xaa','e').replace(' ','').title()

years = ['2016','2017']

file_t_systeme = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/seiners/seiners_T_SYSTEME.csv_out'
file_t_detection = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/seiners/seiners_T_DETECTION.csv_out'

for year in years:

 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/seiners/'+year+'/'
 filename = 'seiners_ROUTE.csv_out'

 with open(path+filename, 'rU') as csvfile:
  spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
  spamreader.next()
 
  l_fiche = []
  n_fiche = []
  l_fiche = 0

  for lines in spamreader:
   for i in range(len(lines)):
    if lines[i] == '': lines[i]=None
    if lines[i] == '\n': lines[i]=None
    if lines[i] == '-9999': lines[i]=None
    if lines[i] == 'NA': lines[i]=None
    if lines[i] == 'NaN': lines[i]=None
  
   # fill l_fiche field
  # print lines[3]
   n_fiche.append(lines[3])
   if len(n_fiche) > 1:
    if (n_fiche[-1] == n_fiche[-2]): 
     l_fiche = l_fiche + 1
    else:
     l_fiche = 1
   # print lines[3],l_fiche
   # formatting
  
   id_navire = vms_navire[vms_navire[:,1] == lines[0].replace('\xc3\xaa','e').replace(' ','').title(),0][0]

   if lines[2] != None:
    date = lines[2].lstrip().split('/')
    date = "20"+date[2]+'-'+date[0]+'-'+date[1]
   # print date
  
   if lines[4] != None:
    time = lines[4].lstrip().split('h')
    time = time[0]+':'+time[1]
   # print time
  
   if lines[5] != None:
    deg = float(lines[5].split()[0])
    min = float(lines[5].split()[1])
    lat = deg+min/60.
    if lines[6] == 'S': lat = -1*lat
   else:
    deg = np.nan 
    min = np.nan
    lat = np.nan
  
   if lines[7] != None:
    deg = float(lines[7].split()[0])
    min = float(lines[7].split()[1])
    lon = deg+min/60.
    if lines[8] == 'O': lon = -1*lon
   else:
    deg = np.nan
    min = np.nan 
    lon = np.nan
  # print deg, min
 
   t_activite = peche_sql.convertID(os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/seiners/seiners_T_ACTIVITE_CONV.csv',lines[10].strip(' '))
   if lines[16] != None:
    t_systeme = str(peche_sql.findID(lines[16],file_t_systeme,1))
   else:
    t_systeme = None

   if lines[15] != None:
    t_detection = str(peche_sql.findID(lines[15],file_t_detection,1))
   else:
    t_detection = None

   comment = lines[14]
 
   if str(lat) != 'nan' and str(lon) != 'nan':
    ## Execute a command: this creates a new table
    query = "INSERT INTO seiners.route (username, id_navire, maree, date, n_route, l_route, time, speed, t_activite, t_neighbours, temperature, windspeed, location, comment, t_systeme, t_detection) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,ST_GeomFromText('POINT(%s %s)',4326),%s,%s,%s);"
   
    print query
    cur.execute(query,('jmensa', id_navire, lines[1], date, lines[3], str(l_fiche), time, lines[9], t_activite, lines[11], lines[12], lines[13], lon,lat, comment, t_systeme, t_detection))
    conn.commit()
  
cur.close()
conn.close()
