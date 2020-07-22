import matplotlib
matplotlib.use('Agg')
import glob
import psycopg2
import numpy as np
from mpl_toolkits.basemap import Basemap
import matplotlib.pyplot as plt
import datetime
from scipy import interpolate
import numpy as np
import os

import matplotlib.pyplot as plt
import matplotlib.patheffects as PathEffects
from matplotlib.patches import Polygon
from matplotlib.collections import PatchCollection
from matplotlib.patches import PathPatch

from fpdf import FPDF
from datetime import datetime, timedelta

conn = psycopg2.connect('dbname=geospatialdb user=postgres')
cur = conn.cursor()

query = 'SELECT DISTINCT infraction.id, infraction.username, infraction.datetime::date, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, owner_t_card, owner_ycard, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_ycard_1, fish_t_nationality_1, fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_ycard_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_ycard_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, fish_idcard_4, fish_t_card_4, fish_ycard_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, amount, payment, n_dep, n_cdc, n_lib, comments, ST_X(location), ST_Y(location), settled, infraction.datetime FROM infraction.infraction LEFT JOIN infraction.t_org ON infraction.t_org.id = infraction.infraction.t_org WHERE EXTRACT(MONTH FROM infraction.datetime) = 12.0'
#query = "SELECT DISTINCT infraction.id, infraction.username, infraction.datetime::date, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, owner_t_card, owner_ycard, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_ycard_1, fish_t_nationality_1, fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_ycard_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_ycard_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, fish_idcard_4, fish_t_card_4, fish_ycard_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, amount, payment, n_dep, n_cdc, n_lib, comments, ST_X(location), ST_Y(location), settled, infraction.datetime FROM infraction.infraction LEFT JOIN infraction.t_org ON infraction.t_org.id = infraction.infraction.t_org WHERE EXTRACT(MONTH FROM infraction.datetime) = EXTRACT(MONTH FROM CURRENT_DATE - INTERVAL '1 DAY')"

cur.execute(query)
infractions = np.asarray(cur.fetchall())

month = (datetime.today() - timedelta(days=1)).month
year = (datetime.today() - timedelta(days=1)).year
month_l = ['janvier', 'fevrier', 'mars', 'avril', 'mai', 'juin', 'juillet', 'aout', 'septembre', 'octobre', 'novembre', 'decembre']

def zones(z):
 if z == 0:
  lat_0 = -1
  lat_1 = 1
  lon_0 = 7
  lon_1 = 10
  label = 'Nord'
 elif z == 1:
  lat_0 = -3
  lat_1 = -1
  lon_0 = 7
  lon_1 = 10
  label = 'Centre'
 elif z == 2:
  lat_0 = -5
  lat_1 = -3
  lon_0 = 9.25
  lon_1 = 11.5
  label = 'Sud-Est'
 elif z == 3:
  lat_0 = -6
  lat_1 = -3
  lon_0 = 7
  lon_1 = 9.25
  label = 'Sud-Ouest'
 elif z == 99:
  lat_0 = -7
  lat_1 = 1
  lon_0 = 5
  lon_1 = 12
  label = 'Complet'
 return lat_0,lat_1,lon_0,lon_1,label

pdf = FPDF()
pdf.add_page(orientation='P')
#pdf.add_font('Raleway', '', os.environ['HOME']+'/_database/scripts_python/infraction/Raleway-Regular.ttf', uni=True)
#pdf.add_font('Raleway-Bold', '', os.environ['HOME']+'/_database/scripts_python/infraction/Raleway-Bold.ttf', uni=True)
pdf.add_font('Raleway', '', 'Raleway-Regular.ttf', uni=True)
pdf.add_font('Raleway-Bold', '', 'Raleway-Bold.ttf', uni=True)

#\definecolor{webBleu}{RGB}{78,140,246}
#\definecolor{webGrey}{RGB}{228,240,242}

