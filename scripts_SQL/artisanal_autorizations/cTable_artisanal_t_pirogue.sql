--CREATE SCHEMA artisanal;
--DROP TABLE artisanal.flotille;
DROP TABLE IF EXISTS artisanal.t_pirogue;
CREATE TABLE artisanal.t_pirogue (
   id integer,
   pirogue varchar(100),
   PRIMARY KEY(id)
);

INSERT INTO artisanal.t_pirogue(id, pirogue) VALUES ('0', 'Bois');
INSERT INTO artisanal.t_pirogue(id, pirogue) VALUES ('1', 'Plastique');
