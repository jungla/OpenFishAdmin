<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'catches';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_id_species'] = $_POST['f_id_species'];
$_SESSION['filter']['f_id_maree'] = $_POST['f_id_maree'];
$_SESSION['filter']['f_observateur'] = $_POST['f_observateur'];

if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}
if ($_GET['f_id_maree'] != "") {$_SESSION['filter']['f_id_maree'] = $_GET['f_id_maree'];}
if ($_GET['f_observateur'] != "") {$_SESSION['filter']['f_observateur'] = $_GET['f_observateur'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
  print "<h2>".label2name($source)." ".label2name($table)."</h2>";

  $start = $_GET['start'];

  if (!isset($start) OR $start<0) $start = 0;

  $step = 50;

  ?>

  <form method="post" action="<?php echo $self;?>?source=artisanal&table=captures&action=show" enctype="multipart/form-data">
    <fieldset>
      <table id="no-border"><tr><td><b>Maree</b></td><td><b>Observateur</b></td><!--<td><b>Capture</b></td>--></tr>
        <tr>
          <td>
            <select name="f_id_maree" class="chosen-select" style="width:100%">
              <option value="obs_action.id_maree" selected="selected">Tous</option>
              <?php
              $result = pg_query("SELECT DISTINCT obs_action.id_maree, date_d, concat(pirogue.immatriculation, obs_maree.immatriculation) FROM artisanal_catches.obs_action "
              . "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_action.id_maree "
              . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
              . "ORDER BY date_d");
              while($row = pg_fetch_row($result)) {
                if ("'".$row[0]."'" == $_SESSION['filter']['f_id_maree']) {
                  print "<option value=\"'$row[0]'\" selected=\"selected\">$row[1] $row[2]</option>";
                } else {
                  print "<option value=\"'$row[0]'\">$row[1] $row[2]</option>";
                }
              }
              ?>
            </select>
          </td>
          <td>
            <input type="text" size="20" name="f_observateur" value="<?php echo $_SESSION['filter']['f_observateur']?>"/>
          </td>
          <!-- <td>
            <select name="f_t_mission" class="chosen-select" style="width:100%">
              <option value="obs_action.t_mission" selected="selected">Tous</option>
              <?php
              $result = pg_query("SELECT id, mission FROM artisanal_catches.t_mission ORDER BY mission");
              while($row = pg_fetch_row($result)) {
                if ($row[0] == $_SESSION['filter']['f_t_mission']) {
                  print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
                } else {
                  print "<option value=\"$row[0]\">".$row[1]."</option>";
                }
              }
              ?>
            </select>
          </td> -->
        </tr>
      </table>
      <input type="submit" name="Filter" value="filter" />
    </fieldset>
  </form>

  <br/>

  <table id="small">
    <tr align="center"><td></td>
      <td><b>Date & Utilisateur</b></td>
      <td><b>Maree</b></td>
      <td><b>Observateur</b></td>
      <td><b>WPT</b></td>
      <td><b>Date et heure</b></td>
      <td><b>Engin Peche</b></td>
      <td><b>Remonte</b></td>
      <td><b>Capture</b></td>
      <td><b>Point GPS</b></td>
      <td><b>Commentaires</b></td>
    </tr>

    <?php

    if ($_SESSION['filter']['f_id_maree'] != "" OR $_SESSION['filter']['f_id_species'] != "" OR $_SESSION['filter']['f_observateur'] != "") {

      //id, datetime,username, id_maree, wpt, t_gear, lat, lon, t_prise, t_group, boarded

      $_SESSION['start'] = 0;

      $query = "SELECT count(DISTINCT obs_action.id) FROM artisanal_catches.obs_action "
      . "WHERE obs_action.id_maree = ".$_SESSION['filter']['f_id_maree']." ";

      $pnum = pg_fetch_row(pg_query($query))[0];

      if ($_SESSION['filter']['f_immatriculation'] != "" OR $_SESSION['filter']['f_observateur'] != "") {
        $query = "SELECT obs_action.id, obs_action.datetime::date, obs_action.username, date_d, concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.id_pirogue, wpt, date_a, time_a, t_gear.gear, boarded, obs_action.comments, st_x(location), st_y(location), obs_maree.id, obs_maree.obs_name, "
        . " coalesce(similarity(obs_name, '".$_SESSION['filter']['f_observateur']."'),0) AS score "
        . "FROM artisanal_catches.obs_action "
        . "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_action.id_maree "
        . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
        . "LEFT JOIN artisanal_catches.t_gear ON artisanal_catches.t_gear.id = artisanal_catches.obs_action.t_gear "
        . "WHERE obs_action.id_maree = ".$_SESSION['filter']['f_id_maree']." "
        . "ORDER BY score DESC OFFSET $start LIMIT $step";
      } else {
        $query = "SELECT obs_action.id, obs_action.datetime::date, obs_action.username, date_d, concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.id_pirogue, wpt, date_a, time_a, t_gear.gear, boarded, obs_action.comments, st_x(location), st_y(location), obs_maree.id, obs_maree.obs_name "
        . "FROM artisanal_catches.obs_action "
        . "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_action.id_maree "
        . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
        . "LEFT JOIN artisanal_catches.t_gear ON artisanal_catches.t_gear.id = artisanal_catches.obs_action.t_gear "
        . "WHERE obs_action.id_maree = ".$_SESSION['filter']['f_id_maree']." "
        . "ORDER BY date_d DESC OFFSET $start LIMIT $step";
      }
    } else {
      $query = "SELECT count(obs_action.id) FROM artisanal_catches.obs_action";
      $pnum = pg_fetch_row(pg_query($query))[0];

      $query = "SELECT obs_action.id, obs_action.datetime::date, obs_action.username, date_d, concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.id_pirogue, wpt, date_a, time_a, t_gear.gear, boarded, obs_action.comments, st_x(location), st_y(location), obs_maree.id, obs_maree.obs_name "
      . "FROM artisanal_catches.obs_action "
      . "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_action.id_maree "
      . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
      . "LEFT JOIN artisanal_catches.t_gear ON artisanal_catches.t_gear.id = artisanal_catches.obs_action.t_gear "
      . "ORDER BY obs_maree.date_d::date DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

      $lon = $results[12];
      $lat = $results[13];

      $lon_deg = intval($lon);
      $lat_deg = intval($lat);

      $lon_min = round(($lon - $lon_deg)*60);
      $lat_min = round(($lat - $lat_deg)*60);


      print "<tr align=\"center\"><td>";
      //. "<a href=\"./view_owner.php?id=$results[0]\">Voir</a><br/>";
      if(right_write($_SESSION['username'],3,2)) {
        print "<a href=\"./view_catches_obs_actions.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_catches_obs_actions.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
      }
      print "</td>";

      print "<td nowrap>$results[1]<br/>$results[2]</td><td><a href=\"./view_obs_maree.php?id=$results[14]&source=obs_catches&table=maree\">$results[3]<br/>$results[4]</a></td><td>$results[15]</td><td>$results[6]</td>"
      . "<td>$results[7]</br>$results[8]</td><td>$results[9]</td>";

      if ($results[10] == 't') {
        $remonte = "Oui";
      } else {
        $remonte = "Non";
      }

      print "<td>$remonte</td>";

      print "<td>Capture</td>";

      print "<td nowrap><a href=\"./view_obs_point.php?X=$results[10]&Y=$results[11]\">".abs($lat_deg)."&deg;".abs($lat_min)."&prime; ";
      if($lat_deg >= 0) {print "N";} else {print "S";}

      print "<br/>".abs($lon_deg)."&deg;".abs($lon_min)."&prime; ";
      if($lon_deg >= 0) {print "E";} else {print "O";}

      print "</a></td>";

      print "<td>$results[11]</td>";

    }
    print "</tr>";

    print "</table>";

    pages($start,$step,$pnum,'./view_catches_obs_actions.php?source=obs_catches&table=actions&action=show&f_id_maree='.$_SESSION['filter']['f_id_maree'].'&f_observateur='.$_SESSION['filter']['f_observateur']);

    $controllo = 1;

  } else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    // obs_action.id, obs_action.datetime::date, obs_action.username, obs_name, t_mission, date_d, time_d, t_site_d, date_r, time_r, t_site_r, n_deb, zone, id_pirogue, obs_action.immatriculation,
    // engine, g1.gear, length_1, height_1, mesh_min_1, mesh_max_1, g2.gear, length_2, height_2, mesh_min_2, mesh_max_2,
    // g3.gear, length_3, height_3, mesh_min_3, mesh_max_3, gps_track, obs_action.comments

    $q_id = "SELECT *, st_x(location), st_y(location) FROM artisanal_catches.obs_action WHERE id = '$id' ";
    //print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    $lon = $results[11];
    $lat = $results[12];

    if ($lat >= 0) {$NS = 'N';} else {$lat = -1*$lat; $NS = 'S';}
    if ($lon >= 0) {$EO = 'E';} else {$lon = -1*$lon; $EO = 'O';}

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

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">
      <b>Maree</b>
      <br/>
      <select name="id_maree">
        <?php
        $result = pg_query("SELECT obs_maree.id, concat(obs_maree.immatriculation, pirogue.immatriculation), date_d FROM artisanal_catches.obs_maree LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue ORDER BY date_d");
        while($row = pg_fetch_row($result)) {
          if ($results[3] == $row[0]) {
            print "<option value=\"$row[0]\" selected>".$row[1]." / ".$row[2]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]." / ".$row[2]."</option>";
          }

        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Date</b>
      <br/>
      <input type="date" size="10" name="date_a" value="<?php echo $results[4];?>" />
      <br/>
      <br/>
      <b>Heure</b>
      <br/>
      <input type="time" size="10" name="time_a" value="<?php echo $results[5];?>" />
      <br/>
      <br/>
      <b>ID Waypoint</b>
      <br/>
      <input type="text" size="10" name="wpt" value="<?php echo $results[6];?>" />
      <br/>
      <br/>
      <b>Engin de peche utilize</b>
      <br/>
      <select name="t_gear">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_gear ORDER BY gear");
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

      <b>Capture embarque?</b>
      <br/>
      <input type="radio" name="boarded" value="true" <?php if($results[8] == 't') {print 'checked';}?>>
      <label for="true">Oui</label>
      <input type="radio" name="boarded" value="false" <?php if($results[8] != 't') {print 'checked';}?>>
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
    <b>Commentaires</b>
    <br/>
    <textarea cols=30 rows=3 name="comments"><?php echo $results[9];?></textarea>
    <br/>
    <br/>
    <input type="hidden" value="<?php echo $results[0];?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
  </form>
  <br/>
  <br/>

  <?php

}  else if ($_GET['action'] == 'delete') {
  $id = $_GET['id'];

  $query = "DELETE FROM artisanal_catches.obs_action WHERE id = '$id'";
  if(!pg_query($query)) {
    msg_queryerror();
  }

  header("Location: ".$_SESSION['http_host']."/artisanal/catches/view_catches_obs_actions.php?source=$source&table=$table&action=show");

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
      $point = "ST_GeomFromText('POINT($lon $lat)',4326)";
  }

  $id = $_POST['id'];
  $username = $_SESSION['username'];
  $id_maree = $_POST['id_maree'];
  $wpt = htmlspecialchars($_POST['wpt'],ENT_QUOTES);
  $date_a = $_POST['date_a'];
  $time_a = $_POST['time_a'];
  $t_gear = $_POST['t_gear'];
  $boarded = $_POST['boarded'];
  $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);

  $query = "UPDATE artisanal_catches.obs_action SET "
  . "username = '$username', datetime = NOW(), id_maree = '$id_maree', wpt = '$wpt', date_a = '$date_a', time_a = '$time_a', t_gear = '$t_gear', boarded = '$boarded', comments = '$comments', location = $point "
  . "WHERE id = '$id'";

  $query = str_replace('\'-- \'', 'NULL', $query);
  $query = str_replace('\'\'', 'NULL', $query);

  if(!pg_query($query)) {
    echo "<p>".$query,"</p>";
    msg_queryerror();
    foot();
    die();
  } else {
    //print $query;
    header("Location: ".$_SESSION['http_host']."/artisanal/catches/view_catches_obs_actions.php?source=$source&table=$table&action=show");

  }


}

foot();
