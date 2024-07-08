<?php
require("../../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'pelagic';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
$id = $_GET['id'];

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

print "<h2>".label2name($source)." ".label2name($table)."</h2>";

$query_t = "SELECT st_astext(gps_track) FROM artisanal_catches.obs WHERE id = '$id'";
$gps_track = pg_fetch_array(pg_query($query_t));

//plot it in gmaps

$track_points = split(",", $gps_track[0]);

$lat = array();
$lon = array();

for ($i = 1; $i < count($track_points)-10; $i = $i+10) {
    $lat[] = split(" ",$track_points[$i])[1];
    $lon[] = split(" ",$track_points[$i])[0];
}

$lat_m = array_sum($lat)/count($lat);
$lon_m = array_sum($lon)/count($lon);


print "<div id=\"map\" style=\"height: 400px; width:100%; border: 1px solid black; float:right; margin-left:5%; margin-top:1em; margin-bottom:1em;\"></div>";

print "<script>
function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 11,
      center: {lat: $lat_m, lng: $lon_m},
      mapTypeId: 'terrain'
    });

    var flightPlanCoordinates = [";

    for ($i = 0; $i < count($lat)-2; $i++) {
        print "{lat: ".$lat[$i].", lng: ".$lon[$i]."},";
    }

    print "{lat: ".$lat[$i+1].", lng: ".$lon[$i+1]."}";

print "];
    var flightPath = new google.maps.Polyline({
      path: flightPlanCoordinates,
      geodesic: true,
      strokeColor: '#FF0000',
      strokeOpacity: 1.0,
      strokeWeight: 2
    });

    flightPath.setMap(map);
  }

</script>
<script async defer
src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyBI5MQWC4N5SgUXs989_7MTDkQghaiGUuA&callback=initMap\">
</script>";

print "<br/>
<a href=\"javascript:history.back()\">Retourner</a>";

foot();
    ?>
