<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'autorisation';

$username = $_SESSION['username'];

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_license'] = $_POST['f_license'];
$_SESSION['filter']['f_year'] = $_POST['f_year'];
$_SESSION['filter']['s_immatriculation'] = str_replace('\'','',$_POST['s_immatriculation']);
$_SESSION['filter']['s_pir_name'] = str_replace('\'','',$_POST['s_pir_name']);
$_SESSION['filter']['f_t_site'] = $_POST['f_t_site'];
$_SESSION['filter']['f_t_license'] = $_POST['f_t_license'];
$_SESSION['filter']['f_t_gear'] = $_POST['f_t_gear'];
$_SESSION['filter']['f_active'] = $_POST['f_active'];

if ($_GET['f_license'] != "") {$_SESSION['filter']['f_license'] = $_GET['f_license'];}
if ($_GET['f_year'] != "") {$_SESSION['filter']['f_year'] = $_GET['f_year'];}
if ($_GET['s_immatriculation'] != "") {$_SESSION['filter']['s_immatriculation'] = $_GET['s_immatriculation'];}
if ($_GET['s_pir_name'] != "") {$_SESSION['filter']['s_pir_name'] = $_GET['s_pir_name'];}
if ($_GET['f_t_site'] != "") {$_SESSION['filter']['f_t_site'] = $_GET['f_t_site'];}
if ($_GET['f_t_license'] != "") {$_SESSION['filter']['f_t_license'] = $_GET['f_t_license'];}
if ($_GET['f_active'] != "") {$_SESSION['filter']['f_active'] = $_GET['f_active'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if(right_write($_SESSION['username'],2,1)) {

if ($_GET['action'] == 'show') {

    top();

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $start = $_GET['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=license&table=licenses&action=show" enctype="multipart/form-data">
    <fieldset>
    <table id="no-border"><tr><td><b>Autorisation de p&ecirc;che</b></td><td><b>Ann&eacute;e validit&eacute;</b></td><td><b>Immatriculation pirogue</b></td><td><b>Nom pirogue</b></td><td><b>D&eacute;barcad&egrave;re</b></td><td><b>Esp&egrave;ces cibles</b></td><td><b>Autorisations actif</b></td></tr>
    <tr>
    <td>
    <select name="f_license">
    <option value="license.license" selected="selected">Tous</option>
    <?php
    $result = pg_query("SELECT DISTINCT license FROM artisanal.license ORDER BY license");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $_SESSION['filter']['f_license']) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    </td>
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
    <select name="f_t_site" class="chosen-select">
        <option value="license.t_site" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT id, site FROM artisanal.t_site ORDER BY site");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_site']) {
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
    <input type="radio" name="f_active" value="'TRUE'" <?php if($_SESSION['filter']['f_active'] == "TRUE"){ print "checked=\"checked\"";}?> />Oui<br/>
    <input type="radio" name="f_active" value="'FALSE'" <?php if($_SESSION['filter']['f_active'] == "FALSE"){ print "checked=\"checked\"";}?> />Non<br/>
    <input type="radio" name="f_active" value="license.active" <?php if($_SESSION['filter']['f_active'] == "license.active" OR $_SESSION['filter']['f_active'] == ""){ print "checked=\"checked\"";}?> />Tous<br/>
    </td>
    </tr>
    </table>
    <input type="submit" name="Filter" value="filter" />
    </fieldset>
    </form>

    <br/>
    <form class="table" method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <!-- <button name="submit" value="enregistrer_tous"><i class="material-icons">assignment_turned_in</i>Enregistrer tous</button> -->
    <!--<button name="submit" value="imprimer_tous"><i class="material-icons">local_printshop</i>Imprimer tous</button>-->
    <table id="small"><tr align="center"><td></td>
    <td><b>Date et Utilisateur</b></td>
    <td><b>Autorisation et validit&eacute;</b></td>
    <td><b>Montant et Quittance Autorisation</b></td>
    <td><b>Montant et Quittance Taxe Production</b></td>
    <td><b>AGASA</b></td>
    <td><b>Pirogue et Coop&eacute;rative</b></td>
    <td><b>Actif</b></td>
    <td><b>Infractions</b></td>
    <td><b>Production annee precedent [kg]</b></td>
    </tr>

    <?php

    # username, date_v, date_f, t_license, receipt, payment, zone, t_site, engine_brand, engine_cv, t_gear, license_cope, id_pirogue

    if ($_SESSION['filter']['f_license'] != "" OR $_SESSION['filter']['f_year'] != "" OR $_SESSION['filter']['s_immatriculation'] != "" OR $_SESSION['filter']['f_t_site'] != "" OR $_SESSION['filter']['f_t_license'] != "" OR $_SESSION['filter']['f_active'] != "") {

        $_SESSION['start'] = 0;

        $query = "SELECT count(license.id) FROM artisanal.license "
            . "WHERE (license.t_site=".$_SESSION['filter']['f_t_site']." OR license.t_site_obb=".$_SESSION['filter']['f_t_site'].") "
            . "AND (license.license=".$_SESSION['filter']['f_license'].") "
            . "AND (t_license=".$_SESSION['filter']['f_t_license']." OR license.t_license_2=".$_SESSION['filter']['f_t_license'].") "
            . "AND (extract(year from date_v) = ".$_SESSION['filter']['f_year'].") "
            . "AND (license.active=".$_SESSION['filter']['f_active'].") ";

        $pnum = pg_fetch_row(pg_query($query))[0];

        if ($_SESSION['filter']['s_immatriculation'] != "" OR $_SESSION['filter']['s_pir_name'] != "") {

            $query = "SELECT license.id, license.datetime::date, license.username, license.license, extract(year from date_v), "
            . "l1.license, l2.license, g1.gear, g2.gear, mesh_min, mesh_max, mesh_min_2, mesh_max_2, s1.site, s2.site, engine_brand, "
            . "engine_cv, receipt, "
            . "CASE WHEN owner.t_nationality=7 THEN '100000' WHEN license.t_gear=4 THEN '200000' ELSE '150000' END"
            . ", agasa, id_pirogue, pirogue.name, pirogue.immatriculation, t_coop.coop, license.active, "
            . "coalesce(similarity(artisanal.pirogue.immatriculation, '".$_SESSION['filter']['s_immatriculation']."'),0) + "
            . "coalesce(similarity(artisanal.pirogue.name, '".$_SESSION['filter']['s_pir_name']."'),0) AS score"
            . ", payment_prod, receipt_prod"
            . " FROM artisanal.license "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = license.id_pirogue "
            . "LEFT JOIN artisanal.owner ON artisanal.owner.id = artisanal.pirogue.id_owner "
            . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
            . "LEFT JOIN artisanal.t_license l1 ON l1.id = artisanal.license.t_license "
            . "LEFT JOIN artisanal.t_license l2 ON l2.id = artisanal.license.t_license_2 "
            . "LEFT JOIN artisanal.t_gear g1 ON g1.id = artisanal.license.t_gear "
            . "LEFT JOIN artisanal.t_gear g2 ON g2.id = artisanal.license.t_gear_2 "
            . "LEFT JOIN artisanal.t_site s1 ON s1.id = artisanal.license.t_site "
            . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = artisanal.license.t_site_obb "
            . "WHERE (license.t_site=".$_SESSION['filter']['f_t_site']." OR license.t_site_obb=".$_SESSION['filter']['f_t_site'].") "
            . "AND (license.license=".$_SESSION['filter']['f_license'].") "
            . "AND (t_license=".$_SESSION['filter']['f_t_license']." OR license.t_license_2=".$_SESSION['filter']['f_t_license'].") "
            . "AND (extract(year from date_v) = ".$_SESSION['filter']['f_year'].") "
            . "AND (license.active=".$_SESSION['filter']['f_active'].") "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT license.id, license.datetime::date, license.username, license.license, extract(year from date_v), "
            . "l1.license, l2.license, g1.gear, g2.gear, mesh_min, mesh_max, mesh_min_2, mesh_max_2, s1.site, s2.site, engine_brand, "
            . "engine_cv, receipt, "
            . "CASE WHEN owner.t_nationality=7 THEN '100000' WHEN license.t_gear=4 THEN '200000' ELSE '150000' END"
            . ", agasa, id_pirogue, pirogue.name, pirogue.immatriculation, t_coop.coop, license.active "
            . ", payment_prod, receipt_prod"
            . " FROM artisanal.license "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = license.id_pirogue "
            . "LEFT JOIN artisanal.owner ON artisanal.owner.id = artisanal.pirogue.id_owner "
            . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
            . "LEFT JOIN artisanal.t_license l1 ON l1.id = artisanal.license.t_license "
            . "LEFT JOIN artisanal.t_license l2 ON l2.id = artisanal.license.t_license_2 "
            . "LEFT JOIN artisanal.t_gear g1 ON g1.id = artisanal.license.t_gear "
            . "LEFT JOIN artisanal.t_gear g2 ON g2.id = artisanal.license.t_gear_2 "
            . "LEFT JOIN artisanal.t_site s1 ON s1.id = artisanal.license.t_site "
            . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = artisanal.license.t_site_obb "
            . "WHERE (license.t_site=".$_SESSION['filter']['f_t_site']." OR license.t_site_obb=".$_SESSION['filter']['f_t_site'].") "
            . "AND (license.license=".$_SESSION['filter']['f_license'].") "
            . "AND (t_license=".$_SESSION['filter']['f_t_license']." OR license.t_license_2=".$_SESSION['filter']['f_t_license'].") "
            . "AND (extract(year from date_v) = ".$_SESSION['filter']['f_year'].") "
            . "AND (license.active=".$_SESSION['filter']['f_active'].") "
            . "ORDER BY license.datetime DESC OFFSET $start LIMIT $step";
        }

    } else {

        $query = "SELECT count(license.id) FROM artisanal.license";

        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT license.id, license.datetime::date, license.username, license.license, extract(year from date_v), "
            . "l1.license, l2.license, g1.gear, g2.gear, mesh_min, mesh_max, mesh_min_2, mesh_max_2, s1.site, s2.site, engine_brand, "
            . "engine_cv, receipt, "
            . "CASE WHEN owner.t_nationality=7 THEN '100000' WHEN license.t_gear=4 THEN '200000' ELSE '150000' END"
            . ", agasa, id_pirogue, pirogue.name, pirogue.immatriculation, t_coop.coop, license.active"
            . ", payment_prod, receipt_prod"
            . " FROM artisanal.license "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = license.id_pirogue "
            . "LEFT JOIN artisanal.owner ON artisanal.owner.id = artisanal.pirogue.id_owner "
            . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
            . "LEFT JOIN artisanal.t_license l1 ON l1.id = artisanal.license.t_license "
            . "LEFT JOIN artisanal.t_license l2 ON l2.id = artisanal.license.t_license_2 "
            . "LEFT JOIN artisanal.t_gear g1 ON g1.id = artisanal.license.t_gear "
            . "LEFT JOIN artisanal.t_gear g2 ON g2.id = artisanal.license.t_gear_2 "
            . "LEFT JOIN artisanal.t_site s1 ON s1.id = artisanal.license.t_site "
            . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = artisanal.license.t_site_obb "
            . "ORDER BY license.datetime DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    $n = 0;

    while ($results = pg_fetch_row($r_query)) {

        $date = $results[4] - 1;

        # infractions
        $query_i = "SELECT id FROM infraction.infraction WHERE id_pirogue='".$results[20]."' AND EXTRACT(year FROM date_i) = '".$date."';";
        $res_i = pg_num_rows(pg_query($query_i));

        if ($results[24] == 't') {
            $val = 'Oui';
        } else {
            $val = 'Non';
        }

        print "<tr align=\"center\">";

        print "<td align=\"left\">"
        . "<button name=\"enregistrer\" class=\"thin\" value=\"$n\"><i class=\"material-icons\">assignment_turned_in</i>Enregistrer</button>";

        if ($results[24] == 't' and $results[17] != '') {
            print "</br><button class=\"thin\" name=\"imprimer\" value=\"$n\"><i class=\"material-icons\">local_printshop</i>Imprimer</button>";
        }

        print "</td>";
        print "<td nowrap>$results[1]<br/>$results[2]</td><td><a href=\"./view_license.php?id=$results[0]\"><b>#$results[3]</b></a><br/>$results[4]</td>"
        . "<td nowrap>"
        . "CFA: $results[18]<br/>"
        . "#:<input type=\"text\" size=\"12\" name=\"receipt[$n]\"  value=\"$results[17]\">"
        . "</td>"
        . "<td nowrap>"
        . "CFA: <input type=\"text\" size=\"12\" name=\"payment_prod[$n]\"  value=\"$results[25]\"><br/>"
        . "#: <textarea rows=\"2\" cols=\"16\" name=\"receipt_prod[$n]\" >$results[26]</textarea>"
        . "</td>";

        print "<td><input type=\"text\" size=\"12\" name=\"agasa[$n]\"  value=\"$results[19]\"></td>"
        . "<td nowrap><a href=\"./view_pirogue.php?id=$results[20]\">$results[21]<br/>$results[22]</a><br/><b>$results[23]</b></td>"
        . "<td nowrap><input type=\"radio\" name=\"activate[$n]\" value=\"TRUE\" "; if($results[24] == 't'){ print "checked=\"checked\""; }
        print "/>Oui<br/><input type=\"radio\" name=\"activate[$n]\" value=\"FALSE\" "; if($results[24] == 'f'){ print "checked=\"checked\"";}
        print "/>Non<br/>";

        print "<input type=\"hidden\" name=\"id[$n]\" value=\"$results[0]\" />";

        print "</td><td ";

        if ($res_i > 0) {
            if ($res_i > 1) {
                print "style=\"background-color: orange; \"";
            } else {
                print "style=\"background-color: yellow; \"";
            }
        }

        print "><b>$res_i</b></td>";

        $query = "SELECT SUM(wgt_spc) FROM artisanal.captures "
        . "LEFT JOIN artisanal.maree ON artisanal.maree.id = artisanal.captures.id_maree "
        . "WHERE maree.id_pirogue = '$results[20]' AND EXTRACT(year FROM datetime_d) = '$date' AND t_study = '2' "
        . "GROUP BY EXTRACT(year FROM datetime_d) ";

        //print $query;
        $prod_log = pg_fetch_row(pg_query($query));

        $query = "SELECT SUM(wgt_spc) FROM artisanal.captures "
        . "LEFT JOIN artisanal.maree ON artisanal.maree.id = artisanal.captures.id_maree "
        . "WHERE maree.id_pirogue = '$results[20]' AND EXTRACT(year FROM datetime_d) = '$date' AND t_study = '1' "
        . "GROUP BY EXTRACT(year FROM datetime_d) ";

        //print $query;
        $prod_ins = pg_fetch_row(pg_query($query));

        print "<td nowrap>Log: $prod_log[0]<br/>Enq: $prod_ins[0]</td>";
        $n++;
    }

    print "</tr>";

    print "</table>";

    print "</form>";

    pages($start,$step,$pnum,'./view_licenses_validate.php?source=license&table=licenses&action=show&f_license='.$_SESSION['filter']['f_license'].'&f_t_site='.$_SESSION['filter']['f_t_site'].'&f_t_license='.$_SESSION['filter']['f_t_license'].'&f_t_gear='.$_SESSION['filter']['f_t_gear'].'&s_immatriculation='.$_SESSION['filter']['s_immatriculation'].'&f_active='.'&s_pir_name='.$_SESSION['filter']['s_pir_name'].'&f_year='.$_SESSION['filter']['f_year'].'&f_active='.$_SESSION['filter']['f_active']);

    $controllo = 1;

} else if (isset($_POST['enregistrer'])) {

  $receipt_prod = htmlspecialchars($_POST['receipt_prod'][$_POST['enregistrer']], ENT_QUOTES);
  $payment_prod = htmlspecialchars($_POST['payment_prod'][$_POST['enregistrer']], ENT_QUOTES);
  $receipt = htmlspecialchars($_POST['receipt'][$_POST['enregistrer']], ENT_QUOTES);
  $agasa = htmlspecialchars($_POST['agasa'][$_POST['enregistrer']], ENT_QUOTES);

    $query = "UPDATE artisanal.license SET "
        . "datetime = now(), "
        . "username = '$username', "
        . "receipt = '$receipt', receipt_prod = '$receipt_prod', payment_prod = '$payment_prod', agasa = '$agasa', active = '".$_POST['activate'][$_POST['enregistrer']]."' "
        . "WHERE id = '{".$_POST['id'][$_POST['enregistrer']]."}'";

    $query = str_replace('\'\'', 'NULL', $query);

    //print $query;

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/artisanal/view_licenses_validate.php?source=$source&table=licenses&action=show");
    }

} else if (isset($_POST['imprimer'])) {
    //print $_POST['id'][$_POST['imprimer']];

    $query = "SELECT license.license, extract(year from date_v), "
            . "l1.license, l2.license, g1.gear, g2.gear, s1.site, s2.site, engine_brand, "
            . "engine_cv, receipt, CASE WHEN owner.t_nationality=7 THEN '100000' WHEN license.t_gear=4 THEN '200000' ELSE '150000' END, t_coop.coop, license.active, pirogue.name, pirogue.immatriculation, t_pirogue.pirogue, "
            . "first_name, last_name, bday, t_card.card, idcard , address, t_nationality.nationality, telephone, date_v, s2.site, s2_2.site, t_strata.strata "
            . " FROM artisanal.license "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.license.id_pirogue "
            . "LEFT JOIN artisanal.owner ON artisanal.owner.id = artisanal.pirogue.id_owner "
            . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue "
            . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
            . "LEFT JOIN artisanal.t_license l1 ON l1.id = artisanal.license.t_license "
            . "LEFT JOIN artisanal.t_license l2 ON l2.id = artisanal.license.t_license_2 "
            . "LEFT JOIN artisanal.t_gear g1 ON g1.id = artisanal.license.t_gear "
            . "LEFT JOIN artisanal.t_gear g2 ON g2.id = artisanal.license.t_gear_2 "
            . "LEFT JOIN artisanal.t_site s1 ON s1.id = artisanal.license.t_site "
            . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = artisanal.license.t_site_obb "
            . "LEFT JOIN artisanal.t_site_obb s2_2 ON s2_2.id = artisanal.license.t_site_obb_2 "
            . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.owner.t_card "
            . "LEFT JOIN artisanal.t_strata ON artisanal.t_strata.id = artisanal.license.t_strata "
            . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.owner.t_nationality "
            . "WHERE license.id = '{".$_POST['id'][$_POST['imprimer']]."}'";

    $r_query = pg_query($query);
    $results = pg_fetch_row($r_query);
    //print $query;
    print_license($results);

}

if ($_POST['submit'] == "enregistrer_tous") {

    $N = sizeof($_POST['id']);

    $n = 0;

    while ($n < $N) {

        $receipt_prod = htmlspecialchars($_POST['receipt_prod'][$n], ENT_QUOTES);
        $payment_prod = htmlspecialchars($_POST['payment_prod'][$n], ENT_QUOTES);

        $receipt = htmlspecialchars($_POST['receipt'][$n], ENT_QUOTES);
        $agasa = htmlspecialchars($_POST['agasa'][$n], ENT_QUOTES);

        $query = "UPDATE artisanal.license SET "
        . "datetime = now(), "
        . "username = '$username', "
        . "receipt = '$receipt', receipt_prod = '$receipt_prod', payment_prod = '$payment_prod', agasa = '$agasa', active = '".$_POST['activate'][$n]."' "
        . "WHERE id = '{".$_POST['id'][$n]."}'";

        $query = str_replace('\'\'', 'NULL', $query);

        //print $query;

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        }

        $n++;
    }

    header("Location: ".$_SESSION['http_host']."/artisanal/view_licenses_validate.php?source=$source&table=licenses&action=show");



} else if ($_POST['submit'] == "imprimer_tous") {

    print "Imprimer tous";
}


} else {
  msg_noaccess();
}
foot();
