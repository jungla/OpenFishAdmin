--CREATE SCHEMA users 
DROP TABLE users.projects
CREATE TABLE users.projects (
   	id integer,
	project varchar(100),
   	PRIMARY KEY(id)
);

TRUNCATE TABLE users.projects

INSERT INTO users.projects(id, project) VALUES ('1', 'industrial fishery observers');
INSERT INTO users.projects(id, project) VALUES ('2', 'artisanal fishery observers');
INSERT INTO users.projects(id, project) VALUES ('3', 'CSP');
INSERT INTO users.projects(id, project) VALUES ('4', 'DGPA licenses');
INSERT INTO users.projects(id, project) VALUES ('5', 'DGPA fishing records');