pdf.set_font('Raleway-Bold', '', 36)
pdf.set_text_color(78,140,246)
pdf.cell(100, 10, 'Rapport d\'Infractions Mensuel',border=0,align='l',ln=1)
pdf.set_font('Raleway', '', 18)
pdf.set_text_color(0,0,0)
pdf.cell(100, 10, 'genere a partir de la base de donnees data.gabonbleu.org',border=0,ln=1,align='l')
pdf.set_font('Raleway-Bold', '', 18)
pdf.cell(100, 10, 'Liste des infractions enregistrees en '+month_l[month-1]+' '+str(year),border=0,ln=1,align='l')

pdf.set_font('Helvetica', '', 11)

lh = 4

lons = []
lats = []
labels = []

for infraction in infractions:

 # date

 pdf.ln(2)

 pdf.set_font('Raleway-Bold', '', 13)
 pdf.set_text_color(78,140,246)

 # date infraction
 

 # immatriculation and name pirogue

 label = ''

 if (infraction[7] is not None) :
  query = 'SELECT name, immatriculation FROM artisanal.pirogue WHERE id = \''+infraction[7]+'\''
  cur.execute(query)
  result = cur.fetchall()
  if len(result) > 0:  
   pirogue_name = np.asarray(result)[0]
   if pirogue_name[0] is not None and pirogue_name[1] is not None:
    pdf.cell(50,lh,pirogue_name[1]+' '+pirogue_name[0],border=0)
    label = pirogue_name[1]+' '+pirogue_name[0]
   elif pirogue_name[0] is not None: 
    pdf.cell(50,lh,pirogue_name[0],border=0)
    label = pirogue_name[0]
   elif pirogue_name[1] is not None: 
    pdf.cell(50,lh,pirogue_name[1],border=0)
    label = pirogue_name[1]
 else :
  if infraction[9] is not None:
   pdf.cell(30,lh,infraction[9])
   label = label + ' ' + infraction[9]
  if infraction[8] is not None:
   pdf.cell(30,lh,infraction[8])
