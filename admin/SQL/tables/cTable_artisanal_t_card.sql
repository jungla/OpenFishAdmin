DROP TABLE IF EXISTS artisanal.t_card;

CREATE TABLE artisanal.t_card (
   id integer,
   card varchar(100),
   active boolean,
   PRIMARY KEY(id)
);

--INSERT INTO artisanal.t_card(id, card) VALUES ('0', 'Carte de S&eacute;jour');
--INSERT INTO artisanal.t_card(id, card) VALUES ('1', 'Carte Nationale d&#39;Identit&eacute;');
--INSERT INTO artisanal.t_card(id, card) VALUES ('2', 'Passeport');
--INSERT INTO artisanal.t_card(id, card) VALUES ('3', 'Carte Professionnelle');
--INSERT INTO artisanal.t_card(id, card) VALUES ('4', 'Carte Militaire');
--INSERT INTO artisanal.t_card(id, card) VALUES ('5', 'Permis de Conduire');
--INSERT INTO artisanal.t_card(id, card) VALUES ('6', 'R&eacute;c&eacute;piss&eacute;');
--INSERT INTO artisanal.t_card(id, card) VALUES ('99', 'None');
