DROP TABLE IF EXISTS crevette.lance;
CREATE TABLE crevette.lance(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   id_navire uuid,
   date_l date,
   t_zone integer,
   lance integer,
   h_d time,
   h_f time,
   D_d float,
   D_f float,
   T_d float,
   rejets float,
   c0_cre float,
   c1_cre float,
   c2_cre float,
   c3_cre float,
   c4_cre float,
   c5_cre float,
   c6_cre float,
   c7_cre float,
   c8_cre float,
   c_cre float,
   cc_cre float,
   o_cre float,
   v6_cre float,
   PRIMARY KEY(id)
);

SELECT AddGeometryColumn ('crevette','lance','location_d',4326,'POINT',2);
SELECT AddGeometryColumn ('crevette','lance','location_f',4326,'POINT',2);
