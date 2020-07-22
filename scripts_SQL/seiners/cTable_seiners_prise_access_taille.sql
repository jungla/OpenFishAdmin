DROP TABLE IF EXISTS seiners.prise_access_taille;
CREATE TABLE seiners.prise_access_taille(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   maree varchar(100),
   n_cale integer,
   id_route uuid,
   n_route integer,
   l_route integer,
   id_species uuid,
   t_measure integer,
   taille float,
   poids float,
   t_sexe integer,
   t_capture integer,
   t_relache integer,
   photo varchar(100),
   remarque text,
   PRIMARY KEY(id)
);
