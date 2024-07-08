DROP TABLE IF EXISTS users.t_project;
CREATE TABLE users.t_project (
   id integer,
   project varchar(100),
   active boolean,
   PRIMARY KEY(id)
);
