import psycopg2
import numpy as np
import peche_sql
import csv
import re

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#conn = psycopg2.connect("dbname=geospatialdb user=postgres host=212.237.6.69 password=alpha8etA123!")
#
## Open a cursor to perform database operations
cur = conn.cursor()

path = '/home/jean/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal_autorizations'

file_csv = path+'/2018/old_2018.csv_out'
file_t_strata = path+'/artisanal_T_STRATA.csv_out'
file_t_license = path+'/artisanal_T_LICENSE.csv_out'
file_t_site = path+'/artisanal_T_SITE.csv_out'
file_t_gear = path+'/artisanal_T_GEAR.csv_out'
file_t_site_obb = path+'/artisanal_T_SITE_OBB.csv_out'
file_t_coop = path+'/artisanal_T_COOP.csv_out'

with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next() 

 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None 
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None

# PIROGUE

  immatriculation = lines[0]

  query = "SELECT id FROM artisanal.pirogue WHERE immatriculation = %s"
  cur.execute(query, (immatriculation,))
  result = cur.fetchall()

  if len(result) == 0:
   print immatriculation
  else :
   id_pirogue = result[0]
#   print id_pirogue
  
   t_strata = peche_sql.findID(lines[1],file_t_strata,1)
   t_license = peche_sql.findID(lines[2],file_t_license,1)
   t_gear = peche_sql.findID(lines[3],file_t_gear,1)
   t_site_obb = peche_sql.findID_site(lines[4],file_t_site_obb,0)
   t_license_2 = peche_sql.findID(lines[5],file_t_license,1)
   t_gear_2 = peche_sql.findID(lines[6],file_t_gear,1)
   t_site = peche_sql.findID_site(lines[7],file_t_site,0)
   t_coop = peche_sql.findID(lines[8],file_t_coop,1)

   date_v = '01/01/2018' 
   query = "INSERT INTO artisanal.license (username, date_v, id_pirogue, t_strata, t_license, t_gear, t_site_obb, t_license_2, t_gear_2, t_site, t_coop) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)" 
#   print query
   cur.execute(query,('jmensa', date_v, id_pirogue, t_strata, t_license, t_gear, t_site_obb, t_license_2, t_gear_2, t_site, t_coop))

conn.commit()

cur.close()
conn.close()
