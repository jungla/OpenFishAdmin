<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'thon';

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

        $query = "SELECT navire.id, datetime::date, username, navire.navire, flag, owners, fullname, radio, "
        . " registration_ext, registration_int, registration_qrt, mobile, mmsi, imo, port, active, beacon, satellite, unknown, t_navire.navire "
        . " FROM vms.navire LEFT JOIN vms.t_navire ON vms.navire.t_navire = vms.t_navire.id "
        . "WHERE navire.id = '$id'";

//print $query;
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
    <td><b>T&eacute;l&eacute;phone</b></td><td><?php echo $results[11]; ?></td></tr>
    <td><b>MMSI</b></td><td><?php echo $results[12]; ?></td></tr>
    <td><b>IMO</b></td><td><?php echo $results[13]; ?></td></tr>
    <td><b>Port</b></td><td><?php echo $results[14]; ?></td></tr>
    <td><b>Actif</b></td><td><?php echo $results[15]; ?></td></tr>
    <td><b>Code Balise</b></td><td><?php echo $results[16]; ?></td></tr>
    <td><b>Type Balise</b></td><td><?php echo $results[17]; ?></td></tr>
    <td><b>Inconnue</b></td><td><?php echo $results[18]; ?></td></tr>
    <td><b>Type navire</b></td><td><?php echo $results[19]; ?></td></tr>
    </tr>
</table>

<br/>
<button onClick="goBack()">Retourner</button>
<?php
foot();
