<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'trawlers';

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

if (right_write($_SESSION['username'],5,2)) {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

if ($table == 'route') {

//se submit = go!
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

    //navire, maree, t_fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment

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
    $comment = $_POST['comment'];

    $query = "INSERT INTO trawlers.route "
            . "(username, datetime, id_navire, maree, t_fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment, location_d, location_f) "
            . "VALUES ('$username', now(), '$id_navire', '$maree', '$t_fleet', '$date', '$lance', '$h_d', '$h_f', '$depth_d', '$depth_f', '$speed', '$reject', '$sample', '$comment', ST_GeomFromText($point_d,4326), ST_GeomFromText($point_f,4326))";

    $query = str_replace('\'-- \'', 'NULL', $query);
    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
        echo "<p>".$query,"</p>";
        msg_queryerror();
    } else {
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_form_trawlers.php");
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
        $result = pg_query("SELECT id, navire FROM vms.navire ORDER BY navire");
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
    <b>Mar&eacute;e</b>
    <br/>
    <input type="text" size="20" name="maree" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Flottille</b>
    <br/>
    <select name="t_fleet">
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
    <b>Heure debut</b> (hh:mm:ss)
    <br/>
    <input type="time" size="20" name="h_d" value="<?php echo $results[8]; ?>"/>
    <br/>
    <br/>
    <b>Heure fin</b> (hh:mm:ss)
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
    <b>Vitesse</b>
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
    <input type="text" size="5" name="lat_deg_d"  value="<?php print $lat_deg_d;?>"/>&deg;
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
    <input type="text" size="5" name="lat_deg_f"  value="<?php print $lat_deg_f;?>"/>&deg;
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
    <br/><br/>

<?php
}

} else if ($table == 'production') {

//se submit = go!
if ($_POST['submit'] == "Enregistrer") {

    $navire = $_POST['navire'];
    $maree = $_POST['maree'];
    $lance = $_POST['lance'];
    $id_species = $_POST['id_species'];
    $poids = $_POST['poids'];
    $comment = $_POST['comment'];

    $q_id = "SELECT id FROM trawlers.route WHERE maree = '$maree' AND lance = '$lance'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    #username, maree, t_zee, id_route, t_objet, type_balise, code_balise, t_operation, t_appartenance, t_devenir, remarque
    $query = "INSERT INTO trawlers.captures "
        . "(datetime, username, id_route, maree, lance, id_species, poids, comment) "
        . "VALUES (now(), '$username', '$id_route', '$maree', '$lance', '$id_species', '$poids', '$comment')";

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_form_trawlers.php");
    }


  $controllo = 1;

}

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Mar&eacute;e</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','lance','maree','lance','trawlers.route')">
    <option value="none">None</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route ORDER BY maree");
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
    <b>Lanc&eacute;</b>
    <br/>
    <select id="lance" name="lance">
    <option  value="none">Please choose from above</option>
    <?php
    $result = pg_query("SELECT DISTINCT lance FROM trawlers.route  WHERE maree = '$results[4]' ORDER BY lance");
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
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.captures ON fishery.species.id = trawlers.captures.id_species  WHERE species IS NOT NULL ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
           print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Poids</b> (kg)
    <br/>
    <input type="text" size="20" name="poids" value="<?php echo $results[7]; ?>"/>
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="comment" value="<?php echo $results[8];?>" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

<?php
}


} else if ($table == 'p_lance') {

//se submit = go!
if ($_POST['submit'] == "Enregistrer") {


    $maree = $_POST['maree'];
    $lance = $_POST['lance'];
    $id_species = $_POST['id_species'];

    $c0_cre = $_POST['c0_cre'];
    $c1_cre = $_POST['c1_cre'];
    $c2_cre = $_POST['c2_cre'];
    $c3_cre = $_POST['c3_cre'];
    $c4_cre = $_POST['c4_cre'];
    $c5_cre = $_POST['c5_cre'];
    $c6_cre = $_POST['c6_cre'];
    $c7_cre = $_POST['c7_cre'];
    $c8_cre = $_POST['c8_cre'];
    $c9_cre = $_POST['c9_cre'];
    $c0_poi = $_POST['c0_poi'];
    $c1_poi = $_POST['c1_poi'];
    $c2_poi = $_POST['c2_poi'];
    $c3_poi = $_POST['c3_poi'];
    $c4_poi = $_POST['c4_poi'];
    $c5_poi = $_POST['c5_poi'];
    $c6_poi = $_POST['c6_poi'];

    $q_id = "SELECT id FROM trawlers.route WHERE maree = '$maree' AND lance = '$lance'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    $query = "INSERT INTO trawlers.p_lance "
        . "(datetime, username, id_route, maree, id_species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi) "
        . "VALUES (now(), '$username', '$id_route', '$maree', '$id_species', '$c0_cre', '$c1_cre', '$c2_cre', '$c3_cre', '$c4_cre', '$c5_cre', '$c6_cre', '$c7_cre', '$c8_cre', '$c9_cre', '$c0_poi', '$c1_poi', '$c2_poi', '$c3_poi', '$c4_poi', '$c5_poi', '$c6_poi')";


    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_form_trawlers.php");
    }


  $controllo = 1;

}

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Mar&eacute;e</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','lance','maree','lance','trawlers.route')">
    <option value="none">None</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route ORDER BY maree");
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
    <b>Lanc&eacute;</b>
    <br/>
    <select id="lance" name="lance">
    <option  value="none">Please choose from above</option>
    <?php
    $result = pg_query("SELECT DISTINCT lance FROM trawlers.route  WHERE maree = '$results[5]' ORDER BY lance");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[6]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.p_lance ON fishery.species.id = trawlers.p_lance.id_species  WHERE species IS NOT NULL ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
           print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Poids pur categorie taille crevette</b> (kg)
    <br/>
    <table>
        <tr><td>0</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td><td>8</td><td>CC</td></tr>
        <tr>
            <?php
            for ($i = 0; $i < 10; $i++) {
                print '<td><input type="text" size="3" name="c'.$i.'_cre" value="'.$results[7+$i].'"></td>';
            }
            ?>
        </tr>
    </table>
    <br/>
    <b>Poids pour categorie taille poisson</b> (kg)
    <br/>
    <table>
        <tr><td>G</td><td>M</td><td>P</td><td>F</td><td>FF</td><td>Mix</td><td>Grosse pi&egrave;ce</td></tr>
        <tr>
            <?php
            for ($i = 0; $i < 7; $i++) {
                print '<td><input type="text" size="3" name="c'.$i.'_poi" value="'.$results[7+10+$i].'"></td>';
            }
            ?>
        </tr>
    </table>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

