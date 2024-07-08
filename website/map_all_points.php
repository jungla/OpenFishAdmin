<?php
require("./top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'pelagic';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

print "<h2>".label2name($source)." ".label2name($table)."</h2>";

$query_t = "SELECT name, date_t, ST_Y(location), ST_X(location) FROM artisanal.pelagic_points";    
$query_r = pg_query($query_t);

//plot it in gmaps

$lon_m = 0;
$lat_m = 0;

while($row = pg_fetch_array($query_r) ){
        $LKP[] = $row;
    }

for ($i = 0; $i < count($LKP); $i++) {
    $lon_m = $lon_m + $LKP[$i][3];
    $lat_m = $lat_m + $LKP[$i][2];
}

$lon_m = $lon_m/count($LKP);
$lat_m = $lat_m/count($LKP);

print "<div id=\"map\" style=\"height: 400px; width:100%; border: 1px solid black; float:right; margin-left:5%; margin-top:1em;\"></div>";

print "<script  type=\"text/javascript\">

var locations = [";

for ($i = 0; $i < count($LKP)-2; $i++) {
    print "{lat: ".$LKP[$i][2].", lng: ".$LKP[$i][3]."},";
}

print "{lat: ".$LKP[count($LKP)-1][2].", lng: ".$LKP[count($LKP)-1][3]."}";

print "];

function initMap() {

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 12,
      center: new google.maps.LatLng($lat_m, $lon_m),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/AiresProtegeesAquatiques_20170601_Final_EPSG4326.geojson');
    map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/ZoneTamponParcsMarins_20170601_Final.geojson');

    map.data.setStyle({
      fillColor: 'green',
      strokeWeight: 1
    });

    var markers = locations.map(function(location, i) {
      return new google.maps.Marker({
        position: location
      });
    });

    // Add a marker clusterer to manage the markers.
    var markerCluster = new MarkerClusterer(map, markers,
        {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});


}    
</script>

<script src=\"https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js\">
</script>
    
<script async defer
src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyBI5MQWC4N5SgUXs989_7MTDkQghaiGUuA&callback=initMap\">
</script>";

print "<br/><a href=\"javascript:history.back()\">Retourner</a>";




foot();
?>

