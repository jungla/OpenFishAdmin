DROP TABLE IF EXISTS artisanal.fleet;
CREATE TABLE artisanal.fleet(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100) ,
   date_f date,
   obs_name varchar(200) ,
   t_site integer,
   source text,
   PPB integer ,
   GPF integer ,
   PPF integer ,
   TOT integer ,
   PRIMARY KEY(id)
);
