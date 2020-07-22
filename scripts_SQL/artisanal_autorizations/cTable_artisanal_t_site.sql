DROP TABLE IF EXISTS artisanal.t_site;

CREATE TABLE artisanal.t_site (
   id serial,
   site varchar(100),
   strata varchar(100),
   region varchar(100),
   code varchar(100),
   PRIMARY KEY(id)
);

SELECT AddGeometryColumn ('artisanal','t_site','location',4326,'POINT',2);
