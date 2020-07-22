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

$_SESSION['filter']['f_t_rejete'] = $_POST['f_t_rejete'];
$_SESSION['filter']['f_s_maree'] = $_POST['f_s_maree'];

if ($_GET['f_t_rejete'] != "") {$_SESSION['filter']['f_t_rejete'] = $_GET['f_t_rejete'];}
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
    <form method="post" action="<?php echo $self;?>?source=trawlers&table=ft_poi&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Mar&eacute;e</b></td><td><b>Rejete</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <select name="f_t_rejete">
        <option value="t_rejete" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT id, rejete FROM trawlers.t_rejete");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_rejete']) {
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
    <td><b>Mar&eacute;e et Lanc&eacute;</b></td>
    <td><b>Esp&egrave;ce</b></td>
    <td><b>Rejete</b></td>
    <td><b>Measure</b></td>
    <td><b>Poids</b></td>
    <td><b>Taille</b></td>
    </tr>

    <?php

    // fetch data

    if ($_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_t_rejete'] != "" ) {

        # id, datetime, username, id_route, maree, lance, t_rejete, id_species, t_measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi, ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, ft70_poi, ft71_poi, ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, ft109_poi, ft110_poi, ft111_poi, ft112_poi ,

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(ft_poi.id) FROM trawlers.ft_poi "
                . "WHERE t_rejete=".$_SESSION['filter']['f_t_rejete']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT ft_poi.id, datetime::date, username, id_route, maree, lance, t_rejete.rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_measure.measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi, ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, ft70_poi, ft71_poi, ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, ft109_poi, ft110_poi, ft111_poi, ft112_poi, "
            . " coalesce(similarity(trawlers.ft_poi.maree, '".$_SESSION['filter']['f_s_maree']."'),0) AS score"
            . " FROM trawlers.ft_poi "
            . "LEFT JOIN fishery.species ON trawlers.ft_poi.id_species = fishery.species.id "
            . "LEFT JOIN trawlers.t_rejete ON trawlers.ft_poi.t_rejete = trawlers.t_rejete.id "
            . "LEFT JOIN trawlers.t_measure ON trawlers.ft_poi.t_measure = trawlers.t_measure.id "
            . "WHERE (t_rejete IS NULL OR t_rejete=".$_SESSION['filter']['f_t_rejete'].") "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT count(ft_poi.id) FROM trawlers.ft_poi "
            . "WHERE t_rejete=".$_SESSION['filter']['f_t_rejete']." ";
            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT ft_poi.id, datetime::date, username, id_route, maree, lance, t_rejete.rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_measure.measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi, ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, ft70_poi, ft71_poi, ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, ft109_poi, ft110_poi, ft111_poi, ft112_poi  "
            . " FROM trawlers.ft_poi "
            . "LEFT JOIN fishery.species ON trawlers.ft_poi.id_species = fishery.species.id "
            . "LEFT JOIN trawlers.t_rejete ON trawlers.ft_poi.t_rejete = trawlers.t_rejete.id "
            . "LEFT JOIN trawlers.t_measure ON trawlers.ft_poi.t_measure = trawlers.t_measure.id "
            . "WHERE (t_rejete IS NULL OR t_rejete=".$_SESSION['filter']['f_t_rejete'].") "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(ft_poi.id) FROM trawlers.ft_poi";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT ft_poi.id, datetime::date, username, id_route, maree, lance, t_rejete.rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_measure.measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi, ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, ft70_poi, ft71_poi, ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, ft109_poi, ft110_poi, ft111_poi, ft112_poi "
        . " FROM trawlers.ft_poi "
        . "LEFT JOIN fishery.species ON trawlers.ft_poi.id_species = fishery.species.id "
        . "LEFT JOIN trawlers.t_rejete ON trawlers.ft_poi.t_rejete = trawlers.t_rejete.id "
        . "LEFT JOIN trawlers.t_measure ON trawlers.ft_poi.t_measure = trawlers.t_measure.id "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
    }

    $r_query = pg_query($query);

    #print $query;

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_trawlers_ft_poi.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_trawlers_ft_poi.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td><a href=\"./view_route.php?table=route&id=$results[3]\">$results[4]<br/>$results[5]<br/><td>".formatSpecies($results[8],$results[9],$results[10],$results[11])."</td><td>$results[6]</td>"
        . "<td>$results[12]</td><td>$results[13]</td><td><img src=\"./graph_trawlers_ft_poi.php?id=$results[0]\"></td>"
        . "</tr>";
    }
    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_trawlers_ft_poi.php?source=trawlers&table=ft_poi&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_t_rejete='.$_SESSION['filter']['f_t_rejete']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT *, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM trawlers.ft_poi"
        . " LEFT JOIN fishery.species ON fishery.species.id = trawlers.ft_poi.id_species "
        . " WHERE ft_poi.id = '$id'";

    //print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    # id, datetime, username, id_route, maree, lance, t_rejete, id_species, t_measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi, ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, ft70_poi, ft71_poi, ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, ft109_poi, ft110_poi, ft111_poi, ft112_poi ,

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
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.ft_poi ON fishery.species.id = trawlers.ft_poi.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[122]) {
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
    <select name="t_rejete">
    <?php
    $result = pg_query("SELECT id, rejete FROM trawlers.t_rejete");
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
    <b>Measure</b>
    <br/>
    <select name="t_measure">
    <?php
    $result = pg_query("SELECT id, measure FROM trawlers.t_measure");
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
    <b>Poids &eacute;chantillon</b> (kg)
    <br/>
    <input type="text" size="5" name="poids" value="<?php print $results[9]; ?>">
    <br/>
    <br/>
    <b>Numero Individu pat taille</b> (cm)
    <br/>
    <br/>

    <?php
    print '<table border="1">';
    for ($i = 0; $i < 11; $i++) {
        print '<tr align="center">';

            for ($j = 1; $j <= 10; $j++) {
                $k = $j+$i*10;
                $l = $k;
                print '<td>'.$l.'</td>';
            }

        print '<tr/><tr align="center">';

            for ($j = 1; $j <= 10; $j++) {
                $k = $j+$i*10;
                print '<td><input type="text" size="3" name="ft'.$k.'_poi" value="'.$results[$k+9].'"></td>';
            }
        print "</tr>";
    }

?>

    <tr align="center"><td>111</td><td>112</td></tr>
    <tr align="center"><td><input type="text" size="3" name="ft111_poi" value="<?php print $results[$k+10]; ?>"></td><td><input type="text" size="3" name="ft112_poi" value="<?php print $results[$k+10+1]; ?>"></td></tr>
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
    $query = "DELETE FROM trawlers.ft_poi WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_ft_poi.php?source=$source&table=ft_poi&action=show");
    }
    $controllo = 1;

}


