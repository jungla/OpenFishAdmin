<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'thon';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_id_species'] = $_POST['f_id_species'];
$_SESSION['filter']['f_s_navire'] = $_POST['f_s_navire'];
$_SESSION['filter']['f_s_date'] = $_POST['f_s_date'];

if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}
if ($_GET['f_s_navire'] != "") {$_SESSION['filter']['f_s_navire'] = $_GET['f_s_navire'];}
if ($_GET['f_s_date'] != "") {$_SESSION['filter']['f_s_date'] = $_GET['f_s_navire'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    $q_id = "SELECT *, fishery.species.id, fishery.species.FAO, fishery.species.family, fishery.species.genus, fishery.species.species, st_x(location), st_y(location) FROM thon.captures "
        . "LEFT JOIN fishery.species ON fishery.species.id = thon.captures.id_species "
        . "WHERE captures.id_maree = '".$_GET['id']."'";
    
    print $q_id;
    
    $r_id = pg_query($q_id);
    
    $results = pg_fetch_row($r_id);
    
    ?>
    
    <br/>

    <h3>Donnees lanc&eacute;</h3>
    <table>
        <tr>
            <td><b>Date lanc&eacute;</b><br/>[aaaa-mm-jj]</td>
            <td><b>Heure lanc&eacute;</b><br/>[hh:mm:ss]</td>
            <td><b>Numbre lanc&eacute;</b></td>
            <td><b>EEZ</b></td>
            <td><b>Banc libre</b></td>
            <td><b>Code balise</b></td>
            <td><b>Remarque</b></td>
        </tr>
        <tr>
            <td><?php echo $results[10]; ?></td>
            <td><?php echo $results[11]; ?></td>
            <td><?php echo $results[12]; ?></td>
            <td><?php echo $results[13]; ?></td>
            <td>
            <?php if ($results[19] == 't') {print 'Oui';} ?>
            <?php if ($results[19] != 't') {print 'No';} ?>
            </td>
            <td><?php echo $results[20]; ?></td>
            <td><?php echo $results[25]; ?></td>
        </tr>
    </table>
    <br/>
    <table>
        <tr>
            <td colspan="2"><b>Point GPS</b></td>
            <td colspan="2"><b>YFT</b></td>
            <td colspan="2"><b>SKJ</b></td>
            <td colspan="2"><b>BET</b></td>
            <td colspan="2"><b>ALB</b></td>
            <td colspan="3"><b>Rejete</b></td>
        </tr>
        <tr>
            <td>Longitude</td>
            <td>Latitude</td>
            <td>Taille [kg]</td>
            <td>Poids [t]</td>
            <td>Taille [kg]</td>
            <td>Poids [t]</td>
            <td>Taille [kg]</td>
            <td>Poids [t]</td>
            <td>Taille [kg]</td>
            <td>Poids [t]</td>
            <td>Espece</td>
            <td>Taille [kg]</td>
            <td>Poids [t]</td>
        </tr>
        <tr>
            <td>
            <?php echo $lat_deg;?> &deg;
            <?php echo $lat_min;?> &prime;
                <?php if($lat_deg > 0) {print "N";} ?></option>
                <?php if($lat_deg < 0) {print "S";} ?></option>
            </select>
            </td>
            <td>
            <?php echo abs($lon_deg);?> &deg;
            <?php echo abs($lon_min);?> &prime;
                <?php if($lon_deg > 0) {print "E";} ?></option>
                <?php if($lon_deg < 0) {print "W";} ?></option>
            </td>
            <td><?php if($results[32] == 'YFT'){ echo $results[23];} ?></td>
            <td><?php if($results[32] == 'YFT'){ echo $results[24];} ?></td>
            <td><?php if($results[32] == 'SKJ'){ echo $results[23];} ?></td>
            <td><?php if($results[32] == 'SKJ'){ echo $results[24];} ?></td>
            <td><?php if($results[32] == 'BET'){ echo $results[23];} ?></td>
            <td><?php if($results[32] == 'BET'){ echo $results[24];} ?></td>
            <td><?php if($results[32] == 'ALB'){ echo $results[23];} ?></td>
            <td><?php if($results[32] == 'ALB'){ echo $results[24];} ?></td>
            <td>
                <select name="id_species_rej">
                <option value="none">Aucun</option>
                <?php 
                    $result = pg_query("SELECT DISTINCT id, FAO FROM fishery.species WHERE FAO is not NULL ORDER BY FAO");
                    while($row = pg_fetch_row($result)) {
                        if ($row[1] == $results[32] AND $results[21] == 't') {
                            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
                        } else {
                            print "<option value=\"$row[0]\">".$row[1]."</option>";
                        }
                    }
                 
            ?>  
            </select>
            </td>
            <td><?php if($results[21] == 't'){ echo $results[24];} ?></td>
            <td><?php if($results[21] == 't'){ echo $results[24];} ?></td>
        </tr>
    </table>
    <br/>
    <h3>Donnees environmental</h3>
    <table>
        <tr>
            <td><b>Temperature Eau</b></td>
            <td><b>Vitesse vent</b></td>
            <td><b>Direction vent</b></td>
            <td><b>Vitesse courant</b></td>
        </tr>
        <tr>
            <td><?php echo $results[14]; ?></td>
            <td><?php echo $results[15]; ?></td>
            <td><?php echo $results[16]; ?></td>
            <td><?php echo $results[17]; ?></td>
        </tr>
    </table>
    <br/>
    
    <br/>
    <br/>

<?php

foot();
