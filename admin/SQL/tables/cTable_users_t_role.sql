DROP TABLE IF EXISTS users.t_role;
CREATE TABLE users.t_role (
   id integer,
   role varchar(100),
   active boolean,
   PRIMARY KEY(id)
);
