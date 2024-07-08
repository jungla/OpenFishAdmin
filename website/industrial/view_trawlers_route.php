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
$_SESSION['filter']['f_s_lance'] = $_POST['f_s_lance'];
$_SESSION['filter']['f_id_navire'] = $_POST['f_id_navire'];

if ($_GET['f_t_fleet'] != "") {$_SESSION['filter']['f_t_fleet'] = $_GET['f_t_fleet'];}
if ($_GET['f_s_maree'] != "") {$_SESSION['filter']['f_s_maree'] = $_GET['f_s_maree'];}
if ($_GET['f_s_lance'] != "") {$_SESSION['filter']['f_s_lance'] = $_GET['f_s_lance'];}
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
    <form method="post" action="<?php echo $self;?>?source=trawlers&table=route&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Navire</b></td><td><b>Maree</b></td><td><b>Lancee</b></td><td><b>Flottille</b></td></tr>
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
    <input type="text" size="10" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <input type="text" size="5" name="f_s_lance" value="<?php echo $_SESSION['filter']['f_s_lance']?>"/>
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

    <table id='small'>
    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Navire</b></td>
    <td><b>Maree</b></td>
    <td><b>Fleet</b></td>
    <td><b>Date</b></td>
    <td><b>Lanc&eacute;</b></td>
    <td><b>Heure debut/fin</b></td>
    <td><b>Prof. debut/fin (m)</b></td>
    <td><b>Vitesse (nd)</b></td>
    <td><b>Rejete (kg)</b></td>
    <td><b>Echantillion (kg)</b></td>
    <td nowrap><b>GPS debut/fin</b></td>
    <td><b>Remarque</b></td>
    </tr>

    <?php

    // fetch data

    #id, datetime, username, carte, id_route, t_site, payment, receipt, date_d, date_f, id_license, carte_saisie ,

    if ($_SESSION['filter']['f_id_navire'] != "" OR $_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_s_lance'] != "" OR $_SESSION['filter']['f_t_fleet'] != "") {

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_lance'] == '') {
          $f_s_lance = 'lance';
        } else {
          $f_s_lance = $_SESSION['filter']['f_s_lance'];
        }

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(route.id) FROM trawlers.route "
            . "WHERE trawlers.route.t_fleet=".$_SESSION['filter']['f_t_fleet']." "
            . "AND trawlers.route.lance = ".$f_s_lance." "
            . "AND id_navire=".$_SESSION['filter']['f_id_navire']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT route.datetime::date, route.username, route.id, navire, maree, t_fleet.fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment,  st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f), "
            . " coalesce(similarity(trawlers.route.maree, '".$_SESSION['filter']['f_s_maree']."'),0) AS score"
            . " FROM trawlers.route "
            . "LEFT JOIN trawlers.t_fleet ON trawlers.t_fleet.id = trawlers.route.t_fleet "
            . "LEFT JOIN vms.navire ON trawlers.route.id_navire = vms.navire.id "
            . "WHERE trawlers.route.t_fleet=".$_SESSION['filter']['f_t_fleet']." "
            . "AND id_navire=".$_SESSION['filter']['f_id_navire']." "
            . "AND trawlers.route.lance = ".$f_s_lance." "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {
            $query = "SELECT count(route.id) FROM trawlers.route "
            . "WHERE trawlers.route.t_fleet=".$_SESSION['filter']['f_t_fleet']." "
            . "AND id_navire=".$_SESSION['filter']['f_id_navire']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT route.datetime::date, route.username, route.id, navire, maree, t_fleet.fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment,  st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f) "
            . " FROM trawlers.route "
            . "LEFT JOIN trawlers.t_fleet ON trawlers.t_fleet.id = trawlers.route.t_fleet "
            . "LEFT JOIN vms.navire ON trawlers.route.id_navire = vms.navire.id "
            . "WHERE trawlers.route.t_fleet=".$_SESSION['filter']['f_t_fleet']." "
            . "AND id_navire=".$_SESSION['filter']['f_id_navire']." "
            . "AND trawlers.route.lance = ".$f_s_lance." "
            . "ORDER BY route.datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(route.id) FROM trawlers.route";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT route.datetime::date, route.username, route.id, navire, maree, t_fleet.fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment,  st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f), id_navire  "
        . " FROM trawlers.route "
        . "LEFT JOIN trawlers.t_fleet ON trawlers.t_fleet.id = trawlers.route.t_fleet "
        . "LEFT JOIN vms.navire ON trawlers.route.id_navire = vms.navire.id "
        . "ORDER BY route.datetime DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_trawlers_route.php?source=$source&table=$table&action=edit&id=$results[2]\">Modifier</a><br/>"
        . "<a href=\"./view_trawlers_route.php?source=$source&table=$table&action=delete&id=$results[2]\">Effacer</a>";
        }
        print "</td>";
        if ($results[16] > 0) {$lon_d = round($results[16],4).'&deg; E';} else {$lon_d = -1*(round($results[16],4)).'&deg; O';}
        if ($results[17] > 0) {$lat_d = round($results[17],4).'&deg; N';} else {$lat_d = -1*(round($results[17],4)).'&deg; S';}
        if ($results[18] > 0) {$lon_f = round($results[18],4).'&deg; E';} else {$lon_f = -1*(round($results[18],4)).'&deg; O';}
        if ($results[19] > 0) {$lat_f = round($results[19],4).'&deg; N';} else {$lat_f = -1*(round($results[19],4)).'&deg; S';}

        print "<td nowrap>$results[0]<br/>$results[1]</td><td nowrap><a href=\"./view_navire.php?source=vms&id=$results[20]\">$results[3]</a></td><td>$results[4]</td><td>$results[5]</td><td nowrap>$results[6]</td>"
        . "<td>$results[7]</td><td>$results[8]<br/>$results[9]</td><td>$results[10]<br/>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td>"
                . "<td><a href=\"view_point.php?X=$results[16]&Y=$results[17]\">$lat_d<br/>$lon_d</a><br/><a href=\"view_point.php?X=$results[18]&Y=$results[19]\">$lat_f<br/>$lon_f</a><br/></td><td>$results[15]</td></tr>";
    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_trawlers_route.php?source=trawlers&table=route&action=show&f_id_navire='.$_SESSION['filter']['f_id_navire'].'&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_s_lance='.$_SESSION['filter']['f_s_lance'].'&f_t_fleet='.$_SESSION['filter']['f_t_fleet']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    // navire, maree, t_fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment


    //find record info by ID
    $q_id = "SELECT *, st_y(location_d), st_x(location_d), st_y(location_f), st_x(location_f) FROM trawlers.route WHERE id = '$id' ORDER BY datetime DESC";
    //print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    $lat_d = $results[18];
    $lon_d = $results[19];

    if ($lat_d > 0) {$NS_d = 'N';} else {$lat_d = -1*$lat_d; $NS_d = 'S';}
    if ($lon_d > 0) {$EO_d = 'E';} else {$lon_d = -1*$lon_d; $EO_d = 'O';}

    $lat_deg_d_d = $lat_d;
    $lon_deg_d_d = $lon_d;

    $lat_deg_dm_d = intval($lat_deg_d_d);
    $lat_min_dm_d = ($lat_deg_d_d - intval($lat_deg_d_d))*60;
    $lon_deg_dm_d = intval($lon_deg_d_d);
    $lon_min_dm_d = ($lon_deg_d_d - intval($lon_deg_d_d))*60;

    $lat_deg_dms_d = $lat_deg_dm_d;
    $lat_min_dms_d = intval($lat_min_dm_d);
    $lat_sec_dms_d = ($lat_min_dm_d - intval($lat_min_dm_d))*60;
    $lon_deg_dms_d = $lon_deg_dm_d;
    $lon_min_dms_d = intval($lon_min_dm_d);
    $lon_sec_dms_d = ($lon_min_dm_d - intval($lon_min_dm_d))*60;

    $lat_f = $results[20];
    $lon_f = $results[21];

    if ($lat_f > 0) {$NS_f = 'N';} else {$lat_f = -1*$lat_f; $NS_f = 'S';}
    if ($lon_f > 0) {$EO_f = 'E';} else {$lon_f = -1*$lon_f; $EO_f = 'O';}

    $lat_deg_d_f = $lat_f;
    $lon_deg_d_f = $lon_f;

    $lat_deg_dm_f = intval($lat_deg_d_f);
    $lat_min_dm_f = ($lat_deg_d_f - intval($lat_deg_d_f))*60;
    $lon_deg_dm_f = intval($lon_deg_d_f);
    $lon_min_dm_f = ($lon_deg_d_f - intval($lon_deg_d_f))*60;

    $lat_deg_dms_f = $lat_deg_dm_f;
    $lat_min_dms_f = intval($lat_min_dm_f);
    $lat_sec_dms_f = ($lat_min_dm_f - intval($lat_min_dm_f))*60;
    $lon_deg_dms_f = $lon_deg_dm_f;
    $lon_min_dms_f = intval($lon_min_dm_f);
    $lon_sec_dms_f = ($lon_min_dm_f - intval($lon_min_dm_f))*60;


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
        if ($row[0] == $results[3]) {
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
    <input type="text" size="20" name="maree" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Flottille</b>
    <br/>
    <select name="t_fleet">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, fleet FROM trawlers.t_fleet ORDER BY t_fleet");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[5]) {
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
    <b>Lanc&eacute;</b>
    <br/>
    <input type="text" size="10" name="lance" value="<?php echo $results[7]; ?>"/>
    <br/>
    <br/>
    <b>Heure debut</b>
    <br/>
    <input type="time" size="20" name="h_d" value="<?php echo $results[8]; ?>"/>
    <br/>
    <br/>
    <b>Heure fin</b>
    <br/>
    <input type="time" size="20" name="h_f" value="<?php echo $results[9]; ?>"/>
    <br/>
    <br/>
    <b>Profondeur debut</b> (m)
    <br/>
    <input type="text" size="20" name="depth_d" value="<?php echo $results[10]; ?>"/>
    <br/>
    <br/>
    <b>Profondeur fin</b> (m)
    <br/>
    <input type="text" size="20" name="depth_f" value="<?php echo $results[11]; ?>"/>
    <br/>
    <br/>
    <b>Vitesse</b> (nd)
    <br/>
    <input type="text" size="30" name="speed" value="<?php echo $results[12];?>" />
    <br/>
    <br/>
    <b>Rejete</b> (kg)
    <br/>
    <input type="text" size="30" name="reject" value="<?php echo $results[13];?>" />
    <br/>
    <br/>
    <b>Echantillion</b> (kg)
    <br/>
    <input type="text" size="30" name="sample" value="<?php echo $results[14];?>" />
    <br/>
    <br/>

    <b>Point debut GPS</b>
    <br/>
    <input type="radio" name="t_coord_d" value="DDMMSS_d" onchange="show('','DDMMSS_d');hide('DDMM_d');hide('DD_d')">DD&deg;MM&prime;SS.SS&prime;&prime;
    <input type="radio" name="t_coord_d" value="DDMM_d" checked onchange="show('','DDMM_d');hide('DDMMSS_d');hide('DD_d')">DD&deg;MM.MM&prime;
    <input type="radio" name="t_coord_d" value="DD_d" onchange="show('','DD_d');hide('DDMM_d');hide('DDMMSS_d')">DD.DD&deg;
    <br/>
    <br/>

    <div class="DDMMSS_d" style="display:none">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_dms_d" value="<?php print $lat_deg_dms_d;?>"/>&deg;
    <input type="text" size="5" name="lat_min_dms_d" value="<?php print $lat_min_dms_d;?>"/>&prime;
    <input type="text" size="5" name="lat_sec_dms_d" value="<?php print $lat_sec_dms_d;?>"/>&prime;&prime;
    </div>

    <div class="DDMM_d">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_dm_d" value="<?php print $lat_deg_dm_d;?>"/>&deg;
    <input type="text" size="5" name="lat_min_dm_d" value="<?php print $lat_min_dm_d;?>"/>&prime;
    </div>

    <div class="DD_d" style="display:none">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_d_d"  value="<?php print $lat_deg_d_d;?>"/>&deg;
    </div>

    <select name="NS_d">
        <option value="N" <?php if($NS_d == 'N') {print 'selected';} ?>>N</option>
        <option value="S" <?php if($NS_d == 'S') {print 'selected';} ?>>S</option>
    </select>

    <div class="DDMMSS_d" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dms_d" value="<?php print $lon_deg_dms_d;?>"/>&deg;
    <input type="text" size="5" name="lon_min_dms_d" value="<?php print $lon_min_dms_d;?>"/>&prime;
    <input type="text" size="5" name="lon_sec_dms_d" value="<?php print $lon_sec_dms_d;?>"/>&prime;&prime;
    </div>

    <div class="DDMM_d">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dm_d" value="<?php print $lon_deg_dm_d;?>"/>&deg;
    <input type="text" size="5" name="lon_min_dm_d" value="<?php print $lon_min_dm_d;?>"/>&prime;
    </div>

    <div class="DD_d" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_d_d"  value="<?php print $lon_deg_d_d;?>"/>&deg;
    </div>

    <select name="EO_d">
        <option value="E" <?php if($EO_d == 'E') {print 'selected';} ?> >E</option>
        <option value="O" <?php if($EO_d == 'O') {print 'selected';} ?>>O</option>
    </select>
    <br/>
    <br/>
    <b>Point fin GPS</b>
    <br/>
    <input type="radio" name="t_coord_f" value="DDMMSS_f" onchange="show('','DDMMSS_f');hide('DDMM_f');hide('DD_f')">DD&deg;MM&prime;SS.SS&prime;&prime;
    <input type="radio" name="t_coord_f" value="DDMM_f" checked onchange="show('','DDMM_f');hide('DDMMSS_f');hide('DD_f')">DD&deg;MM.MM&prime;
    <input type="radio" name="t_coord_f" value="DD_f" onchange="show('','DD_f');hide('DDMM_f');hide('DDMMSS_f')">DD.DD&deg;
    <br/>
    <br/>

    <div class="DDMMSS_f" style="display:none">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_dms_f" value="<?php print $lat_deg_dms_f;?>"/>&deg;
    <input type="text" size="5" name="lat_min_dms_f" value="<?php print $lat_min_dms_f;?>"/>&prime;
    <input type="text" size="5" name="lat_sec_dms_f" value="<?php print $lat_sec_dms_f;?>"/>&prime;&prime;
    </div>

    <div class="DDMM_f">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_dm_f" value="<?php print $lat_deg_dm_f;?>"/>&deg;
    <input type="text" size="5" name="lat_min_dm_f" value="<?php print $lat_min_dm_f;?>"/>&prime;
    </div>

    <div class="DD_f" style="display:none">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_d_f"  value="<?php print $lat_deg_d_f;?>"/>&deg;
    </div>

    <select name="NS_f">
        <option value="N" <?php if($NS_f == 'N') {print 'selected';} ?>>N</option>
        <option value="S" <?php if($NS_f == 'S') {print 'selected';} ?>>S</option>
    </select>

    <div class="DDMMSS_f" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dms_f" value="<?php print $lon_deg_dms_f;?>"/>&deg;
    <input type="text" size="5" name="lon_min_dms_f" value="<?php print $lon_min_dms_f;?>"/>&prime;
    <input type="text" size="5" name="lon_sec_dms_f" value="<?php print $lon_sec_dms_f;?>"/>&prime;&prime;
    </div>

    <div class="DDMM_f">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dm_f" value="<?php print $lon_deg_dm_f;?>"/>&deg;
    <input type="text" size="5" name="lon_min_dm_f" value="<?php print $lon_min_dm_f;?>"/>&prime;
    </div>

    <div class="DD_f" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_d_f"  value="<?php print $lon_deg_d_f;?>"/>&deg;
    </div>

    <select name="EO_f">
        <option value="E" <?php if($EO_f == 'E') {print 'selected';} ?> >E</option>
        <option value="O" <?php if($EO_f == 'O') {print 'selected';} ?>>O</option>
    </select>

    <br/>
    <br/>
    <b>Comment</b>
    <br/>
    <input type="text" size="30" name="comment" value="<?php echo $results[15];?>" />
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
    $query = "DELETE FROM trawlers.route WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_route.php?source=$source&table=route&action=show");
    }
    $controllo = 1;

}


