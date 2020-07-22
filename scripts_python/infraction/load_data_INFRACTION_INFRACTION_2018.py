import psycopg2
import numpy as np
import csv
import peche_sql

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#
## Open a cursor to perform database operations
cur = conn.cursor()

file_csv = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/infraction/infraction_INFRACTION_2018.csv_out'

file_t_nationality = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_NATIONALITY.csv_out'
file_t_infraction = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/infraction/infraction_T_INFRACTION.csv_out'
file_t_org = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/infraction/infraction_T_ORG.csv_out'

nones = []

with open(file_csv, 'rb') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
 spamreader.next()
 for lines in spamreader:

  for i in range(len(lines)):
   if lines[i] == '': lines[i] = None 
   if lines[i] == ' ': lines[i] = None
   if lines[i] == '\n': lines[i] = None

  id_pv = lines[0]
  name_org = None
  date_i = lines[2]
  t_org = peche_sql.findID(lines[6],file_t_org,1)

  # find owner

  if lines[15] is None and lines[14] is not None:
   owner_last = ''
   owner_first = ''
   f_s = lines[14].split(' ')
   for i in range(len(f_s)):
    if f_s[i].isupper():
     owner_last = owner_last+' '+f_s[i]
    else:
     owner_first = owner_first+' '+f_s[i]
   owner_last = owner_last.strip().title() 
   owner_first = owner_first.strip().title()
  else: #if lines[15] is not None and lines[14] is not None:
   if lines[14] is not None:
    owner_last = lines[14].title()
   else:
    owner_last = None
   if lines[15] is not None:
    owner_first = lines[15].title()
   else:
    owner_first = None

  if owner_last is not None:
   id_owner = peche_sql.findUUID('artisanal.owner','last_name',owner_last.replace("'","''"),conn)
   #if id_owner is None:
    #print owner_last
  else:
   id_owner = None

  # find pirogue

  id_pirogue = peche_sql.findUUID('artisanal.pirogue','immatriculation',lines[5],conn)

  if id_pirogue is None:
   id_pirogue = peche_sql.findUUID('artisanal.pirogue','name',lines[1],conn)
   if id_pirogue is None and id_owner is None:
#    print lines[5]
    nones.append(lines[5])

  pir_name = lines[1]
  immatriculation = lines[5]

  owner_idcard = None
  owner_t_card = None
  fish_idcard_1 = None
  fish_t_card_1 = None
  fish_idcard_2 = None
  fish_t_card_2 = None
  fish_idcard_3 = None
  fish_t_card_3 = None
  id_fisherman_4 = None
  fish_idcard_4 = None
  fish_first_4 = None
  fish_last_4 = None
  fish_t_card_4 = None
  fish_t_nationality_4 = None
  fish_telephone_4 = None


#  owner_idcard
#  owner_t_card
  owner_t_nationality = peche_sql.findID(lines[16],file_t_nationality,1)
  owner_telephone = lines[17]

  id_fisherman_1 = peche_sql.findUUID('artisanal.fisherman','last_name',lines[18],conn)
  fish_first_1 = lines[19]
  fish_last_1 = lines[18]
#  fish_idcard_1
#  fish_t_card_1
  fish_t_nationality_1 = peche_sql.findID(lines[20],file_t_nationality,1)
  fish_telephone_1 = lines[21]

  id_fisherman_2 = peche_sql.findUUID('artisanal.fisherman','last_name',lines[22],conn)
  fish_first_2 = lines[23]
  fish_last_2 = lines[22]
#  fish_idcard_2
#  fish_t_card_2
  fish_t_nationality_2 = peche_sql.findID(lines[24],file_t_nationality,1)
  fish_telephone_2 = lines[25]

  id_fisherman_3 = peche_sql.findUUID('artisanal.fisherman','last_name',lines[26],conn)
  fish_first_3 = lines[27]
  fish_last_3 = lines[26]
#  fish_idcard_3
#  fish_t_card_3
  fish_t_nationality_3 = peche_sql.findID(lines[28],file_t_nationality,1)
  fish_telephone_3 = lines[29]

#  id_fisherman_4
#  fish_first_4
#  fish_last_4
#  fish_idcard_4
#  fish_t_card_4
#  fish_t_nationality_4
#  fish_telephone_4
  if lines[30] is not None:
   pir_conf = lines[30].title()
  else:
   pir_conf = None

  if lines[31] is not None:
   eng_conf = lines[31].title()
  else:
   eng_conf = None

  if lines[32] is not None:
   net_conf = lines[32].title()
  else:
   net_conf = None

  if lines[33] is not None:
   doc_conf = lines[33].title()
  else:
   doc_conf = None

  if lines[34] is not None:
   other_conf = lines[34].title()
  else:
   other_conf = None

  amount = lines[10]
  payment = lines[9]
  n_dep = lines[11]
  n_cdc = lines[12]
  n_lib = lines[13]
  if lines[35] is not None:
   comments = lines[35]
  else:
   comments = None

  t_infraction = peche_sql.findID(lines[3],file_t_infraction,1)
  #print t_infraction

  query = "SELECT * FROM infraction.infraction WHERE date_i = %s AND immatriculation = %s"
  cur.execute(query, (date_i, immatriculation))
  id_infraction = cur.fetchall()

  if len(id_infraction) > 0:
   id_infraction = id_infraction[0][0]
  else:
   if lines[7] is not None and lines[8] is not None:
   # id_pv, date_i, t_org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, owner_t_card, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_t_nationality_1, fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, fish_idcard_4, fish_t_card_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, amount, payment, n_dep, n_cdc, n_lib, comments
    lat = float(lines[7])
    lon = float(lines[8])
    query = "INSERT INTO infraction.infraction (username, id_pv, date_i, t_org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, owner_t_card, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_t_nationality_1, fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3,fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, fish_idcard_4, fish_t_card_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, amount, payment, n_dep, n_cdc, n_lib, comments, location) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, ST_GeomFromText('POINT(%s %s)',4326)) RETURNING id"
 #  print query
    cur.execute(query,('jmensa', id_pv, date_i, t_org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, owner_t_card, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_t_nationality_1, fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, fish_idcard_4, fish_t_card_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, amount, payment, n_dep, n_cdc, n_lib, comments, lon, lat))
   else:
    query = "INSERT INTO infraction.infraction (username, id_pv, date_i, t_org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, owner_t_card, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_t_nationality_1, fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3,fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, fish_idcard_4, fish_t_card_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, amount, payment, n_dep, n_cdc, n_lib, comments) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s) RETURNING id"
    cur.execute(query,('jmensa', id_pv, date_i, t_org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, owner_t_card, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_t_nationality_1, fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, fish_idcard_4, fish_t_card_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, amount, payment, n_dep, n_cdc, n_lib, comments))
 #  print query
 
   id_infraction = cur.fetchall()[0][0]

  query = "INSERT INTO infraction.infractions (username, t_infraction, id_infraction) VALUES (%s, %s, %s)";
  cur.execute(query,('jmensa',t_infraction,id_infraction))

conn.commit()

cur.close()
conn.close()
