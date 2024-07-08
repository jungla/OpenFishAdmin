DROP TABLE IF EXISTS users.users;
CREATE TABLE users.users (
	id uuid DEFAULT uuid_generate_v4 (),
	datetime timestamp DEFAULT now(),
	username varchar(100),
	first_name varchar(100),
	last_name varchar(100),
	nickname varchar(100),
        email varchar(200),
        password text,
        PRIMARY KEY(id)
);
