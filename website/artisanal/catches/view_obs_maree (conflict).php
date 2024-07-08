<?php
require("../../top_foot.inc.php");


$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'iccat';

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

print $query;

$results = pg_fetch_array(pg_query($query));

?>
<table id="results">
  <tr><td><b>Date & Utilisateur</b></td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Observateur</b></td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Type mission</b></td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Debarquement</td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Zone de peche</b></td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Details<br/>D&eacute;part</b></td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Details<br/>Retour</b></td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Pirogue</b></td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Engin</br>p&ecirc;che</br>1</b></td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Engin</br>p&ecirc;che</br>2</b></td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Engin</br>p&ecirc;che</br>3</b></td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Trace GPS</b></td><td><?php echo $results[4]; ?></td></tr>
  <tr><td><b>Commentaires</b></td><td><?php echo $results[4]; ?></td></tr>
</table>

<?php

// ACTIONS de PECHE

$query = "SELECT obs_action.id, obs_action.datetime::date, obs_action.username, date_d, concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.id_pirogue, wpt, t_gear.gear, boarded, obs_action.comments, st_x(location), st_y(location) "
. "FROM artisanal_catches.obs_action "
. "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_action.id_maree "
. "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
. "LEFT JOIN artisanal_catches.t_gear ON artisanal_catches.t_gear.id = artisanal_catches.obs_action.t_gear "
. "WHERE id_maree = '$id'";

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>

    <h3>Actions de Peche</h3>
    <table>
    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Maree</b></td>
    <td><b>Date et heure lance</b></td>
    <td><b>EEZ</b></td>
    <td><b>Coup null</b></td>
    <td><b>Banc libre</b></td>
    <td><b>Code balise</b></td>
    <td><b>Temperature Eau</b></td>
    <td><b>Vitesse vent</b></td>
    <td><b>Direction vent</b></td>
    <td><b>Vitesse courant</b></td>
    <td><b>Remarque</b></td>
    <td><b>Point GPS</b></td>
    </tr>

    <?php
    while ($results = pg_fetch_row($r_query)) {

        $lon = $results[17];
        $lat = $results[18];

        $lon_deg = intval($lon);
        $lat_deg = intval($lat);

        $lon_min = round(($lon - $lon_deg)*60);
        $lat_min = round(($lat - $lat_deg)*60);

        print "<tr align=\"center\">";

        print "<td>"
        . "<a href=\"./view_iccat_lance.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_iccat_lance.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td nowrap>$results[4]<br/>$results[5]</td>"
                . "<td nowrap><a href=\"./view_lance.php?id=$results[0]&source=iccat&table=lance&action=show\">$results[6]<br/>$results[7]</a></td><td>$results[8]</td><td>$results[9]</td><td>$results[10]</td>"
                . "<td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td><td>$results[15]</td><td>$results[16]</td>"
                . "<td nowrap>".abs($lat_deg)."&deg;".abs($lat_min)."&prime; ";
                if($lat_deg >= 0) {print "N";} else {print "S";}

                print "<br/>".abs($lon_deg)."&deg;".abs($lon_min)."&prime; ";
                if($lon_deg >= 0) {print "E";} else {print "W";}

                print "</tr>";


    }

    print "</table><br/>";

}

// CAPTURES Requins

        $query = "SELECT lance.id, lance.username, lance.datetime, id_maree, maree.navire, maree.year, date_c, heure_c, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment, st_x(location), st_y(location)"
        . " FROM iccat.lance "
        . "LEFT JOIN iccat.maree ON iccat.lance.id_maree = iccat.maree.id "
        . "WHERE id_maree = '$id'";

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>

    <h3>Actions de Peche</h3>
    <table>
    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Maree</b></td>
    <td><b>Date et heure lance</b></td>
    <td><b>EEZ</b></td>
    <td><b>Coup null</b></td>
    <td><b>Banc libre</b></td>
    <td><b>Code balise</b></td>
    <td><b>Temperature Eau</b></td>
    <td><b>Vitesse vent</b></td>
    <td><b>Direction vent</b></td>
    <td><b>Vitesse courant</b></td>
    <td><b>Remarque</b></td>
    <td><b>Point GPS</b></td>
    </tr>

    <?php
    while ($results = pg_fetch_row($r_query)) {

        $lon = $results[17];
        $lat = $results[18];

        $lon_deg = intval($lon);
        $lat_deg = intval($lat);

        $lon_min = round(($lon - $lon_deg)*60);
        $lat_min = round(($lat - $lat_deg)*60);

        print "<tr align=\"center\">";

        print "<td>"
        . "<a href=\"./view_iccat_lance.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_iccat_lance.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td nowrap>$results[4]<br/>$results[5]</td>"
                . "<td nowrap><a href=\"./view_lance.php?id=$results[0]&source=iccat&table=lance&action=show\">$results[6]<br/>$results[7]</a></td><td>$results[8]</td><td>$results[9]</td><td>$results[10]</td>"
                . "<td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td><td>$results[15]</td><td>$results[16]</td>"
                . "<td nowrap>".abs($lat_deg)."&deg;".abs($lat_min)."&prime; ";
                if($lat_deg >= 0) {print "N";} else {print "S";}

                print "<br/>".abs($lon_deg)."&deg;".abs($lon_min)."&prime; ";
                if($lon_deg >= 0) {print "E";} else {print "W";}

                print "</tr>";


    }

    print "</table><br/>";

}
?>

<br/>
<button onClick="goBack()">Retourner</button>
<?php
foot();
