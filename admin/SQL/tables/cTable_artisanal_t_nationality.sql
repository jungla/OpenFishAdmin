DROP TABLE IF EXISTS artisanal.t_nationality;

CREATE TABLE artisanal.t_nationality (
   id integer,
   nationality varchar(100),
   active boolean,
   PRIMARY KEY(id)
);
