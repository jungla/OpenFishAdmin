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
    <form method="post" action="<?php echo $self;?>?source=trawlers&table=poids_taille&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Maree</b></td><td><b>Esp&eacute;c&eacute;</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <select name="f_id_species">
        <option value="id_species" selected="selected">Tous</option>
        <?php
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.poids_taille ON fishery.species.id = trawlers.poids_taille.id_species  ORDER BY  fishery.species.family, fishery.species.genus, fishery.species.species");
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY  fishery.species.family, fishery.species.genus, fishery.species.species");
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

    <table>
    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Maree</b></td>
    <td><b>Measure</b></td>
    <td><b>Espece</b></td>
    <td><b>Taille</b></td>
    <td><b>Poids 1</b></td>
    <td><b>Poids 2</b></td>
    <td><b>Poids 3</b></td>
    <td><b>Poids 4</b></td>
    <td><b>Poids 5</b></td>
    </tr>

    <?php

    // fetch data

    if ($_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_id_species'] != "") {

        #id, datetime, username, id_route, id_species, maree, lance, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(poids_taille.id) FROM trawlers.poids_taille "
                . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT poids_taille.id, datetime::date, username, maree, t_measure.measure, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, p1, p2, p3, p4, p5 "
            . " coalesce(similarity(trawlers.poids_taille.maree, '".$_SESSION['filter']['f_s_maree']."'),0) AS score"
            . " FROM trawlers.poids_taille "
            . "LEFT JOIN fishery.species ON trawlers.poids_taille.id_species = fishery.species.id "
            . "LEFT JOIN seiners.t_measure ON trawlers.poids_taille.t_measure = seiners.t_measure.id "
            . "WHERE (id_species IS NULL OR id_species=".$_SESSION['filter']['f_id_species'].") "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT count(poids_taille.id) FROM trawlers.poids_taille "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";
            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT poids_taille.id, datetime::date, username, maree, t_measure.measure, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, p1, p2, p3, p4, p5 "
            . " FROM trawlers.poids_taille "
            . "LEFT JOIN fishery.species ON trawlers.poids_taille.id_species = fishery.species.id "
            . "LEFT JOIN seiners.t_measure ON trawlers.poids_taille.t_measure = seiners.t_measure.id "
            . "WHERE (id_species IS NULL OR id_species=".$_SESSION['filter']['f_id_species'].") "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(poids_taille.id) FROM trawlers.poids_taille";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT poids_taille.id, datetime::date, username, maree, t_measure.measure, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, p1, p2, p3, p4, p5 "
        . " FROM trawlers.poids_taille "
        . "LEFT JOIN seiners.t_measure ON trawlers.poids_taille.t_measure = seiners.t_measure.id "
        . "LEFT JOIN fishery.species ON trawlers.poids_taille.id_species = fishery.species.id "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
    }

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_trawlers_poids_taille.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_trawlers_poids_taille.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td>$results[3]<br/><td>$results[4]</td><td>".formatSpecies($results[6],$results[7],$results[8],$results[9])."</td><td>$results[10]</td>"
        . "<td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td><td>$results[15]</td></tr>";
    }
    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_trawlers_poids_taille.php?source=trawlers&table=poids_taille&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_id_species='.$_SESSION['filter']['f_id_species']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT *, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM trawlers.poids_taille "
    . " LEFT JOIN fishery.species ON fishery.species.id = trawlers.poids_taille.id_species "
    . " WHERE poids_taille.id = '$id'";

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    # id, datetime, username, id_route, id_species, maree, lance, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Maree</b>
    <br/>
    <select id="maree" name="maree">
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
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.ft_poi ON fishery.species.id = trawlers.ft_poi.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[12]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Measure</b>
    <br/>
    <select name="t_measure">
    <?php
    $result = pg_query("SELECT id, measure FROM trawlers.t_measure");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[5]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Taille</b>
    <br/>
    <input type="text" name="taille" value="<?php print $results[6];?>">
    <br/>
    <br/>
    <b>Poids 1</b>
    <br/>
    <input type="text" name="p1" value="<?php print $results[7];?>">
    <br/>
    <br/>
    <b>Poids 2</b>
    <br/>
    <input type="text" name="p2" value="<?php print $results[8];?>">
    <br/>
    <br/>
    <b>Poids 3</b>
    <br/>
    <input type="text" name="p3" value="<?php print $results[9];?>">
    <br/>
    <br/>
    <b>Poids 4</b>
    <br/>
    <input type="text" name="p4" value="<?php print $results[10];?>">
    <br/>
    <br/>
    <b>Poids 5</b>
    <br/>
    <input type="text" name="p5" value="<?php print $results[11];?>">
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
    $query = "DELETE FROM trawlers.poids_taille WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_poids_taille.php?source=$source&table=poids_taille&action=show");
    }
    $controllo = 1;

}

print $_POST['submit'];

if ($_POST['submit'] == "Enregistrer") {
    # id, datetime, username, id_route, id_species, maree, lance, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi

    $maree = $_POST['maree'];
    $id_species = $_POST['id_species'];
    $t_measure = $_POST['t_measure'];
    $taille = $_POST['taille'];
    $p1 = $_POST['p1'];
    $p2 = $_POST['p2'];
    $p3 = $_POST['p3'];
    $p4 = $_POST['p4'];
    $p5 = $_POST['p5'];

    if ($_POST['new_old']) {
        $query = "INSERT INTO trawlers.poids_taille "
            . "(datetime, username, maree, id_species, t_measure, taille, p1, p2, p3, p4, p5) "
            . "VALUES (now(), '$username', '$maree', '$id_species', '$t_measure', '$taille', '$p1', '$p2', '$p3', '$p4', '$p5')";

    } else {
        $query = "UPDATE trawlers.poids_taille SET "
            . "username = '$username', datetime = now(), "
            . "maree = '".$maree."', id_species = '".$id_species."', t_measure = '".$t_measure."', taille = '".$taille."', "
            . "p1 = '".$p1."', p2 = '".$p2."', p3 = '".$p3."', p4 = '".$p4."', p5 = '".$p5."'"
            . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
//        print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_poids_taille.php?source=$source&table=poids_taille&action=show");
    }
}

foot();
