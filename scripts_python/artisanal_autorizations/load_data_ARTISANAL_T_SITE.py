import psycopg2
import numpy as np
import csv
import peche_sql

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#conn = psycopg2.connect("dbname=geospatialdb user=postgres host=212.237.6.69 password=alpha8etA123!")
#
## Open a cursor to perform database operations
cur = conn.cursor()

file_csv = '../../CSV_data/artisanal_autorizations/artisanal_T_SITE.csv_out'

#file_sql = '/Volumes/Storage/Google_Drive/Gabon_Bleu/_database/scripts_load_create_tables/scripts_SQL/artisanal/cTable_artisanal_t_site.sql'

# drop and create table

#cur.execute(open(file_sql, "r").read().decode("utf-8-sig").encode("utf-8").replace('\n',' '))

with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
# spamreader.next()
 
 for lines in spamreader:
  print lines
  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None

  lines[0] = lines[0].replace('\xef\xbb\xbf','') 
  
  if lines[3] != None and lines[4] != None:
   query = "INSERT INTO artisanal.t_site (site, strata, region, code, location) VALUES (%s, %s, %s, %s, ST_GeomFromText('POINT(%s %s)',4326))"
   print query
   cur.execute(query,(peche_sql.title(lines[0]),peche_sql.title(lines[1]),peche_sql.title(lines[2]),lines[5],float(lines[3]),float(lines[4])))
  else:
   query = "INSERT INTO artisanal.t_site (site, strata, region, code) VALUES (%s, %s, %s, %s)"
   print query
   cur.execute(query,(peche_sql.title(lines[0]),peche_sql.title(lines[1]),peche_sql.title(lines[2]),lines[5]))

conn.commit()

cur.close()
conn.close()
