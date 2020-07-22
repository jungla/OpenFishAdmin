DROP TABLE IF EXISTS trawlers.captures_requin;
CREATE TABLE trawlers.captures_requin(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   id_route uuid,
   maree varchar(100),
   date date,
   time time,
   lance integer,
   id_species uuid,
   n_ind float,
   t_sex integer,
   taille varchar(100),
   poids float,
   t_capture integer,
   t_relache integer,
   preleve varchar(100),
   camera text,
   photo text,
   remarque text,
   PRIMARY KEY(id)
);
