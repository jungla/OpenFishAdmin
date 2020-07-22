import csv
import datetime
import numpy as np
import psycopg2

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
## Open a cursor to perform database operations
cur = conn.cursor()

# list of names. We can start with a given list of names...

# wipes LKP table

query = "TRUNCATE TABLE artisanal.pelagic_lkp"
cur.execute(query)

pirogues = ['2782','2784','2785','2786','2787','2788']

for pirogue in pirogues:
 # for each new_p in file, read from DB last entry for each pirogue and decide if lines need to be added
 query = "SELECT *, ST_X(location), ST_Y(location) FROM artisanal.pelagic_points WHERE name = '"+pirogue+"' ORDER BY date_t DESC LIMIT 1"

 cur.execute(query)

 LKP = cur.fetchall()[0]
 print LKP
 query = "INSERT INTO artisanal.pelagic_lkp (id, date_t, name, location) VALUES (%s, %s, %s, ST_GeomFromText('POINT(%s %s)',4326))"
 cur.execute(query,(LKP[0],LKP[2],LKP[3],LKP[8],LKP[9]))
 
conn.commit()
cur.close()
conn.close()