#   label = label + ' ' + infraction[8]



 pdf.set_font('Helvetica', '', 11)
 pdf.set_text_color(0,0,0)
 pdf.ln(5)
 if infraction[4] is not None:
  pdf.cell(27,lh,'date infraction : '+infraction[4].isoformat(),border=0,ln=1)
 pdf.cell(27,lh,'date d\'enregistrement: '+infraction[2].isoformat(),border=0)

 pdf.ln(5)
 if infraction[5] is not None:
  pdf.cell(30,lh,'Mission : '+infraction[5],border=0)
 pdf.ln(2)

 # infractions

 query = 'SELECT t_infraction.infraction FROM infraction.infractions LEFT JOIN infraction.t_infraction ON infraction.t_infraction.id = infraction.infractions.t_infraction WHERE id_infraction =\''+infraction[0]+'\''
 cur.execute(query)
 t_infractions = np.asarray(cur.fetchall())

 pdf.ln(lh)
 pdf.set_font('Helvetica', 'B', 11)
 pdf.cell(50,lh,'Type Infraction:',border=0) 
 pdf.set_font('Helvetica', '', 11)
 for t_infraction in t_infractions:
  pdf.ln(lh)
  pdf.cell(50,lh,t_infraction[0],border=0) 

 pdf.ln(2)
 pdf.set_font('Helvetica', '', 11)
 # owner
 if (infraction[10] is not None or infraction[11] is not None or infraction[12] is not None) :
  pdf.ln(lh)
  pdf.cell(30,lh,'Proprietaire : ',border=0)

 if (infraction[10] is not None) :
  query = 'SELECT first_name, last_name FROM artisanal.owner WHERE id = \''+infraction[10]+'\''
  cur.execute(query)
  owner_name = np.asarray(cur.fetchall())[0]
  if owner_name[0] is not None and owner_name[1] is not None:
   pdf.cell(30,lh,owner_name[1].upper()+' '+owner_name[0].capitalize(),border=0)
  elif owner_name[0] is not None:
   pdf.cell(30,lh,owner_name[0].capitalize(),border=0)
  elif owner_name[1] is not None:
   pdf.cell(30,lh,owner_name[1].upper(),border=0)
 else :
  if (infraction[12] is not None) :
   pdf.cell(30,lh,infraction[12].upper(),border=0)
  if (infraction[11] is not None) :
   pdf.cell(30,lh,infraction[11].capitalize(),border=0)


 if (infraction[18] is not None or infraction[19] is not None or infraction[20] is not None) :
  pdf.ln(lh)
  pdf.cell(30,lh,'Pecheur 1 : ',border=0)

 if (infraction[18] is not None) :
  query = 'SELECT first_name, last_name FROM artisanal.fisherman WHERE id = \''+infraction[18]+'\''
  cur.execute(query)
  fisherman_name = np.asarray(cur.fetchall())[0]

  pdf.cell(30,lh,fisherman_name[1].upper()+' '+fisherman_name[0].capitalize(),border=0)
 else:
  if (infraction[20] is not None) :
   pdf.cell(30,lh,infraction[20].upper(),border=0)
  if (infraction[19] is not None) :
   pdf.cell(30,lh,infraction[19].capitalize(),border=0)


 if (infraction[26] is not None or infraction[27] is not None or infraction[28] is not None) :
  pdf.ln(lh)
  pdf.cell(30,lh,'Pecheur 2 : ',border=0)

 if (infraction[26] is not None) :
  query = 'SELECT first_name, last_name FROM artisanal.fisherman WHERE id = \''+infraction[26]+'\''
  cur.execute(query)
  fisherman_name = np.asarray(cur.fetchall())[0]

  pdf.cell(30,lh,fisherman_name[1].upper()+' '+fisherman_name[0].capitalize(),border=0)
 else:
  if (infraction[28] is not None) :
   pdf.cell(30,lh,infraction[28].upper(),border=0)
  if (infraction[27] is not None) :
   pdf.cell(30,lh,infraction[27].capitalize(),border=0)


 if (infraction[34] is not None or infraction[35] is not None or infraction[36] is not None) :
  pdf.ln(lh)
  pdf.cell(30,lh,'Pecheur 3 : ',border=0)

 if (infraction[34] is not None) :
  query = 'SELECT first_name, last_name FROM artisanal.fisherman WHERE id = \''+infraction[34]+'\''
  cur.execute(query)
  fisherman_name = np.asarray(cur.fetchall())[0]

  pdf.cell(30,lh,fisherman_name[1].upper()+' '+fisherman_name[0].capitalize(),border=0)
 else:
  if (infraction[36] is not None) :
   pdf.cell(30,lh,infraction[36].upper(),border=0)
  if (infraction[35] is not None) :
   pdf.cell(30,lh,infraction[35].capitalize(),border=0)


 if (infraction[42] is not None or infraction[43] is not None or infraction[44] is not None) : 
  pdf.ln(lh)
  pdf.cell(30,lh,'Pecheur 4 : ',border=0)

 if (infraction[42] is not None) :
  query = 'SELECT first_name, last_name FROM artisanal.fisherman WHERE id = \''+infraction[42]+'\''
  cur.execute(query)
  fisherman_name = np.asarray(cur.fetchall())[0]

  pdf.cell(30,lh,fisherman_name[1].upper()+' '+fisherman_name[0].capitalize(),border=0)
 else:
  if (infraction[44] is not None) :
   pdf.cell(30,lh,infraction[44].upper(),border=0)
  if (infraction[43] is not None) :
   pdf.cell(30,lh,infraction[43].capitalize(),border=0)

 
