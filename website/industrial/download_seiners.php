<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'industrial';

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}

$table = $_GET['table'];

if ($_GET['action'] == 'download') {

    $filename = 'Observateurs_Thoniere_'.$table.'.csv';

    if ($table == 'route') {

        $q_id = "SELECT route.datetime::date, route.username, navire, maree, date, time, speed, t_activite.activite, t_neighbours.neighbours, temperature, windspeed, comment, st_x(location), st_y(location) "
            . " FROM seiners.route "
            . "LEFT JOIN vms.navire ON vms.navire.id = seiners.route.id_navire "
            . "LEFT JOIN seiners.t_activite ON seiners.t_activite.id = seiners.route.t_activite "
            . "LEFT JOIN seiners.t_neighbours ON seiners.t_neighbours.id = seiners.route.t_neighbours "
            . "ORDER BY datetime";

        $header = ['datetime', 'username', 'navire', 'maree', 'date', 'time', 'speed', 'activite', 'neighbours', 'temperature', 'windspeed', 'comment', 'GPS_x', 'GPS_y'];

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'objet') {

        $q_id = "SELECT objet.datetime::date, objet.username, n_objet, objet.maree, t_zee.zee, route.date, route.time, t_objet.objet, type_balise, code_balise, t_operation.operation, t_appartenance.appartenance, t_devenir.devenir, remarque, st_x(route.location), st_y(route.location) "
        . " FROM seiners.objet "
        . "LEFT JOIN seiners.t_objet ON seiners.t_objet.id = seiners.objet.t_objet "
        . "LEFT JOIN seiners.t_operation ON seiners.t_operation.id = seiners.objet.t_operation "
        . "LEFT JOIN seiners.t_appartenance ON seiners.t_appartenance.id = seiners.objet.t_appartenance "
        . "LEFT JOIN seiners.t_devenir ON seiners.t_devenir.id = seiners.objet.t_devenir "
        . "LEFT JOIN seiners.t_zee ON seiners.t_zee.id = seiners.objet.t_zee "
        . "LEFT JOIN seiners.route ON seiners.route.id = seiners.objet.id_route "
        . "ORDER BY route.date, route.time";

        $header = ['datetime', 'username', 'n_objet', 'maree', 'zee', 'date', 'time', 'type objet', 'type balise', 'code balise', 'operation', 'appartenance', 'devenir', 'remarque', 'GPS_x', 'GPS_y'];

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'thon_ret') {

        $q_id = "SELECT thon_retenue.datetime::date, thon_retenue.username, thon_retenue.maree, route.date, route.time, n_calee, t_zee.zee, t_type.type, h_d, h_c, h_f, vitesse, direction, d_max, sonar, raison, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, fishery.species.OBS, fishery.species.FAO, t_categorie.categorie, poids, cuve, remarque, st_x(route.location), st_y(route.location)  "
        . " FROM seiners.thon_retenue "
        . "LEFT JOIN seiners.t_zee ON seiners.t_zee.id = seiners.thon_retenue.t_zee "
        . "LEFT JOIN seiners.t_type ON seiners.t_type.id = seiners.thon_retenue.t_type "
        . "LEFT JOIN fishery.species ON fishery.species.id = seiners.thon_retenue.id_species "
        . "LEFT JOIN seiners.t_categorie ON seiners.t_categorie.id = seiners.thon_retenue.t_categorie "
        . "LEFT JOIN seiners.route ON seiners.route.id = seiners.thon_retenue.id_route "
        . "ORDER BY datetime";

        $header = ['datetime', 'username', 'maree', 'date', 'time', 'n_calee', 'zee', 'type peche',  'heure debut', 'heure calee', 'heure fin', 'vitesse', 'direction', 'profondeur', 'sonar', 'raison coup null', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'categorie taille', 'poids capture (tonne)', 'cuve', 'remarque', 'GPS_x', 'GPS_y'];

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'thon_rej') {

        $q_id = "SELECT thon_rejete.datetime::date, thon_rejete.username, thon_rejete.maree, route.date, route.time, n_calee, t_zee.zee,  t_type.type,  h_d, h_c, h_f, vitesse, direction, d_max, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, fishery.species.OBS, fishery.species.FAO, t_categorie.categorie, t_raison.raison, poids, monte, photo, remarque, st_x(route.location), st_y(route.location)  "
        . " FROM seiners.thon_rejete "
        . "LEFT JOIN seiners.t_zee ON seiners.t_zee.id = seiners.thon_rejete.t_zee "
        . "LEFT JOIN seiners.t_type ON seiners.t_type.id = seiners.thon_rejete.t_type "
        . "LEFT JOIN fishery.species ON fishery.species.id = seiners.thon_rejete.id_species "
        . "LEFT JOIN seiners.t_categorie ON seiners.t_categorie.id = seiners.thon_rejete.t_categorie "
        . "LEFT JOIN seiners.t_raison ON seiners.t_raison.id = seiners.thon_rejete.t_raison "
        . "LEFT JOIN seiners.route ON seiners.route.id = seiners.thon_rejete.id_route "
        . "ORDER BY date, time";

        //print $q_id;

        $header = ['datetime', 'username', 'maree', 'date', 'time', 'n_calee', 'zee', 'type peche',  'heure debut', 'heure calee', 'heure fin', 'vitesse', 'direction', 'profondeur', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'categorie taille', 'raison rejet', 'poids capture (tonne)', 'monte a bord', 'photo', 'remarque', 'GPS_x', 'GPS_y'];

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'thon_rej_taille') {

        $labels_c = ['009', '010', '011', '012', '013', '014', '015', '016', '017', '018', '019', '020', '021', '022', '023', '024', '025', '026', '027', '028', '029', '030', '031', '032', '033', '034', '035', '036', '037', '038', '039', '040', '041', '042', '043', '044', '045', '046', '047', '048', '049', '050', '051', '052', '053', '054', '055', '056', '057', '058', '059', '060', '061', '062', '063', '064', '065', '066', '067', '068', '069', '070', '071', '072', '073', '074', '075', '076', '077', '078', '079', '080', '081', '082', '083', '084', '085', '086', '087', '088', '089', '090', '091', '092', '093', '094', '095', '096', '097', '098', '099', '100', '110', '111', '112', '135', '138', '139', '140', '144', '145', '146', '147', '148', '149', '150', '151', '154', '155', '156', '157', '158', '159', '160', '170'];

        $q_id = "SELECT thon_rejete_taille.datetime::date, thon_rejete_taille.username, thon_rejete_taille.maree, route.date, route.time, n_calee, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, fishery.species.OBS, fishery.species.FAO ";

        foreach($labels_c as $label) {
            $q_id = $q_id.", c".$label;
        }

        $q_id = $q_id. " FROM seiners.thon_rejete_taille "
        . "LEFT JOIN seiners.route ON seiners.route.id = seiners.thon_rejete_taille.id_route "
        . "LEFT JOIN fishery.species ON fishery.species.id = seiners.thon_rejete_taille.id_species "
        . "ORDER BY route.date, route.time";

        $header = ['datetime', 'username', 'maree', 'date', 'time', 'n_calee', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO'];

        foreach($labels_c as $label) {
            $header[] = $label;
        }

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'prise_access') {

        $q_id = "SELECT prise_access.datetime::date, prise_access.username, t_zee.zee, prise_access.maree, n_calee, route.date, route.time, t_type.type, h_d, h_c, h_f, vitesse, direction, d_max, t_prise.prise, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species,fishery.species.OBS,fishery.species.FAO, t_action.action, t_raison.raison, poids, n_ind, taille, photo, remarque, st_x(route.location), st_y(route.location)  "
        . " FROM seiners.prise_access "
        . "LEFT JOIN seiners.t_zee ON seiners.t_zee.id = seiners.prise_access.t_zee "
        . "LEFT JOIN seiners.t_type ON seiners.t_type.id = seiners.prise_access.t_type "
        . "LEFT JOIN fishery.species ON fishery.species.id = seiners.prise_access.id_species "
        . "LEFT JOIN seiners.t_prise ON seiners.t_prise.id = seiners.prise_access.t_prise "
        . "LEFT JOIN seiners.t_action ON seiners.t_action.id = seiners.prise_access.t_action "
        . "LEFT JOIN seiners.t_raison ON seiners.t_raison.id = seiners.prise_access.t_raison "
        . "LEFT JOIN seiners.route ON seiners.route.id = seiners.prise_access.id_route "
        . "ORDER BY route.date, route.time";

        $header = ['datetime', 'username', 'zee', 'maree', 'calee', 'date', 'time', 'type peche', 'heure debut', 'heure calee', 'heure fin', 'vitesse', 'direction', 'profondeur', 'type prise', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'action', 'raison rejet', 'poids', 'nombre individue', 'taille moyenne (cm)', 'photo', 'remarque', 'GPS_x', 'GPS_y'];

        //print $q_id;

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'prise_access_taille') {

        $q_id = "SELECT prise_access_taille.datetime::date, prise_access_taille.username, id_route, prise_access_taille.maree, n_cale, route.date, route.time, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, fishery.species.OBS, fishery.species.FAO, t_measure.measure, taille, poids, t_sexe.sexe, t_capture.capture, t_relache.relache, photo, remarque, st_x(route.location), st_y(route.location)  "
        . " FROM seiners.prise_access_taille "
        . "LEFT JOIN fishery.species ON fishery.species.id = seiners.prise_access_taille.id_species "
        . "LEFT JOIN seiners.t_sexe ON seiners.t_sexe.id = seiners.prise_access_taille.t_sexe "
        . "LEFT JOIN seiners.t_measure ON seiners.t_measure.id = seiners.prise_access_taille.t_measure "
        . "LEFT JOIN seiners.t_relache ON seiners.t_relache.id = seiners.prise_access_taille.t_relache "
        . "LEFT JOIN seiners.t_capture ON seiners.t_capture.id = seiners.prise_access_taille.t_capture "
        . "LEFT JOIN seiners.route ON seiners.route.id = seiners.prise_access_taille.id_route "
        . "ORDER BY route.date, route.time";

        $header = ['datetime', 'username', 'id_route', 'maree', 'n_cale', 'date', 'time', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'measure', 'taille', 'poids', 'sexe', 'capture', 'relache', 'photo', 'remarque', 'GPS_x', 'GPS_y'];

        print $q_id;

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    }

}
