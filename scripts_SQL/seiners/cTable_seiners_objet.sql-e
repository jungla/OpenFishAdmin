DROP TABLE IF EXISTS seiners.objet;
CREATE TABLE seiners.objet(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   maree varchar(100),
   t_zee integer,
   n_objet integer,
   id_route uuid,
   n_route integer,
   l_route integer,
   t_objet integer,
   type_balise varchar(100),
   code_balise varchar(100),
   t_operation integer,
   t_appartenance integer,
   t_devenir integer,
   remarque text,
   PRIMARY KEY(id)
);

CREATE INDEX trgm_idx_obj_maree ON seiners.objet USING gist (maree gist_trgm_ops);

