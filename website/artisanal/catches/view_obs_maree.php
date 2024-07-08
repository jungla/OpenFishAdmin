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
$id = $_GET['id'];

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

print "<h2>".label2name($source)." ".label2name($table)."</h2>";
print "<h3>Maree details</h3>";

$query = "SELECT obs_maree.id, obs_maree.datetime::date, obs_maree.username, obs_name, t_mission.mission, date_d, time_d, t1.site, date_r, time_r, t2.site, n_deb, zone, id_pirogue, obs_maree.immatriculation, "
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
. "WHERE obs_maree.id = '$id'";

//print $query;

$results = pg_fetch_array(pg_query($query));

?>
<table id="results">
  <tr><td><b>Date & Utilisateur</b></td><td><?php echo $results[1]. " ".$results[2]; ?></td></tr>
  <tr><td><b>Observateur</b></td><td><?php echo $results[3]; ?></td></tr>
  <tr><td><b>Type mission</b></td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Details<br/>D&eacute;part</b></td><td><?php echo $results[5]. " " .$results[6]. " ".$results[7]; ?></td></tr>
  <tr><td><b>Details<br/>Retour</b></td><td><?php echo $results[8]. " " .$results[9]. " ".$results[10]; ?></td></tr>
  <tr><td><b>Debarquement</td><td><?php echo $results[11]; ?></td></tr>
  <tr><td><b>Zone de peche</b></td><td><?php echo $results[12]; ?></td></tr>

<tr><td><b>Pirogue</b></td>
<?php
  if ($results[13] != '') {
    $query = "SELECT immatriculation FROM artisanal.pirogue WHERE id = '$results[13]'";
    $immatriculation = pg_fetch_row(pg_query($query));
    //print $query;
    print "<td nowrap><a href=../administration/view_pirogue.php?id=$results[13]>$immatriculation[0]</a></td>";
  } else {
    print "<td nowrap>$results[14]<br/>$results[15]</td>";
  }
?>

  </tr>
  <tr><td><b>Puissance motor</b></td><td><?php echo $results[15]; ?></td></tr>
  <tr><td><b>Engin p&ecirc;che 1</b></td><td><?php echo $results[16]. " ".$results[17]." ".$results[18]. " ".$results[19]." ".$results[20]; ?></td></tr>
  <tr><td><b>Engin p&ecirc;che 2</b></td><td><?php echo $results[21]. " ".$results[22]." ".$results[23]. " ".$results[24]." ".$results[25]; ?></td></tr>
  <tr><td><b>Engin p&ecirc;che 3</b></td><td><?php echo $results[26]. " ".$results[27]." ".$results[28]. " ".$results[29]." ".$results[30]; ?></td></tr>
  <tr><td><b>Trace GPS</b></td><td><?php if($results[31] != '') {print "Oui";} else {print "Non";}?></td></tr>
  <tr><td><b>Commentaires</b></td><td><?php echo $results[32]; ?></td></tr>
</table>

<?php

// ACTIONS de PECHE

$query = "SELECT obs_action.id, obs_action.datetime::date, obs_action.username, date_d, concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.id_pirogue, wpt, date_a, time_a, t_gear.gear, boarded, obs_action.comments, st_x(location), st_y(location), obs_maree.id, obs_maree.obs_name "
. "FROM artisanal_catches.obs_action "
. "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_action.id_maree "
. "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
. "LEFT JOIN artisanal_catches.t_gear ON artisanal_catches.t_gear.id = artisanal_catches.obs_action.t_gear "
. "WHERE id_maree = '$id'";

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>

    <h3>Actions de Peche</h3>
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

  print "<td nowrap>$results[1]<br/>$results[2]</td><td>$results[3]<br/>$results[4]</td><td>$results[15]</td><td>$results[6]</td>"
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

}

// CAPTURES Requins

