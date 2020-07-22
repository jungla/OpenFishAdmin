<?php
require('../connect.inc.php');
require('../functions.inc.php');

session_start();

dbconnect();

    $q_id = "SELECT license.license, extract(year from date_v), "
            . "l1.license, l2.license, g1.gear, g2.gear, s1.site, s2.site, engine_brand, "
            . "engine_cv, receipt, CASE WHEN owner.t_nationality=7 THEN '100000' WHEN license.t_gear=4 THEN '200000' ELSE '150000' END, t_coop.coop, license.active, pirogue.name, pirogue.immatriculation, t_pirogue.pirogue, "
            . "first_name, last_name, bday, t_card.card, idcard , date_v, address, t_nationality.nationality, telephone, plate, s2_2.site "
            . " FROM artisanal.license "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.license.id_pirogue "
            . "LEFT JOIN artisanal.owner ON artisanal.owner.id = artisanal.pirogue.id_owner "
            . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue "
            . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
            . "LEFT JOIN artisanal.t_license l1 ON l1.id = artisanal.license.t_license "
            . "LEFT JOIN artisanal.t_license l2 ON l2.id = artisanal.license.t_license_2 "
            . "LEFT JOIN artisanal.t_gear g1 ON g1.id = artisanal.license.t_gear "
            . "LEFT JOIN artisanal.t_gear g2 ON g2.id = artisanal.license.t_gear_2 "
            . "LEFT JOIN artisanal.t_site s1 ON s1.id = artisanal.license.t_site "
            . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = artisanal.license.t_site_obb "
            . "LEFT JOIN artisanal.t_site_obb s2_2 ON s2_2.id = artisanal.license.t_site_obb_2 "
            . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.owner.t_card "
            . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.owner.t_nationality "
            . "WHERE active = TRUE";

    $csv = "license, date_activation, type_license, type_license_2, 'engin_peche, engin_peche_2, site_attach, debarcadere,marque_moteur,\\
        moteur_CV,quittance_payment,montant,cooperative,license_active,nom_pirogue,immatriculation,\\
        type_pirogue,prenom_proprietaire,nom_proprietaire,date_naissance,type_carte_d\'identite,nombre,expiration,\\
        addresse,nationalite,telephone,plate,debarcadere_2\n";

    //print $q_id;

    $r_id = pg_query($q_id);

    $filename = '/var/www/html/artisanal/files/csv/peche_artisanale_licenses.csv';

    while($rec = pg_fetch_row($r_id)) {
      foreach($rec as $line) {
        $csv.= $line.','; //Append data to csv
        }
        print $csv[-1];
        $csv[-1] = "\n";
      }

    $csv_handler = fopen($filename,'w');
    fwrite($csv_handler, $csv);
    fclose ($csv_handler);
