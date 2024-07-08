<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'trawlers';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_t_fleet'] = $_POST['f_t_fleet'];
$_SESSION['filter']['f_s_maree'] = $_POST['f_s_maree'];
$_SESSION['filter']['f_id_navire'] = $_POST['f_id_navire'];

if ($_GET['f_t_fleet'] != "") {$_SESSION['filter']['f_t_fleet'] = $_GET['f_t_fleet'];}
if ($_GET['f_s_maree'] != "") {$_SESSION['filter']['f_s_maree'] = $_GET['f_s_maree'];}
if ($_GET['f_id_navire'] != "") {$_SESSION['filter']['f_id_navire'] = $_GET['f_id_navire'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'map') {

    $query_t = "SELECT navire, date, ST_Y(location_d), ST_X(location_d) FROM trawlers.route LEFT JOIN vms.navire ON trawlers.route.id_navire = vms.navire.id";
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

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=trawlers&table=route_accidentelle&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Navire</b></td><td><b>Maree</b></td><td><b>Flottille</b></td></tr>
    <tr>
    <td>
    <select name="f_id_navire">
    <option value="id_navire" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT route.id_navire, navire FROM trawlers.route LEFT JOIN vms.navire ON vms.navire.id = trawlers.route.id_navire ORDER BY navire");
        while($row = pg_fetch_row($result)) {
        if ("'".$row[0]."'" == $_SESSION['filter']['f_id_navire']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">$row[1]</option>";
            } else {
                print "<option value=\"'$row[0]'\">$row[1]</option>";
            }
        }
    ?>
    </select>
    </td>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <select name="f_t_fleet">
        <option value="t_fleet" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT id, fleet FROM trawlers.t_fleet");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_fleet']) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[1]."</option>";
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
    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Navire</b></td>
    <td><b>Maree</b></td>
    <td><b>Fleet</b></td>
    <td><b>Date</b></td>
    <td><b>Capture/Observation</b></td>
    <td><b>Lanc&eacute;</b></td>
    <td><b>Heure</b></td>
    <td><b>Position GPS</b></td>
    </tr>

    <?php

    // fetch data

    #id, datetime, username, navire, maree, date date, t_co, lance, heure

    if ($_SESSION['filter']['f_id_navire'] != "" OR $_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_t_fleet'] != "") {

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(route_accidentelle.id) FROM trawlers.route_accidentelle "
            . "WHERE trawlers.route_accidentelle.t_fleet=".$_SESSION['filter']['f_t_fleet']." "
            . "OR trawlers.route_accidentelle.t_fleet IS NULL ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT route_accidentelle.id, route_accidentelle.datetime::date, route_accidentelle.username, t_fleet.fleet, navire, maree, date, t_co.co, lance, time, st_x(location), st_y(location), id_navire, "
            . " coalesce(similarity(trawlers.route_accidentelle.maree, '".$_SESSION['filter']['f_s_maree']."'),0) AS score"
            . " FROM trawlers.route_accidentelle "
            . "LEFT JOIN vms.navire ON trawlers.route_accidentelle.id_navire = vms.navire.id "
            . "LEFT JOIN trawlers.t_co ON trawlers.t_co.id = trawlers.route_accidentelle.t_co "
            . "LEFT JOIN trawlers.t_fleet ON trawlers.t_fleet.id = trawlers.route_accidentelle.t_fleet "
            . "WHERE trawlers.route_accidentelle.t_fleet=".$_SESSION['filter']['f_t_fleet']." "
            . "OR trawlers.route_accidentelle.t_fleet IS NULL "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT count(route_accidentelle.id) FROM trawlers.route_accidentelle "
            . "WHERE trawlers.route_accidentelle.t_fleet=".$_SESSION['filter']['f_t_fleet']." "
            . "OR trawlers.route_accidentelle.t_fleet IS NULL ";


            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT route_accidentelle.id, route_accidentelle.datetime::date, route_accidentelle.username, t_fleet.fleet, navire, maree, date, t_co.co, lance, time, st_x(location), st_y(location), id_navire "
            . " FROM trawlers.route_accidentelle "
            . "LEFT JOIN vms.navire ON trawlers.route_accidentelle.id_navire = vms.navire.id "
            . "LEFT JOIN trawlers.t_co ON trawlers.t_co.id = trawlers.route_accidentelle.t_co "
            . "LEFT JOIN trawlers.t_fleet ON trawlers.t_fleet.id = trawlers.route_accidentelle.t_fleet "
            . "WHERE trawlers.route_accidentelle.t_fleet=".$_SESSION['filter']['f_t_fleet']." "
            . "OR trawlers.route_accidentelle.t_fleet IS NULL "
            . "AND id_navire=".$_SESSION['filter']['f_id_navire']." "
            . "ORDER BY route_accidentelle.datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(route_accidentelle.id) FROM trawlers.route_accidentelle";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT route_accidentelle.id, route_accidentelle.datetime::date, route_accidentelle.username, t_fleet.fleet, navire, maree, date, t_co.co, lance, time, st_x(location), st_y(location), id_navire  "
        . " FROM trawlers.route_accidentelle "
        . "LEFT JOIN vms.navire ON trawlers.route_accidentelle.id_navire = vms.navire.id "
        . "LEFT JOIN trawlers.t_co ON trawlers.t_co.id = trawlers.route_accidentelle.t_co "
        . "LEFT JOIN trawlers.t_fleet ON trawlers.t_fleet.id = trawlers.route_accidentelle.t_fleet "
        . "ORDER BY route_accidentelle.datetime DESC OFFSET $start LIMIT $step";
    }

    $r_query = pg_query($query);

    print $query;

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_trawlers_route_accidentelle.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_trawlers_route_accidentelle.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        if ($results[10] > 0) {$lon = round($results[10],4).'&deg; E';} else {$lon = -1*(round($results[10],4)).'&deg; O';}
        if ($results[11] > 0) {$lat = round($results[11],4).'&deg; N';} else {$lat = -1*(round($results[11],4)).'&deg; S';}

        print "<td>$results[1]<br/>$results[2]</td><td><a href=\"./view_navire.php?source=vms&id=$results[12]\">$results[4]</a></td><td>$results[5]</td><td>$results[3]</td><td>$results[6]</td>"
        . "<td>$results[7]</td><td>$results[8]</td><td>$results[9]</td><td><a href=\"view_point.php?X=$results[10]&Y=$results[11]\">$lat $lon</a></td></tr>";
    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_trawlers_route_accidentelle.php?source=trawlers&table=route_accidentelle&action=show&f_id_navire='.$_SESSION['filter']['f_id_navire'].'&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_t_fleet='.$_SESSION['filter']['f_t_fleet']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //    t_fleet integer,
   //navire, maree, date date, t_co, lance, heure


    //find record info by ID
    $q_id = "SELECT *, st_y(location), st_x(location) FROM trawlers.route_accidentelle WHERE id = '$id' ORDER BY datetime DESC";

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    $lat = $results[11];
    $lon = $results[12];

    if ($lat > 0) {$NS = 'N';} else {$lat = -1*$lat; $NS = 'S';}
    if ($lon > 0) {$EO = 'E';} else {$lon = -1*$lon; $EO = 'O';}

    $lat_deg_d = $lat;
    $lon_deg_d = $lon;

    $lat_deg_dm = intval($lat_deg_d);
    $lat_min_dm = ($lat_deg_d - intval($lat_deg_d))*60;
    $lon_deg_dm = intval($lon_deg_d);
    $lon_min_dm = ($lon_deg_d - intval($lon_deg_d))*60;

    $lat_deg_dms = $lat_deg_dm;
    $lat_min_dms = intval($lat_min_dm);
    $lat_sec_dms = ($lat_min_dm - intval($lat_min_dm))*60;
    $lon_deg_dms = $lon_deg_dm;
    $lon_min_dms = intval($lon_min_dm);
    $lon_sec_dms = ($lon_min_dm - intval($lon_min_dm))*60;

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Navire</b>
    <br/>
    <select name="id_navire">
    <?php
        $result = pg_query("SELECT id, navire FROM vms.navire WHERE navire NOT LIKE 'M\_%' ORDER BY navire");
        while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[4]) {
                print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
            } else {
                print "<option value=\"$row[0]\">$row[1]</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Maree</b>
    <br/>
    <input type="text" size="20" name="maree" value="<?php echo $results[5]; ?>"/>
    <br/>
    <br/>
    <b>Flottille</b>
    <br/>
    <select name="t_fleet">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, fleet FROM trawlers.t_fleet ORDER BY t_fleet");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[3]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Date</b>
    <br/>
    <input type="date" size="20" name="date" value="<?php echo $results[6]; ?>"/>
    <br/>
    <br/>
    <b>Capture/Observe</b>
    <br/>
    <select name="t_co">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, co FROM trawlers.t_co ORDER BY t_co");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[8]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Lanc&eacute;</b>
    <br/>
    <input type="text" size="10" name="lance" value="<?php echo $results[9]; ?>"/>
    <br/>
    <br/>
    <b>Heure</b>
    <br/>
    <input type="time" size="20" name="time" value="<?php echo $results[7]; ?>"/>
    <br/>
    <br/>
    <b>Point GPS</b>
    <br/>
    <input type="radio" name="t_coord" value="DDMMSS" onchange="show('','DDMMSS');hide('DDMM');hide('DD')">DD&deg;MM&prime;SS.SS&prime;&prime;
    <input type="radio" name="t_coord" value="DDMM" checked onchange="show('','DDMM');hide('DDMMSS');hide('DD')">DD&deg;MM.MM&prime;
    <input type="radio" name="t_coord" value="DD" onchange="show('','DD');hide('DDMM');hide('DDMMSS')">DD.DD&deg;
    <br/>
    <br/>

    <div class="DDMMSS" style="display:none">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_dms" value="<?php print $lat_deg_dms;?>"/>&deg;
    <input type="text" size="5" name="lat_min_dms" value="<?php print $lat_min_dms;?>"/>&prime;
    <input type="text" size="5" name="lat_sec_dms" value="<?php print $lat_sec_dms;?>"/>&prime;&prime;
    </div>

    <div class="DDMM">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_dm" value="<?php print $lat_deg_dm;?>"/>&deg;
    <input type="text" size="5" name="lat_min_dm" value="<?php print $lat_min_dm;?>"/>&prime;
    </div>

    <div class="DD" style="display:none">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_d"  value="<?php print $lat_deg_d;?>"/>&deg;
    </div>

    <select name="NS">
        <option value="N" <?php if($NS == 'N') {print 'selected';} ?>>N</option>
        <option value="S" <?php if($NS == 'S') {print 'selected';} ?>>S</option>
    </select>

    <div class="DDMMSS" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dms" value="<?php print $lon_deg_dms;?>"/>&deg;
    <input type="text" size="5" name="lon_min_dms" value="<?php print $lon_min_dms;?>"/>&prime;
    <input type="text" size="5" name="lon_sec_dms" value="<?php print $lon_sec_dms;?>"/>&prime;&prime;
    </div>

    <div class="DDMM">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dm" value="<?php print $lon_deg_dm;?>"/>&deg;
    <input type="text" size="5" name="lon_min_dm" value="<?php print $lon_min_dm;?>"/>&prime;
    </div>

    <div class="DD" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_d"  value="<?php print $lon_deg_d;?>"/>&deg;
    </div>

    <select name="EO">
        <option value="E" <?php if($EO == 'E') {print 'selected';} ?> >E</option>
        <option value="O" <?php if($EO == 'O') {print 'selected';} ?>>O</option>
    </select>

    <br/>
    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM trawlers.route_accidentelle WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_route_accidentelle.php?source=$source&table=route_accidentelle&action=show");
    }
    $controllo = 1;

}


