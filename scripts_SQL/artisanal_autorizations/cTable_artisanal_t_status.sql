DROP TABLE IF EXISTS artisanal.t_status;

CREATE TABLE artisanal.t_status (
   id serial,
   status varchar(100),
   PRIMARY KEY(id)
);
