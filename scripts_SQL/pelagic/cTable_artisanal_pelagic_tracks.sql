DROP TABLE IF EXISTS artisanal.pelagic_tracks;
CREATE TABLE artisanal.pelagic_tracks(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   date_t timestamp,
   name varchar(100),
   speed float,
   range float,
   heading float,
   PRIMARY KEY(id)
);

SELECT AddGeometryColumn ('artisanal','pelagic_tracks','location',4326,'POINT',2);


--Code2	licence_date	licence_res	Pech_date_saisie	Lic_val
