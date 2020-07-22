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

    $query_t = "SELECT vms.navire.navire, date_c, ST_Y(location), ST_X(location) FROM thon.lance  "
    . "LEFT JOIN vms.navire ON thon.lance.id_navire = vms.navire.id ";
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
    <form method="post" action="<?php echo $self;?>?source=thon&table=lance&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border">
    <tr>
    <td><b>Navire</b></td>
    <td><b>Ann&eacute;e lance</b></td>
    </tr>
    <tr>
    <td>
    <select name="f_s_navire">
        <option value="vms.navire.navire">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT vms.navire.navire FROM thon.lance "
                . "LEFT JOIN vms.navire ON thon.lance.id_navire = vms.navire.id "
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
        <option value="EXTRACT(year FROM lance.date_c)">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT EXTRACT(year FROM lance.date_c) as year FROM thon.lance ORDER BY year");
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
    <td><b>Date et heure lance</b></td>
    <td><b>EEZ</b></td>
    <td><b>Portant</b></td>
    <td><b>Banc libre</b></td>
    <td><b>Code balise</b></td>
    <td><b>Temp Eau</b></td>
    <td><b>Direction et Vitesse vent</b></td>
    <td><b>Vitesse courant</b></td>
    <td><b>Captures</b></td>
    <td><b>Remarque</b></td>
    <td><b>Point GPS</b></td>
    </tr>

    <?php

    // fetch data

    if ($_SESSION['filter']['f_s_navire'] != "" OR $_SESSION['filter']['f_s_year'] != "" ) {

        # id_maree, date_c, heure_c, lance, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment ,

        $_SESSION['start'] = 0;

        $query = "SELECT count(lance.id) FROM thon.lance "
        . "LEFT JOIN vms.navire ON thon.lance.id_navire = vms.navire.id "
        . "WHERE EXTRACT(year FROM lance.date_c) = ".$_SESSION['filter']['f_s_year']." "
        . "AND vms.navire.navire = ".$_SESSION['filter']['f_s_navire']." ";

        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT lance.id, lance.username, lance.datetime::date, vms.navire.navire, EXTRACT(year FROM date_c), date_c, heure_c, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment, st_x(location), st_y(location)"
        . " FROM thon.lance "
        . "LEFT JOIN vms.navire ON thon.lance.id_navire = vms.navire.id "
        . "WHERE EXTRACT(year FROM lance.date_c) =".$_SESSION['filter']['f_s_year']." "
        . "AND vms.navire.navire =".$_SESSION['filter']['f_s_navire']." "
        . "ORDER BY lance.datetime DESC OFFSET $start LIMIT $step";

    } else {
        $query = "SELECT count(lance.id) FROM thon.lance";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT lance.id, lance.username, lance.datetime::date, vms.navire.navire, EXTRACT(year FROM date_c), date_c, heure_c, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment, st_x(location), st_y(location) "
        . " FROM thon.lance "
        . "LEFT JOIN vms.navire ON thon.lance.id_navire = vms.navire.id "
        . "ORDER BY lance.datetime DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        $lon = $results[16];
        $lat = $results[17];

        $lon_deg = intval($lon);
        $lat_deg = intval($lat);

        $lon_min = round(($lon - $lon_deg)*60);
        $lat_min = round(($lat - $lat_deg)*60);

        print "<tr align=\"center\">";

        print "<td>"
        . "<a href=\"./view_thon_lance.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_thon_lance.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td nowrap>$results[3]<br/>$results[4]</td>"
                . "<td nowrap>$results[5]<br/>$results[6]</td><td>$results[7]</td><td>$results[8]</td><td>$results[9]</td><td>$results[10]</td>"
                . "<td>$results[11]</td><td>$results[13]<br/>$results[12]</td><td>$results[14]</td>"
                . "<td>";
                if ($results[8] == 't') {
                  print "<img src=\"./graph_thon_captures.php?id=$results[0]\">";
                }
                if ($results[8] == 'f') {
                  print "<b>Coup Null</b>";
                }
                if ($results[8] == '') {
                  print "Autre activite";
                }
                print "</td><td>$results[15]</td><td nowrap>".abs($lat_deg)."&deg;".abs($lat_min)."&prime; ";
                if($lat_deg >= 0) {print "N";} else {print "S";}

                print "<br/>".abs($lon_deg)."&deg;".abs($lon_min)."&prime; ";
                if($lon_deg >= 0) {print "E";} else {print "W";}

                print "</tr>";

    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_thon_lance.php?source=thon&table=lance&action=show&f_s_navire='.$_SESSION['filter']['f_s_navire'].'&f_s_year='.$_SESSION['filter']['f_s_year']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    // id, datetime, username, id_maree, date_c, heure_c, lance, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment,

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT id, datetime, username, id_navire, date_c, heure_c, lance, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment, st_x(location), st_y(location) FROM thon.lance "
        . "WHERE lance.id = '$id'";

    #print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    $lon = $results[16];
    $lat = $results[17];

    $lat_deg = intval($lat);
    $lat_min = ($lat - intval($lat))*60;

    $lon_deg = intval($lon);
    $lon_min = ($lon - intval($lon))*60;

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <table id='small'>
      <tr>
        <td><b>Navire</b></td><td><b>EEZ</b></td><td><b>Date lance</b></td><td><b>Heure lance</b></td>
        <td><b>Temp eau</b> [C]</td>
        <td><b>Vitesse vent</b> [m/s]</td>
        <td><b>Direction vent</b> [degree]</td>
        <td><b>Vitesse courant</b> [nd]</td>
        </tr>
      <tr>
        <td><select name="id_navire">
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
        </td>
        <td><input type="text" size="6" name="eez" value="<?php echo $results[7];?>" /></td>
        <td><input type="date" size="20" name="date_c" value="<?php echo $results[4]; ?>"/></td>
        <td><input type="time" size="20" name="heure_c" value="<?php echo $results[5];?>" /></td>
        <td><input type="text" size="3" name="water_temp" value="<?php echo $results[11];?>" /></td>
        <td><input type="text" size="3" name="wind_speed" value="<?php echo $results[12];?>" /></td>
        <td><input type="text" size="3" name="wind_dir" value="<?php echo $results[13];?>" /></td>
        <td><input type="text" size="3" name="cur_speed" value="<?php echo $results[14];?>" /></td>
        </tr>

</table>
<table id='small'>
      <tr>
        <td><b>Portant</b></td><td><b>Banc libre</b>
        </td><td><b>Code balise</b></td>
        <td><b>Latitude</b></td><td><b>Longitude</b></td><td><b>Remarques</b></td>
      </tr>
      <tr>
        <td nowrap>
          <input type="radio" name="success" value="TRUE" <?php if($results[8] == 't') {print "checked";} ?>/>Oui
          <input type="radio" name="success" value="FALSE" <?php if($results[8] == 'f') {print "checked";} ?>/>No
        </td>
        <td nowrap>
          <input type="radio" name="banclibre" value="TRUE" <?php if($results[9] == 't') {print "checked";} ?>/>Oui
          <input type="radio" name="banclibre" value="FALSE" <?php if($results[9] == 'f') {print "checked";} ?>/>No<br/>
          <input type="radio" name="banclibre" value="" <?php if($results[9] == '') {print "checked";} ?>/>Pas precis&eacute;
        </td>
        <td><input type="text" size="10" name="balise_id" value="<?php echo $results[10];?>" /></td>
        <td nowrap>
          <input type="text" size="3" name="lat_deg" value="<?php echo abs($lat_deg);?>" />&deg;
          <input type="text" size="3" name="lat_min" value="<?php echo abs($lat_min);?>" />&prime;
          <select name="NS">
            <option value="N" <?php if($lat_deg >= 0){print "selected";} ?>>N</option>
            <option value="S" <?php if($lat_deg < 0){print "selected";} ?>>S</option>
          </select>
          </td>
        <td nowrap>
          <input type="text" size="3" name="lon_deg" value="<?php echo abs($lon_deg);?>" />&deg;
          <input type="text" size="3" name="lon_min" value="<?php echo abs($lon_min);?>" />&prime;
          <select name="EW">
            <option value="E" <?php if($lon_deg >= 0){print "selected";} ?>>E</option>
            <option value="W" <?php if($lon_deg < 0){print "selected";} ?>>W</option>
          </select>
        </td>
        <td><textarea cols="20" rows="3" name="comment"><?php echo $results[15];?></textarea></td>
    </tr>
    </table>
<?php

$q_nl = "SELECT count(*) FROM thon.captures GROUP BY id_species, rejete, id_lance HAVING id_lance = '$results[0]' ORDER BY count(*) DESC";
$r_nl = pg_query($q_nl);
$nl = pg_fetch_row($r_nl)[0];

?>

<table id="small" >
  <tr align="center" >
    <td colspan="2" style="border: 1px solid black"><b>YFT</b></td><td colspan="2" style="border: 1px solid black"><b>BET</b></td><td colspan="2" style="border: 1px solid black"><b>SKJ</b></td>
    <td colspan="3" style="border: 1px solid black"><b>Prise accessoires</b></td><td colspan="3" style="border: 1px solid black"><b>Rejets</b></td>
  </tr>
  <tr align="center">
    <td width="9%">Taille</td><td width="9%">Capture [T]</td>
    <td width="9%">Taille</td><td width="9%">Capture [T]</td>
    <td width="9%">Taille</td><td width="9%">Capture [T]</td>
    <td width="7.6%">FAO</td><td width="7.6%">Taille</td><td width="7.6%">Capture [T]</td>
    <td width="7.6%">FAO</td><td width="7.6%">Taille</td><td width="7.6%">Capture [T]</td>
  </tr>
<?php

$q_id = "SELECT * FROM thon.captures WHERE id_lance = '$id'";

//print $q_id;

$r_id = pg_query($q_id);

$taille_YFT = array();
$poids_YFT = array();
$taille_BET = array();
$poids_BET = array();
$taille_SKJ = array();
$poids_SKJ = array();
$FAO_r = array();
$taille_r = array();
$poids_r = array();
$FAO_a = array();
$taille_a = array();
$poids_a = array();

while ($results_p = pg_fetch_row($r_id)) {
  $rejete = $results_p[4];
  $species = $results_p[5];
  $taille = $results_p[6];
  $poids = $results_p[7];

  $q_s = "SELECT FAO FROM fishery.species WHERE id = '$species'";

  $r_s = pg_query($q_s);
  $FAO = pg_fetch_row($r_s)[0];

  if ($rejete != 't') {
    if ($FAO == 'YFT') {
        array_push($taille_YFT,$taille);
        array_push($poids_YFT,$poids);
    } else if ($FAO == 'BET') {
        array_push($taille_BET,$taille);
        array_push($poids_BET,$poids);
    } else if ($FAO == 'SKJ') {
        array_push($taille_SKJ,$taille);
        array_push($poids_SKJ,$poids);
    } else {
      array_push($FAO_a,$FAO);
      array_push($taille_a,$taille);
      array_push($poids_a,$poids);
    }
  } else {
    array_push($FAO_r,$FAO);
    array_push($taille_r,$taille);
    array_push($poids_r,$poids);
  }

}

for ($i=0; $i<$nl; $i++) {

?>

    <tr align="center">
      <td width="9%"><input type="text" size="1" name="taille_YFT[]" value="<?php echo $taille_YFT[$i]; ?>"/></td>
      <td width="9%"><input type="text" size="1" name="poids_YFT[]" value="<?php echo $poids_YFT[$i]; ?>"/></td>
      <td width="9%"><input type="text" size="1" name="taille_BET[]" value="<?php echo $taille_BET[$i]; ?>"/></td>
      <td width="9%"><input type="text" size="1" name="poids_BET[]" value="<?php echo $poids_BET[$i]; ?>"/></td>
      <td width="9%"><input type="text" size="1" name="taille_SKJ[]" value="<?php echo $taille_SKJ[$i]; ?>"/></td>
      <td width="9%"><input type="text" size="1" name="poids_SKJ[]" value="<?php echo $poids_SKJ[$i]; ?>"/></td>

      <td width="7.6%"><input type="text" size="1" name="FAO_a[]" value="<?php echo $FAO_a[$i]; ?>"/></td>
      <td width="7.6%"><input type="text" size="1" name="taille_a[]" value="<?php echo $taille_a[$i]; ?>"/></td>
      <td width="7.6%"><input type="text" size="1" name="poids_a[]" value="<?php echo $poids_a[$i]; ?>"/></td>

      <td width="7.6%"><input type="text" size="1" name="FAO_r[]" value="<?php echo $FAO_r[$i]; ?>"/></td>
      <td width="7.6%"><input type="text" size="1" name="taille_r[]" value="<?php echo $taille_r[$i]; ?>"/></td>
      <td width="7.6%"><input type="text" size="1" name="poids_r[]" value="<?php echo $poids_r[$i]; ?>"/></td>
  </tr>
<?php
}
?>

  <script type='text/javascript'>
  var DivCapture = `<tr class="capture" align="center">
      <td width="9%"><input type="text" size="1" name="taille_YFT[]" /></td>
      <td width="9%"><input type="text" size="1" name="poids_YFT[]" /></td>
      <td width="9%"><input type="text" size="1" name="taille_BET[]" /></td>
      <td width="9%"><input type="text" size="1" name="poids_BET[]" /></td>
      <td width="9%"><input type="text" size="1" name="taille_SKJ[]" /></td>
      <td width="9%"><input type="text" size="1" name="poids_SKJ[]" /></td>

      <td width="7.6%"><input type="text" size="1" name="FAO_a[]" /></td>
      <td width="7.6%"><input type="text" size="1" name="taille_a[]" /></td>
      <td width="7.6%"><input type="text" size="1" name="poids_a[]" /></td>

      <td width="7.6%"><input type="text" size="1" name="FAO_r[]" /></td>
      <td width="7.6%"><input type="text" size="1" name="taille_r[]" /></td>
      <td width="7.6%"><input type="text" size="1" name="poids_r[]" /></td>
    </tr>
  `

  function appendDivCapture() {
   $( ".container" ).append(DivCapture)
  }

  function removeDivCapture() {
   $( ".capture" ).last().remove()
  }

  </script>
  <table class="container">
  </table>
  <button type="button" onclick="appendDivCapture()" style="height:30px; width:30px;"><b>+</b></button>&nbsp;
  <button type="button" onclick="removeDivCapture()" style="height:30px; width:30px;"><b>-</b></button>
  <br/>


  </table>
    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>


    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM thon.lance WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/thon/view_thon_lance.php?source=$source&table=lance&action=show");
    }
    $controllo = 1;
}

