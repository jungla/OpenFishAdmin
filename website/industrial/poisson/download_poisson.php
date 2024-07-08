<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}

$table = $_GET['table'];

if ($_GET['action'] == 'download') {
    $filename = 'Crevette_'.$table.'.csv';

    if ($table == 'production') {

      $q_id = "SELECT lance.id, lance.datetime::date, lance.username, navire, date_l, t_zone.zone, lance, h_d, h_f, D_d, D_f, T_d, rejets, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c_cre, cc_cre, o_cre, v6_cre, st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f)"
      . " FROM crevette.lance "
      . "LEFT JOIN vms.navire ON vms.navire.id = crevette.lance.id_navire "
      . "LEFT JOIN crevette.t_zone ON crevette.t_zone.id = crevette.lance.t_zone "
      . "ORDER BY lance.datetime::date";

        $header = ['id', 'date', 'username', 'navire', 'date_l', 'zone', 'lance', 'heure dedut', 'heure fin', 'depth debut', 'depth fin', 'temp debut', 'rejets', 'c0_cre', 'c1_cre', 'c2_cre', 'c3_cre', 'c4_cre', 'c5_cre', 'c6_cre', 'c7_cre', 'c8_cre', 'c_cre', 'cc_cre', 'o_cre', 'v6_cre', 'GPS_debut X', 'GPS_debut Y', 'GPD_fin X', 'GPS_fin Y'];

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'prise_access') {

      $q_id = "SELECT capture.id, capture.username, capture.datetime::date, navire, capture.id_lance, lance.date_l, lance.lance, h_d, h_f, D_d, D_f, T_d, st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f), fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_taille.taille, poids FROM crevette.capture "
      . "LEFT JOIN crevette.lance ON crevette.lance.id = crevette.capture.id_lance "
      . "LEFT JOIN vms.navire ON vms.navire.id = crevette.lance.id_navire "
      . "LEFT JOIN crevette.t_zone ON crevette.t_zone.id = crevette.lance.t_zone "
      . "LEFT JOIN crevette.t_taille ON crevette.t_taille.id = crevette.capture.t_taille "
      . "LEFT JOIN fishery.species ON crevette.capture.id_species = fishery.species.id "
      . "ORDER BY capture.datetime::date";

        $header = ['id', 'username', 'datetime', 'navire', 'id_lance', 'date_l', 'lance', 'heure debut', 'heure fin', 'prof. debut', 'prof. fin', 'temp debut', 'lon_d','lat_d','lon_f', 'lat_f', 'nom francaise', 'species family', 'species genus', 'species species', 'taille', 'poids'];

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    }

}
