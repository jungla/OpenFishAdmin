DROP TABLE IF EXISTS artisanal.infractions;
CREATE TABLE artisanal.infractions(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   t_infraction integer,
   id_infraction uuid,
   PRIMARY KEY(id)
);

--Code2	licence_date	licence_res	Pech_date_saisie	Lic_val
