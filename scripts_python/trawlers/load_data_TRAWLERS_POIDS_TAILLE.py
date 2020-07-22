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

file_obs = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/fishery/fishery_SPECIES_CONV.csv_out'

years = ['2016']

for year in years:

 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/'+year+'/'
 filename = 'trawlers_POIDS_TAILLE.csv_out'

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

   maree = lines[0]

   if lines[1] is not None:
    obs_species = peche_sql.convert_OBS(lines[1], file_obs)

    id_species = peche_sql.findUUID_species(obs_species,conn)

    if len(id_species) > 0:
     id_species = str(id_species[0][0])

     t_measure = lines[2]
  
     taille = lines[3]
  
     p1 = lines[4]
     p2 = lines[5]
     p3 = lines[6]
     p4 = lines[7]
     p5 = lines[8]
  
     ## Execute a command: this creates a new table
     query = "INSERT INTO trawlers.poids_taille (username, maree, id_species, t_measure, taille, p1, p2, p3, p4, p5) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);"
  
#     print query
     cur.execute(query,('jmensa', maree, id_species, t_measure, taille, p1, p2, p3, p4, p5))
     conn.commit()
    else:
     print lines[1]
   
cur.close()
conn.close()
