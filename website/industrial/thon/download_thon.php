<?php
require("../../top_foot.inc.php");


$_SESSION['where'][0] = 'industrial';

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}

$table = $_GET['table'];

if ($_GET['action'] == 'download') {

    $filename = 'Thon_'.$table.'.csv';

    if ($table == 'lance') {
      $q_id = "SELECT lance.id, lance.username, lance.datetime::date, vms.navire.navire, date_c, heure_c, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment, st_x(location), st_y(location) "
      . " rejete, fishery.species.FAO, taille, poids "
      . " FROM thon.lance "
      . "LEFT JOIN thon.captures ON thon.captures.id_lance = thon.lance.id "
      . "LEFT JOIN fishery.species ON thon.captures.id_species = fishery.species.id "
      . "LEFT JOIN vms.navire ON thon.lance.id_navire = vms.navire.id "
      . "ORDER BY lance.date_c";

        $header = ['id', 'username', 'date', 'navire', 'date lance', 'heure lance', 'eez', 'portant', 'banclibre', 'DCP balise', 'Temp eaux', 'Vent vitesse', 'Vent direction', 'Currant vitesse', 'Remarques', 'GPS Lon', 'GPS Lat', 'Rejete', 'FAO', 'Taille', 'Quantite'];

        $r_id = pg_query($q_id);

        print $q_id;
        write2CSV($filename,$r_id,$header);

    }

}
