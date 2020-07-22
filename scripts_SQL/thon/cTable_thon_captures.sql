DROP TABLE IF EXISTS thon.captures;
CREATE TABLE thon.captures(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   id_lance uuid,
   rejete boolean,
   id_species uuid,
   taille varchar,
   poids float,
   PRIMARY KEY(id)
);

--CREATE INDEX trgm_idx_logbook_date ON thon.logbook USING gist (date_c gist_trgm_ops);
--CREATE INDEX trgm_idx_logbook_navire ON thon.logbook USING gist (navire gist_trgm_ops);
