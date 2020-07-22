DROP TABLE IF EXISTS crevette.capture;
CREATE TABLE crevette.capture(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   id_lance uuid,
   id_species uuid,
   t_taille integer,
   poids float,
   PRIMARY KEY(id)
);