$query = "SELECT obs_sharks.id, obs_sharks.datetime::date, obs_sharks.username, date_d, "
. "concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.obs_name, obs_maree.id_pirogue, "
. "wpt, date_a, time_a, obs_sharks.id_maree, id_action, fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, "
. " t_sex.sex, t_maturity.maturity, LT, LA, wgt, t_status.status, t_action.action, photo_data, obs_sharks.comments  "
. "FROM artisanal_catches.obs_sharks "
. "LEFT JOIN fishery.species ON obs_sharks.id_species = fishery.species.id "
. "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_sharks.id_maree "
. "LEFT JOIN artisanal_catches.obs_action ON artisanal_catches.obs_action.id = artisanal_catches.obs_sharks.id_action "
. "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
. "LEFT JOIN artisanal_catches.t_sex ON artisanal_catches.t_sex.id = artisanal_catches.obs_sharks.t_sex "
. "LEFT JOIN artisanal_catches.t_maturity ON artisanal_catches.t_maturity.id = artisanal_catches.obs_sharks.t_maturity "
. "LEFT JOIN artisanal_catches.t_status ON artisanal_catches.t_status.id = artisanal_catches.obs_sharks.t_status "
. "LEFT JOIN artisanal_catches.t_action ON artisanal_catches.t_action.id = artisanal_catches.obs_sharks.t_action "
. "WHERE obs_sharks.id_maree = '$id' "
. "ORDER BY obs_maree.date_d::date DESC ";

//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>

    <h3>Captures Requins</h3>
    <table id="small">
      <!-- id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, LT, LA, wgt, t_status, t_action, photo_data, comments -->
      <tr align="center"><td></td>
        <td><b>Date & Utilisateur</b></td>
        <td><b>Observateur</b></td>
        <td><b>Maree</b></td>
        <td><b>Action Peche</b></td>
        <td><b>Espece</b></td>
        <td><b>Sex et maturite</b></td>
        <td><b>LA/LT</b> [cm]</td>
        <td><b>Poids</b> [kg]</td>
        <td><b>Etat et Action</b></td>
        <td><b>Photo</b></td>
        <td><b>Commentaires</b></td>
      </tr>

    <?php
    while ($results = pg_fetch_row($r_query)) {

      print "<tr align=\"center\"><td>";
      //. "<a href=\"./view_owner.php?id=$results[0]\">Voir</a><br/>";
      if(right_write($_SESSION['username'],3,2)) {
        print "<a href=\"./view_catches_obs_sharks.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_catches_obs_sharks.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
      }
      print "</td>";

      print "<td nowrap>$results[1]<br/>$results[2]</td><td>$results[5]</td><td nowrap>$results[3]<br/>$results[4]</td><td nowrap>$results[8]<br/>$results[9]<br/>WPT $results[7]</td>"
      . "<td>".formatSpeciesFAO($results[13],$results[14],$results[15],$results[16],$results[17])."</td>"
      . "<td>$results[18]</br>$results[19]</td><td>LA: $results[20]<br/>LT: $results[21]</td>";

      print "<td>$results[22]</td><td>$results[23]<br/>$results[24]</td>";

      // photo
      //print "<td>$results[25]</td>";

      if ($results[25] != '') {
        print "<td align=\"center\" width=\"30%\"><img class=\"img_frame\" width=\"100%\" src=\"image.php?id=$results[0]&table=artisanal_catches.obs_sharks&photo_data=photo_data\" /></td>";
      } else {
        print "<td>pas de photo</td>";
      }
      print "<td>$results[26]</td>";
    }
    print "</tr>";

    print "</table>";

}


// CAPTURES Tortues

$query = "SELECT obs_turtles.id, obs_turtles.datetime::date, obs_turtles.username, date_d, "
. "concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.obs_name, obs_maree.id_pirogue, "
. "wpt, date_a, time_a, obs_turtles.id_maree, id_action, fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, "
. " t_sex.sex, t_maturity.maturity, bague, integrity, fibrop, epibionte, LT, wgt, t_status.status, t_action.action, photo_data, obs_turtles.comments  "
. "FROM artisanal_catches.obs_turtles "
. "LEFT JOIN fishery.species ON obs_turtles.id_species = fishery.species.id "
. "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_turtles.id_maree "
. "LEFT JOIN artisanal_catches.obs_action ON artisanal_catches.obs_action.id = artisanal_catches.obs_turtles.id_action "
. "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
. "LEFT JOIN artisanal_catches.t_sex ON artisanal_catches.t_sex.id = artisanal_catches.obs_turtles.t_sex "
. "LEFT JOIN artisanal_catches.t_maturity ON artisanal_catches.t_maturity.id = artisanal_catches.obs_turtles.t_maturity "
. "LEFT JOIN artisanal_catches.t_status ON artisanal_catches.t_status.id = artisanal_catches.obs_turtles.t_status "
. "LEFT JOIN artisanal_catches.t_action ON artisanal_catches.t_action.id = artisanal_catches.obs_turtles.t_action "
. "WHERE obs_turtles.id_maree = '$id' "
. "ORDER BY date_d::date DESC ";

