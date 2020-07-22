import psycopg2
import numpy as np
import peche_sql
import csv
import datetime
import os

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

file_csv = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_CAPTURES_MYB.csv_out'
file_t_nationality = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_NATIONALITY.csv_out'
file_t_site = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_SITE.csv_out'

def findUUID_license(value,conn):
 cur = conn.cursor()
 if (value is not None):
  value = value.lstrip().lower()
#  print value
  query = "SELECT license.id FROM artisanal.license LEFT JOIN artisanal.pirogue ON license.id_pirogue = pirogue.id WHERE  pirogue.immatriculation = '"+value+"' LIMIT 1;"
  #print query
  cur.execute(query)
  result = cur.fetchall()
  conn.commit()
  return result
 else:
  return None

def findUUID_species_name(value_1,value_2,conn):
 cur = conn.cursor()
 if (value_1 is not None and value_2 is not None):
  value_1 = value_1.strip().lower()
  value_2 = value_2.strip().lower()
#  print value
  query = "SELECT id FROM fishery.species WHERE LOWER(genus) = '"+value_1+"' AND LOWER(species) = '"+value_2+"' LIMIT 1;"
  #print query
  cur.execute(query)
  result = cur.fetchall()
  conn.commit()
  return result
 else:
  return None

with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')

 spamreader.next() 

 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None 
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None

  if lines[0] is not None and lines[1] is not None:
   date = datetime.datetime.strptime(lines[0]+' '+lines[1],'%m/%d/%y %H:%M')
   datetime_d = date
  else:
   datetime_d = None

  if lines[2] is not None and lines[3] is not None:
   date = datetime.datetime.strptime(lines[2]+' '+lines[3],'%m/%d/%y %H:%M')
   datetime_r = date
  else:
   datetime_r = None

  obs_name = lines[4]
  t_site = peche_sql.findID_site(lines[5],file_t_site,5)
#  t_site = peche_sql.findID(lines[5],file_t_site,5) 

  license_id = findUUID_license(lines[8],conn)
  if len(license_id) == 0:
   license_id = None

  immatriculation = lines[8]
  t_gear = lines[10]

  net_s = lines[11]

  net_l = lines[13]

  cap_tot = lines[19]

  if lines[15] is not None:
   species_name = lines[15][lines[15].find("(")+1:lines[15].find(")")].lstrip().rstrip()
   id_species = findUUID_species_name(species_name.split(' ')[0],species_name.split(' ')[1],conn) 
   if len(id_species)>0:
    id_species = id_species[0][0]
   else:
    id_species = None
    print lines[15]
  else:
   id_species = None

  sample_s = lines[16]
  n_ind = lines[17]
 
#   username, datetime_d, datetime_r, obs_name, t_site, license_id, license_num, t_gear, net_s, net_l, cap_tot, id_species, sample_s, n_ind, gps_file 

  query = "INSERT INTO artisanal.captures (username, datetime_d, datetime_r, obs_name, t_site, license_id, immatriculation, t_gear, net_s, net_l, cap_tot, id_species, sample_s, n_ind) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
#  print query
  cur.execute(query,('jmensa', datetime_d, datetime_r, obs_name, t_site, license_id, immatriculation, t_gear, net_s, net_l, cap_tot, id_species, sample_s, n_ind))

conn.commit()

cur.close()
conn.close()
