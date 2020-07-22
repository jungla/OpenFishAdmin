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
 filename = 'trawlers_CAPTURES_TORTUE.csv_out'

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
   # printnavire  date

   if lines[1] != None:
    navire = lines[1]
   else:
    navire = None
   # print date

   id_navire = vms_navire[vms_navire[:,1] == lines[1].replace('\xc3\xaa','e').replace(' ','').title(),0][0]

   if lines[2] != None:
    maree = lines[2]
   else:
    maree = None
   # print date
  
   if lines[3] != None:
    date = lines[3]
   else:
    date = None
   # print date

   if lines[4] != None:
    t_co = lines[4]
    if t_co == 'C': t_co = 0   
    if t_co == 'O': t_co = 1   
    if t_co == 'Obs': t_co = 1   
   else:
    t_co = None


   # print date

   lance = lines[5]

   if lines[6] != None:
    time = lines[6]
   else:
    time = None 
  # print time
  
   if lines[8] != None:
    lat = lines[8]
    if lines[7] < 0: lat = -1*lat
 
   if lines[10] != None:
    lon = lines[10]
    if lines[9] < 0: lon = -1*lon
 
   if lines[10] is not None  and lines[8] is not None:
 
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
#    print query
    cur.execute(query,('jmensa', id_navire, maree, t_fleet, date, t_co, lance, time))
    conn.commit()

   query_id = "SELECT id FROM trawlers.route_accidentelle ORDER BY datetime DESC LIMIT 1;"
   cur.execute(query_id)
   id_route = cur.fetchall()[0][0]


  
   # load CAPTURES
   if lines[11] is not None:
    obs_species = peche_sql.convert_OBS(lines[11], file_obs)

    id_species = peche_sql.findUUID_species(obs_species,conn)

    if len(id_species) > 0:
     id_species = str(id_species[0][0])
  
     n_ind = lines[13]
   
     t_sex = lines[12]
   
     if t_sex == 'M': 
      t_sex = 0
     elif t_sex == 'F': 
      t_sex = 1
     else: 
      t_sex = None
  
     length = lines[16]
  
     width = lines[17]
  
     ring = lines[18]

     if ring == 'O': ring = True
     if ring == 'F': ring = False 
  
     if lines[20] != None:
      if position_1 == 'AG': position_1 = 0   
      if position_1 == 'AD': position_1 = 1   
      if position_1 == 'PG': position_1 = 2   
      if position_1 == 'PD': position_1 = 3   
     else:
      position_1 = None
  
     code_1 = lines[19]
  
     if lines[22] != None:
      if position_2 == 'AG': position_2 = 0   
      if position_2 == 'AD': position_2 = 1   
      if position_2 == 'PG': position_2 = 2   
      if position_2 == 'PD': position_2 = 3   
     else:
      position_2 = None
  
     code_2 = lines[21]
  
     t_capture = lines[23]
     if t_capture == 'V': t_capture = 0
     if t_capture == 'C': t_capture = 1
     if t_capture == 'M': t_capture = 2

     resumation = lines[24]
     if resumation == 'O': resumation = True
     if resumation == 'N': resumation = False

     resumation_res = lines[25]
     if resumation_res == 'O': resumation_res = True
     if resumation_res == 'N': resumation_res = False
      
     t_relache = lines[26]
     if t_relache == 'V': t_relache = 0
     if t_relache == 'C': t_relache = 1
     if t_relache == 'M': t_relache = 2
  
     preleve = lines[27]
  
     camera = lines[14]
  
     photo = lines[15]
  
     remarque = lines[28] 
  
  #   n_ind, t_sex, length, width, ring, position_1, code_1, position_2, code_2, t_capture, t_relache, resumation, resumation_res, preleve, camera, photo, remarque
  
     query = "INSERT INTO trawlers.captures_tortue (username, id_route, maree, date, time, id_species, n_ind, t_sex, length, width, ring, position_1, code_1, position_2, code_2, t_capture, t_relache, resumation, resumation_res, preleve, camera, photo, remarque) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);"
      #   
 #    print query
     cur.execute(query,('jmensa', id_route, maree, date, time, id_species, n_ind, t_sex, length, width, ring, position_1, code_1, position_2, code_2, t_capture, t_relache, resumation, resumation_res, preleve, camera, photo, remarque))
     conn.commit()

    else:

     print lines[11]

  
cur.close()
conn.close()
