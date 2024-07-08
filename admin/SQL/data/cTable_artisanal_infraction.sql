DROP TABLE IF EXISTS artisanal.infraction;
CREATE TABLE artisanal.infraction(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),

   id_pv varchar, 
   date_i date,

   t_org integer,
   name_org varchar(200),

   --id_license uuid,
   id_pirogue uuid,
   pir_name varchar(100),
   immatriculation varchar(100),

   id_owner uuid,
   owner_first varchar(100),
   owner_last varchar(100),
   owner_idcard varchar(100),
   owner_t_card integer,
   owner_ycard date,
   owner_t_nationality integer,
   owner_telephone varchar(100),

   id_fisherman_1 uuid,
   fish_first_1 varchar(100),
   fish_last_1 varchar(100),
   fish_idcard_1 varchar(100),
   fish_t_card_1 integer,
   fish_ycard_1 date,
   fish_t_nationality_1 integer, 
   fish_telephone_1 varchar(100),
   id_fisherman_2 uuid,
   fish_first_2 varchar(100),
   fish_last_2 varchar(100),
   fish_idcard_2 varchar(100),
   fish_t_card_2 integer,
   fish_ycard_2 date,
   fish_t_nationality_2 integer, 
   fish_telephone_2 varchar(100),
   id_fisherman_3 uuid,
   fish_first_3 varchar(100),
   fish_last_3 varchar(100),
   fish_idcard_3 varchar(100),
   fish_t_card_3 integer,
   fish_ycard_3 date,
   fish_t_nationality_3 integer, 
   fish_telephone_3 varchar(100),
   id_fisherman_4 uuid,
   fish_first_4 varchar(100),
   fish_last_4 varchar(100),
   fish_idcard_4 varchar(100),
   fish_t_card_4 integer,
   fish_ycard_4 date,
   fish_t_nationality_4 integer, 
   fish_telephone_4 varchar(100),

   pir_conf varchar(200),
   eng_conf varchar(200),
   net_conf varchar(200),
   doc_conf varchar(200),
   other_conf varchar(200),

   amount varchar,									
   payment varchar,

   n_dep varchar,
   n_cdc varchar,
   n_lib varchar,

   comments text,
   settled boolean,
   PRIMARY KEY(id)
);

SELECT AddGeometryColumn ('infraction','infraction','location',4326,'POINT',2);

CREATE INDEX trgm_idx_owner_first_fraction ON infraction.infraction USING gist (owner_first gist_trgm_ops);
CREATE INDEX trgm_idx_owner_last_infraction ON infraction.infraction USING gist (owner_last gist_trgm_ops);
CREATE INDEX trgm_idx_owner_idcard_infraction ON infraction.infraction USING gist (owner_idcard gist_trgm_ops);

CREATE INDEX trgm_idx_fish_first_1_infraction ON infraction.infraction USING gist (fish_first_1 gist_trgm_ops);
CREATE INDEX trgm_idx_fish_last_1_infraction ON infraction.infraction USING gist (fish_last_1 gist_trgm_ops);
CREATE INDEX trgm_idx_fish_idcard_1_infraction ON infraction.infraction USING gist (fish_idcard_1 gist_trgm_ops);
CREATE INDEX trgm_idx_fish_first_2_infraction ON infraction.infraction USING gist (fish_first_2 gist_trgm_ops);
CREATE INDEX trgm_idx_fish_last_2_infraction ON infraction.infraction USING gist (fish_last_2 gist_trgm_ops);
CREATE INDEX trgm_idx_fish_idcard_2_infraction ON infraction.infraction USING gist (fish_idcard_2 gist_trgm_ops);
CREATE INDEX trgm_idx_fish_first_3_infraction ON infraction.infraction USING gist (fish_first_3 gist_trgm_ops);
CREATE INDEX trgm_idx_fish_last_3_infraction ON infraction.infraction USING gist (fish_last_3 gist_trgm_ops);
CREATE INDEX trgm_idx_fish_idcard_3_infraction ON infraction.infraction USING gist (fish_idcard_3 gist_trgm_ops);

CREATE INDEX trgm_idx_pir_name_infraction ON infraction.infraction USING gist (pir_name gist_trgm_ops);
CREATE INDEX trgm_idx_pir_reg_infraction ON infraction.infraction USING gist (immatriculation gist_trgm_ops);
