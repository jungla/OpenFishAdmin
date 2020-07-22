import psycopg2
import numpy as np
import csv
import peche_sql
import os

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

# read CSV data to be uploaded
query = "SELECT id, navire, beacon FROM vms.navire;"

cur.execute(query)
vms_navire = np.asarray(cur.fetchall())

path = os.getenv('HOME')+'/Google_Drive/Gabon_Bleu/_database/CSV_data/thon/'

file_obs = os.getenv('HOME')+'/Google_Drive/Gabon_Bleu/_database/CSV_data/fishery/fishery_SPECIES_CONV.csv_out'

# read CSV data to be uploaded

#tailles = ['0.9','1.8','2.4','3','3.2','3.4','4.2','7.5','10','15','25','30','35','45','55','65','75','85','nan','0.9','1.8','2.4','3','3.2','3.4','4.2','7.5','10','15','25','35','45','55','65','75','85','105','nan','0.75','1.5','1.7','1.8','2.4','3.2','3.4','7.2','nan','15','25','35','45','nan','0.75','1.5','1.7','1.8','nan','0.9','1.8','nan','0.9','1.8','2.4','nan','0.9','1.8','nan','0.9','nan','0.9','nan','1.7','1.7','nan','1.8','0.9','55','nan','nan','nan','nan','80','nan','25','200','nan','nan','nan','nan','nan','0.9','0.9','nan','0.9','1.7','nan','0.9','1.7','nan','0.9','1.7','nan','0.9','1.7','205','nan','nan','nan','65','205','nan','nan','nan','nan','nan','nan','nan','nan','1.7','3.4','nan','nan']

tailles = ['<1.8','>1.8','<3','>3','<3.4','>3.4','>5','<10','>10','10-19','20-29','>30','<30','30-39','40-49','50-59','60-69','70-79','80-89','90-99','100-119','120-139','>140','nan','<1.8','>1.8','<3','>3','<3.4','>3.4','>5','<10','>10','10-19','20-29','30-39','40-49','50-59','60-69','70-79','80-98','100-109','nan','<1.5','>1.5','<1.8','>1.8','<3','<3.4','>3.4','<10','nan','10-19','20-29','30-39','40-49','nan','<1.5','>1.5','<1.8','>1.8','nan','<1.8','>1.8','nan','<1.8','>1.8','<3','nan','<1.8','>1.8','nan','<1.8','nan','<1.8','nan','<3.4','<3.4','nan','>1.8','<1.8','50-59','nan','nan','nan','nan','80','nan','25','200','nan','nan','nan','nan','<1.8','<1.8','nan','<1.8','<3.4','nan','<1.8','<3.4','nan','<1.8','<3.4','nan','<1.8','<3.4','200-209','nan','nan','nan','60-69','200-209','nan','nan','nan','nan','nan','nan','nan','nan','<3.4','>3.4','nan','nan']