if ($_POST['submit'] == "Enregistrer") {

  $lon_deg_dms = htmlspecialchars($_POST['lon_deg_dms'],ENT_QUOTES);
  $lat_deg_dms = htmlspecialchars($_POST['lat_deg_dms'],ENT_QUOTES);
  $lon_min_dms = htmlspecialchars($_POST['lon_min_dms'],ENT_QUOTES);
  $lat_min_dms = htmlspecialchars($_POST['lat_min_dms'],ENT_QUOTES);
  $lon_sec_dms = htmlspecialchars($_POST['lon_sec_dms'],ENT_QUOTES);
  $lat_sec_dms = htmlspecialchars($_POST['lat_sec_dms'],ENT_QUOTES);
  $lon_deg_dm = htmlspecialchars($_POST['lon_deg_dm'],ENT_QUOTES);
  $lat_deg_dm = htmlspecialchars($_POST['lat_deg_dm'],ENT_QUOTES);
  $lon_min_dm = htmlspecialchars($_POST['lon_min_dm'],ENT_QUOTES);
  $lat_min_dm = htmlspecialchars($_POST['lat_min_dm'],ENT_QUOTES);
  $lon_deg_d = htmlspecialchars($_POST['lon_deg_d'],ENT_QUOTES);
  $lat_deg_d = htmlspecialchars($_POST['lat_deg_d'],ENT_QUOTES);

  if($_POST['t_coord'] == 'DDMMSS') {
    $lon = $lon_deg_dms+$lon_min_dms/60+$lon_sec_dms/3600;
    $lat = $lat_deg_dms+$lat_min_dms/60+$lon_sec_dms/3600;
  } elseif ($_POST['t_coord'] == 'DDMM') {
    $lon = $lon_deg_dm+$lon_min_dm/60;
    $lat = $lat_deg_dm+$lat_min_dm/60;
  } elseif ($_POST['t_coord'] == 'DD') {
    $lon = $lon_deg_d;
    $lat = $lat_deg_d;
  }

  if ($lon == "" OR $lat == "") {
      $point = "NULL";
  } else {
      if ($_POST['NS'] == 'S') {$lat = -1*$lat;}
      if ($_POST['EO'] == 'O') {$lon = -1*$lon;}
      $point = "'POINT($lon $lat)'";
  }


    $id_navire = $_POST['id_navire'];
    $maree = $_POST['maree'];
    $t_fleet = $_POST['t_fleet'];
    $date = $_POST['date'];
    $lance = $_POST['lance'];
    $time = $_POST['time'];
    $t_co = $_POST['t_co'];

    if ($_POST['new_old']) {

        //navire, maree, t_fleet, date, lance, time, depth_d, depth_f, speed, reject, sample, comment

        $query = "INSERT INTO trawlers.route_accidentelle "
                . "(username, datetime, id_navire, maree, t_fleet, date, lance, time, t_co, location) "
                . "VALUES ('$username', now(), '$id_navire', '$maree', '$t_fleet', '$date', '$lance', '$time', '$t_co', ST_GeomFromText($point,4326))";

    } else {

        $query = "UPDATE trawlers.route_accidentelle SET "
                . "username = '$username', datetime = now(), "
                . "id_navire = '".$_POST['id_navire']."', maree = '".$_POST['maree']."', "
                . "t_fleet = '".$_POST['t_fleet']."', date = '".$_POST['date']."', lance = '".$_POST['lance']."', "
                . "time = '".$_POST['time']."', t_co = '".$_POST['t_co']."', "
                . "location = ST_GeomFromText($point,4326)"
                . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_route_accidentelle.php?source=$source&table=route_accidentelle&action=show");
    }
}

foot();