<?php
}

} else if ($table == 'p_day') {

    if ($_POST['submit'] == "Enregistrer") {

        $maree = $_POST['maree'];
        $lance_d = $_POST['lance_d'];
        $lance_f = $_POST['lance_f'];
        $id_species = $_POST['id_species'];

        $c0_cre = $_POST['c0_cre'];
        $c1_cre = $_POST['c1_cre'];
        $c2_cre = $_POST['c2_cre'];
        $c3_cre = $_POST['c3_cre'];
        $c4_cre = $_POST['c4_cre'];
        $c5_cre = $_POST['c5_cre'];
        $c6_cre = $_POST['c6_cre'];
        $c7_cre = $_POST['c7_cre'];
        $c8_cre = $_POST['c8_cre'];
        $c9_cre = $_POST['c9_cre'];
        $c0_poi = $_POST['c0_poi'];
        $c1_poi = $_POST['c1_poi'];
        $c2_poi = $_POST['c2_poi'];
        $c3_poi = $_POST['c3_poi'];
        $c4_poi = $_POST['c4_poi'];
        $c5_poi = $_POST['c5_poi'];
        $c6_poi = $_POST['c6_poi'];

        $query = "INSERT INTO trawlers.p_day "
            . "(datetime, username, maree, lance_d, lance_f, id_species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi) "
            . "VALUES (now(), '$username', '$maree', '$lance_d', '$lance_f', '$id_species', '$c0_cre', '$c1_cre', '$c2_cre', '$c3_cre', '$c4_cre', '$c5_cre', '$c6_cre', '$c7_cre', '$c8_cre', '$c9_cre', '$c0_poi', '$c1_poi', '$c2_poi', '$c3_poi', '$c4_poi', '$c5_poi', '$c6_poi')";

        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        } else {
            #print $query;
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_form_trawlers.php");
        }

            $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Mar&eacute;e</b>
    <br/>
    <select id="maree" name="maree">
    <option value="none">None</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[3]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Lanc&eacute; Debut</b>
    <br/>
    <input type="text" size="3" name="lance_d" value="<?php print $results[4]; ?>">
    <br/>
    <br/>
    <b>Lanc&eacute; Fin</b>
    <br/>
    <input type="text" size="3" name="lance_f" value="<?php print $results[5]; ?>">
    <br/>
    <br/>
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.p_day ON fishery.species.id = trawlers.p_day.id_species  WHERE species IS NOT NULL ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
           print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Categorie taille crevette</b>
    <br/>
    <table>
        <tr><td>0</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td><td>8</td><td>CC</td></tr>
        <tr>
            <?php
            for ($i = 0; $i < 10; $i++) {
                print '<td><input type="text" size="3" name="c'.$i.'_cre" value="'.$results[7+$i].'"></td>';
            }
            ?>
        </tr>
    </table>
    <br/>
    <b>Categorie taille poisson</b>
    <br/>
    <table>
        <tr><td>G</td><td>M</td><td>P</td><td>F</td><td>FF</td><td>Mix</td><td>Grosse pi&egrave;ce</td></tr>
        <tr>
            <?php
            for ($i = 0; $i < 7; $i++) {
                print '<td><input type="text" size="3" name="c'.$i.'_poi" value="'.$results[7+10+$i].'"></td>';
            }
            ?>
        </tr>
    </table>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
}  else if ($table == 'ft_cre') {

    if ($_POST['submit'] == "Enregistrer") {

        $maree = $_POST['maree'];
        $lance = $_POST['lance'];
        $id_species = $_POST['id_species'];
        $t_sex = $_POST['t_sex'];
        $t_maturity = $_POST['t_maturity'];
        $poids = $_POST['poids'];

        $q_id = "SELECT id FROM trawlers.route WHERE maree = '$maree' AND lance = '$lance'";
        $id_route = pg_fetch_row(pg_query($q_id))[0];

        $query = "INSERT INTO trawlers.ft_cre "
            . "(datetime, username, id_route, maree, lance, id_species, t_sex, t_maturity, poids, "
                . "ft1_cre, ft2_cre, ft3_cre, ft4_cre, ft5_cre, ft6_cre, ft7_cre, ft8_cre, ft9_cre, "
                . "ft10_cre, ft11_cre, ft12_cre, ft13_cre, ft14_cre, ft15_cre, ft16_cre, ft17_cre, ft18_cre, ft19_cre, "
                . "ft20_cre, ft21_cre, ft22_cre, ft23_cre, ft24_cre, ft25_cre, ft26_cre, ft27_cre, ft28_cre, ft29_cre, "
                . "ft30_cre, ft31_cre, ft32_cre, ft33_cre, ft34_cre, ft35_cre, ft36_cre, ft37_cre, ft38_cre, ft39_cre, "
                . "ft40_cre, ft41_cre, ft42_cre, ft43_cre, ft44_cre, ft45_cre, ft46_cre, ft47_cre, ft48_cre, ft49_cre, "
                . "ft50_cre, ft51_cre, ft52_cre, ft53_cre, ft54_cre, ft55_cre, ft56_cre, ft57_cre, ft58_cre, ft59_cre, "
                . "ft60_cre, ft61_cre, ft62_cre, ft63_cre, ft64_cre, ft65_cre, ft66_cre, ft67_cre, ft68_cre, ft69_cre, "
                . "ft70_cre, ft71_cre, ft72_cre, ft73_cre, ft74_cre, ft75_cre, ft76_cre, ft77_cre, ft78_cre, ft79_cre, "
                . "ft80_cre, ft81_cre, ft82_cre, ft83_cre, ft84_cre, ft85_cre, ft86_cre, ft87_cre, ft88_cre, ft89_cre, "
                . "ft90_cre, ft91_cre, ft92_cre, ft93_cre, ft94_cre, ft95_cre, ft96_cre, ft97_cre, ft98_cre, ft99_cre, "
                . "ft100_cre) "
            . "VALUES (now(), '$username', '$id_route', '$maree', '$lance', '$id_species', '$t_sex', '$t_maturity', '$poids', '"
            .$_POST['ft1_cre']."', '".$_POST['ft2_cre']."', '".$_POST['ft3_cre']."', '".$_POST['ft4_cre']."', '".$_POST['ft5_cre']."', '".$_POST['ft6_cre']."', '".$_POST['ft7_cre']."', '".$_POST['ft8_cre']."', '".$_POST['ft9_cre']."', '"
            .$_POST['ft10_cre']."', '".$_POST['ft11_cre']."', '".$_POST['ft12_cre']."', '".$_POST['ft13_cre']."', '".$_POST['ft14_cre']."', '".$_POST['ft15_cre']."', '".$_POST['ft16_cre']."', '".$_POST['ft17_cre']."', '".$_POST['ft18_cre']."', '".$_POST['ft19_cre']."', '"
            .$_POST['ft20_cre']."', '".$_POST['ft21_cre']."', '".$_POST['ft22_cre']."', '".$_POST['ft23_cre']."', '".$_POST['ft24_cre']."', '".$_POST['ft25_cre']."', '".$_POST['ft26_cre']."', '".$_POST['ft27_cre']."', '".$_POST['ft28_cre']."', '".$_POST['ft29_cre']."', '"
            .$_POST['ft30_cre']."', '".$_POST['ft31_cre']."', '".$_POST['ft32_cre']."', '".$_POST['ft33_cre']."', '".$_POST['ft34_cre']."', '".$_POST['ft35_cre']."', '".$_POST['ft36_cre']."', '".$_POST['ft37_cre']."', '".$_POST['ft38_cre']."', '".$_POST['ft39_cre']."', '"
            .$_POST['ft40_cre']."', '".$_POST['ft41_cre']."', '".$_POST['ft42_cre']."', '".$_POST['ft43_cre']."', '".$_POST['ft44_cre']."', '".$_POST['ft45_cre']."', '".$_POST['ft46_cre']."', '".$_POST['ft47_cre']."', '".$_POST['ft48_cre']."', '".$_POST['ft49_cre']."', '"
            .$_POST['ft50_cre']."', '".$_POST['ft51_cre']."', '".$_POST['ft52_cre']."', '".$_POST['ft53_cre']."', '".$_POST['ft54_cre']."', '".$_POST['ft55_cre']."', '".$_POST['ft56_cre']."', '".$_POST['ft57_cre']."', '".$_POST['ft58_cre']."', '".$_POST['ft59_cre']."', '"
            .$_POST['ft60_cre']."', '".$_POST['ft61_cre']."', '".$_POST['ft62_cre']."', '".$_POST['ft63_cre']."', '".$_POST['ft64_cre']."', '".$_POST['ft65_cre']."', '".$_POST['ft66_cre']."', '".$_POST['ft67_cre']."', '".$_POST['ft68_cre']."', '".$_POST['ft69_cre']."', '"
            .$_POST['ft70_cre']."', '".$_POST['ft71_cre']."', '".$_POST['ft72_cre']."', '".$_POST['ft73_cre']."', '".$_POST['ft74_cre']."', '".$_POST['ft75_cre']."', '".$_POST['ft76_cre']."', '".$_POST['ft77_cre']."', '".$_POST['ft78_cre']."', '".$_POST['ft79_cre']."', '"
            .$_POST['ft80_cre']."', '".$_POST['ft81_cre']."', '".$_POST['ft82_cre']."', '".$_POST['ft83_cre']."', '".$_POST['ft84_cre']."', '".$_POST['ft85_cre']."', '".$_POST['ft86_cre']."', '".$_POST['ft87_cre']."', '".$_POST['ft88_cre']."', '".$_POST['ft89_cre']."', '"
            .$_POST['ft90_cre']."', '".$_POST['ft91_cre']."', '".$_POST['ft92_cre']."', '".$_POST['ft93_cre']."', '".$_POST['ft94_cre']."', '".$_POST['ft95_cre']."', '".$_POST['ft96_cre']."', '".$_POST['ft97_cre']."', '".$_POST['ft98_cre']."', '".$_POST['ft99_cre']."', '"
            .$_POST['ft100_cre']."')";

        $query = str_replace('none', '', $query);
        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        } else {
            #print $query;
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_form_trawlers.php");
        }

        $controllo = 1;

    }

