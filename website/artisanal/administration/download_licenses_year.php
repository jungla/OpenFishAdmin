<?php
require("../../top_foot.inc.php");

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}

$table = $_POST['table'];
$format = $_POST['format'];
$year = $_POST['year'];

//print $table;
//print $format;
//print $year;

if ($format == 'CSV') {

    top();

    if ($table == 'licenses') {

        $date = date_create();
        date_sub($date, date_interval_create_from_date_string('6 months'));
        $Y1 = date_format($date, 'Y');
        date_sub($date, date_interval_create_from_date_string('12 months'));
        $Y2 = date_format($date, 'Y');
        date_sub($date, date_interval_create_from_date_string('12 months'));
        $Y3 = date_format($date, 'Y');
        date_sub($date, date_interval_create_from_date_string('12 months'));
        $Y4 = date_format($date, 'Y');

        $q_id = "SELECT art1.username, art1.datetime::date, t_strata.strata, art1.license, extract(year from  date_v), "
            . "l1.license, l2.license, g1.gear, g2.gear, s2.site, s1.site, engine_brand, "
            . "engine_cv, receipt, "
            . "CASE WHEN owner.t_nationality=7 THEN '100000' WHEN art1.t_gear=4 THEN '200000' ELSE '150000' END"
            . ", payment_prod, receipt_prod, "
            . "(SELECT SUM(wgt_spc) FROM artisanal.captures "
            . "LEFT JOIN artisanal.maree ON artisanal.maree.id = artisanal.captures.id_maree "
            . "WHERE maree.id_pirogue = art1.id_pirogue AND EXTRACT(year FROM datetime_d) = '$Y1' AND t_study = '2' "
            . "GROUP BY EXTRACT(year FROM datetime_d)), t_coop.coop, art1.active, "
            . "(SELECT COUNT(*) FROM artisanal.license art WHERE extract(year FROM date_v) = $Y1 AND art.id_pirogue=art1.id_pirogue), "
            . "(SELECT COUNT(*) FROM artisanal.license art WHERE extract(year FROM date_v) = $Y2 AND art.id_pirogue=art1.id_pirogue), "
            . "(SELECT COUNT(*) FROM artisanal.license art WHERE extract(year FROM date_v) = $Y3 AND art.id_pirogue=art1.id_pirogue),"
            . "(SELECT COUNT(*) FROM artisanal.license art WHERE extract(year FROM date_v) = $Y4 AND art.id_pirogue=art1.id_pirogue), "
            . "art1.comments, pirogue.name, pirogue.immatriculation, pirogue.length, "
            . "first_name, last_name, bday, t_card.card, idcard, ycard, address, t_nationality.nationality, telephone, owner.comments, "
            . "id_pv, date_i, t_org.org, t_infraction.infraction, "
            . "owner_first, owner_last, owner_telephone, "
            . "fish_first_1, fish_last_1, "
            . "fish_first_2, fish_last_2, "
            . "fish_first_3, fish_last_3, "
            . "fish_first_4, fish_last_4, "
            . "amount, infraction.payment, infraction.comments "
            . "FROM artisanal.license art1 "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = art1.id_pirogue "
            . "LEFT JOIN artisanal.owner ON artisanal.owner.id = artisanal.pirogue.id_owner "
            . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = art1.t_coop "
            . "LEFT JOIN artisanal.t_license l1 ON l1.id = art1.t_license "
            . "LEFT JOIN artisanal.t_license l2 ON l2.id = art1.t_license_2 "
            . "LEFT JOIN artisanal.t_gear g1 ON g1.id = art1.t_gear "
            . "LEFT JOIN artisanal.t_gear g2 ON g2.id = art1.t_gear_2 "
            . "LEFT JOIN artisanal.t_site s1 ON s1.id = art1.t_site "
            . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = art1.t_site_obb "
            //. "LEFT JOIN artisanal.t_site_obb s2_2 ON s2.id = art1.t_site_obb_2 "
            . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.owner.t_card "
            . "LEFT JOIN artisanal.t_strata ON artisanal.t_strata.id = art1.t_strata "
            . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.owner.t_nationality "
            . "LEFT JOIN infraction.infraction ON infraction.infraction.id_pirogue = artisanal.pirogue.id "
            . "LEFT JOIN infraction.infractions ON infraction.infraction.id = infraction.infractions.id_infraction "
            . "LEFT JOIN infraction.t_infraction ON infraction.t_infraction.id = infraction.infractions.t_infraction "
            . "LEFT JOIN infraction.t_org ON infraction.t_org.id = infraction.infraction.t_org "
            . "WHERE (extract(year from date_v) = ".$year.") ORDER BY art1.license";
            //. "AND license.active = TRUE";

            $header = ['nom utilisateur', 'date saisie', 'strata', '# license', 'date activation',
                'type license', 'type license 2', 'engin peche', 'engin peche 2', 'debarcadere', 'site attach', 'marque moteur',
                'moteur CV', 'quittance payment license', 'montant license', 'quittance payment taxe production', 'montant taxe', 'Production annee', 'cooperative', 'license active', $Y1, $Y2, $Y3, $Y4, 'commentaire license','nom pirogue', 'immatriculation',
                'longeur pirogue', 'prenom proprietaire', 'nom proprietaire', 'date naissance', 'type carte d\'identite', 'nombre', 'expiration',
                'addresse', 'nationalite', 'telephone', 'commentaire prop',
                'id pv', 'date infraction', 'organisme', 'type infraction',  'prenom proprietaire', 'nom proprietaire', '# telephone',
                'prenom pecheur 1', 'nom pecheur 1', 'prenom pecheur 2', 'nom pecheur 2', 'prenom pecheur 3', 'nom pecheur 3', 'prenom pecheur 4', 'nom pecheur 4',
                'montant inf', 'montant paye', 'commentaires infraction'];

// without infractions
            $q_id = "SELECT art1.username, art1.datetime::date, t_strata.strata, art1.license, extract(year from  date_v), "
                . "l1.license, l2.license, g1.gear, g2.gear, s2.site, s1.site, engine_brand, "
                . "engine_cv, receipt, "
                . "CASE WHEN owner.t_nationality=7 THEN '100000' WHEN art1.t_gear=4 THEN '200000' ELSE '150000' END"
                . ", payment_prod, receipt_prod, "
                . "(SELECT SUM(wgt_spc) FROM artisanal.captures "
                . "LEFT JOIN artisanal.maree ON artisanal.maree.id = artisanal.captures.id_maree "
                . "WHERE maree.id_pirogue = art1.id_pirogue AND EXTRACT(year FROM datetime_d) = '$Y1' AND t_study = '2' "
                . "GROUP BY EXTRACT(year FROM datetime_d)), t_coop.coop, art1.active, "
                . "(SELECT COUNT(*) FROM artisanal.license art WHERE extract(year FROM date_v) = $Y1 AND art.id_pirogue=art1.id_pirogue), "
                . "(SELECT COUNT(*) FROM artisanal.license art WHERE extract(year FROM date_v) = $Y2 AND art.id_pirogue=art1.id_pirogue), "
                . "(SELECT COUNT(*) FROM artisanal.license art WHERE extract(year FROM date_v) = $Y3 AND art.id_pirogue=art1.id_pirogue),"
                . "(SELECT COUNT(*) FROM artisanal.license art WHERE extract(year FROM date_v) = $Y4 AND art.id_pirogue=art1.id_pirogue), "
                . "art1.comments, pirogue.name, pirogue.immatriculation, pirogue.length, pirogue.comments, "
                . "first_name, last_name, bday, t_card.card, idcard, ycard, address, t_nationality.nationality, telephone, owner.comments "
                . "FROM artisanal.license art1 "
                . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = art1.id_pirogue "
                . "LEFT JOIN artisanal.owner ON artisanal.owner.id = artisanal.pirogue.id_owner "
                . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = art1.t_coop "
                . "LEFT JOIN artisanal.t_license l1 ON l1.id = art1.t_license "
                . "LEFT JOIN artisanal.t_license l2 ON l2.id = art1.t_license_2 "
                . "LEFT JOIN artisanal.t_gear g1 ON g1.id = art1.t_gear "
                . "LEFT JOIN artisanal.t_gear g2 ON g2.id = art1.t_gear_2 "
                . "LEFT JOIN artisanal.t_site s1 ON s1.id = art1.t_site "
                . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = art1.t_site_obb "
                //. "LEFT JOIN artisanal.t_site_obb s2_2 ON s2.id = art1.t_site_obb_2 "
                . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.owner.t_card "
                . "LEFT JOIN artisanal.t_strata ON artisanal.t_strata.id = art1.t_strata "
                . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.owner.t_nationality "
                . "WHERE (extract(year from date_v) = ".$year.") ORDER BY art1.license";
                //. "AND license.active = TRUE";

        //print $q_id;

        # previous years
        $query = "SELECT * FROM artisanal.license WHERE extract(year FROM date_v) = $Y1 AND id_pirogue='".$results[22]."'";
        if(pg_num_rows(pg_query($query)) > 0) {
            $l_Y1 = 'Oui';
        } else {
            $l_Y1 = 'Non';
        }

        $query = "SELECT * FROM artisanal.license WHERE extract(year FROM date_v) = $Y2 AND id_pirogue='".$results[22]."'";
        if(pg_num_rows(pg_query($query)) > 0) {
            $l_Y2 = 'Oui';
        } else {
            $l_Y2 = 'Non';
        }

        $query = "SELECT * FROM artisanal.license WHERE extract(year FROM date_v) = $Y3 AND id_pirogue='".$results[22]."'";
        if(pg_num_rows(pg_query($query)) > 0) {
            $l_Y3 = 'Oui';
        } else {
            $l_Y3 = 'Non';
        }


        $header = ['nom utilisateur', 'date saisie', 'strata', '# license', 'date activation',
            'type license', 'type license 2', 'engin peche', 'engin peche 2', 'debarcadere', 'site attach', 'marque moteur',
            'moteur CV', 'quittance payment license', 'montant license', 'quittance payment taxe production', 'montant taxe', 'Production annee', 'cooperative', 'license active', $Y1, $Y2, $Y3, $Y4, 'commentaire license','nom pirogue', 'immatriculation',
            'longeur pirogue', 'commentaire pirogue', 'prenom proprietaire', 'nom proprietaire', 'date naissance', 'type carte d\'identite', 'nombre', 'expiration',
            'addresse', 'nationalite', 'telephone', 'commentaire prop'];

        //print $q_id;

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanale_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'infractions') {

        $q_id = "SELECT infraction.id, infraction.username, infraction.datetime::date, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, immatriculation, "
          . "id_owner, owner_first, owner_last, owner_idcard, "
          . "c1.card, n1.nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, c2.card, n2.nationality, "
          . "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, c3.card, n3.nationality, fish_telephone_2, id_fisherman_3, "
          . "fish_first_3, fish_last_3, fish_idcard_3, c4.card, n4.nationality, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
          . "fish_idcard_4, c5.card, n5.nationality, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
          . "amount, payment, n_dep, n_cdc, n_lib, comments, st_x(location), st_y(location) FROM infraction.infraction "
          . "LEFT JOIN infraction.t_org ON infraction.infraction.t_org = infraction.t_org.id "
          . "LEFT JOIN artisanal.t_card c1 ON c1.id = infraction.infraction.owner_t_card "
          . "LEFT JOIN artisanal.t_nationality n1 ON n1.id = infraction.infraction.owner_t_nationality "
          . "LEFT JOIN artisanal.t_card c2 ON c2.id = infraction.infraction.fish_t_card_1 "
          . "LEFT JOIN artisanal.t_nationality n2 ON n2.id = infraction.infraction.fish_t_nationality_1 "
          . "LEFT JOIN artisanal.t_card c3 ON c3.id = infraction.infraction.fish_t_card_2 "
          . "LEFT JOIN artisanal.t_nationality n3 ON n3.id = infraction.infraction.fish_t_nationality_2 "
          . "LEFT JOIN artisanal.t_card c4 ON c4.id = infraction.infraction.fish_t_card_3 "
          . "LEFT JOIN artisanal.t_nationality n4 ON n4.id = infraction.infraction.fish_t_nationality_3 "
          . "LEFT JOIN artisanal.t_card c5 ON c5.id = infraction.infraction.fish_t_card_4 "
          . "LEFT JOIN artisanal.t_nationality n5 ON n5.id = infraction.infraction.fish_t_nationality_4 "
          . "WHERE (EXTRACT(year FROM date_i) = ".$year.") ORDER BY date_i";
        //. "AND license.active = TRUE";

        $header = ['Utilizateur', 'Date saisie', 'ID PV', 'Date infraction', 'Organisme', 'Nom agent', 'Nom pirogue', 'Immatriculation pirogue', 'Proprietaire prenom', 'Proprietaire nom', 'Proprietaire ID',
        'type ID', 'Nationalite', 'Num telephone', 'Pecheur 1 prenome', 'Pecheur 1 nom', 'Pecheur 1 ID', 'Pecheur 1 ID type', 'Pecheur 1 nationalite',
        'Pecheur 1 telephone', 'Pecheur 2 prenome', 'Pecheur 2 nom', 'Pecheur 2 ID', 'Pecheur 2 ID type', 'Pecheur 2 nationalite',
        'Pecheur 2 telephone', 'Pecheur 3 prenome', 'Pecheur 3 nom', 'Pecheur 3 ID', 'Pecheur 3 ID type', 'Pecheur 3 nationalite',
        'Pecheur 3 telephone', 'Pecheur 4 prenome', 'Pecheur 4 nom', 'Pecheur 4 ID', 'Pecheur 4 ID type', 'Pecheur 4 nationalite',
        'Pecheur 4 telephone', 'Pirogue confisque', 'Moteur confisque', 'Filet confisque', 'Documents confisque', 'Autre confisque',
        'Totale amend', 'Totale payee', 'Quittance', 'CDC', 'liberatoire', 'commentaires', 'longitude', 'latitude'];

        //print $q_id;

        $r_id = pg_query($q_id);

        $values = [];
        $i = 0;

        while ($results = pg_fetch_row($r_id)) {

          $values[$i][0] = $results[1];
          $values[$i][1] = $results[2];
          $values[$i][2] = $results[3];
          $values[$i][3] = $results[4];
          $values[$i][4] = $results[5];
          $values[$i][5] = $results[6];

          # Pirogue

          if ($results[7] != '' AND $results[10] != '') {
            # separate ID for PIROGUE and OWNER

            # PIROGUE
            $query = "SELECT name, immatriculation FROM artisanal.pirogue LEFT JOIN WHERE id = '$results[7]'";
            $pirogue = pg_fetch_row(pg_query($query));
            $values[$i][6] = $pirogue[0];
            $values[$i][7] = $pirogue[1];

            # OWNER
            $query = "SELECT first_name, last_name, idcard, t_card.card, t_nationality.nationality, telephone FROM artisanal.owner "
            . "LEFT JOIN artisanal.t_card ON owner.t_card = t_card.id "
            . "LEFT JOIN artisanal.t_nationality ON owner.t_nationality = t_nationality.id "
            . "WHERE owner.id ='$results[10]'";

            $owner = pg_fetch_row(pg_query($query));
            $values[$i][8] = $owner[0];
            $values[$i][9] = $owner[1];
            $values[$i][10] = $owner[2];
            $values[$i][11] = $owner[3];
            $values[$i][12] = $owner[4];
            $values[$i][13] = $owner[5];

          } elseif ($results[7] != '' AND $results[10] == '') {
            # ID only for PIROGUE. OWNER taken from PIROGUE. Overridden in case an owner name has been added by user.

            if ($results[11] == '' AND $results[12] == '') {
              # IF OWNER name is NOT given by user
              $query = "SELECT pirogue.name, pirogue.immatriculation, owner.first_name, owner.last_name, owner.idcard, t_card.card, t_nationality.nationality, owner.telephone "
              . " FROM artisanal.pirogue "
              . " LEFT JOIN artisanal.owner ON artisanal.owner.id = artisanal.pirogue.id_owner "
              . " LEFT JOIN artisanal.t_card ON owner.t_card = t_card.id "
              . " LEFT JOIN artisanal.t_nationality ON owner.t_nationality = t_nationality.id "
              . " WHERE pirogue.id = '$results[7]'";

              $pirogue_owner = pg_fetch_row(pg_query($query));

              # PIROGUE
              $values[$i][6] = $pirogue_owner[0];
              $values[$i][7] = $pirogue_owner[1];

              # OWNER
              $values[$i][8] = $pirogue_owner[2];
              $values[$i][9] = $pirogue_owner[3];
              $values[$i][10] = $pirogue_owner[4];
              $values[$i][11] = $pirogue_owner[5];
              $values[$i][12] = $pirogue_owner[6];
              $values[$i][13] = $pirogue_owner[7];

            } else {
              $query = "SELECT pirogue.name, pirogue.immatriculation "
              . " FROM artisanal.pirogue "
              . " WHERE pirogue.id = '$results[7]'";

              $pirogue = pg_fetch_row(pg_query($query));

              # PIROGUE
              $values[$i][6] = $pirogue[0];
              $values[$i][7] = $pirogue[1];

              # OWNER
              if ($results[11] != '' OR $results[12] != '') {
              # Owner name
                $values[$i][8] = strtoupper($results[12]);
                $values[$i][9] = ucfirst($results[11]);
                $values[$i][10] = $results[13];
                $values[$i][11] = $results[14];
                $values[$i][12] = $results[15];
                $values[$i][13] = $results[16];
              } else {
                $values[$i][8] = "Aucun details";
                $values[$i][9] = "Aucun details";
                $values[$i][10] = "Aucun details";
                $values[$i][11] = "Aucun details";
                $values[$i][12] = "Aucun details";
                $values[$i][13] = "Aucun details";
              }
            }
          } elseif ($results[7] == '' AND $results[10] != '') {
            # ID owner only
            $query = "SELECT owner.first_name, owner.last_name, owner.idcard, t_card.card, t_nationality.nationality, owner.telephone "
            . " FROM artisanal.owner "
            . " LEFT JOIN artisanal.t_card ON owner.t_card = t_card.id "
            . " LEFT JOIN artisanal.t_nationality ON owner.t_nationality = t_nationality.id "
            . " WHERE owner.id = '$results[10]'";

            $owner = pg_fetch_row(pg_query($query));
            $values[$i][8] = $owner[0];
            $values[$i][9] = $owner[1];
            $values[$i][10] = $owner[2];
            $values[$i][11] = $owner[3];
            $values[$i][12] = $owner[4];
            $values[$i][13] = $owner[5];

          } else {
            # NO ID OWNER NOT PIROGUE

            if ($results[8] != '' AND $results[9] != '') {
            # Pirogue name
              $values[$i][6] = $results[8];
              $values[$i][7] = $results[9];
            } else {
              $values[$i][6] = "Aucun details";
              $values[$i][7] = "Aucun details";
            }

            if ($results[11] != '' OR $results[12] != '') {
            # Owner name
              $values[$i][8] = strtoupper($results[12]);
              $values[$i][9] = ucfirst($results[11]);
              $values[$i][10] = $results[13];
              $values[$i][11] = $results[14];
              $values[$i][12] = $results[15];
              $values[$i][13] = $results[16];
            } else {
              $values[$i][8] = "Aucun details";
              $values[$i][9] = "Aucun details";
              $values[$i][10] = "Aucun details";
              $values[$i][11] = "Aucun details";
              $values[$i][12] = "Aucun details";
              $values[$i][13] = "Aucun details";
            }
        }

  # fisherman 1

          if ($results[17] != '') {
            $query = "SELECT first_name, last_name, idcard, t_card.card, t_nationality.nationality, telephone "
            . "FROM artisanal.fisherman LEFT JOIN artisanal.t_card ON fisherman.t_card = t_card.id "
            . "LEFT JOIN artisanal.t_nationality ON fisherman.t_nationality = t_nationality.id "
            . "WHERE fisherman.id ='$results[17]'";

            $fisherman = pg_fetch_row(pg_query($query));
            $values[$i][14] = $fisherman[0];
            $values[$i][15] = $fisherman[1];
            $values[$i][16] = $fisherman[2];
            $values[$i][17] = $fisherman[3];
            $values[$i][18] = $fisherman[4];
            $values[$i][19] = $fisherman[5];

          } else {
            if ($results[17] == '' AND $results[18] == '') {
              $values[$i][14] = "Aucun details";
              $values[$i][15] = "Aucun details";
              $values[$i][16] = "Aucun details";
              $values[$i][17] = "Aucun details";
              $values[$i][18] = "Aucun details";
              $values[$i][19] = "Aucun details";
            } else {
              $values[$i][14] = strtoupper($results[18]);
              $values[$i][15] = ucfirst($results[19]);
              $values[$i][16] = $results[20];
              $values[$i][17] = $results[21];
              $values[$i][18] = $results[22];
              $values[$i][19] = $results[23];
            }
          }

          # fisherman 2

          if ($results[24] != '') {
            $query = "SELECT first_name, last_name, idcard, t_card.card, t_nationality.nationality, telephone "
            . "FROM artisanal.fisherman LEFT JOIN artisanal.t_card ON fisherman.t_card = t_card.id "
            . "LEFT JOIN artisanal.t_nationality ON fisherman.t_nationality = t_nationality.id "
            . "WHERE fisherman.id ='$results[24]'";

            $fisherman = pg_fetch_row(pg_query($query));
            $values[$i][20] = $fisherman[0];
            $values[$i][21] = $fisherman[1];
            $values[$i][22] = $fisherman[2];
            $values[$i][23] = $fisherman[3];
            $values[$i][24] = $fisherman[4];
            $values[$i][25] = $fisherman[5];

          } else {
            if ($results[25] == '' AND $results[26] == '') {
              $values[$i][20] = "Aucun details";
              $values[$i][21] = "Aucun details";
              $values[$i][22] = "Aucun details";
              $values[$i][23] = "Aucun details";
              $values[$i][24] = "Aucun details";
              $values[$i][25] = "Aucun details";

            } else {
              $values[$i][20] = strtoupper($results[26]);
              $values[$i][21] = ucfirst($results[25]);
              $values[$i][22] = $results[27];
              $values[$i][23] = $results[28];
              $values[$i][24] = $results[29];
              $values[$i][25] = $results[30];
            }
          }

          # fisherman 3

          if ($results[31] != '') {
            $query = "SELECT first_name, last_name, idcard, t_card.card, t_nationality.nationality, telephone "
            . "FROM artisanal.fisherman LEFT JOIN artisanal.t_card ON fisherman.t_card = t_card.id "
            . "LEFT JOIN artisanal.t_nationality ON fisherman.t_nationality = t_nationality.id "
            . "WHERE fisherman.id ='$results[31]'";

            $fisherman = pg_fetch_row(pg_query($query));
            $values[$i][26] = $fisherman[0];
            $values[$i][27] = $fisherman[1];
            $values[$i][28] = $fisherman[2];
            $values[$i][29] = $fisherman[3];
            $values[$i][30] = $fisherman[4];
            $values[$i][31] = $fisherman[5];

          } else {
            if ($results[32] == '' AND $results[33] == '') {
              $values[$i][26] = "Aucun details";
              $values[$i][27] = "Aucun details";
              $values[$i][28] = "Aucun details";
              $values[$i][29] = "Aucun details";
              $values[$i][30] = "Aucun details";
              $values[$i][31] = "Aucun details";

            } else {
              $values[$i][26] = strtoupper($results[33]);
              $values[$i][27] = ucfirst($results[32]);
              $values[$i][28] = $results[34];
              $values[$i][29] = $results[35];
              $values[$i][30] = $results[36];
              $values[$i][31] = $results[37];
            }
          }

          # fisherman 4

          if ($results[38] != '') {
            $query = "SELECT first_name, last_name, idcard, t_card.card, t_nationality.nationality, telephone "
            . "FROM artisanal.fisherman LEFT JOIN artisanal.t_card ON fisherman.t_card = t_card.id "
            . "LEFT JOIN artisanal.t_nationality ON fisherman.t_nationality = t_nationality.id "
            . "WHERE fisherman.id ='$results[38]'";

            $fisherman = pg_fetch_row(pg_query($query));
            $values[$i][32] = $fisherman[0];
            $values[$i][33] = $fisherman[1];
            $values[$i][34] = $fisherman[2];
            $values[$i][35] = $fisherman[3];
            $values[$i][36] = $fisherman[4];
            $values[$i][37] = $fisherman[5];

          } else {
            if ($results[39] == '' AND $results[40] == '') {
              $values[$i][32] = "Aucun details";
              $values[$i][33] = "Aucun details";
              $values[$i][34] = "Aucun details";
              $values[$i][35] = "Aucun details";
              $values[$i][36] = "Aucun details";
              $values[$i][37] = "Aucun details";
            } else {
              $values[$i][32] = strtoupper($results[40]);
              $values[$i][33] = ucfirst($results[39]);
              $values[$i][34] = $results[41];
              $values[$i][35] = $results[42];
              $values[$i][36] = $results[43];
              $values[$i][37] = $results[44];
            }
          }

          $values[$i][38] = html_entity_decode($results[45]);
          $values[$i][39] = html_entity_decode($results[46]);
          $values[$i][40] = html_entity_decode($results[47]);
          $values[$i][41] = html_entity_decode($results[48]);
          $values[$i][42] = html_entity_decode($results[49]);
          $values[$i][43] = html_entity_decode($results[50]);
          $values[$i][44] = html_entity_decode($results[51]);
          $values[$i][45] = html_entity_decode($results[52]);
          $values[$i][46] = html_entity_decode($results[53]);
          $values[$i][47] = html_entity_decode($results[54]);
          $values[$i][48] = html_entity_decode($results[55]);
          $values[$i][49] = html_entity_decode($results[56]);
          $values[$i][50] = html_entity_decode($results[57]);

          $i++;

      }

      $filename = 'peche_artisanale_'.$table.'.csv';
      //print_r($values);
      write_Array2CSV($filename,$values,$header);

    } else if ($table == 'pirogue') {

        $q_id = "SELECT pirogue.id, datetime, username, name, immatriculation, t_pirogue.pirogue, length, id_owner, comments "
        . "FROM artisanal.pirogue "
        . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue ";

        $header = ['id', 'datetime', 'username', 'Nom pirogue', 'Numero d\'immatriculation', 'type pirogue',
            'length', 'id_owner', 'commentaires'];


        $r_id = pg_query($q_id);

        $filename = 'peche_artisanale_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'owner') {

        $q_id = "SELECT owner.id, datetime, username, first_name, last_name, bday date, t_card.card, "
                . "idcard, address, t_nationality.nationality, telephone, photo_path, comments "
        . "FROM artisanal.owner "
        . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.owner.t_card "
        . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.owner.t_nationality";

        $header = ['id', 'datetime', 'username', 'Prenom', 'Nom', 'date of birth', 'type of ID',
            'ID #', 'address', 'nationality', 'telephone', 'commentaires' ];

        print $q_id;

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanale_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'fisherman') {

        $q_id = "SELECT fisherman.id, datetime, username, t_site.site, first_name, last_name, "
                . "bday, t_nationality.nationality, t_card.card, idcard, telephone, address, photo_path "
        . "FROM artisanal.fisherman "
        . "LEFT JOIN artisanal.t_site ON artisanal.t_site.id = artisanal.fisherman.t_site "
        . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.fisherman.t_card "
        . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.fisherman.t_nationality";

        $header = ['id', 'datetime', 'username', 'D&eacute;barcad&egrave;re', 'Pr&eacute;nom', 'Nom',
                'date of birth', 'nationality', 'type of ID', 'ID', 'telephone', 'address'];

        print $q_id;

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanale_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'carte') {

        $q_id = "SELECT carte.id, datetime, username, carte, id_fisherman, "
                . "t_site.site, payment, receipt, date_d, date_f, id_license, carte_saisie "
        . "FROM artisanal.carte "
        . "LEFT JOIN artisanal.t_site ON artisanal.t_site.id = artisanal.carte.t_site";

        $header = ['id', 'datetime', 'username', 'carte #', 'id_fisherman', 'D&eacute;barcad&egrave;re', 'payment amount',
            'Num&eacute;ro de quittance', 'date start', 'date end', 'id_license', 'carte_saisie'];

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanale_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    }

} elseif ($format = 'PDF') {
  if ($table == 'infractions') {
    $filepath = './GB_report_infractions.pdf';
    chdir('../script_python');
    $command = 'python ./generate_REPORT_year.py "'. $year . '"';
    $output = exec($command);
//    print $command.$output;

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    flush(); // Flush system output buffer
    readfile($filepath);
    exit;

  }
}
