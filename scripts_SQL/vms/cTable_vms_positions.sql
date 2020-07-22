DROP TABLE IF EXISTS vms.positions;
CREATE TABLE vms.positions(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   id_navire uuid,
   date_p timestamp,
   speed float,
   PRIMARY KEY(id)
);

SELECT AddGeometryColumn ('vms','positions','location',4326,'POINT',2);

CREATE INDEX id_navire_date_p_idx ON vms.positions (id_navire, date_p DESC);
CREATE INDEX id_navire_datetime_idx ON vms.positions (id_navire, datetime DESC);

--CREATE INDEX trgm_idx_logbook_navire ON iccat.maree USING gist (navire gist_trgm_ops);
