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

file_csv = path+'/2018/new_2018.csv_out'
file_t_pirogue = path+'/artisanal_T_PIROGUE.csv_out'
file_t_strata = path+'/artisanal_T_STRATA.csv_out'
file_t_license = path+'/artisanal_T_LICENSE.csv_out'
file_t_site = path+'/artisanal_T_SITE.csv_out'
file_t_gear = path+'/artisanal_T_GEAR.csv_out'
file_t_site_obb = path+'/artisanal_T_SITE_OBB.csv_out'
file_t_coop = path+'/artisanal_T_COOP.csv_out'
file_t_nationality = path+'/artisanal_T_NATIONALITY.csv_out'
file_t_card = path+'/artisanal_T_CARD.csv_out'

with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next() 

 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None 
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None

# INSERT OWNER

  last_name = lines[8]	
  first_name = lines[9]
  t_nationality = peche_sql.findID(lines[10],file_t_nationality,1)
  t_card = peche_sql.findID(lines[11],file_t_card,1)
  idcard = lines[12]
  address = lines[13]
  telephone = lines[14]

  query = "INSERT INTO artisanal.owner (username, last_name, first_name, t_nationality, t_card, idcard, address, telephone) VALUES (%s, %s, %s, %s, %s, %s, %s, %s) RETURNING id"
#   print query
  cur.execute(query,('jmensa', last_name, first_name, t_nationality, t_card, idcard, address, telephone))
  id_owner = cur.fetchall()[0][0]

# INSERT PIROGUE

  immatriculation = lines[0]	
  name = lines[2]
  t_pirogue = peche_sql.findID(lines[3],file_t_pirogue,1)

  query = "INSERT INTO artisanal.pirogue (username, name, immatriculation, t_pirogue, id_owner) VALUES (%s, %s, %s, %s, %s) RETURNING id"
#   print query
  cur.execute(query,('jmensa', name, immatriculation, t_pirogue, id_owner))
  id_pirogue = cur.fetchall()[0][0]

# INSERT LICENSE
  t_strata = peche_sql.findID(lines[1],file_t_strata,1)
  t_site_obb = peche_sql.findID_site(lines[4],file_t_site_obb,0)
  t_site = peche_sql.findID_site(lines[5],file_t_site,0)
  engine_brand = lines[6] 
  engine_cv = lines[7] 
  t_license = peche_sql.findID(lines[15],file_t_license,1)
  t_gear = peche_sql.findID(lines[16],file_t_gear,1)
  t_license_2 = peche_sql.findID(lines[17],file_t_license,1)
  t_gear_2 = peche_sql.findID(lines[18],file_t_gear,1)
  t_coop = peche_sql.findID(lines[19],file_t_coop,1)

  date_v = '01/01/2018' 
  query = "INSERT INTO artisanal.license (username, date_v, id_pirogue, t_strata, t_license, t_gear, t_site_obb, t_license_2, t_gear_2, t_site, t_coop) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)" 
#   print query
  cur.execute(query,('jmensa', date_v, id_pirogue, t_strata, t_license, t_gear, t_site_obb, t_license_2, t_gear_2, t_site, t_coop))

conn.commit()

cur.close()
conn.close()
