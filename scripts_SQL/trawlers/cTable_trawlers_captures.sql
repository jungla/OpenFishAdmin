DROP TABLE IF EXISTS trawlers.captures;
CREATE TABLE trawlers.captures(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   id_route uuid,
   maree varchar(100),
   lance integer,
   id_species uuid,
   poids float,
   comment text,
   nind float,
   PRIMARY KEY(id)
);
