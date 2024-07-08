DROP TABLE IF EXISTS artisanal.t_study;

CREATE TABLE artisanal.t_study (
   id serial,
   study varchar(100),
   active boolean,
   PRIMARY KEY(id)
);
