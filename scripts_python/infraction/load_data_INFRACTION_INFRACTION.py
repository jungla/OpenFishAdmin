import psycopg2
import numpy as np
import csv
import peche_sql

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

file_csv = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_INFRACTION.csv_out'

file_t_infraction = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_INFRACTION.csv_out'
file_t_org = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_ORG.csv_out'
file_t_site = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_SITE.csv_out'
file_pirogue = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_PIROGUE.csv_out'

def findUUID(table,field,id,conn):
 if id is not None:
  cur = conn.cursor()
  query = "SELECT id FROM "+table+" WHERE "+field+"='"+id+"'"
  cur.execute(query)
  result = cur.fetchall()
  if result == []: 
   result = None
  else:
   result = result[0][0]
 else:
  result = None
 return result

with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next()
 for lines in spamreader:

  comments = ""
 
  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None 
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None

  date_i = lines[3]

  t_infraction = peche_sql.findID(lines[4],file_t_infraction,1)

  if t_infraction is None:
   print lines[4]
   t_infraction = '99'

  lines[0] = peche_sql.stripList(lines[0],['.',',','N',' '])

  id_license = findUUID('artisanal.license','license',lines[0],conn)
  #id_pirogue = findUUID('artisanal.pirogue','immatriculation',peche_sql.stripList(lines[2],[' ','.',',',':']),conn) 
  if lines[2] is not None:
   immatriculation = lines[2].replace(' ','').replace('.','. ') 
   id_pirogue = findUUID('artisanal.pirogue','immatriculation',immatriculation,conn) 
  else:
   immatriculation = None
   id_pirogue = None

  pir_name = lines[1] 
#  immatriculation = peche_sql.stripList(lines[2],[' ','.',',',':'])
  id_carte = None

  fish_first = ''
  fish_last = ''

  if lines[8] is not None: 
   names = lines[8].split(' ')

   for name in names:
    if name.isupper():
     fish_last = fish_last+' '+name
    else:
     fish_first = fish_first+' '+name

   fish_first = fish_first.strip().replace('\'','')
   fish_last = fish_last.strip().replace('\'','')
   id_fisherman = findUUID('artisanal.fisherman','last_name',fish_last.title(),conn)
  else:
   id_fisherman = None
   fish_first = None
   fish_last = None

  fish_idcard = None 

  t_org = peche_sql.findID(lines[5],file_t_org,1)
#  print t_org

  if t_org is None:
   t_org = '99'
   comments = comments + ' ' + t_org
  
  name = None
  obj_confiscated = lines[9]

  amount_infract = peche_sql.stripList(lines[10],['.','C','F','A',','])

  if not peche_sql.is_number(amount_infract):
   amount_infract = None
   if lines[10] is not None:
    comments = comments + ', ' + lines[10]

  if lines[11] == 'Integrale':
   payment = amount_infract
  else:
   payment = peche_sql.stripList(lines[11],['.','F','C','A',',']) 
   if not peche_sql.is_number(payment):
    payment = None
    if lines[11] is not None:
     if lines[11] not in comments:
      comments = comments + ', ' + lines[11]

  lat = lines[6]
  lon = lines[7]

  if lines[6] is not None:
   if len(peche_sql.stripList(lines[6],['\'']).split(' ')) > 1:
    lat = float(peche_sql.stripList(lines[6],['\'']).split(' ')[0])+float(peche_sql.stripList(lines[6],['\'']).split(' ')[1])/60.
   else:
    lat = float(lines[6])

  if lines[7] is not None:
   if len(peche_sql.stripList(lines[7],['\'']).split(' ')) > 1:
    lon = float(peche_sql.stripList(lines[7],['\'']).split(' ')[0])+float(peche_sql.stripList(lines[7],['\'']).split(' ')[1])/60.
   else:
    lon = float(lines[7])

  query = "SELECT * FROM infraction.infraction WHERE date_i = %s AND immatriculation = %s"
  cur.execute(query, (date_i, immatriculation))
  id_infraction = cur.fetchall()

  if len(id_infraction) > 0:
   id_infraction = id_infraction[0][0] 
  else:
   if lat is not None and lon is not None:
    query = "INSERT INTO artisanal.infraction (username, date_i, id_license,  id_pirogue,  pir_name,  immatriculation,  id_carte,  id_fisherman,  fish_first_1,  fish_last_1,  fish_idcard_1,  t_org,  name,  obj_confiscated,  amount_infract,  payment,  comments, location) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, ST_GeomFromText('POINT(%s %s)',4326)) RETURNING id"
    cur.execute(query,('jmensa', date_i, id_license, id_pirogue, pir_name, immatriculation, id_carte, id_fisherman, fish_first, fish_last, fish_idcard, t_org, name, obj_confiscated, amount_infract, payment, comments, lon, lat))
   else:
    query = "INSERT INTO artisanal.infraction (username, date_i, id_license,  id_pirogue,  pir_name,  immatriculation,  id_carte,  id_fisherman,  fish_first_1,  fish_last_1,  fish_idcard_1,  t_org,  name,  obj_confiscated,  amount_infract,  payment,  comments) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) RETURNING id"
    cur.execute(query,('jmensa', date_i, id_license, id_pirogue, pir_name, immatriculation, id_carte, id_fisherman, fish_first, fish_last, fish_idcard, t_org, name, obj_confiscated, amount_infract, payment, comments))
 
   id_infraction = cur.fetchall()[0][0]

  query = "INSERT INTO artisanal.infractions (username, t_infraction, id_infraction) VALUES (%s, %s, %s)";
  cur.execute(query,('jmensa',t_infraction,id_infraction))

conn.commit()

cur.close()
conn.close()
