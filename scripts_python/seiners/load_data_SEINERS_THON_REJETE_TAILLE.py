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

for year in years:

 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/seiners/'+year+'/'
 filename = 'seiners_THON_REJETE_TAILLE.csv_out'

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
 
   id_route = peche_sql.findUUID_route(lines[0],lines[3],lines[4],conn)
 
   maree = lines[0]
   n_calee = lines[1]
   n_route = lines[3]   
   l_route = lines[4]   

   obs_species = peche_sql.convert_OBS(lines[2], file_obs)

   id_species = peche_sql.findUUID_species(obs_species,conn)

   if len(id_species) > 0:
    id_species = str(id_species[0][0])


     ## Execute a command: this creates a new table
    query = "INSERT INTO seiners.thon_rejete_taille (id_route, username, maree, id_species, n_calee, n_route, l_route, "
    
    taille = range(9,101)+[110,111,112,135,138,139,140,144,145,146,147,148,149,150,151,154,155,156,157,158,159,160,170]
    
    for i in taille[:-1]:
     query = query + "c"+str(i).zfill(3)+", "
    query = query + "c"+str(taille[-1]).zfill(3)
   
    query = query + ") VALUES (%s, %s, %s, %s, %s, %s, %s,"
   
    for i in range(len(taille[:-1])):
     query = query + "%s, "
    query = query + "%s"
   
    query = query + ");"
   
    print query
    cur.execute(query, [id_route]+['jmensa'] + [maree] + [id_species] + [n_calee] + [n_route] + [l_route] + lines[5:])
    conn.commit()
 
   else:
    print lines[2]

cur.close()
conn.close()

