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

$_SESSION['filter']['f_t_sex'] = $_POST['f_t_sex'];
$_SESSION['filter']['f_s_maree'] = $_POST['f_s_maree'];

if ($_GET['f_t_sex'] != "") {$_SESSION['filter']['f_t_sex'] = $_GET['f_t_sex'];}
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
    <form method="post" action="<?php echo $self;?>?source=trawlers&table=ft_cre&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Maree</b></td><td><b>Sexe</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <select name="f_t_sex">
        <option value="t_sex" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT id, sex FROM trawlers.t_sex");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_sex']) {
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
    <td><b>Maree et Lanc&eacute;</b></td>
    <td><b>Espece</b></td>
    <td><b>Sexe</b></td>
    <td><b>Maturit&eacute;</b></td>
    <td><b>Poids</b></td>
    <td><b>Taille</b></td>
    </tr>

    <?php

    // fetch data

    if ($_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_t_sex'] != "") {

        #id, datetime, username, id_route, maree, lance, id_species, t_sex, t_maturity, poids, ft10_cre, ft11_cre, ft12_cre, ft13_cre, ft14_cre, ft15_cre, ft16_cre, ft17_cre, ft18_cre, ft19_cre, ft20_cre, ft21_cre, ft22_cre, ft23_cre, ft24_cre, ft25_cre, ft26_cre, ft27_cre, ft28_cre, ft29_cre, ft30_cre, ft31_cre, ft32_cre, ft33_cre, ft34_cre, ft35_cre, ft36_cre, ft37_cre, ft38_cre, ft39_cre, ft40_cre, ft41_cre, ft42_cre, ft43_cre, ft44_cre, ft45_cre, ft46_cre, ft47_cre, ft48_cre, ft49_cre, ft50_cre, ft51_cre, ft52_cre, ft53_cre, ft54_cre, ft55_cre, ft56_cre, ft57_cre, ft58_cre, ft59_cre, ft60_cre, ft61_cre, ft62_cre, ft63_cre, ft64_cre, ft65_cre, ft66_cre, ft67_cre, ft68_cre, ft69_cre, ft70_cre ,

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(ft_cre.id) FROM trawlers.ft_cre "
                . "WHERE t_sex=".$_SESSION['filter']['f_t_sex']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT ft_cre.id, datetime::date, username, id_route, maree, lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_sex.sex, t_maturity.maturity, poids, ft10_cre, ft11_cre, ft12_cre, ft13_cre, ft14_cre, ft15_cre, ft16_cre, ft17_cre, ft18_cre, ft19_cre, ft20_cre, ft21_cre, ft22_cre, ft23_cre, ft24_cre, ft25_cre, ft26_cre, ft27_cre, ft28_cre, ft29_cre, ft30_cre, ft31_cre, ft32_cre, ft33_cre, ft34_cre, ft35_cre, ft36_cre, ft37_cre, ft38_cre, ft39_cre, ft40_cre, ft41_cre, ft42_cre, ft43_cre, ft44_cre, ft45_cre, ft46_cre, ft47_cre, ft48_cre, ft49_cre, ft50_cre, ft51_cre, ft52_cre, ft53_cre, ft54_cre, ft55_cre, ft56_cre, ft57_cre, ft58_cre, ft59_cre, ft60_cre, ft61_cre, ft62_cre, ft63_cre, ft64_cre, ft65_cre, ft66_cre, ft67_cre, ft68_cre, ft69_cre, ft70_cre, "
            . " coalesce(similarity(trawlers.ft_cre.maree, '".$_SESSION['filter']['f_s_maree']."'),0) AS score"
            . " FROM trawlers.ft_cre "
            . "LEFT JOIN fishery.species ON trawlers.ft_cre.id_species = fishery.species.id "
            . "LEFT JOIN trawlers.t_sex ON trawlers.ft_cre.t_sex = trawlers.t_sex.id "
            . "LEFT JOIN trawlers.t_maturity ON trawlers.ft_cre.t_maturity = trawlers.t_maturity.id "
            . "WHERE t_sex=".$_SESSION['filter']['f_t_sex']." "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT count(ft_cre.id) FROM trawlers.ft_cre "
            . "WHERE t_sex=".$_SESSION['filter']['f_t_sex']." ";
            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT ft_cre.id, datetime::date, username, id_route, maree, lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_sex.sex, t_maturity.maturity, poids, ft10_cre, ft11_cre, ft12_cre, ft13_cre, ft14_cre, ft15_cre, ft16_cre, ft17_cre, ft18_cre, ft19_cre, ft20_cre, ft21_cre, ft22_cre, ft23_cre, ft24_cre, ft25_cre, ft26_cre, ft27_cre, ft28_cre, ft29_cre, ft30_cre, ft31_cre, ft32_cre, ft33_cre, ft34_cre, ft35_cre, ft36_cre, ft37_cre, ft38_cre, ft39_cre, ft40_cre, ft41_cre, ft42_cre, ft43_cre, ft44_cre, ft45_cre, ft46_cre, ft47_cre, ft48_cre, ft49_cre, ft50_cre, ft51_cre, ft52_cre, ft53_cre, ft54_cre, ft55_cre, ft56_cre, ft57_cre, ft58_cre, ft59_cre, ft60_cre, ft61_cre, ft62_cre, ft63_cre, ft64_cre, ft65_cre, ft66_cre, ft67_cre, ft68_cre, ft69_cre, ft70_cre  "
            . " FROM trawlers.ft_cre "
            . "LEFT JOIN fishery.species ON trawlers.ft_cre.id_species = fishery.species.id "
            . "LEFT JOIN trawlers.t_sex ON trawlers.ft_cre.t_sex = trawlers.t_sex.id "
            . "LEFT JOIN trawlers.t_maturity ON trawlers.ft_cre.t_maturity = trawlers.t_maturity.id "
            . "WHERE t_sex=".$_SESSION['filter']['f_t_sex']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(ft_cre.id) FROM trawlers.ft_cre";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT ft_cre.id, datetime::date, username, id_route, maree, lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_sex.sex, t_maturity.maturity, poids, ft10_cre, ft11_cre, ft12_cre, ft13_cre, ft14_cre, ft15_cre, ft16_cre, ft17_cre, ft18_cre, ft19_cre, ft20_cre, ft21_cre, ft22_cre, ft23_cre, ft24_cre, ft25_cre, ft26_cre, ft27_cre, ft28_cre, ft29_cre, ft30_cre, ft31_cre, ft32_cre, ft33_cre, ft34_cre, ft35_cre, ft36_cre, ft37_cre, ft38_cre, ft39_cre, ft40_cre, ft41_cre, ft42_cre, ft43_cre, ft44_cre, ft45_cre, ft46_cre, ft47_cre, ft48_cre, ft49_cre, ft50_cre, ft51_cre, ft52_cre, ft53_cre, ft54_cre, ft55_cre, ft56_cre, ft57_cre, ft58_cre, ft59_cre, ft60_cre, ft61_cre, ft62_cre, ft63_cre, ft64_cre, ft65_cre, ft66_cre, ft67_cre, ft68_cre, ft69_cre, ft70_cre "
        . " FROM trawlers.ft_cre "
        . "LEFT JOIN fishery.species ON trawlers.ft_cre.id_species = fishery.species.id "
        . "LEFT JOIN trawlers.t_sex ON trawlers.ft_cre.t_sex = trawlers.t_sex.id "
        . "LEFT JOIN trawlers.t_maturity ON trawlers.ft_cre.t_maturity = trawlers.t_maturity.id "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
    }

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_trawlers_ft_cre.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_trawlers_ft_cre.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td><a href=\"./view_route.php?table=route&id=$results[3]\">$results[4]<br/>$results[5]<br/><td>".formatSpecies($results[7],$results[8],$results[9],$results[10])."</td><td>$results[11]</td><td>$results[12]</td>"
        . "<td>$results[13]</td><td><img src=\"./graph_trawlers_ft_cre.php?id=$results[0]\"></td>"
        . "</tr>";
    }
    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_trawlers_ft_cre.php?source=trawlers&table=ft_cre&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_t_sex='.$_SESSION['filter']['f_t_sex']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID

    $q_id = "SELECT *, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM trawlers.ft_cre"
        . " LEFT JOIN fishery.species ON fishery.species.id = trawlers.ft_cre.id_species "
        . " WHERE ft_cre.id = '$id'";

    #print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    #id, datetime, username, id_route, maree, lance, id_species, t_sex, t_maturity, poids, ft10_cre, ft11_cre, ft12_cre, ft13_cre, ft14_cre, ft15_cre, ft16_cre, ft17_cre, ft18_cre, ft19_cre, ft20_cre, ft21_cre, ft22_cre, ft23_cre, ft24_cre, ft25_cre, ft26_cre, ft27_cre, ft28_cre, ft29_cre, ft30_cre, ft31_cre, ft32_cre, ft33_cre, ft34_cre, ft35_cre, ft36_cre, ft37_cre, ft38_cre, ft39_cre, ft40_cre, ft41_cre, ft42_cre, ft43_cre, ft44_cre, ft45_cre, ft46_cre, ft47_cre, ft48_cre, ft49_cre, ft50_cre, ft51_cre, ft52_cre, ft53_cre, ft54_cre, ft55_cre, ft56_cre, ft57_cre, ft58_cre, ft59_cre, ft60_cre, ft61_cre, ft62_cre, ft63_cre, ft64_cre, ft65_cre, ft66_cre, ft67_cre, ft68_cre, ft69_cre, ft70_cre ,

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Maree</b>
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
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.ft_cre ON fishery.species.id = trawlers.ft_cre.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[119]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Sexe</b>
    <br/>
    <select name="t_sex">
    <option value="none">Indetermine</option>
    <?php
    $result = pg_query("SELECT id, sex FROM trawlers.t_sex");
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
    <b>Maturite</b>
    <br/>
    <select name="t_maturity">
    <option value="none">Indetermine</option>
    <?php
    $result = pg_query("SELECT id, maturity FROM trawlers.t_maturity");
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
    <b>Numero Individu par taille</b> (mm)
    <br/>
    <br/>
        <table border="1">
        <tr align="center">
            <td>1</td>
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
            <td><input type="text" size="3" name="ft1_cre" value="<?php print $results[10]; ?>"></td>
            <td><input type="text" size="3" name="ft2_cre" value="<?php print $results[11]; ?>"></td>
            <td><input type="text" size="3" name="ft3_cre" value="<?php print $results[12]; ?>"></td>
            <td><input type="text" size="3" name="ft4_cre" value="<?php print $results[13]; ?>"></td>
            <td><input type="text" size="3" name="ft5_cre" value="<?php print $results[14]; ?>"></td>
            <td><input type="text" size="3" name="ft6_cre" value="<?php print $results[15]; ?>"></td>
            <td><input type="text" size="3" name="ft7_cre" value="<?php print $results[16]; ?>"></td>
            <td><input type="text" size="3" name="ft8_cre" value="<?php print $results[17]; ?>"></td>
            <td><input type="text" size="3" name="ft9_cre" value="<?php print $results[18]; ?>"></td>
        </tr>

    <?php
        for ($i = 1; $i < 10; $i++) {
          print '<tr/><tr align="center">';

                for ($j = 0; $j < 10; $j++) {
                    $k = $j+$i*10;
                    $l = $k;
                    print '<td>'.$l.'</td>';
                }

            print '<tr/><tr align="center">';

                for ($j = 0; $j < 10; $j++) {
                    $k = $j+$i*10;
                    print '<td><input type="text" size="3" name="ft'.$k.'_cre" value="'.$results[$k+9].'"></td>';
                }
            print "</tr>";
        }
    ?>

    <tr align="center"><td>100</td></tr><tr align="center"><td><input type="text" size="3" name="ft100_cre" value="<?php print $results[$k+10]; ?>"></td></tr></table>

    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM trawlers.ft_cre WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_ft_cre.php?source=$source&table=ft_cre&action=show");
    }
    $controllo = 1;

}


