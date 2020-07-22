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

file_obs = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/fishery/fishery_SPECIES_CONV.csv_out'
file_t_taille_poi = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/trawlers_T_TAILLE_POI.csv_out'
file_t_taille_cre = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/trawlers_T_TAILLE_CRE.csv_out'

years = ['2018']
years = ['2016','2017','2018']

def findUUID_route(maree,lance,conn):
 if maree is not None and lance is not None:
  cur = conn.cursor()
  query = "SELECT id FROM trawlers.route WHERE maree='"+maree+"' AND lance='"+lance+"' "
#  print query
  cur.execute(query)
  result = cur.fetchall()
  if len(result) == 0:
   return result
  else:
   return result[0][0]
 else:
  result = None
 return result

for year in years:

 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/'+year+'/'
 filename = 'trawlers_CM_CRE.csv_out'

 with open(path+filename, 'rU') as csvfile:
  spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
  spamreader.next()

  for lines in spamreader:
   for i in range(len(lines)):
    if lines[i] == '': lines[i]=None
    if lines[i] == '\n': lines[i]=None
    if lines[i] == '-9999': lines[i]=None
    if lines[i] == 'NA': lines[i]=None
    if lines[i] == 'NaN': lines[i]=None
    if lines[i] == '#VALUE!': lines[i]=None

   if lines[0] != None:
    maree = lines[0]
   # print date

   if lines[1] != None:
    lance = lines[1]
   # print date

   if lines[2] is not None:
    obs_species = peche_sql.convert_OBS(lines[2], file_obs)

    id_species = peche_sql.findUUID_species(obs_species,conn)
#    print id_species, obs_species
    if len(id_species) > 0:
     id_species = str(id_species[0][0])

     id_route = findUUID_route(maree,lance,conn)

     t_taille_poi = None
     t_taille_cre = None
 
     if lines[3] != None:
      t_taille_poi = peche_sql.findID(lines[3],file_t_taille_poi,1)
      if t_taille_poi is None:
       t_taille_cre = peche_sql.findID(lines[3],file_t_taille_cre,1)
 
