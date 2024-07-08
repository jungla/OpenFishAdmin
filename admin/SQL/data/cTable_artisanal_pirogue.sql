DROP TABLE IF EXISTS artisanal.pirogue;
CREATE TABLE artisanal.pirogue(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   name varchar(100),
   immatriculation varchar(200),	
   t_pirogue integer,
   length varchar,
   id_owner uuid,
   comments text,
   id_temp integer,
   photo_data_1 bytea,
   photo_data_2 bytea,
   photo_data_3 bytea,
   plate varchar,
   PRIMARY KEY(id)
);

CREATE INDEX trgm_idx_immatriculation ON artisanal.pirogue USING gist (immatriculation gist_trgm_ops);
CREATE INDEX trgm_idx_name ON artisanal.pirogue USING gist (name gist_trgm_ops);
--Code2	licence_date	licence_res	Pech_date_saisie	Lic_val
