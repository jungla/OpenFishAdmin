DROP TABLE IF EXISTS artisanal.license;
CREATE TABLE artisanal.license(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   license serial,
   date_v date,
   t_license integer,
   t_license_2 integer,
   t_gear integer,
   t_gear_2 integer,
   t_site integer,
   t_site_obb integer,
   mesh_min float,
   mesh_max float,
   length float,
   mesh_min_2 float,
   mesh_max_2 float,
   length_2 float,
   engine_brand varchar(100),
   engine_cv integer,
   payment integer,
   receipt varchar,
   agasa varchar(100),
   t_coop integer, 
   id_pirogue uuid,
   active boolean DEFAULT FALSE,
   id_temp integer,
   comments text,
   PRIMARY KEY(id)
);

-- CREATE INDEX trgm_idx_license ON artisanal.license USING gist (receipt gist_trgm_ops);
--Code2	licence_date	licence_res	Pech_date_saisie	Lic_val
