import csv
import datetime
import numpy as np
import psycopg2
import peche_sql
import geojson

## Connect to an existing database
conn = psycopg2.connect("dbname=geospatialdb user=postgres")
## Open a cursor to perform database operations
cur = conn.cursor()

# list of names. We can start with a given list of names...

# wipes LKP table

query = "SELECT json_build_object('type','Feature','id',gid,'geometry',ST_AsGeoJSON(geom)::json,'properties', json_build_object('feat_area', ST_Area(geom))) FROM shapefiles.mpa;"
query = "SELECT ST_AsGeoJSON(geom) FROM shapefiles.mpa;"

cur.execute(query)

JSON = cur.fetchall()

with open('shapefile_mpa.json', 'w') as outfile:
 geojson.dump(JSON, outfile, sort_keys=True, indent=4)

conn.commit()
cur.close()
conn.close()


