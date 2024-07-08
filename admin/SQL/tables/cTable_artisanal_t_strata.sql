DROP TABLE IF EXISTS artisanal.t_strata;

CREATE TABLE artisanal.t_strata (
   id serial,
   strata varchar(100),
   active boolean,
   PRIMARY KEY(id)
);
