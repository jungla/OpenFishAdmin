import psycopg2
import csv
import sys

conn = psycopg2.connect("dbname=geospatialdb user=postgres")
#conn = psycopg2.connect("dbname=geospatialdb user=postgres host=212.237.6.69 password=alpha8etA123!")

cur = conn.cursor()

table = sys.argv[1]
path = sys.argv[2] 

schema = table.split("_",1)[0]
table_name = table.split("_",1)[1]

filename = table + '.csv'

with open(path+filename, 'rt') as csvfile:
 spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')

 for lines in spamreader:
  lines[0] = lines[0].replace('\ufeff','')
  query = "INSERT INTO "+schema+"."+table_name+" (id, "+table_name[2:]+", active) VALUES (%s, %s, %s)"
  print(query)
  cur.execute(query,(lines[0],lines[1], 'true'))
 
conn.commit()
 
cur.close()
conn.close()
