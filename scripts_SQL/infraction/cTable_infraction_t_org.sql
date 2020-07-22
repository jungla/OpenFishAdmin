--CREATE SCHEMA infraction;
--DROP TABLE infraction.flotille;
DROP TABLE IF EXISTS infraction.t_org;
CREATE TABLE infraction.t_org (
   id integer,
   org varchar(100),
   PRIMARY KEY(id)
);

