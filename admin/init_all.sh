# CREATE STRUCTURE, THIS ERASE ALL PREVIOUS DATA #

sql_tables_files='./SQL/tables/*sql';

for file in $sql_tables_files;
 do
 echo $file
 sudo psql -U postgres -d geospatialdb -a -f $file
 done


sql_data_files='./SQL/data/*sql';

for file in $sql_data_files;
 do
 echo $file
 sudo psql -U postgres -d geospatialdb -a -f $file
 done



# POPULATE DATA

# TABLES 

tables="artisanal_T_STRATA artisanal_T_STATUS artisanal_T_NATIONALITY artisanal_T_GEAR artisanal_T_LICENSE artisanal_T_ZONE artisanal_T_COOP artisanal_T_CARD artisanal_T_IMMATRICULATION  users_T_ROLE users_T_PROJECT artisanal_T_PIROGUE artisanal_T_INFRACTION artisanal_T_ORG"

path="./CSV/tables/"

for table in $tables;
 do
 echo $table
 python load_table_structure.py $table $path
 done

# DATA

path="./CSV/data/"

files="users_USERS users_PROJECT fishery_SPECIES ARTISANAL_T_SITE ARTISANAL_T_SITE_OBB"
#files="users_USERS"

for file in $files;
do
python load_data_$file.py $path $file
done


