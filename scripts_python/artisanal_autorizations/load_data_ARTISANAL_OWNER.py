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

file_csv = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_OWNER.csv_out'
file_t_nationality = '/Users/jeanmensa/Google_Drive/Gabon_Bleu/_database/CSV_data/artisanal/artisanal_T_NATIONALITY.csv_out'

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
  dob = peche_sql.mdy2ymd(lines[2])
  t_nationality = peche_sql.findID(lines[8],file_t_nationality,1)
 
  idcard = peche_sql.stripList(lines[4],[' ','.',':'])
  if idcard is not None:
   t_card_t = re.split('(\d+)',idcard.strip())
   t_card = t_card_t[0].strip('/')
   if t_card == 'CNI': idcard = idcard.replace(t_card,''); t_card = 1
   elif t_card == 'PC': idcard = idcard.replace(t_card,''); t_card = 4
   elif t_card == 'permismilitaire': idcard = idcard.replace(t_card,''); t_card = 3
   elif t_card == 'ActeNaisN': idcard = idcard.replace(t_card,''); t_card = 6
   elif t_card == 'recepisseCNI': idcard = idcard.replace(t_card,''); t_card = 1
   elif t_card == 'CD': idcard = idcard.replace(t_card,''); t_card = 5
   elif t_card == 'CS': idcard = idcard.replace(t_card,''); t_card = 0
   elif t_card == 'CP': idcard = idcard.replace(t_card,''); t_card = 3
   elif len(t_card_t) > 1:
    if t_card_t[1] == 'CB': idcard = idcard.replace(t_card,''); t_card = 2
    elif t_card_t[1] == 'BC': idcard = idcard.replace(t_card,''); t_card = 2
    elif t_card_t[1] == 'IS': idcard = idcard.replace(t_card,''); t_card = 2
    elif t_card_t[1] == 'GB': idcard = idcard.replace(t_card,''); t_card = 2
    else: t_card = 99 
   elif len(t_card_t) > 2:
    if t_card_t[2] == 'CB': idcard = idcard.replace(t_card,''); t_card = 2
    elif t_card_t[2] == 'BC': idcard = idcard.replace(t_card,''); t_card = 2
    elif t_card_t[2] == 'IS': idcard = idcard.replace(t_card,''); t_card = 2
    elif t_card_t[2] == 'GB': idcard = idcard.replace(t_card,''); t_card = 2
    elif t_card_t[2] == '-' and t_card_t[4] == '-': idcard = idcard.replace(t_card,''); t_card = 1
    else: t_card = 99
   else: t_card = 99

  tel = peche_sql.stripList(lines[9],[' ','.',':','-'])
  address = lines[6]
  query = "INSERT INTO artisanal.owner (username, first_name, last_name, bday, t_nationality, t_card, idcard, telephone, address, id_temp) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
#  print query
  cur.execute(query,('jmensa',first_name,last_name,dob,t_nationality,t_card,idcard,tel,address,lines[12]))

conn.commit()

cur.close()
conn.close()
