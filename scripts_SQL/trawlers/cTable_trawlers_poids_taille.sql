DROP TABLE IF EXISTS trawlers.poids_taille;
CREATE TABLE trawlers.poids_taille(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   maree varchar(100),
   id_species uuid,
   t_measure integer,
   taille float, 
   p1 float, 
   p2 float, 
   p3 float, 
   p4 float, 
   p5 float, 
   PRIMARY KEY(id)
);
