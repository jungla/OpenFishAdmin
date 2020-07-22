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
    <form method="post" action="<?php echo $self;?>?source=trawlers&table=p_day&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Mar&eacute;e</b></td><td><b>Esp&eacute;c&eacute;</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <select name="f_id_species">
        <option value="id_species" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.p_day ON fishery.species.id = trawlers.p_day.id_species  ORDER BY  fishery.species.family, fishery.species.genus, fishery.species.species");
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
    <td><b>Mar&eacute;e</b></td>
    <td><b>Date</b></td>
    <td><b>Lanc&eacute; debut</b></td>
    <td><b>Lanc&eacute; fin</b></td>
    <td><b>Esp&egrave;ce</b></td>
    <td><b>0</b></td>
    <td><b>1</b></td>
    <td><b>2</b></td>
    <td><b>3</b></td>
    <td><b>4</b></td>
    <td><b>5</b></td>
    <td><b>6</b></td>
    <td><b>7</b></td>
    <td><b>8</b></td>
    <td><b>CC</b></td>
    <td><b>G</b></td>
    <td><b>M</b></td>
    <td><b>P</b></td>
    <td><b>F</b></td>
    <td><b>FF</b></td>
    <td><b>Mix</b></td>
    <td><b>Grosse pi&egrave;ce</b></td>
    </tr>

    <?php

    // fetch data

    if ($_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_id_species'] != "") {

        #id, datetime, username, id_route, id_species, maree, lance, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(p_day.id) FROM trawlers.p_day "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT p_day.id, datetime::date, username, maree, date_d, lance_d, lance_f, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi, "
            . " coalesce(similarity(trawlers.p_day.maree, '".$_SESSION['filter']['f_s_maree']."'),0) AS score"
            . " FROM trawlers.p_day "
            . "LEFT JOIN fishery.species ON trawlers.p_day.id_species = fishery.species.id "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
            . "ORDER BY score DESC, datetime DESC OFFSET $start LIMIT $step";
        } else {
            $query = "SELECT count(p_day.id) FROM trawlers.p_day "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";
            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT p_day.id, datetime::date, username, maree, date_d, lance_d, lance_f, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi  "
            . " FROM trawlers.p_day "
            . "LEFT JOIN fishery.species ON trawlers.p_day.id_species = fishery.species.id "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(p_day.id) FROM trawlers.p_day";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT p_day.id, datetime::date, username, maree, date_d, lance_d, lance_f, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi "
        . " FROM trawlers.p_day "
        . "LEFT JOIN fishery.species ON trawlers.p_day.id_species = fishery.species.id "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
    }

    $r_query = pg_query($query);
    #print $query;
    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_trawlers_p_day.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_trawlers_p_day.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td nowrap>$results[3]<br/><td nowrap>$results[4]</td><td>$results[5]</td><td>$results[6]</td><td>".formatSpecies($results[8],$results[9],$results[10],$results[11])."</td>"
        . "<td>$results[12]</td><td>$results[13]</td><td>$results[14]</td>"
        . "<td>$results[15]</td><td>$results[16]</td><td>$results[17]</td><td>$results[18]</td>"
        . "<td>$results[19]</td><td>$results[20]</td><td>$results[21]</td><td>$results[22]</td>"
        . "<td>$results[23]</td><td>$results[24]</td><td>$results[25]</td><td>$results[26]</td>"
        . "<td>$results[27]</td><td>$results[28]</td>"
        . "</tr>";
    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_trawlers_p_day.php?source=trawlers&table=p_day&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_id_species='.$_SESSION['filter']['f_id_species']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT p_day.id, datetime::date, username, maree, date_d, lance_d, lance_f, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi FROM trawlers.p_day"
            . " LEFT JOIN fishery.species ON fishery.species.id = trawlers.p_day.id_species "
            . " WHERE p_day.id = '$id'";

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    # print $q_id;

    # id, datetime, username, id_route, id_species, maree, lance, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Mar&eacute;e</b>
    <br/>
    <select id="maree" name="maree">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route ORDER BY maree DESC");
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
    <b>Date</b>
    <br/>
    <input type="date" size="12" name="date_d" value="<?php print $results[4]; ?>">
    <br/>
    <br/>
    <b>Lanc&eacute; Debut</b>
    <br/>
    <input type="text" size="3" name="lance_d" value="<?php print $results[5]; ?>">
    <br/>
    <br/>
    <b>Lanc&eacute; Fin</b>
    <br/>
    <input type="text" size="3" name="lance_f" value="<?php print $results[6]; ?>">
    <br/>
    <br/>
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.p_lance ON fishery.species.id = trawlers.p_lance.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
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
    <b>Categorie taille crevette</b>
    <br/>
    <table border="1">
        <tr align="center"><td>0</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td><td>8</td><td>CC</td></tr>
        <tr align="center">
            <?php
            for ($i = 0; $i < 10; $i++) {
                print '<td><input type="text" size="3" name="c'.$i.'_cre" value="'.$results[12+$i].'"></td>';
            }
            ?>
        </tr>
    </table>
    <br/>
    <b>Categorie taille poisson</b>
    <br/>
    <table border="1">
        <tr align="center"><td>G</td><td>M</td><td>P</td><td>F</td><td>FF</td><td>Mix</td><td>Grosse pi&egrave;ce</td></tr>
        <tr align="center">
            <?php
            for ($i = 0; $i < 7; $i++) {
                print '<td><input type="text" size="3" name="c'.$i.'_poi" value="'.$results[12+10+$i].'"></td>';
            }
            ?>
        </tr>
    </table>
    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM trawlers.p_day WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_p_day.php?source=$source&table=p_day&action=show");
    }
    $controllo = 1;

}


