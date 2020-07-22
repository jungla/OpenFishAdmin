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

years = ['2016','2017','2018']
#years = ['2018']

def findUUID_route(maree,lance,conn):
 if maree is not None and lance is not None:
  cur = conn.cursor()
  query = "SELECT id FROM trawlers.route WHERE maree='"+maree+"' AND lance='"+lance+"' "
#  print query
  cur.execute(query)
  result = cur.fetchall()
  if len(result) == 0:
   return result
  else:
   return result[0][0]
 else:
  result = None
 return result

for year in years:

 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/'+year+'/'
 filename = 'trawlers_P_LANCE.csv_out'

 with open(path+filename, 'rU') as csvfile:
  spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
  spamreader.next()

  for lines in spamreader:
   lines[0] = lines[0].replace('\xef\xbb\xbf','')
   for i in range(len(lines)):
    if lines[i] == '': lines[i]=None
    if lines[i] == ' ': lines[i]=None
    if lines[i] == '\n': lines[i]=None
    if lines[i] == '-9999': lines[i]=None
    if lines[i] == 'NA': lines[i]=None
    if lines[i] == 'NaN': lines[i]=None
    if lines[i] == '#VALUE!': lines[i]=None

   if lines[0] != None:
    maree = lines[0]
   # print date

   if lines[1] != None:
    lance = lines[1]
   # print date

   id_route = findUUID_route(maree,lance,conn)

   if lines[2] is not None:
    obs_species = peche_sql.convert_OBS(lines[2], file_obs)
 
    id_species = peche_sql.findUUID_species(obs_species,conn)
 
    if len(id_species) > 0:
     id_species = str(id_species[0][0])
 
     ## Execute a command: this creates a new table
  
     c0_cre = lines[3]
     c1_cre = lines[4]
     c2_cre = lines[5] 
     c3_cre = lines[6] 
     c4_cre = lines[7]
     c5_cre = lines[8]
     c6_cre = lines[9]
     c7_cre = lines[10]
     c8_cre = lines[11]
     c9_cre = lines[12]
     c0_poi = lines[13]
     c1_poi = lines[14]
     c2_poi = lines[15]
     c3_poi = lines[16]
     c4_poi = lines[17]
     c5_poi = lines[18]
     c6_poi = lines[19]
  
     if len(id_route) > 0:
  
      query = "INSERT INTO trawlers.p_lance (username, maree, lance, id_species, id_route, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);"
      #   
 #     print query
      cur.execute(query,('jmensa', maree, lance, id_species, id_route, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi))
      conn.commit()
 
    else:
 
     print lines[2] 
    
cur.close()
conn.close()
