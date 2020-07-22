<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'seiners';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_s_maree'] = $_POST['f_s_maree'];
$_SESSION['filter']['f_t_measure'] = $_POST['f_t_measure'];
$_SESSION['filter']['f_id_species'] = $_POST['f_id_species'];
$_SESSION['filter']['f_t_capture'] = $_POST['f_t_capture'];
$_SESSION['filter']['f_t_relache'] = $_POST['f_t_relache'];

if ($_GET['f_s_maree'] != "") {$_SESSION['filter']['f_s_maree'] = $_GET['f_s_maree'];}
if ($_GET['f_t_measure'] != "") {$_SESSION['filter']['f_t_measure'] = $_GET['f_t_measure'];}
if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}
if ($_GET['f_t_capture'] != "") {$_SESSION['filter']['f_t_capture'] = $_GET['f_t_capture'];}
if ($_GET['f_t_relache'] != "") {$_SESSION['filter']['f_t_relache'] = $_GET['f_t_relache'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=seiners&table=prise_access_taille&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Maree</b></td><td><b>Espece</b></td><td><b>Capture</b></td><td><b>Relache</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <select name="f_id_species">
        <option value="id_species" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN seiners.prise_access_taille ON fishery.species.id = seiners.prise_access_taille.id_species  ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ("'".$row[0]."'" == $_SESSION['filter']['f_id_species']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"'$row[0]'\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    </td>
    <td>
    <select name="f_t_capture">
        <option value="t_capture" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT t_capture.id, t_capture.capture FROM seiners.prise_access_taille LEFT JOIN seiners.t_capture ON seiners.t_capture.id = seiners.prise_access_taille.t_capture WHERE t_capture IS NOT NULL ORDER BY t_capture.id ");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_capture']) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
        }
    ?>
    </select>
    </td>
    <td>
    <select name="f_t_relache">
        <option value="t_relache" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT t_relache.id, t_relache.relache FROM seiners.prise_access_taille LEFT JOIN seiners.t_relache ON seiners.t_relache.id = seiners.prise_access_taille.t_relache  WHERE relache IS NOT NULL ORDER BY id");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_relache']) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
        }
    ?>
    </select>
    </td>
    </tr>
    </table>
    <input type="submit" name="Filter" value="filter" />
    </fieldset>
    </form>

    <br/>


    <table id="small">
    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Route</b></td>
    <td><b>Maree</b></td>
    <td><b># Calee</b></td>
    <td><b>Espece</b></td>
    <td><b>Type mesure, poids et taille</b></td>
    <td><b>Sexe</b></td>
    <td><b>Capture / Relache</b></td>
    <td><b>Photo/Video</b></td>
    <td><b>Remarque</b></td>
    <td><b>GPS</b></td>
    </tr>
    <?php

    // fetch data

    # id, datetime, username, maree, n_cale, id_route, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_measure.measure, taille, poids, t_sexe.sexe, t_capture.capture, t_relache.relache, photo, remarque

    if ($_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_id_species'] != "" OR $_SESSION['filter']['f_t_capture'] != "" OR $_SESSION['filter']['f_t_relache'] != "") {

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(prise_access_taille.id) FROM seiners.prise_access_taille "
                . "WHERE (id_species IS NULL OR id_species=".$_SESSION['filter']['f_id_species'].") "
                . "AND (t_relache IS NULL OR t_relache=".$_SESSION['filter']['f_t_relache'].") "
                . "AND (t_capture IS NULL OR t_capture=".$_SESSION['filter']['f_t_capture'].") ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT prise_access_taille.id, prise_access_taille.datetime::date, prise_access_taille.username, prise_access_taille.maree, n_cale, id_route, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_measure.measure, taille, poids, t_sexe.sexe, t_capture.capture, t_relache.relache, photo, remarque, st_x(route.location), st_y(route.location), route.date, route.time,  "
            . " coalesce(similarity(seiners.prise_access_taille.maree, '".$_SESSION['filter']['f_s_maree']."'),0)  AS score"
            . " FROM seiners.prise_access_taille "
            . "LEFT JOIN fishery.species ON fishery.species.id = seiners.prise_access_taille.id_species "
            . "LEFT JOIN seiners.t_sexe ON seiners.t_sexe.id = seiners.prise_access_taille.t_sexe "
            . "LEFT JOIN seiners.t_measure ON seiners.t_measure.id = seiners.prise_access_taille.t_measure "
            . "LEFT JOIN seiners.t_relache ON seiners.t_relache.id = seiners.prise_access_taille.t_relache "
            . "LEFT JOIN seiners.t_capture ON seiners.t_capture.id = seiners.prise_access_taille.t_capture "
            . "LEFT JOIN seiners.route ON seiners.route.id = seiners.prise_access_taille.id_route "
            . "WHERE (id_species IS NULL OR id_species=".$_SESSION['filter']['f_id_species'].") "
            . "AND (t_relache IS NULL OR t_relache=".$_SESSION['filter']['f_t_relache'].") "
            . "AND (t_capture IS NULL OR t_capture=".$_SESSION['filter']['f_t_capture'].") "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT count(prise_access_taille.id) FROM seiners.prise_access_taille "
            . "WHERE (id_species IS NULL OR id_species=".$_SESSION['filter']['f_id_species'].") "
            . "AND (t_relache IS NULL OR t_relache=".$_SESSION['filter']['f_t_relache'].") "
            . "AND (t_capture IS NULL OR t_capture=".$_SESSION['filter']['f_t_capture'].") ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT prise_access_taille.id, prise_access_taille.datetime::date, prise_access_taille.username, prise_access_taille.maree, n_cale, id_route, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_measure.measure, taille, poids, t_sexe.sexe, t_capture.capture, t_relache.relache, photo, remarque, st_x(route.location), st_y(route.location), route.date, route.time "
            . " FROM seiners.prise_access_taille "
            . "LEFT JOIN fishery.species ON fishery.species.id = seiners.prise_access_taille.id_species "
            . "LEFT JOIN seiners.t_sexe ON seiners.t_sexe.id = seiners.prise_access_taille.t_sexe "
            . "LEFT JOIN seiners.t_measure ON seiners.t_measure.id = seiners.prise_access_taille.t_measure "
            . "LEFT JOIN seiners.t_relache ON seiners.t_relache.id = seiners.prise_access_taille.t_relache "
            . "LEFT JOIN seiners.t_capture ON seiners.t_capture.id = seiners.prise_access_taille.t_capture "
            . "LEFT JOIN seiners.route ON seiners.route.id = seiners.prise_access_taille.id_route "
            . "WHERE (id_species IS NULL OR id_species=".$_SESSION['filter']['f_id_species'].") "
            . "AND (t_relache IS NULL OR t_relache=".$_SESSION['filter']['f_t_relache'].") "
            . "AND (t_capture IS NULL OR t_capture=".$_SESSION['filter']['f_t_capture'].") "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
        }
    } else {

        $query = "SELECT count(prise_access_taille.id) FROM seiners.prise_access_taille";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT prise_access_taille.id, prise_access_taille.datetime::date, prise_access_taille.username, prise_access_taille.maree, n_cale, id_route, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_measure.measure, taille, poids, t_sexe.sexe, t_capture.capture, t_relache.relache, photo, remarque, st_x(route.location), st_y(route.location), route.date, route.time  "
        . " FROM seiners.prise_access_taille "
        . "LEFT JOIN fishery.species ON fishery.species.id = seiners.prise_access_taille.id_species "
        . "LEFT JOIN seiners.t_sexe ON seiners.t_sexe.id = seiners.prise_access_taille.t_sexe "
        . "LEFT JOIN seiners.t_measure ON seiners.t_measure.id = seiners.prise_access_taille.t_measure "
        . "LEFT JOIN seiners.t_relache ON seiners.t_relache.id = seiners.prise_access_taille.t_relache "
        . "LEFT JOIN seiners.t_capture ON seiners.t_capture.id = seiners.prise_access_taille.t_capture "
        . "LEFT JOIN seiners.route ON seiners.route.id = seiners.prise_access_taille.id_route "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";

    }

    #print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_seiners_prise_access_taille.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_seiners_prise_access_taille.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td><a href=\"./view_route.php?id=$results[5]\">$results[21] $results[22]</a></td><td>$results[3]</td><td>$results[4]</td>"
        . "<td>".formatSpecies($results[7],$results[8],$results[9],$results[10])."</td><td>$results[11]/$results[12]/$results[13]</td><td>$results[14]</td><td>$results[15] / $results[16]</td><td>$results[17]</td><td>$results[18]</td><td><a href=\"view_point.php?X=$results[19]&Y=$results[20]\">".round($results[19],3)."E ".round($results[20],3)."N</td></tr>";
    }
    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_seiners_prise_access_taille.php?source=seiners&table=prise_access_taille&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_id_species='.$_SESSION['filter']['f_id_species'].'&f_t_capture='.$_SESSION['filter']['f_t_capture'].'&f_t_relache='.$_SESSION['filter']['f_t_relache']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    # id, datetime, username, maree, n_cale, id_route, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_measure.measure, taille, poids, t_sexe.sexe, t_capture.capture, t_relache.relache, photo, remarque

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT prise_access_taille.id, prise_access_taille.datetime, prise_access_taille.username, prise_access_taille.maree, n_cale, id_route, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_measure, taille, poids, t_sexe, t_capture, t_relache, photo, remarque, st_x(route.location), st_y(route.location), route.date, route.time "
            . "FROM seiners.prise_access_taille "
            . "LEFT JOIN fishery.species ON fishery.species.id = seiners.prise_access_taille.id_species "
            . "LEFT JOIN seiners.route ON seiners.route.id = seiners.prise_access_taille.id_route "
            . "WHERE prise_access_taille.id = '$id'";

    #print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Maree</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','seiners.route')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM seiners.route ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[3]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
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
        if ($row[0] == $results[21]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <select id="time" name="time" >
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT time FROM seiners.route WHERE maree = '$results[3]' AND date = '$results[21]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[22]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Numero Calee</b>
    <br/>
    <input type="text" size="20" name="n_cale" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN seiners.prise_access_taille ON fishery.species.id = seiners.prise_access_taille.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
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
    <b>Type mesure</b>
    <br/>
    <select name="t_measure">
    <?php
    $result = pg_query("SELECT id, measure FROM seiners.t_measure ORDER BY measure");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[11]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Taille</b> (cm)
    <br/>
    <input type="text" size="20" name="taille" value="<?php echo $results[12]; ?>"/>
    <br/>
    <br/>
    <b>Poids</b> (kg)
    <br/>
    <input type="text" size="20" name="poids" value="<?php echo $results[13]; ?>"/>
    <br/>
    <br/>
    <b>Sexe</b>
    <br/>
    <select name="t_sexe">
    <?php
    $result = pg_query("SELECT id, sexe FROM seiners.t_sexe ORDER BY sexe");
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
    <b>Capture</b>
    <br/>
    <select name="t_capture">
    <?php
    $result = pg_query("SELECT id, capture FROM seiners.t_capture ORDER BY id");
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
    <b>Relache</b>
    <br/>
    <select name="t_relache">
    <?php
    $result = pg_query("SELECT id, relache FROM seiners.t_relache ORDER BY id");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[16]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Photo/Video</b>
    <br/>
    <input type="text" size="30" name="photo" value="<?php echo $results[17];?>" />
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" value="<?php echo $results[18];?>" />
    <br/>
    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>

    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM seiners.prise_access_taille WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_seiners_prise_access_taille.php?source=$source&table=prise_access_taille&action=show");
    }
    $controllo = 1;

}

