<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'maintenance';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_year'] = $_POST['f_year'];
$_SESSION['filter']['s_immatriculation'] = htmlspecialchars($_POST['s_immatriculation'], ENT_QUOTES);
$_SESSION['filter']['s_pir_name'] = htmlspecialchars($_POST['s_pir_name'], ENT_QUOTES);
$_SESSION['filter']['f_t_coop'] = $_POST['f_t_coop'];
$_SESSION['filter']['f_t_license'] = $_POST['f_t_license'];
$_SESSION['filter']['f_t_gear'] = $_POST['f_t_gear'];
$_SESSION['filter']['f_active'] = $_POST['f_active'];
$_SESSION['filter']['f_old'] = $_POST['f_old'];

if ($_GET['f_year'] != "") {$_SESSION['filter']['f_year'] = $_GET['f_year'];}
if ($_GET['s_immatriculation'] != "") {$_SESSION['filter']['s_immatriculation'] = $_GET['s_immatriculation'];}
if ($_GET['s_pir_name'] != "") {$_SESSION['filter']['s_pir_name'] = $_GET['s_pir_name'];}
if ($_GET['f_t_coop'] != "") {$_SESSION['filter']['f_t_coop'] = $_GET['f_t_coop'];}
if ($_GET['f_t_license'] != "") {$_SESSION['filter']['f_t_license'] = $_GET['f_t_license'];}
if ($_GET['f_t_gear'] != "") {$_SESSION['filter']['f_t_gear'] = $_GET['f_t_gear'];}
if ($_GET['f_active'] != "") {$_SESSION['filter']['f_active'] = $_GET['f_active'];}
if ($_GET['f_old'] != "") {$_SESSION['filter']['f_old'] = $_GET['f_old'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $start = $_GET['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=license&table=licenses&action=show" enctype="multipart/form-data">
    <fieldset>
    <table id="no-border"><tr><td><b>Ann&eacute;e validit&eacute;</b></td><td><b>Immatriculation pirogue</b></td><td><b>Nom pirogue</b></td><td><b>Cooperative</b></td><td><b>Esp&egrave;ces cibles</b></td><td><b>Engin</b></td><td><b>Autorisations actif</b></td></tr>
    <tr>
    <td>
    <select name="f_year">
        <option value="extract(year from date_v)" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT extract(year from date_v) FROM artisanal.license WHERE date_v IS NOT NULL ORDER BY extract(year from date_v)");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_year']) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[0]."</option>";
            }
        }
    ?>
    </select>
    </td>
    <td>
    <input type="text" size="10" name="s_immatriculation" value="<?php echo $_SESSION['filter']['s_immatriculation']?>"/>
    </td>
    <td>
    <input type="text" size="20" name="s_pir_name" value="<?php echo $_SESSION['filter']['s_pir_name']?>"/>
    </td>
    <td>
    <select name="f_t_coop" class="chosen-select">
        <option value="license.t_coop" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT id, coop FROM artisanal.t_coop ORDER BY coop");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_coop']) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
        }
    ?>
    </select>
    </td>
    <td>
    <select name="f_t_license">
        <option value="t_license" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT id, license FROM artisanal.t_license ORDER BY license");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_license']) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
        }
    ?>
    </select>
    </td>
    <td>
    <select name="f_t_gear">
    <option value="t_gear" selected="selected">Tous</option>
    <?php
    $result = pg_query("SELECT id, gear FROM artisanal.t_gear");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $_SESSION['filter']['f_t_gear']) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    </td>
    <td>
    <input type="radio" name="f_active" value="TRUE" <?php if($_SESSION['filter']['f_active'] == "TRUE"){ print "checked=\"checked\"";}?> />Oui<br/>
    <input type="radio" name="f_active" value="FALSE" <?php if($_SESSION['filter']['f_active'] == "FALSE"){ print "checked=\"checked\"";}?> />Non<br/>
    <input type="radio" name="f_active" value="active" <?php if($_SESSION['filter']['f_active'] == "active" OR $_SESSION['filter']['f_active'] == ""){ print "checked=\"checked\"";}?> />Tous<br/>
    </td>
    </tr>
    </table>
    <input type="submit" name="Filter" value="filter" />
    </fieldset>
    </form>

    <br/>
    <table><tr><td align="center" bgcolor="yellow"><b>une seule infraction dans l'ann&eacute;e</b></td><td align="center" bgcolor="orange"><b>infractions multiples dans l'ann&eacute;e</b></td></tr></table>
    <br/>

    <?php
    $date = date_create();
    date_sub($date, date_interval_create_from_date_string('6 months'));
    $Y1 = date_format($date, 'Y');
    date_sub($date, date_interval_create_from_date_string('12 months'));
    $Y2 = date_format($date, 'Y');
    date_sub($date, date_interval_create_from_date_string('12 months'));
    $Y3 = date_format($date, 'Y');
    ?>

    <table id="small"><tr align="center"><td></td>
    <td><b>Date et Utilisateur</b></td>
    <td><b>Autorisation de p&ecirc;che</b></td>
    <td nowrap><b>Esp&egrave;ces cibles,<br/>engins et filet</b></td>
    <!--<td nowrap><b>Esp&egrave;ces cibles,<br/> engins et filet supp.</b></td>-->
    <td><b>D&eacute;barcad&egrave;res</b></td>
    <td><b>Moteur</b></td>
    <td><b>Pirogue</b></td>
    <td><b>Coop&eacute;rative</b></td>
    <td><b><?php print "$Y1/$Y2/$Y3"; ?></b></td>
    <td><b>Actif</b></td>
    <td><b>Carte</b></td>
    <td><b>Infractions</b></td>
    </tr>

    <?php

    # username, date_v, date_f, t_license, receipt, payment, zone, t_site, engine_brand, engine_cv, t_gear, license_cope, id_pirogue

    if ($_SESSION['filter']['f_year'] != "" OR $_SESSION['filter']['s_immatriculation'] != "" OR $_SESSION['filter']['f_t_coop'] != "" OR $_SESSION['filter']['f_t_license'] != "" OR $_SESSION['filter']['f_t_gear'] != "" OR $_SESSION['filter']['f_active'] != "" OR $_SESSION['filter']['f_old'] != "") {

        $_SESSION['start'] = 0;

        $query = "SELECT count(license.id) FROM artisanal.license "
            . "WHERE (license.t_coop=".$_SESSION['filter']['f_t_coop']."  OR license.t_coop IS NULL)"
            . "AND (t_license=".$_SESSION['filter']['f_t_license']." OR license.t_license_2=".$_SESSION['filter']['f_t_license'].") "
            . "AND (t_gear=".$_SESSION['filter']['f_t_gear']." OR license.t_gear_2=".$_SESSION['filter']['f_t_gear'].") "
            . "AND (extract(year from date_v) = ".$_SESSION['filter']['f_year'].") "
            . "AND (active=".$_SESSION['filter']['f_active'].") ";

        $pnum = pg_fetch_row(pg_query($query))[0];

        if ($_SESSION['filter']['s_immatriculation'] != "" OR $_SESSION['filter']['s_pir_name'] != "") {

            $query = "SELECT license.id, license.datetime::date, license.username, license.license, extract(year from date_v), "
            . "l1.license, l2.license, g1.gear, g2.gear, mesh_min, mesh_max, mesh_min_2, mesh_max_2, license.length, license.length_2, s1.site, s2.site, engine_brand, "
            . "engine_cv, receipt, payment, agasa, id_pirogue, pirogue.name, pirogue.immatriculation, t_coop.coop, license.active, license.comments, s2_2.site, "
            . "coalesce(similarity(artisanal.pirogue.immatriculation, '".$_SESSION['filter']['s_immatriculation']."'),0) + "
            . "coalesce(similarity(artisanal.pirogue.name, '".$_SESSION['filter']['s_pir_name']."'),0) AS score"
            . " FROM artisanal.license "
            . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
            . "LEFT JOIN artisanal.t_license l1 ON l1.id = artisanal.license.t_license "
            . "LEFT JOIN artisanal.t_license l2 ON l2.id = artisanal.license.t_license_2 "
            . "LEFT JOIN artisanal.t_gear g1 ON g1.id = artisanal.license.t_gear "
            . "LEFT JOIN artisanal.t_gear g2 ON g2.id = artisanal.license.t_gear_2 "
            . "LEFT JOIN artisanal.t_site s1 ON s1.id = artisanal.license.t_site "
            . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = artisanal.license.t_site_obb "
            . "LEFT JOIN artisanal.t_site_obb s2_2 ON s2_2.id = artisanal.license.t_site_obb_2 "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.license.id_pirogue "
            . "WHERE (license.t_coop=".$_SESSION['filter']['f_t_coop']." OR license.t_coop IS NULL) "
            . "AND (t_license=".$_SESSION['filter']['f_t_license']." OR license.t_license_2=".$_SESSION['filter']['f_t_license']." OR license.t_license IS NULL  OR license.t_license_2 IS NULL) "
            . "AND (t_gear=".$_SESSION['filter']['f_t_gear']." OR license.t_gear_2=".$_SESSION['filter']['f_t_gear']." OR license.t_gear IS NULL) "
            . "AND (extract(year from date_v) = ".$_SESSION['filter']['f_year'].") "
            . "AND (active=".$_SESSION['filter']['f_active'].") "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT license.id, license.datetime::date, license.username, license.license, extract(year from date_v), "
            . "l1.license, l2.license, g1.gear, g2.gear, mesh_min, mesh_max, mesh_min_2, mesh_max_2, license.length, license.length_2, s1.site, s2.site, engine_brand, "
            . "engine_cv, receipt, payment, agasa, id_pirogue, pirogue.name, pirogue.immatriculation, t_coop.coop, license.active, license.comments, s2_2.site "
            . " FROM artisanal.license "
            . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
            . "LEFT JOIN artisanal.t_license l1 ON l1.id = artisanal.license.t_license "
            . "LEFT JOIN artisanal.t_license l2 ON l2.id = artisanal.license.t_license_2 "
            . "LEFT JOIN artisanal.t_gear g1 ON g1.id = artisanal.license.t_gear "
            . "LEFT JOIN artisanal.t_gear g2 ON g2.id = artisanal.license.t_gear_2 "
            . "LEFT JOIN artisanal.t_site s1 ON s1.id = artisanal.license.t_site "
            . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = artisanal.license.t_site_obb "
            . "LEFT JOIN artisanal.t_site_obb s2_2 ON s2.id = artisanal.license.t_site_obb_2 "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.license.id_pirogue "
            . "WHERE (license.t_coop=".$_SESSION['filter']['f_t_coop']."  OR license.t_coop IS NULL)"
            . "AND (t_license=".$_SESSION['filter']['f_t_license']." OR license.t_license_2=".$_SESSION['filter']['f_t_license']." OR license.t_license IS NULL  OR license.t_license_2 IS NULL) "
            . "AND (t_gear=".$_SESSION['filter']['f_t_gear']." OR license.t_gear_2=".$_SESSION['filter']['f_t_gear']." OR license.t_gear IS NULL) "
            . "AND (extract(year from date_v) = ".$_SESSION['filter']['f_year'].") "
            . "AND (active=".$_SESSION['filter']['f_active'].") "
            . "ORDER BY license.datetime DESC OFFSET $start LIMIT $step";
        }

    } else {

        $query = "SELECT count(license.id) FROM artisanal.license";

        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT license.id, license.datetime::date, license.username, license.license, extract(year from date_v), "
            . "l1.license, l2.license, receipt, payment, agasa, id_pirogue, pirogue.name, pirogue.immatriculation, t_coop.coop, active, count(*) "
            . " FROM artisanal.license "
            . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
            . "LEFT JOIN artisanal.t_license l1 ON l1.id = artisanal.license.t_license "
            . "LEFT JOIN artisanal.t_license l2 ON l2.id = artisanal.license.t_license_2 "
            . "LEFT JOIN artisanal.t_gear g1 ON g1.id = artisanal.license.t_gear "
            . "LEFT JOIN artisanal.t_gear g2 ON g2.id = artisanal.license.t_gear_2 "
            . "LEFT JOIN artisanal.t_site s1 ON s1.id = artisanal.license.t_site "
            . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = artisanal.license.t_site_obb "
            . "LEFT JOIN artisanal.t_site_obb s2_2 ON s2_2.id = artisanal.license.t_site_obb_2 "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.license.id_pirogue "
            . "GROUP BY license.license, extract(year from date_v), id_pirogue"
            . "ORDER BY license.datetime DESC OFFSET $start LIMIT $step";
    }

    print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        # infractions
        $query_i = "SELECT id FROM infraction.infraction WHERE id_pirogue='".$results[22]."';";
        $res_i = pg_num_rows(pg_query($query_i));

        # infractions current year
        $query_i_c = "SELECT id FROM infraction.infraction WHERE id_pirogue='".$results[22]."' AND date_i >
        CURRENT_DATE - INTERVAL '1 year';";
        $res_i_c = pg_num_rows(pg_query($query_i_c));

        # carte
        $query_c = "SELECT id FROM artisanal.carte WHERE id_license='".$results[0]."';";
        $res_c = pg_num_rows(pg_query($query_c));

        if ($results[26] == 't') {
            $val = 'Oui';
        } else {
            $val = 'Non';
        }

        # previous years
        $query = "SELECT * FROM artisanal.license WHERE extract(year FROM date_v) = $Y1 AND id_pirogue='".$results[22]."'";
        if(pg_num_rows(pg_query($query)) > 0) {
            $l_Y1 = 'Oui';
        } else {
            $l_Y1 = 'Non';
        }

        $query = "SELECT * FROM artisanal.license WHERE extract(year FROM date_v) = $Y2 AND id_pirogue='".$results[22]."'";
        if(pg_num_rows(pg_query($query)) > 0) {
            $l_Y2 = 'Oui';
        } else {
            $l_Y2 = 'Non';
        }

        $query = "SELECT * FROM artisanal.license WHERE extract(year FROM date_v) = $Y3 AND id_pirogue='".$results[22]."'";
        if(pg_num_rows(pg_query($query)) > 0) {
            $l_Y3 = 'Oui';
        } else {
            $l_Y3 = 'Non';
        }

        print "<tr align=\"center\" ";

        if ($res_i_c > 0) {
            if ($res_i_c > 1) {
                print "style=\"background-color: orange; \"";
            } else {
                print "style=\"background-color: yellow; \"";
            }
        }

        print ">";


        print "<td>"
        . "<a href=\"./view_license.php?id=$results[0]\">Voir</a><br/>";
        if(right_write($_SESSION['username'],2,2)) {
            print "<a href=\"./view_licenses_license.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_licenses_license.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td nowrap>$results[1]<br/>$results[2]</td><td><b>$results[3]</b><br/>$results[4]</td><td nowrap>$results[5]<br/>$results[7]";

        if ($results[13] != '' OR $results[9]  != '' OR  $results[10]  != '') {
            print "<br/>$results[13]: $results[9]-$results[10]";
        }

        print "<hr><br/>$results[8]";

        if ($results[14] != '' OR $results[11]  != '' OR  $results[12]  != '') {
            print "<br/>$results[14]: $results[11]-$results[12]";
        }

        print "<td nowrap>Attach: $results[15]<br/>Obb: $results[16]<br/>Obb 2: $results[28]</td><td>$results[17]<br/>$results[18] CV</td><td nowrap><a href=\"./view_pirogue.php?id=$results[22]\"><b>$results[23]<br/>$results[24]</b></a></td>"
        . "<td>$results[25]</td><td>$l_Y1/$l_Y2/$l_Y3</td><td>$val</td><td>$res_c</td><td>$res_i</td>";

    }

    print "</tr>";

    print "</table>";

    pages($start,$step,$pnum,'./view_licenses_license.php?source=license&table=licenses&action=show&f_t_coop='.$_SESSION['filter']['f_t_coop'].'&f_t_license='.$_SESSION['filter']['f_t_license'].'&f_t_gear='.$_SESSION['filter']['f_t_gear'].'&s_immatriculation='.$_SESSION['filter']['s_immatriculation'].'&f_active='.'&s_pir_name='.$_SESSION['filter']['s_pir_name'].'&f_year='.$_SESSION['filter']['f_year'].'&f_active='.$_SESSION['filter']['f_active']);

    $controllo = 1;


foot();
