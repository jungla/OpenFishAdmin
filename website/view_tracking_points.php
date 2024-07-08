<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'pelagic';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
$id = $_GET['id'];

$_SESSION['filter']['s_pir_name'] = $_POST['s_pir_name'];
$_SESSION['filter']['f_mere'] = $_POST['f_mere'];
$_SESSION['filter']['f_parc'] = $_POST['f_parc'];

if ($_GET['s_pir_name'] != "") {$_SESSION['filter']['s_pir_name'] = $_GET['s_pir_name'];}
if ($_GET['f_mere'] != "") {$_SESSION['filter']['f_mere'] = $_GET['f_mere'];}
if ($_GET['f_parc'] != "") {$_SESSION['filter']['f_parc'] = $_GET['f_parc'];}


$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

print "<h2>".label2name($source)." ".label2name($table)."</h2>";

if ($_GET['action'] == 'map') {

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
            print "['".$LKP[$i][0]." ".$LKP[$i][1]."', ".$LKP[$i][2].", ".$LKP[$i][3]."],";
        }

        print "['".$LKP[count($LKP)-1][0]." ".$LKP[count($LKP)-1][1]."', ".$LKP[count($LKP)-1][2].", ".$LKP[count($LKP)-1][3]."]";

        print "];

    function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: new google.maps.LatLng($lat_m, $lon_m),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        map.data.loadGeoJson('https://data.gabonbleu.org/shapefiles/AiresProtegeesAquatiques_20170601_Final_EPSG4326.geojson');
        map.data.loadGeoJson('https://data.gabonbleu.org/shapefiles/Aires_Protegees.geojson');

        map.data.setStyle({
          fillColor: 'green',
          strokeWeight: 1
        });


        var infowindow = new google.maps.InfoWindow();

        var circle = {
        path: google.maps.SymbolPath.CIRCLE,
        fillColor: 'red',
        fillOpacity: .4,
        scale: 4.5,
        strokeColor: 'white',
        strokeWeight: 1
        };

        var marker, i;

        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: 'https://storage.googleapis.com/support-kms-prod/SNP_2752125_en_v0'
          });

          google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
              infowindow.setContent(locations[i][0]);
              infowindow.open(map, marker);
            }
          })(marker, i));
        }
    }
        </script>

    <script async defer
    src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyBI5MQWC4N5SgUXs989_7MTDkQghaiGUuA&callback=initMap\">
    </script>";

    print "<br/><a href=\"javascript:history.back()\">Retourner</a>";


} else if ($_GET['action'] == 'show') {

    $start = $_GET['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>

    <form method="post" action="<?php echo $self;?>?source=pelagic&table=point&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Nom du pirogue</b></td><td><b>Points dans les AMPs</b></td></tr>
    <tr>
    <td>
    <select name="s_pir_name">
    <option value="name" selected="selected">Tous</option>
    <?php
    $result = pg_query("SELECT id, name FROM artisanal.pelagic_lkp");
    while($row = pg_fetch_row($result)) {
        print $_SESSION['filter']['s_pir_name'];
        if ("'".$row[1]."'" == $_SESSION['filter']['s_pir_name']) {
            print "<option value=\"'$row[1]'\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"'$row[1]'\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    </td>
    <td>
    <input type="radio" name="f_parc" value="TRUE" <?php if($_SESSION['filter']['f_parc'] == "TRUE"){ print "checked=\"checked\"";}?> />Oui<br/>
    <input type="radio" name="f_parc" value="FALSE" <?php if($_SESSION['filter']['f_parc'] == "FALSE"){ print "checked=\"checked\"";}?> />Non<br/>
    <input type="radio" name="f_parc" value="" <?php if($_SESSION['filter']['f_parc'] == ""){ print "checked=\"checked\"";}?> />tous les deux<br/>
    </td>
    </tr>
    </table>
    <input type="submit" name="Filter" value="filter" />
    </fieldset>
    </form>

    <br/>

    <table>
    <tr align="center">
    <td><b>Derni&egrave;re mise &agrave; jour</b></td>
    <td><b>Heure derni&egrave;re position</b></td>
    <td><b>Nom de la pirogue</b></td>
    <td><b>Vitesse</b></td>
    <td><b>Distance</b></td>
    <td><b>Direction</b></td>
    <td><b>Lat, Lon</b></td>
    </tr>

    <?php

    # datetime, username, name, immatriculation, t_pirogue, length, t_site, id_owner
    if ($_SESSION['filter']['s_pir_name'] != "" OR $_SESSION['filter']['f_parc'] != "") {

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_parc'] == "TRUE") {
            $query = "SELECT count(artisanal.pelagic_points.id) "
            . "FROM artisanal.pelagic_points "
            . "INNER JOIN shapefiles.mpa ON ST_Intersects(artisanal.pelagic_points.location, shapefiles.mpa.geom) "
            . "WHERE name=".$_SESSION['filter']['s_pir_name']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT datetime, date_t, name, speed, range, heading, ST_X(location), ST_Y(location) "
            . "FROM artisanal.pelagic_points "
            . "INNER JOIN shapefiles.mpa ON ST_Intersects(artisanal.pelagic_points.location, shapefiles.mpa.geom) "
            . "WHERE name=".$_SESSION['filter']['s_pir_name']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";

        } else if ($_SESSION['filter']['f_parc'] == "FALSE") {
            $query = "SELECT count(name) "
            . "FROM artisanal.pelagic_points, "
            . "(SELECT st_union(shapefiles.mpa.geom) AS geom FROM shapefiles.mpa) AS mpa "
            . "WHERE NOT st_contains(mpa.geom, artisanal.pelagic_points.location) AND name=".$_SESSION['filter']['s_pir_name']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT datetime, date_t, name, speed, range, heading, ST_X(location), ST_Y(location) "
            . "FROM artisanal.pelagic_points, "
            . "(SELECT st_union(shapefiles.mpa.geom) AS geom FROM shapefiles.mpa) AS mpa "
            . "WHERE NOT st_contains(mpa.geom, artisanal.pelagic_points.location) AND name=".$_SESSION['filter']['s_pir_name']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";


        } else {
            $query = "SELECT count(artisanal.pelagic_points.id) "
            . "FROM artisanal.pelagic_points "
            . "WHERE name=".$_SESSION['filter']['s_pir_name']." ";
            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT datetime, date_t, name, speed, range, heading, ST_X(location), ST_Y(location) "
            . "FROM artisanal.pelagic_points "
            . "WHERE name=".$_SESSION['filter']['s_pir_name']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";

        }

    } else {

        $query = "SELECT count(id) FROM artisanal.pelagic_points";

        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT datetime, date_t, name, speed, range, heading, ST_X(location), ST_Y(location) "
            . " FROM artisanal.pelagic_points "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
    }

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {
        print "<tr align=\"center\">";
        print "<td>$results[0]</td><td>$results[1]</td><td>$results[2]</td><td>$results[3]</td><td>$results[4]</td><td>$results[5]</td><td><a href=\"view_point.php?X=$results[6]&Y=$results[7]\">$results[6] $results[7]</a></td>";
    }

    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_tracking_points.php?source=pelagic&table=point&action=show&s_pir_name='.$_SESSION['filter']['s_pir_name'].'&f_mere='.$_SESSION['filter']['f_mere'].'&f_parc='.$_SESSION['filter']['f_parc']);

}

foot();
?>
