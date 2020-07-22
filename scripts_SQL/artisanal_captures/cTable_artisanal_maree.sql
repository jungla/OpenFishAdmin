DROP TABLE IF EXISTS artisanal.maree;
CREATE TABLE artisanal.maree(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   datetime_d timestamp,
   datetime_r timestamp,
   obs_name varchar(200),
   t_site integer,
   t_study integer,
   id_pirogue uuid,
   immatriculation varchar(200),
   t_gear integer,
   mesh_min varchar(9),
   mesh_max varchar(9),
   length varchar(9),
   wgt_tot varchar(9),
   gps_file varchar(200),
   PRIMARY KEY(id)
);

SELECT AddGeometryColumn ('artisanal','maree','gps_track',4326,'LineString',3);
