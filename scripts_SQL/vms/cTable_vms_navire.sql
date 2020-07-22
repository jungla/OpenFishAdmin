DROP TABLE IF EXISTS vms.navire;
CREATE TABLE vms.navire(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   navire varchar(100),	
   flag varchar(100),
   owners varchar(100),	
   fullname varchar(100),
   radio varchar(100),
   registration varchar(100),	
   registration_ext varchar(100),
   registration_int varchar(100),
   registration_qrt varchar(100),
   mobile varchar(100), 	
   mmsi varchar(100),
   imo varchar(100),	
   port varchar(100),	
   active boolean,	
   beacon varchar(100),
   satellite varchar(100),
   unknown varchar(100),
   t_navire integer,
   PRIMARY KEY(id)
);

CREATE INDEX vms_navire_date_p_idx ON vms.navire (navire);
CREATE INDEX vms_navire_id_idx ON vms.navire (id);

--CREATE INDEX trgm_idx_logbook_navire ON iccat.maree USING gist (navire gist_trgm_ops);