if ($_POST['submit'] == "Enregistrer") {

    # id, datetime, username, maree, n_cale, id_route, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_measure.measure, taille, poids, t_sexe.sexe, t_capture.capture, t_relache.relache, photo, remarque

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

    if ($_POST['new_old']) {

    # id, datetime, username, maree, n_cale, t_type, t_zee, id_route, h_d, h_c, h_f, vitesse, direction, d_max, t_sexe, id_species, t_action, t_raison, poids, n_ind, taille, photo, remarque

    $query = "INSERT INTO seiners.prise_access_taille "
            . "(datetime, username, maree, n_cale, id_route, id_species, t_measure, taille, poids, t_sexe, t_capture, t_relache, photo, remarque) "
            . "VALUES (now(), '$username', '$maree', '$n_cale', '$id_route', '$id_species', '$t_measure', '$taille', '$poids', '$t_sexe', '$t_capture', '$t_relache', '$photo', '$remarque')";

    } else {
        $query = "UPDATE seiners.prise_access_taille SET "
            . "username = '$username', datetime = now(), "
            . "maree = '$maree', n_cale = '$n_cale', id_route = '$id_route', id_species = '$id_species', t_measure = '$t_measure', "
            . "taille = '$taille', poids = '$poids', t_sexe = '$t_sexe', t_capture = '$t_capture', "
            . "t_relache = $t_relache, photo = '$photo', remarque = '$remarque'"
            . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);
    #print $query;
    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_seiners_prise_access_taille.php?source=$source&table=prise_access_taille&action=show");
    }
}

foot();
