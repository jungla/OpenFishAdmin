<?php
require("../top_foot.inc.php");

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}

$table = $_GET['table'];

    if ($table == 'pirogue') {
      $date = date_create();
      date_sub($date, date_interval_create_from_date_string('6 months'));
      $Y1 = date_format($date, 'Y');
      date_sub($date, date_interval_create_from_date_string('12 months'));
      $Y2 = date_format($date, 'Y');
      date_sub($date, date_interval_create_from_date_string('12 months'));
      $Y3 = date_format($date, 'Y');
      date_sub($date, date_interval_create_from_date_string('12 months'));
      $Y4 = date_format($date, 'Y');

        $q_id = "SELECT DISTINCT pirogue.datetime::date, pirogue.username, name, immatriculation, t_pirogue.pirogue, pirogue.length, license.active, first_name, upper(last_name),  "
        . "pirogue.comments "
        . "FROM artisanal.pirogue "
        . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = pirogue.t_pirogue "
        . "LEFT JOIN artisanal.owner ON artisanal.owner.id = pirogue.id_owner "
        . "LEFT JOIN artisanal.license ON artisanal.license.id_pirogue = pirogue.id "
        . "ORDER BY pirogue.datetime::date";

        $header = ['Date Saisie', 'Nom Utilisateur', 'Nom pirogue', 'Numero d\'immatriculation', 'Type pirogue',
            'longeur', 'Authorisation active '.$Y1, 'Prenom proprietaire', 'Nom proprietaire', 'Commentaires'];

        //print $q_id;

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanal_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'owner') {
      $date = date_create();
      date_sub($date, date_interval_create_from_date_string('6 months'));
      $Y1 = date_format($date, 'Y');
      date_sub($date, date_interval_create_from_date_string('12 months'));
      $Y2 = date_format($date, 'Y');
      date_sub($date, date_interval_create_from_date_string('12 months'));
      $Y3 = date_format($date, 'Y');
      date_sub($date, date_interval_create_from_date_string('12 months'));
      $Y4 = date_format($date, 'Y');

        $q_id = "SELECT DISTINCT owner.datetime, owner.username, first_name, UPPER(last_name), bday date, t_card.card, "
                . "idcard, address, t_nationality.nationality, telephone, "
                . "owner.comments FROM artisanal.owner "
                . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.owner.t_card "
                . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.owner.t_nationality "
                . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id_owner = artisanal.owner.id "
                . "LEFT JOIN artisanal.license ON artisanal.pirogue.id = artisanal.license.id_pirogue ";

        $header = ['Date saisie', 'Nom utilizateur', 'Prenom proprietaire', 'Nom proprietaire', 'date de naissance', 'type de ID',
            'ID #', 'addresse', 'nationalite', 'telephone', 'commentaires' ];

        //print $q_id;

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanal_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'fisherman') {

        $q_id = "SELECT DISTINCT datetime, username, first_name, last_name, "
          . "bday, t_nationality.nationality, t_card.card, idcard, telephone, address  "
          . "FROM artisanal.fisherman "
          . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.fisherman.t_card "
          . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.fisherman.t_nationality";

        $header = ['Date saisie', 'Nom utilizateur', 'Pr&eacute;nom', 'Nom',
                'date of birth', 'nationality', 'type of ID', 'ID', 'telephone', 'address'];

        //print $q_id;

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanal_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'carte') {

        $q_id = "SELECT DISTINCT carte.datetime::date, carte.username, carte, EXTRACT(year FROM carte.date_v), fisherman.first_name, fisherman.last_name, t_nationality.nationality, t_coop.coop, license, t_strata.strata, license.active, carte.paid, name, immatriculation  "
        . " FROM artisanal.carte "
        . "LEFT JOIN artisanal.fisherman ON artisanal.fisherman.id = artisanal.carte.id_fisherman "
        . "LEFT JOIN artisanal.t_nationality ON artisanal.fisherman.t_nationality = artisanal.t_nationality.id "
        . "LEFT JOIN artisanal.license ON artisanal.license.id = artisanal.carte.id_license "
        . "LEFT JOIN artisanal.t_strata ON artisanal.license.t_strata = artisanal.t_strata.id "
        . "LEFT JOIN artisanal.t_coop ON artisanal.license.t_coop = artisanal.t_coop.id "
        . "LEFT JOIN artisanal.pirogue ON artisanal.license.id_pirogue = artisanal.pirogue.id ";

        $header = ['Date saisie', 'Nom utilizateur', 'carte #', 'Annee validite carte','Prenom pecheur', 'Nom pecheur', 'Nationalite', 'Cooperative', 'License', 'Strata', 'Actif', 'Carte Payee',
            'Nom pirogue', 'Immatriculation'];

        $r_id = pg_query($q_id);
        print $q_id;

        $filename = 'peche_artisanal_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    }
