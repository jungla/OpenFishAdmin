<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'seiners';

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

if (right_write($_SESSION['username'],5,2)) {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

if ($table == 'route') {

//se submit = go!
if ($_POST['submit'] == "Enregistrer") {

    # loads shapefile to temporary db table (syncs later)
    # a random table name should be generated here and passed in SESSION for later use
    $id_navire = $_POST['id_navire'];
    $maree = htmlspecialchars($_POST['maree'],ENT_QUOTES);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $speed = htmlspecialchars($_POST['speed'],ENT_QUOTES);
    $t_activite = $_POST['t_activite'];
    $t_neighbours = $_POST['t_neighbours'];
    $t_detection = $_POST['t_detection'];
    $t_systeme = $_POST['t_systeme'];

    $temperature = htmlspecialchars($_POST['temperature'],ENT_QUOTES);
    $windspeed = htmlspecialchars($_POST['windspeed'],ENT_QUOTES);
    $comment = htmlspecialchars($_POST['comment'],ENT_QUOTES);

    $lon_deg_dms = htmlspecialchars($_POST['lon_deg_dms'],ENT_QUOTES);
    $lat_deg_dms = htmlspecialchars($_POST['lat_deg_dms'],ENT_QUOTES);
    $lon_min_dms = htmlspecialchars($_POST['lon_min_dms'],ENT_QUOTES);
    $lat_min_dms = htmlspecialchars($_POST['lat_min_dms'],ENT_QUOTES);
    $lon_sec_dms = htmlspecialchars($_POST['lon_sec_dms'],ENT_QUOTES);
    $lat_sec_dms = htmlspecialchars($_POST['lat_sec_dms'],ENT_QUOTES);
    $lon_deg_dm = htmlspecialchars($_POST['lon_deg_dm'],ENT_QUOTES);
    $lat_deg_dm = htmlspecialchars($_POST['lat_deg_dm'],ENT_QUOTES);
    $lon_min_dm = htmlspecialchars($_POST['lon_min_dm'],ENT_QUOTES);
    $lat_min_dm = htmlspecialchars($_POST['lat_min_dm'],ENT_QUOTES);
    $lon_deg_d = htmlspecialchars($_POST['lon_deg_d'],ENT_QUOTES);
    $lat_deg_d = htmlspecialchars($_POST['lat_deg_d'],ENT_QUOTES);

    if($_POST['t_coord'] == 'DDMMSS') {
      $lon = $lon_deg_dms+$lon_min_dms/60+$lon_sec_dms/3600;
      $lat = $lat_deg_dms+$lat_min_dms/60+$lon_sec_dms/3600;
    } elseif ($_POST['t_coord'] == 'DDMM') {
      $lon = $lon_deg_dm+$lon_min_dm/60;
      $lat = $lat_deg_dm+$lat_min_dm/60;
    } elseif ($_POST['t_coord'] == 'DD') {
      $lon = $lon_deg_d;
      $lat = $lat_deg_d;
    }

    if ($lon == "" OR $lat == "") {
        $point = "NULL";
    } else {
        if ($_POST['NS'] == 'S') {$lat = -1*$lat;}
        if ($_POST['EO'] == 'O') {$lon = -1*$lon;}
        $point = "'POINT($lon $lat)'";
    }

    $query = "INSERT INTO seiners.route "
            . "(username, datetime, id_navire, maree, date, time, speed, t_activite, t_neighbours, temperature, windspeed, comment, t_detection, t_systeme, location) "
            . "VALUES ('$username', now(), '$id_navire', '$maree', '$date', '$time', '$speed', '$t_activite', '$t_neighbours','$temperature', '$windspeed', '$comment', '$t_detection', '$t_systeme', ST_GeomFromText($point,4326))";

    $query = str_replace('\'-- \'', 'NULL', $query);
    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
        echo "<p>".$query,"</p>";
        msg_queryerror();
    } else {
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_seiners.php");
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
    <b>Maree</b>
    <br/>
    <input type="text" size="20" name="maree" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Date</b>
    <br/>
    <input type="date" size="20" name="date" value="<?php echo $results[5]; ?>"/>
    <br/>
    <br/>
    <b>Heure</b>
    <br/>
    <input type="time" size="20" name="time" value="<?php echo $results[8]; ?>"/>
    <br/>
    <br/>
    <b>Vitesse</b> (nd)
    <br/>
    <input type="text" size="5" name="speed" value="<?php echo $results[9]; ?>"/>
    <br/>
    <br/>
    <b>Activit&eacute; bateau</b>
    <br/>
    <select name="t_activite">
    <?php
    $result = pg_query("SELECT id, activite FROM seiners.t_activite ORDER BY id");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Activit&eacute; autour</b>
    <br/>
    <select name="t_neighbours">
    <?php
    $result = pg_query("SELECT id, neighbours FROM seiners.t_neighbours ORDER BY id");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Temperature</b> (C)
    <br/>
    <input type="text" size="5" name="temperature" value="<?php echo $results[11];?>" />
    <br/>
    <br/>
    <b>Vitesse vent</b> (nd)
    <br/>
    <input type="text" size="5" name="windspeed" value="<?php echo $results[12];?>" />
    <br/>
    <br/>
    <b>Point GPS</b>
    <br/>
    <input type="radio" name="t_coord" value="DDMMSS" onchange="show('','DDMMSS');hide('DDMM');hide('DD')">DD&deg;MM&prime;SS.SS&prime;&prime;
    <input type="radio" name="t_coord" value="DDMM" checked onchange="show('','DDMM');hide('DDMMSS');hide('DD')">DD&deg;MM.MM&prime;
    <input type="radio" name="t_coord" value="DD" onchange="show('','DD');hide('DDMM');hide('DDMMSS')">DD.DD&deg;
    <br/>
    <br/>

    <div class="DDMMSS" style="display:none">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_dms" />&deg;
    <input type="text" size="5" name="lat_min_dms" />&prime;
    <input type="text" size="5" name="lat_sec_dms" />&prime;&prime;
    </div>

    <div class="DDMM">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_dm" />&deg;
    <input type="text" size="5" name="lat_min_dm" />&prime;
    </div>

    <div class="DD" style="display:none">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_d" />&deg;
    </div>

    <select name="NS">
        <option value="N" >N</option>
        <option value="S" >S</option>
    </select>

    <div class="DDMMSS" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dms" />&deg;
    <input type="text" size="5" name="lon_min_dms" />&prime;
    <input type="text" size="5" name="lon_sec_dms" />&prime;&prime;
    </div>

    <div class="DDMM">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dm" />&deg;
    <input type="text" size="5" name="lon_min_dm" />&prime;
    </div>

    <div class="DD" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_d" />&deg;
    </div>
    <select name="EO">
        <option value="E" >E</option>
        <option value="O" >O</option>
    </select>

    <br/>
    <br/>
    <b>Mode detection</b>
    <br/>
    <select name="t_detection">
    <?php
    $result = pg_query("SELECT id, detection FROM seiners.t_detection ORDER BY id");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[14]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Systeme observ&eacute;</b>
    <br/>
    <select name="t_systeme">
    <?php
    $result = pg_query("SELECT id, systeme FROM seiners.t_systeme ORDER BY id");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[15]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>

    <b>Comment</b>
    <br/>
    <textarea type="text" cols="50" rows="5" name="comment" ><?php echo $results[16];?></textarea>
    <br/>
    <br/>

    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/><br/>

<?php
}

} else if ($table == 'objet') {

//se submit = go!
if ($_POST['submit'] == "Enregistrer") {
    $username = $_SESSION['username'];
    $maree = htmlspecialchars($_POST['maree'],ENT_QUOTES);
    $t_zee = $_POST['t_zee'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $t_objet = $_POST['t_objet'];
    $type_balise = htmlspecialchars($_POST['type_balise'],ENT_QUOTES);
    $code_balise = htmlspecialchars($_POST['code_balise'],ENT_QUOTES);
    $t_operation = $_POST['t_operation'];
    $t_appartenance = $_POST['t_appartenance'];
    $t_devenir = $_POST['t_devenir'];
    $remarque = htmlspecialchars($_POST['remarque'],ENT_QUOTES);

    $q_id = "SELECT id FROM seiners.route WHERE maree = '$maree' AND date = '$date' AND time = '$time'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    #username, maree, t_zee, id_route, t_objet, type_balise, code_balise, t_operation, t_appartenance, t_devenir, remarque
    $query = "INSERT INTO seiners.objet "
        . "(username, datetime, maree, t_zee, id_route, t_objet, type_balise, code_balise, t_operation, t_appartenance, t_devenir, remarque) "
        . "VALUES ('$username', now(), '$maree', '$t_zee', '$id_route', '$t_objet', '$type_balise', '$code_balise', '$t_operation', '$t_appartenance', '$t_devenir', '$remarque')";

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_seiners.php");
    }


  $controllo = 1;

}

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Maree</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','seiners.route')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM seiners.route ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Route</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','seiners.route')">
    <option  value="none">Veuillez choisir ci-dessus</option>
    </select>

    <br/>
    <select id="time" name="time" >
    <option  value="none">Veuillez choisir ci-dessus</option>
    </select>

    <br/>
    <br/>
    <b>ZEE</b>
    <br/>
    <select name="t_zee">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, zee FROM seiners.t_zee ORDER BY zee");
    while($row = pg_fetch_row($result)) {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Objet</b>
    <br/>
    <select name="t_objet">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, objet FROM seiners.t_objet ORDER BY objet");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select><br/>
    <br/>
    <b>Type balise</b>
    <br/>
    <input type="text" size="20" name="type_balise"/>
    <br/>
    <br/>
    <b>Code balise</b>
    <br/>
    <input type="text" size="20" name="code_balise"/>
    <br/>
    <br/>
    <b>Operation</b>
    <br/>
    <select name="t_operation">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, operation FROM seiners.t_operation ORDER BY operation");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Appartenance</b>
    <br/>
    <select name="t_appartenance">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, appartenance FROM seiners.t_appartenance ORDER BY appartenance");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Devenir</b>
    <br/>
    <select name="t_devenir">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, devenir FROM seiners.t_devenir ORDER BY devenir");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

<?php
}


} else if ($table == 'thon_ret') {

//se submit = go!
if ($_POST['submit'] == "Enregistrer") {

    $username = $_SESSION['username'];
    $maree = $_POST['maree'];
    $t_zee = $_POST['t_zee'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $n_calee = $_POST['n_calee'];
    $t_type = $_POST['t_type'];
    $h_d = $_POST['h_d'];
    $h_c = $_POST['h_c'];
    $h_f = $_POST['h_f'];
    $vitesse = $_POST['vitesse'];
    $direction = $_POST['direction'];
    $d_max = $_POST['d_max'];
    $sonar = $_POST['sonar'];
    $raison = $_POST['raison'];
    $id_species = $_POST['id_species'];
    $t_categorie = $_POST['t_categorie'];
    $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);
    $cuve = $_POST['cuve'];
    $remarque = htmlspecialchars($_POST['remarque'],ENT_QUOTES);

    $q_id = "SELECT id FROM seiners.route WHERE maree = '$maree' AND date = '$date' AND time = '$time'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    print $q_id;
    #id, datetime, username, maree, t_zee, n_calee, t_type, id_route, n_route, l_route, h_d, h_c, h_f, vitesse, direction,
    #d_max, sonar, raison, id_species, t_categorie, poids, cuve, remarque

    $query = "INSERT INTO seiners.thon_retenue "
            . "(username, datetime, maree, t_zee, n_calee, t_type, id_route, h_d, h_c, h_f, vitesse, direction, d_max, sonar, raison, id_species, t_categorie, poids, cuve, remarque) "
            . "VALUES ('$username', now(), '$maree', '$t_zee', '$n_calee', '$t_type', '$id_route', '$h_d', '$h_c', '$h_f', '$vitesse', '$direction', '$d_max', '$sonar', '$raison', '$id_species', '$t_categorie', '$poids', '$cuve', '$remarque')";

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_seiners.php");
    }


  $controllo = 1;

}

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Maree</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','seiners.route')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM seiners.route ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Route</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','seiners.route')">
    <option  value="none">Veuillez choisir ci-dessus</option>
    </select>
    <br/>
    <select id="time" name="time" >
    <option  value="none">Veuillez choisir ci-dessus</option>
    </select>

    <br/>
    <br/>
    <b>ZEE</b>
    <br/>
    <select name="t_zee">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, zee FROM seiners.t_zee ORDER BY zee");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Numero Calee</b>
    <br/>
    <input type="text" size="20" name="n_calee" />
    <br/>
    <br/>
    <b>Type peche</b>
    <br/>
    <select name="t_type">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, type FROM seiners.t_type ORDER BY type");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[6]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Heure de debut</b>
    <br/>
    <input type="time" size="20" name="h_d" />
    <br/>
    <br/>
    <b>Fin de coulissage</b>
    <br/>
    <input type="time" size="20" name="h_c" />
    <br/>
    <br/>
    <b>Heure de fin</b>
    <br/>
    <input type="time" size="20" name="h_f" />
    <br/>
    <br/>
    <b>Vitesse Courant</b> (nd)
    <br/>
    <input type="text" size="20" name="vitesse" />
    <br/>
    <br/>
    <b>Direction Courant</b>
    <br/>
    <input type="text" size="20" name="direction" />
    <br/>
    <br/>
    <b>Profondeur maximale</b> (m)
    <br/>
    <input type="text" size="20" name="d_max" />
    <br/>
    <br/>
    <b>Pr&eacute;sence de sonar</b>
    <br/>
    <?php
    if($results[16]=="t") {
        print "<input type=\"radio\" name=\"sonar\" value=\"TRUE\" checked=\"checked\"/>Oui<br/>";
        print "<input type=\"radio\" name=\"sonar\" value=\"FALSE\" />Non";
    } else {
        print "<input type=\"radio\" name=\"sonar\" value=\"TRUE\" />Oui<br/>";
        print "<input type=\"radio\" name=\"sonar\" value=\"FALSE\" checked=\"checked\"/>Non";
    }
    ?>
    <br/>
    <br/>
    <b>Raison coup nul</b>
    <br/>
    <input type="text" size="30" name="raison" />
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN seiners.thon_retenue ON fishery.species.id = seiners.thon_retenue.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ("'".$row[0]."'" == $results[25]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Categorie taille</b>
    <br/>
    <select name="t_categorie">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, categorie FROM seiners.t_categorie ORDER BY categorie");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[19]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Poids</b>(kg)
    <br/>
    <input type="text" size="30" name="poids" />
    <br/>
    <br/>
    <b>Code cuve</b>
    <br/>
    <input type="text" size="30" name="cuve" />
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

<?php
}

} else if ($table == 'thon_rej') {


    if ($_POST['submit'] == "Enregistrer") {

        $username = $_SESSION['username'];
        $maree = $_POST['maree'];
        $t_zee = $_POST['t_zee'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $n_calee = $_POST['n_calee'];
        $t_type = $_POST['t_type'];
        $h_d = $_POST['h_d'];
        $h_c = $_POST['h_c'];
        $h_f = $_POST['h_f'];
        $vitesse = $_POST['vitesse'];
        $direction = $_POST['direction'];
        $d_max = $_POST['d_max'];
        $t_raison = $_POST['t_raison'];
        $id_species = $_POST['id_species'];
        $t_categorie = $_POST['t_categorie'];
        $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);
        $monte = $_POST['monte'];
        $photo = $_POST['photo'];
        $remarque = htmlspecialchars($_POST['remarque'],ENT_QUOTES);

        $q_id = "SELECT id FROM seiners.route WHERE maree = '$maree' AND date = '$date' AND time = '$time'";
        $id_route = pg_fetch_row(pg_query($q_id))[0];

        #thon_rejete.id, thon_rejete.datetime, thon_rejete.username, thon_rejete.maree, t_zee, n_calee, t_type, id_route, n_route,
        #l_route, h_d, h_c, h_f, vitesse, direction, d_max, id_species, t_categorie, t_raison, poids, monte, photo, remarque

        $query = "INSERT INTO seiners.thon_rejete "
                . "(username, datetime, maree, t_zee, n_calee, t_type, id_route, h_d, h_c, h_f, vitesse, direction, d_max, t_raison, id_species, t_categorie, poids, monte, photo, remarque) "
                . "VALUES ('$username', now(), '$maree', '$t_zee', '$n_calee', '$t_type', '$id_route', '$h_d', '$h_c', '$h_f', '$vitesse', '$direction', '$d_max', '$t_raison', '$id_species', '$t_categorie', '$poids', '$monte', '$photo', '$remarque')";


        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        } else {
            #print $query;
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_seiners.php");
        }

            $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Maree</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','seiners.route')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM seiners.route ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Route</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','seiners.route')">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT date FROM seiners.route  WHERE maree = '$results[3]' ORDER BY date");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <select id="time" name="time" >
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT time FROM seiners.route WHERE maree = '$results[3]' AND date = '$results[28]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>

    <br/>
    <br/>
    <b>ZEE</b>
    <br/>
    <select name="t_zee">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, zee FROM seiners.t_zee ORDER BY zee");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Numero Calee</b>
    <br/>
    <input type="text" size="20" name="n_calee" />
    <br/>
    <br/>
    <b>Type peche</b>
    <br/>
    <select name="t_type">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, type FROM seiners.t_type ORDER BY type");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Heure de debut</b>
    <br/>
    <input type="time" size="20" name="h_d" />
    <br/>
    <br/>
    <b>Fin de coulissage</b>
    <br/>
    <input type="time" size="20" name="h_c" />
    <br/>
    <br/>
    <b>Heure de fin</b>
    <br/>
    <input type="time" size="20" name="h_f" />
    <br/>
    <br/>
    <b>Vitesse Courant</b> (nd)
    <br/>
    <input type="text" size="10" name="vitesse" />
    <br/>
    <br/>
    <b>Direction Courant</b>
    <br/>
    <input type="text" size="10" name="direction" />
    <br/>
    <br/>
    <b>Profondeur maximale</b> (m)
    <br/>
    <input type="text" size="10" name="d_max" />
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN seiners.thon_rejete ON fishery.species.id = seiners.thon_rejete.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[25]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Categorie taille</b>
    <br/>
    <select name="t_categorie">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, categorie FROM seiners.t_categorie ORDER BY categorie");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Raison rejet</b>
    <br/>
    <select name="t_raison">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, raison FROM seiners.t_raison ORDER BY raison");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select><br/>
    <br/>
    <b>Poids</b> (kg)
    <br/>
    <input type="text" size="10" name="poids" />
    <br/>
    <br/>
    <b>Mont&eacute; sur pont</b>
    <br/>
    <input type="radio" name="monte" value="TRUE" checked="checked"/>Oui<br/>
    <input type="radio" name="monte" value="FALSE" />Non
    <br/>
    <br/>
    <b>Photo/Video</b>
    <br/>
    <input type="text" size="30" name="photo" />
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
}  else if ($table == 'thon_rej_taille') {

    if ($_POST['submit'] == "Enregistrer") {
        $username = $_SESSION['username'];
        $maree = $_POST['maree'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $n_calee = $_POST['n_calee'];
        $id_species = $_POST['id_species'];

        $q_id = "SELECT id FROM seiners.route WHERE maree = '$maree' AND date = '$date' AND time = '$time'";
        $id_route = pg_fetch_row(pg_query($q_id))[0];

        #thon_rejete.id, thon_rejete.datetime, thon_rejete.username, thon_rejete.maree, t_zee, n_calee, t_type, id_route, n_route,
        #l_route, h_d, h_c, h_f, vitesse, direction, d_max, id_species, t_categorie, t_raison, poids, monte, photo, remarque

        $query = "INSERT INTO seiners.thon_rejete_taille "
            . "(username, datetime, maree, n_calee, id_species, id_route, c009, c010, c011, c012, c013, c014, c015, c016, c017, c018, c019, c020, c021, c022, c023, c024, c025, c026, c027, c028, c029, c030, c031, c032, c033, c034, c035, c036, c037, c038, c039, c040, c041, c042, c043, c044, c045, c046, c047, c048, c049, c050, c051, c052, c053, c054, c055, c056, c057, c058, c059, c060, c061, c062, c063, c064, c065, c066, c067, c068, c069, c070, c071, c072, c073, c074, c075, c076, c077, c078, c079, c080, c081, c082, c083, c084, c085, c086, c087, c088, c089, c090, c091, c092, c093, c094, c095, c096, c097, c098, c099, c100, c110, c111, c112, c135, c138, c139, c140, c144, c145, c146, c147, c148, c149, c150, c151, c154, c155, c156, c157, c158, c159, c160, c170) "
            . "VALUES ('$username', 'now()', '$maree', '$n_calee', '$id_species', '$id_route', '".$_POST['c009']."', '".$_POST['c010']."', '".$_POST['c011']."', '".$_POST['c012']."', '".$_POST['c013']."', '".$_POST['c014']."', '".$_POST['c015']."', '".$_POST['c016']."', '".$_POST['c017']."', '".$_POST['c018']."', '".$_POST['c019']."', '".$_POST['c020']."', '".$_POST['c021']."', '".$_POST['c022']."', '".$_POST['c023']."', '".$_POST['c024']."', '".$_POST['c025']."', '".$_POST['c026']."', '".$_POST['c027']."', '".$_POST['c028']."', '".$_POST['c029']."', '".$_POST['c030']."', '".$_POST['c031']."', '".$_POST['c032']."', '".$_POST['c033']."', '".$_POST['c034']."', '".$_POST['c035']."', '".$_POST['c036']."', '".$_POST['c037']."', '".$_POST['c038']."', '".$_POST['c039']."', '".$_POST['c040']."', '".$_POST['c041']."', '".$_POST['c042']."', '".$_POST['c043']."', '".$_POST['c044']."', '".$_POST['c045']."', '".$_POST['c046']."', '".$_POST['c047']."', '".$_POST['c048']."', '".$_POST['c049']."', '".$_POST['c050']."', '".$_POST['c051']."', '".$_POST['c052']."', '".$_POST['c053']."', '".$_POST['c054']."', '".$_POST['c055']."', '".$_POST['c056']."', '".$_POST['c057']."', '".$_POST['c058']."', '".$_POST['c059']."', '".$_POST['c060']."', '".$_POST['c061']."', '".$_POST['c062']."', '".$_POST['c063']."', '".$_POST['c064']."', '".$_POST['c065']."', '".$_POST['c066']."', '".$_POST['c067']."', '".$_POST['c068']."', '".$_POST['c069']."', '".$_POST['c070']."', '".$_POST['c071']."', '".$_POST['c072']."', '".$_POST['c073']."', '".$_POST['c074']."', '".$_POST['c075']."', '".$_POST['c076']."', '".$_POST['c077']."', '".$_POST['c078']."', '".$_POST['c079']."', '".$_POST['c080']."', '".$_POST['c081']."', '".$_POST['c082']."', '".$_POST['c083']."', '".$_POST['c084']."', '".$_POST['c085']."', '".$_POST['c086']."', '".$_POST['c087']."', '".$_POST['c088']."', '".$_POST['c089']."', '".$_POST['c090']."', '".$_POST['c091']."', '".$_POST['c092']."', '".$_POST['c093']."', '".$_POST['c094']."', '".$_POST['c095']."', '".$_POST['c096']."', '".$_POST['c097']."', '".$_POST['c098']."', '".$_POST['c099']."', '".$_POST['c100']."', '".$_POST['c110']."', '".$_POST['c111']."', '".$_POST['c112']."', '".$_POST['c135']."', '".$_POST['c138']."', '".$_POST['c139']."', '".$_POST['c140']."', '".$_POST['c144']."', '".$_POST['c145']."', '".$_POST['c146']."', '".$_POST['c147']."', '".$_POST['c148']."', '".$_POST['c149']."', '".$_POST['c150']."', '".$_POST['c151']."', '".$_POST['c154']."', '".$_POST['c155']."', '".$_POST['c156']."', '".$_POST['c157']."', '".$_POST['c158']."', '".$_POST['c159']."', '".$_POST['c160']."', '".$_POST['c170']."') ";

        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        } else {
            #print $query;
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_seiners.php");
        }

        $controllo = 1;

    }

if (!$controllo) {

    $labels = ['9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59', '60', '61', '62', '63', '64', '65', '66', '67', '68', '69', '70', '71', '72', '73', '74', '75', '76', '77', '78', '79', '80', '81', '82', '83', '84', '85', '86', '87', '88', '89', '90', '91', '92', '93', '94', '95', '96', '97', '98', '99', '100', '110', '111', '112', '135', '138', '139', '140', '144', '145', '146', '147', '148', '149', '150', '151', '154', '155', '156', '157', '158', '159', '160', '170'];

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
        <b>Maree</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','seiners.route')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM seiners.route ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Route</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','seiners.route')">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT date FROM seiners.route  WHERE maree = '$results[3]' ORDER BY date");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <select id="time" name="time" >
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT time FROM seiners.route WHERE maree = '$results[3]' AND date = '$results[139]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>

    <br/>
    <br/>
    <b># Calee</b>
    <br/>
    <input type="text" size="10" name="n_calee" />
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN seiners.thon_rejete_taille ON fishery.species.id = seiners.thon_rejete_taille.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[25]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Numero individu par taille</b>
    <table border="1">
    <?php

    print "<tr align='center'><td><b>9 cm</b><td></tr>";
    print "<tr align=\"center\"><td><input type=\"text\" size=\"3\" name=\"c009\" value=\"".$results[9]."\"><td></tr>";

    for ($i = 1; $i < 12; $i++) {
      print "<tr align='center'>";
      for ($j = 0; $j < 10; $j++) {
          print "<td><b>";
          print $labels[$i*10+$j-9];
          //print $i*10+$j-1;
          print " cm</b></td>";
      }
      print "</tr>";

      print "<tr align='center'>";
      for ($j = 0; $j < 10; $j++) {
          print "<td><input type=\"text\" size=\"3\" name=\"c".$labels_c[$i*10+$j-9]."\" \"></td>";
        }
      print "</tr>";
    }

    // last 5 entries
    print "<tr align=\"center\"><td><b>$labels[111] cm</b></td><td><b>$labels[112] cm</b></td><td><b>$labels[113] cm</b></td><td><b>$labels[114] cm</b></td></tr>";
    print "<tr align=\"center\">";
    print "<td><input type=\"text\" size=\"3\" name=\"c158\" \"></td>";
    print "<td><input type=\"text\" size=\"3\" name=\"c159\" \"></td>";
    print "<td><input type=\"text\" size=\"3\" name=\"c160\" \"></td>";
    print "<td><input type=\"text\" size=\"3\" name=\"c170\" \"></td>";
    print "</tr>";


    // <?php
    // print "<tr>";
    // foreach ($labels as $label) {
    //     print "<td>".$label."</td>";
    // }
    // print "</tr><tr>";
    // for ($i = 0; $i < count($labels); $i++) {
    //     print "<td><input type=\"text\" size=\"3\" name=\"".$labels[$i]. "\"></td>";
    // }
    // print "</tr>";
    ?>
    </table>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
}  else if ($table == 'prise_access') {

    if ($_POST['submit'] == "Enregistrer") {

        $username = $_SESSION['username'];
        $maree = $_POST['maree'];
        $t_zee = $_POST['t_zee'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $n_calee = $_POST['n_calee'];
        $t_type = $_POST['t_type'];
        $h_d = $_POST['h_d'];
        $h_c = $_POST['h_c'];
        $h_f = $_POST['h_f'];
        $vitesse = $_POST['vitesse'];
        $direction = $_POST['direction'];
        $d_max = $_POST['d_max'];
        $t_prise = $_POST['t_prise'];
        $t_raison = $_POST['t_raison'];
        $id_species = $_POST['id_species'];
        $t_action = $_POST['t_action'];
        $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);
        $n_ind = htmlspecialchars($_POST['n_ind'],ENT_QUOTES);
        $taille = $_POST['taille'];
        $photo = $_POST['photo'];
        $remarque = htmlspecialchars($_POST['remarque'],ENT_QUOTES);

        $q_id = "SELECT id FROM seiners.route WHERE maree = '$maree' AND date = '$date' AND time = '$time'";
        $id_route = pg_fetch_row(pg_query($q_id))[0];

        # id, datetime, username, maree, n_calee, t_type, t_zee, id_route, h_d, h_c, h_f, vitesse, direction, d_max, t_prise, id_species, t_action, t_raison, poids, n_ind, taille, photo, remarque

        $query = "INSERT INTO seiners.prise_access "
        . "(username, datetime, maree, n_calee, t_type, t_zee, id_route, h_d, h_c, h_f, vitesse, direction, d_max, t_prise, id_species, t_action, t_raison, poids, n_ind, taille, photo, remarque) "
        . "VALUES ('$username', now(), '$maree', '$n_calee', '$t_type', '$t_zee', '$id_route', '$h_d', '$h_c', '$h_f', '$vitesse', '$direction', '$d_max', '$t_prise', '$id_species', '$t_action', '$t_raison', '$poids', '$n_ind', '$taille', '$photo', '$remarque')";


        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        } else {
            #print $query;
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_seiners.php");
        }

            $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Maree</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','seiners.route')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM seiners.route ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Route</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','seiners.route')">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT date FROM seiners.route  WHERE maree = '$results[3]' ORDER BY date");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <select id="time" name="time" >
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT time FROM seiners.route WHERE maree = '$results[3]' AND date = '$results[30]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>

    <br/>
    <br/>
    <b>ZEE</b>
    <br/>
    <select name="t_zee">
    <?php
    $result = pg_query("SELECT id, zee FROM seiners.t_zee ORDER BY zee");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Numero Calee</b>
    <br/>
    <input type="text" size="20" name="n_calee" />
    <br/>
    <br/>
    <b>Type peche</b>
    <br/>
    <select name="t_type">
    <?php
    $result = pg_query("SELECT id, type FROM seiners.t_type ORDER BY type");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Heure de debut</b>
    <br/>
    <input type="time" size="20" name="h_d" />
    <br/>
    <br/>
    <b>Fin de coulissage</b>
    <br/>
    <input type="time" size="20" name="h_c" />
    <br/>
    <br/>
    <b>Heure de fin</b>
    <br/>
    <input type="time" size="20" name="h_f" />
    <br/>
    <br/>
    <b>Vitesse Courant</b> (nd)
    <br/>
    <input type="text" size="10" name="vitesse" />
    <br/>
    <br/>
    <b>Direction Courant</b>
    <br/>
    <input type="text" size="10" name="direction" />
    <br/>
    <br/>
    <b>Profondeur maximale</b> (m)
    <br/>
    <input type="text" size="20" name="d_max" />
    <br/>
    <br/>
    <b>Type de prise</b>
    <br/>
    <select name="t_prise">
    <?php
    $result = pg_query("SELECT id, prise FROM seiners.t_prise ORDER BY prise");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN seiners.prise_access ON fishery.species.id = seiners.prise_access.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[25]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Devenir</b>
    <br/>
    <select name="t_action">
    <?php
    $result = pg_query("SELECT id, action FROM seiners.t_action ORDER BY action");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Raison rejet</b>
    <br/>
    <select name="t_raison">
    <?php
    $result = pg_query("SELECT id, raison FROM seiners.t_raison ORDER BY raison");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Poids Total</b> (kg)
    <br/>
    <input type="text" size="10" name="poids" />
    <br/>
    <br/>
    <b>Numero individue</b>
    <br/>
    <input type="text" size="10" name="n_ind" />
    <br/>
    <br/>
    <b>Taille moyenne</b> (cm)
    <br/>
    <input type="text" size="10" name="taille" />
    <br/>
    <br/>
    <b>Photo/Video</b>
    <br/>
    <input type="text" size="30" name="photo" />
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
}   else if ($table == 'prise_access_taille') {

    if ($_POST['submit'] == "Enregistrer") {

        $username = $_SESSION['username'];
        $maree = $_POST['maree'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $n_cale = $_POST['n_cale'];
        $id_species = $_POST['id_species'];
        $t_measure = $_POST['t_measure'];
        $taille = $_POST['taille'];
        $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);
        $t_sexe = $_POST['t_sexe'];
        $t_capture = $_POST['t_capture'];
        $t_relache = $_POST['t_relache'];
        $photo = $_POST['photo'];
        $remarque = htmlspecialchars($_POST['remarque'],ENT_QUOTES);

        $q_id = "SELECT id FROM seiners.route WHERE maree = '$maree' AND date = '$date' AND time = '$time'";
        $id_route = pg_fetch_row(pg_query($q_id))[0];

        #print $q_id;


        # id, datetime, username, maree, n_cale, t_type, t_zee, id_route, h_d, h_c, h_f, vitesse, direction, d_max, t_sexe, id_species, t_action, t_raison, poids, n_ind, taille, photo, remarque

        $query = "INSERT INTO seiners.prise_access_taille "
            . "(datetime, username, maree, n_cale, id_route, id_species, t_measure, taille, poids, t_sexe, t_capture, t_relache, photo, remarque) "
            . "VALUES (now(), '$username', '$maree', '$n_cale', '$id_route', '$id_species', '$t_measure', '$taille', '$poids', '$t_sexe', '$t_capture', '$t_relache', '$photo', '$remarque')";


        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        } else {
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=industrial/input_seiners.php");
        }

        $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Maree</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','seiners.route')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM seiners.route ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Route</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','seiners.route')">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT date FROM seiners.route  WHERE maree = '$results[3]' ORDER BY date");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <select id="time" name="time" >
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT time FROM seiners.route WHERE maree = '$results[3]' AND date = '$results[22]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[0]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Numero Calee</b>
    <br/>
    <input type="text" size="20" name="n_cale" />
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN seiners.prise_access_taille ON fishery.species.id = seiners.prise_access_taille.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[25]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Type mesure</b>
    <br/>
    <select name="t_measure">
    <?php
    $result = pg_query("SELECT id, measure FROM seiners.t_measure ORDER BY measure");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Taille</b> (cm)
    <br/>
    <input type="text" size="20" name="taille" />
    <br/>
    <br/>
    <b>Poids</b> (kg)
    <br/>
    <input type="text" size="20" name="poids" />
    <br/>
    <br/>
    <b>Sexe</b>
    <br/>
    <select name="t_sexe">
    <?php
    $result = pg_query("SELECT id, sexe FROM seiners.t_sexe ORDER BY sexe");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Capture</b>
    <br/>
    <select name="t_capture">
    <?php
    $result = pg_query("SELECT id, capture FROM seiners.t_capture ORDER BY id");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Relache</b>
    <br/>
    <select name="t_relache">
    <?php
    $result = pg_query("SELECT id, relache FROM seiners.t_relache ORDER BY id");
    while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Photo/Video</b>
    <br/>
    <input type="text" size="30" name="photo" />
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
}
} else {
    msg_noaccess();
}

foot();