//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>

    <h3>Captures Tortues</h3>
    <table id="small">
      <!-- username, datetime, id_maree, id_action, id_species, t_sex, t_maturity, bague, integrity, fibrop, epibionte, LT, wgt, t_status, t_action, photo_data, comments -->
      <tr align="center"><td></td>
        <td><b>Date & Utilisateur</b></td>
        <td><b>Observateur</b></td>
        <td><b>Maree</b></td>
        <td><b>Action Peche</b></td>
        <td><b>Espece</b></td>
        <td><b>Sex et maturite</b></td>
        <td><b>Bague</b></td>
        <td><b>Integrite</b></td>
        <td><b>Fibrop.</b></td>
        <td><b>Epibionthe</b></td>
        <td><b>LT [cm]<br/>et<br/>poids [kg]</b></td>
        <td><b>Etat et Action</b></td>
        <td><b>Photo</b></td>
        <td><b>Commentaires</b></td>
      </tr>

    <?php
    while ($results = pg_fetch_row($r_query)) {

      print "<tr align=\"center\"><td>";
      //. "<a href=\"./view_owner.php?id=$results[0]\">Voir</a><br/>";
      if(right_write($_SESSION['username'],3,2)) {
        print "<a href=\"./view_catches_obs_turtles.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_catches_obs_turtles.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
      }
      print "</td>";

      print "<td nowrap>$results[1]<br/>$results[2]</td><td>$results[5]</td><td nowrap><a href=\"./view_obs_maree.php?id=$results[10]&source=obs_catches&table=maree\">$results[3]<br/>$results[4]</a></td><td nowrap>$results[8]<br/>$results[9]<br/>WPT $results[7]</td>"
      . "<td>".formatSpeciesFAO($results[13],$results[14],$results[15],$results[16],$results[17])."</td>"
      . "<td>$results[18]</br>$results[19]</td><td>$results[20]</td><td>$results[21]</td>";

      print "<td>$results[22]</td><td>$results[23]</td><td>$results[24]cm<br/>$results[25]kg</td><td>$results[26]<br/>$results[27]</td>";

      // photo
      //print "<td>$results[25]</td>";

      if ($results[28] != '') {
        print "<td align=\"center\" width=\"30%\"><img class=\"img_frame\" width=\"100%\" src=\"image.php?id=$results[0]&table=artisanal_catches.obs_turtles&photo_data=photo_data\" /></td>";
      } else {
        print "<td>pas de photo</td>";
      }
      print "<td>$results[29]</td>";
    }
    print "</tr>";

    print "</table>";

}

// CAPTURES Mammals

$query = "SELECT obs_mammals.id, obs_mammals.datetime::date, obs_mammals.username, date_d, "
. "concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.obs_name, obs_maree.id_pirogue, "
. "wpt, date_a, time_a, obs_mammals.id_maree, id_action, fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, "
. " t_sex.sex, t_maturity.maturity, LT, wgt, t_status.status, t_action.action, photo_data, obs_mammals.comments  "
. "FROM artisanal_catches.obs_mammals "
. "LEFT JOIN fishery.species ON obs_mammals.id_species = fishery.species.id "
. "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_mammals.id_maree "
. "LEFT JOIN artisanal_catches.obs_action ON artisanal_catches.obs_action.id = artisanal_catches.obs_mammals.id_action "
. "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
. "LEFT JOIN artisanal_catches.t_sex ON artisanal_catches.t_sex.id = artisanal_catches.obs_mammals.t_sex "
. "LEFT JOIN artisanal_catches.t_maturity ON artisanal_catches.t_maturity.id = artisanal_catches.obs_mammals.t_maturity "
. "LEFT JOIN artisanal_catches.t_status ON artisanal_catches.t_status.id = artisanal_catches.obs_mammals.t_status "
. "LEFT JOIN artisanal_catches.t_action ON artisanal_catches.t_action.id = artisanal_catches.obs_mammals.t_action "
. "WHERE obs_mammals.id_maree = '$id' "
. "ORDER BY date_d::date DESC ";

