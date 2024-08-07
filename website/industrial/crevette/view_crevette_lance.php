<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'crevette';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_id_navire'] = $_POST['f_id_navire'];
$_SESSION['filter']['f_s_year'] = $_POST['f_s_year'];
$_SESSION['filter']['f_lance'] = $_POST['f_lance'];

if ($_GET['f_id_navire'] != "") {$_SESSION['filter']['f_id_navire'] = $_GET['f_id_navire'];}
if ($_GET['f_s_year'] != "") {$_SESSION['filter']['f_s_year'] = $_GET['f_s_year'];}
if ($_GET['f_lance'] != "") {$_SESSION['filter']['f_lance'] = $_GET['f_lance'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'map') {

  $query_t = "SELECT navire, date_l, ST_Y(location_d), ST_X(location_d) FROM crevette.lance LEFT JOIN vms.navire ON vms.navire.id = crevette.lance.id_navire";
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

  print "<h2>".label2name($source)." ".label2name($table)."</h2>";

  if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

  $start = $_SESSION['start'];

  if (!isset($start) OR $start<0) $start = 0;

  $step = 50;

  ?>
  <form method="post" action="<?php echo $self;?>?source=crevette&table=lance&action=show" enctype="multipart/form-data">
    <fieldset>

      <table id="no-border">
        <tr>
          <td><b>Navire</b></td>
          <td><b>Lance</b></td>
          <td><b>Annee</b></td>
        </tr>
        <tr>
          <td>
            <select name="f_id_navire">
              <option value="id_navire" selected="selected">Tous</option>
              <?php
              $result = pg_query("SELECT DISTINCT lance.id_navire, navire FROM crevette.lance LEFT JOIN vms.navire ON vms.navire.id = crevette.lance.id_navire ORDER BY navire");
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
            <select name="f_lance">
              <option value="lance" selected="selected">Tous</option>
              <?php
              $result = pg_query("SELECT DISTINCT lance.lance FROM crevette.lance WHERE lance IS NOT NULL ORDER BY lance");
              while($row = pg_fetch_row($result)) {
                if ($row[0] == $_SESSION['filter']['f_lance']) {
                  print "<option value=\"$row[0]\" selected=\"selected\">$row[0]</option>";
                } else {
                  print "<option value=\"$row[0]\">$row[0]</option>";
                }
              }
              ?>
            </select>

          </td>
          <td>
            <select name="f_s_year">
              <option value="EXTRACT(year FROM lance.date_l)">Tous</option>
              <?php
              $result = pg_query("SELECT DISTINCT EXTRACT(year FROM lance.date_l) as year FROM crevette.lance WHERE lance.date_l IS NOT NULL ORDER BY year");
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

  <table id='small'>

    <tr align="center"><td></td>
      <td><b>Date & Utilisateur</b></td>
      <td><b>Navire</b></td>
      <td><b>Date lance</b></td>
      <td><b>Zone</b></td>
      <td><b>Lance</b></td>
      <td><b>Heure debut/fin</b></td>
      <td><b>Prof. (m) debut/fin</b></td>
      <td><b>Temp. (C)</b></td>
      <td><b>Rejets (kg)</b></td>
      <td><b>Categorie Taille</b></td>
      <td><b>Crevette cass&eacute;e</b></td>
      <td><b>Point GPS debut/fin</b></td>
    </tr>

    <?php

    // fetch data

    if ($_SESSION['filter']['f_id_navire'] != "" OR $_SESSION['filter']['f_lance'] != "" OR $_SESSION['filter']['f_s_year'] != "") {

      # id_maree, date_c, heure_c, lance, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment ,

      $_SESSION['start'] = 0;

      $query = "SELECT count(lance.id) FROM crevette.lance "
      . "WHERE lance.id_navire=".$_SESSION['filter']['f_id_navire']." "
      . "AND EXTRACT(year FROM lance.date_l) = ".$_SESSION['filter']['f_s_year']." "
      . "AND lance.lance=".$_SESSION['filter']['f_lance']." ";

      //print $query;

      $pnum = pg_fetch_row(pg_query($query))[0];

      $query = "SELECT lance.id, lance.datetime::date, lance.username, navire, date_l, t_zone, lance, h_d, h_f, D_d, D_f, T_d, "
      . "rejets, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c_cre, cc_cre, o_cre, v6_cre, "
      . "st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f), id_navire "
      . " FROM crevette.lance "
      . "LEFT JOIN vms.navire ON vms.navire.id = crevette.lance.id_navire "
      . "WHERE lance.id_navire=".$_SESSION['filter']['f_id_navire']." "
      . "AND lance.lance=".$_SESSION['filter']['f_lance']." "
      . "AND EXTRACT(year FROM lance.date_l) = ".$_SESSION['filter']['f_s_year']." "
      . "ORDER BY lance.date_l DESC OFFSET $start LIMIT $step";

      } else {
      $query = "SELECT count(lance.id) FROM crevette.lance";
      $pnum = pg_fetch_row(pg_query($query))[0];

      $query = "SELECT lance.id, lance.datetime::date, lance.username, navire, date_l, t_zone, lance, h_d, h_f, D_d, D_f, T_d, "
      . "rejets, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c_cre, cc_cre, o_cre, v6_cre, "
      . "st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f), id_navire "
      . " FROM crevette.lance "
      . "LEFT JOIN vms.navire ON vms.navire.id = crevette.lance.id_navire "
      . "LEFT JOIN crevette.t_zone ON crevette.t_zone.id = crevette.lance.t_zone "
      . "ORDER BY lance.date_l DESC OFFSET $start LIMIT $step";
      }

      //print $query;

      $r_query = pg_query($query);

      while ($results = pg_fetch_row($r_query)) {

      $lon_d = $results[26];
      $lat_d = $results[27];

      $lon_deg_d = intval($lon_d);
      $lat_deg_d = intval($lat_d);

      $lon_min_d = round(($lon_d - $lon_deg_d)*60);
      $lat_min_d = round(($lat_d - $lat_deg_d)*60);

      $lon_f = $results[28];
      $lat_f = $results[29];

      $lon_deg_f = intval($lon_f);
      $lat_deg_f = intval($lat_f);

      $lon_min_f = round(($lon_f - $lon_deg_f)*60);
      $lat_min_f = round(($lat_f - $lat_deg_f)*60);


      print "<tr align=\"center\">";

      print "<td>"
      . "<a href=\"./view_crevette_lance.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
      . "<a href=\"./view_crevette_lance.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
      . "</td>";

      print "<td>$results[1]<br/>$results[2]</td><td nowrap><a href=\"../view_navire.php?source=vms&id=$results[30]\">$results[3]</a></td>"
      . "<td nowrap>$results[4]</td><td>$results[5]</td><td>$results[6]</td><td>$results[7]<br/>$results[8]</td>"
      . "<td>$results[9]<br/>$results[10]</td><td>$results[11]</td><td>$results[12]</td><td><img src=\"./graph_crevette_lance.php?id=$results[0]\"></td>"
      . "<td>";
      if ($results[22]!='') {
      print "G : $results[22]kg<br/>";
      }
      if ($results[23]!='') {
      print "P : $results[23]kg<br/>";
      }
      if ($results[24]!='') {
      print "O : $results[24]kg<br/>";
      }
      if ($results[25]!='') {
      print "Vrac : $results[25]kg<br/>";
      }

      print "</td>"
      . "<td nowrap>"
      .abs($lat_deg_d)."&deg;".abs($lat_min_d)."&prime; ";
      if($lat_deg_d >= 0) {print "N";} else {print "S";}

      print "<br/>".abs($lon_deg_d)."&deg;".abs($lon_min_d)."&prime; ";
      if($lon_deg_d >= 0) {print "E";} else {print "O";}

      print "<br/>".abs($lat_deg_f)."&deg;".abs($lat_min_f)."&prime; ";
      if($lat_deg_f >= 0) {print "N";} else {print "S";}

      print "<br/>".abs($lon_deg_f)."&deg;".abs($lon_min_f)."&prime; ";
      if($lon_deg_f >= 0) {print "E";} else {print "O";}

      print "</td></tr>";

      }
      print "</tr>";
      print "</table>";
      pages($start,$step,$pnum,'./view_crevette_lance.php?source=crevette&table=lance&action=show&f_id_navire='.$_SESSION['filter']['f_id_navire'].'&f_lance='.$_SESSION['filter']['f_lance'].'&f_s_year='.$_SESSION['filter']['f_s_year']);

      $controllo = 1;

      } else if ($_GET['action'] == 'edit') {
      print "<h2>".label2name($source)." ".label2name($table)."</h2>";

      // id, datetime, username, id_maree, date_c, heure_c, lance, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment,

      $id = $_GET['id'];

      //find record info by ID
      $q_id = "SELECT *, st_x(location_d), st_y(location_d),st_x(location_f), st_y(location_f) FROM crevette.lance WHERE lance.id = '$id'";

      $r_id = pg_query($q_id);
      $results = pg_fetch_row($r_id);

      $lon_d = $results[28];
      $lat_d = $results[29];

      if ($lat_d >= 0) {$NS_d = 'N';} else {$lat_d = -1*$lat_d; $NS_d = 'S';}
      if ($lon_d >= 0) {$EO_d = 'E';} else {$lon_d = -1*$lon_d; $EO_d = 'O';}

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

      $lon_f = $results[30];
      $lat_f = $results[31];

      if ($lat_f >= 0) {$NS_f = 'N';} else {$lat_f = -1*$lat_f; $NS_f = 'S';}
      if ($lon_f >= 0) {$EO_f = 'E';} else {$lon_f = -1*$lon_f; $EO_f = 'O';}

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
      $result = pg_query("SELECT DISTINCT id_navire, navire FROM crevette.lance "
        . "LEFT JOIN vms.navire ON crevette.lance.id_navire = vms.navire.id "
        . "WHERE navire IS NOT NULL "
        . "ORDER BY navire");
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
        <b>Date lance</b> [mm/jj/aaaa]
        <br/>
        <input type="date" size="10" name="date_l" value="<?php echo $results[4]; ?>"/>
        <br/>
        <br/>
        <b>Zone</b>
        <br/>
        <select name="t_zone">
        <option value="">Aucun</option>
        <?php
        $result = pg_query("SELECT id, zone FROM crevette.t_zone ORDER BY zone");
        while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[5]) {
        print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
        } else {
        print "<option value=\"$row[0]\">$row[1]</option>";
        }
        }
        ?>
        </select>
        <br/>
        <br/>
        <b>Numero lance</b>
        <br/>
        <input type="text" size="5" name="lance" value="<?php echo $results[6];?>" />
        <br/>
        <br/>
        <b>Heure debut lance</b> [hh:mm]
        <br/>
        <input type="time" size="10" name="h_d" value="<?php echo $results[7];?>" />
        <br/>
        <br/>
        <b>Heure fin lance</b> [hh:mm]
        <br/>
        <input type="time" size="10" name="h_f" value="<?php echo $results[8];?>" />
        <br/>
        <br/>
        <b>Profondeur debut lance</b> [m]
        <br/>
        <input type="text" size="5" name="D_d" value="<?php echo $results[9];?>" />
        <br/>
        <br/>
        <b>Profondeur fin lance</b> [m]
        <br/>
        <input type="text" size="5" name="D_f" value="<?php echo $results[10];?>" />
        <br/>
        <br/>
        <b>Temperature eau debut lance</b> [C]
        <br/>
        <input type="text" size="3" name="T_d" value="<?php echo $results[11];?>" />
        <br/>
        <br/>
        <b>Rejets</b> [kg]
        <br/>
        <input type="text" size="5" name="rejets" value="<?php echo $results[12];?>" />
        <br/>
        <br/>
        <b>Poids des crevettes par cat&eacute;gorie [kg]</b>
        <table>
        <tr><td><b>CAT 0</b></td><td><b>CAT 1</b></td><td><b>CAT 2</b></td><td><b>CAT 3</b></td><td><b>CAT 4</b></td><td><b>CAT 5</b></td><td><b>CAT 6</b></td><td><b>CAT 7</b></td><td><b>CAT 8</b></td></tr>
        <tr>
        <td><input type="text" size="3" name="c0_cre" value="<?php print $results[13]; ?>"/></td>
        <td><input type="text" size="3" name="c1_cre" value="<?php print $results[14]; ?>"/></td>
        <td><input type="text" size="3" name="c2_cre" value="<?php print $results[15]; ?>"/></td>
        <td><input type="text" size="3" name="c3_cre" value="<?php print $results[16]; ?>"/></td>
        <td><input type="text" size="3" name="c4_cre" value="<?php print $results[17]; ?>"/></td>
        <td><input type="text" size="3" name="c5_cre" value="<?php print $results[18]; ?>"/></td>
        <td><input type="text" size="3" name="c6_cre" value="<?php print $results[19]; ?>"/></td>
        <td><input type="text" size="3" name="c7_cre" value="<?php print $results[20]; ?>"/></td>
        <td><input type="text" size="3" name="c8_cre" value="<?php print $results[21]; ?>"/></td>
        </tr>
        </table>
        <b>Petit crevette cassees (C)</b> [kg]
        <br/>
        <input type="text" size="3" name="c_cre" value="<?php echo $results[22];?>" />
        <br/>
        <br/>
        <b>Grand crevette cassees (CC)</b> [kg]
        <br/>
        <input type="text" size="3" name="cc_cre" value="<?php echo $results[23];?>" />
        <br/>
        <br/>
        <b>Crevette cassees (O)</b> [kg]
        <br/>
        <input type="text" size="3" name="o_cre" value="<?php echo $results[24];?>" />
        <br/>
        <br/>
        <b>Vrac crevette cassees</b> [kg]
        <br/>
        <input type="text" size="3" name="v6_cre" value="<?php echo $results[25];?>" />
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
        <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
        <input type="submit" value="Enregistrer" name="submit"/>
        </form>

        <br/>
        <br/>


        <?php

        }  else if ($_GET['action'] == 'delete') {
        $id = $_GET['id'];
        $query = "DELETE FROM crevette.lance WHERE id = '$id'";

        if(!pg_query($query)) {
        msg_queryerror();
        //        print $query;
        } else {
        header("Location: ".$_SESSION['http_host']."/industrial/crevette/view_crevette_lance.php?source=$source&table=lance&action=show");
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
        $point_d = "ST_GeomFromText('POINT($lon $lat)',4326)";
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
        $point_f = "ST_GeomFromText('POINT($lon $lat)',4326)";
        }

        $id_navire = $_POST['id_navire'];
        $date_l = $_POST['date_l'];
        $lance = $_POST['lance'];
        $t_zone = $_POST['t_zone'];
        $h_d = $_POST['h_d'];
        $h_f = $_POST['h_f'];
        $D_d = comma2dot($_POST['D_d']);
        $D_f = comma2dot($_POST['D_f']);
        $T_d = comma2dot($_POST['T_d']);
        $rejets = $_POST['rejets'];

        if ($_POST['new_old']) {
        #navire, country, port_d, port_a, date_d, date_a, ndays, date_c, heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, comment, st_x(location), st_y(location)
        $query = "INSERT INTO crevette.lance "
        . "(username, datetime, id_navire, date_l, t_zone, lance, h_d, h_f, D_d, D_f, T_d, rejets, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c_cre, cc_cre, o_cre, v6_cre, location_d, location_f) "
        . "VALUES ('$username', now(), '$id_navire', '$date_l', '$t_zone', '$lance', '$h_d', '$h_f', '$D_d', '$D_f', '$T_d', '$rejets', '"
        .$_POST['c0_cre']."', '".$_POST['c1_cre']."', '".$_POST['c2_cre']."', '".$_POST['c3_cre']."', '"
        .$_POST['c4_cre']."', '".$_POST['c5_cre']."', '".$_POST['c6_cre']."', '".$_POST['c7_cre']."', '"
        .$_POST['c8_cre']."', '".$_POST['c_cre']."', '".$_POST['cc_cre']."', '".$_POST['o_cre']."', '".$_POST['v6_cre']."', $point_d, $point_f)";
        } else {
        $query = "UPDATE crevette.lance SET "
        . "username = '$username', datetime = now(), "
        . "id_navire = '".$id_navire."', t_zone = '".$_POST['t_zone']."', date_l = '".$_POST['date_l']."', lance = '".$_POST['lance']."', "
        . " h_d = '".$_POST['h_d']."', h_f = '".$_POST['h_f']."', D_d = '".$_POST['D_d']."', D_f = '".$_POST['D_f']."', "
        . " T_d = '".$_POST['T_d']."', rejets = '".$_POST['rejets']."', "
        . "c0_cre = '".$_POST['c0_cre']."', c1_cre = '".$_POST['c1_cre']."',  c2_cre = '".$_POST['c2_cre']."', "
        . "c3_cre = '".$_POST['c3_cre']."', c4_cre = '".$_POST['c4_cre']."',  c5_cre = '".$_POST['c5_cre']."', "
        . "c6_cre = '".$_POST['c6_cre']."', c7_cre = '".$_POST['c7_cre']."',  c8_cre = '".$_POST['c8_cre']."', "
        . "c_cre = '".$_POST['c_cre']."', cc_cre = '".$_POST['cc_cre']."', o_cre = '".$_POST['o_cre']."', v6_cre = '".$_POST['v6_cre']."', "
        . " location_d = $point_d,  location_f = $point_f"
        . " WHERE id = '{".$_POST['id']."}'";
        }

        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
        //        print $query;
        msg_queryerror();
        } else {
        //        print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/crevette/view_crevette_lance.php?source=$source&table=lance&action=show");
        }


        }

        foot();
