DROP TABLE IF EXISTS fishery.species;

CREATE TABLE fishery.species (
	id uuid DEFAULT uuid_generate_v4 (),
	francaise varchar(100),
	family varchar(100),	
	genus  varchar(100),
	species varchar(100),
	FAO varchar(100),
	OBS varchar(100),
	category varchar(100),
	IUCN varchar(100),
	PRIMARY KEY(id)
);
