<?php
require("../../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}

$table = $_GET['table'];

if ($_GET['action'] == 'download') {
    if ($table == 'captures') {

        $q_id = "SELECT datetime, username, datetime_d, datetime_r, obs_name, t_site.site, fisherman_id, first_name, last_name, license_id, license_num, t_gear.gear, net_s, "
            . "net_l, n_days, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, sample_s, n_ind, st_astext(gps_track)"
            . " FROM artisanal.captures "
            . "LEFT JOIN fishery.species ON fishery.species.id = artisanal.captures.id_species "
            . " LEFT JOIN artisanal.t_site ON artisanal.t_site.id = artisanal.captures.t_site "
            . " LEFT JOIN artisanal.t_gear ON artisanal.t_gear.id = artisanal.captures.t_gear";

        print $q_id;

        $header = ['Date','Username','Departure date', 'Return date', 'Enqu&ecirc;teur', 'D&eacute;barcad&egrave;re', 'Fisherman ID', 'Pr&eacute;nom', 'Nom', 'Autorisation de p&ecirc;che', 'License #', 'Engin', 'Taille filet',
                'Net length', '# days', 'Species common name', 'Family', 'Genus', 'Species', 'Sample size', '# Individuals', 'GPS track'];

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanal_'.$table.'.csv';

        //write2CSV($filename,$r_id,$header);

    } else if ($table == 'effort') {

        $q_id = "SELECT datetime, username, date_e, obs_name, artisanal.t_site.site, DB1, DH1, DB3, DH3, PS1, PC1, PS3, PC3 "
                . "FROM artisanal.effort "
                . "LEFT JOIN artisanal.t_site ON artisanal.t_site.id = artisanal.effort.t_site";

        $header = ['Date', 'Username', 'Date collection', 'Enqu&ecirc;teur', 'D&eacute;barcad&egrave;re',
            'DB1', 'DH1', 'DB3', 'DH3', 'PS1', 'PC1', 'PS3', 'PC3'];

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanal_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'fleet') {

        $q_id = "SELECT datetime, username, date_f, obs_name, t_site.site, source, PPB, GPF, PPF, TOT "
                . "FROM artisanal.fleet "
                . "LEFT JOIN artisanal.t_site ON artisanal.t_site.id = artisanal.fleet.t_site";

        $header = ['Date', 'Username', 'Date collection', 'Enqu&ecirc;teur', 'D&eacute;barcad&egrave;re', 'Source', '# PPB', '# GPF', '# PPF', '# TOT'];

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanal_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'market') {

        $q_id = "SELECT datetime, username, date_m, obs_name, t_site.site, bp_f, bp_c, bp_fm, bp_s, "
                . "sar_f, sar_c, sar_fm, sar_s, sl_f, sl_c, sl_fm, sl_s, mac_f, mac_c, mac_fm, mac_s, "
                . "req_f, req_c, req_fm, req_s, ailreq_f, ailreq_c, ailreq_fm, ailreq_s, "
                . "lang_f, lang_c, lang_fm, lang_s, crab_f, crab_c, crab_fm, crab_s "
        . "FROM artisanal.market "
        . "LEFT JOIN artisanal.t_site ON artisanal.t_site.id = artisanal.market.t_site";

        $header = ['Date', 'Username', 'Date collection', 'Enqu&ecirc;teur', 'D&eacute;barcad&egrave;re',
            'bp_f', 'bp_c', 'bp_fm', 'bp_s', 'sar_f', 'sar_c', 'sar_fm', 'sar_s', 'sl_f', 'sl_c', 'sl_fm', 'sl_s',
            'mac_f', 'mac_c', 'mac_fm', 'mac_s', 'req_f', 'req_c', 'req_fm', 'req_s',
            'ailreq_f', 'ailreq_c', 'ailreq_fm', 'ailreq_s', 'lang_f', 'lang_c', 'lang_fm', 'lang_s',
            'crab_f', 'crab_c', 'crab_fm', 'crab_s'];

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanal_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'licenses') {

        $q_id = "SELECT license.id, datetime, username, license.license, date_d, date_f, t_license.license, payment, "
        . "receipt, t_zone.zone, t_site.site, engine_brand, engine_cv, t_gear.gear, t_coop.coop, id_pirogue "
        . "FROM artisanal.license "
        . "LEFT JOIN artisanal.t_site ON artisanal.t_site.id = artisanal.license.t_site "
        . "LEFT JOIN artisanal.t_zone ON artisanal.t_zone.id = artisanal.license.t_zone "
        . "LEFT JOIN artisanal.t_gear ON artisanal.t_gear.id = artisanal.license.t_gear "
        . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
        . "LEFT JOIN artisanal.t_license ON artisanal.t_license.id = artisanal.license.t_license";

        $header = ['id', 'datetime', 'username', 'license #', 'date start', 'date end', 'type of license',
            'payment amount', 'Num&eacute;ro de quittance', 'zone', 'D&eacute;barcad&egrave;re', 'engine brand', 'engine power',
            'type of gear', 'cooperative', 'id pirogue'];

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanal_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'pirogue') {

        $q_id = "SELECT pirogue.id, datetime, username, name, immatriculation, t_pirogue.pirogue, length, id_owner, comments "
        . "FROM artisanal.pirogue "
        . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue ";

        $header = ['id', 'datetime', 'username', 'Nom pirogue', 'Numero d\'immatriculation', 'type pirogue',
            'length', 'id_owner', 'commentaires'];


        $r_id = pg_query($q_id);

        $filename = 'peche_artisanal_'.$table.'.csv';

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

        $filename = 'peche_artisanal_'.$table.'.csv';

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

        $filename = 'peche_artisanal_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'carte') {

        $q_id = "SELECT carte.id, datetime, username, carte, id_fisherman, "
                . "t_site.site, payment, receipt, date_d, date_f, id_license, carte_saisie "
        . "FROM artisanal.carte "
        . "LEFT JOIN artisanal.t_site ON artisanal.t_site.id = artisanal.carte.t_site";

        $header = ['id', 'datetime', 'username', 'carte #', 'id_fisherman', 'D&eacute;barcad&egrave;re', 'payment amount',
            'Num&eacute;ro de quittance', 'date start', 'date end', 'id_license', 'carte_saisie'];

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanal_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'infraction') {

        $q_id = "SELECT infraction.id, datetime, username, date_i, t_infraction, id_license, "
                . "id_pirogue, pir_name, pir_reg, id_carte, id_fisherman, fish_first, fish_last, fish_idcard, "
                . "t_org, name, obj_confiscated, amount_infract, amount_paid, comments "
        . "FROM infraction.infraction "
        . "LEFT JOIN infraction.t_infraction ON infraction.t_infraction.id = infraction.infraction.t_infraction "
        . "LEFT JOIN infraction.t_org ON infraction.t_org.id = infraction.infraction.t_org";

        $header = ['id', 'datetime', 'username', 'date infraction', 'type infraction', 'id_license',
                'id_pirogue', 'pirogue name', 'pirogue immatriculation', 'id_carte', 'id_fisherman', 'Pr&eacute;nom p&ecirc;cheur',
            'Nom p&ecirc;cheur', 'Num&eacute;ro de pi&egrave;ce d\'identit&eacute;', 'officer organization', 'officer name', 'object confiscated', 'amount infract', 'amount infraction paid', 'commentaires'];

        $r_id = pg_query($q_id);

        $filename = 'peche_artisanal_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);
    }

}
