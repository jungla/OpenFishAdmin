##shp2pgsql -d -s 4326 ~/_Sync/GabonBleu_data/gshhg-shp-2.3.7/GSHHS_shp/f/GSHHS_f_L1.shp shapefiles.gshhs | psql -h localhost -d geospatialdb -U postgres

#shp2pgsql -d -s 4326 ~/_Sync/GabonBleu_data/GB_MPAs/Final\ Version\ APA_Shapefile/AiresProtegeesAquatiques_20170601_Final_EPSG4326.shp shapefiles.mpa | psql -h localhost -d geospatialdb -U postgres

#shp2pgsql -d -s 4326 ~/_Sync/GabonBleu_data/GB_MPAs/Final\ Version\ APA_Shapefile/ZoneTamponParcsMarins_20170601_Final_EPSG4326.shp shapefiles.mpa_buffer | psql -h localhost -d geospatialdb -U postgres

shp2pgsql -d -s 4326 /home/jean/_Sync/GabonBleu_data/shapefiles/World_EEZ_v9_20161021_LR/eez_lr_GB.shp shapefiles.eez | psql -h localhost -d geospatialdb -U postgres
