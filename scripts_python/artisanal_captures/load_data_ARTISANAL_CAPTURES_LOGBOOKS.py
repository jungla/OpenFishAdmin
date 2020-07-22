import psycopg2
import numpy as np
import peche_sql
import csv
import datetime
import os

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#conn = psycopg2.connect("dbname=geospatialdb user=postgres host=212.237.6.69 password=alpha8etA123!")
#
## Open a cursor to perform database operations
cur = conn.cursor()

file_csv = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal_captures/artisanal_CAPTURES_LOGBOOKS_OUT.csv'
file_t_site = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal_captures/artisanal_T_SITE.csv_out'
file_t_gear = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal_captures/artisanal_T_GEAR.csv_out'

#file_csv = os.environ['HOME']+'/_database/CSV_data/artisanal_captures/artisanal_CAPTURES_LOGBOOKS_OUT.csv'
#file_t_site = os.environ['HOME']+'/_database/CSV_data/artisanal_captures/artisanal_T_SITE.csv_out'
#file_t_gear = os.environ['HOME']+'/_database/CSV_data/artisanal_captures/artisanal_T_GEAR.csv_out'

print file_csv

def findUUID_license(value,conn):
 cur = conn.cursor()
 if (value is not None):
  value = value.lstrip().lower()
#  print value
  query = "SELECT license.id FROM artisanal.license LEFT JOIN artisanal.pirogue ON license.id_pirogue = pirogue.id WHERE pirogue.immatriculation = '"+value+"' LIMIT 1;"
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

  if lines[2] is not None:
   datetime_d = datetime.datetime.strptime(lines[2],'%m/%d/%Y')
  else:
   datetime_d = None

  if lines[3] is not None:
   datetime_r = datetime.datetime.strptime(lines[3],'%m/%d/%Y')
  else:
   datetime_r = None

  id_pirogue = peche_sql.findUUID('artisanal.pirogue','immatriculation',lines[1],conn)

  if id_pirogue is None:
   id_pirogue = peche_sql.findUUID('artisanal.pirogue','name',lines[0].replace('\'',''),conn)

   if id_pirogue is None:
    print lines[0], lines[1]
    id_pirogue = None

  immatriculation = lines[1]
  t_gear = None 
  mesh_min = None
  mesh_max = None
  length = None

#   username, datetime_d, datetime_r, obs_name, t_site, id_pirogue, license_num, t_gear, mesh_max, length 

  query = "INSERT INTO artisanal.maree (username, datetime_d, datetime_r, id_pirogue, immatriculation, t_study) VALUES (%s,%s,%s,%s,%s,%s) RETURNING id"
#  print query
  cur.execute(query,('jmensa', datetime_d, datetime_r, id_pirogue, immatriculation, '2'))

  id_maree = cur.fetchall()[0][0]

  species_l = [['Bossu', 'CKL'],['Bar longue tete', 'PSS'],['Bar courte tete', 'PTY'],['Gd Capitaine', 'TGA'],['Petit capitaine', 'GAL'],['Sardine', 'BOA'],['Barbillon', 'PET'],['Sole', 'YOX'],['Dorade rose', 'DEX'],['Pageot', 'PAR'],['Rouget barbet', 'GOA'],['dorade grise', 'GBX'],['Rouge', 'SNX'],['Merou', 'BSX'],['Machoiron', 'AWX'],['Disque', 'SIC'],['Mulet', 'MUF'],['Pagre', 'RPG'],['Serran', 'CBR'],['Raie', 'RAJ'],['Requin', 'SMD'],['Becune', 'BAZ'],['Bogue', 'BOG'],['Carpe', 'GBL'],['Turbo', 'SOT'],['Baudroie', 'ANF'],['Ceinture', 'LHT'],['Banane de mer', 'CEC'],['Carangue', 'CGX'],['Thon', 'CGX'],['Maquereau', 'MAS'],['Chanchard', 'SDX'],['Melange', 'PPP'],['Crevettes', 'PEN'],['Crabes', 'SWN'],['Gambas', 'ARV'],['Langouste', 'LOY'],['Seiches', 'IAX'],['Encornet', 'ILL'],['Calamar', 'OUK'],['Divers', 'DIV']]

  catch = lines[5:-2]
  for i in range(len(catch)):
   if catch[i] is not None:
    species_FAO = species_l[i][1]
#    print species_FAO

    id_species = peche_sql.findUUID('fishery.species','fao',species_FAO,conn) 
    if id_species is not None:
     wgt_spc = catch[i]

     query = "INSERT INTO artisanal.captures (username,id_maree,id_species,wgt_spc) VALUES (%s,%s,%s,%s)"
     print query
     cur.execute(query,('jmensa',id_maree,id_species[0],wgt_spc))
    else:
     print species_l[i][1]

conn.commit()

cur.close()
conn.close()
