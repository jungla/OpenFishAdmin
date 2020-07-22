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
    <form method="post" action="<?php echo $self;?>?source=trawlers&table=cm_poi&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Mar&eacute;e</b></td><td><b>Esp&egrave;ce</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <select name="f_id_species">
        <option value="id_species" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.cm_poi ON fishery.species.id = trawlers.cm_poi.id_species  ORDER BY  fishery.species.family, fishery.species.genus, fishery.species.species");
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
    <td><b>Mar&eacute;e et Lanc&eacute;</b></td>
    <td><b>Esp&egrave;ce</b></td>
    <td><b>Categorie</b></td>
    <td><b>Poids</b></td>
    <td><b>Taille</b></td>
    </tr>

    <?php

    // fetch data

    if ($_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_id_species'] != "") {

        #id, datetime, username, id_route, maree, lance, id_species, t_sex, category, poids, cm10_poi, cm11_poi, cm12_poi, cm13_poi, cm14_poi, cm15_poi, cm16_poi, cm17_poi, cm18_poi, cm19_poi, cm20_poi, cm21_poi, cm22_poi, cm23_poi, cm24_poi, cm25_poi, cm26_poi, cm27_poi, cm28_poi, cm29_poi, cm30_poi, cm31_poi, cm32_poi, cm33_poi, cm34_poi, cm35_poi, cm36_poi, cm37_poi, cm38_poi, cm39_poi, cm40_poi, cm41_poi, cm42_poi, cm43_poi, cm44_poi, cm45_poi, cm46_poi, cm47_poi, cm48_poi, cm49_poi, cm50_poi, cm51_poi, cm52_poi, cm53_poi, cm54_poi, cm55_poi, cm56_poi, cm57_poi, cm58_poi, cm59_poi, cm60_poi, cm61_poi, cm62_poi, cm63_poi, cm64_poi, cm65_poi, cm66_poi, cm67_poi, cm68_poi, cm69_poi, cm70_poi ,

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(cm_poi.id) FROM trawlers.cm_poi "
                . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT cm_poi.id, datetime::date, username, id_route, maree, lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_taille_poi.taille_poi, t_taille_cre.taille_cre, poids, cm10_poi, cm11_poi, cm12_poi, cm13_poi, cm14_poi, cm15_poi, cm16_poi, cm17_poi, cm18_poi, cm19_poi, cm20_poi, cm21_poi, cm22_poi, cm23_poi, cm24_poi, cm25_poi, cm26_poi, cm27_poi, cm28_poi, cm29_poi, cm30_poi, cm31_poi, cm32_poi, cm33_poi, cm34_poi, cm35_poi, cm36_poi, cm37_poi, cm38_poi, cm39_poi, cm40_poi, cm41_poi, cm42_poi, cm43_poi, cm44_poi, cm45_poi, cm46_poi, cm47_poi, cm48_poi, cm49_poi, cm50_poi, cm51_poi, cm52_poi, cm53_poi, cm54_poi, cm55_poi, cm56_poi, cm57_poi, cm58_poi, cm59_poi, cm60_poi, cm61_poi, cm62_poi, cm63_poi, cm64_poi, cm65_poi, cm66_poi, cm67_poi, cm68_poi, cm69_poi, cm70_poi, "
            . " coalesce(similarity(trawlers.cm_poi.maree, '".$_SESSION['filter']['f_s_maree']."'),0) AS score"
            . " FROM trawlers.cm_poi "
            . "LEFT JOIN fishery.species ON trawlers.cm_poi.id_species = fishery.species.id "
            . "LEFT JOIN trawlers.t_taille_poi ON trawlers.t_taille_poi.id = trawlers.cm_poi.t_taille_poi "
            . "LEFT JOIN trawlers.t_taille_cre ON trawlers.t_taille_cre.id = trawlers.cm_poi.t_taille_cre "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT count(cm_poi.id) FROM trawlers.cm_poi "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";
            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT cm_poi.id, datetime::date, username, id_route, maree, lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_taille_poi.taille_poi, t_taille_cre.taille_cre, poids, cm10_poi, cm11_poi, cm12_poi, cm13_poi, cm14_poi, cm15_poi, cm16_poi, cm17_poi, cm18_poi, cm19_poi, cm20_poi, cm21_poi, cm22_poi, cm23_poi, cm24_poi, cm25_poi, cm26_poi, cm27_poi, cm28_poi, cm29_poi, cm30_poi, cm31_poi, cm32_poi, cm33_poi, cm34_poi, cm35_poi, cm36_poi, cm37_poi, cm38_poi, cm39_poi, cm40_poi, cm41_poi, cm42_poi, cm43_poi, cm44_poi, cm45_poi, cm46_poi, cm47_poi, cm48_poi, cm49_poi, cm50_poi, cm51_poi, cm52_poi, cm53_poi, cm54_poi, cm55_poi, cm56_poi, cm57_poi, cm58_poi, cm59_poi, cm60_poi, cm61_poi, cm62_poi, cm63_poi, cm64_poi, cm65_poi, cm66_poi, cm67_poi, cm68_poi, cm69_poi, cm70_poi  "
            . " FROM trawlers.cm_poi "
            . "LEFT JOIN fishery.species ON trawlers.cm_poi.id_species = fishery.species.id "
            . "LEFT JOIN trawlers.t_taille_poi ON trawlers.t_taille_poi.id = trawlers.cm_poi.t_taille_poi "
            . "LEFT JOIN trawlers.t_taille_cre ON trawlers.t_taille_cre.id = trawlers.cm_poi.t_taille_cre "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(cm_poi.id) FROM trawlers.cm_poi";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT cm_poi.id, datetime::date, username, id_route, maree, lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_taille_poi.taille_poi, t_taille_cre.taille_cre, poids, cm10_poi, cm11_poi, cm12_poi, cm13_poi, cm14_poi, cm15_poi, cm16_poi, cm17_poi, cm18_poi, cm19_poi, cm20_poi, cm21_poi, cm22_poi, cm23_poi, cm24_poi, cm25_poi, cm26_poi, cm27_poi, cm28_poi, cm29_poi, cm30_poi, cm31_poi, cm32_poi, cm33_poi, cm34_poi, cm35_poi, cm36_poi, cm37_poi, cm38_poi, cm39_poi, cm40_poi, cm41_poi, cm42_poi, cm43_poi, cm44_poi, cm45_poi, cm46_poi, cm47_poi, cm48_poi, cm49_poi, cm50_poi, cm51_poi, cm52_poi, cm53_poi, cm54_poi, cm55_poi, cm56_poi, cm57_poi, cm58_poi, cm59_poi, cm60_poi, cm61_poi, cm62_poi, cm63_poi, cm64_poi, cm65_poi, cm66_poi, cm67_poi, cm68_poi, cm69_poi, cm70_poi "
        . " FROM trawlers.cm_poi "
        . "LEFT JOIN fishery.species ON trawlers.cm_poi.id_species = fishery.species.id "
        . "LEFT JOIN trawlers.t_taille_poi ON trawlers.t_taille_poi.id = trawlers.cm_poi.t_taille_poi "
        . "LEFT JOIN trawlers.t_taille_cre ON trawlers.t_taille_cre.id = trawlers.cm_poi.t_taille_cre "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
    }

    #print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_trawlers_cm_poi.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_trawlers_cm_poi.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td><a href=\"./view_route.php?table=route&id=$results[3]\">$results[4]<br/>$results[5]<br/><td>".formatSpecies($results[7],$results[8],$results[9],$results[10])."</td><td>$results[11] $results[12]</td><td>$results[13]</td>"
        . "<td><img src=\"./graph_trawlers_cm_poi.php?id=$results[0]\"></td>"
        . "</tr>";
    }
    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_trawlers_cm_poi.php?source=trawlers&table=cm_poi&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_id_species='.$_SESSION['filter']['f_id_species']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID

    $q_id = "SELECT *, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM trawlers.cm_poi"
        . " LEFT JOIN fishery.species ON fishery.species.id = trawlers.cm_poi.id_species "
        . " WHERE cm_poi.id = '$id'";

    #print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    #id, datetime, username, id_route, maree, lance, id_species, t_sex, category, poids, cm10_poi, cm11_poi, cm12_poi, cm13_poi, cm14_poi, cm15_poi, cm16_poi, cm17_poi, cm18_poi, cm19_poi, cm20_poi, cm21_poi, cm22_poi, cm23_poi, cm24_poi, cm25_poi, cm26_poi, cm27_poi, cm28_poi, cm29_poi, cm30_poi, cm31_poi, cm32_poi, cm33_poi, cm34_poi, cm35_poi, cm36_poi, cm37_poi, cm38_poi, cm39_poi, cm40_poi, cm41_poi, cm42_poi, cm43_poi, cm44_poi, cm45_poi, cm46_poi, cm47_poi, cm48_poi, cm49_poi, cm50_poi, cm51_poi, cm52_poi, cm53_poi, cm54_poi, cm55_poi, cm56_poi, cm57_poi, cm58_poi, cm59_poi, cm60_poi, cm61_poi, cm62_poi, cm63_poi, cm64_poi, cm65_poi, cm66_poi, cm67_poi, cm68_poi, cm69_poi, cm70_poi ,

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Mar&eacute;e</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','lance','maree','lance','trawlers.route')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route ORDER BY maree DESC");
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
    <b>Lanc&eacute;</b>
    <br/>
    <select id="lance" name="lance">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT lance FROM trawlers.route  WHERE maree = '$results[4]' ORDER BY lance");
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
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.cm_poi ON fishery.species.id = trawlers.cm_poi.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
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
    <b>Cat&eacute;gorie de taille Poisson</b>
    <br/>
    <select name="t_taille_poi">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT id, taille_poi FROM trawlers.t_taille_poi");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[7]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Cat&eacute;gorie de taille Crevette</b>
    <br/>
    <select name="t_taille_cre">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT id, taille_cre FROM trawlers.t_taille_cre");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[8]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Poids</b> (kg)
    <br/>
    <input type="text" size="5" name="poids" value="<?php print $results[9]; ?>">
    <br/>
    <br/>
    <b>Individu pour categorie de taille</b> (cm)
    <br/>
    <br/>
    <table border="1">
        <tr align="center">
            <td></td>
            <td></td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>5</td>
            <td>6</td>
            <td>7</td>
            <td>8</td>
            <td>9</td>
        </tr>
        <tr align="center">
            <td></td>
            <td></td>
            <td><input type="text" size="3" name="cm2_poi" value="<?php print $results[10]; ?>"></td>
            <td><input type="text" size="3" name="cm3_poi" value="<?php print $results[11]; ?>"></td>
            <td><input type="text" size="3" name="cm4_poi" value="<?php print $results[12]; ?>"></td>
            <td><input type="text" size="3" name="cm5_poi" value="<?php print $results[13]; ?>"></td>
            <td><input type="text" size="3" name="cm6_poi" value="<?php print $results[14]; ?>"></td>
            <td><input type="text" size="3" name="cm7_poi" value="<?php print $results[15]; ?>"></td>
            <td><input type="text" size="3" name="cm8_poi" value="<?php print $results[16]; ?>"></td>
            <td><input type="text" size="3" name="cm9_poi" value="<?php print $results[17]; ?>"></td>
        </tr>
    <?php
        for ($i = 1; $i < 11; $i++) {
            print '<tr align="center">';

                for ($j = 0; $j < 10; $j++) {
                    $k = $j+$i*10;
                    $l = $k;
                    print '<td>'.$l.'</td>';
                }

            print '<tr/><tr align="center">';

                for ($j = 0; $j < 10; $j++) {
                    $k = $j+$i*10;
                    print '<td><input type="text" size="3" name="cm'.$k.'_poi" value="'.$results[$k + 8].'"></td>';
                }
            print "</tr>";
        }
    ?>

        <tr align="center">
            <td>110</td>
        </tr>
        <tr align="center">
            <td><input type="text" size="3" name="cm110_poi" value="<?php print $results[$k + 9]; ?>"></td>
        </tr></table>

    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM trawlers.cm_poi WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_cm_poi.php?source=$source&table=cm_poi&action=show");
    }
    $controllo = 1;

}


