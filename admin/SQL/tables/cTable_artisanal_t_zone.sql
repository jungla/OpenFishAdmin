--CREATE SCHEMA artisanal;
--DROP TABLE artisanal.flotille;
DROP TABLE IF EXISTS artisanal.t_zone;
CREATE TABLE artisanal.t_zone (
   id integer,
   zone varchar(100),
   active boolean,
   PRIMARY KEY(id)
);
