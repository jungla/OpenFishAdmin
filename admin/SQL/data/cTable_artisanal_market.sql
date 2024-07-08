DROP TABLE IF EXISTS artisanal.market;
CREATE TABLE artisanal.market(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100) ,
   date_m date,
   obs_name varchar(100) ,
   t_site integer,
   id_species uuid,
   p_s integer,
   p_p integer,
   p_c integer,
   p_m integer,
   p_f integer,
   PRIMARY KEY(id)
);
