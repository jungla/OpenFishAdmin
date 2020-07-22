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

if ($table == 'lance') {

if ($_POST['submit'] == "Enregistrer") {

    $lon_deg_d = $_POST['lon_deg_d'];
    $lat_deg_d = $_POST['lat_deg_d'];
    $lon_min_d = $_POST['lon_min_d'];
    $lat_min_d = $_POST['lat_min_d'];

    $lon_d = $lon_deg_d+$lon_min_d/60;
    $lat_d = $lat_deg_d+$lat_min_d/60;

    if ($lon_d == "" OR $lat_d == "") {
        $point_d = "NULL";
    } else {
        if ($_POST['NS'] == 'S') {$lat_d = -1*$lat_d;}
        if ($_POST['EW'] == 'E') {$lon_d = -1*$lon_d;}
        $point_d = "'POINT($lon_d $lat_d)'";
    }

    $lon_feg_f = $_POST['lon_feg_f'];
    $lat_feg_f = $_POST['lat_feg_f'];
    $lon_min_f = $_POST['lon_min_f'];
    $lat_min_f = $_POST['lat_min_f'];

    $lon_f = $lon_feg_f+$lon_min_f/60;
    $lat_f = $lat_feg_f+$lat_min_f/60;

    if ($lon_f == "" OR $lat_f == "") {
        $point_f = "NULL";
    } else {
        if ($_POST['NS'] == 'S') {$lat_f = -1*$lat_f;}
        if ($_POST['EW'] == 'E') {$lon_f = -1*$lon_f;}
        $point_f = "'POINT($lon_f $lat_f)'";
    }

    $id_navire = $_POST['id_navire'];
    $date_l = $_POST['date_l'];
    $lance = $_POST['lance'];
    $t_zone = $_POST['t_zone'];
    $h_d = $_POST['h_d'];
    $h_f = $_POST['h_f'];
    $D_d = comma2dot($_POST['D_d']);
    $D_f = comma2dot($_POST['D_f']);
    $T_d = comma2dot($_POST['T_d']);
    $rejets = $_POST['rejets'];

    #navire, country, port_d, port_a, date_d, date_a, ndays, date_c, heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, comment, st_x(location), st_y(location)
        $query = "INSERT INTO crevette.lance "
                . "(username, datetime, id_navire, date_l, t_zone, lance, h_d, h_f, D_d, D_f, T_d, rejets, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c_cre, cc_cre, o_cre, v6_cre, location_d, location_f) "
                . "VALUES ('$username', now(), '$id_navire', '$date_l', '$t_zone', '$lance', '$h_d', '$h_f', '$D_d', '$D_f', '$T_d', '$rejets', '"
                .$_POST['c0_cre']."', '".$_POST['c1_cre']."', '".$_POST['c2_cre']."', '".$_POST['c3_cre']."', '"
                .$_POST['c4_cre']."', '".$_POST['c5_cre']."', '".$_POST['c6_cre']."', '".$_POST['c7_cre']."', '"
                .$_POST['c8_cre']."', '".$_POST['c_cre']."', '".$_POST['cc_cre']."', '".$_POST['o_cre']."', '".$_POST['v6_cre']."', ST_GeomFromText($point_d,4326), ST_GeomFromText($point_f,4326))";

    $query = str_replace('\'-- \'', 'NULL', $query);
    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
        echo "<p>".$query,"</p>";
        msg_queryerror();
    } else {
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/crevette/input_crevette.php?source=crevette&table=lance");
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
    <b>Date lance</b>
    <br/>
    <input type="date" size="10" name="date_l" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Zone</b>
    <br/>
    <select name="t_zone">
    <?php
        $result = pg_query("SELECT id, zone FROM crevette.t_zone ORDER BY zone");
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
    <b>Numero lance</b>
    <br/>
    <input type="text" size="5" name="lance" value="<?php echo $results[6];?>" />
    <br/>
    <br/>
    <b>Heure debut lance</b>
    <br/>
    <input type="time" size="10" name="h_d" value="<?php echo $results[7];?>" />
    <br/>
    <br/>
    <b>Heure fin lance</b>
    <br/>
    <input type="time" size="10" name="h_f" value="<?php echo $results[8];?>" />
    <br/>
    <br/>
    <b>Profondeur debut lance</b> [m]
    <br/>
    <input type="text" size="5" name="D_d" value="<?php echo $results[9];?>" />
    <br/>
    <br/>
    <b>Profondeur fin lance</b> [m]
    <br/>
    <input type="text" size="5" name="D_f" value="<?php echo $results[10];?>" />
    <br/>
    <br/>
    <b>Temperature eau debut lance</b> [C]
    <br/>
    <input type="text" size="3" name="T_d" value="<?php echo $results[11];?>" />
    <br/>
    <br/>
    <b>Rejets</b> [kg]
    <br/>
    <input type="text" size="5" name="rejets" value="<?php echo $results[12];?>" />
    <br/>
    <br/>
    <b>Poids des crevettes par cat&eacute;gorie [kg]</b>
    <table>
        <tr><td><b>CAT 0</b></td><td><b>CAT 1</b></td><td><b>CAT 2</b></td><td><b>CAT 3</b></td><td><b>CAT 4</b></td><td><b>CAT 5</b></td><td><b>CAT 6</b></td><td><b>CAT 7</b></td><td><b>CAT 8</b></td></tr>
        <tr>
            <td><input type="text" size="3" name="c0_cre" value="<?php print $results[13]; ?>"/></td>
            <td><input type="text" size="3" name="c1_cre" value="<?php print $results[14]; ?>"/></td>
            <td><input type="text" size="3" name="c2_cre" value="<?php print $results[15]; ?>"/></td>
            <td><input type="text" size="3" name="c3_cre" value="<?php print $results[16]; ?>"/></td>
            <td><input type="text" size="3" name="c4_cre" value="<?php print $results[17]; ?>"/></td>
            <td><input type="text" size="3" name="c5_cre" value="<?php print $results[18]; ?>"/></td>
            <td><input type="text" size="3" name="c6_cre" value="<?php print $results[19]; ?>"/></td>
            <td><input type="text" size="3" name="c7_cre" value="<?php print $results[20]; ?>"/></td>
            <td><input type="text" size="3" name="c8_cre" value="<?php print $results[21]; ?>"/></td>
        </tr>
    </table>
    <b>Petit crevette cassees (C)</b> [kg]
    <br/>
    <input type="text" size="3" name="c_cre" value="<?php echo $results[22];?>" />
    <br/>
    <br/>
    <b>Grand crevette cassees (CC)</b> [kg]
    <br/>
    <input type="text" size="3" name="cc_cre" value="<?php echo $results[23];?>" />
    <br/>
    <br/>
    <b>Crevette cassees (O)</b> [kg]
    <br/>
    <input type="text" size="3" name="o_cre" value="<?php echo $results[24];?>" />
    <br/>
    <br/>
    <b>Vrac crevette cassees</b> [kg]
    <br/>
    <input type="text" size="3" name="v6_cre" value="<?php echo $results[25];?>" />
    <br/>
    <br/>
    <b>Point debut GPS</b>
    <br/>
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_d" value="<?php echo abs($lat_deg_d);?>" />&deg;
    <input type="text" size="5" name="lat_min_d" value="<?php echo abs($lat_min_d);?>" />&prime;
    <select name="NS">
        <option value="N" <?php if($lat_deg_d >= 0){print "selected";} ?>>N</option>
        <option value="S" <?php if($lat_deg_d < 0){print "selected";} ?>>S</option>
    </select>
    <br/><br/>
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_d" value="<?php echo abs($lon_deg_d);?>" />&deg;
    <input type="text" size="5" name="lon_min_d" value="<?php echo abs($lon_min_d);?>" />&prime;
    <select name="EW">
        <option value="E" <?php if($lon_deg_d >= 0){print "selected";} ?>>E</option>
        <option value="W" <?php if($lon_deg_d < 0){print "selected";} ?>>W</option>
    </select>
    <br/>
    <br/>
    <b>Point fin GPS</b>
    <br/>
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_f" value="<?php echo abs($lat_deg_f);?>" />&deg;
    <input type="text" size="5" name="lat_min_f" value="<?php echo abs($lat_min_f);?>" />&prime;
    <select name="NS">
        <option value="N" <?php if($lat_deg_f >= 0){print "selected";} ?>>N</option>
        <option value="S" <?php if($lat_deg_f < 0){print "selected";} ?>>S</option>
    </select>
    <br/><br/>
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_f" value="<?php echo abs($lon_deg_f);?>" />&deg;
    <input type="text" size="5" name="lon_min_f" value="<?php echo abs($lon_min_f);?>" />&prime;
    <select name="EW">
        <option value="E" <?php if($lon_deg_f >= 0){print "selected";} ?>>E</option>
        <option value="W" <?php if($lon_deg_f < 0){print "selected";} ?>>W</option>
    </select>
    <br/>
    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/><br/>

<?php
}

} else if ($table == 'captures') {

//se submit = go!
if ($_POST['submit'] == "Enregistrer") {
    $id_navire = $_POST['id_navire'];
    $date_l = $_POST['date_l'];
    $lance = $_POST['lance'];

    $id_species = $_POST['id_species'];
    $t_taille = $_POST['t_taille'];
    $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);

    $q_id = "SELECT id FROM crevette.lance "
            . "WHERE id_navire = '$id_navire' AND date_l = '$date_l' AND lance = '$lance'";
    $id_lance = pg_fetch_row(pg_query($q_id))[0];

    #navire, country, port_d, port_a, date_d, date_a, ndays, date_c, heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, comment, st_x(location), st_y(location)
    $query = "INSERT INTO crevette.capture "
        . "(username, datetime, id_lance, id_species, t_taille, poids) "
        . "VALUES ('$username', now(), '$id_lance', '$id_species', '$t_taille', '$poids')";

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/crevette/input_crevette.php?source=crevette&table=lance");
    }


  $controllo = 1;

}

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Navire</b>
    <br/>
    <select id="id_navire" name="id_navire" onchange="menu_pop_1('id_navire','date_l','id_navire','date_l','crevette.lance')">
    <option  value="none">Please choose one</option>
    <?php
    $result = pg_query("SELECT DISTINCT id_navire, navire FROM crevette.lance "
            . "LEFT JOIN vms.navire ON crevette.lance.id_navire = vms.navire.id "
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
    <b>Date lance</b>
    <br/>
    <select id="date_l" name="date_l" onchange="menu_pop_2('id_navire','date_l','lance','id_navire','date_l','lance','crevette.lance')">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT date_l FROM crevette.lance "
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
    <b>Lance</b>
    <br/>
    <select id="lance" name="lance" >
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT lance FROM crevette.lance WHERE id_navire = '$results[3]' AND date_l = '$results[4]' ORDER BY lance");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[5]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    Vous ne pouvez pas trouver un lance? Ajoutez un nouveau <a href="input_crevette.php?source=crevette&table=lance">lance</a>.
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select id="species" name="id_species">
    <?php
    $result = pg_query("SELECT id, francaise FROM fishery.species WHERE category = 'crevette' ORDER BY francaise");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[6]) {
            print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
        } else {
            print "<option value=\"$row[0]\">$row[1]</option>";
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
        $result = pg_query("SELECT id, taille FROM crevette.t_taille ORDER BY id");
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
