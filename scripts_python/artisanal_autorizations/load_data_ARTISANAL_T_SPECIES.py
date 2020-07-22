import psycopg2
import numpy as np
import csv

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

file_species = '/Volumes/Storage/Google_Drive/Gabon_Bleu/_database/CSV_data/list_of_species.csv'


cur.execute('DROP TABLE artisanal.t_species')
conn.commit()

query = "CREATE TABLE artisanal.t_species (id serial PRIMARY KEY, common_name varchar(100), family varchar(100), genus varchar(100), species varchar(100), fao_code varchar(100));"

print query

cur.execute(query) 

conn.commit()

with open(file_species, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next()
 
 for lines in spamreader:
 
  query = "INSERT INTO artisanal.t_species (common_name, family, genus, species, fao_code) VALUES (%s, %s, %s, %s, %s)"
  print query
  cur.execute(query,(lines[0],lines[1],lines[2],lines[3],lines[4]))

conn.commit()

cur.close()
conn.close()
