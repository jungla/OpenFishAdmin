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
    
        $query = "SELECT maree.id, maree.username, maree.datetime, navire, country, year, port_d, date_d, port_a, date_a"
        . " FROM iccat.maree "
        . "WHERE maree.id = '$id'";    

#print $query;

$results = pg_fetch_array(pg_query($query));

?>
<table id="results">
    <tr><td><b>Date & Utilisateur</b></td><td><?php echo $results[2]." ".$results[1]; ?></td></tr>
    <tr><td><b>Navire</b></td><td><?php echo $results[3]; ?></td></tr>
    <tr><td><b>Nationalite Navire</b></td><td><?php echo $results[4]; ?></td></tr>
    <tr><td><b>Ann&eacute;e maree</b></td><td><?php echo $results[5]; ?></td></tr>    
    <tr><td><b>Port et date depart</b></td><td><?php echo $results[6]; ?></td></tr>    
    <tr><td><b>Port et date arrive</b></td><td><?php echo $results[7]; ?></td></tr>
</table>

<?php
        $query = "SELECT lance.id, lance.username, lance.datetime, id_maree, maree.navire, maree.year, date_c, heure_c, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment, st_x(location), st_y(location)"
        . " FROM iccat.lance "
        . "LEFT JOIN iccat.maree ON iccat.lance.id_maree = iccat.maree.id "
        . "WHERE id_maree = '$id'";

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>

    <h3>Lancee pour cette maree</h3>
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