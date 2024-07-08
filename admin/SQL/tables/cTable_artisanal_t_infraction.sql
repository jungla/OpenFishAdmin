DROP TABLE IF EXISTS artisanal.t_infraction;
CREATE TABLE artisanal.t_infraction (
   id integer,
   infraction varchar(200),
   active boolean,
   PRIMARY KEY(id)
);