# print '<td rowspan=$nrows>infraction[55]</td>'
# print '<td rowspan=$nrows>infraction[56]</td>'

 pdf.ln(lh)
 pdf.cell(30,lh,'Montant infraction : '+str(infraction[55]),border=0,ln=1)

 pdf.set_font('Helvetica', 'B', 11)
 if infraction[63] == 't' or infraction[63] is True :
  pdf.cell(30,lh,'REGLEE',border=0)
 elif infraction[63] is not None :
  pdf.cell(30,lh,'PAS REGLEE',border=0)
# else :
#  pdf.cell(30,lh,'PAS REGLEE',border=0)
 
 pdf.set_font('Helvetica', '', 11)

 pdf.ln(lh+3)

 ''' print map when coords exists '''

 if label != '' and infraction[61] is not None and infraction[62] is not None and infraction[61] >= -360.000000 and infraction[61] <= 720.000000 and infraction[62] >= -360.000000 and infraction[62] <= 720.000000:
  lons.append(infraction[61])
  lats.append(infraction[62])
  labels.append(label.strip().decode('utf-8'))

  if infraction[61] is not None and infraction[62] is not None:
   lat_0 = infraction[62]-0.01
   lat_1 = infraction[62]+0.01
   lon_0 = infraction[61]-0.01
   lon_1 = infraction[61]+0.01
   
#   map = Basemap(projection='merc',llcrnrlat=lat_0,urcrnrlat=lat_1,llcrnrlon=lon_0,urcrnrlon=lon_1,resolution='f')
#   
#   parks_shp = os.environ['HOME']+'/_database/scripts_python/infraction/shp/Aires_Protegees_update20170609_PN_May_EPSG4326'
#   amps_shp = os.environ['HOME']+'/_database/scripts_python/infraction/shp/AiresProtegeesAquatiques_20170601_Final_EPSG4326_2D'
#   
#   fig = plt.figure()
#   ax = fig.add_subplot(111)
#   map.drawcoastlines(linewidth=0.5)
#   map.drawcountries(linewidth=0.5)
#   map.fillcontinents(color='coral',lake_color='aqua')
#   map.drawmapboundary(fill_color='aqua')
#   map.drawmeridians(np.linspace(-40,18,59),labels=[False,False,False,True],linewidth=0.5)
#   map.drawparallels(np.linspace(-20,20,41) ,labels=[True,False,False,False],linewidth=0.5)
#   map.readshapefile(parks_shp,'parks',color='red', antialiased=1)
#   map.readshapefile(amps_shp,'amps',color='red', antialiased=1)
#   
#   patches   = []
#   for info, shape in zip(map.parks_info, map.parks):
#    patches.append( Polygon(np.array(shape), True) )
#   ax.add_collection(PatchCollection(patches, facecolor= 'red', edgecolor='k', linewidths=0.8, zorder=2))
#   
#   patches   = []
#   for info, shape in zip(map.amps_info, map.amps):
#    patches.append( Polygon(np.array(shape), True) )
#   ax.add_collection(PatchCollection(patches, facecolor= 'red', edgecolor='k', linewidths=0.8, zorder=2))
#   
#   x,y = map(lons,lats)
#   
#   plt.scatter(x,y,s=5,facecolor='b',zorder=99)
#   for i in range(len(x)):
#    plt.text(x[i],y[i],labels[i],zorder=100,size=6)
#   
#   plt.savefig(os.environ['HOME']+'/_database/scripts_python/infraction/map_infractions_latest.png',dpi=300,bbox_inches='tight')
#   #print       './map_infractions_latest.png'
#   plt.close()
   
   #pdf.ln(5)
   #pdf.set_font('Raleway-Bold', '', 18)
   #pdf.cell(100, 10, 'Carte des points GPS',border=0,ln=1,align='l')
   #pdf.image(os.environ['HOME']+'/_database/scripts_python/infraction/map_infractions_latest.png',100,10,50)
  

''' Generate Global Map '''

