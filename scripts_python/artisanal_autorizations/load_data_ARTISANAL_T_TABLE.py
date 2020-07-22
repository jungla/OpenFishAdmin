import psycopg2
import numpy as np
import csv

scheme = 'artisanal'

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#conn = psycopg2.connect("dbname=geospatialdb user=postgres host=212.237.6.69 password=alpha8etA123!")
#
## Open a cursor to perform database operations
cur = conn.cursor()

table_names = ['T_STRATA'.lower(),'T_STATUS'.lower(),'T_NATIONALITY'.lower(),'T_GEAR'.lower(),'T_LICENSE'.lower(),'T_ZONE'.lower(),'T_COOP'.lower(),'T_CARD'.lower(),'T_IMMATRICULATION'.lower()]

for table_name in table_names:

 path = '../CSV_data/artisanal_autorizations/'
 filename = 'artisanal_'+table_name.upper()+'.csv_out'


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
