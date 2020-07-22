DROP TABLE IF EXISTS artisanal.captures;
CREATE TABLE artisanal.captures(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   id_maree uuid,
   id_species uuid,
   wgt_tot double precision,
   wgt_spc double precision,
   n_ind double precision,
   PRIMARY KEY(id)
);

