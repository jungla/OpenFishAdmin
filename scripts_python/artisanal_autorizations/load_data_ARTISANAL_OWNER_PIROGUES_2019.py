import psycopg2
import numpy as np
import peche_sql
import csv
import re

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

file_csv = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_OWNER_PIROGUE_2019.csv_out'
file_t_card = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_CARD.csv_out'
file_t_nationality = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_NATIONALITY.csv_out'
file_t_gear = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_GEAR.csv_out'
file_t_site = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_SITE.csv_out'
file_t_site_obb = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_SITE_OBB.csv_out'
file_t_license = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_LICENSE.csv_out'

with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next() 

 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None 
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None
 
  last_name = peche_sql.title(lines[0])
  first_name = peche_sql.title(lines[1])
  t_nationality = peche_sql.findID(lines[2],file_t_nationality,1)

  if lines[3] == None:
   t_card = 99
  else:
   t_card = peche_sql.findID(lines[3],file_t_card,1) 

  idcard = peche_sql.stripList(lines[4],[' ','.',':'])
  address = lines[5]
  tel = peche_sql.stripList(lines[6],[' ','.',':','-'])

  query = "SELECT * FROM artisanal.owner WHERE UPPER(first_name) = UPPER(%s) AND UPPER(last_name) = UPPER(%s) AND username = 'jmensa'"
  cur.execute(query, (first_name, last_name))
  result = cur.fetchall()

  if len(result) != 0:
#   print result[0][:]
   query = "UPDATE artisanal.owner SET first_name = %s, last_name = %s, t_nationality = %s, t_card = %s, idcard = %s, address = %s WHERE id = %s"
   cur.execute(query,(first_name, last_name, t_nationality, t_card, idcard, address, result[0][0]))
   id_owner = result[0][0]
  else:
   query = "INSERT INTO artisanal.owner (username, first_name, last_name, t_nationality, t_card, idcard, telephone, address) VALUES (%s, %s, %s, %s, %s, %s, %s, %s) RETURNING id"
   cur.execute(query,('jmensa',first_name,last_name,t_nationality,t_card,idcard,tel,address))
   id_owner = cur.fetchall()[0][0]

# PIROGUE

  name = lines[7]
  immatriculation = lines[8]
  
  if lines[9] == 'bois':
   t_pirogue = '0'
  elif lines[9] == 'plastique':
   t_pirogue = '1'
  else:
   t_pirogue = None

  if lines[10] is not None:
   length = round(float(lines[10]))
  else:
   length = None

  query = "SELECT * FROM artisanal.pirogue WHERE UPPER(immatriculation) = UPPER(%s) AND username = 'jmensa'"
  cur.execute(query, (immatriculation,))
  result = cur.fetchall()
  
  if len(result) != 0:
   id_pirogue = result[0][0]
#   print result[0][:]
   query = "UPDATE artisanal.pirogue SET name = %s, t_pirogue = %s, id_owner = %s, length = %s WHERE id = %s"
   cur.execute(query,(name, t_pirogue, id_owner, length, id_pirogue))
  else:
   query = "INSERT INTO artisanal.pirogue (username, name, immatriculation, t_pirogue, id_owner, length) VALUES (%s, %s, %s, %s, %s, %s) RETURNING id" 
   cur.execute(query,('jmensa', name, immatriculation, t_pirogue, id_owner, length))
   id_pirogue = cur.fetchall()[0][0]

# AUTHORIZATION

  date_v = '2019'

  t_license = peche_sql.findID(lines[15],file_t_license,1) 
  t_license_2 = peche_sql.findID(lines[16],file_t_license,1)
  t_gear = peche_sql.findID(lines[17],file_t_gear,1)
  t_gear_2 = peche_sql.findID(lines[18],file_t_gear,1)
  t_site = peche_sql.findID_site(lines[11],file_t_site,0)
  t_site_obb = peche_sql.findID_site(lines[12],file_t_site_obb,0)

  if lines[19] is not None:
   if len(lines[19].split('/')) > 1:
    mesh_min = lines[19].split('/')[0]
    mesh_max = lines[19].split('/')[1]
   else:
    mesh_min = lines[19]
  else:
   mesh_min = None
   mesh_max = None
  engine_brand = lines[13]
  engine_cv = lines[14]
  comments = lines[20]
 
#  date_v, t_license, t_license_2, t_gear, t_gear_2, t_site, t_site_obb, mesh_min, mesh_max, engine_brand, engine_cv, id_pirogue, comments

  query = "SELECT * FROM artisanal.license \
LEFT JOIN artisanal.pirogue ON artisanal.license.id_pirogue = artisanal.pirogue.id \
WHERE UPPER(immatriculation) = UPPER(%s) AND extract(year FROM date_v) = '2019' AND license.username = 'jmensa'"                              
  cur.execute(query, (immatriculation,))                                                                                                    
  result = cur.fetchall()                                                                                                                   
                                                                                                                                            
  if len(result) != 0:                                                                                                                      
   query = "UPDATE artisanal.license SET date_v = %s, t_license = %s, t_license_2 = %s, t_gear = %s, t_gear_2 = %s, t_site = %s, t_site_obb = %s, mesh_min = %s, mesh_max = %s, engine_brand = %s, engine_cv = %s, id_pirogue = %s, comments = %s WHERE id = %s"                               
   cur.execute(query,('01-01-'+date_v, t_license, t_license_2, t_gear, t_gear_2, t_site, t_site_obb, mesh_min, mesh_max, engine_brand, engine_cv, id_pirogue, comments, result[0][0]))  
  else:
   query = "INSERT INTO artisanal.license (username, date_v, t_license, t_license_2, t_gear, t_gear_2, t_site, t_site_obb, mesh_min, mesh_max, engine_brand, engine_cv, id_pirogue, comments) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)" 
   cur.execute(query,('jmensa', '01-01-'+date_v, t_license, t_license_2, t_gear, t_gear_2, t_site, t_site_obb, mesh_min, mesh_max, engine_brand, engine_cv, id_pirogue, comments)) 

conn.commit()
cur.close()
conn.close()
