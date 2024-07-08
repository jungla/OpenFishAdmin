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
$_SESSION['filter']['f_t_site'] = $_POST['f_t_site'];
$_SESSION['filter']['f_immatriculation'] = str_replace('\'','',$_POST['f_immatriculation']);
$_SESSION['filter']['f_t_mission'] = $_POST['f_t_mission'];
$_SESSION['filter']['f_observateur'] = $_POST['f_observateur'];

if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}
if ($_GET['f_t_site'] != "") {$_SESSION['filter']['f_t_site'] = $_GET['f_t_site'];}
if ($_GET['f_immatriculation'] != "") {$_SESSION['filter']['f_immatriculation'] = $_GET['f_immatriculation'];}
if ($_GET['f_t_mission'] != "") {$_SESSION['filter']['f_t_mission'] = $_GET['f_t_mission'];}
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
      <table id="no-border"><tr><td><b>Debarcadere retour</b></td><td><b>Observateur</b></td><td><b>Immatriculation</b></td><!--<td><b>Espece</b></td>--><td><b>Type etude</b></td></tr>
        <tr>
          <td>
            <select name="f_t_site" class="chosen-select" style="width:100%">
              <option value="obs_maree.t_site_r" selected="selected">Tous</option>
              <?php
              $result = pg_query("SELECT DISTINCT t_site.id, site FROM artisanal.t_site JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.t_site_r = artisanal.t_site.id ORDER BY site");
              while($row = pg_fetch_row($result)) {
                if ($row[0] == $_SESSION['filter']['f_t_site']) {
                  print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
                } else {
                  print "<option value=\"$row[0]\">".$row[1]."</option>";
                }
              }
              ?>
            </select>
          </td>
          <td>
            <input type="text" size="20" name="f_observateur" value="<?php echo $_SESSION['filter']['f_observateur']?>"/>
          </td>
          <td>
            <input type="text" size="20" name="f_immatriculation" value="<?php echo $_SESSION['filter']['f_immatriculation']?>"/>
          </td>
          <!-- <td>
            <select name="f_id_species" class="chosen-select" >
              <option value="captures.id_species" selected="selected">Tous</option>
              <?php
              $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN artisanal.captures ON fishery.species.id = artisanal.captures.id_species  ORDER BY  fishery.species.family, fishery.species.genus, fishery.species.species");
              while($row = pg_fetch_row($result)) {
                if ("'".$row[0]."'" == $_SESSION['filter']['f_id_species']) {
                  print "<option value=\"'$row[0]'\" selected=\"selected\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
                } else {
                  print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
                }
              }
              ?>
            </select>
          </td> -->
          <td>
            <select name="f_t_mission" class="chosen-select" style="width:100%">
              <option value="obs_maree.t_mission" selected="selected">Tous</option>
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
          </td>
        </tr>
      </table>
      <input type="submit" name="Filter" value="filter" />
    </fieldset>
  </form>

  <br/>

  <table id="small">
    <tr align="center"><td></td>
      <td><b>Date & Utilisateur</b></td>
      <td><b>Observateur</b></td>
      <td><b>Type mission</b></td>
      <td nowrap><b>Debarquement</br>et</br>zone peche</b></td>
      <td><b>Details<br/>D&eacute;part</b></td>
      <td><b>Details<br/>Retour</b></td>
      <td><b>Pirogue</b></td>
      <td nowrap><b>Engin</br>p&ecirc;che</br>1</b></td>
      <td nowrap><b>Engin</br>p&ecirc;che</br>2</b></td>
      <td nowrap><b>Engin</br>p&ecirc;che</br>3</b></td>
      <td><b>Capture</b></td>
      <td><b>Trace GPS</b></td>
      <td><b>Commentaires</b></td>
    </tr>

    <?php

    if ($_SESSION['filter']['f_immatriculation'] != "" OR $_SESSION['filter']['f_id_species'] != "" OR $_SESSION['filter']['f_t_mission'] != "" OR $_SESSION['filter']['f_t_site'] != "" OR $_SESSION['filter']['f_observateur'] != "") {

      $_SESSION['start'] = 0;

      $query = "SELECT count(DISTINCT obs_maree.id) FROM artisanal_catches.obs_maree "
      . "WHERE obs_maree.t_site_r = ".$_SESSION['filter']['f_t_site']." "
      . "AND obs_maree.t_mission = ".$_SESSION['filter']['f_t_mission']." ";

      //print $query;

      $pnum = pg_fetch_row(pg_query($query))[0];

      if ($_SESSION['filter']['f_immatriculation'] != "" OR $_SESSION['filter']['f_observateur'] != "") {
        $query = "SELECT DISTINCT obs_maree.id, obs_maree.datetime::date, obs_maree.username, obs_name, t_mission.mission, date_d, time_d, t1.site, date_r, time_r, t2.site, n_deb, zone, id_pirogue, obs_maree.immatriculation, obs_maree.nom_pir, "
        . "engine, g1.gear, length_1, height_1, mesh_min_1, mesh_max_1, g2.gear, length_2, height_2, mesh_min_2, mesh_max_2, "
        . "g3.gear, length_3, height_3, mesh_min_3, mesh_max_3, gps_track, obs_maree.comments, "
        . " coalesce(similarity(artisanal_catches.obs_maree.immatriculation, '".$_SESSION['filter']['f_immatriculation']."'),0) + "
        . " coalesce(similarity(artisanal.pirogue.immatriculation, '".$_SESSION['filter']['f_immatriculation']."'),0) + "
        . " coalesce(similarity(obs_name, '".$_SESSION['filter']['f_observateur']."'),0) AS score "
        . "FROM artisanal_catches.obs_maree "
        . "LEFT JOIN artisanal_catches.t_mission ON artisanal_catches.obs_maree.t_mission = artisanal_catches.t_mission.id "
        . "LEFT JOIN artisanal.t_site_obb t1 ON t1.id = artisanal_catches.obs_maree.t_site_r "
        . "LEFT JOIN artisanal.t_site_obb t2 ON t2.id = artisanal_catches.obs_maree.t_site_d "
        . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
        . "LEFT JOIN artisanal_catches.t_gear g1 ON artisanal_catches.obs_maree.t_gear_1 = g1.id "
        . "LEFT JOIN artisanal_catches.t_gear g2 ON artisanal_catches.obs_maree.t_gear_2 = g2.id "
        . "LEFT JOIN artisanal_catches.t_gear g3 ON artisanal_catches.obs_maree.t_gear_3 = g3.id "
        . "LEFT JOIN artisanal_catches.obs_sharks ON artisanal_catches.obs_maree.id = artisanal_catches.obs_sharks.id_maree "
        . "LEFT JOIN artisanal_catches.obs_turtles ON artisanal_catches.obs_maree.id = artisanal_catches.obs_turtles.id_maree "
        . "LEFT JOIN artisanal_catches.obs_mammals ON artisanal_catches.obs_maree.id = artisanal_catches.obs_mammals.id_maree "
        . "LEFT JOIN artisanal_catches.obs_fish ON artisanal_catches.obs_maree.id = artisanal_catches.obs_fish.id_maree "
        . "WHERE obs_maree.t_site_r = ".$_SESSION['filter']['f_t_site']." "
        . "AND obs_maree.t_mission = ".$_SESSION['filter']['f_t_mission']." "
        . "ORDER BY score, date_d DESC OFFSET $start LIMIT $step";
      } else {
        $query = "SELECT DISTINCT obs_maree.id, obs_maree.datetime::date, obs_maree.username, obs_name, t_mission.mission, date_d, time_d, t1.site, date_r, time_r, t2.site, n_deb, zone, id_pirogue, obs_maree.immatriculation, obs_maree.nom_pir, "
        . "engine, g1.gear, length_1, height_1, mesh_min_1, mesh_max_1, g2.gear, length_2, height_2, mesh_min_2, mesh_max_2, "
        . "g3.gear, length_3, height_3, mesh_min_3, mesh_max_3, gps_track, obs_maree.comments "
        . "FROM artisanal_catches.obs_maree "
        . "LEFT JOIN artisanal_catches.t_mission ON artisanal_catches.obs_maree.t_mission = artisanal_catches.t_mission.id "
        . "LEFT JOIN artisanal.t_site_obb t1 ON t1.id = artisanal_catches.obs_maree.t_site_r "
        . "LEFT JOIN artisanal.t_site_obb t2 ON t2.id = artisanal_catches.obs_maree.t_site_d "
        . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
        . "LEFT JOIN artisanal_catches.t_gear g1 ON artisanal_catches.obs_maree.t_gear_1 = g1.id "
        . "LEFT JOIN artisanal_catches.t_gear g2 ON artisanal_catches.obs_maree.t_gear_2 = g2.id "
        . "LEFT JOIN artisanal_catches.t_gear g3 ON artisanal_catches.obs_maree.t_gear_3 = g3.id "
        . "LEFT JOIN artisanal_catches.obs_sharks ON artisanal_catches.obs_maree.id = artisanal_catches.obs_sharks.id_maree "
        . "LEFT JOIN artisanal_catches.obs_turtles ON artisanal_catches.obs_maree.id = artisanal_catches.obs_turtles.id_maree "
        . "LEFT JOIN artisanal_catches.obs_mammals ON artisanal_catches.obs_maree.id = artisanal_catches.obs_mammals.id_maree "
        . "LEFT JOIN artisanal_catches.obs_fish ON artisanal_catches.obs_maree.id = artisanal_catches.obs_fish.id_maree "
        . "WHERE obs_maree.t_site_r = ".$_SESSION['filter']['f_t_site']." "
        . "AND obs_maree.t_mission = ".$_SESSION['filter']['f_t_mission']." "
        . "ORDER BY date_d DESC OFFSET $start LIMIT $step";
      }
    } else {
      $query = "SELECT count(obs_maree.id) FROM artisanal_catches.obs_maree";

      //print $query;

      $pnum = pg_fetch_row(pg_query($query))[0];

      $query = "SELECT DISTINCT obs_maree.id, obs_maree.datetime::date, obs_maree.username, obs_name, t_mission.mission, date_d, time_d, t1.site, date_r, time_r, t2.site, n_deb, zone, id_pirogue, obs_maree.immatriculation, obs_maree.nom_pir, "
      . "engine, g1.gear, length_1, height_1, mesh_min_1, mesh_max_1, g2.gear, length_2, height_2, mesh_min_2, mesh_max_2, "
      . "g3.gear, length_3, height_3, mesh_min_3, mesh_max_3, gps_track, obs_maree.comments "
      . "FROM artisanal_catches.obs_maree "
      . "LEFT JOIN artisanal_catches.t_mission ON artisanal_catches.obs_maree.t_mission = artisanal_catches.t_mission.id "
      . "LEFT JOIN artisanal.t_site_obb t1 ON t1.id = artisanal_catches.obs_maree.t_site_r "
      . "LEFT JOIN artisanal.t_site_obb t2 ON t2.id = artisanal_catches.obs_maree.t_site_d "
      . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
      . "LEFT JOIN artisanal_catches.t_gear g1 ON artisanal_catches.obs_maree.t_gear_1 = g1.id "
      . "LEFT JOIN artisanal_catches.t_gear g2 ON artisanal_catches.obs_maree.t_gear_2 = g2.id "
      . "LEFT JOIN artisanal_catches.t_gear g3 ON artisanal_catches.obs_maree.t_gear_3 = g3.id "
      . "LEFT JOIN artisanal_catches.obs_sharks ON artisanal_catches.obs_maree.id = artisanal_catches.obs_sharks.id_maree "
      . "LEFT JOIN artisanal_catches.obs_turtles ON artisanal_catches.obs_maree.id = artisanal_catches.obs_turtles.id_maree "
      . "LEFT JOIN artisanal_catches.obs_mammals ON artisanal_catches.obs_maree.id = artisanal_catches.obs_mammals.id_maree "
      . "LEFT JOIN artisanal_catches.obs_fish ON artisanal_catches.obs_maree.id = artisanal_catches.obs_fish.id_maree "
      . "ORDER BY date_d::date DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    // obs_maree.id, obs_maree.datetime::date, obs_maree.username, obs_name, t_mission,
    // date_d, time_d, t_site_d, date_r, time_r, t_site_r, n_deb, zone, id_pirogue, obs_maree.immatriculation, engine,
    // t_gear_1, length_1, height_1, mesh_min_1, mesh_max_1,
    // t_gear_2, length_2, height_2, mesh_min_2, mesh_max_2,
    // t_gear_3, length_3, height_3, mesh_min_3, mesh_max_3, gps_track, comments

    while ($results = pg_fetch_row($r_query)) {

      print "<tr align=\"center\"><td>";
      //. "<a href=\"./view_owner.php?id=$results[0]\">Voir</a><br/>";
      if(right_write($_SESSION['username'],3,2)) {
        print "<a href=\"./view_obs_maree.php?source=$source&table=$table&action=edit&id=$results[0]\">Voire</a><br/>"
        . "<a href=\"./view_catches_obs_maree.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_catches_obs_maree.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
      }
      print "</td>";

      print "<td nowrap>$results[1]<br/>$results[2]</td><td>$results[3]</td><td nowrap>$results[4]</td><td>$results[11]<br/>$results[12]</td><td nowrap>$results[5]<br/>$results[6]<br/>$results[7]</td>"
      . "<td nowrap>$results[8]<br/>$results[9]<br/>$results[10]</td>";

      if ($results[13] != '') {
        $query = "SELECT immatriculation FROM artisanal.pirogue WHERE id = '$results[13]'";
        $immatriculation = pg_fetch_row(pg_query($query));
        //print $query;
        print "<td nowrap><a href=../administration/view_pirogue.php?id=$results[13]>$immatriculation[0]</a></td>";
      } else {
        print "<td nowrap>$results[14]<br/>$results[15]<br/>$results[16]</td>";
      }

      print "<td nowrap>";
      // engine 1
      if($results[17] != ''){
        preg_match("/\\[(.*?)\\]/", $results[17], $match);
        print $match[1]."<br/>";
        print "Lo: $results[18] La:$results[19]<br/>";
        print "M: $results[20] m:$results[21]";
      }
      print "</td>";

      print "<td nowrap>";
      // engine 2
      if($results[22] != ''){
        preg_match("/\\[(.*?)\\]/", $results[22], $match);
        print $match[1]."<br/>";
        print "Lo: $results[23] La:$results[24]<br/>";
        print "M: $results[25] m:$results[26]";
      }
      print "</td>";

      print "<td nowrap>";
      // engine 3
      if($results[27] != ''){
        preg_match("/\\[(.*?)\\]/", $results[27], $match);
        print $match[1]."<br/>";
        print "Lo: $results[28] La:$results[29]<br/>";
        print "M: $results[30] m:$results[31]";
      }
      print "</td>";

      print "<td>";
      // what was captured?
      $query = "SELECT count(*) FROM artisanal_catches.obs_sharks WHERE id_maree = '$results[0]'";
      $nsharks = pg_fetch_row(pg_query($query))[0];

      $query = "SELECT count(*) FROM artisanal_catches.obs_turtles WHERE id_maree = '$results[0]'";
      $nturtles = pg_fetch_row(pg_query($query))[0];

      $query = "SELECT count(*) FROM artisanal_catches.obs_mammals WHERE id_maree = '$results[0]'";
      $nmammals = pg_fetch_row(pg_query($query))[0];

      if ($nsharks != 0) {
        print "R: $nsharks<br/>";
      }
      if ($nturtles != 0) {
        print "T: $nturtles<br/>";
      }
      if ($nmammals != 0) {
        print "M: $nmammals";
      }

      print "</td>";
      if ($results[32] != '') {
        print "<td><a href=\"view_track_obs.php?id=$results[0]>Trace GPS</td>";
      } else {
        print "<td></td>";
      }
      print "<td>$results[33]</td>";

    }
    print "</tr>";

    print "</table>";

    pages($start,$step,$pnum,'./view_catches_obs_maree.php?source=artisanal&table=captures&action=show&f_immatriculation='.$_SESSION['filter']['f_immatriculation'].'&f_id_species='.$_SESSION['filter']['f_id_species'].'&f_t_site='.$_SESSION['filter']['f_t_site'].'&f_t_mission='.$_SESSION['filter']['f_t_mission'].'&f_observateur='.$_SESSION['filter']['f_observateur']);

    $controllo = 1;

  } else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    // obs_maree.id, obs_maree.datetime::date, obs_maree.username, obs_name, t_mission, date_d, time_d, t_site_d, date_r, time_r, t_site_r, n_deb, zone, id_pirogue, obs_maree.immatriculation,
    // engine, g1.gear, length_1, height_1, mesh_min_1, mesh_max_1, g2.gear, length_2, height_2, mesh_min_2, mesh_max_2,
    // g3.gear, length_3, height_3, mesh_min_3, mesh_max_3, gps_track, obs_maree.comments

    $q_id = "SELECT * FROM artisanal_catches.obs_maree WHERE id = '$id' ";
    //print $q_id;
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    $split = explode('. ',$results[14]);
    $t_immatriculation = $split[0];
    $reg_num = explode('/',$split[1])[0];
    $reg_year = explode('/',$split[1])[1];

    ?>

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">
      <b>Nom du collecteur</b>
      <br/>
      <input type="text" size="20" name="obs_name" value="<?php echo $results[3];?>" />
      <br/>
      <br/>
      <b>Type de mission</b>
      <br/>
      <select name="t_mission">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_mission ORDER BY mission");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[4]) {
            print "<option value=\"$row[0]\" selected>".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Date de d&eacute;part</b>
      <br/>
      <input type="date" size="30" name="date_d" value="<?php echo $results[5];?>" />
      <br/>
      <br/>
      <b>Heure de d&eacute;part</b>
      <br/>
      <input type="time" size="30" name="time_d" value="<?php echo $results[6];?>" />
      <br/>
      <br/>
      <b>D&eacute;barcad&egrave;re de d&eacute;part</b>
      <br/>
      <select name="t_site_d" class="chosen-select" >
        <?php
        $result = pg_query("SELECT * FROM artisanal.t_site_obb ORDER BY site");
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

      <b>Date de retour</b>
      <br/>
      <input type="date" size="30" name="date_r" value="<?php echo $results[8];?>" />
      <br/>
      <br/>
      <b>Heure de retour</b>
      <br/>
      <input type="time" size="30" name="time_r" value="<?php echo $results[9];?>" />
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
      <input type="text" size="3" name="n_deb" value="<?php echo $results[11];?>" />
      <br/>
      <br/>

      <b>Zone de peche</b>
      <br/>
      <input type="text" size="20" name="zone" value="<?php echo $results[12];?>" />
      <br/>
      <br/>

      <b>D&eacute;tails Pirogue</b>
      <br/>
      <select name="id_pirogue"  class="chosen-select" onchange="java_script_:show(this.options[this.selectedIndex].value,'pir_id')">
        <option value=''>PAS DANS LA LISTE</option>
        <?php
        $result = pg_query("SELECT id, name, immatriculation FROM artisanal.pirogue ORDER BY name");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[13]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[2]." - ".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[2]." - ".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <div class="pir_id" <?php if($results[13] != "") {print 'style="display:none"';} ?>>
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
        <input type="text" size="15" name="nom_pir" value="<?php echo $results[15];?>" />
        <br/>
        <br/>
      </div>

      <b>Puissance moteur</b> [CV]
      <br/>
      <input type="text" size="6" name="engine" value="<?php echo $results[16];?>" />
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
            if ($row[0] == $results[17]) {
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
        <input type="text" size="4" name="length_1" value="<?php echo $results[18];?>" />
        <br/>
        <br/>
        <b>Hauteur filet</b> [m]
        <br/>
        <input type="text" size="4" name="height_1" value="<?php echo $results[19];?>" />
        <br/>
        <br/>
        <b>Taille de la maille</b> [de cote en mm]
        <br/>
        min: <input type="text" size="4" name="mesh_min_1" value="<?php echo $results[20];?>" />
        max: <input type="text" size="4" name="mesh_max_1" value="<?php echo $results[21];?>" />
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
        <b>Longueur filet</b> [m]
        <br/>
        <input type="text" size="4" name="length_2" value="<?php echo $results[23];?>" />
        <br/>
        <br/>
        <b>Hauteur filet</b> [m]
        <br/>
        <input type="text" size="4" name="height_2" value="<?php echo $results[24];?>" />
        <br/>
        <br/>
        <b>Taille de la maille</b> [de cote en mm]
        <br/>
        min: <input type="text" size="4" name="mesh_min_2" value="<?php echo $results[25];?>" />
        max: <input type="text" size="4" name="mesh_max_2" value="<?php echo $results[26];?>" />
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
            if ($row[0] == $results[27]) {
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
        <input type="text" size="4" name="length_3" value="<?php echo $results[28];?>" />
        <br/>
        <br/>
        <b>Hauteur filet</b> [m]
        <br/>
        <input type="text" size="4" name="height_3" value="<?php echo $results[29];?>" />
        <br/>
        <br/>
        <b>Taille de la maille</b> [de cote en mm]
        <br/>
        min: <input type="text" size="4" name="mesh_min_3" value="<?php echo $results[30];?>" />
        max: <input type="text" size="4" name="mesh_max_3" value="<?php echo $results[31];?>" />
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
      <textarea cols=30 rows=3 name="comments"><?php echo $results[33];?></textarea>
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

    $query = "DELETE FROM artisanal_catches.obs_maree WHERE id = '$id'";
    if(!pg_query($query)) {
      msg_queryerror();
    }

    header("Location: ".$_SESSION['http_host']."/artisanal/catches/view_catches_obs_maree.php?source=$source&table=$table&action=show");

    $controllo = 1;

  }

  if ($_POST['submit'] == "Enregistrer") {

    $id = $_POST['id'];
    $username = $_SESSION['username'];

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
    } else {
      $immatriculation = '';
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

    $query = "UPDATE artisanal_catches.obs_maree SET "
    . "username = '$username', datetime = now(), obs_name = '$obs_name', t_mission = '$t_mission', date_d = '$date_d', time_d = '$time_d', "
    . "t_site_d = '$t_site_d', date_r = '$date_r', time_r = '$time_r', t_site_r = '$t_site_r', n_deb = '$n_deb', zone = '$zone', id_pirogue = '$id_pirogue', immatriculation = '$immatriculation', "
    . "engine = '$engine', t_gear_1 = '$t_gear_1', length_1 = '$length_1', height_1 = '$height_1', mesh_min_1 = '$mesh_min_1', mesh_max_1 = '$mesh_max_1', t_gear_2 = '$t_gear_2', "
    . "length_2 = '$length_2', height_2 = '$height_2', mesh_min_2 = '$mesh_min_2', mesh_max_2 = '$mesh_max_2', "
    . "t_gear_3 = '$t_gear_3', length_3 = '$length_3', height_3 = '$height_3', mesh_min_3 = '$mesh_min_3', mesh_max_3 = '$mesh_max_3', comments = '$comments', gps_track = '$gps_track' "
    . "WHERE id = '$id'";

    $query = str_replace('\'-- \'', 'NULL', $query);
    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
      echo "<p>".$query,"</p>";
      msg_queryerror();
      foot();
      die();
    } else {
      header("Location: ".$_SESSION['http_host']."/artisanal/catches/view_catches_obs_maree.php?source=$source&table=$table&action=show");

    }


  }

  foot();
