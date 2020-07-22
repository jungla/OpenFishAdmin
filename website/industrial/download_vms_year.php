<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}

$table = $_POST['table'];
$format = $_POST['format'];
$year = $_POST['year'];

if ($format == 'CSV') {

    if ($table == 'point') {

      $q_id = "SELECT positions.datetime, date_p, speed, navire.navire, ST_X(location), ST_Y(location) "
          . " FROM vms.positions "
          . "LEFT JOIN vms.navire ON navire.id = positions.id_navire "
          . "WHERE date_p < (now() - '30 day'::interval) "
          . "AND (extract(year from date_p) = ".$year.") "
          . "ORDER BY date_p";

        $header = ['date', 'date position', 'vitesse', 'navire', 'lon', 'lat'];
        print $q_id;
        $r_id = pg_query($q_id);

        $filename = 'VMS_'.$table.'.csv';

        write2CSV($filename,$r_id,$header);

    }

}