species=['SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.alb','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Thu.obe','SCOM/Kat.pel','SCOM/Kat.pel','SCOM/Kat.pel','SCOM/Kat.pel','SCOM/Kat.pel','SCOM/Kat.pel','SCOM/Kat.pel','SCOM/Kat.pel','SCOM/Kat.pel','SCOM/Thu.ala','SCOM/Thu.ala','SCOM/Thu.ala','SCOM/Thu.ala','SCOM/Thu.ala','SCOM/Aux.tha','SCOM/Aux.tha','SCOM/Aux.tha','SCOM/Aux.tha','SCOM/Aux.tha','SCOM/Eut.all','SCOM/Eut.all','SCOM/Eut.all','SCOM/Aux.roc','SCOM/Aux.roc','SCOM/Aux.roc','SCOM/Aux.roc','SCOM/Eut.all','SCOM/Eut.all','SCOM/Eut.all','BAL/Bal.vet','BAL/Bal.vet','CAR/Ela.bip','CAR/Ela.bip','SCOM/Sar.sar','nan','nan','nan','SCOM/Aux.tha','XIP/Xip.gla','SPA/Spa.aur','COR','IST/Ist.alb','IST/Ist.alb','MYL/Myl','SQL/Squ','SCOM/Thu.ala','COR','IST/Ist.alb','nan','nan','nan','POI','SCOM/Thu.alb','SCOM/Kat.pel','SCOM/Kat.pel','SCOM/Aux.tha','SCOM/Aux.tha','SCOM/Aux.tha','SCOM/Eut.all','SCOM/Eut.all','SCOM/Eut.all','SCOM/Eut.all','SCOM/Eut.all','SCOM/Eut.all','SCOM/Aux','SCOM/Aux','IST/Ist.alb','IST/Ist.alb','BAL/Bal.vet','CAR/Ela.bip','SQL/Squ','IST/Ist.alb','CAR/Car.cry','BAL/Can.mac','nan','SQL/Squ','MYL/Myl','nan','BAL','SPA/Spa.aur','COR','COR','POI','REQ/Rhi.typ']

rejects = ['FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','FALSE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE','TRUE']

path = os.getenv('HOME')+'/Google_Drive/Gabon_Bleu/_database/CSV_data/thon/'
filename = 'thon_CAPTURES.csv_out'

#def findUUID_maree(id_navire,year,conn):
# if id_navire is not None and year is not None:
#  cur = conn.cursor()
#  query = "SELECT id FROM thon.maree WHERE id_navire='"+id_navire+"' AND year='"+year+"'"
#  cur.execute(query)
#  result = cur.fetchall()
#
#  if len(result) > 1:
#   result = result[0][0]
#  else:
#   result = result[0]
#
# else:
#  result = None
# return result

def findUUID_lance(id_navire,date,time,conn):
 if id_navire is not None and date is not None and time is not None:
  cur = conn.cursor()
  query = "SELECT id FROM thon.lance WHERE id_navire='"+id_navire+"' AND date_c ='"+date+"' AND heure_c ='"+time+"'"
  cur.execute(query)
  result = cur.fetchall()

  if len(result) > 1:
   result = result[0][0]
  else:
   result = None 

 else:
  result = None
 return result

with open(path+filename, 'rU') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next()

 for lines in spamreader:
  for i in range(len(lines)):
   if lines[i] is not None:
    lines[i] = lines[i].replace('\xc2\xa0','')
   if lines[i] == '': lines[i]=None
   if lines[i] == '\n': lines[i]=None
   if lines[i] == '-9999': lines[i]=None
   if lines[i] == 'NA': lines[i]=None
   if lines[i] == 'NaN': lines[i]=None
   if lines[i] == '#REF!': lines[i]=None
   if lines[i] == 'No data': lines[i]=None
   if lines[i] == 'NO DATA': lines[i]=None

  year = lines[0]

  if len(vms_navire[vms_navire[:,1] == lines[1].replace('\xc3\xaa','e').strip().title(),0]) > 0:
   id_navire = vms_navire[vms_navire[:,1] == lines[1].replace('\xc3\xaa','e').strip().title(),0][0]
  else:
   id_navire = None
   print 'id_navire', lines[1]

#  id_maree = findUUID_maree(id_navire,year,conn) 
#
#  if (id_maree is None):
#   print 'id_navire, year', id_navire, year
#
#   # add maree
#
#   country = None
#   year = lines[0]
#   port_d = None
#   port_a = None
#   date_d = None
#   date_a = None
# 
#   query = "INSERT INTO thon.maree (username, id_navire, country, year, port_d, port_a, date_d, date_a) VALUES (%s,%s,%s,%s,%s,%s,%s,%s);"
# 
# # print query
#   cur.execute(query,('jmensa', id_navire, country, year, port_d, port_a, date_d, date_a))
#   conn.commit()

  date_c = lines[3]
  heure_c = lines[4]
  eez = lines[2]
  water_temp = lines[11] 
  wind_speed = lines[145]
  wind_dir = lines[144]
  cur_speed = lines[150]

  if lines[12] == 'X':
   success = True
  elif lines[13] == 'X':
   success = False
  else:
   success = None

  for f in range(len(tailles)):
    poids = lines[f+14]
    if poids is not None and species[f] != 'nan' and tailles[f] != 'nan' and poids != '':
     success = True
  