for z in [0,1,2,3,99]:

 lat_0,lat_1,lon_0,lon_1,label_z = zones(z)

 lats_m = []
 lons_m = []
 labels_m = []
 
 if len(lats) > 0 and len(lons) > 0:
 
 # lat_0 = np.min(lats)-0.1
 # lat_1 = np.max(lats)+0.1
 # lon_0 = np.min(lons)-0.1
 # lon_1 = np.max(lons)+0.1
 
 # lat_0 = -5 
 # lat_1 = 2
 # lon_0 = 5
 # lon_1 = 12
 
  for i in range(len(lats)):
   if lats[i] > lat_0 and lats[i] < lat_1 and lons[i] > lon_0 and lons[i] < lon_1:
    lats_m.append(lats[i])
    lons_m.append(lons[i])
    labels_m.append(labels[i])
 
  
  map = Basemap(projection='merc',llcrnrlat=lat_0,urcrnrlat=lat_1,llcrnrlon=lon_0,urcrnrlon=lon_1,resolution='f')
 # map = Basemap(projection='mill',llcrnrlat=-5,urcrnrlat=2,llcrnrlon=5,urcrnrlon=12,resolution='h')
  
  parks_shp = os.environ['HOME']+'/_database/scripts_python/infraction/shp/Aires_Protegees_update20170609_PN_May_EPSG4326'
  amps_shp = os.environ['HOME']+'/_database/scripts_python/infraction/shp/AiresProtegeesAquatiques_20170601_Final_EPSG4326_2D'
  eez_shp = os.environ['HOME']+'/_database/scripts_python/infraction/shp/eez_lr_GB' 
 
  fig = plt.figure()
  ax = fig.add_subplot(111)
  map.drawcoastlines(linewidth=0.5)
  map.drawcountries(linewidth=0.5)
  map.fillcontinents(color='coral',lake_color='aqua')
  map.drawmapboundary(fill_color='aqua')
  map.drawmeridians(np.linspace(-40,18,59),labels=[False,False,False,True],linewidth=0.5)
  map.drawparallels(np.linspace(-20,20,41) ,labels=[True,False,False,False],linewidth=0.5)
  map.readshapefile(parks_shp,'parks',color='red', antialiased=1)
  map.readshapefile(amps_shp,'amps',color='red', antialiased=1)
  map.readshapefile(eez_shp,'eez',color='silver', antialiased=1)
 
  patches   = []
  for info, shape in zip(map.parks_info, map.parks):
   patches.append( Polygon(np.array(shape), True) )
  ax.add_collection(PatchCollection(patches, facecolor= 'None', edgecolor='k', linewidths=0.8, zorder=2))
  
  patches   = []
  for info, shape in zip(map.amps_info, map.amps):
   patches.append( Polygon(np.array(shape), True) )
  ax.add_collection(PatchCollection(patches, facecolor= 'None', edgecolor='k', linewidths=0.8, zorder=2))
  
  x,y = map(lons_m,lats_m)
  
  plt.scatter(x,y,s=5,facecolor='b',zorder=99)
  for i in range(len(x)):
   plt.text(x[i],y[i],labels_m[i],zorder=100,size=6)
  
  #plt.legend(loc=1, bbox_to_anchor=(-0.15,1),fontsize=8)
  
  plt.savefig(os.environ['HOME']+'/_database/scripts_python/infraction/map_infractions_latest_'+label_z+'.png',dpi=300,bbox_inches='tight')
  #print       './map_infractions_latest.png'
  plt.close()
 
 #pdf.ln(5)
 #pdf.set_font('Raleway-Bold', '', 18)
 #pdf.cell(100, 10, 'Carte des points GPS',border=0,ln=1,align='l')
 #pdf.image(os.environ['HOME']+'/_database/scripts_python/infraction/map_infractions_latest.png')
 
print      os.environ['HOME']+'/_database/scripts_python/infraction/GB_report_infractions.pdf'
pdf.output(os.environ['HOME']+'/_database/scripts_python/infraction/GB_report_infractions.pdf', 'F')
