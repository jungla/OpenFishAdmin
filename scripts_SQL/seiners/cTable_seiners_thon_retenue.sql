DROP TABLE IF EXISTS seiners.thon_retenue;
CREATE TABLE seiners.thon_retenue(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   maree varchar(100),
   t_zee integer,
   n_calee integer,
   t_type integer,
   id_route uuid,
   n_route integer,
   l_route integer,
   h_d time,
   h_c time,
   h_f time,
   vitesse real,
   direction integer,
   d_max real,
   sonar boolean,
   raison varchar(100),
   id_species uuid,
   t_categorie integer,
   poids real,
   cuve varchar(100),
   remarque text,
   PRIMARY KEY(id)
);

CREATE INDEX trgm_idx_thon_retenue_maree ON seiners.thon_retenue USING gist (maree gist_trgm_ops);
