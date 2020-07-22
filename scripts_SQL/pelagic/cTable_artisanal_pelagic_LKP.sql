DROP TABLE IF EXISTS artisanal.pelagic_lkp;
CREATE TABLE artisanal.pelagic_lkp(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   date_t timestamp,
   name varchar(100),
   PRIMARY KEY(id)
);

SELECT AddGeometryColumn ('artisanal','pelagic_lkp','location',4326,'POINT',2);


--Code2	licence_date	licence_res	Pech_date_saisie	Lic_val
