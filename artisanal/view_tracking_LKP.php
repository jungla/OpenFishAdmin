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

if ($_GET['s_pir_name'] != "") {$_SESSION['filter']['s_pir_name'] = $_GET['s_pir_name'];}


$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

print "<h2>".label2name($source)." ".label2name($table)."</h2>";

if ($_GET['action'] == 'map') {

    $query_t = "SELECT name, date_t, ST_Y(location), ST_X(location) FROM artisanal.pelagic_lkp";    
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

        map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/AiresProtegeesAquatiques_20170601_Final_EPSG4326.geojson');
        map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/ZoneTamponParcsMarins_20170601_Final.geojson');

        map.data.setStyle({
          fillColor: 'green',
          strokeWeight: 1
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        for (i = 0; i < locations.length; i++) {  
          marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map
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

    <form method="post" action="<?php echo $self;?>?source=pelagic&table=pirogue&action=show" enctype="multipart/form-data">
    <fieldset>
    
    <table id="no-border"><tr><td><b>Nom du pirogue</b></td></tr>
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
    <td><b>Lat, Lon</b></td>
    </tr>
    
    <?php
    
    # datetime, username, name, immatriculation, t_pirogue, length, t_site, id_owner
    if ($_SESSION['filter']['s_pir_name'] != "") {

        $_SESSION['start'] = 0;

        $query = "SELECT count(id) FROM artisanal.pelagic_lkp "
        . "WHERE name=".$_SESSION['filter']['s_pir_name']." ";
            
        $pnum = pg_fetch_row(pg_query($query))[0];
            
        $query = "SELECT datetime, date_t, name, ST_X(location), ST_Y(location) "
        . " FROM artisanal.pelagic_lkp "
        . "WHERE name=".$_SESSION['filter']['s_pir_name']." "
        . "ORDER BY name DESC OFFSET $start LIMIT $step";   

    } else {
        
        $query = "SELECT count(id) FROM artisanal.pelagic_lkp";

        $pnum = pg_fetch_row(pg_query($query))[0];
        
        $query = "SELECT datetime, date_t, name, ST_X(location), ST_Y(location) "
            . " FROM artisanal.pelagic_lkp "
            . "ORDER BY name DESC OFFSET $start LIMIT $step";
    }
    
    $r_query = pg_query($query);
    
    while ($results = pg_fetch_row($r_query)) {
        
        print "<tr align=\"center\">";

        print "<td>$results[0]</td><td>$results[1]</td><td>$results[2]</td><td><a href=\"view_point.php?X=$results[3]&Y=$results[4]\">$results[3] $results[4]</a></td>";
        
    }
    print "</tr>";
    
    print "</table>";
    
    pages($start,$step,$pnum,'./view_licenses_pirogue.php?source=pelagic&table=pirogue&action=show&s_pir_name='.$_SESSION['filter']['s_pir_name'].'&f_t_pirogue='.$_SESSION['filter']['f_t_pirogue'].'&s_pir_reg='.$_SESSION['filter']['s_pir_reg']);
    
}

foot();
    ?>

