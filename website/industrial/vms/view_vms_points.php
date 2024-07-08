<?php
require("../../top_foot.inc.php");


$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'vms';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
$id = $_GET['id'];

$_SESSION['filter']['s_navire'] = $_POST['s_navire'];
$_SESSION['filter']['f_mere'] = $_POST['f_mere'];
$_SESSION['filter']['f_parc'] = $_POST['f_parc'];

if ($_GET['s_navire'] != "") {$_SESSION['filter']['s_navire'] = $_GET['s_navire'];}
if ($_GET['f_mere'] != "") {$_SESSION['filter']['f_mere'] = $_GET['f_mere'];}
if ($_GET['f_parc'] != "") {$_SESSION['filter']['f_parc'] = $_GET['f_parc'];}


$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

print "<h2>".label2name($source)." ".label2name($table)."</h2>";

if ($_GET['action'] == 'map') {

    $query_t = "SELECT navire.navire, date_p, ST_Y(location), ST_X(location) FROM vms.positions LEFT JOIN vms.navire ON navire.id = positions.id_navire ORDER BY date_p DESC LIMIT 10000";
    $query_r = pg_query($query_t);

    //print $query_t;

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
          zoom: 7,
          center: new google.maps.LatLng($lat_m, $lon_m),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/AiresProtegeesAquatiques_20170601_Final_EPSG4326.geojson');
        map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/ZoneTamponParcsMarins_20170601_Final.geojson');

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

    print "<br/><button onClick=\"goBack()\">Retourner</button>";


} else if ($_GET['action'] == 'show') {

    $start = $_GET['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>

    <form method="post" action="<?php echo $self;?>?source=vms&table=point&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Nom du navire</b></td></tr>
    <tr>
    <td>
    <select name="s_navire">
    <option value="name" selected="selected">Tous</option>
    <?php
    $result = pg_query("SELECT DISTINCT navire.id, navire.navire FROM vms.positions LEFT JOIN vms.navire ON positions.id_navire = navire.id ORDER BY navire");
    while($row = pg_fetch_row($result)) {
        print $_SESSION['filter']['s_navire'];
        if ("'".$row[1]."'" == $_SESSION['filter']['s_navire']) {
            print "<option value=\"'$row[1]'\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"'$row[1]'\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
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
    <td><b>Heure position</b></td>
    <td><b>Nom navire</b></td>
    <td><b>Lat, Lon</b></td>
    </tr>

    <?php

    # datetime, username, name, immatriculation, t_pirogue, length, t_site, id_owner
    if ($_SESSION['filter']['s_navire'] != "") {

        $_SESSION['start'] = 0;

            $query = "SELECT count(vms.positions.id) "
            . "FROM vms.positions "
            . "LEFT JOIN vms.navire ON navire.id = positions.id_navire "
            . "WHERE navire=".$_SESSION['filter']['s_navire']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT positions.datetime::date, date_p, navire.navire, ST_X(location), ST_Y(location) "
            . "FROM vms.positions "
            . "LEFT JOIN vms.navire ON navire.id = positions.id_navire "
            . "WHERE navire=".$_SESSION['filter']['s_navire']." "
            . "ORDER BY positions.datetime DESC OFFSET $start LIMIT $step";

    } else {

        $query = "SELECT count(id) FROM vms.positions";

        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT positions.datetime::date, date_p, navire.navire, ST_X(location), ST_Y(location) "
            . " FROM vms.positions "
            . "LEFT JOIN vms.navire ON navire.id = positions.id_navire "
            . "ORDER BY positions.datetime DESC OFFSET $start LIMIT $step";
    }

    #print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {
        print "<tr align=\"center\">";
        print "<td>$results[0]</td><td>$results[1]</td><td>$results[2]</td><td><a href=\"../view_point.php?X=$results[3]&Y=$results[4]\">$results[3] $results[4]</a></td>";
    }

    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_vms_points.php?source=vms&table=point&action=show&s_navire='.$_SESSION['filter']['s_navire'].'&f_mere='.$_SESSION['filter']['f_mere'].'&f_parc='.$_SESSION['filter']['f_parc']);

}

foot();
?>
