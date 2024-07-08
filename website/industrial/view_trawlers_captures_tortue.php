<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'trawlers';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_id_species'] = $_POST['f_id_species'];
$_SESSION['filter']['f_s_maree'] = $_POST['f_s_maree'];

if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}
if ($_GET['f_s_maree'] != "") {$_SESSION['filter']['f_s_maree'] = $_GET['f_s_maree'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=trawlers&table=captures_tortue&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Maree</b></td><td><b>Esp&eacute;c&eacute;</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <select name="f_id_species" class="chosen-select" >
        <option value="id_species" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM trawlers.captures_tortue LEFT JOIN fishery.species ON captures_tortue.id_species = fishery.species.id ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
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
    </tr>
    </table>
    <input type="submit" name="Filter" value="filter" />
    </fieldset>
    </form>

    <br/>

    <table id="small">
    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Maree, date & heure</b></td>
    <td><b>Espece</b></td>
    <td><b>Numbre individue et Sexe</b></td>
    <td><b>Longueur - Largeur</b></td>
    <td><b>Nouvelle bague</b></td>
    <td><b>Bague code et position 1</b></td>
    <td><b>Bague code et position 2</b></td>
    <td><b>Condition capture et relache</b></td>
    <td><b>Tentative de r&eacute;animation et r&eacute;sultat</b></td>
    <td><b>Pr&eacute;l&egrave;vement code</b></td>
    <td><b>Camera/Photo ID</b></td>
    <td><b>Remarque</b></td>
    </tr>

    <?php

    // id, datetime, username, id_route, maree, date, time, id_species, n_ind, t_sex, length, width, ring, position_1, code_1, position_2, code_2, t_capture, t_relache, resumation, resumation_res, preleve, camera, photo, remarque ,

    // fetch data

    if ($_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_id_species']) {

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(captures_tortue.id) FROM trawlers.captures_tortue "
                . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT captures_tortue.id, captures_tortue.datetime::date, captures_tortue.username, id_route, route_accidentelle.maree, route_accidentelle.date, route_accidentelle.time, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, n_ind, t_sex.sex, length, width, captures_tortue.ring, p1.ring, code_1, p2.ring, code_2, t1.condition, t2.condition, resumation, resumation_res, preleve, camera, photo, remarque, "
            . " coalesce(similarity(trawlers.captures_tortue.maree, '".$_SESSION['filter']['f_s_maree']."'),0) AS score"
            . " FROM trawlers.captures_tortue "
            . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_tortue.id_route "
            . "LEFT JOIN fishery.species ON trawlers.captures_tortue.id_species = fishery.species.id "
            . "LEFT JOIN trawlers.t_sex ON trawlers.captures_tortue.t_sex = trawlers.t_sex.id "
            . "LEFT JOIN trawlers.t_condition t1 ON trawlers.captures_tortue.t_capture = t1.id "
            . "LEFT JOIN trawlers.t_condition t2 ON trawlers.captures_tortue.t_relache = t2.id "
            . "LEFT JOIN trawlers.t_ring p1 ON trawlers.captures_tortue.position_1 = p1.id "
            . "LEFT JOIN trawlers.t_ring p2 ON trawlers.captures_tortue.position_2 = p2.id "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT count(captures_tortue.id) FROM trawlers.captures_tortue "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";
            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT captures_tortue.id, captures_tortue.datetime::date, captures_tortue.username, id_route, route_accidentelle.maree, route_accidentelle.date, route_accidentelle.time, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, n_ind, t_sex.sex, length, width, captures_tortue.ring, p1.ring, code_1, p2.ring, code_2, t1.condition, t2.condition, resumation, resumation_res, preleve, camera, photo, remarque "
            . " FROM trawlers.captures_tortue "
            . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_tortue.id_route "
            . "LEFT JOIN fishery.species ON trawlers.captures_tortue.id_species = fishery.species.id "
            . "LEFT JOIN trawlers.t_sex ON trawlers.captures_tortue.t_sex = trawlers.t_sex.id "
            . "LEFT JOIN trawlers.t_condition t1 ON trawlers.captures_tortue.t_capture = t1.id "
            . "LEFT JOIN trawlers.t_condition t2 ON trawlers.captures_tortue.t_relache = t2.id "
            . "LEFT JOIN trawlers.t_ring p1 ON trawlers.captures_tortue.position_1 = p1.id "
            . "LEFT JOIN trawlers.t_ring p2 ON trawlers.captures_tortue.position_2 = p2.id "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
            . "ORDER BY captures_tortue.datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(captures_tortue.id) FROM trawlers.captures_tortue";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT captures_tortue.id, captures_tortue.datetime::date, captures_tortue.username, id_route, route_accidentelle.maree, route_accidentelle.date, route_accidentelle.time, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, n_ind, t_sex.sex, length, width, captures_tortue.ring, p1.ring, code_1, p2.ring, code_2, t1.condition, t2.condition, resumation, resumation_res, preleve, camera, photo, remarque "
        . " FROM trawlers.captures_tortue "
        . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_tortue.id_route "
            . "LEFT JOIN fishery.species ON trawlers.captures_tortue.id_species = fishery.species.id "
        . "LEFT JOIN trawlers.t_sex ON trawlers.captures_tortue.t_sex = trawlers.t_sex.id "
        . "LEFT JOIN trawlers.t_condition t1 ON trawlers.captures_tortue.t_capture = t1.id "
        . "LEFT JOIN trawlers.t_condition t2 ON trawlers.captures_tortue.t_relache = t2.id "
        . "LEFT JOIN trawlers.t_ring p1 ON trawlers.captures_tortue.position_1 = p1.id "
        . "LEFT JOIN trawlers.t_ring p2 ON trawlers.captures_tortue.position_2 = p2.id "
        . "ORDER BY captures_tortue.datetime DESC OFFSET $start LIMIT $step";
    }

    #print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_trawlers_captures_tortue.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_trawlers_captures_tortue.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td nowrap>$results[1]<br/>$results[2]</td><td nowrap><a href=\"./view_route.php?id=$results[3]&table=route_accidentelle\">$results[4]<br/>$results[5]<br/>$results[6]</td>"
        . "<td>".formatSpecies($results[8],$results[9],$results[10],$results[11])."</td><td>$results[12]<br/>$results[13]</td><td nowrap>$results[14] - $results[15]</td>"
        . "<td>$results[16]</td><td nowrap>$results[17] - $results[18]</td><td nowrap>$results[19] - $results[20]</td><td nowrap>$results[21] - $results[22]</td><td>$results[23] - $results[24]</td>"
        . "<td>$results[25]</td><td>$results[26]<br/>$results[27]</td><td>$results[28]</td></tr>";
    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_trawlers_captures_tortue.php?source=trawlers&table=captures_tortue&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_id_species='.$_SESSION['filter']['f_id_species']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT captures_tortue.id, captures_tortue.datetime, captures_tortue.username, id_route, maree, date, time, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, n_ind, t_sex, length, width, ring, position_1, code_1, position_2, code_2, t_capture, t_relache, resumation, resumation_res, preleve, camera, photo, remarque  "
            . "FROM trawlers.captures_tortue "
            . "LEFT JOIN fishery.species ON trawlers.captures_tortue.id_species = fishery.species.id "
            . "WHERE captures_tortue.id = '$id'";

    #print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    //

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Maree</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','trawlers.route_accidentelle')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route_accidentelle ORDER BY maree");
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
    <b>Date</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','trawlers.route_accidentelle')">
    <?php
    $result = pg_query("SELECT DISTINCT date FROM trawlers.route_accidentelle  WHERE maree = '$results[4]' ORDER BY date");
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
    <b>Heure</b>
    <br/>
    <select id="time" name="time">
    <?php
    $result = pg_query("SELECT DISTINCT time FROM trawlers.route_accidentelle  WHERE maree = '$results[4]' AND date = '$results[5]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[6]) {
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
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM trawlers.captures_tortue LEFT JOIN fishery.species ON captures_tortue.id_species = fishery.species.id ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[7]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Nombre d&apos;individus</b>
    <br/>
    <input type="text" size="5" name="n_ind" value="<?php echo $results[12]; ?>"/>
    <br/>
    <br/>
    <b>Sexe</b>
    <br/>
    <select name="t_sex">
    <option value="none">Indetermine</option>
    <?php
    $result = pg_query("SELECT id, sex FROM trawlers.t_sex ORDER BY sex");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[13]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select><br/>
    <br/>
    <b>Longueur</b> (cm)
    <br/>
    <input type="text" size="10" name="length" value="<?php echo $results[14]; ?>"/>
    <br/>
    <br/>
    <b>Largeur</b> (cm)
    <br/>
    <input type="text" size="10" name="width" value="<?php echo $results[15]; ?>"/>
    <br/>
    <br/>
    <b>Nouvelle bague</b>
    <br/>
    <input type="radio" name="ring" value="TRUE" <?php if($results[16] == 't') {print "checked";} ?>/>Oui<br/>
    <input type="radio" name="ring" value="FALSE" <?php if($results[16] == 'f') {print "checked";} ?>/>No
    <br/>
    <br/>
    <b>Code bague 1</b>
    <br/>
    <input type="text" size="10" name="code_1" value="<?php echo $results[18]; ?>"/>
    <br/>
    <br/>
    <b>Position bague 1</b>
    <br/>
    <select name="position_1">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, ring FROM trawlers.t_ring");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[17]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select><br/>
    <br/>
    <b>Code bague 2</b>
    <br/>
    <input type="text" size="10" name="code_2" value="<?php echo $results[20]; ?>"/>
    <br/>
    <br/>
    <b>Position bague 2</b>
    <br/>
    <select name="position_2">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, ring FROM trawlers.t_ring");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[19]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select><br/>
    <br/>
    <b>Condition capture</b>
    <br/>
    <select name="t_capture">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, condition FROM trawlers.t_condition ORDER BY condition");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[21]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select><br/>
    <br/>
    <b>Condition relache</b>
    <br/>
    <select name="t_relache">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, condition FROM trawlers.t_condition ORDER BY condition");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[22]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Tentative de r&eacute;animation</b>
    <br/>
    Oui<input type="radio" name="resumation" value="TRUE" <?php if($results[23] == 't') {print "checked";} ?>/><br/>
    No<input type="radio" name="resumation" value="FALSE" <?php if($results[23] == 'f') {print "checked";} ?>/><br/>
    Inconnu<input type="radio" name="resumation" value="" <?php if($results[23] == '') {print "checked";} ?>/>
    <br/>
    <br/>
    <b>Resultat tentative de r&eacute;animation</b>
    <br/>
    Oui<input type="radio" name="resumation_res" value="TRUE" <?php if($results[24] == 't') {print "checked";} ?>/><br/>
    No<input type="radio" name="resumation_res" value="FALSE" <?php if($results[24] == 'f') {print "checked";} ?>/><br/>
    Inconnu<input type="radio" name="resumation_res" value="" <?php if($results[24] == '') {print "checked";} ?>/>
    <br/>
    <br/>
    <b>Pr&eacute;l&egrave;vement code</b>
    <br/>
    <input type="text" size="10" name="preleve" value="<?php echo $results[25]; ?>"/>
    <br/>
    <br/>
    <b>Camera ID</b>
    <br/>
    <input type="text" size="20" name="camera" value="<?php echo $results[26]; ?>"/>
    <br/>
    <br/>
    <b>Photo ID</b>
    <br/>
    <input type="text" size="20" name="photo" value="<?php echo $results[27]; ?>"/>
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" value="<?php echo $results[28];?>" />
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
    $query = "DELETE FROM trawlers.captures_tortue WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_captures_tortue.php?source=$source&table=captures_tortue&action=show");
    }
    $controllo = 1;

}


