<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'catches';

$username = $_SESSION['username'];

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
if ($_GET['action'] != "") {$_SESSION['path'][2] = $_GET['action'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];
$action = $_SESSION['path'][2];

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if (right_write($_SESSION['username'],10,2)) {
  print "<h2>".label2name($source)." ".label2name($table)."</h2>";

  if ($table == 'maree') {

    //se submit = go!
    if ($_POST['submit'] == "Enregistrer") {

      #obs_name, t_mission, date_d, time_d, t_site_d, date_r, time_r, t_site_r, n_deb, zone, id_pirogue, obs_maree.immatriculation, "
      #. "engine, t_gear_1, length_1, height_1, mesh_min_1, mesh_max_1, t_gear_2, length_2, height_2, mesh_min_2, mesh_max_2, "
      #. "t_gear_3, length_3, height_3, mesh_min_3, mesh_max_3

      if ($_FILES['kml_file']['tmp_name'] != "") {

        $filename = $_FILES['kml_file']['tmp_name'];
        $filename_out = uniqid($username);
        # generate shapefiles

        exec("cp $filename ./files/tracks/$filename_out.kml");
        exec("chmod 0777 ./files/tracks/$filename_out.kml");

        $xml = file_get_contents($filename);

        $string = split("coordinates",$xml)[1];
        $string = str_replace([">","</"],"",$string);
        $coords_array = split(" ",$string);

        $coords = "";

        foreach ($coords_array as $coord) {
          $coords = $coords . str_replace(","," ",$coord) . ",";
        }
        $coords = rtrim($coords,",");
        $gps_track = "'LINESTRING(" . $coords . ")',4326";
        $gps_file = "./tracks/$filename_out.kml";

      }

      $username = $_SESSION['username'];
      $obs_name = htmlspecialchars($_POST['obs_name'],ENT_QUOTES);
      $t_mission = $_POST['t_mission'];
      $date_d = $_POST['date_d'];
      $date_r = $_POST['date_r'];
      $time_d = $_POST['time_d'];
      $time_r = $_POST['time_r'];
      $t_site_d = $_POST['t_site_d'];
      $t_site_r = $_POST['t_site_r'];
      $n_deb = htmlspecialchars($_POST['n_deb'],ENT_QUOTES);
      $zone = htmlspecialchars($_POST['zone'],ENT_QUOTES);
      $engine = htmlspecialchars($_POST['engine'],ENT_QUOTES);
      $id_pirogue = $_POST['id_pirogue'];

      if ($id_pirogue == '') {
        $t_immatriculation = $_POST['t_immatriculation'];
        $reg_num = htmlspecialchars($_POST['reg_num'],ENT_QUOTES);
        $reg_year = htmlspecialchars($_POST['reg_year'],ENT_QUOTES);
        $immatriculation = $t_immatriculation.". ".$reg_num."/".$reg_year;
        $nom_pir = htmlspecialchars($_POST['nom_pir'],ENT_QUOTES);
      } else {
        $immatriculation = '';
        $nom_pir = '';
      }

      $t_gear_1 = $_POST['t_gear_1'];
      $mesh_min_1 = htmlspecialchars($_POST['mesh_min_1'],ENT_QUOTES);
      $mesh_max_1 = htmlspecialchars($_POST['mesh_max_1'],ENT_QUOTES);
      $length_1 = htmlspecialchars($_POST['length_1'],ENT_QUOTES);
      $height_1 = htmlspecialchars($_POST['height_1'],ENT_QUOTES);

      $t_gear_2 = $_POST['t_gear_2'];
      $mesh_min_2 = htmlspecialchars($_POST['mesh_min_2'],ENT_QUOTES);
      $mesh_max_2 = htmlspecialchars($_POST['mesh_max_2'],ENT_QUOTES);
      $length_2 = htmlspecialchars($_POST['length_2'],ENT_QUOTES);
      $height_2 = htmlspecialchars($_POST['height_2'],ENT_QUOTES);

      $t_gear_3 = $_POST['t_gear_3'];
      $mesh_min_3 = htmlspecialchars($_POST['mesh_min_3'],ENT_QUOTES);
      $mesh_max_3 = htmlspecialchars($_POST['mesh_max_3'],ENT_QUOTES);
      $length_3 = htmlspecialchars($_POST['length_3'],ENT_QUOTES);
      $height_3 = htmlspecialchars($_POST['height_3'],ENT_QUOTES);

      $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);

      $query = "INSERT INTO artisanal_catches.obs_maree "
      . "(username, datetime, obs_name, t_mission, date_d, time_d, t_site_d, date_r, time_r, t_site_r, n_deb, zone, id_pirogue, immatriculation, nom_pir, "
      . "engine, t_gear_1, length_1, height_1, mesh_min_1, mesh_max_1, t_gear_2, length_2, height_2, mesh_min_2, mesh_max_2, "
      . "t_gear_3, length_3, height_3, mesh_min_3, mesh_max_3, comments, gps_track) "
      . "VALUES ('$username', NOW(), '$obs_name', '$t_mission', '$date_d', '$time_d', '$t_site_d', '$date_r', '$time_r', '$t_site_r', '$n_deb', "
      . "'$zone', '$id_pirogue', '$immatriculation', '$nom_pir', '$engine', '$t_gear_1', '$mesh_max_1', '$mesh_min_1', '$length_1', '$height_1', "
      . "'$t_gear_2', '$mesh_max_2', '$mesh_min_2', '$length_2', '$height_2', "
      . "'$t_gear_3', '$mesh_max_3', '$mesh_min_3', '$length_3', '$height_3', '$comments', '$gps_track' );";

      $query = str_replace('\'-- \'', 'NULL', $query);
      $query = str_replace('\'\'', 'NULL', $query);

      //print $query;

      if(!pg_query($query)) {
        echo "<p>".$query,"</p>";
        msg_queryerror();
        foot();
        die();
      } else {
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=artisanal/catches/input_catches_obs.php?source=artisanal&table=catches");
      }

      //header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=artisanal/input_records.php");

      $controllo = 1;
    }

    if (!$controllo) {
      ?>

      <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">
        <b>Nom du collecteur</b>
        <br/>
        <input type="text" size="20" name="obs_name" value="<?php echo $obs_name;?>" />
        <br/>
        <br/>
        <b>Type de mission</b>
        <br/>
        <select name="t_mission">
          <?php
          $result = pg_query("SELECT * FROM artisanal_catches.t_mission ORDER BY mission");
          while($row = pg_fetch_row($result)) {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
          ?>
        </select>
        <br/>
        <br/>
        <b>Date de d&eacute;part</b>
        <br/>
        <input type="date" size="30" name="date_d" value="<?php echo $results[4];?>" />
        <br/>
        <br/>
        <b>Heure de d&eacute;part</b>
        <br/>
        <input type="time" size="30" name="time_d" value="<?php echo $results[4];?>" />
        <br/>
        <br/>
        <b>D&eacute;barcad&egrave;re de d&eacute;part</b>
        <br/>
        <select name="t_site_d" class="chosen-select" >
          <?php
          $result = pg_query("SELECT * FROM artisanal.t_site_obb ORDER BY site");
          while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[10]) {
              print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
              print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
          }
          ?>
        </select>
        <br/>
        <br/>

        <b>Date de retour</b>
        <br/>
        <input type="date" size="30" name="date_r" value="<?php echo $results[4];?>" />
        <br/>
        <br/>
        <b>Heure de retour</b>
        <br/>
        <input type="time" size="30" name="time_r" value="<?php echo $results[4];?>" />
        <br/>
        <br/>
        <b>D&eacute;barcad&egrave;re de retour</b>
        <br/>
        <select name="t_site_r" class="chosen-select" >
          <?php
          $result = pg_query("SELECT * FROM artisanal.t_site_obb ORDER BY site");
          while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[10]) {
              print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
              print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
          }
          ?>
        </select>
        <br/>
        <br/>

        <b>Numero debarqument</b>
        <br/>
        <input type="text" size="3" name="n_deb" value="<?php echo $results[4];?>" />
        <br/>
        <br/>

        <b>Zone de peche</b>
        <br/>
        <input type="text" size="20" name="zone" value="<?php echo $results[4];?>" />
        <br/>
        <br/>

        <b>D&eacute;tails Pirogue</b>
        <br/>
        <select name="id_pirogue"  class="chosen-select" onchange="java_script_:show(this.options[this.selectedIndex].value,'pir_id')">
          <option value=''>PAS DANS LA LISTE</option>
          <?php
          $result = pg_query("SELECT id, name, immatriculation FROM artisanal.pirogue ORDER BY name");
          while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[7]) {
              print "<option value=\"$row[0]\" selected=\"selected\">".$row[2]." - ".$row[1]."</option>";
            } else {
              print "<option value=\"$row[0]\">".$row[2]." - ".$row[1]."</option>";
            }
          }
          ?>
        </select>
        <br/>
        <br/>
        <div class="pir_id" <?php if($results[7] != "") {print 'style="display:none"';} ?>>
          <b>Num&eacute;ro d'immatriculation</b> [format: L. 123/18]
          <br/>
          <select name="t_immatriculation">
            <?php
            $result = pg_query("SELECT * FROM artisanal.t_immatriculation ORDER BY immatriculation");
            while($row = pg_fetch_row($result)) {
              if ($row[1] == $t_immatriculation) {
                print "<option value=\"$row[1]\" selected>".$row[1]."</option>";
              } else {
                print "<option value=\"$row[1]\">".$row[1]."</option>";
              }
            }
            ?>
          </select>
          <input type="text" size="5" name="reg_num" value="<?php echo $reg_num;?>" /> /
          <input type="text" size="5" name="reg_year" value="<?php echo $reg_year;?>" />
          <br/>
          <br/>
          <b>Nom pirogue</b>
          <br/>
          <input type="text" size="15" name="nom_pir" value="<?php echo $reg_year;?>" />
          <br/>
          <br/>

        </div>

        <b>Puissance moteur</b> [CV]
        <br/>
        <input type="text" size="6" name="engine" value="<?php echo $results[13];?>" />
        <br/>
        <br/>

        <fieldset class="border">
          <legend>D&eacute;tails Engin Peche 1</legend>
          <b>Engin de peche</b>
          <br/>
          <select name="t_gear_1">
            <?php
            $result = pg_query("SELECT * FROM artisanal_catches.t_gear ORDER BY t_gear");
            print "<option value=''>Aucun</option>";

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
          <b>Longueur filet</b> [m]
          <br/>
          <input type="text" size="4" name="length_1" value="<?php echo $results[13];?>" />
          <br/>
          <br/>
          <b>Hauteur filet</b> [m]
          <br/>
          <input type="text" size="4" name="height_1" value="<?php echo $results[13];?>" />
          <br/>
          <br/>
          <b>Taille de la maille</b> [de cote en mm]
          <br/>
          min: <input type="text" size="4" name="mesh_min_1" value="<?php echo $results[11];?>" />
          max: <input type="text" size="4" name="mesh_max_1" value="<?php echo $results[12];?>" />
          <br/>
          <br/>
        </fieldset>

        <br/>

        <fieldset class="border">
          <legend>D&eacute;tails Engin Peche 2</legend>
          <b>Engin de peche</b>
          <br/>
          <select name="t_gear_2">
            <?php
            $result = pg_query("SELECT * FROM artisanal_catches.t_gear ORDER BY t_gear");
            print "<option value=''>Aucun</option>";

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
          <b>Longueur filet</b> [m]
          <br/>
          <input type="text" size="4" name="length_2" value="<?php echo $results[13];?>" />
          <br/>
          <br/>
          <b>Hauteur filet</b> [m]
          <br/>
          <input type="text" size="4" name="height_2" value="<?php echo $results[13];?>" />
          <br/>
          <br/>
          <b>Taille de la maille</b> [de cote en mm]
          <br/>
          min: <input type="text" size="4" name="mesh_min_2" value="<?php echo $results[11];?>" />
          max: <input type="text" size="4" name="mesh_max_2" value="<?php echo $results[12];?>" />
          <br/>
          <br/>
        </fieldset>

        <br/>

        <fieldset class="border">
          <legend>D&eacute;tails Engin Peche 3</legend>
          <b>Engin de peche</b>
          <br/>
          <select name="t_gear_3">
            <?php
            $result = pg_query("SELECT * FROM artisanal_catches.t_gear ORDER BY t_gear");
            print "<option value=''>Aucun</option>";
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
          <b>Longueur filet</b> [m]
          <br/>
          <input type="text" size="4" name="length_3" value="<?php echo $results[13];?>" />
          <br/>
          <br/>
          <b>Hauteur filet</b> [m]
          <br/>
          <input type="text" size="4" name="height_3" value="<?php echo $results[13];?>" />
          <br/>
          <br/>
          <b>Taille de la maille</b> [de cote en mm]
          <br/>
          min: <input type="text" size="4" name="mesh_min_3" value="<?php echo $results[11];?>" />
          max: <input type="text" size="4" name="mesh_max_3" value="<?php echo $results[12];?>" />
          <br/>
          <br/>
        </fieldset>
        <br/>
        <b>Charger le trac&eacute; GPS</b> (KML format)
        <br/>
        <input type="file" size="40" name="kml_file" />
        <br/>
        <br/>

        <b>Commentaires</b>
        <br/>
        <textarea cols=30 rows=3 name="comments"><?php echo $results[13];?></textarea>
        <br/>
        <br/>
        <input type="submit" value="Enregistrer" name="submit"/>
      </form>

      <?php
    }
  } elseif ($table == 'actions') {
    //se submit = go!
    if ($_POST['submit'] == "Enregistrer") {

      #obs_action.id, obs_action.datetime, obs_action.username, id_maree, wpt, t_gear, t_prise, t_group, boarded, comments

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
        $point = "ST_GeomFromText('POINT($lon $lat)',4326)";
      }

      $username = $_SESSION['username'];
      $id_maree = $_POST['id_maree'];
      $wpt = htmlspecialchars($_POST['wpt'],ENT_QUOTES);
      $date_a = $_POST['date_a'];
      $time_a = $_POST['time_a'];
      $t_gear = $_POST['t_gear'];
      $boarded = $_POST['boarded'];
      $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);

      $query = "INSERT INTO artisanal_catches.obs_action "
      . "(username, datetime, id_maree, wpt, date_a, time_a, t_gear, boarded, comments, location) "
      . "VALUES ('$username', NOW(), '$id_maree', '$wpt', '$date_a', '$time_a', '$t_gear', '$boarded', '$comments', $point);";

      $query = str_replace('\'-- \'', 'NULL', $query);
      $query = str_replace('\'\'', 'NULL', $query);

      //print $query;

      if(!pg_query($query)) {
        echo "<p>".$query,"</p>";
        msg_queryerror();
        foot();
        die();
      } else {
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=artisanal/catches/input_catches_obs.php?source=obs_catches&table=actions");
      }

      $controllo = 1;
    }

    if (!$controllo) {
      #obs_action.id, obs_action.datetime, obs_action.username, id_maree, wpt, t_gear, t_prise, t_group, boarded, comments
      ?>

      <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">
        <b>Maree</b>
        <br/>
        <select name="id_maree">
          <?php
          $result = pg_query("SELECT obs_maree.id, concat(obs_maree.immatriculation, pirogue.immatriculation), date_d FROM artisanal_catches.obs_maree LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue ORDER BY date_d");
          while($row = pg_fetch_row($result)) {
            print "<option value=\"$row[0]\">".$row[1]." / ".$row[2]."</option>";
          }
          ?>
        </select>
        <br/>
        <br/>
        <b>ID Waypoint</b>
        <br/>
        <input type="text" size="10" name="wpt" value="<?php echo $results[4];?>" />
        <br/>
        <br/>
        <b>Date</b>
        <br/>
        <input type="date" size="10" name="date_a" value="<?php echo $results[4];?>" />
        <br/>
        <br/>
        <b>Heure</b>
        <br/>
        <input type="time" size="10" name="time_a" value="<?php echo $results[4];?>" />
        <br/>
        <br/>
        <b>Engin de peche utilize</b>
        <br/>
        <select name="t_gear">
          <?php
          $result = pg_query("SELECT * FROM artisanal_catches.t_gear ORDER BY gear");
          while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[10]) {
              print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
              print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
          }
          ?>
        </select>
        <br/>
        <br/>

        <b>Capture embarque?</b>
        <br/>
        <input type="radio" name="boarded" value="true" checked>
        <label for="true">Oui</label>
        <input type="radio" name="boarded" value="false">
        <label for="false">Non</label>
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
        <input type="text" size="5" name="lat_deg_dms" value="<?php print $lat_deg_dms_d;?>"/>&deg;
        <input type="text" size="5" name="lat_min_dms" value="<?php print $lat_min_dms_d;?>"/>&prime;
        <input type="text" size="5" name="lat_sec_dms" value="<?php print $lat_sec_dms_d;?>"/>&prime;&prime;
      </div>

      <div class="DDMM">
        <b>Latitude</b><br/>
        <input type="text" size="5" name="lat_deg_dm" value="<?php print $lat_deg_dm_d;?>"/>&deg;
        <input type="text" size="5" name="lat_min_dm" value="<?php print $lat_min_dm_d;?>"/>&prime;
      </div>

      <div class="DD" style="display:none">
        <b>Latitude</b><br/>
        <input type="text" size="5" name="lat_deg_d"  value="<?php print $lat_deg_d_d;?>"/>&deg;
      </div>

      <select name="NS">
        <option value="N" <?php if($NS_d == 'N') {print 'selected';} ?>>N</option>
        <option value="S" <?php if($NS_d == 'S') {print 'selected';} ?>>S</option>
      </select>

      <div class="DDMMSS" style="display:none">
        <b>Longitude</b><br/>
        <input type="text" size="5" name="lon_deg_dms" value="<?php print $lon_deg_dms_d;?>"/>&deg;
        <input type="text" size="5" name="lon_min_dms" value="<?php print $lon_min_dms_d;?>"/>&prime;
        <input type="text" size="5" name="lon_sec_dms" value="<?php print $lon_sec_dms_d;?>"/>&prime;&prime;
      </div>

      <div class="DDMM">
        <b>Longitude</b><br/>
        <input type="text" size="5" name="lon_deg_dm" value="<?php print $lon_deg_dm_d;?>"/>&deg;
        <input type="text" size="5" name="lon_min_dm" value="<?php print $lon_min_dm_d;?>"/>&prime;
      </div>

      <div class="DD" style="display:none">
        <b>Longitude</b><br/>
        <input type="text" size="5" name="lon_deg_d"  value="<?php print $lon_deg_d_d;?>"/>&deg;
      </div>

      <select name="EO">
        <option value="E" <?php if($EO_d == 'E') {print 'selected';} ?> >E</option>
        <option value="O" <?php if($EO_d == 'O') {print 'selected';} ?>>O</option>
      </select>
      <br/>
      <br/>
      <b>Commentaires</b>
      <br/>
      <textarea cols=30 rows=3 name="comments"><?php echo $results[13];?></textarea>
      <br/>
      <br/>

      <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <?php
  }
} elseif ($table == 'sharks') {

  if ($_POST['submit'] == "Enregistrer") {

    # id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, LT, LA, wgt, t_status, t_action, photo_data, comments
    $photo_data = upload_photo($_FILES['photo_file']['tmp_name']);

    $username = $_SESSION['username'];
    $id_maree = $_POST['id_maree'];
    $wpt = htmlspecialchars($_POST['wpt'],ENT_QUOTES);

    $query = "SELECT id FROM artisanal_catches.obs_action WHERE id_maree = '$id_maree' AND wpt = '$wpt'";
    $id_action = pg_fetch_row(pg_query($query))[0];

    $id_species = $_POST['id_species'];

    $t_sex = $_POST['t_sex'];
    $t_maturity = $_POST['t_maturity'];
    $LD = htmlspecialchars($_POST['LD'],ENT_QUOTES);
    $LT = htmlspecialchars($_POST['LT'],ENT_QUOTES);
    $wgt = htmlspecialchars($_POST['wgt'],ENT_QUOTES);
    $t_status = $_POST['t_status'];
    $t_action = $_POST['t_action'];

    $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);

    $query = "INSERT INTO artisanal_catches.obs_sharks "
    . "(username, datetime, id_maree, id_action, id_species, t_sex, t_maturity, LT, LD, wgt, t_status, t_action, photo_data, comments) "
    . "VALUES ('$username', NOW(), '$id_maree', '$id_action', '$id_species', '$t_sex', '$t_maturity', '$LT', '$LD', '$wgt', '$t_status', '$t_action', '$photo_data', '$comments');";

    $query = str_replace('\'-- \'', 'NULL', $query);
    $query = str_replace('\'\'', 'NULL', $query);

    //print $query;

    if(!pg_query($query)) {
      echo "<p>".$query,"</p>";
      msg_queryerror();
      foot();
      die();
    } else {
      header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=artisanal/catches/input_catches_obs.php?source=obs_catches&table=sharks");
    }

    $controllo = 1;
  }

  if (!$controllo) {
    # id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, LT, LA, wgt, t_status, t_action, photo_file, photo_data

    ?>

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">

      <b>Maree</b>
      <br/>
      <select name="id_maree" id="id_maree" onchange="menu_pop_1('id_maree','wpt','id_maree','wpt','artisanal_catches.obs_action')">
        <?php
        $result = pg_query("SELECT obs_maree.id, concat(obs_maree.immatriculation, pirogue.immatriculation), date_d FROM artisanal_catches.obs_maree LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue ORDER BY date_d");
        while($row = pg_fetch_row($result)) {
          print "<option value=\"$row[0]\">".$row[1]." / ".$row[2]."</option>";
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Action peche</b>
      <br/>
      <select name="wpt" id="wpt">
        <option  value="none">Veuillez choisir ci-dessus</option>
        <?php
        $result = pg_query("SELECT wpt FROM artisanal_catches.obs_action WHERE id_navire = '$results[3]' ORDER BY wpt");
        while($row = pg_fetch_row($result)) {
          print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Espece</b>
      <br/>
      <select name="id_species" class="chosen-select">
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species  FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
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
      <b>Sex</b>
      <br/>
      <select name="t_sex">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_sex ORDER BY sex");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
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
        $result = pg_query("SELECT * FROM artisanal_catches.t_maturity ORDER BY maturity");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Poids</b> [kg]
      <br/>
      <input type="text" size="10" name="wgt" value="<?php echo $results[4];?>" />
      <br/>
      <br/>
      <b>LD</b> [cm]
      <br/>
      <input type="text" size="10" name="LD" value="<?php echo $results[4];?>" />
      <br/>
      <br/>
      <b>LT</b> [cm]
      <br/>
      <input type="text" size="10" name="LT" value="<?php echo $results[4];?>" />
      <br/>
      <br/>
      <b>Status</b>
      <br/>
      <select name="t_status">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_status ORDER BY status");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Action</b>
      <br/>
      <select name="t_action">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_action ORDER BY action");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Ajouter photo</b> (format jpg/png/gif, max 500 KB)
      <br/>
      <input type="file" size="40" name="photo_file" onchange="ValidateSize(this)" />
      <br/>
      <br/>
      <b>Commentaires</b>
      <br/>
      <textarea cols=30 rows=3 name="comments"><?php echo $results[13];?></textarea>
      <br/>
      <br/>

      <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <?php
  }
} elseif ($table == 'turtles') {
  //se submit = go!
  if ($_POST['submit'] == "Enregistrer") {

    #id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, bague, integrity, fibrop, epibionte, LT, wgt, t_status, t_action, photo_data, comments

    $photo_data = upload_photo($_FILES['photo_file']['tmp_name']);

    $username = $_SESSION['username'];
    $id_maree = $_POST['id_maree'];
    $wpt = htmlspecialchars($_POST['wpt'],ENT_QUOTES);

    $query = "SELECT id FROM artisanal_catches.obs_action WHERE id_maree = '$id_maree' AND wpt = '$wpt'";
    $id_action = pg_fetch_row(pg_query($query))[0];

    $id_species = $_POST['id_species'];

    $t_sex = $_POST['t_sex'];
    $t_maturity = $_POST['t_maturity'];
    $bague = htmlspecialchars($_POST['bague'],ENT_QUOTES);
    $integrity = htmlspecialchars($_POST['integrity'],ENT_QUOTES);
    $fibrop = htmlspecialchars($_POST['fibrop'],ENT_QUOTES);
    $epibionte = htmlspecialchars($_POST['epibionte'],ENT_QUOTES);
    $LT = htmlspecialchars($_POST['LT'],ENT_QUOTES);
    $wgt = htmlspecialchars($_POST['wgt'],ENT_QUOTES);
    $t_status = $_POST['t_status'];
    $t_action = $_POST['t_action'];
    $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);

    $query = "INSERT INTO artisanal_catches.obs_turtles "
    . "(username, datetime, id_maree, id_action, id_species, t_sex, t_maturity, bague, integrity, fibrop, epibionte, LT, wgt, t_status, t_action, photo_data, comments) "
    . "VALUES ('$username', NOW(), '$id_maree', '$id_action', '$id_species', '$t_sex', '$t_maturity', '$bague', '$integrity', '$fibrop', '$epibionte', '$LT', '$wgt', '$t_status', '$t_action', '$photo_data', '$comments');";

    $query = str_replace('\'-- \'', 'NULL', $query);
    $query = str_replace('\'\'', 'NULL', $query);

    //print $query;

    if(!pg_query($query)) {
      echo "<p>".$query,"</p>";
      msg_queryerror();
      foot();
      die();
    } else {
      header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=artisanal/catches/input_catches_obs.php?source=obs_catches&table=turtles");
    }

    $controllo = 1;
  }

  if (!$controllo) {
    # id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, LT, LA, wgt, t_status, t_action, photo_file, photo_data

    ?>

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">

      <b>Maree</b>
      <br/>
      <select name="id_maree" id="id_maree" onchange="menu_pop_1('id_maree','wpt','id_maree','wpt','artisanal_catches.obs_action')">
        <?php
        $result = pg_query("SELECT obs_maree.id, concat(obs_maree.immatriculation, pirogue.immatriculation), date_d FROM artisanal_catches.obs_maree LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue ORDER BY date_d");
        while($row = pg_fetch_row($result)) {
          print "<option value=\"$row[0]\">".$row[1]." / ".$row[2]."</option>";
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Action peche</b>
      <br/>
      <select name="wpt" id="wpt">
        <option  value="none">Veuillez choisir ci-dessus</option>
        <?php
        $result = pg_query("SELECT wpt FROM artisanal_catches.obs_action WHERE id_navire = '$results[3]' ORDER BY wpt");
        while($row = pg_fetch_row($result)) {
          print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Espece</b>
      <br/>
      <select name="id_species" class="chosen-select">
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species  FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
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
      <b>Sex</b>
      <br/>
      <select name="t_sex">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_sex ORDER BY sex");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
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
        $result = pg_query("SELECT * FROM artisanal_catches.t_maturity ORDER BY maturity");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Code bague</b>
      <br/>
      <input type="text" size="30" name="bague" value="<?php echo $results[4];?>" />
      <br/>
      <br/>
      <b>Description integrite</b>
      <br/>
      <input type="text" size="30" name="integrity" value="<?php echo $results[4];?>" />
      <br/>
      <br/>
      <b>Presence fibropapillomatosis</b>
      <br/>
      <input type="text" size="30" name="fibrop" value="<?php echo $results[4];?>" />
      <br/>
      <br/>
      <b>Presence epibiontes</b>
      <br/>
      <input type="text" size="30" name="epibionte" value="<?php echo $results[4];?>" />
      <br/>
      <br/>
      <b>LT</b> [cm]
      <br/>
      <input type="text" size="10" name="LT" value="<?php echo $results[4];?>" />
      <br/>
      <br/>
      <b>Poids</b> [kg]
      <br/>
      <input type="text" size="10" name="wgt" value="<?php echo $results[4];?>" />
      <br/>
      <br/>
      <b>Status</b>
      <br/>
      <select name="t_status">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_status ORDER BY status");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Action</b>
      <br/>
      <select name="t_action">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_action ORDER BY action");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Ajouter photo</b> (format jpg/png/gif, max 500 KB)
      <br/>
      <input type="file" size="40" name="photo_file" onchange="ValidateSize(this)" />
      <br/>
      <br/>
      <b>Commentaires</b>
      <br/>
      <textarea cols=30 rows=3 name="comments"><?php echo $results[13];?></textarea>
      <br/>
      <br/>

      <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <?php
  }
} elseif ($table == 'mammals') {

  #id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, LT, wgt, t_status, t_action, photo_data, comments

  if ($_POST['submit'] == "Enregistrer") {

    $photo_data = upload_photo($_FILES['photo_file']['tmp_name']);

    $username = $_SESSION['username'];
    $id_maree = $_POST['id_maree'];
    $wpt = htmlspecialchars($_POST['wpt'],ENT_QUOTES);

    $query = "SELECT id FROM artisanal_catches.obs_action WHERE id_maree = '$id_maree' AND wpt = '$wpt'";
    $id_action = pg_fetch_row(pg_query($query))[0];

    $id_species = $_POST['id_species'];

    $t_sex = $_POST['t_sex'];
    $t_maturity = $_POST['t_maturity'];
    $LT = htmlspecialchars($_POST['LT'],ENT_QUOTES);
    $wgt = htmlspecialchars($_POST['wgt'],ENT_QUOTES);
    $t_status = $_POST['t_status'];
    $t_action = $_POST['t_action'];

    $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);

    $query = "INSERT INTO artisanal_catches.obs_mammals "
    . "(username, datetime, id_maree, id_action, id_species, t_sex, t_maturity, LT, wgt, t_status, t_action, photo_data, comments) "
    . "VALUES ('$username', NOW(), '$id_maree', '$id_action', '$id_species', '$t_sex', '$t_maturity', '$LT', '$wgt', '$t_status', '$t_action', '$photo_data', '$comments');";

    $query = str_replace('\'-- \'', 'NULL', $query);
    $query = str_replace('\'\'', 'NULL', $query);

    //print $query;

    if(!pg_query($query)) {
      echo "<p>".$query,"</p>";
      msg_queryerror();
      foot();
      die();
    } else {
      header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=artisanal/catches/input_catches_obs.php?source=obs_catches&table=mammals");
    }

    $controllo = 1;
  }

  if (!$controllo) {
    # id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, LT, LA, wgt, t_status, t_action, photo_file, photo_data

    ?>

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">

      <b>Maree</b>
      <br/>
      <select name="id_maree" id="id_maree" onchange="menu_pop_1('id_maree','wpt','id_maree','wpt','artisanal_catches.obs_action')">
        <?php
        $result = pg_query("SELECT obs_maree.id, concat(obs_maree.immatriculation, pirogue.immatriculation), date_d FROM artisanal_catches.obs_maree LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue ORDER BY date_d");
        while($row = pg_fetch_row($result)) {
          print "<option value=\"$row[0]\">".$row[1]." / ".$row[2]."</option>";
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Action peche</b>
      <br/>
      <select name="wpt" id="wpt">
        <option  value="none">Veuillez choisir ci-dessus</option>
        <?php
        $result = pg_query("SELECT wpt FROM artisanal_catches.obs_action WHERE id_navire = '$results[3]' ORDER BY wpt");
        while($row = pg_fetch_row($result)) {
          print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Espece</b>
      <br/>
      <select name="id_species" class="chosen-select">
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species  FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
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
      <b>Sex</b>
      <br/>
      <select name="t_sex">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_sex ORDER BY sex");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
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
        $result = pg_query("SELECT * FROM artisanal_catches.t_maturity ORDER BY maturity");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Poids</b> [kg]
      <br/>
      <input type="text" size="10" name="wgt" value="<?php echo $results[4];?>" />
      <br/>
      <br/>
      <b>LT</b> [cm]
      <br/>
      <input type="text" size="10" name="LT" value="<?php echo $results[4];?>" />
      <br/>
      <br/>
      <b>Status</b>
      <br/>
      <select name="t_status">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_status ORDER BY status");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Action</b>
      <br/>
      <select name="t_action">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_action ORDER BY action");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Ajouter photo</b> (format jpg/png/gif, max 500 KB)
      <br/>
      <input type="file" size="40" name="photo_file" onchange="ValidateSize(this)" />
      <br/>
      <br/>
      <b>Commentaires</b>
      <br/>
      <textarea cols=30 rows=3 name="comments"><?php echo $results[13];?></textarea>
      <br/>
      <br/>

      <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <?php
  }
} elseif ($table == 'fish') {
  if ($_POST['submit'] == "Enregistrer") {

    # id, datetime, username, id_maree, id_species, n_lot, perc, wgt

    $username = $_SESSION['username'];
    $id_maree = $_POST['id_maree'];

    $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);

    //print $query;

    for($i = 0; $i < sizeof($_POST['id_species']); $i++) {
      $id_species = $_POST['id_species'][$i];
      $wgt = htmlspecialchars($_POST['wgt'][$i],ENT_QUOTES);
      $perc = htmlspecialchars($_POST['perc'][$i],ENT_QUOTES);
      $n_lot = htmlspecialchars($_POST['n_lot'][$i],ENT_QUOTES);

      $query = "INSERT INTO artisanal_catches.obs_fish "
      . "(username, id_maree, id_species, wgt, n_lot, perc, comments) "
      . "VALUES ('$username', '$id_maree', '$id_species', '$wgt', '$n_lot', '$perc', '$comments');";

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

    ///

    header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=artisanal/catches/input_catches_obs.php?source=obs_catches&table=fish");

    $controllo = 1;
  }

  if (!$controllo) {
    # id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, LT, LA, wgt, t_status, t_action, photo_file, photo_data

    ?>

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">

      <b>Maree</b>
      <br/>
      <select name="id_maree" id="id_maree">
        <?php
        $result = pg_query("SELECT obs_maree.id, concat(obs_maree.immatriculation, pirogue.immatriculation), date_d FROM artisanal_catches.obs_maree LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue ORDER BY date_d");
        while($row = pg_fetch_row($result)) {
          print "<option value=\"$row[0]\">".$row[1]." / ".$row[2]."</option>";
        }
        ?>
      </select>
      <br/>
      <br/>

      <fieldset class="border">
        <legend>D&eacute;tails Capture</legend>
        <b>Espece</b>
        <br/>
        <select id="species" name="id_species[]" class="chosen-select">
          <?php
          $result = pg_query("SELECT DISTINCT id, family, genus, species, francaise FROM fishery.species ORDER BY francaise, species");
          while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[25]) {
              print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesCommon($row[4],$row[1],$row[2],$row[3])."</option>";
            } else {
              print "<option value=\"$row[0]\">".formatSpeciesCommon($row[4],$row[1],$row[2],$row[3])."</option>";
            }
          }
          ?>
        </select>
        <br/>
        <br/>
        <b>Numero du contenant</b>
        <br/>
        <input type="text" size="5" name="n_lot[]" value="<?php echo $ech;?>" />
        <br/>
        <br/>
        <b>Pourcentage du contenant</b>
        <br/>
        <input type="text" size="5" name="perc[]" value="<?php echo $ech;?>" />
        <br/>
        <br/>
        <b>Poids total contenant</b> [kg]
        <br/>
        <input type="text" size="5" name="wgt[]" value="<?php echo $ech;?>" />
        <br/>
        <br/>
      </fieldset>
      <br/>

      <script type='text/javascript'>
      var DivCapture = `<div class="capture">
      <fieldset class="border">
      <legend>D&eacute;tails Capture</legend>
      <b>Espece</b>
      <br/>
      <select id="species" name="id_species[]" class="chosen-select">
      <?php
      $result = pg_query("SELECT DISTINCT id, family, genus, species, francaise FROM fishery.species ORDER BY francaise, species");
      while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[25]) {
          print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesCommon($row[4],$row[1],$row[2],$row[3])."</option>";
        } else {
          print "<option value=\"$row[0]\">".formatSpeciesCommon($row[4],$row[1],$row[2],$row[3])."</option>";
        }
      }
      ?>
      </select>
      <br/>
      <br/>
      <b>Numero du contenant</b>
      <br/>
      <input type="text" size="5" name="n_lot[]" value="<?php echo $ech;?>" />
      <br/>
      <br/>
      <b>Pourcentage du contenant</b>
      <br/>
      <input type="text" size="5" name="perc[]" value="<?php echo $ech;?>" />
      <br/>
      <br/>
      <b>Poids total contenant</b> [kg]
      <br/>
      <input type="text" size="5" name="wgt[]" value="<?php echo $ech;?>" />
      <br/>
      <br/>
      </fieldset>
      <br/>
      </div>
      `

      function appendDivCapture() {
        $( ".container" ).append(DivCapture)
      }

      function removeDivCapture() {
        $( ".capture" ).last().remove()
      }

      </script>
      <div class="container">
      </div>
      <button type="button" onclick="appendDivCapture()">Ajouter Capture</button>
      <button type="button" onclick="removeDivCapture()">Supprimer Capture</button>
      <br/>
      <br/>
      <b>Commentaires</b>
      <br/>
      <textarea cols=30 rows=3 name="comments"><?php echo $results[13];?></textarea>
      <br/>
      <br/>

      <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <?php
  }
} elseif ($table == 'poids_taille') {

  // id , datetime, username, id_maree, id_species, t_maturity, t_measure, length, wgt, comments

  if ($_POST['submit'] == "Enregistrer") {

    $username = $_SESSION['username'];
    $id_maree = $_POST['id_maree'];
    $id_species = $_POST['id_species'];
    $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);

    for($i = 0; $i < sizeof($_POST['wgt']); $i++) {
      $t_maturity = htmlspecialchars($_POST['t_maturity'][$i],ENT_QUOTES);
      $t_measure = htmlspecialchars($_POST['t_measure'][$i],ENT_QUOTES);
      $length = htmlspecialchars($_POST['length'][$i],ENT_QUOTES);
      $wgt = htmlspecialchars($_POST['wgt'][$i],ENT_QUOTES);

      $query = "INSERT INTO artisanal_catches.obs_poids_taille "
      . "(username, datetime, id_maree, id_species, wgt, length, t_maturity, t_measure, comments) "
      . "VALUES ('$username', NOW(), '$id_maree', '$id_species', '$wgt', '$length', '$t_maturity', '$t_measure', '$comments');";

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

    header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=artisanal/catches/input_catches_obs.php?source=obs_catches&table=poids_taille");

    $controllo = 1;
  }

  if (!$controllo) {
    // id , datetime, username, id_maree, id_species, t_maturity, t_measure, length, wgt, comments
    ?>

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">
      <b>Maree</b>
      <br/>
      <select name="id_maree">
        <?php
        $result = pg_query("SELECT obs_maree.id, concat(obs_maree.immatriculation, pirogue.immatriculation), date_d FROM artisanal_catches.obs_maree LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue ORDER BY date_d");
        while($row = pg_fetch_row($result)) {
          print "<option value=\"$row[0]\">".$row[1]." / ".$row[2]."</option>";
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Espece</b>
      <br/>
      <select name="id_species" class="chosen-select">
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species  FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
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

      <fieldset class="border">
      <legend>D&eacute;tails Individue</legend>
      <b>Etat maturite</b>
      <br/>
      <select name="t_maturity[]">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_maturity ORDER BY maturity");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Type measure</b>
      <br/>
      <select name="t_measure[]">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_measure ORDER BY measure");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Longeur</b> [cm]
      <br/>
      <input type="text" size="5" name="length[]" value="<?php echo $ech;?>" />
      <br/>
      <br/>
      <b>Poids</b> [kg]
      <br/>
      <input type="text" size="5" name="wgt[]" value="<?php echo $ech;?>" />
      <br/>
      </fieldset>
      <br/>

      <script type='text/javascript'>
      var DivCapture = `<div class="capture">
      <fieldset class="border">
      <legend>D&eacute;tails Individue</legend>
      <b>Etat maturite</b>
      <br/>
      <select name="t_maturity[]">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_maturity ORDER BY maturity");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Type measure</b>
      <br/>
      <select name="t_measure[]">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_measure ORDER BY measure");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Longeur</b> [cm]
      <br/>
      <input type="text" size="5" name="length[]" value="<?php echo $ech;?>" />
      <br/>
      <br/>
      <b>Poids</b> [kg]
      <br/>
      <input type="text" size="5" name="wgt[]" value="<?php echo $ech;?>" />
      <br/>
      </fieldset>
      <br/>
      </div>
      `


      function appendDivCapture() {
        $( ".container" ).append(DivCapture)
      }

      function removeDivCapture() {
        $( ".capture" ).last().remove()
      }

      </script>
      <div class="container">
      </div>
      <button type="button" onclick="appendDivCapture()">Ajouter Individue</button>
      <button type="button" onclick="removeDivCapture()">Supprimer Individue</button>
      <br/>
      <br/>


      <b>Commentaires</b>
      <br/>
      <textarea cols=30 rows=3 name="comments"><?php echo $results[13];?></textarea>
      <br/>
      <br/>

      <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <?php
  }
}

} else {
  msg_noaccess();
}

foot();
