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

path = os.getenv('HOME')+'/Google_Drive/Gabon_Bleu/_database/CSV_data'
file_t_zone = path+'/crevette/crevette_T_ZONE.csv_out'

file_t_zone_conv = path+'/crevette/crevette_T_ZONE_CONV.csv_out'

species_l = [ ('POR',None) ,('PAL',None) ,('CAC',None) ,('TOR',None) ,('P',None) ,('POL/Gal.dec',2) ,('P','2') ,('DRE/Dre.afr',None) ,('SPA/Den',1) ,('POL/Gal.dec',None) ,('SPA/Den',None) ,('SPA/Den',None) ,('DRE/Dre.afr',None) ,('POL/Gal.dec',None) ,('CAR/Ale.ale',None) ,('ARI',None) ,('P',None) ,('LUT/Lut.den',None) ,('CAR/Ale.ale',None) ,('SOL/Peg.cad',None) ,('PSE/Pse.bel',None) ,('SCI/Pse',None) ,('SCI/Pse',None) ,('SPH/Sph.gua',None) ,('SPA/Pag',None) ,('POL',1) ,('POL',1) ,('POL/Gal.dec',2) ,('POL/Gal.dec',4) ,('DRE/Dre.afr',0) ,('DRE/Dre.afr',1) ,('DRE/Dre.afr',2) ,('DRE/Dre.afr',3) ,('DRE/Dre.afr',4) ,('SPA/Den',1) ,('SPA/Den',2) ,('SPA/Den',3) ,('SPA/Den',4) ,('SOL/Peg.cad',0) ,('SOL/Peg.cad',2) ,('SOL/Peg.cad',3) ,('PSE/Pse.bel',0) ,('PSE/Pse.bel',1) ,('PSE/Pse.bel',2) ,('SCI/Pse',0) ,('SCI/Pse',1) ,('SCI/Pse',2) ,('SCI/Pse',3) ,('ARI',2) ,('ARI',3) ,('SPA/Pag.bog',None) ,('CAR/Tra.tre',None) ,('ARI',0) ,('PSE/Pse.bel',3)]

# read CSV data to be uploaded

query = "SELECT id, navire FROM vms.navire;"

cur.execute(query)
vms_navire = np.asarray(cur.fetchall())

for i in range(len(vms_navire[:,1])):
 vms_navire[i,1] = vms_navire[i,1].replace('\xc3\xaa','e').replace(' ','').title()

years = ['2015','2016','2017','2018']
#years = ['2016']

