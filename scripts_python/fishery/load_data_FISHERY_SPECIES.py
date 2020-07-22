import psycopg2
import numpy as np
import csv

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

path = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/fishery/'
filename = 'fishery_SPECIES.csv_out'


with open(path+filename, 'rU') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next()

 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] == '':
    lines[i] = None
   else:
    lines[i] = lines[i].lstrip() 
    lines[i] = lines[i].rstrip() 
 # francaise, family, genus, FAO, OBS, category, IUCN 

  query = "INSERT INTO fishery.species (francaise, family, genus, species, FAO, OBS, category, IUCN) VALUES (%s, UPPER(%s), %s, %s, %s, %s, %s, %s)"
  print query
  cur.execute(query,(lines[:8]))

conn.commit()

cur.close()
conn.close()
