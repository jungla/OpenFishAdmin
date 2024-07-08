--CREATE SCHEMA artisanal;
--DROP TABLE artisanal.flotille;
DROP TABLE IF EXISTS artisanal.t_pirogue;
CREATE TABLE artisanal.t_pirogue (
   id integer,
   pirogue varchar(100),
   active boolean,
   PRIMARY KEY(id)
);