//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>

    <h3>Captures Mammiferes Marines</h3>
    <table id="small">
      <!-- id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, LT, LA, wgt, t_status, t_action, photo_data, comments -->
      <tr align="center"><td></td>
        <td><b>Date & Utilisateur</b></td>
        <td><b>Observateur</b></td>
        <td><b>Maree</b></td>
        <td><b>Action Peche</b></td>
        <td><b>Espece</b></td>
        <td><b>Sex et maturite</b></td>
        <td><b>LT</b> [cm]</td>
        <td><b>Poids</b> [kg]</td>
        <td><b>Etat et Action</b></td>
        <td><b>Photo</b></td>
        <td><b>Commentaires</b></td>
      </tr>

    <?php
    while ($results = pg_fetch_row($r_query)) {

      print "<tr align=\"center\"><td>";
      //. "<a href=\"./view_owner.php?id=$results[0]\">Voir</a><br/>";
      if(right_write($_SESSION['username'],3,2)) {
        print "<a href=\"./view_catches_obs_turtles.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_catches_obs_turtles.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
      }
      print "</td>";

      print "<td nowrap>$results[1]<br/>$results[2]</td><td>$results[5]</td><td nowrap>$results[3]<br/>$results[4]</td><td nowrap>$results[8]<br/>$results[9]<br/>WPT $results[7]</td>"
      . "<td>".formatSpeciesFAO($results[13],$results[14],$results[15],$results[16],$results[17])."</td>"
      . "<td>$results[18]</br>$results[19]</td><td>$results[20]</td><td>$results[21]</td><td>$results[22]<br/>$results[23]</td>";

      if ($results[24] != '') {
        print "<td align=\"center\" width=\"30%\"><img class=\"img_frame\" width=\"100%\" src=\"image.php?id=$results[0]&table=artisanal_catches.obs_mammals&photo_data=photo_data\" /></td>";
      } else {
        print "<td>pas de photo</td>";
      }
      print "<td>$results[25]</td>";
    }
    print "</tr>";

    print "</table>";

}

// CAPTURES Fish

$query = "SELECT DISTINCT ON (id_maree) obs_fish.id, obs_fish.datetime::date, obs_fish.username, date_d, "
. "concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.obs_name, obs_maree.id_pirogue, "
. "obs_fish.id_maree, obs_fish.comments  "
. "FROM artisanal_catches.obs_fish "
. "LEFT JOIN fishery.species ON obs_fish.id_species = fishery.species.id "
. "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_fish.id_maree "
. "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
. "WHERE obs_fish.id_maree = '$id' "
. "ORDER BY id_maree, date_d::date DESC ";

