<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'thon';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_s_navire'] = $_POST['f_s_navire'];
$_SESSION['filter']['f_s_year'] = $_POST['f_s_year'];

if ($_GET['f_s_navire'] != "") {$_SESSION['filter']['f_s_navire'] = $_GET['f_s_navire'];}
if ($_GET['f_s_year'] != "") {$_SESSION['filter']['f_s_year'] = $_GET['f_s_year'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'map') {

    $query_t = "SELECT vms.navire.navire, date_e, ST_Y(location), ST_X(location) FROM thon.entreesortie  "
    . "LEFT JOIN vms.navire ON thon.entreesortie.id_navire = vms.navire.id ";
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

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=thon&table=entreesortie&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border">
    <tr>
    <td><b>Navire</b></td>
    <td><b>Ann&eacute;e entreesortie</b></td>
    </tr>
    <tr>
    <td>
    <select name="f_s_navire">
        <option value="vms.navire.navire">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT vms.navire.navire FROM thon.entreesortie "
                . "LEFT JOIN vms.navire ON thon.entreesortie.id_navire = vms.navire.id "
                . "WHERE navire IS NOT NULL "
                . "ORDER BY vms.navire.navire");
        while($row = pg_fetch_row($result)) {
            if ("'".$row[0]."'" == $_SESSION['filter']['f_s_navire']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">$row[0]</option>";
            } else {
                print "<option value=\"'$row[0]'\">$row[0]</option>";
            }
        }
        ?>
    </select>
    </td>
    <td>
    <select name="f_s_year">
        <option value="EXTRACT(year FROM entreesortie.date_e)">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT EXTRACT(year FROM entreesortie.date_e) as year FROM thon.entreesortie ORDER BY year");
        while($row = pg_fetch_row($result)) {
            if ("'".$row[0]."'" == $_SESSION['filter']['f_s_year']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">$row[0]</option>";
            } else {
                print "<option value=\"'$row[0]'\">$row[0]</option>";
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
    <td><b>Mar&eacute;e</b></td>
    <td><b>Date et heure entree/sortie</b></td>
    <td><b>EEZ</b></td>
    <td><b>Entree/Sortie</b></td>
    <td><b>YFT</b></td>
    <td><b>BET</b></td>
    <td><b>SKJ</b></td>
    <td><b>FRI</b></td>
    <td><b>Remarques</b></td>
    <td><b>Point GPS</b></td>
    </tr>

    <?php

    // fetch data

    if ($_SESSION['filter']['f_s_navire'] != "" OR $_SESSION['filter']['f_s_year'] != "" ) {

        # id_maree, date_e, heure_e, entreesortie, eez, entree, banclibre, YFT, BET, SKJ, FRI,  comment ,

        $_SESSION['start'] = 0;

        $query = "SELECT count(entreesortie.id) FROM thon.entreesortie "
        . "LEFT JOIN vms.navire ON thon.entreesortie.id_navire = vms.navire.id "
        . "WHERE EXTRACT(year FROM entreesortie.date_e) = ".$_SESSION['filter']['f_s_year']." "
        . "AND vms.navire.navire = ".$_SESSION['filter']['f_s_navire']." ";

        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT entreesortie.id, entreesortie.username, entreesortie.datetime::date, vms.navire.navire, EXTRACT(year FROM date_e), date_e, heure_e, eez, entree, YFT, BET, SKJ, FRI,  remarques, st_x(location), st_y(location) "
        . " FROM thon.entreesortie "
        . "LEFT JOIN vms.navire ON thon.entreesortie.id_navire = vms.navire.id "
        . "WHERE EXTRACT(year FROM entreesortie.date_e) =".$_SESSION['filter']['f_s_year']." "
        . "AND vms.navire.navire =".$_SESSION['filter']['f_s_navire']." "
        . "ORDER BY entreesortie.datetime DESC OFFSET $start LIMIT $step";

    } else {
        $query = "SELECT count(entreesortie.id) FROM thon.entreesortie";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT entreesortie.id, entreesortie.username, entreesortie.datetime::date, vms.navire.navire, EXTRACT(year FROM date_e), date_e, heure_e, eez, entree, YFT, BET, SKJ, FRI,  remarques, st_x(location), st_y(location) "
        . " FROM thon.entreesortie "
        . "LEFT JOIN vms.navire ON thon.entreesortie.id_navire = vms.navire.id "
        . "ORDER BY entreesortie.datetime DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        $lon = $results[14];
        $lat = $results[15];

        $lon_deg = intval($lon);
        $lat_deg = intval($lat);

        $lon_min = round(($lon - $lon_deg)*60);
        $lat_min = round(($lat - $lat_deg)*60);

        print "<tr align=\"center\">";

        print "<td>"
        . "<a href=\"./view_thon_entreesortie.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_thon_entreesortie.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";

        if ($results[8] == 't') {
          $ES = 'Entree';
        } else {
          $ES = 'Sortie';
        }

        print "<td>$results[1]<br/>$results[2]</td><td nowrap>$results[3]<br/>$results[4]</td>"
                . "<td nowrap>$results[5]<br/>$results[6]</td><td>$results[7]</td><td>$ES</td><td>$results[9]</td><td>$results[10]</td>"
                . "<td>$results[11]</td><td>$results[12]</td><td>$results[13]</td>";


                print "<td nowrap>".abs($lat_deg)."&deg;".abs($lat_min)."&prime; ";
                if($lat_deg >= 0) {print "N";} else {print "S";}

                print "<br/>".abs($lon_deg)."&deg;".abs($lon_min)."&prime; ";
                if($lon_deg >= 0) {print "E";} else {print "W";}

                print "</td></tr>";

    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_thon_entreesortie.php?source=thon&table=entreesortie&action=show&f_s_navire='.$_SESSION['filter']['f_s_navire'].'&f_s_year='.$_SESSION['filter']['f_s_year']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    // id, datetime, username, id_maree, date_e, heure_e, entreesortie, eez, entree, banclibre, YFT, BET, SKJ, FRI,  remarques,

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT entreesortie.id, entreesortie.username, entreesortie.datetime::date, id_navire, date_e, heure_e, eez, entree, YFT, BET, SKJ, FRI,  remarques, st_x(location), st_y(location) "
        . "FROM thon.entreesortie WHERE entreesortie.id = '$id'";

    //print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    $lon = $results[13];
    $lat = $results[14];

    $lat_deg = intval($lat);
    $lat_min = ($lat - intval($lat))*60;

    $lon_deg = intval($lon);
    $lon_min = ($lon - intval($lon))*60;

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Navire</b><br/>
    <select name="id_navire">
            <?php
            $result = pg_query("SELECT DISTINCT id, navire FROM vms.navire "
                    . "ORDER BY navire");
            while($row = pg_fetch_row($result)) {
                if ($row[0] == $results[3]) {
                    print "<option value=\"'$row[0]'\" selected=\"selected\">$row[1]</option>";
                } else {
                    print "<option value=\"'$row[0]'\">$row[1]</option>";
                }
            }
            ?>
    </select>
    <br/>
    <br/>
    <b>EEZ</b><br/>
    <input type="text" size="6" name="eez" value="<?php echo $results[6];?>" />
    <br/>
    <br/>
    <b>Date entree/sortie</b><br/>
    <input type="date" size="20" name="date_e" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Heure entree/sortie</b><br/>
    <input type="time" size="20" name="heure_e" value="<?php echo $results[5];?>" />
    <br/>
    <br/>
    <b>Entree/Sortie</b><br/>
    <input type="radio" name="entree" value="TRUE" <?php if($results[7] == 't') {print "checked";} ?>/>Entree
    <input type="radio" name="entree" value="FALSE" <?php if($results[7] == 'f') {print "checked";} ?>/>Sortie
    <br/>
    <br/>
    <table>
      <tr><td><b>YFT</b> [ton]</td><td><b>BET</b> [ton]</td><td><b>SKJ</b> [ton]</td><td><b>FRI</b> [ton]</td></tr>
      <tr>
        <td><input type="text" size="3" name="YFT" value="<?php echo $results[8];?>" /></td>
        <td><input type="text" size="3" name="BET" value="<?php echo $results[9];?>" /></td>
        <td><input type="text" size="3" name="SKJ" value="<?php echo $results[10];?>" /></td>
        <td><input type="text" size="3" name="FRI" value="<?php echo $results[11];?>" /></td>
      </tr>
    </table>
    <br/>

        <b>Latitude</b><br/>
        <input type="text" size="3" name="lat_deg" value="<?php echo abs($lat_deg);?>" />&deg;
        <input type="text" size="3" name="lat_min" value="<?php echo abs($lat_min);?>" />&prime;
        <select name="NS">
          <option value="N" <?php if($lat_deg >= 0){print "selected";} ?>>N</option>
          <option value="S" <?php if($lat_deg < 0){print "selected";} ?>>S</option>
        </select>
<br/>
<br/>
        <b>Longitude</b><br/>
        <input type="text" size="3" name="lon_deg" value="<?php echo abs($lon_deg);?>" />&deg;
        <input type="text" size="3" name="lon_min" value="<?php echo abs($lon_min);?>" />&prime;
        <select name="EW">
          <option value="E" <?php if($lon_deg >= 0){print "selected";} ?>>E</option>
          <option value="W" <?php if($lon_deg < 0){print "selected";} ?>>W</option>
        </select>
        <br/>
        <br/>
      <b>Remarques</b><br/>
      <textarea cols="20" rows="3" name="remarques"><?php echo $results[12];?></textarea>

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
    $query = "DELETE FROM thon.entreesortie WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/thon/view_thon_entreesortie.php?source=$source&table=entreesortie&action=show");
    }
    $controllo = 1;
}

if ($_POST['submit'] == "Enregistrer") {

    $lon_deg = htmlspecialchars($_POST['lon_deg'],ENT_QUOTES);
    $lat_deg = htmlspecialchars($_POST['lat_deg'],ENT_QUOTES);
    $lon_min = htmlspecialchars($_POST['lon_min'],ENT_QUOTES);
    $lat_min = htmlspecialchars($_POST['lat_min'],ENT_QUOTES);

    $lon = $lon_deg+$lon_min/60;
    $lat = $lat_deg+$lat_min/60;

    if ($lon == "" OR $lat == "") {
        $point = "NULL";
    } else {
        if ($_POST['NS'] == 'S') {$lat = -1*$lat;}
        if ($_POST['EW'] == 'W') {$lon = -1*$lon;}
        $point = "'POINT($lon $lat)'";
    }

    $id_navire = str_replace("'","",$_POST['id_navire']);
    $date_e = htmlspecialchars($_POST['date_e'],ENT_QUOTES);
    $heure_e = htmlspecialchars($_POST['heure_e'],ENT_QUOTES);
    $eez = htmlspecialchars($_POST['eez'],ENT_QUOTES);
    $YFT = htmlspecialchars(comma2dot($_POST['YFT'],ENT_QUOTES));
    $BET = htmlspecialchars(comma2dot($_POST['BET'],ENT_QUOTES));
    $SKJ = htmlspecialchars(comma2dot($_POST['SKJ'],ENT_QUOTES));
    $FRI = htmlspecialchars(comma2dot($_POST['FRI'],ENT_QUOTES));

    $entree = $_POST['entree'];
    $remarques = htmlspecialchars($_POST['remarques'],ENT_QUOTES);

    if ($_POST['new_old']) {
        #navire, country, port_d, port_a, date_d, date_a, ndays, date_e, heure_e, entreesortie, eez, BET, SKJ, FRI,  entree, banclibre, YFT, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, remarques, st_x(location), st_y(location)
        $query = "INSERT INTO thon.entreesortie "
                . "(username, datetime, id_navire, date_e, heure_e, eez, YFT, BET, SKJ, FRI, entree, remarques, location) "
                . "VALUES ('$username', now(), '$id_navire', '$date_e', '$heure_e', '$eez', '$YFT', '$BET', '$SKJ', '$FRI', '$entree', '$remarques', ST_GeomFromText($point,4326)) RETURNING id";
        $query = str_replace('\'\'', 'NULL', $query);
        $id_entreesortie = pg_fetch_row(pg_query($query))[0];

        //print $query;
        //print $id_entreesortie;

    } else {
        $query = "UPDATE thon.entreesortie SET "
            . "username = '$username', datetime = now(), "
            . "id_navire = '".$id_navire."', date_e = '".$date_e."', heure_e = '".$heure_e."', "
            . "eez = '".$eez."', BET = '".$BET."', YFT = '".$YFT."', "
            . "SKJ = '".$SKJ."', FRI = '".$FRI."', "
            . "entree = '".$entree."', remarques = '".$remarques."', "
            . " location = ST_GeomFromText($point,4326)"
            . " WHERE id = '{".$_POST['id']."}'";

        $query = str_replace('\'\'', 'NULL', $query);

    }

    if(!pg_query($query)) {
      echo "<p>".$query,"</p>";
      msg_queryerror();
      foot();
      die();
    } else {
      header("Location: ".$_SESSION['http_host']."/industrial/thon/view_thon_entreesortie.php?source=$source&table=entreesortie&action=show");
    }


}

foot();