if ($_POST['submit'] == "Enregistrer") {
    # id, datetime, username, id_route, maree, lance, id_species, poids, cm10_poi, cm11_poi, cm12_poi, cm13_poi, cm14_poi, cm15_poi, cm16_poi, cm17_poi, cm18_poi, cm19_poi, cm20_poi, cm21_poi, cm22_poi, cm23_poi, cm24_poi, cm25_poi, cm26_poi, cm27_poi, cm28_poi, cm29_poi, cm30_poi, cm31_poi, cm32_poi, cm33_poi, cm34_poi, cm35_poi, cm36_poi, cm37_poi, cm38_poi, cm39_poi, cm40_poi, cm41_poi, cm42_poi, cm43_poi, cm44_poi, cm45_poi, cm46_poi, cm47_poi, cm48_poi, cm49_poi, cm50_poi, cm51_poi, cm52_poi, cm53_poi, cm54_poi, cm55_poi, cm56_poi, cm57_poi, cm58_poi, cm59_poi, cm60_poi, cm61_poi, cm62_poi, cm63_poi, cm64_poi, cm65_poi, cm66_poi, cm67_poi, cm68_poi, cm69_poi, cm70_poi ,

    $maree = $_POST['maree'];
    $lance = $_POST['lance'];
    $id_species = $_POST['id_species'];
    $t_taille_poi = $_POST['t_taille_poi'];
    $t_taille_cre = $_POST['t_taille_cre'];
    $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);

    $q_id = "SELECT id FROM trawlers.route WHERE maree = '$maree' AND lance = '$lance'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    if ($_POST['new_old']) {
        $query = "INSERT INTO trawlers.cm_poi "
            . "(datetime, username, id_route, maree, lance, id_species, t_taille_poi, t_taille_cre, poids, cm2_poi, cm3_poi, cm4_poi, cm5_poi, cm6_poi, cm7_poi, cm8_poi, cm9_poi, "
                . "cm10_poi, cm11_poi, cm12_poi, cm13_poi, cm14_poi, cm15_poi, cm16_poi, cm17_poi, cm18_poi, cm19_poi, "
                . "cm20_poi, cm21_poi, cm22_poi, cm23_poi, cm24_poi, cm25_poi, cm26_poi, cm27_poi, cm28_poi, cm29_poi, "
                . "cm30_poi, cm31_poi, cm32_poi, cm33_poi, cm34_poi, cm35_poi, cm36_poi, cm37_poi, cm38_poi, cm39_poi, "
                . "cm40_poi, cm41_poi, cm42_poi, cm43_poi, cm44_poi, cm45_poi, cm46_poi, cm47_poi, cm48_poi, cm49_poi, "
                . "cm50_poi, cm51_poi, cm52_poi, cm53_poi, cm54_poi, cm55_poi, cm56_poi, cm57_poi, cm58_poi, cm59_poi, "
                . "cm60_poi, cm61_poi, cm62_poi, cm63_poi, cm64_poi, cm65_poi, cm66_poi, cm67_poi, cm68_poi, cm69_poi, "
                . "cm70_poi, cm71_poi, cm72_poi, cm73_poi, cm74_poi, cm75_poi, cm76_poi, cm77_poi, cm78_poi, cm79_poi, "
                . "cm80_poi, cm81_poi, cm82_poi, cm83_poi, cm84_poi, cm85_poi, cm86_poi, cm87_poi, cm88_poi, cm89_poi, "
                . "cm90_poi, cm91_poi, cm92_poi, cm93_poi, cm94_poi, cm95_poi, cm96_poi, cm97_poi, cm98_poi, cm99_poi, "
                . "cm100_poi, cm101_poi, cm102_poi, cm103_poi, cm104_poi, cm105_poi, cm106_poi, cm107_poi, cm108_poi, cm109_poi, "
                . "cm110_poi) "
            . "VALUES (now(), '$username', '$id_route', '$maree', '$lance', '$id_species', '$t_taille_poi', '$t_taille_cre', '$poids', '"
            .$_POST['cm2_poi']."', '".$_POST['cm3_poi']."', '".$_POST['cm4_poi']."', '".$_POST['cm5_poi']."', '".$_POST['cm6_poi']."', '".$_POST['cm7_poi']."', '".$_POST['cm8_poi']."', '".$_POST['cm9_poi']."', '".$_POST['cm10_poi']."', '".$_POST['cm11_poi']."', '".$_POST['cm12_poi']."', '".$_POST['cm13_poi']."', '".$_POST['cm14_poi']."', '".$_POST['cm15_poi']."', '".$_POST['cm16_poi']."', '".$_POST['cm17_poi']."', '".$_POST['cm18_poi']."', '".$_POST['cm19_poi']."', '"
            .$_POST['cm20_poi']."', '".$_POST['cm21_poi']."', '".$_POST['cm22_poi']."', '".$_POST['cm23_poi']."', '".$_POST['cm24_poi']."', '".$_POST['cm25_poi']."', '".$_POST['cm26_poi']."', '".$_POST['cm27_poi']."', '".$_POST['cm28_poi']."', '".$_POST['cm29_poi']."', '"
            .$_POST['cm30_poi']."', '".$_POST['cm31_poi']."', '".$_POST['cm32_poi']."', '".$_POST['cm33_poi']."', '".$_POST['cm34_poi']."', '".$_POST['cm35_poi']."', '".$_POST['cm36_poi']."', '".$_POST['cm37_poi']."', '".$_POST['cm38_poi']."', '".$_POST['cm39_poi']."', '"
            .$_POST['cm40_poi']."', '".$_POST['cm41_poi']."', '".$_POST['cm42_poi']."', '".$_POST['cm43_poi']."', '".$_POST['cm44_poi']."', '".$_POST['cm45_poi']."', '".$_POST['cm46_poi']."', '".$_POST['cm47_poi']."', '".$_POST['cm48_poi']."', '".$_POST['cm49_poi']."', '"
            .$_POST['cm50_poi']."', '".$_POST['cm51_poi']."', '".$_POST['cm52_poi']."', '".$_POST['cm53_poi']."', '".$_POST['cm54_poi']."', '".$_POST['cm55_poi']."', '".$_POST['cm56_poi']."', '".$_POST['cm57_poi']."', '".$_POST['cm58_poi']."', '".$_POST['cm59_poi']."', '"
            .$_POST['cm60_poi']."', '".$_POST['cm61_poi']."', '".$_POST['cm62_poi']."', '".$_POST['cm63_poi']."', '".$_POST['cm64_poi']."', '".$_POST['cm65_poi']."', '".$_POST['cm66_poi']."', '".$_POST['cm67_poi']."', '".$_POST['cm68_poi']."', '".$_POST['cm69_poi']."', '"
            .$_POST['cm70_poi']."', '".$_POST['cm71_poi']."', '".$_POST['cm72_poi']."', '".$_POST['cm73_poi']."', '".$_POST['cm74_poi']."', '".$_POST['cm75_poi']."', '".$_POST['cm76_poi']."', '".$_POST['cm77_poi']."', '".$_POST['cm78_poi']."', '".$_POST['cm79_poi']."', '"
            .$_POST['cm80_poi']."', '".$_POST['cm81_poi']."', '".$_POST['cm82_poi']."', '".$_POST['cm83_poi']."', '".$_POST['cm84_poi']."', '".$_POST['cm85_poi']."', '".$_POST['cm86_poi']."', '".$_POST['cm87_poi']."', '".$_POST['cm88_poi']."', '".$_POST['cm89_poi']."', '"
            .$_POST['cm90_poi']."', '".$_POST['cm91_poi']."', '".$_POST['cm92_poi']."', '".$_POST['cm93_poi']."', '".$_POST['cm94_poi']."', '".$_POST['cm95_poi']."', '".$_POST['cm96_poi']."', '".$_POST['cm97_poi']."', '".$_POST['cm98_poi']."', '".$_POST['cm99_poi']."', '"
            .$_POST['cm100_poi']."', '".$_POST['cm101_poi']."', '".$_POST['cm102_poi']."', '".$_POST['cm103_poi']."', '".$_POST['cm104_poi']."', '".$_POST['cm105_poi']."', '".$_POST['cm106_poi']."', '".$_POST['cm107_poi']."', '".$_POST['cm108_poi']."', '".$_POST['cm109_poi']."', '"
            .$_POST['cm110_poi']."')";


    } else {
        $query = "UPDATE trawlers.cm_poi SET "
            . "username = '$username', datetime = now(), id_route = '".$id_route."', "
            . "maree = '".$maree."', lance = '".$lance."', id_species = '".$id_species."', t_taille_poi = '".$t_taille_poi."', t_taille_cre = '".$t_taille_cre."', poids = '".$poids."', "
            . "cm2_poi = '".$_POST['cm2_poi']."', cm3_poi = '".$_POST['cm3_poi']."', cm4_poi = '".$_POST['cm4_poi']."', cm5_poi = '".$_POST['cm5_poi']."', cm6_poi = '".$_POST['cm6_poi']."', cm7_poi = '".$_POST['cm7_poi']."', cm8_poi = '".$_POST['cm8_poi']."', cm9_poi = '".$_POST['cm9_poi']."', cm10_poi = '".$_POST['cm10_poi']."', cm11_poi = '".$_POST['cm11_poi']."', cm12_poi = '".$_POST['cm12_poi']."', cm13_poi  = '".$_POST['cm13_poi']."', cm14_poi  = '".$_POST['cm14_poi']."', cm15_poi  = '".$_POST['cm15_poi']."', cm16_poi  = '".$_POST['cm16_poi']."', cm17_poi  = '".$_POST['cm17_poi']."', cm18_poi  = '".$_POST['cm18_poi']."', cm19_poi  = '".$_POST['cm19_poi']."', "
            . "cm20_poi = '".$_POST['cm20_poi']."', cm21_poi = '".$_POST['cm21_poi']."', cm22_poi = '".$_POST['cm22_poi']."', cm23_poi = '".$_POST['cm23_poi']."', cm24_poi = '".$_POST['cm24_poi']."', cm25_poi = '".$_POST['cm25_poi']."', cm26_poi = '".$_POST['cm26_poi']."', cm27_poi = '".$_POST['cm27_poi']."', cm28_poi = '".$_POST['cm28_poi']."', cm29_poi = '".$_POST['cm29_poi']."', "
            . "cm30_poi = '".$_POST['cm30_poi']."', cm31_poi = '".$_POST['cm31_poi']."', cm32_poi = '".$_POST['cm32_poi']."', cm33_poi = '".$_POST['cm33_poi']."', cm34_poi = '".$_POST['cm34_poi']."', cm35_poi = '".$_POST['cm35_poi']."', cm36_poi = '".$_POST['cm36_poi']."', cm37_poi = '".$_POST['cm37_poi']."', cm38_poi = '".$_POST['cm38_poi']."', cm39_poi = '".$_POST['cm39_poi']."', "
            . "cm40_poi = '".$_POST['cm40_poi']."', cm41_poi = '".$_POST['cm41_poi']."', cm42_poi = '".$_POST['cm42_poi']."', cm43_poi = '".$_POST['cm43_poi']."', cm44_poi = '".$_POST['cm44_poi']."', cm45_poi = '".$_POST['cm45_poi']."', cm46_poi = '".$_POST['cm46_poi']."', cm47_poi = '".$_POST['cm47_poi']."', cm48_poi = '".$_POST['cm48_poi']."', cm49_poi = '".$_POST['cm49_poi']."', "
            . "cm50_poi = '".$_POST['cm50_poi']."', cm51_poi = '".$_POST['cm51_poi']."', cm52_poi = '".$_POST['cm52_poi']."', cm53_poi = '".$_POST['cm53_poi']."', cm54_poi = '".$_POST['cm54_poi']."', cm55_poi = '".$_POST['cm55_poi']."', cm56_poi = '".$_POST['cm56_poi']."', cm57_poi = '".$_POST['cm57_poi']."', cm58_poi = '".$_POST['cm58_poi']."', cm59_poi = '".$_POST['cm59_poi']."', "
            . "cm60_poi = '".$_POST['cm60_poi']."', cm61_poi = '".$_POST['cm61_poi']."', cm62_poi = '".$_POST['cm62_poi']."', cm63_poi = '".$_POST['cm63_poi']."', cm64_poi = '".$_POST['cm64_poi']."', cm65_poi = '".$_POST['cm65_poi']."', cm66_poi = '".$_POST['cm66_poi']."', cm67_poi = '".$_POST['cm67_poi']."', cm68_poi = '".$_POST['cm68_poi']."', cm69_poi = '".$_POST['cm69_poi']."', "
            . "cm70_poi = '".$_POST['cm70_poi']."', cm71_poi = '".$_POST['cm71_poi']."', cm72_poi = '".$_POST['cm72_poi']."', cm73_poi = '".$_POST['cm73_poi']."', cm74_poi = '".$_POST['cm74_poi']."', cm75_poi = '".$_POST['cm75_poi']."', cm76_poi = '".$_POST['cm76_poi']."', cm77_poi = '".$_POST['cm77_poi']."', cm78_poi = '".$_POST['cm78_poi']."', cm79_poi = '".$_POST['cm79_poi']."', "
            . "cm80_poi = '".$_POST['cm80_poi']."', cm81_poi = '".$_POST['cm81_poi']."', cm82_poi = '".$_POST['cm82_poi']."', cm83_poi = '".$_POST['cm83_poi']."', cm84_poi = '".$_POST['cm84_poi']."', cm85_poi = '".$_POST['cm85_poi']."', cm86_poi = '".$_POST['cm86_poi']."', cm87_poi = '".$_POST['cm87_poi']."', cm88_poi = '".$_POST['cm88_poi']."', cm89_poi = '".$_POST['cm89_poi']."', "
            . "cm90_poi = '".$_POST['cm90_poi']."', cm91_poi = '".$_POST['cm91_poi']."', cm92_poi = '".$_POST['cm92_poi']."', cm93_poi = '".$_POST['cm93_poi']."', cm94_poi = '".$_POST['cm94_poi']."', cm95_poi = '".$_POST['cm95_poi']."', cm96_poi = '".$_POST['cm96_poi']."', cm97_poi = '".$_POST['cm97_poi']."', cm98_poi = '".$_POST['cm98_poi']."', cm99_poi = '".$_POST['cm99_poi']."', "
            . "cm100_poi = '".$_POST['cm100_poi']."', cm101_poi = '".$_POST['cm101_poi']."', cm102_poi = '".$_POST['cm102_poi']."', cm103_poi = '".$_POST['cm103_poi']."', cm104_poi = '".$_POST['cm104_poi']."', cm105_poi = '".$_POST['cm105_poi']."', cm106_poi = '".$_POST['cm106_poi']."', cm107_poi = '".$_POST['cm107_poi']."', cm108_poi = '".$_POST['cm108_poi']."', cm109_poi = '".$_POST['cm109_poi']."', "
            . "cm110_poi = '".$_POST['cm110_poi']."' "
            . "WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_cm_poi.php?source=$source&table=cm_poi&action=show");
    }
}

foot();
