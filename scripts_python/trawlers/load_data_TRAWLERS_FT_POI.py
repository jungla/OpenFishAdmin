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
file_t_measure = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/trawlers_T_MEASURE.csv_out'

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
 filename = 'trawlers_FT_POI.csv_out'

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

   id_route = findUUID_route(maree,lance,conn)

   if lines[2] == 'RET':
    rejete = 1
   elif lines[2] == 'REJ':
    rejete = 0
   else:
    rejete = lines[2]

   if lines[3] is not None:
    id_species = peche_sql.findUUID_species(lines[3],conn)

    if len(id_species) == 0:
     obs_species = peche_sql.convert_OBS(lines[3], file_obs)
     id_species = peche_sql.findUUID_species(obs_species,conn)

    if len(id_species) > 0:
     id_species = str(id_species[0][0])

     if lines[4] == 'LA' or lines[4] == 'LF' or lines[4] == 'LMF' or lines[4] == 'LT' or lines[4] == 'LPA':
      measure = peche_sql.findID(lines[4],file_t_measure,1)
     else:
      measure = lines[4]

     poids = lines[5]

     ## Execute a command: this creates a new table

     ft1_poi = lines[6]
     ft2_poi = lines[7]
     ft3_poi = lines[8]
     ft4_poi = lines[9]
     ft5_poi = lines[10]
     ft6_poi = lines[11]
     ft7_poi = lines[12]
     ft8_poi = lines[13]
     ft9_poi = lines[14]
     ft10_poi = lines[15]
     ft11_poi = lines[16]
     ft12_poi = lines[17]
     ft13_poi = lines[18]
     ft14_poi = lines[19]
     ft15_poi = lines[20]
     ft16_poi = lines[21]
     ft17_poi = lines[22]
     ft18_poi = lines[23]
     ft19_poi = lines[24]
     ft20_poi = lines[25]
     ft21_poi = lines[26]
     ft22_poi = lines[27]
     ft23_poi = lines[28]
     ft24_poi = lines[29]
     ft25_poi = lines[30]
     ft26_poi = lines[31]
     ft27_poi = lines[32]
     ft28_poi = lines[33]
     ft29_poi = lines[34]
     ft30_poi = lines[35]
     ft31_poi = lines[36]
     ft32_poi = lines[37]
     ft33_poi = lines[38]
     ft34_poi = lines[39]
     ft35_poi = lines[40]
     ft36_poi = lines[41]
     ft37_poi = lines[42]
     ft38_poi = lines[43]
     ft39_poi = lines[44]
     ft40_poi = lines[45]
     ft41_poi = lines[46]
     ft42_poi = lines[47]
     ft43_poi = lines[48]
     ft44_poi = lines[49]
     ft45_poi = lines[50]
     ft46_poi = lines[51]
     ft47_poi = lines[52]
     ft48_poi = lines[53]
     ft49_poi = lines[54]
     ft50_poi = lines[55]
     ft51_poi = lines[56]
     ft52_poi = lines[57]
     ft53_poi = lines[58]
     ft54_poi = lines[59]
     ft55_poi = lines[60]
     ft56_poi = lines[61]
     ft57_poi = lines[62]
     ft58_poi = lines[63]
     ft59_poi = lines[64]
     ft60_poi = lines[65]
     ft61_poi = lines[66]
     ft62_poi = lines[67]
     ft63_poi = lines[68]
     ft64_poi = lines[69]
     ft65_poi = lines[70]
     ft66_poi = lines[71]
     ft67_poi = lines[72]
     ft68_poi = lines[73]
     ft69_poi = lines[74]
     ft70_poi = lines[75]
     ft71_poi = lines[76]
     ft72_poi = lines[77]
     ft73_poi = lines[78]
     ft74_poi = lines[79]
     ft75_poi = lines[80]
     ft76_poi = lines[81]
     ft77_poi = lines[82]
     ft78_poi = lines[83]
     ft79_poi = lines[84]
     ft80_poi = lines[85]
     ft81_poi = lines[86]
     ft82_poi = lines[87]
     ft83_poi = lines[88]
     ft84_poi = lines[89]
     ft85_poi = lines[90]
     ft86_poi = lines[91]
     ft87_poi = lines[92]
     ft88_poi = lines[93]
     ft89_poi = lines[94]
     ft90_poi = lines[95]
     ft91_poi = lines[96]
     ft92_poi = lines[97]
     ft93_poi = lines[98]
     ft94_poi = lines[99]
     ft95_poi = lines[90]
     ft96_poi = lines[101]
     ft97_poi = lines[102]
     ft98_poi = lines[103]
     ft99_poi = lines[104]
     ft100_poi = lines[105]
     ft101_poi = lines[106]
     ft102_poi = lines[107]
     ft103_poi = lines[108]
     ft104_poi = lines[109]
     ft105_poi = lines[110]
     ft106_poi = lines[111]
     ft107_poi = lines[112]
     ft108_poi = lines[113]
     ft109_poi = lines[114]
     ft110_poi = lines[115]
     ft111_poi = lines[116]
     ft112_poi = lines[117]


     if len(id_route) > 0:

      query = "INSERT INTO trawlers.ft_poi (username, id_route, maree, lance, t_rejete, id_species, t_measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi,ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, ft70_poi, ft71_poi,  ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, ft109_poi, ft110_poi, ft111_poi, ft112_poi) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);"

      #   
#      print query
      cur.execute(query,('jmensa', id_route, maree, lance, rejete, id_species, measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi, ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, ft70_poi, ft71_poi, ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, ft109_poi, ft110_poi, ft111_poi, ft112_poi))
      conn.commit()

    else:

     print lines[3]
 
cur.close()
conn.close()
