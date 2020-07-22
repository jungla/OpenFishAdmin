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

file_obs = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/fishery/fishery_SPECIES_CONV.csv_out'

for year in years:

 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/seiners/'+year+'/'
 filename = 'seiners_THON_REJETE.csv_out'

 with open(path+filename, 'rb') as csvfile:
  spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
  spamreader.next()

  for lines in spamreader:
   # data formatting
   for i in range(len(lines)):
    if lines[i] == '': lines[i]=None 
    if lines[i] == '\n': lines[i]=None 
    if lines[i] == '-9999': lines[i]=None
    if lines[i] == 'NA': lines[i]=None
    if lines[i] == 'NaN': lines[i]=None
  
   # round degree current
   if lines[10]:
    lines[10] = round(float(lines[10]))
  
   h_d = None
   h_c = None
   h_f = None
  
   if lines[6]: h_d = lines[6]
   if lines[7]: h_c = lines[7]
   if lines[8]: h_f = lines[8]

   id_route = peche_sql.findUUID_route(lines[0],lines[4],lines[5],conn) 


   obs_species = peche_sql.convert_OBS(lines[12], file_obs)
   id_species = peche_sql.findUUID_species(obs_species,conn)

   if len(id_species) > 0:
    id_species = str(id_species[0][0])

    ## Execute a command: this creates a new table
    query = "INSERT INTO seiners.thon_rejete (id_route, username, maree, t_zee, n_calee, t_type, n_route, l_route, h_d, h_c, h_f, vitesse, direction, d_max, id_species, t_categorie, t_raison, poids, monte, photo, remarque) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
   
    print query
    cur.execute(query, (id_route,'jmensa',lines[0],lines[1],lines[2],lines[3],lines[4],lines[5],h_d,h_c,h_f,lines[9],lines[10],lines[11],id_species,lines[13],lines[14],lines[15],lines[16],lines[17],lines[18]))
    conn.commit()
   else:
    print lines[12]
cur.close()
conn.close()
