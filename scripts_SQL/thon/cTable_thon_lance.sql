DROP TABLE IF EXISTS thon.lance;
CREATE TABLE thon.lance(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   id_navire uuid,
   date_c date,
   heure_c time,
   eez varchar(100),
   success boolean,
   banclibre boolean,
   balise_id varchar(100),
   water_temp float,
   wind_speed float,
   wind_dir float,
   cur_speed float,
   comment text,
   PRIMARY KEY(id)
);

SELECT AddGeometryColumn ('thon','lance','location',4326,'POINT',2);

--CREATE INDEX trgm_idx_logbook_date ON thon.logbook USING gist (date_c gist_trgm_ops);
--CREATE INDEX trgm_idx_logbook_navire ON thon.logbook USING gist (navire gist_trgm_ops);
