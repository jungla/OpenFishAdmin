DROP TABLE IF EXISTS trawlers.route_accidentelle;
CREATE TABLE trawlers.route_accidentelle(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   t_fleet integer,
   id_navire uuid,
   maree varchar(100),
   date date,
   time time,
   t_co integer,
   lance integer,
   PRIMARY KEY(id)
);

CREATE INDEX trgm_idx_route_acc_maree ON trawlers.route_accidentelle USING gist (maree gist_trgm_ops);
SELECT AddGeometryColumn ('trawlers','route_accidentelle','location',4326,'POINT',2);
