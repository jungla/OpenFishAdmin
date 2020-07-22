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

file_t_fleet = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/trawlers_T_FLEET.csv_out'

years = ['2017']

for year in years:

 path = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/'+year+'/'
 filename = 'trawlers_ROUTE_ACCIDENTELLE.csv_out'

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
   # print date

   if lines[1] != None:
    navire = lines[1]
   # print date

   if lines[2] != None:
    maree = lines[2]
   # print date
  
   if lines[3] != None:
    date = lines[3]
   # print date

   if lines[4] != None:
    t_co = lines[4]
   # print date

   lance = lines[5]

   if lines[6] != None:
    heure = lines[6]
   # print time
  
   if lines[8] != None:
    lat = lines[8]
    if lines[7] < 0: lat = -1*lat
 
   if lines[10] != None:
    lon = lines[10]
    if lines[9] < 0: lon = -1*lon
 
   if lon != '' and lat != '':
 
    ## Execute a command: this creates a new table
    query = "INSERT INTO trawlers.route_accidentelle (username, navire, maree, t_fleet, date, t_co, lance, heure, location) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,ST_GeomFromText('POINT(%s %s)',4326));"
   #   
    #print query
    cur.execute(query,('jmensa',navire, maree, t_fleet, date, t_co, lance, heure, float(lon), float(lat)))
    conn.commit()

   else:
    print lon, lat 
   
cur.close()
conn.close()
