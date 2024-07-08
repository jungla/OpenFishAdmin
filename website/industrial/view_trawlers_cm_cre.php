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
    <form method="post" action="<?php echo $self;?>?source=trawlers&table=cm_cre&action=show" enctype="multipart/form-data">
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
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.cm_cre ON fishery.species.id = trawlers.cm_cre.id_species  ORDER BY  fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ("'".$row[0]."'" == $_SESSION['filter']['f_id_species']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
            } else {
                print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
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

        #id, datetime, username, id_route, maree, lance, id_species, t_sex, category, poids, cm10_cre, cm11_cre, cm12_cre, cm13_cre, cm14_cre, cm15_cre, cm16_cre, cm17_cre, cm18_cre, cm19_cre, cm20_cre, cm21_cre, cm22_cre, cm23_cre, cm24_cre, cm25_cre, cm26_cre, cm27_cre, cm28_cre, cm29_cre, cm30_cre, cm31_cre, cm32_cre, cm33_cre, cm34_cre, cm35_cre, cm36_cre, cm37_cre, cm38_cre, cm39_cre, cm40_cre, cm41_cre, cm42_cre, cm43_cre, cm44_cre, cm45_cre, cm46_cre, cm47_cre, cm48_cre, cm49_cre, cm50_cre, cm51_cre, cm52_cre, cm53_cre, cm54_cre, cm55_cre, cm56_cre, cm57_cre, cm58_cre, cm59_cre, cm60_cre, cm61_cre, cm62_cre, cm63_cre, cm64_cre, cm65_cre, cm66_cre, cm67_cre, cm68_cre, cm69_cre, cm70_cre ,

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(cm_cre.id) FROM trawlers.cm_cre "
                . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT cm_cre.id, datetime::date, username, id_route, maree, lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_taille_poi.taille_poi, t_taille_cre.taille_cre, poids, cm10_cre, cm11_cre, cm12_cre, cm13_cre, cm14_cre, cm15_cre, cm16_cre, cm17_cre, cm18_cre, cm19_cre, cm20_cre, cm21_cre, cm22_cre, cm23_cre, cm24_cre, cm25_cre, cm26_cre, cm27_cre, cm28_cre, cm29_cre, cm30_cre, cm31_cre, cm32_cre, cm33_cre, cm34_cre, cm35_cre, cm36_cre, cm37_cre, cm38_cre, cm39_cre, cm40_cre, cm41_cre, cm42_cre, cm43_cre, cm44_cre, cm45_cre, cm46_cre, cm47_cre, cm48_cre, cm49_cre, cm50_cre, cm51_cre, cm52_cre, cm53_cre, cm54_cre, cm55_cre, cm56_cre, cm57_cre, cm58_cre, cm59_cre, cm60_cre, cm61_cre, cm62_cre, cm63_cre, cm64_cre, cm65_cre, cm66_cre, cm67_cre, cm68_cre, cm69_cre, cm70_cre, "
            . " coalesce(similarity(trawlers.cm_cre.maree, '".$_SESSION['filter']['f_s_maree']."'),0) AS score"
            . " FROM trawlers.cm_cre "
            . "LEFT JOIN fishery.species ON trawlers.cm_cre.id_species = fishery.species.id "
            . "LEFT JOIN trawlers.t_taille_poi ON trawlers.t_taille_poi.id = trawlers.cm_cre.t_taille_poi "
            . "LEFT JOIN trawlers.t_taille_cre ON trawlers.t_taille_cre.id = trawlers.cm_cre.t_taille_cre "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT count(cm_cre.id) FROM trawlers.cm_cre "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";
            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT cm_cre.id, datetime::date, username, id_route, maree, lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_taille_poi.taille_poi, t_taille_cre.taille_cre, poids, cm10_cre, cm11_cre, cm12_cre, cm13_cre, cm14_cre, cm15_cre, cm16_cre, cm17_cre, cm18_cre, cm19_cre, cm20_cre, cm21_cre, cm22_cre, cm23_cre, cm24_cre, cm25_cre, cm26_cre, cm27_cre, cm28_cre, cm29_cre, cm30_cre, cm31_cre, cm32_cre, cm33_cre, cm34_cre, cm35_cre, cm36_cre, cm37_cre, cm38_cre, cm39_cre, cm40_cre, cm41_cre, cm42_cre, cm43_cre, cm44_cre, cm45_cre, cm46_cre, cm47_cre, cm48_cre, cm49_cre, cm50_cre, cm51_cre, cm52_cre, cm53_cre, cm54_cre, cm55_cre, cm56_cre, cm57_cre, cm58_cre, cm59_cre, cm60_cre, cm61_cre, cm62_cre, cm63_cre, cm64_cre, cm65_cre, cm66_cre, cm67_cre, cm68_cre, cm69_cre, cm70_cre  "
            . " FROM trawlers.cm_cre "
            . "LEFT JOIN fishery.species ON trawlers.cm_cre.id_species = fishery.species.id "
            . "LEFT JOIN trawlers.t_taille_poi ON trawlers.t_taille_poi.id = trawlers.cm_cre.t_taille_poi "
            . "LEFT JOIN trawlers.t_taille_cre ON trawlers.t_taille_cre.id = trawlers.cm_cre.t_taille_cre "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(cm_cre.id) FROM trawlers.cm_cre";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT cm_cre.id, datetime::date, username, id_route, maree, lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_taille_poi.taille_poi, t_taille_cre.taille_cre, poids, cm10_cre, cm11_cre, cm12_cre, cm13_cre, cm14_cre, cm15_cre, cm16_cre, cm17_cre, cm18_cre, cm19_cre, cm20_cre, cm21_cre, cm22_cre, cm23_cre, cm24_cre, cm25_cre, cm26_cre, cm27_cre, cm28_cre, cm29_cre, cm30_cre, cm31_cre, cm32_cre, cm33_cre, cm34_cre, cm35_cre, cm36_cre, cm37_cre, cm38_cre, cm39_cre, cm40_cre, cm41_cre, cm42_cre, cm43_cre, cm44_cre, cm45_cre, cm46_cre, cm47_cre, cm48_cre, cm49_cre, cm50_cre, cm51_cre, cm52_cre, cm53_cre, cm54_cre, cm55_cre, cm56_cre, cm57_cre, cm58_cre, cm59_cre, cm60_cre, cm61_cre, cm62_cre, cm63_cre, cm64_cre, cm65_cre, cm66_cre, cm67_cre, cm68_cre, cm69_cre, cm70_cre "
        . " FROM trawlers.cm_cre "
        . "LEFT JOIN fishery.species ON trawlers.cm_cre.id_species = fishery.species.id "
        . "LEFT JOIN trawlers.t_taille_poi ON trawlers.t_taille_poi.id = trawlers.cm_cre.t_taille_poi "
        . "LEFT JOIN trawlers.t_taille_cre ON trawlers.t_taille_cre.id = trawlers.cm_cre.t_taille_cre "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_trawlers_cm_cre.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_trawlers_cm_cre.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td><a href=\"./view_route.php?table=route&id=$results[3]\">$results[4]<br/>$results[5]<br/><td>".formatSpecies($results[7],$results[8],$results[9],$results[10])."</td><td>$results[11] $results[12]</td><td>$results[13]</td>"
        . "<td><img src=\"./graph_trawlers_cm_cre.php?id=$results[0]\"></td>"
        . "</tr>";
    }
    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_trawlers_cm_cre.php?source=trawlers&table=cm_cre&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_id_species='.$_SESSION['filter']['f_id_species']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID

    $q_id = "SELECT *, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM trawlers.cm_cre"
        . " LEFT JOIN fishery.species ON fishery.species.id = trawlers.cm_cre.id_species "
        . " WHERE cm_cre.id = '$id'";

    #print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    #id, datetime, username, id_route, maree, lance, id_species, t_sex, category, poids, cm10_cre, cm11_cre, cm12_cre, cm13_cre, cm14_cre, cm15_cre, cm16_cre, cm17_cre, cm18_cre, cm19_cre, cm20_cre, cm21_cre, cm22_cre, cm23_cre, cm24_cre, cm25_cre, cm26_cre, cm27_cre, cm28_cre, cm29_cre, cm30_cre, cm31_cre, cm32_cre, cm33_cre, cm34_cre, cm35_cre, cm36_cre, cm37_cre, cm38_cre, cm39_cre, cm40_cre, cm41_cre, cm42_cre, cm43_cre, cm44_cre, cm45_cre, cm46_cre, cm47_cre, cm48_cre, cm49_cre, cm50_cre, cm51_cre, cm52_cre, cm53_cre, cm54_cre, cm55_cre, cm56_cre, cm57_cre, cm58_cre, cm59_cre, cm60_cre, cm61_cre, cm62_cre, cm63_cre, cm64_cre, cm65_cre, cm66_cre, cm67_cre, cm68_cre, cm69_cre, cm70_cre ,

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
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.cm_cre ON fishery.species.id = trawlers.cm_cre.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
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
          <td></td>
          <td></td>

            <td>4</td>
            <td>5</td>
            <td>6</td>
            <td>7</td>
            <td>8</td>
            <td>9</td>
        </tr>
        <tr  align="center">
          <td></td>
          <td></td>
          <td></td>
          <td></td>
            <td><input type="text" size="3" name="cm4_cre" value="<?php print $results[10]; ?>"></td>
            <td><input type="text" size="3" name="cm5_cre" value="<?php print $results[11]; ?>"></td>
            <td><input type="text" size="3" name="cm6_cre" value="<?php print $results[12]; ?>"></td>
            <td><input type="text" size="3" name="cm7_cre" value="<?php print $results[13]; ?>"></td>
            <td><input type="text" size="3" name="cm8_cre" value="<?php print $results[14]; ?>"></td>
            <td><input type="text" size="3" name="cm9_cre" value="<?php print $results[15]; ?>"></td>
        </tr>

    <?php
        for ($i = 1; $i < 8; $i++) {
            print '<tr align="center">';

                for ($j = 0; $j < 10; $j++) {
                    $k = $j+$i*10;
                    $l = $k;
                    print '<td>'.$l.'</td>';
                }

            print '<tr/><tr align="center">';

                for ($j = 0; $j < 10; $j++) {
                    $k = $j+$i*10;
                    print '<td><input type="text" size="3" name="cm'.$k.'_cre" value="'.$results[$k + 6].'"></td>';
                }
            print "</tr>";
        }
    ?>
        <tr align="center">
            <td>80</td>
            <td>81</td>
            <td>82</td>
            <td>83</td>
            <td>84</td>
            <td>85</td>
        </tr>
        <tr align="center">
            <td><input type="text" size="3" name="cm80_cre" value="<?php print $results[$k+7]; ?>"></td>
            <td><input type="text" size="3" name="cm81_cre" value="<?php print $results[$k+8]; ?>"></td>
            <td><input type="text" size="3" name="cm82_cre" value="<?php print $results[$k+9]; ?>"></td>
            <td><input type="text" size="3" name="cm83_cre" value="<?php print $results[$k+10]; ?>"></td>
            <td><input type="text" size="3" name="cm84_cre" value="<?php print $results[$k+11]; ?>"></td>
            <td><input type="text" size="3" name="cm85_cre" value="<?php print $results[$k+12]; ?>"></td>
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
    $query = "DELETE FROM trawlers.cm_cre WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_cm_cre.php?source=$source&table=cm_cre&action=show");
    }
    $controllo = 1;

}