if ($_POST['submit'] == "Enregistrer") {
    # id, datetime, username, id_route, maree, lance, t_rejete, id_species, t_measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi, ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, ft70_poi, ft71_poi, ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, ft109_poi, ft110_poi, ft111_poi, ft112_poi ,


    $maree = $_POST['maree'];
    $lance = $_POST['lance'];
    $id_species = $_POST['id_species'];
    $t_rejete = $_POST['t_rejete'];
    $t_measure = $_POST['t_measure'];
    $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);

    $q_id = "SELECT id FROM trawlers.route WHERE maree = '$maree' AND lance = '$lance'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    if ($_POST['new_old']) {
        $query = "INSERT INTO trawlers.ft_poi "
            . "(datetime, username, id_route, maree, lance, id_species, t_rejete, t_measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi, ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, ft70_poi, ft71_poi, ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, ft109_poi, ft110_poi, ft111_poi, ft112_poi) "
            . "VALUES (now(), '$username', '$id_route', '$maree', '$lance', '$id_species', '$t_rejete', '$t_measure', '$poids', '"
            .$_POST['ft1_poi']."', '".$_POST['ft2_poi']."', '".$_POST['ft3_poi']."', '".$_POST['ft4_poi']."', '".$_POST['ft5_poi']."', '".$_POST['ft6_poi']."', '".$_POST['ft7_poi']."', '".$_POST['ft8_poi']."', '".$_POST['ft9_poi']."', '"
            .$_POST['ft10_poi']."', '".$_POST['ft11_poi']."', '".$_POST['ft12_poi']."', '".$_POST['ft13_poi']."', '".$_POST['ft14_poi']."', '".$_POST['ft15_poi']."', '".$_POST['ft16_poi']."', '".$_POST['ft17_poi']."', '".$_POST['ft18_poi']."', '".$_POST['ft19_poi']."', '"
            .$_POST['ft20_poi']."', '".$_POST['ft21_poi']."', '".$_POST['ft22_poi']."', '".$_POST['ft23_poi']."', '".$_POST['ft24_poi']."', '".$_POST['ft25_poi']."', '".$_POST['ft26_poi']."', '".$_POST['ft27_poi']."', '".$_POST['ft28_poi']."', '".$_POST['ft29_poi']."', '"
            .$_POST['ft30_poi']."', '".$_POST['ft31_poi']."', '".$_POST['ft32_poi']."', '".$_POST['ft33_poi']."', '".$_POST['ft34_poi']."', '".$_POST['ft35_poi']."', '".$_POST['ft36_poi']."', '".$_POST['ft37_poi']."', '".$_POST['ft38_poi']."', '".$_POST['ft39_poi']."', '"
            .$_POST['ft40_poi']."', '".$_POST['ft41_poi']."', '".$_POST['ft42_poi']."', '".$_POST['ft43_poi']."', '".$_POST['ft44_poi']."', '".$_POST['ft45_poi']."', '".$_POST['ft46_poi']."', '".$_POST['ft47_poi']."', '".$_POST['ft48_poi']."', '".$_POST['ft49_poi']."', '"
            .$_POST['ft50_poi']."', '".$_POST['ft51_poi']."', '".$_POST['ft52_poi']."', '".$_POST['ft53_poi']."', '".$_POST['ft54_poi']."', '".$_POST['ft55_poi']."', '".$_POST['ft56_poi']."', '".$_POST['ft57_poi']."', '".$_POST['ft58_poi']."', '".$_POST['ft59_poi']."', '"
            .$_POST['ft60_poi']."', '".$_POST['ft61_poi']."', '".$_POST['ft62_poi']."', '".$_POST['ft63_poi']."', '".$_POST['ft64_poi']."', '".$_POST['ft65_poi']."', '".$_POST['ft66_poi']."', '".$_POST['ft67_poi']."', '".$_POST['ft68_poi']."', '".$_POST['ft69_poi']."', '"
            .$_POST['ft70_poi']."', '".$_POST['ft71_poi']."', '".$_POST['ft72_poi']."', '".$_POST['ft73_poi']."', '".$_POST['ft74_poi']."', '".$_POST['ft75_poi']."', '".$_POST['ft76_poi']."', '".$_POST['ft77_poi']."', '".$_POST['ft78_poi']."', '".$_POST['ft79_poi']."', '"
            .$_POST['ft80_poi']."', '".$_POST['ft81_poi']."', '".$_POST['ft82_poi']."', '".$_POST['ft83_poi']."', '".$_POST['ft84_poi']."', '".$_POST['ft85_poi']."', '".$_POST['ft86_poi']."', '".$_POST['ft87_poi']."', '".$_POST['ft88_poi']."', '".$_POST['ft89_poi']."', '"
            .$_POST['ft90_poi']."', '".$_POST['ft91_poi']."', '".$_POST['ft92_poi']."', '".$_POST['ft93_poi']."', '".$_POST['ft94_poi']."', '".$_POST['ft95_poi']."', '".$_POST['ft96_poi']."', '".$_POST['ft97_poi']."', '".$_POST['ft98_poi']."', '".$_POST['ft99_poi']."', '"
            .$_POST['ft100_poi']."', '".$_POST['ft101_poi']."', '".$_POST['ft102_poi']."', '".$_POST['ft103_poi']."', '".$_POST['ft104_poi']."', '".$_POST['ft105_poi']."', '".$_POST['ft106_poi']."', '".$_POST['ft107_poi']."', '".$_POST['ft108_poi']."', '".$_POST['ft109_poi']."', '"
            .$_POST['ft110_poi']."', '".$_POST['ft111_poi']."', '".$_POST['ft112_poi']."')";


    } else {
        $query = "UPDATE trawlers.ft_poi SET "
            . "username = '$username', datetime = now(), id_route = '".$id_route."', "
            . "maree = '".$maree."', lance = '".$lance."', id_species = '".$id_species."', t_rejete = '".$t_rejete."', t_measure = '".$t_measure."', poids = '".$poids."', "
            . "ft1_poi = '".$_POST['ft1_poi']."', ft2_poi = '".$_POST['ft2_poi']."', ft3_poi = '".$_POST['ft3_poi']."', ft4_poi  = '".$_POST['ft4_poi']."', ft5_poi  = '".$_POST['ft5_poi']."', ft6_poi  = '".$_POST['ft6_poi']."', ft7_poi  = '".$_POST['ft7_poi']."', ft8_poi  = '".$_POST['ft8_poi']."', ft9_poi  = '".$_POST['ft9_poi']."', "
            . "ft10_poi = '".$_POST['ft10_poi']."', ft11_poi = '".$_POST['ft11_poi']."', ft12_poi = '".$_POST['ft12_poi']."', ft13_poi  = '".$_POST['ft13_poi']."', ft14_poi  = '".$_POST['ft14_poi']."', ft15_poi  = '".$_POST['ft15_poi']."', ft16_poi  = '".$_POST['ft16_poi']."', ft17_poi  = '".$_POST['ft17_poi']."', ft18_poi  = '".$_POST['ft18_poi']."', ft19_poi  = '".$_POST['ft19_poi']."', "
            . "ft20_poi = '".$_POST['ft20_poi']."', ft21_poi = '".$_POST['ft21_poi']."', ft22_poi = '".$_POST['ft22_poi']."', ft23_poi = '".$_POST['ft23_poi']."', ft24_poi = '".$_POST['ft24_poi']."', ft25_poi = '".$_POST['ft25_poi']."', ft26_poi = '".$_POST['ft26_poi']."', ft27_poi = '".$_POST['ft27_poi']."', ft28_poi = '".$_POST['ft28_poi']."', ft29_poi = '".$_POST['ft29_poi']."', "
            . "ft30_poi = '".$_POST['ft30_poi']."', ft31_poi = '".$_POST['ft31_poi']."', ft32_poi = '".$_POST['ft32_poi']."', ft33_poi = '".$_POST['ft33_poi']."', ft34_poi = '".$_POST['ft34_poi']."', ft35_poi = '".$_POST['ft35_poi']."', ft36_poi = '".$_POST['ft36_poi']."', ft37_poi = '".$_POST['ft37_poi']."', ft38_poi = '".$_POST['ft38_poi']."', ft39_poi = '".$_POST['ft39_poi']."', "
            . "ft40_poi = '".$_POST['ft40_poi']."', ft41_poi = '".$_POST['ft41_poi']."', ft42_poi = '".$_POST['ft42_poi']."', ft43_poi = '".$_POST['ft43_poi']."', ft44_poi = '".$_POST['ft44_poi']."', ft45_poi = '".$_POST['ft45_poi']."', ft46_poi = '".$_POST['ft46_poi']."', ft47_poi = '".$_POST['ft47_poi']."', ft48_poi = '".$_POST['ft48_poi']."', ft49_poi = '".$_POST['ft49_poi']."', "
            . "ft50_poi = '".$_POST['ft50_poi']."', ft51_poi = '".$_POST['ft51_poi']."', ft52_poi = '".$_POST['ft52_poi']."', ft53_poi = '".$_POST['ft53_poi']."', ft54_poi = '".$_POST['ft54_poi']."', ft55_poi = '".$_POST['ft55_poi']."', ft56_poi = '".$_POST['ft56_poi']."', ft57_poi = '".$_POST['ft57_poi']."', ft58_poi = '".$_POST['ft58_poi']."', ft59_poi = '".$_POST['ft59_poi']."', "
            . "ft60_poi = '".$_POST['ft60_poi']."', ft61_poi = '".$_POST['ft61_poi']."', ft62_poi = '".$_POST['ft62_poi']."', ft63_poi = '".$_POST['ft63_poi']."', ft64_poi = '".$_POST['ft64_poi']."', ft65_poi = '".$_POST['ft65_poi']."', ft66_poi = '".$_POST['ft66_poi']."', ft67_poi = '".$_POST['ft67_poi']."', ft68_poi = '".$_POST['ft68_poi']."', ft69_poi = '".$_POST['ft69_poi']."', "
            . "ft70_poi = '".$_POST['ft70_poi']."', ft71_poi = '".$_POST['ft71_poi']."', ft72_poi = '".$_POST['ft72_poi']."', ft73_poi = '".$_POST['ft73_poi']."', ft74_poi = '".$_POST['ft74_poi']."', ft75_poi = '".$_POST['ft75_poi']."', ft76_poi = '".$_POST['ft76_poi']."', ft77_poi = '".$_POST['ft77_poi']."', ft78_poi = '".$_POST['ft78_poi']."', ft79_poi = '".$_POST['ft79_poi']."', "
            . "ft80_poi = '".$_POST['ft80_poi']."', ft81_poi = '".$_POST['ft81_poi']."', ft82_poi = '".$_POST['ft82_poi']."', ft83_poi = '".$_POST['ft83_poi']."', ft84_poi = '".$_POST['ft84_poi']."', ft85_poi = '".$_POST['ft85_poi']."', ft86_poi = '".$_POST['ft86_poi']."', ft87_poi = '".$_POST['ft87_poi']."', ft88_poi = '".$_POST['ft88_poi']."', ft89_poi = '".$_POST['ft89_poi']."', "
            . "ft90_poi = '".$_POST['ft90_poi']."', ft91_poi = '".$_POST['ft91_poi']."', ft92_poi = '".$_POST['ft92_poi']."', ft93_poi = '".$_POST['ft93_poi']."', ft94_poi = '".$_POST['ft94_poi']."', ft95_poi = '".$_POST['ft95_poi']."', ft96_poi = '".$_POST['ft96_poi']."', ft97_poi = '".$_POST['ft97_poi']."', ft98_poi = '".$_POST['ft98_poi']."', ft99_poi = '".$_POST['ft99_poi']."', "
            . "ft100_poi = '".$_POST['ft100_poi']."', ft101_poi = '".$_POST['ft101_poi']."', ft102_poi = '".$_POST['ft102_poi']."', ft103_poi = '".$_POST['ft103_poi']."', ft104_poi = '".$_POST['ft104_poi']."', ft105_poi = '".$_POST['ft105_poi']."', ft106_poi = '".$_POST['ft106_poi']."', ft107_poi = '".$_POST['ft107_poi']."', ft108_poi = '".$_POST['ft108_poi']."', ft109_poi = '".$_POST['ft109_poi']."', "
            . "ft110_poi = '".$_POST['ft110_poi']."', ft111_poi = '".$_POST['ft111_poi']."', ft112_poi = '".$_POST['ft112_poi']."' "
            . "WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_ft_poi.php?source=$source&table=ft_poi&action=show");
    }
}

foot();