if ($_POST['submit'] == "Enregistrer") {

    # datetime, username, id_route, maree, date, time, id_species, n_ind, t_sex, length, width, ring, position_1, code_1, position_2, code_2, t_capture, t_relache, resumation, resumation_res, preleve, camera, photo, remarque

    $maree = $_POST['maree'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $id_species = $_POST['id_species'];
    $n_ind = htmlspecialchars($_POST['n_ind'],ENT_QUOTES);
    $t_sex = $_POST['t_sex'];
    $length = $_POST['length'];
    $width = $_POST['width'];
    $position_1 = $_POST['position_1'];
    $code_1 = $_POST['code_1'];
    $position_2 = $_POST['position_2'];
    $code_2 = $_POST['code_2'];
    $resumation = $_POST['resumation'];
    $resumation_res = $_POST['resumation_res'];
    $t_capture = $_POST['t_capture'];
    $t_relache = $_POST['t_relache'];
    $preleve = $_POST['preleve'];
    $camera = $_POST['camera'];
    $photo = $_POST['photo'];
    $remarque = htmlspecialchars($_POST['remarque'],ENT_QUOTES);

    $q_id = "SELECT id FROM trawlers.route_accidentelle WHERE maree = '$maree' AND date = '$date' AND time = '$time'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    if ($_POST['new_old']) {
        $query = "INSERT INTO trawlers.captures_tortue "
            . "(datetime, username, id_route, maree, date, time, id_species, n_ind, t_sex, length, width, ring, position_1, code_1, position_2, code_2, t_capture, t_relache, resumation, resumation_res, preleve, camera, photo, remarque) "
            . "VALUES (now(), '$username', '$id_route', '$maree', '$date', '$time', '$id_species', '$n_ind', '$t_sex', '$length', '$width', '$ring', '$position_1', '$code_1', '$position_2', '$code_2', '$t_capture', '$t_relache', '$resumation', '$resumation_res', '$preleve', '$camera', '$photo', '$remarque')";

    } else {
        $query = "UPDATE trawlers.captures_tortue SET "
            . "username = '$username', datetime = now(), "
            . "id_route = '".$id_route."', maree = '".$maree."', "
            . "date = '".$date."', time = '".$time."', id_species = '".$id_species."', "
            . "n_ind = '".$n_ind."', t_sex = '".$t_sex."', width = '".$width."', length = '".$length."', "
            . "ring = '".$n_ind."', position_1 = '".$position_1."', code_1 = '".$code_1."', position_2 = '".$position_2."', code_2 = '".$code_2."', "
            . "t_capture = '".$t_capture."', t_relache = '".$t_relache."', resumation = '".$resumation."', resumation_res = '".$resumation_res."', preleve = '".$preleve."', camera = '".$camera."', photo = '".$photo."', remarque = '".$remarque."'"
            . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
//        print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_captures_tortue.php?source=$source&table=captures_tortue&action=show");
    }
}

foot();
