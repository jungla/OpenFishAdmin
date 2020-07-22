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

file_t_sex = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/trawlers_T_SEX.csv_out'
file_t_maturity = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/trawlers_T_MATURITY.csv_out'

file_obs = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/fishery/fishery_SPECIES_CONV.csv_out'

years = ['2016','2017','2018']
#years = ['2018']

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
 filename = 'trawlers_FT_CRE.csv_out'

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

   if lines[2] is not None:
    obs_species = peche_sql.convert_OBS(lines[2], file_obs)

    id_species = peche_sql.findUUID_species(obs_species,conn)

    if len(id_species) > 0:
     id_species = str(id_species[0][0])


     sex = peche_sql.findID(lines[3],file_t_sex,1)
     poids = lines[4]
     maturity = peche_sql.findID(lines[5],file_t_maturity,1)
  
     ## Execute a command: this creates a new table
 
     ft_cre = lines[7:]
  
     if len(id_route) > 0:
  
      query = "INSERT INTO trawlers.ft_cre (username, id_route, maree, lance, id_species, t_sex, t_maturity, poids, \
ft1_cre, ft2_cre, ft3_cre, ft4_cre, ft5_cre, ft6_cre, ft7_cre, ft8_cre, ft9_cre, ft10_cre, \
ft11_cre, ft12_cre, ft13_cre, ft14_cre, ft15_cre, ft16_cre, ft17_cre, ft18_cre, ft19_cre, \
ft20_cre, ft21_cre, ft22_cre, ft23_cre, ft24_cre, ft25_cre, ft26_cre, ft27_cre, ft28_cre, ft29_cre, \
ft30_cre, ft31_cre, ft32_cre, ft33_cre, ft34_cre, ft35_cre, ft36_cre, ft37_cre, ft38_cre, ft39_cre, \
ft40_cre, ft41_cre, ft42_cre, ft43_cre, ft44_cre, ft45_cre, ft46_cre, ft47_cre, ft48_cre, ft49_cre, \
ft50_cre, ft51_cre, ft52_cre, ft53_cre, ft54_cre, ft55_cre, ft56_cre, ft57_cre, ft58_cre, ft59_cre, \
ft60_cre, ft61_cre, ft62_cre, ft63_cre, ft64_cre, ft65_cre, ft66_cre, ft67_cre, ft68_cre, ft69_cre, \
ft70_cre, ft71_cre, ft72_cre, ft73_cre, ft74_cre, ft75_cre, ft76_cre, ft77_cre, ft78_cre, ft79_cre, \
ft80_cre, ft81_cre, ft82_cre, ft83_cre, ft84_cre, ft85_cre, ft86_cre, ft87_cre, ft88_cre, ft89_cre, \
ft90_cre, ft91_cre, ft92_cre, ft93_cre, ft94_cre, ft95_cre, ft96_cre, ft97_cre, ft98_cre, ft99_cre, \
ft100_cre) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);"
  
      #   
#      print query
      cur.execute(query,('jmensa', id_route, maree, lance, id_species, sex, maturity, poids, \
ft_cre[0], ft_cre[1], ft_cre[2], ft_cre[3], ft_cre[4], ft_cre[5], ft_cre[6], ft_cre[7], ft_cre[8], ft_cre[9], ft_cre[10], 
ft_cre[11], ft_cre[12], ft_cre[13], ft_cre[14], ft_cre[15], ft_cre[16], ft_cre[17], ft_cre[18], ft_cre[19], ft_cre[20], 
ft_cre[21], ft_cre[22], ft_cre[23], ft_cre[24], ft_cre[25], ft_cre[26], ft_cre[27], ft_cre[28], ft_cre[29], ft_cre[30], 
ft_cre[31], ft_cre[32], ft_cre[33], ft_cre[34], ft_cre[35], ft_cre[36], ft_cre[37], ft_cre[38], ft_cre[39], ft_cre[40], 
ft_cre[41], ft_cre[42], ft_cre[43], ft_cre[44], ft_cre[45], ft_cre[46], ft_cre[47], ft_cre[48], ft_cre[49], ft_cre[50], 
ft_cre[51], ft_cre[52], ft_cre[53], ft_cre[54], ft_cre[55], ft_cre[56], ft_cre[57], ft_cre[58], ft_cre[59], ft_cre[60], 
ft_cre[61], ft_cre[62], ft_cre[63], ft_cre[64], ft_cre[65], ft_cre[66], ft_cre[67], ft_cre[68], ft_cre[69], ft_cre[70], 
ft_cre[71], ft_cre[72], ft_cre[73], ft_cre[74], ft_cre[75], ft_cre[76], ft_cre[77], ft_cre[78], ft_cre[79], ft_cre[80], 
ft_cre[81], ft_cre[82], ft_cre[83], ft_cre[84], ft_cre[85], ft_cre[86], ft_cre[87], ft_cre[88], ft_cre[89], ft_cre[90], 
ft_cre[91], ft_cre[92], ft_cre[93], ft_cre[94], ft_cre[95], ft_cre[96], ft_cre[97], ft_cre[98], ft_cre[99]
))
      conn.commit()

    else:

     print lines[2]
      
cur.close()
conn.close()
