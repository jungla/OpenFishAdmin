--CREATE SCHEMA artisanal;
DROP TABLE IF EXISTS artisanal.effort;
CREATE TABLE artisanal.effort(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100) ,
   date_e date,
   obs_name varchar(200) ,
   t_site integer ,
   DB1 integer ,
   DH1 integer ,
   DB3 integer ,
   DH3 integer ,
   PS1 integer ,
   PC1 integer ,
   PS3 integer ,
   PC3 integer ,
   PRIMARY KEY(id)
);