if ($_POST['submit'] == "Enregistrer") {
    # id, datetime, username, id_route, maree, lance, id_species, t_sex, t_maturity, poids, ft10_cre, ft11_cre, ft12_cre, ft13_cre, ft14_cre, ft15_cre, ft16_cre, ft17_cre, ft18_cre, ft19_cre, ft20_cre, ft21_cre, ft22_cre, ft23_cre, ft24_cre, ft25_cre, ft26_cre, ft27_cre, ft28_cre, ft29_cre, ft30_cre, ft31_cre, ft32_cre, ft33_cre, ft34_cre, ft35_cre, ft36_cre, ft37_cre, ft38_cre, ft39_cre, ft40_cre, ft41_cre, ft42_cre, ft43_cre, ft44_cre, ft45_cre, ft46_cre, ft47_cre, ft48_cre, ft49_cre, ft50_cre, ft51_cre, ft52_cre, ft53_cre, ft54_cre, ft55_cre, ft56_cre, ft57_cre, ft58_cre, ft59_cre, ft60_cre, ft61_cre, ft62_cre, ft63_cre, ft64_cre, ft65_cre, ft66_cre, ft67_cre, ft68_cre, ft69_cre, ft70_cre ,


    $maree = $_POST['maree'];
    $lance = $_POST['lance'];
    $id_species = $_POST['id_species'];
    $t_sex = $_POST['t_sex'];
    $t_maturity = $_POST['t_maturity'];
    $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);

    $q_id = "SELECT id FROM trawlers.route WHERE maree = '$maree' AND lance = '$lance'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    if ($_POST['new_old']) {
        $query = "INSERT INTO trawlers.ft_cre "
            . "(datetime, username, id_route, maree, lance, id_species, t_sex, t_maturity, poids, "
            . "ft1_cre, ft2_cre, ft3_cre, ft4_cre, ft5_cre, ft6_cre, ft7_cre, ft8_cre, ft9_cre, "
            . "ft10_cre, ft11_cre, ft12_cre, ft13_cre, ft14_cre, ft15_cre, ft16_cre, ft17_cre, ft18_cre, ft19_cre, "
            . "ft20_cre, ft21_cre, ft22_cre, ft23_cre, ft24_cre, ft25_cre, ft26_cre, ft27_cre, ft28_cre, ft29_cre, "
            . "ft30_cre, ft31_cre, ft32_cre, ft33_cre, ft34_cre, ft35_cre, ft36_cre, ft37_cre, ft38_cre, ft39_cre, "
            . "ft40_cre, ft41_cre, ft42_cre, ft43_cre, ft44_cre, ft45_cre, ft46_cre, ft47_cre, ft48_cre, ft49_cre, "
            . "ft50_cre, ft51_cre, ft52_cre, ft53_cre, ft54_cre, ft55_cre, ft56_cre, ft57_cre, ft58_cre, ft59_cre, "
            . "ft60_cre, ft61_cre, ft62_cre, ft63_cre, ft64_cre, ft65_cre, ft66_cre, ft67_cre, ft68_cre, ft69_cre, "
            . "ft70_cre, ft71_cre, ft72_cre, ft73_cre, ft74_cre, ft75_cre, ft76_cre, ft77_cre, ft78_cre, ft79_cre, "
            . "ft80_cre, ft81_cre, ft82_cre, ft83_cre, ft84_cre, ft85_cre, ft86_cre, ft87_cre, ft88_cre, ft89_cre, "
            . "ft90_cre, ft91_cre, ft92_cre, ft93_cre, ft94_cre, ft95_cre, ft96_cre, ft97_cre, ft98_cre, ft99_cre, "
            . "ft100_cre) "
            . "VALUES (now(), '$username', '$id_route', '$maree', '$lance', '$id_species', '$t_sex', '$t_maturity', '$poids', '"
            .$_POST['ft1_cre']."', '".$_POST['ft2_cre']."', '".$_POST['ft3_cre']."', '".$_POST['ft4_cre']."', '".$_POST['ft5_cre']."', '".$_POST['ft6_cre']."', '".$_POST['ft7_cre']."', '".$_POST['ft8_cre']."', '".$_POST['ft9_cre']."', '"
            .$_POST['ft10_cre']."', '".$_POST['ft11_cre']."', '".$_POST['ft12_cre']."', '".$_POST['ft13_cre']."', '".$_POST['ft14_cre']."', '".$_POST['ft15_cre']."', '".$_POST['ft16_cre']."', '".$_POST['ft17_cre']."', '".$_POST['ft18_cre']."', '".$_POST['ft19_cre']."', '"
            .$_POST['ft20_cre']."', '".$_POST['ft21_cre']."', '".$_POST['ft22_cre']."', '".$_POST['ft23_cre']."', '".$_POST['ft24_cre']."', '".$_POST['ft25_cre']."', '".$_POST['ft26_cre']."', '".$_POST['ft27_cre']."', '".$_POST['ft28_cre']."', '".$_POST['ft29_cre']."', '"
            .$_POST['ft30_cre']."', '".$_POST['ft31_cre']."', '".$_POST['ft32_cre']."', '".$_POST['ft33_cre']."', '".$_POST['ft34_cre']."', '".$_POST['ft35_cre']."', '".$_POST['ft36_cre']."', '".$_POST['ft37_cre']."', '".$_POST['ft38_cre']."', '".$_POST['ft39_cre']."', '"
            .$_POST['ft40_cre']."', '".$_POST['ft41_cre']."', '".$_POST['ft42_cre']."', '".$_POST['ft43_cre']."', '".$_POST['ft44_cre']."', '".$_POST['ft45_cre']."', '".$_POST['ft46_cre']."', '".$_POST['ft47_cre']."', '".$_POST['ft48_cre']."', '".$_POST['ft49_cre']."', '"
            .$_POST['ft50_cre']."', '".$_POST['ft51_cre']."', '".$_POST['ft52_cre']."', '".$_POST['ft53_cre']."', '".$_POST['ft54_cre']."', '".$_POST['ft55_cre']."', '".$_POST['ft56_cre']."', '".$_POST['ft57_cre']."', '".$_POST['ft58_cre']."', '".$_POST['ft59_cre']."', '"
            .$_POST['ft60_cre']."', '".$_POST['ft61_cre']."', '".$_POST['ft62_cre']."', '".$_POST['ft63_cre']."', '".$_POST['ft64_cre']."', '".$_POST['ft65_cre']."', '".$_POST['ft66_cre']."', '".$_POST['ft67_cre']."', '".$_POST['ft68_cre']."', '".$_POST['ft69_cre']."', '"
            .$_POST['ft70_cre']."', '".$_POST['ft71_cre']."', '".$_POST['ft72_cre']."', '".$_POST['ft73_cre']."', '".$_POST['ft74_cre']."', '".$_POST['ft75_cre']."', '".$_POST['ft76_cre']."', '".$_POST['ft77_cre']."', '".$_POST['ft78_cre']."', '".$_POST['ft79_cre']."', '"
            .$_POST['ft80_cre']."', '".$_POST['ft81_cre']."', '".$_POST['ft82_cre']."', '".$_POST['ft83_cre']."', '".$_POST['ft84_cre']."', '".$_POST['ft85_cre']."', '".$_POST['ft86_cre']."', '".$_POST['ft87_cre']."', '".$_POST['ft88_cre']."', '".$_POST['ft89_cre']."', '"
            .$_POST['ft90_cre']."', '".$_POST['ft91_cre']."', '".$_POST['ft92_cre']."', '".$_POST['ft93_cre']."', '".$_POST['ft94_cre']."', '".$_POST['ft95_cre']."', '".$_POST['ft96_cre']."', '".$_POST['ft97_cre']."', '".$_POST['ft98_cre']."', '".$_POST['ft99_cre']."', '"
            .$_POST['ft100_cre']."')";


    } else {
        $query = "UPDATE trawlers.ft_cre SET "
            . "username = '$username', datetime = now(), id_route = '".$id_route."', "
            . "maree = '".$maree."', lance = '".$lance."', id_species = '".$id_species."', t_sex = '".$t_sex."', t_maturity = '".$t_maturity."', poids = '".$poids."', "
            . "ft1_cre = '".$_POST['ft1_cre']."', ft2_cre = '".$_POST['ft2_cre']."', ft3_cre  = '".$_POST['ft3_cre']."', ft4_cre  = '".$_POST['ft4_cre']."', ft5_cre  = '".$_POST['ft5_cre']."', ft6_cre  = '".$_POST['ft6_cre']."', ft7_cre  = '".$_POST['ft7_cre']."', ft8_cre  = '".$_POST['ft8_cre']."', ft9_cre  = '".$_POST['ft9_cre']."', "
            . "ft10_cre = '".$_POST['ft10_cre']."', ft11_cre = '".$_POST['ft11_cre']."', ft12_cre = '".$_POST['ft12_cre']."', ft13_cre  = '".$_POST['ft13_cre']."', ft14_cre  = '".$_POST['ft14_cre']."', ft15_cre  = '".$_POST['ft15_cre']."', ft16_cre  = '".$_POST['ft16_cre']."', ft17_cre  = '".$_POST['ft17_cre']."', ft18_cre  = '".$_POST['ft18_cre']."', ft19_cre  = '".$_POST['ft19_cre']."', "
            . "ft20_cre = '".$_POST['ft20_cre']."', ft21_cre = '".$_POST['ft21_cre']."', ft22_cre = '".$_POST['ft22_cre']."', ft23_cre = '".$_POST['ft23_cre']."', ft24_cre = '".$_POST['ft24_cre']."', ft25_cre = '".$_POST['ft25_cre']."', ft26_cre = '".$_POST['ft26_cre']."', ft27_cre = '".$_POST['ft27_cre']."', ft28_cre = '".$_POST['ft28_cre']."', ft29_cre = '".$_POST['ft29_cre']."', "
            . "ft30_cre = '".$_POST['ft30_cre']."', ft31_cre = '".$_POST['ft31_cre']."', ft32_cre = '".$_POST['ft32_cre']."', ft33_cre = '".$_POST['ft33_cre']."', ft34_cre = '".$_POST['ft34_cre']."', ft35_cre = '".$_POST['ft35_cre']."', ft36_cre = '".$_POST['ft36_cre']."', ft37_cre = '".$_POST['ft37_cre']."', ft38_cre = '".$_POST['ft38_cre']."', ft39_cre = '".$_POST['ft39_cre']."', "
            . "ft40_cre = '".$_POST['ft40_cre']."', ft41_cre = '".$_POST['ft41_cre']."', ft42_cre = '".$_POST['ft42_cre']."', ft43_cre = '".$_POST['ft43_cre']."', ft44_cre = '".$_POST['ft44_cre']."', ft45_cre = '".$_POST['ft45_cre']."', ft46_cre = '".$_POST['ft46_cre']."', ft47_cre = '".$_POST['ft47_cre']."', ft48_cre = '".$_POST['ft48_cre']."', ft49_cre = '".$_POST['ft49_cre']."', "
            . "ft50_cre = '".$_POST['ft50_cre']."', ft51_cre = '".$_POST['ft51_cre']."', ft52_cre = '".$_POST['ft52_cre']."', ft53_cre = '".$_POST['ft53_cre']."', ft54_cre = '".$_POST['ft54_cre']."', ft55_cre = '".$_POST['ft55_cre']."', ft56_cre = '".$_POST['ft56_cre']."', ft57_cre = '".$_POST['ft57_cre']."', ft58_cre = '".$_POST['ft58_cre']."', ft59_cre = '".$_POST['ft59_cre']."', "
            . "ft60_cre = '".$_POST['ft60_cre']."', ft61_cre = '".$_POST['ft61_cre']."', ft62_cre = '".$_POST['ft62_cre']."', ft63_cre = '".$_POST['ft63_cre']."', ft64_cre = '".$_POST['ft64_cre']."', ft65_cre = '".$_POST['ft65_cre']."', ft66_cre = '".$_POST['ft66_cre']."', ft67_cre = '".$_POST['ft67_cre']."', ft68_cre = '".$_POST['ft68_cre']."', ft69_cre = '".$_POST['ft69_cre']."', "
            . "ft70_cre = '".$_POST['ft70_cre']."', ft71_cre = '".$_POST['ft71_cre']."', ft72_cre = '".$_POST['ft72_cre']."', ft73_cre = '".$_POST['ft73_cre']."', ft74_cre = '".$_POST['ft74_cre']."', ft75_cre = '".$_POST['ft75_cre']."', ft76_cre = '".$_POST['ft76_cre']."', ft77_cre = '".$_POST['ft77_cre']."', ft78_cre = '".$_POST['ft78_cre']."', ft79_cre = '".$_POST['ft79_cre']."', "
            . "ft80_cre = '".$_POST['ft80_cre']."', ft81_cre = '".$_POST['ft81_cre']."', ft82_cre = '".$_POST['ft82_cre']."', ft83_cre = '".$_POST['ft83_cre']."', ft84_cre = '".$_POST['ft84_cre']."', ft85_cre = '".$_POST['ft85_cre']."', ft86_cre = '".$_POST['ft86_cre']."', ft87_cre = '".$_POST['ft87_cre']."', ft88_cre = '".$_POST['ft88_cre']."', ft89_cre = '".$_POST['ft89_cre']."', "
            . "ft90_cre = '".$_POST['ft90_cre']."', ft91_cre = '".$_POST['ft91_cre']."', ft92_cre = '".$_POST['ft92_cre']."', ft93_cre = '".$_POST['ft93_cre']."', ft94_cre = '".$_POST['ft94_cre']."', ft95_cre = '".$_POST['ft95_cre']."', ft96_cre = '".$_POST['ft96_cre']."', ft97_cre = '".$_POST['ft97_cre']."', ft98_cre = '".$_POST['ft98_cre']."', ft99_cre = '".$_POST['ft99_cre']."', "
            . "ft100_cre = '".$_POST['ft100_cre']."' "
            . "WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_ft_cre.php?source=$source&table=ft_cre&action=show");
    }
}

foot();
