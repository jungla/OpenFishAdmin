<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'home';

top();

//top_login();

if ($_GET['action'] == 'show') {

    $id_navire = $_POST['id_navire'];
    $date_d = $_POST['date_d'];
    $date_f = $_POST['date_f'];

    # SELECT * FROM vms.positions

    $query = "SELECT navire FROM vms.navire WHERE id = '$id_navire'";
    print "<h3>";
    print pg_fetch_row(pg_query($query))[0]."</h3>";

    print "<table  id=\"results\">";

    $query = "SELECT * FROM vms.positions WHERE id_navire = '$id_navire' AND date_p > '$date_d' AND date_p < '$date_f'";
    print "<tr><td><b>".pg_num_rows(pg_query($query))."</b> positions VMS</td></tr>";

    $query = "SELECT * FROM thon.captures LEFT JOIN thon.lance ON lance.id = captures.id_lance WHERE thon.lance.id_navire = '$id_navire' AND thon.lance.date_c > '$date_d' AND thon.lance.date_c < '$date_f'";
    $nrecs = pg_num_rows(pg_query($query));

    if ($nrecs > 0) {
        print "<tr><td><b>".$nrecs."</b> captures p&ecirc;che tonnier (logbook capitaine)</td>"
                . "</tr>";
    }

    $query = "SELECT * FROM crevette.lance WHERE crevette.lance.id_navire = '$id_navire' AND crevette.lance.date_l > '$date_d' AND crevette.lance.date_l < '$date_f'";
    $nrecs = pg_num_rows(pg_query($query));

    if ($nrecs > 0) {
        print "<tr><td><b>".$nrecs."</b> lance p&ecirc;che crevettier (logbook capitaine)</td></tr>";
    }

    $query = "SELECT * FROM seiners.route WHERE id_navire = '$id_navire' AND date > '$date_d' AND date < '$date_f'";
    $nrecs = pg_num_rows(pg_query($query));

    if ($nrecs > 0) {
        print "<tr><td><b>".$nrecs."</b> seiner points d'activit&eacute; (programme observateurs)</td></tr>";
    }

    $query = "SELECT * FROM trawlers.route WHERE id_navire = '$id_navire' AND date > '$date_d' AND date < '$date_f'";
    $nrecs = pg_num_rows(pg_query($query));

    if ($nrecs > 0) {
        print "<tr><td><b>".$nrecs."</b> trawlers points d'activit&eacute; (programme observateurs)</td></tr>";
    }

    print "</table>";

    //print "<img src=\"graph_all.php?date_d=$date_d&date_f=$date_f&id_navire=$id_navire\" />";
    # SELECT * FROM thon.captures
    # SELECT * FROM seiners.route



} else {
?>

<!-- <h2>Project Description</h2>
<p>This web site is the result of a collaboration between WCS and local authorities in Congo and Gabon (DGPA, ANPA and ANPN).
    The website allows registered users to <b>input</b>, <b>edit</b> and <b>visualize</b> records from the artisanal fishery program as collected by observers and DGPA. This work is part of the <b>Geospatial Database for Gabon Bleu</b>.</p>
-->

<h2>Dernières données disponibles</h2>
<?php
print "<table  id=\"results\">";

$query = "SELECT date_p, username FROM vms.positions WHERE date_p IS NOT NULL ORDER BY date_p DESC LIMIT 1";
$result = pg_fetch_row(pg_query($query));
print "<tr><td><b>Position VMS:</b> <i>$result[0] - $result[1]</i></td></tr>";

$query = "SELECT date_c, username FROM thon.lance WHERE date_c IS NOT NULL ORDER BY date_c DESC LIMIT 1";
$result = pg_fetch_row(pg_query($query));
print "<tr><td><b>Logbook thoniers:</b> <i>$result[0] - $result[1]</i></td></tr>";

$query = "SELECT date_l, username FROM crevette.lance WHERE date_l IS NOT NULL ORDER BY date_l DESC LIMIT 1";
$result = pg_fetch_row(pg_query($query));
print "<tr><td><b>Logbook crevettiers</b> <i>$result[0] - $result[1]</i></td></tr>";

$query = "SELECT date, username FROM seiners.route WHERE date IS NOT NULL ORDER BY date DESC LIMIT 1";
$result = pg_fetch_row(pg_query($query));
print "<tr><td><b>Programme Observateurs Senneurs</b> <i>$result[0] - $result[1]</i></td></tr>";

$query = "SELECT date, username FROM trawlers.route WHERE date IS NOT NULL ORDER BY date DESC LIMIT 1";
$result = pg_fetch_row(pg_query($query));
print "<tr><td><b>Programme Observateurs Chalutier:</b> <i>$result[0] - $result[1]</i></td></tr>";

print "</table>";

?>

<h2>Rechercher tous les jeux de donn&eacute;es</h2>
<form method="post" action="<?php echo $self;?>?source=thon&table=lance&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border">
    <tr>
    <td><b>Navire</b></td>
    <td><b>Date debut</b></td>
    <td><b>Date fin</b></td>
    </tr>
    <tr>
    <td>
    <select name="id_navire">
    <?php
    $result = pg_query("SELECT id, navire FROM vms.navire WHERE navire NOT LIKE 'M\_%' ORDER BY navire");
    while($row = pg_fetch_row($result)) {
            print "<option value=\"$row[0]\">$row[1]</option>";
    }
    ?>
    </select>
    </td>
    <td>
    <input type="date" name="date_d" value="2015-01-01">
    </td>
    <td>
    <input type="date" name="date_f" value="2018-01-01">
    </td>
    </tr>
    </table>
    <input type="submit" name="Filter" value="filter" />
    </fieldset>
    </form>

<h2>Contenu de la base de donn&eacute;es sur la p&ecirc;che industrielle</h2>
<h3>Statistique de production</h3>
<ul>
    <li>Donn&eacute;es de <a href="./industrial_records.php#1">p&ecirc;che au senne</a> collect&eacute;es via le <b>programme Observateurs</b></li>
    <li>Donn&eacute;es de <a href="./industrial_records.php#2">p&ecirc;che chalutier</a> collect&eacute;es via le <b>programme Observateurs</b></li>
    <li>Donn&eacute;es de <a href="./industrial_records.php#3">p&ecirc;che thonier</a> collect&eacute;es <b>logbook capitaine</b></li>
    <li>Donn&eacute;es de <a href="./industrial_records.php#4">p&ecirc;che crevettier</a> collect&eacute;es <b>logbook capitaine</b></li>
</ul>

<h3>Donn&eacute;es THEMIS</h3>
<ul>
    <li>Donn&eacute;es VMS de <a href="./industrial_tracking.php">p&ecirc;che au senne et chalutier</a> collect&eacute;es via <b>THEMIS</b></li>
</ul>


<!--
<h2>Website Capabilities</h2>
<p>In the current version, the web site allows data entry, edit and visualization.</p>

<ol>
<li>Data is <b>inserted</b> via web interface at the web page <a href="./input.php">Input Data</a></li>
<li>Data can be <b>retrieved</b> and edited at the web page <a href="./edit.php">Edit Data</a></li>
 <li>Data can be <b>visualized</b> via either the native <a href="./visualize.php">Geoserver interface</a> or <a href="./visualize.php">Google Earth</a></li>
<li>Stored data can be <b>viewed</b> at the web page <a href="./view.php">View Data</a></li>
<li>Data is stored on a <b>PostgreSQL</b> and can be accessed <b>simultaneously</b> by multiple users</li>
<li>Data can be manipulated via <a href="./visualize.php">QGIS/ArcGIS</a>
</ol>-->

<br/>

<?php
}

foot();
