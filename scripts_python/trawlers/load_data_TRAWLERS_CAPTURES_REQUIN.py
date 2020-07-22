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
file_obs = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/fishery/fishery_SPECIES_CONV.csv_out'

query = "SELECT id, navire FROM vms.navire;"

cur.execute(query)
vms_navire = np.asarray(cur.fetchall())

for i in range(len(vms_navire[:,1])):
 vms_navire[i,1] = vms_navire[i,1].replace('\xc3\xaa','e').replace(' ','').title()

years = ['2016','2017','2018']
#years = ['2018']

for year in years:

 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/'+year+'/'
 filename = 'trawlers_CAPTURES_REQUIN.csv_out'

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
    if lines[i] == '#REF!': lines[i]=None

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
    t_co = lines[4]
   if t_co == 'C': t_co = 0   
   if t_co == 'O': t_co = 1   

   # print date

   lance = lines[5]

   time = lines[6]
   # print time
  
   if lines[8] != None:
    lat = lines[8]
    if lines[7] < 0: lat = -1*lat
 
   if lines[10] != None:
    lon = lines[10]
    if lines[9] < 0: lon = -1*lon
 
#   if lines[10] != '' and lines[14] != '':

   if lines[8] is not None and lines[10] is not None:
 
    ## Execute a command: this creates a new table
    query = "INSERT INTO trawlers.route_accidentelle (username, id_navire, maree, t_fleet, date, t_co, lance, time, location) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,ST_GeomFromText('POINT(%s %s)',4326));"
   #   
#    print query
    cur.execute(query,('jmensa', id_navire, maree, t_fleet, date, t_co, lance, time, float(lon), float(lat)))
    conn.commit()
   else:
    ## Execute a command: this creates a new table
    query = "INSERT INTO trawlers.route_accidentelle (username, id_navire, maree, t_fleet, date, t_co, lance, time) VALUES (%s,%s,%s,%s,%s,%s,%s,%s);"
   #   
   # print query
    cur.execute(query,('jmensa', id_navire, maree, t_fleet, date, t_co, lance, time))
    conn.commit()

   query_id = "SELECT id FROM trawlers.route_accidentelle ORDER BY datetime DESC LIMIT 1;"
   cur.execute(query_id)
   id_route = cur.fetchall()[0][0]
   conn.commit()

   # load CAPTURES
 
   if lines[11] is not None:
    obs_species = peche_sql.convert_OBS(lines[11], file_obs)

    id_species = peche_sql.findUUID_species(obs_species,conn)

    if len(id_species) > 0:
     id_species = str(id_species[0][0])

     n_ind = lines[12]
   
     t_sex = lines[13]
   
     if t_sex == 'M': 
      t_sex = 0
     elif t_sex == 'F': 
      t_sex = 1
     else: 
      t_sex = None
  
     taille = lines[16]
  
     poids = lines[17]
  
     t_capture = lines[18]
     if t_capture == 'V' or t_capture == 'v': t_capture = 0
     if t_capture == 'C' or t_capture == 'c': t_capture = 1
     if t_capture == 'M' or t_capture == 'm': t_capture = 2
     if t_capture == 'F' or t_capture == 'f': t_capture = 3
     if t_capture == 'R' or t_capture == 'r': t_capture = 4
  
     t_relache = lines[19]
     if t_relache == 'V' or t_relache == 'v': t_relache = 0
     if t_relache == 'C' or t_relache == 'c': t_relache = 1
     if t_relache == 'M' or t_relache == 'm': t_relache = 2
     if t_relache == 'F' or t_relache == 'f': t_relache = 3
     if t_relache == 'R' or t_relache == 'r': t_relache = 4
  
     preleve = lines[20]
  
     camera = lines[14]
  
     photo = lines[15]
  
     remarque = lines[20] 
  
     #n_ind, t_sex, length, width, ring, position_1, code_1, position_2, code_2, t_capture, t_relache, resumation, resumation_res, preleve, camera, photo, remarque
  
     query = "INSERT INTO trawlers.captures_requin (username, id_route, maree, date, time, lance, id_species, n_ind, t_sex, taille, poids, t_capture, t_relache, preleve, camera, photo, remarque) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);"
     # #   
     #print query
     cur.execute(query,('jmensa', id_route, maree, date, time, lance, id_species, n_ind, t_sex, taille, poids, t_capture, t_relache, preleve, camera, photo, remarque))
     conn.commit()

    else:
     print lines[11] 

cur.close()
conn.close()
