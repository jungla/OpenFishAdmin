--CREATE SCHEMA artisanal;
--DROP TABLE artisanal.flotille;
DROP TABLE IF EXISTS artisanal.t_license;
CREATE TABLE artisanal.t_license (
   id serial,
   license varchar(100),
   PRIMARY KEY(id)
);
