<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'crevette';

$username = $_SESSION['username'];

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
if ($_GET['action'] != "") {$_SESSION['path'][2] = $_GET['action'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];
$action = $_SESSION['path'][2];

$self = filter_input(INPUT_SERVER, 'PHP_SELF');
$host = filter_input(INPUT_SERVER, 'HTTP_HOST');

print "<h2>".label2name($source)." ".label2name($table)."</h2>";

if ($table == 'lance') {

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
      $point_d = "NULL";
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
      $point_f = "NULL";
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

    #navire, country, port_d, port_a, date_d, date_a, ndays, date_c, heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, comment, st_x(location), st_y(location)
        $query = "INSERT INTO crevette.lance "
                . "(username, datetime, id_navire, date_l, t_zone, lance, h_d, h_f, D_d, D_f, T_d, rejets, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c_cre, cc_cre, o_cre, v6_cre, location_d, location_f) "
                . "VALUES ('$username', now(), '$id_navire', '$date_l', '$t_zone', '$lance', '$h_d', '$h_f', '$D_d', '$D_f', '$T_d', '$rejets', '"
                .$_POST['c0_cre']."', '".$_POST['c1_cre']."', '".$_POST['c2_cre']."', '".$_POST['c3_cre']."', '"
                .$_POST['c4_cre']."', '".$_POST['c5_cre']."', '".$_POST['c6_cre']."', '".$_POST['c7_cre']."', '"
                .$_POST['c8_cre']."', '".$_POST['c_cre']."', '".$_POST['cc_cre']."', '".$_POST['o_cre']."', '".$_POST['v6_cre']."', $point_d, $point_f)";

    $query = str_replace('\'-- \'', 'NULL', $query);
    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
        echo "<p>".$query,"</p>";
        msg_queryerror();
    } else {
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/crevette/input_crevette.php?source=crevette&table=lance");
    }

    $controllo = 1;
}

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
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
    <b>Date lance</b>
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
    <b>Heure debut lance</b>
    <br/>
    <input type="time" size="10" name="h_d" value="<?php echo $results[7];?>" />
    <br/>
    <br/>
    <b>Heure fin lance</b>
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
    <input type="text" size="5" name="lat_deg_dd_"  value="<?php print $lat_deg_d_d;?>"/>&deg;
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
    <br/><br/>

<?php
}

} else if ($table == 'captures') {

//se submit = go!
if ($_POST['submit'] == "Enregistrer") {
    $id_navire = $_POST['id_navire'];
    $date_l = $_POST['date_l'];
    $lance = $_POST['lance'];

    $id_species = $_POST['id_species'];
    $t_taille = $_POST['t_taille'];
    $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);

    $q_id = "SELECT id FROM crevette.lance "
            . "WHERE id_navire = '$id_navire' AND date_l = '$date_l' AND lance = '$lance'";
    $id_lance = pg_fetch_row(pg_query($q_id))[0];

    #navire, country, port_d, port_a, date_d, date_a, ndays, date_c, heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, comment, st_x(location), st_y(location)
    $query = "INSERT INTO crevette.capture "
        . "(username, datetime, id_lance, id_species, t_taille, poids) "
        . "VALUES ('$username', now(), '$id_lance', '$id_species', '$t_taille', '$poids')";

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/crevette/input_crevette.php?source=crevette&table=lance");
    }


  $controllo = 1;

}

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Navire</b>
    <br/>
    <select id="id_navire" name="id_navire" onchange="menu_pop_1('id_navire','date_l','id_navire','date_l','crevette.lance')">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT id_navire, navire FROM crevette.lance "
            . "LEFT JOIN vms.navire ON crevette.lance.id_navire = vms.navire.id "
            . "WHERE navire IS NOT NULL "
            . "ORDER BY navire");
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
    <b>Date lance</b>
    <br/>
    <select id="date_l" name="date_l" onchange="menu_pop_2('id_navire','date_l','lance','id_navire','date_l','lance','crevette.lance')">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT date_l FROM crevette.lance "
            . "WHERE id_navire = '$results[3]' ORDER BY date_l");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[4]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>

    <br/>
    <br/>
    <b>Lance</b>
    <br/>
    <select id="lance" name="lance" >
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT lance FROM crevette.lance WHERE id_navire = '$results[3]' AND date_l = '$results[4]' ORDER BY lance");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[5]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    Vous ne pouvez pas trouver un lance? Ajoutez un nouveau <a href="input_crevette.php?source=crevette&table=lance">lance</a>.
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select name="id_species" class="chosen-select">
    <?php
    $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species  FROM fishery.species WHERE fishery.species.category LIKE '%industrial%' ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[6]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
        } else {
            print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Taille</b>
    <br/>
    <select name="t_taille">
    <option  value="">Aucun</option>
    <?php
        $result = pg_query("SELECT id, taille FROM crevette.t_taille ORDER BY id");
        while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[11]) {
                print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
            } else {
                print "<option value=\"$row[0]\">$row[1]</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Poids</b> [kg]
    <br/>
    <input type="text" size="5" name="poids" value="<?php echo $results[12];?>" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

<?php
}

}

foot();
