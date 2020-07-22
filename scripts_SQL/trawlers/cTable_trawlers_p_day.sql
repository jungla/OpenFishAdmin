DROP TABLE IF EXISTS trawlers.p_day;
CREATE TABLE trawlers.p_day(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   maree varchar(100),
   id_navire uuid,
   date_d date,
   lance_d integer,
   lance_f integer,
   id_species uuid,
   c0_cre float,
   c1_cre float,
   c2_cre float,
   c3_cre float,
   c4_cre float,
   c5_cre float,
   c6_cre float,
   c7_cre float,
   c8_cre float,
   c9_cre float,
   c0_poi float,
   c1_poi float,
   c2_poi float,
   c3_poi float,
   c4_poi float,
   c5_poi float,
   c6_poi float,
   PRIMARY KEY(id)
);