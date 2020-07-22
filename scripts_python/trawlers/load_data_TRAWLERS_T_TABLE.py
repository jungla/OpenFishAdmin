import psycopg2
import numpy as np
import csv
import os

scheme = 'trawlers'

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

table_names = ['T_FLEET'.lower(),'T_SEX'.lower(),'T_MATURITY'.lower(),'T_REJETE'.lower(),'T_MEASURE'.lower(),'T_CO'.lower(),'T_CONDITION'.lower(),'T_RING'.lower(),'T_TAILLE_POI'.lower(),'T_TAILLE_CRE'.lower()]

for table_name in table_names:

 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/'
 filename = 'trawlers_'+table_name.upper()+'.csv_out'

 with open(path+filename, 'rU') as csvfile:
  spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')

  for lines in spamreader:
   lines[0] = lines[0].replace('\xef\xbb\xbf','')
   query = "INSERT INTO "+scheme+"."+table_name+" (id, "+table_name[2:]+") VALUES (%s, %s)"
   print query
   cur.execute(query,(lines[0],lines[1]))
 
 conn.commit()
 
cur.close()
conn.close()