#  if lines[12] == '' and len(np.where(np.asarray(lines[14:len(tailles)+14]) != None)[0]) > 0:
#   success = True

  if lines[141] == 'X':
   banclibre = True
  else:
   banclibre = False

  balise_id = lines[136]

  if lines[5] != None:
   deg = float(lines[5])
   if lines[6] is not None:
    min = float(lines[6])
   else:
    min = 0
   lat = deg+min/60.
   if lines[7] == 'S': lat = -1*lat
  else:
   deg = np.nan 
   min = np.nan
   lat = np.nan
 
  if lines[8] != None:
   deg = float(lines[8])
   if lines[6] is not None:
    min = float(lines[9])
   else:
    min = 0
   lon = deg+min/60.
   if lines[8] == 'O': lon = -1*lon
  else:
   deg = np.nan
   min = np.nan 
   lon = np.nan

  comment = lines[143]

   ## Execute a command: this creates a new table

  ''' Lance '''
 
  if str(lat) != 'nan' and str(lon) != 'nan':
   # check if it exists already
   id_lance = findUUID_lance(id_navire,date_c,heure_c,conn)

   if (id_lance is not None):
    # if it exists, update contect...
    #print 'id_navire, date_c,heure_c', id_navire, date_c, heure_c
    query = "UPDATE thon.lance SET username = %s, id_navire = %s, date_c = %s, heure_c = %s, eez = %s, success = %s, water_temp = %s, wind_speed = %s, wind_dir = %s, cur_speed = %s, banclibre = %s, balise_id = %s, location = ST_GeomFromText('POINT(%s %s)',4326), comment = %s WHERE id = %s;"

#    print query
    cur.execute(query,('jmensa', id_navire, date_c, heure_c, eez, success, water_temp, wind_speed, wind_dir, cur_speed, banclibre, balise_id, lon, lat, id_lance, comment))
    
   else:
    # if it doesnt, insert.
    query = "INSERT INTO thon.lance (username, id_navire, date_c, heure_c, eez, success, water_temp, wind_speed, wind_dir, cur_speed, banclibre, balise_id, location, comment) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,ST_GeomFromText('POINT(%s %s)',4326), %s) RETURNING id;"

#      print query
    cur.execute(query,('jmensa', id_navire, date_c, heure_c, eez, success, water_temp, wind_speed, wind_dir, cur_speed, banclibre, balise_id, lon, lat, comment))
    id_lance = cur.fetchone()[0]

   ''' Captures '''

   for f in range(len(tailles)):
    poids = lines[f+14]
    if poids is not None and species[f] != 'nan' and tailles[f] != 'nan' and poids != '':
     if float(poids) > 900: poids = float(poids)/1000
     taille = tailles[f]
     rejete = rejects[f]
     #print date_c, heure_c, poids, taille, rejete, species[f]
 
     t_species = species[f].lower()
     obs_species = peche_sql.convert_OBS(t_species, file_obs)
     id_species = peche_sql.findUUID_species(obs_species,conn)
 
     if len(id_species) > 0:
      id_species = str(id_species[0][0]) 
  
      query = "INSERT INTO thon.captures (username, id_lance, rejete, id_species, taille, poids) VALUES (%s,%s,%s,%s,%s,%s);"

      cur.execute(query,('jmensa', id_lance, rejete, id_species, taille, poids))
 
     else:
      print species[f]
   
conn.commit()
cur.close()
conn.close()
