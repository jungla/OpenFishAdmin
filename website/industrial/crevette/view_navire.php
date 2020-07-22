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
print "<h3>Navire details</h3>";

        $query = "SELECT id, datetime, username, navire, flag, owners, fullname, radio, immatriculation, registration_ext,
        registration_int, registration_qrt, mobile, mmsi, imo, port, active, beacon, satellite, unknown "
        . " FROM vms.navire "
        . "WHERE id = '$id'";

$results = pg_fetch_array(pg_query($query));

$lon = $results[17];
$lat = $results[18];

$lon_deg = intval($lon);
$lat_deg = intval($lat);

$lon_min = round(($lon - $lon_deg)*60);
$lat_min = round(($lat - $lat_deg)*60);


?>
<table id="results">
    <tr>
    <td><b>Date & Utilisateur</b></td><td><?php echo $results[2]." ".$results[1]; ?></td></tr>
    <td><b>Nom navire</b></td><td><?php echo $results[3]; ?></td></tr>
    <td><b>Nationalit&eacute;</b></td><td><?php echo $results[4]; ?></td></tr>
    <td><b>Armement</b></td><td><?php echo $results[5]; ?></td></tr>
    <td><b>Nom complet</b></td><td><?php echo $results[6]; ?></td></tr>
    <td><b>Radio</b></td><td><?php echo $results[7]; ?></td></tr>
    <td><b>Immatriculation national / externe / international</b></td><td><?php echo $results[8]."/".$results[9]."/".$results[10]; ?></td></tr>
    <td><b>Enregistrement</b></td><td><?php echo $results[11]; ?></td></tr>
    <td><b>T&eacute;l&eacute;phone</b></td><td><?php echo $results[12]; ?></td></tr>
    <td><b>MMSI</b></td><td><?php echo $results[13]; ?></td></tr>
    <td><b>IMO</b></td><td><?php echo $results[14]; ?></td></tr>
    <td><b>Port</b></td><td><?php echo $results[15]; ?></td></tr>
    <td><b>Actif</b></td><td><?php echo $results[16]; ?></td></tr>
    <td><b>Code Balise</b></td><td><?php echo $results[17]; ?></td></tr>
    <td><b>Type Balise</b></td><td><?php echo $results[18]; ?></td></tr>
    <td><b>Inconnue</b></td><td><?php echo $results[19]; ?></td></tr>
    </tr>
</table>

<?php
$query = "SELECT captures.id, captures.username, captures.datetime, captures.id_maree, maree.navire, maree.year, captures.id_lance, lance.date_c ,lance.heure_c, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids "
        . " FROM iccat.captures "
        . "LEFT JOIN iccat.maree ON iccat.captures.id_maree = iccat.maree.id "
        . "LEFT JOIN iccat.lance ON iccat.captures.id_lance = iccat.lance.id "
        . "LEFT JOIN fishery.species ON iccat.captures.id_species = fishery.species.id "
        . "WHERE id_lance = '$id'";

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>

    <h3>Captures pour lance</h3>
    <table>
    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Maree</b></td>
    <td><b>Lanc&eacute;</b></td>
    <td><b>Rejete</b></td>
    <td><b>Espece</b></td>
    <td><b>Taille</b> [kg]</td>
    <td><b>Poids</b> [t]</td>
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
        . "<a href=\"./view_iccat_captures.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_iccat_captures.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td><a href=\"./view_maree.php?id=$results[3]&source=iccat&table=maree&action=show\">$results[4]<br/>$results[5]</a></td><td>$results[7]<br/>$results[8]</td><td>$results[9]</td><td>".formatSpecies($results[11],$results[12],$results[13],$results[14])."</td>"
        . "<td>$results[15]</td><td>$results[16]</td></tr>";

    }

    print "</table><br/>";

}
?>

<br/>
<button onClick="goBack()">Retourner</button>
<?php
foot();