//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>

    <h3>Captures Especes Cibles</h3>
    <table id="small">
      <tr align="center"><td></td>
        <td><b>Date & Utilisateur</b></td>
        <td><b>Observateur</b></td>
        <td><b>Maree</b></td>
        <td><b>Commentaires</b></td>
        <td><b>Espece</b></td>
        <td><b>Numero lot</b></td>
        <td><b>Pourcentage</b></td>
        <td><b>Poids lot</b></td>
      </tr>

    <?php
    while ($results = pg_fetch_row($r_query)) {

      $query = "SELECT n_lot, perc, wgt, fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species "
      . " FROM artisanal_catches.obs_fish "
      . "LEFT JOIN fishery.species ON obs_fish.id_species = fishery.species.id "
      . "WHERE id_maree = '$results[7]'";

      $l_query = pg_query($query);

      //print $query;
      $nrow = pg_num_rows($l_query)+1;

      print "<tr align=\"center\"><td rowspan=$nrow>";
      if(right_write($_SESSION['username'],3,2)) {
        print "<a href=\"./view_catches_obs_fish.php?source=$source&table=$table&action=edit&id_maree=$results[7]\">Modifier</a><br/>"
        . "<a href=\"./view_catches_obs_fish.php?source=$source&table=$table&action=delete&id_maree=$results[7]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
      }
      print "</td>";

      print "<td nowrap rowspan=$nrow>$results[1]<br/>$results[2]</td><td rowspan=$nrow>$results[5]</td><td nowrap rowspan=$nrow>$results[3]<br/>$results[4]</td>";
      print "<td rowspan=$nrow>$results[8]</td></tr>";

      while ($results_l = pg_fetch_row($l_query)) {
        print "<tr><td>".formatSpeciesFAO($results_l[4],$results_l[5],$results_l[6],$results_l[7],$results_l[8])."</td>"
        . "<td>$results_l[0]</td><td>$results_l[1]</td><td>$results_l[2]</td></tr>";
      }


      //fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.specie



    }
    print "</tr>";

    print "</table>";

}

// CAPTURES Poids Taille

$query = "SELECT DISTINCT ON (id_maree) obs_poids_taille.id, obs_poids_taille.datetime::date, obs_poids_taille.username, date_d, "
. "concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.obs_name, obs_maree.id_pirogue, "
. "obs_poids_taille.id_maree, obs_poids_taille.comments, "
. "fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species "
. "FROM artisanal_catches.obs_poids_taille "
. "LEFT JOIN fishery.species ON obs_poids_taille.id_species = fishery.species.id "
. "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_poids_taille.id_maree "
. "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
. "WHERE obs_poids_taille.id_maree = '$id' "
. "ORDER BY id_maree, date_d::date DESC ";

//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>

    <h3>Relation Poids-Taille</h3>
    <table id="small">
      <tr align="center"><td></td>
          <td><b>Date & Utilisateur</b></td>
          <td><b>Observateur</b></td>
          <td><b>Maree</b></td>
          <td><b>Commentaires</b></td>
          <td><b>Espece</b></td>
          <td><b>Etat maturite</b></td>
          <td><b>Longueur</b></td>
          <td><b>Poid</b></td>
        </tr>

    <?php
    while ($results = pg_fetch_row($r_query)) {

      $query = "SELECT t_maturity.maturity, t_measure.measure, length, wgt  "
      . "FROM artisanal_catches.obs_poids_taille "
      . "LEFT JOIN artisanal_catches.t_measure ON artisanal_catches.t_measure.id = artisanal_catches.obs_poids_taille.t_measure "
      . "LEFT JOIN artisanal_catches.t_maturity ON artisanal_catches.t_maturity.id = artisanal_catches.obs_poids_taille.t_maturity "
      . "WHERE id_maree = '$results[7]'";

      //print $query;
      $l_query = pg_query($query);

      //print $query;
      $nrow = pg_num_rows($l_query)+1;

      print "<tr align=\"center\"><td rowspan=$nrow>";
      if(right_write($_SESSION['username'],3,2)) {
        print "<a href=\"./view_catches_obs_poids_taille.php?source=$source&table=$table&action=edit&id_maree=$results[7]\">Modifier</a><br/>"
        . "<a href=\"./view_catches_obs_poids_taille.php?source=$source&table=$table&action=delete&id_maree=$results[7]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
      }
      print "</td>";

      print "<td nowrap rowspan=$nrow>$results[1]<br/>$results[2]</td><td rowspan=$nrow>$results[5]</td><td nowrap rowspan=$nrow>$results[3]<br/>$results[4]</td>";
      print "<td rowspan=$nrow>$results[8]</td>";
      print "<td rowspan=$nrow>".formatSpeciesFAO($results[9],$results[10],$results[11],$results[12],$results[13])."</td></tr>";

      while ($results_l = pg_fetch_row($l_query)) {
        print "<tr><td>$results_l[0]</td><td>$results_l[1] $results_l[2]</td><td>$results_l[3]</td></tr>";
      }


      //fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.specie



    }
    print "</tr>";

    print "</table>";

}

?>

<br/>
<button onClick="goBack()">Retourner</button>
<?php
foot();