if ($_POST['submit'] == "Enregistrer") {
    # id, datetime, username, id_route, maree, lance, id_species, poids, cm10_cre, cm11_cre, cm12_cre, cm13_cre, cm14_cre, cm15_cre, cm16_cre, cm17_cre, cm18_cre, cm19_cre, cm20_cre, cm21_cre, cm22_cre, cm23_cre, cm24_cre, cm25_cre, cm26_cre, cm27_cre, cm28_cre, cm29_cre, cm30_cre, cm31_cre, cm32_cre, cm33_cre, cm34_cre, cm35_cre, cm36_cre, cm37_cre, cm38_cre, cm39_cre, cm40_cre, cm41_cre, cm42_cre, cm43_cre, cm44_cre, cm45_cre, cm46_cre, cm47_cre, cm48_cre, cm49_cre, cm50_cre, cm51_cre, cm52_cre, cm53_cre, cm54_cre, cm55_cre, cm56_cre, cm57_cre, cm58_cre, cm59_cre, cm60_cre, cm61_cre, cm62_cre, cm63_cre, cm64_cre, cm65_cre, cm66_cre, cm67_cre, cm68_cre, cm69_cre, cm70_cre ,


    $maree = $_POST['maree'];
    $lance = $_POST['lance'];
    $id_species = $_POST['id_species'];
    $t_taille_poi = $_POST['t_taille_poi'];
    $t_taille_cre = $_POST['t_taille_cre'];
    $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);

    $q_id = "SELECT id FROM trawlers.route WHERE maree = '$maree' AND lance = '$lance'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    if ($_POST['new_old']) {
        $query = "INSERT INTO trawlers.cm_cre "
            . "(datetime, username, id_route, maree, lance, id_species, t_taille_poi, t_taille_cre, poids, cm4_cre, cm5_cre, cm6_cre, cm7_cre, cm8_cre, cm9_cre, cm10_cre, cm11_cre, cm12_cre, cm13_cre, cm14_cre, cm15_cre, cm16_cre, cm17_cre, cm18_cre, cm19_cre, cm20_cre, cm21_cre, cm22_cre, cm23_cre, cm24_cre, cm25_cre, cm26_cre, cm27_cre, cm28_cre, cm29_cre, cm30_cre, cm31_cre, cm32_cre, cm33_cre, cm34_cre, cm35_cre, cm36_cre, cm37_cre, cm38_cre, cm39_cre, cm40_cre, cm41_cre, cm42_cre, cm43_cre, cm44_cre, cm45_cre, cm46_cre, cm47_cre, cm48_cre, cm49_cre, cm50_cre, cm51_cre, cm52_cre, cm53_cre, cm54_cre, cm55_cre, cm56_cre, cm57_cre, cm58_cre, cm59_cre, cm60_cre, cm61_cre, cm62_cre, cm63_cre, cm64_cre, cm65_cre, cm66_cre, cm67_cre, cm68_cre, cm69_cre, cm70_cre, cm71_cre, cm72_cre, cm73_cre, cm74_cre, cm75_cre, cm76_cre, cm77_cre, cm78_cre, cm79_cre, cm80_cre, cm81_cre, cm82_cre, cm83_cre, cm84_cre, cm85_cre) "
            . "VALUES (now(), '$username', '$id_route', '$maree', '$lance', '$id_species', '$t_taille_poi', '$t_taille_cre', '$poids', '"
            .$_POST['cm4_cre']."', '".$_POST['cm5_cre']."', '".$_POST['cm6_cre']."', '".$_POST['cm7_cre']."', '".$_POST['cm8_cre']."', '".$_POST['cm9_cre']."', '".$_POST['cm10_cre']."', '".$_POST['cm11_cre']."', '".$_POST['cm12_cre']."', '".$_POST['cm13_cre']."', '".$_POST['cm14_cre']."', '".$_POST['cm15_cre']."', '".$_POST['cm16_cre']."', '".$_POST['cm17_cre']."', '".$_POST['cm18_cre']."', '".$_POST['cm19_cre']."', '"
            .$_POST['cm20_cre']."', '".$_POST['cm21_cre']."', '".$_POST['cm22_cre']."', '".$_POST['cm23_cre']."', '".$_POST['cm24_cre']."', '".$_POST['cm25_cre']."', '".$_POST['cm26_cre']."', '".$_POST['cm27_cre']."', '".$_POST['cm28_cre']."', '".$_POST['cm29_cre']."', '"
            .$_POST['cm30_cre']."', '".$_POST['cm31_cre']."', '".$_POST['cm32_cre']."', '".$_POST['cm33_cre']."', '".$_POST['cm34_cre']."', '".$_POST['cm35_cre']."', '".$_POST['cm36_cre']."', '".$_POST['cm37_cre']."', '".$_POST['cm38_cre']."', '".$_POST['cm39_cre']."', '"
            .$_POST['cm40_cre']."', '".$_POST['cm41_cre']."', '".$_POST['cm42_cre']."', '".$_POST['cm43_cre']."', '".$_POST['cm44_cre']."', '".$_POST['cm45_cre']."', '".$_POST['cm46_cre']."', '".$_POST['cm47_cre']."', '".$_POST['cm48_cre']."', '".$_POST['cm49_cre']."', '"
            .$_POST['cm50_cre']."', '".$_POST['cm51_cre']."', '".$_POST['cm52_cre']."', '".$_POST['cm53_cre']."', '".$_POST['cm54_cre']."', '".$_POST['cm55_cre']."', '".$_POST['cm56_cre']."', '".$_POST['cm57_cre']."', '".$_POST['cm58_cre']."', '".$_POST['cm59_cre']."', '"
            .$_POST['cm60_cre']."', '".$_POST['cm61_cre']."', '".$_POST['cm62_cre']."', '".$_POST['cm63_cre']."', '".$_POST['cm64_cre']."', '".$_POST['cm65_cre']."', '".$_POST['cm66_cre']."', '".$_POST['cm67_cre']."', '".$_POST['cm68_cre']."', '".$_POST['cm69_cre']."', '"
            .$_POST['cm70_cre']."', '".$_POST['cm71_cre']."', '".$_POST['cm72_cre']."', '".$_POST['cm73_cre']."', '".$_POST['cm74_cre']."', '".$_POST['cm75_cre']."', '".$_POST['cm76_cre']."', '".$_POST['cm77_cre']."', '".$_POST['cm78_cre']."', '".$_POST['cm79_cre']."', '"
            .$_POST['cm80_cre']."', '".$_POST['cm81_cre']."', '".$_POST['cm82_cre']."', '".$_POST['cm83_cre']."', '".$_POST['cm84_cre']."', '".$_POST['cm85_cre']."')";


    } else {
        $query = "UPDATE trawlers.cm_cre SET "
            . "username = '$username', datetime = now(), id_route = '".$id_route."', "
            . "maree = '".$maree."', lance = '".$lance."', id_species = '".$id_species."', t_taille_poi = '".$t_taille_poi."', t_taille_cre = '".$t_taille_cre."', poids = '".$poids."', "
            . "cm4_cre = '".$_POST['cm4_cre']."', cm5_cre = '".$_POST['cm5_cre']."', cm6_cre = '".$_POST['cm6_cre']."', cm7_cre = '".$_POST['cm7_cre']."', cm8_cre = '".$_POST['cm8_cre']."', cm9_cre = '".$_POST['cm9_cre']."', cm10_cre = '".$_POST['cm10_cre']."', cm11_cre = '".$_POST['cm11_cre']."', cm12_cre = '".$_POST['cm12_cre']."', cm13_cre  = '".$_POST['cm13_cre']."', cm14_cre  = '".$_POST['cm14_cre']."', cm15_cre  = '".$_POST['cm15_cre']."', cm16_cre  = '".$_POST['cm16_cre']."', cm17_cre  = '".$_POST['cm17_cre']."', cm18_cre  = '".$_POST['cm18_cre']."', cm19_cre  = '".$_POST['cm19_cre']."', "
            . "cm20_cre = '".$_POST['cm20_cre']."', cm21_cre = '".$_POST['cm21_cre']."', cm22_cre = '".$_POST['cm22_cre']."', cm23_cre = '".$_POST['cm23_cre']."', cm24_cre = '".$_POST['cm24_cre']."', cm25_cre = '".$_POST['cm25_cre']."', cm26_cre = '".$_POST['cm26_cre']."', cm27_cre = '".$_POST['cm27_cre']."', cm28_cre = '".$_POST['cm28_cre']."', cm29_cre = '".$_POST['cm29_cre']."', "
            . "cm30_cre = '".$_POST['cm30_cre']."', cm31_cre = '".$_POST['cm31_cre']."', cm32_cre = '".$_POST['cm32_cre']."', cm33_cre = '".$_POST['cm33_cre']."', cm34_cre = '".$_POST['cm34_cre']."', cm35_cre = '".$_POST['cm35_cre']."', cm36_cre = '".$_POST['cm36_cre']."', cm37_cre = '".$_POST['cm37_cre']."', cm38_cre = '".$_POST['cm38_cre']."', cm39_cre = '".$_POST['cm39_cre']."', "
            . "cm40_cre = '".$_POST['cm40_cre']."', cm41_cre = '".$_POST['cm41_cre']."', cm42_cre = '".$_POST['cm42_cre']."', cm43_cre = '".$_POST['cm43_cre']."', cm44_cre = '".$_POST['cm44_cre']."', cm45_cre = '".$_POST['cm45_cre']."', cm46_cre = '".$_POST['cm46_cre']."', cm47_cre = '".$_POST['cm47_cre']."', cm48_cre = '".$_POST['cm48_cre']."', cm49_cre = '".$_POST['cm49_cre']."', "
            . "cm50_cre = '".$_POST['cm50_cre']."', cm51_cre = '".$_POST['cm51_cre']."', cm52_cre = '".$_POST['cm52_cre']."', cm53_cre = '".$_POST['cm53_cre']."', cm54_cre = '".$_POST['cm54_cre']."', cm55_cre = '".$_POST['cm55_cre']."', cm56_cre = '".$_POST['cm56_cre']."', cm57_cre = '".$_POST['cm57_cre']."', cm58_cre = '".$_POST['cm58_cre']."', cm59_cre = '".$_POST['cm59_cre']."', "
            . "cm60_cre = '".$_POST['cm60_cre']."', cm61_cre = '".$_POST['cm61_cre']."', cm62_cre = '".$_POST['cm62_cre']."', cm63_cre = '".$_POST['cm63_cre']."', cm64_cre = '".$_POST['cm64_cre']."', cm65_cre = '".$_POST['cm65_cre']."', cm66_cre = '".$_POST['cm66_cre']."', cm67_cre = '".$_POST['cm67_cre']."', cm68_cre = '".$_POST['cm68_cre']."', cm69_cre = '".$_POST['cm69_cre']."', "
            . "cm70_cre = '".$_POST['cm70_cre']."', cm71_cre = '".$_POST['cm71_cre']."', cm72_cre = '".$_POST['cm72_cre']."', cm73_cre = '".$_POST['cm73_cre']."', cm74_cre = '".$_POST['cm74_cre']."', cm75_cre = '".$_POST['cm75_cre']."', cm76_cre = '".$_POST['cm76_cre']."', cm77_cre = '".$_POST['cm77_cre']."', cm78_cre = '".$_POST['cm78_cre']."', cm79_cre = '".$_POST['cm79_cre']."', "
            . "cm80_cre = '".$_POST['cm80_cre']."', cm81_cre = '".$_POST['cm81_cre']."', cm82_cre = '".$_POST['cm82_cre']."', cm83_cre = '".$_POST['cm83_cre']."', cm84_cre = '".$_POST['cm84_cre']."', cm85_cre = '".$_POST['cm85_cre']."' "
            . "WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_cm_cre.php?source=$source&table=cm_cre&action=show");
    }
}

foot();