if (!$controllo) {

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Mar&eacute;e</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','lance','maree','lance','trawlers.route')">
    <option value="none">None</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route ORDER BY maree");
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
    <b>Lanc&eacute;</b>
    <br/>
    <select id="lance" name="lance">
    <option  value="none">Please choose from above</option>
    <?php
    $result = pg_query("SELECT DISTINCT lance FROM trawlers.route  WHERE maree = '$results[4]' ORDER BY lance");
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
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.ft_cre ON fishery.species.id = trawlers.ft_cre.id_species  WHERE species IS NOT NULL ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
           print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Sexe</b>
    <br/>
    <select name="t_sex">
    <?php
    $result = pg_query("SELECT id, sex FROM trawlers.t_sex");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[7]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Maturite</b>
    <br/>
    <select name="t_maturity">
    <?php
    $result = pg_query("SELECT id, maturity FROM trawlers.t_maturity");
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
    <b>Poids &eacute;chantillon</b> (kg)
    <br/>
    <input type="text" size="5" name="poids" value="<?php print $results[9]; ?>">
    <br/>
    <br/>
    <b>Nombre Individue par taille</b> (cm)
    <br/>
    <br/>
        <table>
        <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>5</td>
            <td>6</td>
            <td>7</td>
            <td>8</td>
            <td>9</td>
        </tr>
        <tr>
            <td><input type="text" size="3" name="ft1_cre" value="<?php print $results[10]; ?>"></td>
            <td><input type="text" size="3" name="ft2_cre" value="<?php print $results[11]; ?>"></td>
            <td><input type="text" size="3" name="ft3_cre" value="<?php print $results[12]; ?>"></td>
            <td><input type="text" size="3" name="ft4_cre" value="<?php print $results[13]; ?>"></td>
            <td><input type="text" size="3" name="ft5_cre" value="<?php print $results[14]; ?>"></td>
            <td><input type="text" size="3" name="ft6_cre" value="<?php print $results[15]; ?>"></td>
            <td><input type="text" size="3" name="ft7_cre" value="<?php print $results[16]; ?>"></td>
            <td><input type="text" size="3" name="ft8_cre" value="<?php print $results[17]; ?>"></td>
            <td><input type="text" size="3" name="ft9_cre" value="<?php print $results[18]; ?>"></td>
        </tr>
    </table>

    <?php
        for ($i = 1; $i < 10; $i++) {
            print "<table><tr>";

                for ($j = 0; $j < 10; $j++) {
                    $k = $j+$i*10;
                    $l = $k;
                    print '<td>'.$l.'</td>';
                }

            print '<tr/><tr>';

                for ($j = 0; $j < 10; $j++) {
                    $k = $j+$i*10;
                    print '<td><input type="text" size="3" name="ft'.$k.'_cre" value="'.$results[$k].'"></td>';
                }
            print "</tr>";
            print "</table><br/>";
        }
    ?>

    <table><tr><td>100</td></tr><tr><td><input type="text" size="3" name="ft100_cre" value="<?php print $results[$k+1]; ?>"></td></tr></table>

    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
} else if ($table == 'ft_poi') {

    if ($_POST['submit'] == "Enregistrer") {

        $maree = $_POST['maree'];
        $lance = $_POST['lance'];
        $id_species = $_POST['id_species'];
        $t_rejete = $_POST['t_rejete'];
        $t_measure = $_POST['t_measure'];
        $poids = $_POST['poids'];

        $q_id = "SELECT id FROM trawlers.route WHERE maree = '$maree' AND lance = '$lance'";
        $id_route = pg_fetch_row(pg_query($q_id))[0];

        $query = "INSERT INTO trawlers.ft_poi "
            . "(datetime, username, id_route, maree, lance, id_species, t_rejete, t_measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi, "
                . "ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, "
                . "ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, "
                . "ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, "
                . "ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, "
                . "ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, "
                . "ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, "
                . "ft70_poi, ft71_poi, ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, "
                . "ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, "
                . "ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, "
                . "ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, "
                . "ft109_poi, ft110_poi, ft111_poi, ft112_poi) "
            . "VALUES (now(), '$username', '$id_route', '$maree', '$lance', '$id_species', '$t_rejete', '$t_measure', '$poids', '"
            .$_POST['ft1_poi']."', '".$_POST['ft2_poi']."', '".$_POST['ft3_poi']."', '".$_POST['ft4_poi']."', '".$_POST['ft5_poi']."', '".$_POST['ft6_poi']."', '".$_POST['ft7_poi']."', '".$_POST['ft8_poi']."', '".$_POST['ft9_poi']."', '"
            .$_POST['ft10_poi']."', '".$_POST['ft11_poi']."', '".$_POST['ft12_poi']."', '".$_POST['ft13_poi']."', '".$_POST['ft14_poi']."', '".$_POST['ft15_poi']."', '".$_POST['ft16_poi']."', '".$_POST['ft17_poi']."', '".$_POST['ft18_poi']."', '".$_POST['ft19_poi']."', '"
            .$_POST['ft20_poi']."', '".$_POST['ft21_poi']."', '".$_POST['ft22_poi']."', '".$_POST['ft23_poi']."', '".$_POST['ft24_poi']."', '".$_POST['ft25_poi']."', '".$_POST['ft26_poi']."', '".$_POST['ft27_poi']."', '".$_POST['ft28_poi']."', '".$_POST['ft29_poi']."', '"
            .$_POST['ft30_poi']."', '".$_POST['ft31_poi']."', '".$_POST['ft32_poi']."', '".$_POST['ft33_poi']."', '".$_POST['ft34_poi']."', '".$_POST['ft35_poi']."', '".$_POST['ft36_poi']."', '".$_POST['ft37_poi']."', '".$_POST['ft38_poi']."', '".$_POST['ft39_poi']."', '"
            .$_POST['ft40_poi']."', '".$_POST['ft41_poi']."', '".$_POST['ft42_poi']."', '".$_POST['ft43_poi']."', '".$_POST['ft44_poi']."', '".$_POST['ft45_poi']."', '".$_POST['ft46_poi']."', '".$_POST['ft47_poi']."', '".$_POST['ft48_poi']."', '".$_POST['ft49_poi']."', '"
            .$_POST['ft50_poi']."', '".$_POST['ft51_poi']."', '".$_POST['ft52_poi']."', '".$_POST['ft53_poi']."', '".$_POST['ft54_poi']."', '".$_POST['ft55_poi']."', '".$_POST['ft56_poi']."', '".$_POST['ft57_poi']."', '".$_POST['ft58_poi']."', '".$_POST['ft59_poi']."', '"
            .$_POST['ft60_poi']."', '".$_POST['ft61_poi']."', '".$_POST['ft62_poi']."', '".$_POST['ft63_poi']."', '".$_POST['ft64_poi']."', '".$_POST['ft65_poi']."', '".$_POST['ft66_poi']."', '".$_POST['ft67_poi']."', '".$_POST['ft68_poi']."', '".$_POST['ft69_poi']."', '"
            .$_POST['ft70_poi']."', '".$_POST['ft71_poi']."', '".$_POST['ft72_poi']."', '".$_POST['ft73_poi']."', '".$_POST['ft74_poi']."', '".$_POST['ft75_poi']."', '".$_POST['ft76_poi']."', '".$_POST['ft77_poi']."', '".$_POST['ft78_poi']."', '".$_POST['ft79_poi']."', '"
            .$_POST['ft80_poi']."', '".$_POST['ft81_poi']."', '".$_POST['ft82_poi']."', '".$_POST['ft83_poi']."', '".$_POST['ft84_poi']."', '".$_POST['ft85_poi']."', '".$_POST['ft86_poi']."', '".$_POST['ft87_poi']."', '".$_POST['ft88_poi']."', '".$_POST['ft89_poi']."', '"
            .$_POST['ft90_poi']."', '".$_POST['ft91_poi']."', '".$_POST['ft92_poi']."', '".$_POST['ft93_poi']."', '".$_POST['ft94_poi']."', '".$_POST['ft95_poi']."', '".$_POST['ft96_poi']."', '".$_POST['ft97_poi']."', '".$_POST['ft98_poi']."', '".$_POST['ft99_poi']."', '"
            .$_POST['ft100_poi']."', '".$_POST['ft101_poi']."', '".$_POST['ft102_poi']."', '".$_POST['ft103_poi']."', '".$_POST['ft104_poi']."', '".$_POST['ft105_poi']."', '".$_POST['ft106_poi']."', '".$_POST['ft107_poi']."', '".$_POST['ft108_poi']."', '".$_POST['ft109_poi']."', '"
            .$_POST['ft110_poi']."', '".$_POST['ft111_poi']."', '".$_POST['ft112_poi']."')";


        $query = str_replace('none', '', $query);
        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        } else {
            #print $query;
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_form_trawlers.php");
        }

            $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Mar&eacute;e</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','lance','maree','lance','trawlers.route')">
    <option value="none">None</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route ORDER BY maree");
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
    <b>Lanc&eacute;</b>
    <br/>
    <select id="lance" name="lance">
    <option  value="none">Please choose from above</option>
    <?php
    $result = pg_query("SELECT DISTINCT lance FROM trawlers.route  WHERE maree = '$results[4]' ORDER BY lance");
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
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.ft_poi ON fishery.species.id = trawlers.ft_poi.id_species  WHERE species IS NOT NULL ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
           print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Rejete</b>
    <br/>
    <select name="t_rejete">
    <?php
    $result = pg_query("SELECT id, rejete FROM trawlers.t_rejete");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[6]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Measure</b>
    <br/>
    <select name="t_measure">
    <?php
    $result = pg_query("SELECT id, measure FROM trawlers.t_measure");
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
    <b>Poids</b> (kg)
    <br/>
    <input type="text" size="5" name="poids" value="<?php print $results[9]; ?>">
    <br/>
    <br/>
    <b>Individue par taille</b> (cm)
    <br/>
    <br/>

    <?php
        for ($i = 0; $i < 11; $i++) {
            print "<table><tr>";

                for ($j = 1; $j <= 10; $j++) {
                    $k = $j+$i*10;
                    $l = $k;
                    print '<td>'.$l.'</td>';
                }

            print '<tr/><tr>';

                for ($j = 1; $j <= 10; $j++) {
                    $k = $j+$i*10;
                    print '<td><input type="text" size="3" name="ft'.$k.'_poi" value="'.$results[$k+9].'"></td>';
                }
            print "</tr>";
            print "</table><br/>";
        }
    ?>

    <table>
        <tr><td>111</td><td>112</td></tr>
        <tr><td><input type="text" size="3" name="ft111_poi" value="<?php print $results[$k+10]; ?>"></td><td><input type="text" size="3" name="ft112_poi" value="<?php print $results[$k+10+1]; ?>"></td></tr>
    </table>

    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
} else if ($table == 'poids_taille') {

    if ($_POST['submit'] == "Enregistrer") {

        $maree = $_POST['maree'];
        $id_species = $_POST['id_species'];
        $t_measure = $_POST['t_measure'];
        $taille = $_POST['taille'];
        $p1 = $_POST['p1'];
        $p2 = $_POST['p2'];
        $p3 = $_POST['p3'];
        $p4 = $_POST['p4'];
        $p5 = $_POST['p5'];

        $query = "INSERT INTO trawlers.poids_taille "
            . "(datetime, username, maree, id_species, t_measure, taille, p1, p2, p3, p4, p5) "
            . "VALUES (now(), '$username', '$maree', '$id_species', '$t_measure', '$taille', '$p1', '$p2', '$p3', '$p4', '$p5')";

        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        } else {
            #print $query;
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_form_trawlers.php");
        }

        $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Mar&eacute;e</b>
    <br/>
    <select id="maree" name="maree">
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[3]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.captures ON fishery.species.id = trawlers.captures.id_species  WHERE species IS NOT NULL ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
           print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Measure</b>
    <br/>
    <select name="t_measure">
    <?php
    $result = pg_query("SELECT id, measure FROM trawlers.t_measure");
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
    <b>Taille</b> (cm)
    <br/>
    <input type="text" name="taille" value="<?php print $results[6];?>">
    <br/>
    <br/>
    <b>Poids 1</b> (kg)
    <br/>
    <input type="text" name="p1" value="<?php print $results[7];?>">
    <br/>
    <br/>
    <b>Poids 2</b> (kg)
    <br/>
    <input type="text" name="p2" value="<?php print $results[8];?>">
    <br/>
    <br/>
    <b>Poids 3</b> (kg)
    <br/>
    <input type="text" name="p3" value="<?php print $results[9];?>">
    <br/>
    <br/>
    <b>Poids 4</b> (kg)
    <br/>
    <input type="text" name="p4" value="<?php print $results[10];?>">
    <br/>
    <br/>
    <b>Poids 5</b> (kg)
    <br/>
    <input type="text" name="p5" value="<?php print $results[11];?>">
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
}  else if ($table == 'route_accidentelle') {

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
        $heure = $_POST['heure'];
        $t_co = $_POST['t_co'];

        //navire, maree, t_fleet, date, lance, heure, depth_d, depth_f, speed, reject, sample, comment

        $query = "INSERT INTO trawlers.route_accidentelle "
            . "(username, datetime, id_navire, maree, t_fleet, date, lance, heure, t_co, location) "
            . "VALUES ('$username', now(), '$id_navire', '$maree', '$t_fleet', '$date', '$lance', '$heure', '$t_co', ST_GeomFromText($point,4326))";

        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        } else {
            #print $query;
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_form_trawlers.php");
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
        $result = pg_query("SELECT id, navire FROM vms.navire ORDER BY navire");
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
    <b>Flottille</b>
    <br/>
    <select name="t_fleet">
    <option value="">None</option>
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
    <b>Mar&eacute;e</b>
    <br/>
    <input type="text" size="20" name="maree" value="<?php echo $results[5]; ?>"/>
    <br/>
    <br/>
    <b>Lanc&eacute;</b>
    <br/>
    <input type="text" size="10" name="lance" value="<?php echo $results[8]; ?>"/>
    <br/>
    <br/>
    <b>Date</b>
    <br/>
    <input type="date" size="20" name="date" value="<?php echo $results[6]; ?>"/>
    <br/>
    <br/>
    <b>Heure</b>
    <br/>
    <input type="time" size="20" name="time" value="<?php echo $results[9]; ?>"/>
    <br/>
    <br/>
    <b>Capture/Observe</b>
    <br/>
    <select name="t_co">
    <option value="">None</option>
    <?php
    $result = pg_query("SELECT id, co FROM trawlers.t_co ORDER BY t_co");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[7]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
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
    <input type="text" size="5" name="lat_deg_dms" />&deg;
    <input type="text" size="5" name="lat_min_dms" />&prime;
    <input type="text" size="5" name="lat_sec_dms" />&prime;&prime;
    </div>

    <div class="DDMM">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_dm" />&deg;
    <input type="text" size="5" name="lat_min_dm" />&prime;
    </div>

    <div class="DD" style="display:none">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_d" />&deg;
    </div>

    <select name="NS">
        <option value="N" >N</option>
        <option value="S" >S</option>
    </select>

    <div class="DDMMSS" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dms" />&deg;
    <input type="text" size="5" name="lon_min_dms" />&prime;
    <input type="text" size="5" name="lon_sec_dms" />&prime;&prime;
    </div>

    <div class="DDMM">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dm" />&deg;
    <input type="text" size="5" name="lon_min_dm" />&prime;
    </div>

    <div class="DD" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_d" />&deg;
    </div>
    <select name="EO">
        <option value="E" >E</option>
        <option value="O" >O</option>
    </select>

    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
} else if ($table == 'captures_requin') {

    if ($_POST['submit'] == "Enregistrer") {

        $maree = $_POST['maree'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $id_species = $_POST['id_species'];
        $n_ind = $_POST['n_ind'];
        $t_sex = $_POST['t_sex'];
        $taille = $_POST['taille'];
        $poids = $_POST['poids'];
        $t_capture = $_POST['t_capture'];
        $t_relache = $_POST['t_relache'];
        $preleve = $_POST['preleve'];
        $camera = $_POST['camera'];
        $photo = $_POST['photo'];
        $remarque = $_POST['remarque'];

        $q_id = "SELECT id FROM trawlers.route_accidentelle WHERE maree = '$maree' AND date = '$date' AND time = '$time'";
        $id_route = pg_fetch_row(pg_query($q_id))[0];

        #print $q_id;

        $query = "INSERT INTO trawlers.captures_mammal "
            . "(datetime, username, id_route, maree, date, heure, id_species, n_ind, t_sex, taille, poids, t_capture, t_relache, preleve, camera, photo, remarque) "
            . "VALUES (now(), '$username', '$id_route', '$maree', '$date', '$heure', '$id_species', '$n_ind', '$t_sex', '$taille', '$poids', '$t_capture', '$t_relache', '$preleve', '$camera', '$photo', '$remarque')";

        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        } else {
            #print $query;
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_form_trawlers.php");
        }

        $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Mar&eacute;e</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','trawlers.route_accidentelle')">
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route_accidentelle ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Date</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','trawlers.route_accidentelle')">
    <option  value="none">Please choose from above</option>
    <?php
    $result = pg_query("SELECT DISTINCT date FROM trawlers.route_accidentelle  WHERE maree = '$results[4]' ORDER BY date");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Heure</b>
    <br/>
    <select id="time" name="time">
    <option  value="none">Please choose from above</option>
    <?php
    $result = pg_query("SELECT DISTINCT time FROM trawlers.route_accidentelle  WHERE maree = '$results[4]' AND date = '$results[5]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.captures_requin ON fishery.species.id = trawlers.captures_requin.id_species  WHERE species IS NOT NULL ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
           print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Nombre individue</b>
    <br/>
    <input type="text" size="5" name="n_ind" value="<?php echo $results[12]; ?>"/>
    <br/>
    <br/>
    <b>Sexe</b>
    <br/>
    <select name="t_sex">
    <option value="">None</option>
    <?php
    $result = pg_query("SELECT id, sex FROM trawlers.t_sex ORDER BY sex");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select><br/>
    <br/>
    <b>Taille</b> (cm)
    <br/>
    <input type="text" size="10" name="taille" value="<?php echo $results[14]; ?>"/>
    <br/>
    <br/>
    <b>Poids</b> (kg)
    <br/>
    <input type="text" size="10" name="poids" value="<?php echo $results[14]; ?>"/>
    <br/>
    <br/>
    <b>Condition capture</b>
    <br/>
    <select name="t_capture">
    <option value="">None</option>
    <?php
    $result = pg_query("SELECT id, condition FROM trawlers.t_condition ORDER BY condition");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select><br/>
    <br/>
    <b>Condition relache</b>
    <br/>
    <select name="t_relache">
    <option value="">None</option>
    <?php
    $result = pg_query("SELECT id, condition FROM trawlers.t_condition ORDER BY condition");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Pr&eacute;l&egrave;vement code</b>
    <br/>
    <input type="text" size="10" name="preleve" value="<?php echo $results[17]; ?>"/>
    <br/>
    <br/>
    <b>Camera ID</b>
    <br/>
    <input type="text" size="20" name="camera" value="<?php echo $results[18]; ?>"/>
    <br/>
    <br/>
    <b>Photo ID</b>
    <br/>
    <input type="text" size="20" name="photo" value="<?php echo $results[19]; ?>"/>
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" value="<?php echo $results[20];?>" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
} else if ($table == 'captures_mammal') {

    if ($_POST['submit'] == "Enregistrer") {

    $maree = $_POST['maree'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $id_species = $_POST['id_species'];
    $n_ind = $_POST['n_ind'];
    $t_sex = $_POST['t_sex'];
    $taille = $_POST['taille'];
    $t_capture = $_POST['t_capture'];
    $t_relache = $_POST['t_relache'];
    $preleve = $_POST['preleve'];
    $camera = $_POST['camera'];
    $photo = $_POST['photo'];
    $remarque = $_POST['remarque'];

    $q_id = "SELECT id FROM trawlers.route_accidentelle WHERE maree = '$maree' AND date = '$date' AND heure = '$heure'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    #print $q_id;

    $query = "INSERT INTO trawlers.captures_mammal "
        . "(datetime, username, id_route, maree, date, heure, id_species, n_ind, t_sex, taille, t_capture, t_relache, preleve, camera, photo, remarque) "
        . "VALUES (now(), '$username', '$id_route', '$maree', '$date', '$heure', '$id_species', '$n_ind', '$t_sex', '$taille', '$t_capture', '$t_relache', '$preleve', '$camera', '$photo', '$remarque')";

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_form_trawlers.php");
    }

    $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Maree</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','trawlers.route_accidentelle')">
    <option value="none">None</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route_accidentelle ORDER BY maree");
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
    <b>Date</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','trawlers.route_accidentelle')">
    <option  value="none">Please choose from above</option>
    <?php
    $result = pg_query("SELECT DISTINCT date FROM trawlers.route_accidentelle  WHERE maree = '$results[4]' ORDER BY date");
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
    <b>Heure</b>
    <br/>
    <select id="time" name="time">
    <option  value="none">Please choose from above</option>
    <?php
    $result = pg_query("SELECT DISTINCT heure FROM trawlers.route_accidentelle  WHERE maree = '$results[4]' AND date = '$results[5]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[6]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>

    <br/>
    <br/>
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.captures_mammal ON fishery.species.id = trawlers.captures_mammal.id_species  WHERE species IS NOT NULL ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
           print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Nombre individue</b>
    <br/>
    <input type="text" size="5" name="n_ind" value="<?php echo $results[12]; ?>"/>
    <br/>
    <br/>
    <b>Sexe</b>
    <br/>
    <select name="t_sex">
    <option value="">None</option>
    <?php
    $result = pg_query("SELECT id, sex FROM trawlers.t_sex ORDER BY sex");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[13]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select><br/>
    <br/>
    <b>Taille</b> (cm)
    <br/>
    <input type="text" size="10" name="taille" value="<?php echo $results[14]; ?>"/>
    <br/>
    <br/>
    <b>Condition capture</b>
    <br/>
    <select name="t_capture">
    <option value="">None</option>
    <?php
    $result = pg_query("SELECT id, condition FROM trawlers.t_condition");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[15]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select><br/>
    <br/>
    <b>Condition relache</b>
    <br/>
    <select name="t_relache">
    <option value="">None</option>
    <?php
    $result = pg_query("SELECT id, condition FROM trawlers.t_condition");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[16]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Pr&eacute;l&egrave;vement code</b>
    <br/>
    <input type="text" size="10" name="preleve" value="<?php echo $results[17]; ?>"/>
    <br/>
    <br/>
    <b>Camera ID</b>
    <br/>
    <input type="text" size="20" name="camera" value="<?php echo $results[18]; ?>"/>
    <br/>
    <br/>
    <b>Photo ID</b>
    <br/>
    <input type="text" size="20" name="photo" value="<?php echo $results[19]; ?>"/>
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" value="<?php echo $results[20];?>" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
} else if ($table == 'captures_tortue') {

    if ($_POST['submit'] == "Enregistrer") {

        $maree = $_POST['maree'];
        $date = $_POST['date'];
        $heure = $_POST['heure'];
        $id_species = $_POST['id_species'];
        $n_ind = $_POST['n_ind'];
        $t_sex = $_POST['t_sex'];
        $length = $_POST['length'];
        $width = $_POST['width'];
        $position_1 = $_POST['position_1'];
        $code_1 = $_POST['code_1'];
        $position_2 = $_POST['position_2'];
        $code_2 = $_POST['code_2'];
        $resumation = $_POST['resumation'];
        $resumation_res = $_POST['resumation_res'];
        $t_capture = $_POST['t_capture'];
        $t_relache = $_POST['t_relache'];
        $preleve = $_POST['preleve'];
        $camera = $_POST['camera'];
        $photo = $_POST['photo'];
        $remarque = $_POST['remarque'];

        $q_id = "SELECT id FROM trawlers.route_accidentelle WHERE maree = '$maree' AND date = '$date' AND heure = '$heure'";
        $id_route = pg_fetch_row(pg_query($q_id))[0];

        #print $q_id;

        $query = "INSERT INTO trawlers.captures_tortue "
            . "(datetime, username, id_route, maree, date, heure, id_species, n_ind, t_sex, length, width, ring, position_1, code_1, position_2, code_2, t_capture, t_relache, resumation, resumation_res, preleve, camera, photo, remarque) "
            . "VALUES (now(), '$username', '$id_route', '$maree', '$date', '$heure', '$id_species', '$n_ind', '$t_sex', '$length', '$width', '$ring', '$position_1', '$code_1', '$position_2', '$code_2', '$t_capture', '$t_relache', '$resumation', '$resumation_res', '$preleve', '$camera', '$photo', '$remarque')";

        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        } else {
            #print $query;
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_form_trawlers.php");
        }

        $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Mar&eacute;e</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','trawlers.route_accidentelle')">
    <option value="none">None</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route_accidentelle ORDER BY maree");
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
    <b>Date</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','trawlers.route_accidentelle')">
    <?php
    $result = pg_query("SELECT DISTINCT date FROM trawlers.route_accidentelle  WHERE maree = '$results[4]' ORDER BY date");
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
    <b>Heure</b>
    <br/>
    <select id="time" name="time">
    <?php
    $result = pg_query("SELECT DISTINCT heure FROM trawlers.route_accidentelle  WHERE maree = '$results[4]' AND date = '$results[5]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[6]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>

    <br/>
    <br/>
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.captures_tortue ON fishery.species.id = trawlers.captures_tortue.id_species  WHERE species IS NOT NULL ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
           print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Nombre individue</b>
    <br/>
    <input type="text" size="5" name="n_ind" value="<?php echo $results[12]; ?>"/>
    <br/>
    <br/>
    <b>Sexe</b>
    <br/>
    <select name="t_sex">
    <option value="">None</option>
    <?php
    $result = pg_query("SELECT id, sex FROM trawlers.t_sex ORDER BY sex");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[13]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select><br/>
    <br/>
    <b>Longueur</b>
    <br/>
    <input type="text" size="10" name="length" value="<?php echo $results[14]; ?>"/>
    <br/>
    <br/>
    <b>Largeur</b>
    <br/>
    <input type="text" size="10" name="width" value="<?php echo $results[15]; ?>"/>
    <br/>
    <br/>
    <b>Nouvelle bague</b>
    <br/>
    <input type="text" size="10" name="ring" value="<?php echo $results[16]; ?>"/>
    <br/>
    <br/>
    <b>Code bague 1</b>
    <br/>
    <input type="text" size="10" name="code_1" value="<?php echo $results[17]; ?>"/>
    <br/>
    <br/>
    <b>Position bague 1</b>
    <br/>
    <input type="text" size="10" name="position_1" value="<?php echo $results[18]; ?>"/>
    <br/>
    <br/>
    <b>Code bague 2</b>
    <br/>
    <input type="text" size="10" name="code_2" value="<?php echo $results[19]; ?>"/>
    <br/>
    <br/>
    <b>Position bague 2</b>
    <br/>
    <input type="text" size="10" name="position_2" value="<?php echo $results[20]; ?>"/>
    <br/>
    <br/>
    <b>Condition capture</b>
    <br/>
    <select name="t_capture">
    <option value="">None</option>
    <?php
    $result = pg_query("SELECT id, condition FROM trawlers.t_condition ORDER BY condition");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[21]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select><br/>
    <br/>
    <b>Condition relache</b>
    <br/>
    <select name="t_relache">
    <option value="">None</option>
    <?php
    $result = pg_query("SELECT id, condition FROM trawlers.t_condition ORDER BY condition");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[22]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Tentative de r&eacute;animation</b>
    <br/>
    Oui<input type="radio" name="resumation" value="TRUE" <?php if($results[23] == 't') {print "checked";} ?>/><br/>
    No<input type="radio" name="resumation" value="FALSE" <?php if($results[23] == 'f') {print "checked";} ?>/><br/>
    Inconnu<input type="radio" name="resumation" value="" <?php if($results[23] == '') {print "checked";} ?>/>
    <br/>
    <br/>
    <b>Resultat tentative de r&eacute;animation</b>
    <br/>
    Oui<input type="radio" name="resumation_res" value="TRUE" <?php if($results[24] == 't') {print "checked";} ?>/><br/>
    No<input type="radio" name="resumation_res" value="FALSE" <?php if($results[24] == 'f') {print "checked";} ?>/><br/>
    Inconnu<input type="radio" name="resumation_res" value="" <?php if($results[24] == '') {print "checked";} ?>/>
    <br/>
    <br/>
    <b>Pr&eacute;l&egrave;vement code</b>
    <br/>
    <input type="text" size="10" name="preleve" value="<?php echo $results[25]; ?>"/>
    <br/>
    <br/>
    <b>Camera ID</b>
    <br/>
    <input type="text" size="20" name="camera" value="<?php echo $results[26]; ?>"/>
    <br/>
    <br/>
    <b>Photo ID</b>
    <br/>
    <input type="text" size="20" name="photo" value="<?php echo $results[27]; ?>"/>
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" value="<?php echo $results[28];?>" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
}
} else {
    msg_noaccess();
}

foot();