if ($_POST['submit'] == "Enregistrer") {

  $lon_deg_dms_d = htmlspecialchars($_POST['lon_deg_dms_d'],ENT_QUOTES);
  $lat_deg_dms_d = htmlspecialchars($_POST['lat_deg_dms_d'],ENT_QUOTES);
  $lon_min_dms_d = htmlspecialchars($_POST['lon_min_dms_d'],ENT_QUOTES);
  $lat_min_dms_d = htmlspecialchars($_POST['lat_min_dms_d'],ENT_QUOTES);
  $lon_sec_dms_d = htmlspecialchars($_POST['lon_sec_dms_d'],ENT_QUOTES);
  $lat_sec_dms_d = htmlspecialchars($_POST['lat_sec_dms_d'],ENT_QUOTES);
  $lon_deg_dm_d = htmlspecialchars($_POST['lon_deg_dm_d'],ENT_QUOTES);
  $lat_deg_dm_d = htmlspecialchars($_POST['lat_deg_dm_d'],ENT_QUOTES);
  $lon_min_dm_d = htmlspecialchars($_POST['lon_min_dm_d'],ENT_QUOTES);
  $lat_min_dm_d = htmlspecialchars($_POST['lat_min_dm_d'],ENT_QUOTES);
  $lon_deg_d_d = htmlspecialchars($_POST['lon_deg_d_d'],ENT_QUOTES);
  $lat_deg_d_d = htmlspecialchars($_POST['lat_deg_d_d'],ENT_QUOTES);

  if($_POST['t_coord_d'] == 'DDMMSS_d') {
    $lon = $lon_deg_dms_d+$lon_min_dms_d/60+$lon_sec_dms_d/3600;
    $lat = $lat_deg_dms_d+$lat_min_dms_d/60+$lon_sec_dms_d/3600;
  } elseif ($_POST['t_coord_d'] == 'DDMM_d') {
    $lon = $lon_deg_dm_d+$lon_min_dm_d/60;
    $lat = $lat_deg_dm_d+$lat_min_dm_d/60;
  } elseif ($_POST['t_coord_d'] == 'DD_d') {
    $lon = $lon_deg_d_d;
    $lat = $lat_deg_d_d;
  }

  if ($lon == "" OR $lat == "") {
      $point = "NULL";
  } else {
      if ($_POST['NS_d'] == 'S') {$lat = -1*$lat;}
      if ($_POST['EO_d'] == 'O') {$lon = -1*$lon;}
      $point_d = "'POINT($lon $lat)'";
  }

  $lon_deg_dms_f = htmlspecialchars($_POST['lon_deg_dms_f'],ENT_QUOTES);
  $lat_deg_dms_f = htmlspecialchars($_POST['lat_deg_dms_f'],ENT_QUOTES);
  $lon_min_dms_f = htmlspecialchars($_POST['lon_min_dms_f'],ENT_QUOTES);
  $lat_min_dms_f = htmlspecialchars($_POST['lat_min_dms_f'],ENT_QUOTES);
  $lon_sec_dms_f = htmlspecialchars($_POST['lon_sec_dms_f'],ENT_QUOTES);
  $lat_sec_dms_f = htmlspecialchars($_POST['lat_sec_dms_f'],ENT_QUOTES);
  $lon_deg_dm_f = htmlspecialchars($_POST['lon_deg_dm_f'],ENT_QUOTES);
  $lat_deg_dm_f = htmlspecialchars($_POST['lat_deg_dm_f'],ENT_QUOTES);
  $lon_min_dm_f = htmlspecialchars($_POST['lon_min_dm_f'],ENT_QUOTES);
  $lat_min_dm_f = htmlspecialchars($_POST['lat_min_dm_f'],ENT_QUOTES);
  $lon_deg_d_f = htmlspecialchars($_POST['lon_deg_d_f'],ENT_QUOTES);
  $lat_deg_d_f = htmlspecialchars($_POST['lat_deg_d_f'],ENT_QUOTES);

  if($_POST['t_coord_f'] == 'DDMMSS_f') {
    $lon = $lon_deg_dms_f+$lon_min_dms_f/60+$lon_sec_dms_f/3600;
    $lat = $lat_deg_dms_f+$lat_min_dms_f/60+$lon_sec_dms_f/3600;
  } elseif ($_POST['t_coord_f'] == 'DDMM_f') {
    $lon = $lon_deg_dm_f+$lon_min_dm_f/60;
    $lat = $lat_deg_dm_f+$lat_min_dm_f/60;
  } elseif ($_POST['t_coord_f'] == 'DD_f') {
    $lon = $lon_deg_d_f;
    $lat = $lat_deg_d_f;
  }

  if ($lon == "" OR $lat == "") {
      $point = "NULL";
  } else {
      if ($_POST['NS_f'] == 'S') {$lat = -1*$lat;}
      if ($_POST['EO_f'] == 'O') {$lon = -1*$lon;}
      $point_f = "'POINT($lon $lat)'";
  }


    $id_navire = $_POST['id_navire'];
    $maree = $_POST['maree'];
    $t_fleet = $_POST['t_fleet'];
    $date = $_POST['date'];
    $lance = $_POST['lance'];
    $h_d = $_POST['h_d'];
    $h_f = $_POST['h_f'];
    $depth_d = $_POST['depth_d'];
    $depth_f = $_POST['depth_f'];
    $speed = $_POST['speed'];
    $reject = $_POST['reject'];
    $sample = $_POST['sample'];
    $comment = htmlspecialchars($_POST['comment'],ENT_QUOTES);

    if ($_POST['new_old']) {

        //navire, maree, t_fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment

        $query = "INSERT INTO trawlers.route "
                . "(username, datetime, id_navire, maree, t_fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment, location_d, location_f) "
                . "VALUES ('$username', now(), '$navire', '$maree', '$t_fleet', '$date', '$lance', '$h_d', '$h_f', '$depth_d', '$depth_f', '$speed', '$reject', '$sample', '$comment', ST_GeomFromText($point_d,4326), ST_GeomFromText($point_f,4326))";

    } else {

        $query = "UPDATE trawlers.route SET "
                . "username = '$username', datetime = now(), "
                . "id_navire = '".$_POST['id_navire']."', maree = '".$_POST['maree']."', "
                . "t_fleet = '".$_POST['t_fleet']."', date = '".$_POST['date']."', lance = '".$_POST['lance']."', "
                . "h_d = '".$_POST['h_d']."', h_f = '".$_POST['h_f']."', depth_d = '".$_POST['depth_d']."', "
                . "depth_f = '".$_POST['depth_f']."', speed = '".$_POST['speed']."', reject = '".$_POST['reject']."', sample = '".$_POST['sample']."', "
                . "comment = '".$_POST['comment']."', location_d = ST_GeomFromText($point_d,4326), location_f = ST_GeomFromText($point_f,4326)"
                . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_route.php?source=$source&table=route&action=show");
    }
}

foot();
