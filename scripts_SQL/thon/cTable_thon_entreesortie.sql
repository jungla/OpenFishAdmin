DROP TABLE IF EXISTS thon.entreesortie;
CREATE TABLE thon.entreesortie(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   id_navire uuid,
   eez varchar(100),
   date_e date,
   heure_e time,
   entree boolean,
   YFT varchar(100),
   BET varchar(100),
   SKJ varchar(100),
   FRI varchar(100),
   remarques text,
   PRIMARY KEY(id)
);

SELECT AddGeometryColumn ('thon','entreesortie','location',4326,'POINT',2);
