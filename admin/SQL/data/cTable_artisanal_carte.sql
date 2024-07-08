DROP TABLE IF EXISTS artisanal.carte;
CREATE TABLE artisanal.carte(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   carte serial,
   id_fisherman uuid,
   date_v date, 
   id_license uuid,	
   active boolean DEFAULT FALSE,
   paid boolean DEFAULT FALSE,
   PRIMARY KEY(id)
);

--Code2	licence_date	licence_res	Pech_date_saisie	Lic_val
