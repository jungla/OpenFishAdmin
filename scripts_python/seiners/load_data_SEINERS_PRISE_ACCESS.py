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

file_id_species = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/list_of_species.csv_out'

def findUUID_species(value,conn):
 cur = conn.cursor()
 if (value is not None):
  value = value.lstrip()
  query = "SELECT id FROM fishery.species WHERE OBS = '"+value+"' LIMIT 1;"
  #print query
  cur.execute(query)
  result = cur.fetchall()
  conn.commit()
  return result

for year in years:

 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/seiners/'+year+'/'
 filename = 'seiners_PRISE_ACCESS.csv_out'

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
  
   if lines[6]:
    h_d = lines[6].split('h')
    h_d = h_d[0]+':'+h_d[1]
   if lines[7]:
    h_c = lines[7].split('h')
    h_c = h_c[0]+':'+h_c[1]

   if lines[8]:
    h_f = lines[8].split('h')
    h_f = h_f[0]+':'+h_f[1]
 
   id_route = peche_sql.findUUID_route(lines[0],lines[4],lines[5],conn) 

   id_species = findUUID_species(lines[13],conn)
   if len(id_species) > 0:
    id_species = str(id_species[0][0])
 
    print id_species
    t_raison = peche_sql.convertID(os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/seiners/seiners_T_RAISON_CONV.csv',lines[15])
  
     ## Execute a command: this creates a new table
    query = "INSERT INTO seiners.prise_access(id_route, username, maree, n_calee, t_type, t_zee, n_route, l_route, h_d, h_c, h_f, vitesse, direction, d_max, t_prise, id_species, t_action, t_raison, poids, n_ind, taille, photo, remarque) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
   
    print query
    cur.execute(query, (id_route,'jmensa',lines[0],lines[1],lines[2],lines[3],lines[4],lines[5],h_d,h_c,h_f,lines[9],lines[10],lines[11],lines[12],id_species,lines[14],t_raison,lines[16],lines[17],lines[18],lines[19],lines[20]))
    conn.commit()
   else:
    print id_species, lines[13]   
cur.close()
conn.close()
