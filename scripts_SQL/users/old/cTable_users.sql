DROP TABLE IF EXISTS users.accounts;

CREATE TABLE users.accounts (
	id uuid DEFAULT uuid_generate_v4 (),
	username varchar(100),
	email varchar(200),
	--ind_obs boolean DEFAULT FALSE,
	--art_obs boolean DEFAULT FALSE,
	--art_cpt boolean DEFAULT FALSE,
	--CSP boolean DEFAULT FALSE,
	--DGPA_lcn boolean DEFAULT FALSE,
	ind boolean DEFAULT FALSE,
	art_lcn boolean DEFAULT FALSE,
	art_inf boolean DEFAULT FALSE,
	art_pec boolean DEFAULT FALSE,
	CSP boolean DEFAULT FALSE,
	password chkpass,
	PRIMARY KEY(id)
);

INSERT INTO users.accounts(username, email, ind_obs, art_obs, art_cpt, CSP, DGPA_lcn, "password") VALUES ('jean', 'jeanmensa@gmail.com', FALSE, TRUE, TRUE, TRUE, FALSE, 'jean');
INSERT INTO users.accounts(username, email, ind_obs, art_obs, art_cpt, CSP, DGPA_lcn, "password") VALUES ('mike', 'anonymous@gmail.com', FALSE, TRUE, TRUE, FALSE, TRUE, 'mike');
INSERT INTO users.accounts(username, email, ind_obs, art_obs, art_cpt, CSP, DGPA_lcn, "password") VALUES ('michelle', 'anonymous@gmail.com', FALSE, TRUE, TRUE, FALSE, TRUE, 'michelle');
INSERT INTO users.accounts(username, email, ind_obs, art_obs, art_cpt, CSP, DGPA_lcn, "password") VALUES ('manu', 'anonymous@gmail.com', FALSE, FALSE, FALSE, FALSE, FALSE, 'manu');
INSERT INTO users.accounts(username, email, ind_obs, art_obs, art_cpt, CSP, DGPA_lcn, "password") VALUES ('floriane', 'anonymous@gmail.com', FALSE, TRUE, FALSE, FALSE, TRUE, 'floriane');
INSERT INTO users.accounts(username, email, ind_obs, art_obs, art_cpt, CSP, DGPA_lcn, "password") VALUES ('godefroy', 'anonymous@gmail.com', FALSE, TRUE, FALSE, FALSE, TRUE, 'godefry');
