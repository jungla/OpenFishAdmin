--CREATE SCHEMA artisanal;
--DROP TABLE artisanal.flotille;
DROP TABLE IF EXISTS artisanal.t_gear;
CREATE TABLE artisanal.t_gear (
   id integer,
   gear varchar(100),
   PRIMARY KEY(id)
);
