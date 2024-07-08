<?php
require("../../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
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

$query = "SELECT carte, first_name, last_name, license, extract(year FROM carte.date_v), *  FROM artisanal.carte "
        . "LEFT JOIN artisanal.fisherman ON artisanal.fisherman.id = artisanal.carte.id_fisherman "
        . "LEFT JOIN artisanal.license ON artisanal.license.id = artisanal.carte.id_license "
        . "WHERE carte.id = '$id'";

//print $query;

$results = pg_fetch_array(pg_query($query));

print "<h3>".strtoupper($results[2])." ".ucfirst($results[1])." - Carte details</h3>";

?>
<table id="results">
    <tr><td><b>Carte number</b></td><td><?php echo $results[0]; ?></td></tr>
    <tr><td><b>Date validit&eacute;</b></td><td><?php echo $results[4]; ?></td></tr>
    <?php
    if ($results[12] != "") {
        print "<tr><td><b>Autorisation de p&ecirc;che</b></td><td><a href=\"./view_license.php?id=$results[11]\">$results[3]</a></td></tr>";
    } else {
        print "<tr><td><b>Autorisation de p&ecirc;che</b></td><td>No license</td><td></td></tr>";

    }
    ?>
    <tr><td><b>D&eacute;tails pecheur</b></td><td><a href="./view_fisherman.php?id=<?php echo $results[9]; ?>"><?php echo strtoupper($results[2])." ".ucfirst($results[1]); ?></td></tr>

</table>

<?php
$query = "SELECT infraction.username, date_i, t_infraction.infraction, id_license, id_fisherman, fish_first, fish_last, fish_idcard, id_carte, id_pirogue, pir_name, pir_reg, t_org.org, infraction.name, obj_confiscated, amount_infract, amount_paid, pirogue.comments FROM artisanal.pirogue "
        . "LEFT JOIN infraction.infraction ON artisanal.pirogue.id = infraction.infraction.id_pirogue "
        . "LEFT JOIN infraction.t_infraction ON infraction.infraction.t_infraction = infraction.t_infraction.id "
        . "LEFT JOIN infraction.t_org ON infraction.infraction.t_org = infraction.t_org.id "
        . "WHERE id_carte = '$id' AND infraction.id IS NOT NULL";

//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>
    <h3>Infractions linked to this fisherman</h3>
    <table>
    <tr align="center">
    <td><b>Date</b></td>
    <td><b>Type infraction</b></td>
    <td><b>Nom du pirogue</b></td>
    <td><b>Num&eacute;ro d'immatriculation</b></td>
    <td><b>Organisation</b></td>
    <td><b>Officer name</b></td>
    <td><b>Objets saisis</b></td>
    <td><b>Montant de l'infraction</b></td>
    <td><b>Montant Pay&eacute;</b></td>
    <td><b>Commentaires</b></td>
    </tr>

    <?php

    while ($results = pg_fetch_row($r_query)) {
        print "<tr align=\"center\"><td>$results[1]</td><td>$results[2]</td>"
        . "<td><a href=\"./view_pirogue.php?id=$results[9]\">$results[10]</a></td><td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td>"
        . "<td>$results[15]</td><td>$results[16]</td><td>$results[17]</td></tr>";
    }

    print "</table><br/>";

}
?>

<br/>
<button onClick="goBack()">Retourner</button>
<?php
foot();
