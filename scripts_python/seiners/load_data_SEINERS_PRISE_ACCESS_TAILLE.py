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
years = ['2016','2017']

file_id_species = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/seiners/seiners_T_ESPECE.csv_out'

file_obs = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/fishery/fishery_SPECIES_CONV.csv_out'

def convert_OBS(obs_in, file_obs):
 with open(file_obs, 'rb') as csvfile:
  spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
  spamreader.next()

  list_out = []
  list_in = []

  for lines in spamreader:
   list_out.append(lines[1])
   list_in.append(lines[0])

 if obs_in in list_in:
  obs_out = list_out[list_in == obs_in]
 else:
  obs_out = obs_in

 return obs_out

for year in years:

 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/seiners/'+year+'/'

 filename = 'seiners_PRISE_ACCESS_TAILLE.csv_out'

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

   id_route = peche_sql.findUUID_route(lines[0],lines[2],lines[3],conn) 
 
  # replace species from table


   obs_species = convert_OBS(lines[4], file_obs) 

   id_species = peche_sql.findUUID_species(obs_species,conn)

   if len(id_species) > 0:
    id_species = str(id_species[0][0])
  
    t_measure = peche_sql.convertID(os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/seiners/seiners_T_MEASURE_CONV.csv',lines[5])
   
     ## Execute a command: this creates a new table
    query = "INSERT INTO seiners.prise_access_taille(id_route, username, maree, n_cale, n_route, l_route, id_species, t_measure, taille, poids, t_sexe, t_capture, t_relache, photo, remarque) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
   
#    print query
    cur.execute(query, (id_route,'jmensa',lines[0],lines[1],lines[2],lines[3],id_species,lines[5],lines[6],lines[7],lines[8],lines[9],lines[10],lines[11],lines[12]))
    conn.commit()
   else:
    print lines[4], obs_species
cur.close()
conn.close()