for year in years:

 filename = path+'/crevette/'+year+'/'+'crevette_LANCE.csv_out'
 print filename

 with open(filename, 'rU') as csvfile:
  spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
  spamreader.next()

  for lines in spamreader:
   lines[0] = lines[0].replace('\xef\xbb\xbf','')
   for i in range(len(lines)):
    lines[i] = lines[i].replace('\xef\xbf\xbd','e')
    if lines[i] == '0': lines[i]=None
    if lines[i] == '': lines[i]=None
    if lines[i] == ' ': lines[i]=None
    if lines[i] == '\n': lines[i]=None
    if lines[i] == '-9999': lines[i]=None
    if lines[i] == 'NA': lines[i]=None
    if lines[i] == 'N/A': lines[i]=None
    if lines[i] == 'NaN': lines[i]=None
    if lines[i] == '#VALUE!': lines[i]=None
    if lines[i] == 'nodata': lines[i]=None

   if lines[0] != None:
    if len(vms_navire[vms_navire[:,1] == lines[0].replace('\xc3\xaa','e').replace(' ','').title(),0]) == 0:
     print lines[0]
     id_navire = None
    else:
     id_navire = vms_navire[vms_navire[:,1] == lines[0].replace('\xc3\xaa','e').replace(' ','').title(),0][0]

   if lines[1] != None: 
    date_l = lines[1] 
   else:
    date_l = None
 
   if lines[5] != None: 
    zone = peche_sql.convert_val(lines[5], file_t_zone_conv)
    t_zone = peche_sql.findID(zone,file_t_zone,1)

   if t_zone == None:
    t_zone = 99
 
   if lines[6] != None:
    lance = lines[6]
   else:
    lance = None

   if lines[9] != None:
    h_d = lines[9]
   else:
    h_d = None

   if lines[14] != None:
    h_f = lines[14]
   else:
    h_f = None
 
   if lines[10] != None:
    D_d = lines[10]
   else:
    D_d = None

   if lines[15] != None:
    D_f = lines[15]
   else:
    D_f = None
 
   if lines[11] != None:
    T_d = lines[11]
   else:
    T_d = None

   if lines[22] != None:
    rejets = lines[22]
   else:
    rejets = None
 
   if lines[24] == None:
    c0_cre = 0
   else:
    c0_cre = lines[24]

   if lines[25] == None:
    c1_cre = 0
   else:
    c1_cre = lines[25]

   if lines[26] == None:
    c2_cre = 0
   else:
    c2_cre = lines[26]

   if lines[27] == None:
    c3_cre = 0
   else:
    c3_cre = lines[27]

   if lines[28] == None:
    c4_cre = 0
   else:
    c4_cre = lines[28]

   if lines[29] == None:
    c5_cre = 0
   else:
    c5_cre = lines[29]

   if lines[30] == None:
    c6_cre = 0
   else:
    c6_cre = lines[30]

   if lines[31] == None:
    c7_cre = 0
   else:
    c7_cre = lines[31]

   if lines[32] == None:
    c8_cre = 0
   else:
    c8_cre = lines[32]

   if lines[33] == None:
    c_cre = 0
   else:
    c_cre = lines[33]

   if lines[34] == None:
    cc_cre = 0
   else:
    cc_cre = lines[34]

   if lines[35] == None:
    o_cre = 0
   else:
    o_cre = lines[35]

   if lines[36] == None:
    v6_cre = 0
   else:
    v6_cre = lines[36]

#   c1_cre = lines[25]
#   c2_cre = lines[26] 
#   c3_cre = lines[27] 
#   c4_cre = lines[28]
#   c5_cre = lines[29]
#   c6_cre = lines[30]
#   c7_cre = lines[31]
#   c8_cre = lines[32]
#   c_cre  = lines[33]
#   cc_cre = lines[34]
#   o_cre  = lines[35]
#   v6_cre = lines[36]
 
   lon_d = lines[8]
   lat_d = lines[7]
   lon_f = lines[13]
   lat_f = lines[12] 

   if lon_d is None or lat_d is None or lon_f is None or lat_f is None: 
    query = "INSERT INTO crevette.lance (username, id_navire, date_l, t_zone, lance, h_d, h_f, D_d, D_f, T_d, rejets, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c_cre, cc_cre, o_cre, v6_cre) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s) RETURNING id;"
    cur.execute(query,('jmensa', id_navire, date_l, t_zone, lance, h_d, h_f, D_d, D_f, T_d, rejets, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c_cre, cc_cre, o_cre, v6_cre))
 
   else:
    query = "INSERT INTO crevette.lance (username, id_navire, date_l, t_zone, lance, h_d, h_f, D_d, D_f, T_d, rejets, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c_cre, cc_cre, o_cre, v6_cre, location_d, location_f) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s, ST_GeomFromText('POINT(%s %s)',4326), ST_GeomFromText('POINT(%s %s)',4326)) RETURNING id;"
    cur.execute(query,('jmensa', id_navire, date_l, t_zone, lance, h_d, h_f, D_d, D_f, T_d, rejets, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c_cre, cc_cre, o_cre, v6_cre, float(lon_d), float(lat_d), float(lon_f), float(lat_f)))

   id_lance = cur.fetchone()[0]

   # captures 
   captures = lines[18:21] + [lines[23]] + lines[37:86]

   for c in range(len(captures)):
    if captures[c] is not None:
     id_species = peche_sql.findUUID_species(species_l[c][0],conn)
 
     if len(id_species) > 0:
      id_species = str(id_species[0][0]) 
      query = "INSERT INTO crevette.capture (username, id_lance, id_species, poids, t_taille) VALUES (%s, %s, %s, %s, %s);"
      cur.execute(query,('jmensa', id_lance, id_species, captures[c], species_l[c][1])) 
     else:
      print captures[c]

conn.commit()
cur.close()
conn.close()
