DROP TABLE IF EXISTS artisanal.fisherman;
CREATE TABLE artisanal.fisherman(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   first_name varchar(100),
   last_name varchar(100),
   bday date,
   wives integer,
   children integer,
   t_card integer,	
   idcard varchar(200),	
   ycard date,	
   address text, 
   t_nationality integer,
   telephone varchar(100),
   photo_data bytea,
   comments text,
   id_temp integer,
   PRIMARY KEY(id)
);

CREATE INDEX trgm_idx_first_name_fisherman ON artisanal.fisherman USING gist (first_name gist_trgm_ops);
CREATE INDEX trgm_idx_last_name_fisherman ON artisanal.fisherman USING gist (last_name gist_trgm_ops);
CREATE INDEX trgm_idx_idcard_fisherman ON artisanal.fisherman USING gist (idcard gist_trgm_ops);

--Code2	licence_date	licence_res	Pech_date_saisie	Lic_val
