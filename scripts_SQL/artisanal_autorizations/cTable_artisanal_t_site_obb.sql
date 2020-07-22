DROP TABLE IF EXISTS artisanal.t_site_obb;

CREATE TABLE artisanal.t_site_obb (
   id serial,
   site varchar(100),
   strata varchar(100),
   region varchar(100),
   code varchar(100),
   PRIMARY KEY(id)
);

SELECT AddGeometryColumn ('artisanal','t_site_obb','location',4326,'POINT',2);
