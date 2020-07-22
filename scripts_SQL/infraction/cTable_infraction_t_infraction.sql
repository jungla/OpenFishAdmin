--CREATE SCHEMA infraction;
--DROP TABLE infraction.flotille;
DROP TABLE IF EXISTS infraction.t_infraction;
CREATE TABLE infraction.t_infraction (
   id integer,
   infraction varchar(200),
   PRIMARY KEY(id)
);