if ($_POST['submit'] == "Enregistrer") {

  function query_captures($taille,$poids,$FAO,$id_lance,$rejete) {

    global $username;

    for($i = 0; $i < sizeof($taille); $i++) {
      $taille_s = htmlspecialchars($taille[$i],ENT_QUOTES);
      $poids_s = htmlspecialchars($poids[$i],ENT_QUOTES);

      if ($poids_s != '') {
        if (is_array($FAO)) {
          $q_s = "SELECT id FROM fishery.species WHERE FAO = '$FAO[$i]'";
        } else {
          $q_s = "SELECT id FROM fishery.species WHERE FAO = '$FAO'";
        }

        print $q_s;
        $r_s = pg_query($q_s);
        $species_id = pg_fetch_row($r_s)[0];

        $query = "INSERT INTO thon.captures"
        . "(username, id_lance, id_species, poids, taille, rejete) "
        . "VALUES ('$username', '$id_lance', '$species_id', '$poids_s', '$taille_s', '$rejete');";

        print $query;

        $query = str_replace('\'-- \'', 'NULL', $query);
        $query = str_replace('\'\'', 'NULL', $query);
        //print $query;

        if(!pg_query($query)) {
          echo "<p>".$query,"</p>";
          msg_queryerror();
          foot();
          die();
        }
      }
    }
}


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
    $date_c = htmlspecialchars($_POST['date_c'],ENT_QUOTES);
    $heure_c = htmlspecialchars($_POST['heure_c'],ENT_QUOTES);
    $eez = htmlspecialchars($_POST['eez'],ENT_QUOTES);
    $water_temp = comma2dot($_POST['water_temp']);
    $wind_speed = comma2dot($_POST['wind_speed']);
    $wind_dir = comma2dot($_POST['wind_dir']);
    $cur_speed = comma2dot($_POST['cur_speed']);
    $success = $_POST['success'];
    $banclibre = $_POST['banclibre'];
    $balise_id = $_POST['balise_id'];
    $comment = htmlspecialchars($_POST['comment'],ENT_QUOTES);

    $taille_YFT = $_POST['taille_YFT'];
    $poids_YFT = $_POST['poids_YFT'];
    $taille_BET = $_POST['taille_BET'];
    $poids_BET = $_POST['poids_BET'];
    $taille_SKJ = $_POST['taille_SKJ'];
    $poids_SKJ = $_POST['poids_SKJ'];
    $FAO_a = $_POST['FAO_a'];
    $taille_a = $_POST['taille_a'];
    $poids_a = $_POST['poids_a'];
    $FAO_r = $_POST['FAO_r'];
    $taille_r = $_POST['taille_r'];
    $poids_r = $_POST['poids_r'];


    if ($_POST['new_old']) {
        #navire, country, port_d, port_a, date_d, date_a, ndays, date_c, heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, comment, st_x(location), st_y(location)
        $query = "INSERT INTO thon.lance "
                . "(username, datetime, id_navire, date_c, heure_c, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, comment, location) "
                . "VALUES ('$username', now(), '$id_navire', '$date_c', '$heure_c', '$eez', '$water_temp', '$wind_speed', '$wind_dir', '$cur_speed', '$success', '$banclibre', '$balise_id', '$comment', ST_GeomFromText($point,4326)) RETURNING id";
        $query = str_replace('\'\'', 'NULL', $query);
        $id_lance = pg_fetch_row(pg_query($query))[0];

        //print $query;
        //print $id_lance;

        query_captures($taille_YFT,$poids_YFT,'YFT',$id_lance,'FALSE');
        query_captures($taille_BET,$poids_BET,'BET',$id_lance,'FALSE');
        query_captures($taille_SKJ,$poids_SKJ,'SKJ',$id_lance,'FALSE');
        query_captures($taille_a,$poids_a,$FAO_a,$id_lance,'FALSE');
        query_captures($taille_r,$poids_r,$FAO_r,$id_lance,'TRUE');

    } else {
        $query = "UPDATE thon.lance SET "
            . "username = '$username', datetime = now(), "
            . "id_navire = '".$id_navire."', date_c = '".$date_c."', heure_c = '".$heure_c."', "
            . "eez = '".$eez."', water_temp = '".$water_temp."', "
            . "wind_speed = '".$wind_speed."', wind_dir = '".$wind_dir."', "
            . "cur_speed = '".$cur_speed."', success = '".$_POST['success']."', banclibre = '".$_POST['banclibre']."', "
            . "balise_id = '".$_POST['balise_id']."', comment = '".$comment."', "
            . " location = ST_GeomFromText($point,4326)"
            . " WHERE id = '{".$_POST['id']."}'";

        $query = str_replace('\'\'', 'NULL', $query);

        $id_lance = $_POST['id'];

        print_r($taille_YFT);
        print sizeof($poids_YFT);

        if(!pg_query($query)) {
          echo "<p>".$query,"</p>";
          msg_queryerror();
          foot();
          die();
        }

        $query = "DELETE FROM thon.captures WHERE id_lance = '$id_lance'";
        pg_query($query);

        query_captures($taille_YFT,$poids_YFT,'YFT',$id_lance,'FALSE');
        query_captures($taille_BET,$poids_BET,'BET',$id_lance,'FALSE');
        query_captures($taille_SKJ,$poids_SKJ,'SKJ',$id_lance,'FALSE');
        query_captures($taille_a,$poids_a,$FAO_a,$id_lance,'FALSE');
        query_captures($taille_r,$poids_r,$FAO_r,$id_lance,'TRUE');
    }

        header("Location: ".$_SESSION['http_host']."/industrial/thon/view_thon_lance.php?source=$source&table=lance&action=show");

}

foot();
