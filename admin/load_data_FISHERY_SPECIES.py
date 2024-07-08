import psycopg2
import numpy as np
import csv
import sys

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
cur = conn.cursor()

path = sys.argv[1]
file = sys.argv[2]
filename = path+file+'.csv'

with open(filename, 'rt') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 header = next(spamreader, None)

 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] == '':
    lines[i] = None
   else:
    lines[i] = lines[i].lstrip() 
    lines[i] = lines[i].rstrip() 
 # francaise, family, genus, FAO, OBS, category, IUCN 

  query = "INSERT INTO fishery.species (francaise, family, genus, species, FAO, OBS, category, IUCN) VALUES (%s, UPPER(%s), %s, %s, %s, %s, %s, %s)"
  print(query)
  cur.execute(query,(lines[:8]))

conn.commit()

cur.close()
conn.close()
