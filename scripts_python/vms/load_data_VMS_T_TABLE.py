import psycopg2
import numpy as np
import csv
import os

scheme = 'themis'

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

table_names = ['T_NAVIRE'.lower()]

for table_name in table_names:

 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/themis/'
 filename = 'themis_'+table_name.upper()+'.csv_out'

 with open(path+filename, 'rU') as csvfile:
  spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
  spamreader.next()

  for lines in spamreader:
 
   query = "INSERT INTO "+scheme+"."+table_name+" (id, "+table_name[2:]+") VALUES (%s, %s)"
   print query
   cur.execute(query,(lines[0],lines[1][:]))
 
 conn.commit()
 
cur.close()
conn.close()
