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

years = ['2016','2017','2018']
#years = ['2018']

query = "SELECT id, navire FROM vms.navire;"

cur.execute(query)
vms_navire = np.asarray(cur.fetchall())

for i in range(len(vms_navire[:,1])):
 vms_navire[i,1] = vms_navire[i,1].replace('\xc3\xaa','e').replace(' ','').title()

file_obs = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/fishery/fishery_SPECIES_CONV.csv_out'

def findUUID_route(maree,lance,conn):
 if maree is not None and lance is not None:
  cur = conn.cursor()
  query = "SELECT id FROM trawlers.route WHERE maree='"+maree+"' AND lance='"+lance+"' "
  #print query
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

 if year == '2016':
  #2016
  species_c = ['PEN','PEN/Pen.ker','PEN/Par.lon','PEN/Pen.not','PAN','PAN/Het.ens','PAN/Ple.mar','SIC','SIC/Sic.car','SIC/Sic.gal','SQU','SQU/Squ.man','POI','ACA/Aca.mon','ALB/Alb.vul','ARI','ARI/Ari.gig','ARI/Ari.heu','ARI/Ari.par','BAL','BAL/Bal.cap','BAL/Bal.vet','BAT','BAT/Bat.lib','BEL','BEL/Abi.hia','BOT','BRA/Bra.sem','CAR','CAR/Ale.ale','CAR/Ale.cil','CAR/Car.cry','CAR/Car.hip','CAR/Car.sen','CAR/Chl.chr','CAR/Dec.mac','CAR/Dec.pun','CAR/Dec.rho','CAR/Ele.bip','CAR/Sel.dor','CAR/Ser.car','CAR/Tra.gor','CAR/Tra','CEN','CEN/Spi.alt','CHA','CIT/Cit.lin','CLU','CLU/Ili.afr','CLU/Sar.aur','CLU/Sar.mad','CON','CYN','CYN/Cyn.cad','CYN/Cyn.can','CYN/Cyn.mon','DAC/Dac.vol','DIO','DRE/Dre.afr','ECH','ELO','ENG','EMM/Ery.mon','EPH','FIS','FIS/Fis.pet','FIS/Fis.tab','HAE','HAE/Bra.aur','HAE/Pom.jub','HAE/Pom.rog','HOL','KYP','KYP/Kyp.inc','LAB','LET','LOP','LUT','LUT/Aps.fus','LUT/Lut.den','LUT/Luj.gor','MON','MOR','MUG','MUL','MURAENE','MUR','MUR/Cha.vit','OPHIC','OPHIC/Myr.par','OPHID','OST','PLA/Gra.gru','POL','POL/Gal.dec','PRI/Pri.are','PSE/Pse.bel','RAC/Rac.can','SCA','SCI','SCI/Arg.reg','SCI/Pse.bra','SCI/Pse.elo','SCI/Pse.sen','SCI/Pse.typ','SCI/Umb.can','SCOM','SCOM/Aux','SCOM/Aux.roc','SCOM/Dec.mac','SCOM/Eut.all','SCOM/Sar.sar','SCOM/Sco.jap','SCOM/Sco.tri','SCOR','SCOR/Pon/kuh','SER','SER/Epi.aen','SER/Ser.acr','SER/Ser.cab','SOL','SOL/Dic.cun','SOL/Dic.hex','SOL/Mic.oce','SOL/Peg.las','SPA','SPA/Boo.boo','SPA/Den.ang','SPA/Den.can','SPA/Den.con','SPA/Den.gib','SPA/Lit.mor','SPA/Pag.aca','SPA/Pag.bel','SPA/Pag.bog','SPA/Pag.aur','SPA/Obl.mel','SPA/Spa.aur','SPA/Spo.can','SPH','SPH/Sph.bar','STR/Str.fia','SYNG','SYNO','SYNO/Tra.myo','TET','TET/Eph.gut','TET/Lag.lae','TRA','TRIC','TRIC/Tric.lep','TRIG','URA','ZEI','ZEI/Zen.con','ZEI/Zeu.fab','REQ/Sph','RAI','RAI/Das','RAI/Das.cen','RAI/Das.mar','RAI/Zan.sch','RAI/Raj','RAI/Raj.alb','RAI/Raj.mir','RAI/Raj.str','RAI/Rhi','RAI/Rhi.alb','RAI/Rhi.cem','RAI/Rhi.rhi','RAI/Tor.mar','PAL','SCY/Scy.her','CRAB','CRAB/Cal','HOM/Par.cuv','CRAB/Por','HOLOTHURIOIDEA','XOur','ESC','OCT','OCT/Oct.def','SEP','TEU','CNI','GAS','XBOU','XBOI','XCAI','XDEC']

 elif year == '2017':
  species_c = ['PEN','PEN/Pen.ker','PEN/Par.lon','PEN/Pen.not','PAN','PAN/Het.ens','PAN/Ple.mar','SIC','SIC/Sic.car','SIC/Sic.gal','SQU','SQU/Squ.man','POI','ACA/Aca.mon','ALB/Alb.vul','ANT','ARI','ARI/Ari.gig','ARI/Ari.heu','ARI/Ari.par','ARIO','BAL','BAL/Bal.cap','BAL/Bal/vet','BAT','BAT/Bat.lib','BEL','BEL/Abi.hia','BOT','BRA/Bra.sem','CAR','CAR/Ale.ale','CAR/Ale.cil','CAR/Car.cry','CAR/Car.fis','CAR/Car.hip','CAR/Car.sen','CAR/Chl.chr','CAR/Dec.mac','CAR/Dec.pun','CAR/Dec.rho','CAR/Ele.bip','CAR/Sel.dor','CAR/Ser.car','CAR/Tra.gor','CAR/Tra','CAR/Ura.hel','CEN','CEN/Spi.alt','CHA','CIT/Cit.lin','CLU','CLU/Ili.afr','CLU/Sar.aur','CLU/Sar.mad','CON','CYN','CYN/Cyn.cad','CYN/Cyn.can','CYN/Cyn.mon','DAC/Dac.vol','DIO','DRE/Dre.afr','ECH','ELO','ENG','EMM/Ery.mon','EPH','FIS','FIS/Fis.pet','FIS/Fis.tab','GER','HAE','HAE/Bra.aur','HAE/Pom.jub','HAE/Pom.per','HAE/Pom.rog','HOL','KYP','KYP/Kyp.inc','LAB','LET','LUT','LUT/Aps.fus','LUT/Lut.den','LUT/Lut.ful','LUT/Lut.gor','MON','MUG','MUL','MUR','MUR/Cha.vit','OPHIC','OPHIC/Myr.par','OPHID','OST','PLA/Gra.gru','POL','POL/Gal.dec','PRI/Pri.are','PSE/Pse.bel','SCA','SCI','SCI/Arg.reg','SCI/Pse.bra','SCI/Pse.elo','SCI/Pse.sen','SCI/Pse.typ','SCI/Umb.can','SCOM','SCOM/Aux','SCOM/Aux.roc','SCOM/Dec.mac','SCOM/Eut.all','SCOM/Kat.pel','SCOM/Sco.jap','SCOM/Sco.tri','SCOR','SCOR/Pon.kuh','SER','SER/Ant.ant','SER/Epi.aen','SER/Ser.acc','SER/Ser.cab','SOL','SOL/Bat.lac','SOL/Dic.cun','SOL/Dic.hex','SOL/Mic.oce','SOL/Peg.las','SPA','SPA/Boo.boo','SPA/Den.ang','SPA/Den.can','SPA/Den.con','SPA/Den.gib','SPA/Den.macroc','SPA/Den.macrop','SPA/Lit.mor','SPA/Pag.aca','SPA/Pag.bel','SPA/Pag.bog','SPA/Pag.afr','SPA/Pag.aur','SPA/Pag.cae','SPA/Obl.mel','SPA/Sap.aur','SPA/Spo.can','SPH','SPH/Sph.bar','STR/Str.fia','SYNG','SYNO','SYNO/Tra.myo','TET','TET/Eph.gut','TET/Lag.lae','TRA','TRIC','TRIC/Tric.lep','TRIG','TRIG/Che.las','URA','ZEI','ZEI/Zen.con','ZEI/Zeu.fab','REQ/Sph','RAI','RAI/Das','RAI/Das.cen','RAI/Das.mar','RAI/Zan.sch','RAI/Raj','RAI/Raj.alb','RAI/Raj.mir','RAI/Raj.str','RAI/Rhi','RAI/Rhi.alb','RAI/Rhi.cem','RAI/Rhi.rhi','RAI/Tor.mar','PAL','SCY/Scy.her','CRAB','CRAB/Cal','CRAB/Por','ESC','AST','HOL','GOR','OCT','OCT/Oct.def','SEP','TEU','CNI','GAS','XBOU','XBOI','XCAI','XOUR','XDEC']

 elif year == '2018':
  species_c = ['PEN','PEN/Pen.ker','PEN/Par.lon','PEN/Pen.not','PAN','PAN/Het.ens','PAN/Ple.mar','SIC','SIC/Sic.car','SIC/Sic.gal','SQU','SQU/Squ.man','POI','ACA/Aca.mon','ALB/Alb.vul','ANT','ARI','ARI/Ari.gig','ARI/Ari.heu','ARI/Ari.par','ARIO','BAL','BAL/Bal.cap','BAL/Bal.vet','BAT','BAT/Bat.lib','BEL','BEL/Abi.hia','BOT','BRA/Bra.sem','CAR','CAR/Ale.ale','CAR/Ale.cil','CAR/Car.cry','CAR/Car.fis','CAR/Car.hip','CAR/Car.sen','CAR/Chl.chr','CAR/Dec.mac','CAR/Dec.pun','CAR/Dec.rho','CAR/Ele.bip','CAR/Sel.dor','CAR/Ser.car','CAR/Tra.gor','CAR/Tra','CAR/Ura.hel','CEN','CEN/Spi.alt','CHA','CIT/Cit.lin','CLU','CLU/Ili.afr','CLU/Sar.aur','CLU/Sar.mad','CON','CYN','CYN/Cyn.cad','CYN/Cyn.can','CYN/Cyn.mon','DAC/Dac.vol','DIO','DRE/Dre.afr','ECH','ELO','ENG','EMM/Ery.mon','EPH','FIS','FIS/Fis.pet','FIS/Fis.tab','GER','HAE','HAE/Bra.aur','HAE/Pom.jub','HAE/Pom.per','HAE/Pom.rog','HOL','KYP','KYP/Kyp.inc','LAB','LET','LUT','LUT/Aps.fus','LUT/Lut.den','LUT/Lut.ful','LUT/Lut.gor','MON','MUG','MUL','MUR','MUR/Cha.vit','OPHIC','OPHIC/Myr.par','OPHID','OST','PLA/Gra.gru','POL','POL/Gal.dec','PRI/Pri.are','PSE/Pse.bel','SCA','SCI','SCI/Arg.reg','SCI/Pse.bra','SCI/Pse.elo','SCI/Pse.sen','SCI/Pse.typ','SCI/Umb.can','SCOM','SCOM/Aux','SCOM/Aux.roc','SCOM/Dec.mac','SCOM/Eut.all','SCOM/Kat.pel','SCOM/Sco.jap','SCOM/Sco.tri','SCOR','SCOR/Pon.kuh','SER','SER/Ant.ant','SER/Epi.aen','SER/Ser.acc','SER/Ser.cab','SOL','SOL/Bat.lac','SOL/Dic.cun','SOL/Dic.hex','SOL/Mic.oce','SOL/Peg.las','SPA','SPA/Boo.boo','SPA/Den.ang','SPA/Den.can','SPA/Den.con','SPA/Den.gib','SPA/Den.macroc','SPA/Den.macrop','SPA/Lit.mor','SPA/Pag.aca','SPA/Pag.bel','SPA/Pag.bog','SPA/Pag.afr','SPA/Pag.aur','SPA/Pag.cae','SPA/Obl.mel','SPA/Spa.aur','SPA/Spo.can','SPH','SPH/Sph.bar','STR/Str.fia','SYNG','SYNO','SYNO/Tra.myo','TET','TET/Eph.gut','TET/Lag.lae','TRA','TRIC','TRIC/Tric.lep','TRIG','TRIG/Che.las','URA','ZEI','ZEI/Zen.con','ZEI/Zeu.fab','REQ/Sph','RAI','RAI/Das','RAI/Das.cen','RAI/Das.mar','RAI/Zan.sch','RAI/Raj','RAI/Raj.alb','RAI/Raj.mir','RAI/Raj.str','RAI/Rhi','RAI/Rhi.alb','RAI/Rhi.cem','RAI/Rhi.rhi','RAI/Tor.mar','PAL','SCY/Scy.her','CRAB','CRAB/Cal','CRAB/Por','ESC','AST','HOL','GOR','OCT','OCT/Oct.def','SEP','TEU','CNI','GAS','XBOU','XBOI','XCAI','XOUR','XDEC','BAL/Xen.pun','CYN/Cyn.bro','EXO','HAE/Pom.inc','LUT/Lut.age','POL/Pol.qua','SCI/Pen.mbi','SCI/Pte.pel','SCI/Umb.ron','SCOM/Sar.sar','SER/Epi.gor','SPA/Sar.sal','TET/Lag.Lag','TRIG/Trig.lyr','TRIG/Che.gab']


 path = os.environ['HOME']+'/Google_Drive/Gabon_Bleu/_database/CSV_data/trawlers/'+year+'/'
 filename = 'trawlers_CAPTURES.csv_out'

 with open(path+filename, 'rb') as csvfile:
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

   if len(id_route) > 0:

    species = lines[2:-1]  
 
    for s in range(len(species)):
     if species[s] is not None:
      poids = species[s]

      t_species = species_c[s].lower()

      obs_species = peche_sql.convert_OBS(t_species, file_obs)
   
      id_species = peche_sql.findUUID_species(obs_species, conn)
   
      if len(id_species) > 0:
       id_species = str(id_species[0][0])
 
       ## Execute a command: this creates a new table
       query = "INSERT INTO trawlers.captures (username, maree, lance, id_route, id_species, poids, comment) VALUES (%s,%s,%s,%s,%s,%s,%s);"
     #   
#       print query
       cur.execute(query,('jmensa', maree, lance, id_route, id_species, poids,''))
       conn.commit()

      else:
       print t_species
   
cur.close()
conn.close()
