<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'trawlers';

$username = $_SESSION['username'];

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
if ($_GET['action'] != "") {$_SESSION['path'][2] = $_GET['action'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];
$action = $_SESSION['path'][2];

$self = filter_input(INPUT_SERVER, 'PHP_SELF');
$host = filter_input(INPUT_SERVER, 'HTTP_HOST');

print "<h2>".label2name($source)." ".label2name($table)."</h2>";

if ($table == 'maree') {

if ($_POST['submit'] == "Enregistrer") {

    $id_navire = $_POST['id_navire'];
    $date_d = $_POST['date_d'];
    $date_r = $_POST['date_r'];
    $captain = $_POST['captain'];
    $nlance = $_POST['nlance'];
    $port_d = $_POST['port_d'];
    $port_r = $_POST['port_r'];
    $t_zone = $_POST['t_zone'];
    $rejets = $_POST['rejets'];

# id_navire, date_d, date_r, captain, nlance, port_d, port_r, t_zone, rejets

    $query = "INSERT INTO poisson.maree "
            . "(username, datetime, id_navire, date_d, date_r, captain, nlance, port_d, port_r, t_zone, rejets) "
            . "VALUES ('$username', now(), '$id_navire', '$date_d', '$date_r', '$captain', '$nlance', '$port_d', '$port_r', '$t_zone', '$rejets')";

    $query = str_replace('\'-- \'', 'NULL', $query);
    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
        echo "<p>".$query,"</p>";
        msg_queryerror();
    } else {
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/poisson/input_poisson.php?source=poisson&table=lance");
    }

    $controllo = 1;
}

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Navire</b>
    <br/>
    <select name="id_navire">
    <?php
        $result = pg_query("SELECT id, navire FROM vms.navire WHERE navire NOT LIKE 'M\_%' ORDER BY navire");
        while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[3]) {
                print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
            } else {
                print "<option value=\"$row[0]\">$row[1]</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Date debut maree</b>
    <br/>
    <input type="date" size="10" name="date_d" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Date fin maree</b>
    <br/>
    <input type="date" size="10" name="date_r" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Nom et prenom capitaine</b>
    <br/>
    <input size="50" name="captain" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Nombre de lacee totale</b>
    <br/>
    <input size="4" name="nlance" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Port depart</b>
    <br/>
    <input size="20" name="port_d" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Port retour</b>
    <br/>
    <input size="20" name="port_r" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Zone</b>
    <br/>
    <select name="t_zone">
    <option value="">Aucun</option>
    <?php
        $result = pg_query("SELECT id, zone FROM poisson.t_zone ORDER BY zone");
        while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[5]) {
                print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
            } else {
                print "<option value=\"$row[0]\">$row[1]</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Rejet totale [kg]</b>
    <br/>
    <input type="text" size="10" name="rejets" value="<?php echo $results[6];?>" />
    <br/>
    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/><br/>

<?php
}

} else if ($table == 'captures') {

if ($_POST['submit'] == "Enregistrer") {
    $id_navire = $_POST['id_navire'];
    $id_maree = $_POST['id_maree'];

    $id_species = $_POST['id_species'];
    $t_taille = $_POST['t_taille'];
    $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);

    $query = "INSERT INTO poisson.capture "
        . "(username, datetime, id_maree, id_species, t_taille, poids) "
        . "VALUES ('$username', now(), '$id_maree', '$id_species', '$t_taille', '$poids')";

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        print $query;
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/poisson/input_poisson.php?source=poisson&table=lance");
    }


  $controllo = 1;

}

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Navire</b>
    <br/>
    <select id="id_navire" name="id_navire" onchange="menu_pop_maree('id_navire','date_d','poisson.maree')">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT id_navire, navire FROM poisson.maree "
            . "LEFT JOIN vms.navire ON poisson.maree.id_navire = vms.navire.id "
            . "WHERE navire IS NOT NULL "
            . "ORDER BY navire");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[3]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Date maree</b>
    <br/>
    <select id="date_d" name="id_maree">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT date_d, date_r FROM poisson.maree "
            . "WHERE id_navire = '$results[3]' ORDER BY date_l");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[4]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select name="id_species" class="chosen-select">
    <?php
    $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species  FROM fishery.species WHERE fishery.species.category LIKE '%industrial%' ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[6]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
        } else {
            print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Taille</b>
    <br/>
    <select name="t_taille">
    <option  value="">Aucun</option>
    <?php
        $result = pg_query("SELECT id, taille FROM poisson.t_taille ORDER BY id");
        while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[11]) {
                print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
            } else {
                print "<option value=\"$row[0]\">$row[1]</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Poids</b> [kg]
    <br/>
    <input type="text" size="5" name="poids" value="<?php echo $results[12];?>" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

<?php
}

}

foot();
