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

file_t_taille_poi = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/trawlers_T_TAILLE_POI.csv_out'
file_t_taille_cre = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/trawlers_T_TAILLE_CRE.csv_out'
file_obs = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/fishery/fishery_SPECIES_CONV.csv_out'

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
 filename = 'trawlers_CM_POI.csv_out'

 with open(path+filename, 'rU') as csvfile:
  spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
  spamreader.next()

  for lines in spamreader:
   for i in range(len(lines)):
    if lines[i] == '': lines[i]=None
    if lines[i] == ' ': lines[i]=None
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

   id_route = findUUID_route(maree,lance,conn)


   if lines[2] is not None:
    obs_species = peche_sql.convert_OBS(lines[2], file_obs)

    id_species = peche_sql.findUUID_species(obs_species,conn)
#    print id_species, obs_species
    if len(id_species) > 0:
     id_species = str(id_species[0][0])


     t_taille_poi = None
     t_taille_cre = None

     if lines[3] != None:
      t_taille_poi = peche_sql.findID(lines[3],file_t_taille_poi,1)
      if t_taille_poi is None:
       t_taille_cre = peche_sql.findID(lines[3],file_t_taille_cre,1)

#     category = lines[3]

     poids = lines[4]
  
     ## Execute a command: this creates a new table
  
     cm2_poi = lines[5]
     cm3_poi = lines[6]
     cm4_poi = lines[7]
     cm5_poi = lines[8]
     cm6_poi = lines[9]
     cm7_poi = lines[10]
     cm8_poi = lines[11]
     cm9_poi = lines[12]
     cm10_poi = lines[13]
     cm11_poi = lines[14]
     cm12_poi = lines[15]
     cm13_poi = lines[16]
     cm14_poi = lines[17]
     cm15_poi = lines[18]
     cm16_poi = lines[19]
     cm17_poi = lines[20]
     cm18_poi = lines[21]
     cm19_poi = lines[22]
     cm20_poi = lines[23]
     cm21_poi = lines[24]
     cm22_poi = lines[25]
     cm23_poi = lines[26]
     cm24_poi = lines[27]
     cm25_poi = lines[28]
     cm26_poi = lines[29]
     cm27_poi = lines[30]
     cm28_poi = lines[31]
     cm29_poi = lines[32]
     cm30_poi = lines[33]
     cm31_poi = lines[34]
     cm32_poi = lines[35]
     cm33_poi = lines[36]
     cm34_poi = lines[37]
     cm35_poi = lines[38]
     cm36_poi = lines[39]
     cm37_poi = lines[40]
     cm38_poi = lines[41]
     cm39_poi = lines[42]
     cm40_poi = lines[43]
     cm41_poi = lines[44]
     cm42_poi = lines[45]
     cm43_poi = lines[46]
     cm44_poi = lines[47]
     cm45_poi = lines[48]
     cm46_poi = lines[49]
     cm47_poi = lines[50]
     cm48_poi = lines[51]
     cm49_poi = lines[52]
     cm50_poi = lines[53]
     cm51_poi = lines[54]
     cm52_poi = lines[55]
     cm53_poi = lines[56]
     cm54_poi = lines[57]
     cm55_poi = lines[58]
     cm56_poi = lines[59]
     cm57_poi = lines[60]
     cm58_poi = lines[61]
     cm59_poi = lines[62]
     cm60_poi = lines[63]
     cm61_poi = lines[64]
     cm62_poi = lines[65]
     cm63_poi = lines[66]
     cm64_poi = lines[67]
     cm65_poi = lines[68]
     cm66_poi = lines[69]
     cm67_poi = lines[70]
     cm68_poi = lines[71]
     cm69_poi = lines[72]
     cm70_poi = lines[73]
     cm71_poi = lines[74]
     cm72_poi = lines[75]
     cm73_poi = lines[76]
     cm74_poi = lines[77]
     cm75_poi = lines[78]
     cm76_poi = lines[79]
     cm77_poi = lines[80]
     cm78_poi = lines[81]
     cm79_poi = lines[82]
     cm80_poi = lines[83]
     cm81_poi = lines[84]
     cm82_poi = lines[85]
     cm83_poi = lines[86]
     cm84_poi = lines[87]
     cm85_poi = lines[88]
     cm86_poi = lines[89]
     cm87_poi = lines[90]
     cm88_poi = lines[91]
     cm89_poi = lines[92]
     cm90_poi = lines[93]
     cm91_poi = lines[94]
     cm92_poi = lines[95]
     cm93_poi = lines[96]
     cm94_poi = lines[97]
     cm95_poi = lines[98]
     cm96_poi = lines[99]
     cm97_poi = lines[100]
     cm98_poi = lines[101]
     cm99_poi = lines[102]
     cm100_poi = lines[103]
     cm101_poi = lines[104]
     cm102_poi = lines[105]
     cm103_poi = lines[106]
     cm104_poi = lines[107]
     cm105_poi = lines[108]
     cm106_poi = lines[109]
     cm107_poi = lines[110]
     cm108_poi = lines[111]
     cm109_poi = lines[112]
     cm110_poi = lines[113]
  
     if len(id_route) > 0:
  
      query = "INSERT INTO trawlers.cm_poi (username, id_route, maree, lance, id_species, t_taille_poi, t_taille_cre, poids, cm5_poi, cm6_poi, cm7_poi, cm8_poi, cm9_poi,cm10_poi, cm11_poi, cm12_poi, cm13_poi, cm14_poi, cm15_poi, cm16_poi, cm17_poi, cm18_poi, cm19_poi, cm20_poi, cm21_poi, cm22_poi, cm23_poi, cm24_poi, cm25_poi, cm26_poi, cm27_poi, cm28_poi, cm29_poi, cm30_poi, cm31_poi, cm32_poi, cm33_poi, cm34_poi, cm35_poi, cm36_poi, cm37_poi, cm38_poi, cm39_poi, cm40_poi, cm41_poi, cm42_poi, cm43_poi, cm44_poi, cm45_poi, cm46_poi, cm47_poi, cm48_poi, cm49_poi, cm50_poi, cm51_poi, cm52_poi, cm53_poi, cm54_poi, cm55_poi, cm56_poi, cm57_poi, cm58_poi, cm59_poi, cm60_poi, cm61_poi, cm62_poi, cm63_poi, cm64_poi, cm65_poi, cm66_poi, cm67_poi, cm68_poi, cm69_poi, cm70_poi, cm71_poi,  cm72_poi, cm73_poi, cm74_poi, cm75_poi, cm76_poi, cm77_poi, cm78_poi, cm79_poi, cm80_poi, cm81_poi, cm82_poi, cm83_poi, cm84_poi, cm85_poi, cm86_poi, cm87_poi, cm88_poi, cm89_poi, cm90_poi, cm91_poi, cm92_poi, cm93_poi, cm94_poi, cm95_poi, cm96_poi, cm97_poi, cm98_poi, cm99_poi, cm100_poi, cm101_poi, cm102_poi, cm103_poi, cm104_poi, cm105_poi, cm106_poi, cm107_poi, cm108_poi, cm109_poi, cm110_poi) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);"
  
      cur.execute(query,('jmensa', id_route, maree, lance, id_species, t_taille_poi, t_taille_cre, poids, cm5_poi, cm6_poi, cm7_poi, cm8_poi, cm9_poi, cm10_poi, cm11_poi, cm12_poi, cm13_poi, cm14_poi, cm15_poi, cm16_poi, cm17_poi, cm18_poi, cm19_poi, cm20_poi, cm21_poi, cm22_poi, cm23_poi, cm24_poi, cm25_poi, cm26_poi, cm27_poi, cm28_poi, cm29_poi, cm30_poi, cm31_poi, cm32_poi, cm33_poi, cm34_poi, cm35_poi, cm36_poi, cm37_poi, cm38_poi, cm39_poi, cm40_poi, cm41_poi, cm42_poi, cm43_poi, cm44_poi, cm45_poi, cm46_poi, cm47_poi, cm48_poi, cm49_poi, cm50_poi, cm51_poi, cm52_poi, cm53_poi, cm54_poi, cm55_poi, cm56_poi, cm57_poi, cm58_poi, cm59_poi, cm60_poi, cm61_poi, cm62_poi, cm63_poi, cm64_poi, cm65_poi, cm66_poi, cm67_poi, cm68_poi, cm69_poi, cm70_poi, cm71_poi, cm72_poi, cm73_poi, cm74_poi, cm75_poi, cm76_poi, cm77_poi, cm78_poi, cm79_poi, cm80_poi, cm81_poi, cm82_poi, cm83_poi, cm84_poi, cm85_poi, cm86_poi, cm87_poi, cm88_poi, cm89_poi, cm90_poi, cm91_poi, cm92_poi, cm93_poi, cm94_poi, cm95_poi, cm96_poi, cm97_poi, cm98_poi, cm99_poi, cm100_poi, cm101_poi, cm102_poi, cm103_poi, cm104_poi, cm105_poi, cm106_poi, cm107_poi, cm108_poi, cm109_poi, cm110_poi))
      conn.commit()

    else:
     print lines[2]
      
cur.close()
conn.close()
