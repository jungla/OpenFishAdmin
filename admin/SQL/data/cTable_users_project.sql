DROP TABLE IF EXISTS users.project;
CREATE TABLE users.project (
	id uuid DEFAULT uuid_generate_v4 (),
	datetime timestamp DEFAULT now(),
	username varchar(100),
	id_user uuid, 
	t_project integer,
	t_role integer,
	active boolean,
        PRIMARY KEY(id)
);
