DROP TABLE IF EXISTS trawlers.route;
CREATE TABLE trawlers.route(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   id_navire uuid,
   maree text,
   t_fleet integer,
   date date,
   lance integer,
   h_d time,
   h_f time,
   depth_d real,
   depth_f real,
   speed real,
   reject real,
   sample real,
   comment text,
   PRIMARY KEY(id)
);

CREATE INDEX trgm_idx_route_maree ON trawlers.route USING gist (maree gist_trgm_ops);
-- CREATE INDEX trgm_idx_route_navire ON trawlers.route USING gist (navire gist_trgm_ops);
SELECT AddGeometryColumn ('trawlers','route','location_d',4326,'POINT',2);
SELECT AddGeometryColumn ('trawlers','route','location_f',4326,'POINT',2);