if ($_POST['submit'] == "Enregistrer") {
    # id, datetime, username, id_route, id_species, maree, lance, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi


    $maree = $_POST['maree'];
    $date_d = $_POST['date_d'];
    $lance_d = $_POST['lance_d'];
    $lance_f = $_POST['lance_f'];
    $id_species = $_POST['id_species'];

    $c0_cre = $_POST['c0_cre'];
    $c1_cre = $_POST['c1_cre'];
    $c2_cre = $_POST['c2_cre'];
    $c3_cre = $_POST['c3_cre'];
    $c4_cre = $_POST['c4_cre'];
    $c5_cre = $_POST['c5_cre'];
    $c6_cre = $_POST['c6_cre'];
    $c7_cre = $_POST['c7_cre'];
    $c8_cre = $_POST['c8_cre'];
    $c9_cre = $_POST['c9_cre'];
    $c0_poi = $_POST['c0_poi'];
    $c1_poi = $_POST['c1_poi'];
    $c2_poi = $_POST['c2_poi'];
    $c3_poi = $_POST['c3_poi'];
    $c4_poi = $_POST['c4_poi'];
    $c5_poi = $_POST['c5_poi'];
    $c6_poi = $_POST['c6_poi'];

    if ($_POST['new_old']) {
        $query = "INSERT INTO trawlers.p_day "
            . "(datetime, username, maree, date_d, lance_d, lance_f, id_species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi) "
            . "VALUES (now(), '$username', '$maree', '$date_d', '$lance_d', '$lance_f', '$id_species', '$c0_cre', '$c1_cre', '$c2_cre', '$c3_cre', '$c4_cre', '$c5_cre', '$c6_cre', '$c7_cre', '$c8_cre', '$c9_cre', '$c0_poi', '$c1_poi', '$c2_poi', '$c3_poi', '$c4_poi', '$c5_poi', '$c6_poi')";


    } else {
        $query = "UPDATE trawlers.p_day SET "
            . "username = '$username', datetime = now(), "
            . "maree = '".$maree."', date_d = '".$date_d."', lance_d = '".$lance_d."', lance_f = '".$lance_f."', id_species = '".$id_species."', "
            . "c0_cre = '".$c0_cre."', c1_cre = '".$c1_cre."', c2_cre = '".$c2_cre."', c3_cre = '".$c3_cre."', c4_cre = '".$c4_cre."', c5_cre = '".$c5_cre."', c6_cre = '".$c6_cre."', c7_cre = '".$c7_cre."', c8_cre = '".$c8_cre."', c9_cre = '".$c9_cre."', c0_poi = '".$c0_poi."', c1_poi = '".$c1_poi."', c2_poi = '".$c2_poi."', c3_poi = '".$c3_poi."', c4_poi = '".$c4_poi."', c5_poi = '".$c5_poi."', c6_poi = '".$c6_poi."' "
            . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_p_day.php?source=$source&table=p_day&action=show");
    }
}

foot();
