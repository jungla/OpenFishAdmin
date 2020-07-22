<?php
require("../top_foot.inc.php");


$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
$id = $_GET['id'];

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

#print "<h2>".label2name($source)." ".label2name($table)."</h2>";
print "<h3>D&eacute;tails route</h3>";

if ($_SESSION['where'][1] == 'seiners') {

$query = "SELECT route.id, route.datetime, route.username, navire, maree, date, time, speed, t_activite.activite, temperature, windspeed, comment, st_x(location), st_y(location) "
        . " FROM seiners.route "
        . " LEFT JOIN seiners.t_activite ON seiners.t_activite.id = seiners.route.t_activite "
        . "LEFT JOIN vms.navire ON vms.navire.id = seiners.route.id_navire "
        . "WHERE route.id = '$id'";

//print $query;

$results = pg_fetch_array(pg_query($query));

?>

<table id="results">
<tr><td><b>navire</b></td><td><?php echo $results[3]; ?></td></tr>
<tr><td><b>maree</b></td><td><?php echo $results[4]; ?></td></tr>
<tr><td><b>date</b></td><td><?php echo $results[5]; ?></td></tr>
<tr><td><b>time</b></td><td><?php echo $results[6]; ?></td></tr>
<tr><td><b>speed</b></td><td><?php echo $results[7]; ?></td></tr>
<tr><td><b>activite</b></td><td><?php echo $results[8]; ?></td></tr>
<tr><td><b>temperature</b></td><td><?php echo $results[9]; ?></td></tr>
<tr><td><b>windspeed</b></td><td><?php echo $results[10]; ?></td></tr>
<tr><td><b>Commentaires</b></td><td><?php echo $results[11]; ?></td></tr>
</table>

<div id="map" style="height: 400px; width:100%; border: 1px solid black; margin-left:0%; margin-top: 1%; margin-bottom: 1%; "></div>

<script>

function initMap() {
    var myLatLng = <?php print "{lat: $results[12], lng: $results[13]}"; ?>

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 8,
      center: myLatLng
    });

    map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/AiresProtegeesAquatiques_20170601_Final_EPSG4326.geojson');
    map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/ZoneTamponParcsMarins_20170601_Final.geojson');

    var marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      title: 'Hello World!'
    });
}
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBI5MQWC4N5SgUXs989_7MTDkQghaiGUuA&callback=initMap">
</script>

<br/>
<button onClick="goBack()">Retourner</button>
<br/>

<?php
} else if ($_SESSION['where'][1] == 'trawlers' and $_GET['table'] == 'route') {

    $query = "SELECT route.datetime, route.username, route.id, navire, maree, t_fleet.fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment,  st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f)  "
        . " FROM trawlers.route "
        . "LEFT JOIN trawlers.t_fleet ON trawlers.t_fleet.id = trawlers.route.t_fleet "
        . "LEFT JOIN vms.navire ON vms.navire.id = trawlers.route.id_navire "
        . "WHERE route.id = '$id'";

    //print $query;

$results = pg_fetch_array(pg_query($query));

?>

<table id="results">
<tr><td><b>Navire</b></td><td><?php echo $results[3]; ?></td></tr>
<tr><td><b>Mar&eacute;e</b></td><td><?php echo $results[4]; ?></td></tr>
<tr><td><b>Fleet</b></td><td><?php echo $results[5]; ?></td></tr>
<tr><td><b>Date</b></td><td><?php echo $results[6]; ?></td></tr>
<tr><td><b>Lanc&eacute;</b></td><td><?php echo $results[7]; ?></td></tr>
<tr><td><b>Heure debut</b></td><td><?php echo $results[8]; ?></td></tr>
<tr><td><b>Heure fin</b></td><td><?php echo $results[9]; ?></td></tr>
<tr><td><b>Prodondeur debut (m)</b></td>    <td><?php echo $results[10]; ?></td></tr>
<tr><td><b>Prodondeur fin (m)</b></td>    <td><?php echo $results[11]; ?></td></tr>
<tr><td><b>Vitesse (nd)</b></td>    <td><?php echo $results[12]; ?></td></tr>
<tr><td><b>Rejete (kg)</b></td>    <td><?php echo $results[13]; ?></td></tr>
<tr><td><b>Echantillion (kg)</b></td>    <td><?php echo $results[14]; ?></td></tr>
<tr><td><b>Remarque</b></td>   <td><?php echo $results[15]; ?></td></tr>
</table>

<div id="map" style="height: 400px; width:100%; border: 1px solid black; margin-left:0%; margin-top: 1%; margin-bottom: 1%; "></div>

<script>

function initMap() {
    var myLatLng = <?php print "{lat: $results[17], lng: $results[18]}"; ?>

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 8,
      center: myLatLng
    });

    map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/AiresProtegeesAquatiques_20170601_Final_EPSG4326.geojson');
    map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/ZoneTamponParcsMarins_20170601_Final.geojson');

    var marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      title: 'Hello World!'
    });
}
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBI5MQWC4N5SgUXs989_7MTDkQghaiGUuA&callback=initMap">
</script>

<br/>
<button onClick="goBack()">Retourner</button>
<br/>

<?php

} else if ($_SESSION['where'][1] == 'trawlers' and $_GET['table'] == 'route_accidentelle') {

    $query = "SELECT route_accidentelle.id, datetime, username, t_fleet.fleet, navire, maree, date date, t_co.co, lance, time, st_x(location), st_y(location)  "
        . " FROM trawlers.route_accidentelle "
        . "LEFT JOIN trawlers.t_co ON trawlers.t_co.id = trawlers.route_accidentelle.t_co "
        . "LEFT JOIN trawlers.t_fleet ON trawlers.t_fleet.id = trawlers.route_accidentelle.t_fleet "
        . "WHERE route_accidentelle.id = '$id'";

    #print $query;

$results = pg_fetch_array(pg_query($query));

?>

<table id="results">
<tr><td><b>Navire</b></td><td><?php echo $results[3]; ?></td></tr>
<tr><td><b>Mar&eacute;e</b></td><td><?php echo $results[4]; ?></td></tr>
<tr><td><b>Fleet</b></td><td><?php echo $results[5]; ?></td></tr>
<tr><td><b>Date</b></td><td><?php echo $results[6]; ?></td></tr>
<tr><td><b>Observe/Capture</b></td><td><?php echo $results[7]; ?></td></tr>
<tr><td><b>Lanc&eacute;</b></td><td><?php echo $results[8]; ?></td></tr>
<tr><td><b>Heure</b></td><td><?php echo $results[9]; ?></td></tr>
</table>

<div id="map" style="height: 400px; width:100%; border: 1px solid black; margin-left:0%; margin-top: 1%; margin-bottom: 1%; "></div>

<script>
function initMap() {
    var myLatLng = <?php print "{lat: $results[11], lng: $results[10]}"; ?>

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 8,
      center: myLatLng
    });

    map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/AiresProtegeesAquatiques_20170601_Final_EPSG4326.geojson');
    map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/ZoneTamponParcsMarins_20170601_Final.geojson');

    var marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      title: 'Hello World!'
    });
}
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBI5MQWC4N5SgUXs989_7MTDkQghaiGUuA&callback=initMap">
</script>

<br/>
<input type="submit" onClick="goBack()" value="Retourner" />
<br/>

<?php
}


foot();