#     category = lines[3] 
     poids = lines[4]
  
     ## Execute a command: this creates a new table
  
     cm4_cre = lines[5]
     cm5_cre = lines[6]
     cm6_cre = lines[7]
     cm7_cre = lines[8]
     cm8_cre = lines[9]
     cm9_cre = lines[10]
     cm10_cre = lines[11]
     cm11_cre = lines[12]
     cm12_cre = lines[13]
     cm13_cre = lines[14]
     cm14_cre = lines[15]
     cm15_cre = lines[16]
     cm16_cre = lines[17]
     cm17_cre = lines[18]
     cm18_cre = lines[19]
     cm19_cre = lines[20]
     cm20_cre = lines[21]
     cm21_cre = lines[22]
     cm22_cre = lines[23]
     cm23_cre = lines[24]
     cm24_cre = lines[25]
     cm25_cre = lines[26]
     cm26_cre = lines[27]
     cm27_cre = lines[28]
     cm28_cre = lines[29]
     cm29_cre = lines[30]
     cm30_cre = lines[31]
     cm31_cre = lines[32]
     cm32_cre = lines[33]
     cm33_cre = lines[34]
     cm34_cre = lines[35]
     cm35_cre = lines[36]
     cm36_cre = lines[37]
     cm37_cre = lines[38]
     cm38_cre = lines[39]
     cm39_cre = lines[40]
     cm40_cre = lines[41]
     cm41_cre = lines[42]
     cm42_cre = lines[43]
     cm43_cre = lines[44]
     cm44_cre = lines[45]
     cm45_cre = lines[46]
     cm46_cre = lines[47]
     cm47_cre = lines[48]
     cm48_cre = lines[49]
     cm49_cre = lines[50]
     cm50_cre = lines[51]
     cm51_cre = lines[52]
     cm52_cre = lines[53]
     cm53_cre = lines[54]
     cm54_cre = lines[55]
     cm55_cre = lines[56]
     cm56_cre = lines[57]
     cm57_cre = lines[58]
     cm58_cre = lines[59]
     cm59_cre = lines[60]
     cm60_cre = lines[61]
     cm61_cre = lines[62]
     cm62_cre = lines[63]
     cm63_cre = lines[64]
     cm64_cre = lines[65]
     cm65_cre = lines[66]
     cm66_cre = lines[67]
     cm67_cre = lines[68]
     cm68_cre = lines[69]
     cm69_cre = lines[70]
     cm70_cre = lines[71]
     cm71_cre = lines[72]
     cm72_cre = lines[73]
     cm73_cre = lines[74]
     cm74_cre = lines[75]
     cm75_cre = lines[76]
     cm76_cre = lines[77]
     cm77_cre = lines[78]
     cm78_cre = lines[79]
     cm79_cre = lines[80]
     cm80_cre = lines[81]
     cm81_cre = lines[82]
     cm82_cre = lines[83]
     cm83_cre = lines[84]
     cm84_cre = lines[85]
     cm85_cre = lines[86]
  
     if len(id_route) > 0:
  
      query = "INSERT INTO trawlers.cm_cre (username, id_route, maree, lance, id_species, t_taille_poi, t_taille_cre, poids, cm4_cre, cm5_cre, cm6_cre, cm7_cre, cm8_cre, cm9_cre, cm10_cre, cm11_cre, cm12_cre, cm13_cre, cm14_cre, cm15_cre, cm16_cre, cm17_cre, cm18_cre, cm19_cre, cm20_cre, cm21_cre, cm22_cre, cm23_cre, cm24_cre, cm25_cre, cm26_cre, cm27_cre, cm28_cre, cm29_cre, cm30_cre, cm31_cre, cm32_cre, cm33_cre, cm34_cre, cm35_cre, cm36_cre, cm37_cre, cm38_cre, cm39_cre, cm40_cre, cm41_cre, cm42_cre, cm43_cre, cm44_cre, cm45_cre, cm46_cre, cm47_cre, cm48_cre, cm49_cre, cm50_cre, cm51_cre, cm52_cre, cm53_cre, cm54_cre, cm55_cre, cm56_cre, cm57_cre, cm58_cre, cm59_cre, cm60_cre, cm61_cre, cm62_cre, cm63_cre, cm64_cre, cm65_cre, cm66_cre, cm67_cre, cm68_cre, cm69_cre, cm70_cre, cm71_cre, cm72_cre, cm73_cre, cm74_cre, cm75_cre, cm76_cre, cm77_cre, cm78_cre, cm79_cre, cm80_cre, cm81_cre, cm82_cre, cm83_cre, cm84_cre, cm85_cre) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);"
  
#      print query
      cur.execute(query,('jmensa', id_route, maree, lance, id_species, t_taille_poi, t_taille_cre, poids, cm4_cre, cm5_cre, cm6_cre, cm7_cre, cm8_cre, cm9_cre, cm10_cre, cm11_cre, cm12_cre, cm13_cre, cm14_cre, cm15_cre, cm16_cre, cm17_cre, cm18_cre, cm19_cre, cm20_cre, cm21_cre, cm22_cre, cm23_cre, cm24_cre, cm25_cre, cm26_cre, cm27_cre, cm28_cre, cm29_cre, cm30_cre, cm31_cre, cm32_cre, cm33_cre, cm34_cre, cm35_cre, cm36_cre, cm37_cre, cm38_cre, cm39_cre, cm40_cre, cm41_cre, cm42_cre, cm43_cre, cm44_cre, cm45_cre, cm46_cre, cm47_cre, cm48_cre, cm49_cre, cm50_cre, cm51_cre, cm52_cre, cm53_cre, cm54_cre, cm55_cre, cm56_cre, cm57_cre, cm58_cre, cm59_cre, cm60_cre, cm61_cre, cm62_cre, cm63_cre, cm64_cre, cm65_cre, cm66_cre, cm67_cre, cm68_cre, cm69_cre, cm70_cre, cm71_cre, cm72_cre, cm73_cre, cm74_cre, cm75_cre, cm76_cre, cm77_cre, cm78_cre, cm79_cre, cm80_cre, cm81_cre, cm82_cre, cm83_cre, cm84_cre, cm85_cre))
      conn.commit()

    else:
     print lines[2]      

cur.close()
conn.close()
