<?php

require('../../connect.inc.php');
require('../../functions.inc.php');

dbconnect();


if ($_GET['lat'] != '' & $_GET['lon'] != '' & $_GET['date_p'] != '') {

    $query_t = "SELECT navire.navire, ST_Distance('SRID=4326;POINT(".$_GET['lon']." ".$_GET['lat'].")'::geometry, vms.positions.location), date_p, ST_Y(location), ST_X(location) "
            . "FROM vms.positions LEFT JOIN vms.navire ON navire.id = positions.id_navire "
            . "WHERE date_p::date = '".$_GET['date_p']."' "
            . "ORDER BY ST_Distance('SRID=4326;POINT(".$_GET['lon']." ".$_GET['lat'].")'::geometry, vms.positions.location) ASC LIMIT 1";

    //print $query_t;

    $query_r = pg_fetch_row(pg_query($query_t));
    //print $query_r[0];
    print $query_r[1]." ".$query_r[2];


}
?>
