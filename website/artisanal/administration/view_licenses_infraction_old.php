<?php
require("../../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'infractions';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_t_infraction'] = $_POST['f_t_infraction'];
$_SESSION['filter']['s_fish_name'] = $_POST['s_fish_name'];
$_SESSION['filter']['s_owner_name'] = $_POST['s_owner_name'];
$_SESSION['filter']['s_pir_name'] = $_POST['s_pir_name'];
$_SESSION['filter']['s_immatriculation'] = $_POST['s_immatriculation'];
$_SESSION['filter']['f_t_org'] = $_POST['f_t_org'];

if ($_GET['f_t_infraction'] != "") {$_SESSION['filter']['f_t_infraction'] = $_GET['f_t_infraction'];}
if ($_GET['s_fish_name'] != "") {$_SESSION['filter']['s_fish_name'] = $_GET['s_fish_name'];}
if ($_GET['s_owner_name'] != "") {$_SESSION['filter']['s_owner_name'] = $_GET['s_owner_name'];}
if ($_GET['s_pir_name'] != "") {$_SESSION['filter']['s_pir_name'] = $_GET['s_pir_name'];}
if ($_GET['s_immatriculation'] != "") {$_SESSION['filter']['s_immatriculation'] = $_GET['s_immatriculation'];}
if ($_GET['f_t_org'] != "") {$_SESSION['filter']['f_t_org'] = $_GET['f_t_org'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $start = $_GET['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>

    <form method="post" action="<?php echo $self;?>?source=infractions&table=infraction&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr>
            <td><b>Type d'infraction</b></td>
            <td><b>Nom p&ecirc;cheur</b></td>
            <td><b>Nom proprietaire</b></td>
            <td><b>Nom pirogue</b></td>
            <td><b>Immatriculation</b></td>
            <td><b>Organisation</b></td>
        </tr>
    <tr>
    <td>
    <select name="f_t_infraction">
    <option value="infractions.t_infraction" selected="selected">Tous</option>
    <?php
    $result = pg_query("SELECT id, infraction FROM infraction.t_infraction ORDER by id");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $_SESSION['filter']['f_t_infraction']) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    </td>
    <td>
    <input type="text" size="20" name="s_fish_name" value="<?php echo $_SESSION['filter']['s_fish_name']?>"/>
    </td>
    <td>
    <input type="text" size="20" name="s_owner_name" value="<?php echo $_SESSION['filter']['s_owner_name']?>"/>
    </td>
    <td>
    <input type="text" size="20" name="s_pir_name" value="<?php echo $_SESSION['filter']['s_pir_name']?>"/>
    </td>
    <td>
    <input type="text" size="20" name="s_immatriculation" value="<?php echo $_SESSION['filter']['s_immatriculation']?>"/>
    </td>
    <td>
    <select name="f_t_org">
        <option value="t_org" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT id, org FROM infraction.t_org ORDER BY org");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_org']) {
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
    <td><b>Date et Utilisateur</b></td>
    <td><b>ID</b></td>
    <td><b>Date infraction</b></td>
    <td><b>Type infraction</b></td>
    <td><b>Pirogue</b></td>
    <td><b>Proprietaire</b></td>
    <td nowrap><b>P&ecirc;cheur 1</b></td>
    <td nowrap><b>P&ecirc;cheur 2</b></td>
    <td nowrap><b>Montant de l'infraction</b></td>
    <td nowrap><b>Montant pay&eacute;</b></td>
    <td><b>Organisation</b></td>
    <td><b>Point GPS</b></td>
    </tr>

    <?php

    # id, date_i, t_infraction, id_license, id_pirogue, pir_name, immatriculation, id_carte, id_fisherman, fish_first, fish_last, fish_idcard, t_org, name, obj_confiscated, amount_infract, payment, comments

    if ($_SESSION['filter']['f_t_infraction'] != "" OR $_SESSION['filter']['s_fish_name'] != "" OR $_SESSION['filter']['s_owner_name'] != ""
            OR $_SESSION['filter']['s_pir_name'] != "" OR $_SESSION['filter']['s_immatriculation'] != "" OR $_SESSION['filter']['f_t_org'] != "" ) {

        $_SESSION['start'] = 0;

        $query = "SELECT DISTINCT count(infraction.id) FROM infraction.infraction "
                . "LEFT JOIN infraction.infractions ON infraction.infraction.id = infraction.infractions.id_infraction "
            . "LEFT JOIN infraction.t_org ON infraction.t_org.id = infraction.infraction.t_org "
            . "LEFT JOIN infraction.t_infraction ON infraction.t_infraction.id = infractions.t_infraction "

        . "WHERE t_org=".$_SESSION['filter']['f_t_org']." AND t_infraction=".$_SESSION['filter']['f_t_infraction']." ";

        $pnum = pg_fetch_row(pg_query($query))[0];

        if ($_SESSION['filter']['s_fish_name'] != "" OR $_SESSION['filter']['s_owner_name'] != ""
                OR $_SESSION['filter']['s_pir_name'] != "" OR $_SESSION['filter']['s_immatriculation'] != "" ) {
            $query = "SELECT DISTINCT infraction.id, infraction.username, infraction.datetime::date, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, "
        . "owner_t_card, owner_ycard, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_ycard_1, fish_t_nationality_1, "
        . "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_ycard_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, "
        . "fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_ycard_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
        . "fish_idcard_4, fish_t_card_4, fish_ycard_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
        . "amount, payment, n_dep, n_cdc, n_lib, comments, ST_X(location), ST_Y(location), settled, "
            . " coalesce(similarity(infraction.infraction.fish_first_1, '".$_SESSION['filter']['s_fish_name']."'),0) + "
            . " coalesce(similarity(infraction.infraction.fish_last_1, '".$_SESSION['filter']['s_fish_name']."'),0) + "
            . " coalesce(similarity(infraction.infraction.fish_first_2, '".$_SESSION['filter']['s_fish_name']."'),0) + "
            . " coalesce(similarity(infraction.infraction.fish_last_2, '".$_SESSION['filter']['s_fish_name']."'),0) + "
            . " coalesce(similarity(infraction.infraction.fish_first_3, '".$_SESSION['filter']['s_fish_name']."'),0) + "
            . " coalesce(similarity(infraction.infraction.fish_last_3, '".$_SESSION['filter']['s_fish_name']."'),0) + "
            . " coalesce(similarity(infraction.infraction.fish_first_4, '".$_SESSION['filter']['s_fish_name']."'),0) + "
            . " coalesce(similarity(infraction.infraction.fish_last_4, '".$_SESSION['filter']['s_fish_name']."'),0) + "
            . " coalesce(similarity(infraction.infraction.owner_first, '".$_SESSION['filter']['s_owner_name']."'),0) + "
            . " coalesce(similarity(infraction.infraction.owner_last, '".$_SESSION['filter']['s_owner_name']."'),0) + "
            . " coalesce(similarity(infraction.infraction.pir_name, '".$_SESSION['filter']['s_pir_name']."'),0) + "
            . " coalesce(similarity(infraction.infraction.immatriculation, '".$_SESSION['filter']['s_immatriculation']."'),0) AS score"
            . " FROM infraction.infraction "
            . "LEFT JOIN infraction.infractions ON infraction.infraction.id = infraction.infractions.id_infraction "
            . "LEFT JOIN infraction.t_org ON infraction.t_org.id = infraction.infraction.t_org "
            . "LEFT JOIN infraction.t_infraction ON infraction.t_infraction.id = infractions.t_infraction "
            . "WHERE (t_org=".$_SESSION['filter']['f_t_org']." OR t_org IS NULL) "
            . "AND (infractions.t_infraction=".$_SESSION['filter']['f_t_infraction']." OR infractions.t_infraction IS NULL) "
            . "ORDER BY score DESC, date_i DESC OFFSET $start LIMIT $step";
        } else {
            $query = "SELECT DISTINCT infraction.id, infraction.username, infraction.datetime::date, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, "
        . "owner_t_card, owner_ycard, owner_ycard, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_ycard_1, fish_t_nationality_1, "
        . "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_ycard_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, "
        . "fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_ycard_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
        . "fish_idcard_4, fish_t_card_4, fish_ycard_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
        . "amount, payment, n_dep, n_cdc, n_lib, comments, ST_X(location), ST_Y(location), settled "
        . " FROM infraction.infraction "
        . "LEFT JOIN infraction.infractions ON infraction.infraction.id = infraction.infractions.id_infraction "
        . "LEFT JOIN infraction.t_org ON infraction.t_org.id = infraction.infraction.t_org "
        . "LEFT JOIN infraction.t_infraction ON infraction.t_infraction.id = infractions.t_infraction "
        . "WHERE (t_org=".$_SESSION['filter']['f_t_org']." OR t_org IS NULL) "
        . "AND (infractions.t_infraction=".$_SESSION['filter']['f_t_infraction']." OR infractions.t_infraction IS NULL) "
        . "ORDER BY date_i DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT DISTINCT count(infraction.id) FROM infraction.infraction";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT DISTINCT infraction.id, infraction.username, infraction.datetime::date, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, "
        . "owner_t_card, owner_ycard, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_ycard_1, fish_t_nationality_1, "
        . "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_ycard_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, "
        . "fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_ycard_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
        . "fish_idcard_4, fish_t_card_4, fish_ycard_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
        . "amount, payment, n_dep, n_cdc, n_lib, comments, ST_X(location), ST_Y(location), settled "
        . " FROM infraction.infraction "
        . "LEFT JOIN infraction.t_org ON infraction.t_org.id = infraction.infraction.t_org "
        . "ORDER BY date_i DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {
        # infractions

        $query_inf = "SELECT t_infraction.infraction FROM infraction.infractions "
                . "LEFT JOIN infraction.t_infraction ON infraction.infractions.t_infraction = infraction.t_infraction.id "
                . "WHERE id_infraction = '$results[0]'";

        $r_query_inf = pg_query($query_inf);
        $nrows = pg_num_rows($r_query_inf);

        print "<tr align=center>";
        print "<td rowspan=$nrows>";
        print "<a href=\"./view_infraction.php?source=$source&table=$table&action=edit&id=$results[0]\">Voir</a>";
            if(right_write($_SESSION['username'],4,2)) {
            print "<br/><a href=\"./view_licenses_infraction.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_licenses_infraction.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        $query_i = "SELECT t_infraction.infraction FROM infraction.infractions LEFT JOIN infraction.t_infraction ON infraction.t_infraction.id = infraction.infractions.t_infraction WHERE id_infraction ='$results[0]'";
        $rquery_i = pg_query($query_i);
        $nrows = pg_num_rows($rquery_i);

        //print $query_i;

        $results_i = pg_fetch_row($rquery_i);

        print "<td rowspan=$nrows nowrap>$results[1]<br/>$results[2]</td><td rowspan=$nrows nowrap>$results[3]</td><td rowspan=$nrows nowrap>$results[4]</td>";

        print "<td>$results_i[0]</td>";

        if ($results[7] != '') {
            print "<td rowspan=$nrows><a href=\"./view_pirogue.php?id=$results[7]\">$results[8]<br/>$results[9]</a></td>";
        } else {
            print "<td rowspan=$nrows>$results[8]<br/>$results[9]</td>";
        }
        if ($results[10] != '') {
            print "<td rowspan=$nrows><a href=\"./view_owner.php?id=$results[10]\">".strtoupper($results[12])."<br/>".ucfirst($results[11])."</a></td>";
        } else {
            print "<td rowspan=$nrows>".strtoupper($results[12])."<br/>".ucfirst($results[11])."</td>";
        }
        if ($results[17] != '') {
            print "<td rowspan=$nrows><a href=\"./view_fisherman.php?id=$results[17]\">".strtoupper($results[19])."<br/>".ucfirst($results[18])."</a></td>";
        } else {
            print "<td rowspan=$nrows>".strtoupper($results[18])."<br/>".ucfirst($results[17])."</a></td>";
        }
        if ($results[24] != '') {
            print "<td rowspan=$nrows><a href=\"./view_fisherman.php?id=$results[24]\">".strtoupper($results[26])."<br/>".ucfirst($results[25])."</a></td>";
        } else {
            print "<td rowspan=$nrows>".strtoupper($results[26])."<br/>".ucfirst($results[25])."</a></td>";
        }

        print "<td rowspan=$nrows>$results[50]</a></td>";
        print "<td rowspan=$nrows>$results[51]</a></td>";

        print "<td rowspan=$nrows>$results[5]</td><td rowspan=$nrows>";
        if ($results[57] != '') {
            print "<a href=\"view_point.php?X=$results[57]&Y=$results[56]\">".round($results[57],3)."E ".round($results[56],3)."N</a>";
        }
        print "</td></tr>";

        while($results_i = pg_fetch_row($rquery_i)) {
            print "<tr align=center><td>$results_i[0]</td></tr>";
        }

    }

    print "</table>";

    pages($start,$step,$pnum,'./view_licenses_infraction.php?source=license&table=infraction&action=show&f_t_infraction='.$_SESSION['filter']['f_t_infraction'].'&s_fish_name='.$_SESSION['filter']['s_fish_name'].'&s_owner_name='.$_SESSION['filter']['s_owner_name'].'&s_pir_name='.$_SESSION['filter']['s_pir_name'].'&s_immatriculation='.$_SESSION['filter']['s_immatriculation'].'&f_t_org='.$_SESSION['filter']['f_t_org']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT infraction.id, infraction.username, infraction.datetime::date, id_pv, date_i, t_org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, "
        . "owner_t_card, owner_ycard, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_ycard_1, fish_t_nationality_1, "
        . "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_ycard_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, "
        . "fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_ycard_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
        . "fish_idcard_4, fish_t_card_4, fish_ycard_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
        . "amount, payment, n_dep, n_cdc, n_lib, comments, ST_X(location), ST_Y(location), settled "
        . "FROM infraction.infraction WHERE id = '$id'";

    print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    $lat = $results[63];
    $lon = $results[62];

    $lat_deg = intval($lat);
    $lat_min = ($lat - intval($lat))*60;

    $lon_deg = intval($lon);
    $lon_min = ($lon - intval($lon))*60;

    $split = split('. ',$results[9]);
    $t_immatriculation = $split[0];
    $reg_num = split('/',$split[1])[0];
    $reg_year = split('/',$split[1])[1];

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">
    <b>Ajouter comme nouvel enregistrement</b><input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>ID PV</b>
    <br/>
    <input type="text" size="30" name="date_i" value="<?php echo $results[3];?>" />
    <br/>
    <br/>
    <b>Infraction R&eacute;gl&eacute;e</b>
    <br/>
    Oui<input type="radio" name="settled" value="true" <?php if ($results[64] == 't') {print 'checked';} ?>/>
    Non<input type="radio" name="settled" value="false" <?php if ($results[64] != 't') {print 'checked';} ?> />
    <br/>
    <br/>
    <b>Date infraction</b>
    <br/>
    <input type="date" size="30" name="date_i" value="<?php echo $results[4];?>" />
    <br/>
    <br/>
    <b>Agence de contr&ocirc;le</b>
    <br/>
    <select name="t_org">
        <?php
        $result = pg_query("SELECT * FROM infraction.t_org ORDER BY t_org");
        while($row = pg_fetch_row($result)) {
            if ($row[1] == $results5) {
                print "<option value=\"$row[1]\" selected>".$row[1]."</option>";
            } else {
                print "<option value=\"$row[1]\">".$row[1]."</option>";
            }
        }
        ?>
        </select>
    <br/>
    <br/>
    <b>Nom et prenom agente de surveillance</b>
    <br/>
    <input type="text" size="30" name="name_org" value="<?php echo $results[6];?>" />
    <br/>
    <br/>
    <b>Point GPS</b>
    <br/>
    <b>Latitude</b><br/>
    degree: <input type="text" size="10" name="lat_deg" value="<?php echo $lat_deg;?>" />
    <br/>
    minute d&eacute;cimale: <input type="text" size="10" name="lat_min" value="<?php echo $lat_min;?>" />
    <br/><br/>
    <b>Longitude</b><br/>
    degree: <input type="text" size="10" name="lon_deg" value="<?php echo $lon_deg;?>" />
    <br/>
    minute d&eacute;cimale: <input type="text" size="10" name="lon_min" value="<?php echo $lon_min;?>" />
    <br/>
    <br/>
    <b>Nature de l'infraction</b>
    <br/>
    <?php
    $query = "SELECT t_infraction FROM infraction.infractions WHERE id_infraction = '$results[0]'";
    $r_query = pg_query($query);

    while($row = pg_fetch_row($r_query)) {
        $results_inf[] = $row[0];
    }

    $result = pg_query("SELECT * FROM infraction.t_infraction ORDER BY infraction");

    while($row = pg_fetch_row($result)) {

        if (in_array($row[0],$results_inf)) {
            print "<input type=\"checkbox\" name=\"t_infraction[]\" value=\"$row[0]\" checked />".$row[1]."<br/>";
        } else {
            print "<input type=\"checkbox\" name=\"t_infraction[]\" value=\"$row[0]\" />".$row[1]."<br/>";
        }
    }
    ?>
    <br/>
    <b>D&eacute;tails Pirogue</b>
    <br/>
    <select name="id_pirogue"  class="chosen-select" onchange="java_script_:show(this.options[this.selectedIndex].value,'pir_id')">
        <option value='none'>PAS DANS LA LISTE</option>
    <?php
    $result = pg_query("SELECT id, name, immatriculation FROM artisanal.pirogue ORDER BY name");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[7]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[2]." - ".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[2]." - ".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>

    <div id="pir_id" <?php if($results[7] != "") {print 'style="display:none"';} ?>>
    <b>Nom de la pirogue</b>
    <br/>
    <input type="text" size="30" name="pir_name" value = "<?php echo $results[8];?>"/>
    <br/>
    <br/>
    <b>Num&eacute;ro d'immatriculation</b> [format: L. 123/18]
    <br/>
    <select name="t_immatriculation">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_immatriculation ORDER BY immatriculation");
    while($row = pg_fetch_row($result)) {
        if ($row[1] == $t_immatriculation) {
            print "<option value=\"$row[1]\" selected>".$row[1]."</option>";
        } else {
            print "<option value=\"$row[1]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <input type="text" size="5" name="reg_num" value="<?php echo $reg_num;?>" /> /
    <input type="text" size="5" name="reg_year" value="<?php echo $reg_year;?>" />
    <br/>
    <br/>
    </div>

    <b>D&eacute;tails Proprietaire</b>
    <br/>
    <select name="id_owner"  class="chosen-select" onchange="java_script_:show(this.options[this.selectedIndex].value,'owner_id')">
        <option value='none'>PAS DANS LA LISTE</option>
    <?php
    $result = pg_query("SELECT id, first_name, UPPER(last_name), idcard FROM artisanal.owner ORDER BY last_name");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[2]." ".$row[1]." - $row[3]</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[2]." ".$row[1]." - $row[3]</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <div id='owner_id' <?php if($results[10] != "") {print 'style="display:none"';} ?>>
    <b>Prenom Proprietaire</b>
    <br/>
    <input type="text" size="30" name="owner_first" value = "<?php echo $results[11];?>"/>
    <br/>
    <br/>
    <b>Nom Proprietaire</b>
    <br/>
    <input type="text" size="30" name="owner_second" value = "<?php echo $results[12];?>"/>
    <br/>
    <br/>
    <b>Nationalit&eacute;</b>
    <br/>
    <select name="owner_t_nationality">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_nationality ORDER BY nationality");
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
    <table>
    <tr><td style="font-size:1em;padding:0px"><b>Type de pi&egrave;ce d'identit&eacute;</b></td><td><b>Num&eacute;ro</b></td><td><b>Ann&eacute;e d'expiration</b></td></tr>
    <tr><td>
    <select name="owner_t_card">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_card ORDER BY card");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[14]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    </td><td>
    <input type="text" size="30" name="owner_idcard" value="<?php echo $results[13];?>" />
    </td><td>
    <input type="date" size="30" name="owner_ycard" value="<?php echo $results[15];?>" />
    </td></tr>
    </table>
    <br/>
    <b>Num&eacute;ro de t&eacute;l&eacute;phone</b>
    <br/>
    <input type="text" size="20" name="owner_telephone" value="<?php echo $results[18];?>" />
    <br/>
    <br/>
    </div>

    <fieldset class="border">
    <legend>P&ecirc;cheur 1</legend>
    <b>D&eacute;tails P&ecirc;cheur</b><br/>
    <select name="id_fisherman_1"  class="chosen-select" onchange="java_script_:show(this.options[this.selectedIndex].value,'fisherman_1_id')">
        <option value='none'>PAS DANS LA LISTE</option>
    <?php
    $result = pg_query("SELECT id, first_name, UPPER(last_name), idcard FROM artisanal.owner ORDER BY last_name");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[18]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[2]." ".$row[1]." - $row[3]</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[2]." ".$row[1]." - $row[3]</option>";
        }
    }
    ?>
    </select>
    <br/>
    <div id='fisherman_1_id' <?php if($results[18] != "") {print 'style="display:none"';} ?>>
    <br/>
    <b>Prenom P&ecirc;cheur</b>
    <br/>
    <input type="text" size="30" name="fish_first_1" value = "<?php echo $results[19];?>"/>
    <br/>
    <br/>
    <b>Nom P&ecirc;cheur</b>
    <br/>
    <input type="text" size="30" name="fish_last_1" value = "<?php echo $results[20];?>"/>
    <br/>
    <br/>
    <b>Nationalit&eacute;</b>
    <br/>
    <select name="fish_t_nationality_1">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_nationality ORDER BY nationality");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[24]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }

    }
    ?>
    </select>
    <br/>
    <br/>
    <table>
    <tr><td style="font-size:1em;padding:0px"><b>Type de pi&egrave;ce d'identit&eacute;</b></td><td><b>Num&eacute;ro</b></td><td><b>Ann&eacute;e d'expiration</b></td></tr>
    <tr><td>
    <select name="fish_t_card_1">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_card ORDER BY card");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[22]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    </td><td>
    <input type="text" size="30" name="fish_idcard_1" value="<?php echo $results[21];?>" />
    </td><td>
    <input type="date" size="30" name="fish_ycard_1" value="<?php echo $results[23];?>" />
    </td></tr>
    </table>
    <br/>
    <b>Num&eacute;ro de t&eacute;l&eacute;phone</b>
    <br/>
    <input type="text" size="20" name="fish_telephone_1" value="<?php echo $results[25];?>" />
    <br/>
    </div>
    </fieldset>

    <br/>
    <fieldset class="border">
    <legend>P&ecirc;cheur 2</legend>
    <b>D&eacute;tails P&ecirc;cheur</b><br/>
    <select name="id_fisherman_2"  class="chosen-select" onchange="java_script_:show(this.options[this.selectedIndex].value,'fisherman_2_id')">
        <option value='none'>PAS DANS LA LISTE</option>
    <?php
    $result = pg_query("SELECT id, first_name, UPPER(last_name), idcard FROM artisanal.owner ORDER BY last_name");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[26]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[2]." ".$row[1]." - $row[3]</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[2]." ".$row[1]." - $row[3]</option>";
        }
    }
    ?>
    </select>
    <br/>
    <div id='fisherman_2_id' <?php if($results[26] != "") {print 'style="display:none"';} ?>>
    <br/>
    <b>Prenom P&ecirc;cheur</b>
    <br/>
    <input type="text" size="30" name="fish_first_2" value = "<?php echo $results[27];?>"/>
    <br/>
    <br/>
    <b>Nom P&ecirc;cheur</b>
    <br/>
    <input type="text" size="30" name="fish_last_2" value = "<?php echo $results[28];?>"/>
    <br/>
    <br/>
    <b>Nationalit&eacute;</b>
    <br/>
    <select name="fish_t_nationality_2">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_nationality ORDER BY nationality");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[32]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }

    }
    ?>
    </select>
    <br/>
    <br/>
    <table>
    <tr><td style="font-size:1em;padding:0px"><b>Type de pi&egrave;ce d'identit&eacute;</b></td><td><b>Num&eacute;ro</b></td><td><b>Ann&eacute;e d'expiration</b></td></tr>
    <tr><td>
    <select name="fish_t_card_2">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_card ORDER BY card");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[30]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    </td><td>
    <input type="text" size="30" name="fish_idcard_2" value="<?php echo $results[29];?>" />
    </td><td>
    <input type="date" size="30" name="fish_ycard_2" value="<?php echo $results[31];?>" />
    </td></tr>
    </table>
    <br/>
    <b>Num&eacute;ro de t&eacute;l&eacute;phone</b>
    <br/>
    <input type="text" size="20" name="fish_telephone_2" value="<?php echo $results[33];?>" />
    <br/>
    </div>
    </fieldset>

    <br/>
    <fieldset class="border">
    <legend>P&ecirc;cheur 3</legend>
    <b>D&eacute;tails P&ecirc;cheur</b><br/>
    <select name="id_fisherman_3"  class="chosen-select" onchange="java_script_:show(this.options[this.selectedIndex].value,'fisherman_3_id')">
        <option value='none'>PAS DANS LA LISTE</option>
    <?php
    $result = pg_query("SELECT id, first_name, UPPER(last_name), idcard FROM artisanal.owner ORDER BY last_name");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[34]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[2]." ".$row[1]." - $row[3]</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[2]." ".$row[1]." - $row[3]</option>";
        }
    }
    ?>
    </select>
    <br/>
    <div id='fisherman_3_id' <?php if($results[34] != "") {print 'style="display:none"';} ?>>
    <br/>
    <b>Prenom P&ecirc;cheur</b>
    <br/>
    <input type="text" size="30" name="fish_first_3" value = "<?php echo $results[35];?>"/>
    <br/>
    <br/>
    <b>Nom P&ecirc;cheur</b>
    <br/>
    <input type="text" size="30" name="fish_last_3" value = "<?php echo $results[36];?>"/>
    <br/>
    <br/>
    <b>Nationalit&eacute;</b>
    <br/>
    <select name="fish_t_nationality_3">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_nationality ORDER BY nationality");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[40]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }

    }
    ?>
    </select>
    <br/>
    <br/>
    <table>
    <tr><td style="font-size:1em;padding:0px"><b>Type de pi&egrave;ce d'identit&eacute;</b></td><td><b>Num&eacute;ro</b></td><td><b>Ann&eacute;e d'expiration</b></td></tr>
    <tr><td>
    <select name="fish_t_card_3">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_card ORDER BY card");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[38]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    </td><td>
    <input type="text" size="30" name="fish_idcard_3" value="<?php echo $results[37];?>" />
    </td><td>
    <input type="date" size="30" name="fish_ycard_3" value="<?php echo $results[39];?>" />
    </td></tr>
    </table>
    <br/>
    <b>Num&eacute;ro de t&eacute;l&eacute;phone</b>
    <br/>
    <input type="text" size="20" name="fish_telephone_3" value="<?php echo $results[41];?>" />
    <br/>
    </div>
    </fieldset>

    <br/>
    <fieldset class="border">
    <legend>P&ecirc;cheur 4</legend>
    <b>D&eacute;tails P&ecirc;cheur</b><br/>
    <select name="id_fisherman_4"  class="chosen-select" onchange="java_script_:show(this.options[this.selectedIndex].value,'fisherman_4_id')">
        <option value='none'>PAS DANS LA LISTE</option>
    <?php
    $result = pg_query("SELECT id, first_name, UPPER(last_name), idcard FROM artisanal.owner ORDER BY last_name");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[42]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[2]." ".$row[1]." - $row[3]</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[2]." ".$row[1]." - $row[3]</option>";
        }
    }
    ?>
    </select>
    <br/>
    <div id='fisherman_4_id' <?php if($results[42] != "") {print 'style="display:none"';} ?>>
    <br/>
    <b>Prenom P&ecirc;cheur</b>
    <br/>
    <input type="text" size="30" name="fish_first_4" value = "<?php echo $results[43];?>"/>
    <br/>
    <br/>
    <b>Nom P&ecirc;cheur</b>
    <br/>
    <input type="text" size="30" name="fish_last_4" value = "<?php echo $results[44];?>"/>
    <br/>
    <br/>
    <b>Nationalit&eacute;</b>
    <br/>
    <select name="fish_t_nationality_4">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_nationality ORDER BY nationality");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[48]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }

    }
    ?>
    </select>
    <br/>
    <br/>
    <table>
    <tr><td style="font-size:1em;padding:0px"><b>Type de pi&egrave;ce d'identit&eacute;</b></td><td><b>Num&eacute;ro</b></td><td><b>Ann&eacute;e d'expiration</b></td></tr>
    <tr><td>
    <select name="fish_t_card_4">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_card ORDER BY card");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[46]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    </td><td>
    <input type="text" size="30" name="fish_idcard_4" value="<?php echo $results[45];?>" />
    </td><td>
    <input type="date" size="30" name="fish_ycard_4" value="<?php echo $results[47];?>" />
    </td></tr>
    </table>
    <br/>
    <b>Num&eacute;ro de t&eacute;l&eacute;phone</b>
    <br/>
    <input type="text" size="20" name="fish_telephone_4" value="<?php echo $results[49];?>" />
    <br/>
    </div>
    </fieldset>

    <br/>
    <fieldset class="border">
    <legend>D&eacute;tails Object saisis</legend>
    <b>Pirogue saisis</b>
    <br/>
    <input type="text" size="20" name="pir_conf" value = "<?php echo $results[50];?>"/>
    <br/>
    <br/>
    <b>Moteur saisis</b>
    <br/>
    <input type="text" size="20" name="eng_conf" value = "<?php echo $results[51];?>"/>
    <br/>
    <br/>
    <b>Filet saisis</b>
    <br/>
    <input type="text" size="20" name="net_conf" value = "<?php echo $results[52];?>"/>
    <br/>
    <br/>
    <b>Document saisis</b>
    <br/>
    <input type="text" size="20" name="doc_conf" value = "<?php echo $results[53];?>"/>
    <br/>
    <br/>
    <b>Autre saisis</b>
    <br/>
    <input type="text" size="20" name="other_conf" value = "<?php echo $results[54];?>"/>
    <br/>
    <br/>
    <b>Montant de l'infraction</b>
    <br/>
    <input type="text" size="20" name="amount" value = "<?php echo $results[55];?>"/>
    <br/>
    <br/>
    <b>Montant Pay&eacute;</b>
    <br/>
    <input type="text" size="20" name="payment" value = "<?php echo $results[56];?>"/>
    <br/>
    <br/>
    <b>N&deg; d'ordre de versamente</b>
    <br/>
    <input type="text" size="20" name="n_dep" value = "<?php echo $results[57];?>"/>
    <br/>
    <br/>
    <b>N&deg; Bordereau CDC</b>
    <br/>
    <input type="text" size="20" name="n_cdc" value = "<?php echo $results[58];?>"/>
    <br/>
    <br/>
    <b>N&deg; d'acte liberatoire</b>
    <br/>
    <input type="text" size="20" name="n_lib" value = "<?php echo $results[59];?>"/>
    <br/>
    <br/>
    </fieldset>
    <br/>
    <b>Commentaires</b>
    <br/>
    <textarea name="comments" rows="4" cols="50"><?php echo $results[60];?></textarea>
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    </form>

    <br/>
    <br/>

    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM infraction.infraction WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/mantainance/view_licenses_infraction.php?source=$source&table=infraction&action=show");
    }
    $controllo = 1;

}

if ($_POST['submit'] == "Enregistrer") {
    $id = $_POST['id'];
    $date_i = $_POST['date_i'];

    $t_infraction = $_POST['t_infraction'];
    $id_license = $_POST['id_license'];
    $id_fisherman = $_POST['id_fisherman'];
    $fish_first = $_POST['fish_first'];
    $fish_last = $_POST['fish_last'];
    $fish_idcard = $_POST['fish_idcard'];
    $id_carte = $_POST['id_carte'];
    $id_pirogue = $_POST['id_pirogue'];
    $pir_name = $_POST['pir_name'];

    $t_immatriculation = $_POST['t_immatriculation'];
    $reg_num = $_POST['reg_num'];
    $reg_year = $_POST['reg_year'];

    $immatriculation = $t_immatriculation.". ".$reg_num."/".$reg_year;
    $t_org = $_POST['t_org'];
    $name = $_POST['name'];
    $obj_confiscated = $_POST['obj_confiscated'];
    $amount_infract = $_POST['amount_infract'];
    $payment = preg_replace("/[^0-9]{1,4}/", '', $payment);
    $receipt = $_POST['receipt'];
    $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);
    $l_yn = $_POST['l_yn'];
    $c_yn = $_POST['c_yn'];

    $lon_deg = $_POST['lon_deg'];
    $lat_deg = $_POST['lat_deg'];
    $lon_min = $_POST['lon_min'];
    $lat_min = $_POST['lat_min'];

    $lon = $lon_deg+$lon_min/60;
    $lat = $lat_deg+$lat_min/60;

    if ($lon == "" OR $lat == "") {
        $point = "NULL";
    } else {
        $point = "'POINT($lon $lat)'";
    }

    if ($l_yn == "true") {
        # given license number, find ID license and pirogue details
        $id_license = $_POST['id_license'];

        $query = "SELECT pirogue.id, name, immatriculation FROM artisanal.pirogue "
                . "LEFT JOIN artisanal.license ON artisanal.license.id_pirogue = artisanal.pirogue.id "
                . "WHERE artisanal.license.id = '$id_license'";

        $pirogue = pg_fetch_row(pg_query($query)); # it should be only one result
        $id_pirogue = $pirogue[0];
        $pir_name = $pirogue[1];
        $immatriculation = $pirogue[2];
    }

    if ($c_yn == 'true') {
        # given carte number, find cardID fisherman, first and last name
        $id_carte = $_POST['id_carte'];

        $query = "SELECT fisherman.id, first_name, last_name, idcard FROM artisanal.fisherman "
                . "LEFT JOIN artisanal.carte ON artisanal.carte.id_fisherman = artisanal.fisherman.id "
                . "WHERE artisanal.carte.id = '$id_carte'";

        #print $query;

        $fisherman = pg_fetch_row(pg_query($query)); # it should be only one result
        $id_fisherman = $fisherman[0];
        $fish_first = $fisherman[1];
        $fish_last = $fisherman[2];
        $fish_idcard = $fisherman[3];
    }

    if ($l_yn == "false" and $c_yn == "true") {

        $id_license = NULL ;

        if ($_POST['update'] == "Enregistrer") {

            if ($_POST['option_l'] != "") {
                $id_pir = $_POST['option_l'];
                $query = "SELECT id, name, immatriculation FROM artisanal.pirogue WHERE id = '$id_pir'";
                $results = pg_fetch_row(pg_query($query));
                $id_pirogue = $results[0];
                $pir_name = $results[1];
                $immatriculation = $results[2];
            } else {
                $id_pirogue = NULL;
                //$pir_name = $_POST['pir_name'];
                //$immatriculation = $_POST['immatriculation'];
            }

        } else {

            $query = "SELECT id, name, immatriculation, "
                    . "coalesce(similarity(artisanal.pirogue.name ,'$pir_name'),0) + "
                    . "coalesce(similarity(artisanal.pirogue.immatriculation ,'$immatriculation'),0) AS score "
                    . "FROM artisanal.pirogue ORDER BY score DESC LIMIT 10";

            $r_query = pg_query($query);

            print "<p>Similar entries to pirogue <b>$pir_name</b> with immatriculation <b>$immatriculation</b></p>";

            print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\">";
            print "<input type=\"radio\" name=\"option_l\" value=\"\" checked />none of the options<br/>";

            while ($results = pg_fetch_row($r_query)) {
                print "<input type=\"radio\" name=\"option_l\" value=\"$results[0]\" /><b>".$results[1]."</b> - <b>".$results[2]."</b><br/>";
            }

            foot();
            die();
        }
        # try to find pirogue from immatriculation and name
        $controllo = 1;

    } else if ($l_yn == "true" and $c_yn == "false") {

        $id_carte = NULL;

        if ($_POST['update'] == "Enregistrer") {

            if ($_POST['option_c'] != "") {
                $id_fish = $_POST['option_c'];
                $query = "SELECT id, first_name, last_name, idcard FROM artisanal.fisherman WHERE id = '$id_fish'";
                $results = pg_fetch_row(pg_query($query));
                $id_fisherman = $results[0];
                $fish_first = $results[1];
                $fish_last = $results[2];
                $fish_idcard = $results[3];
            } else {
                $id_fisherman = NULL;
                //$fish_first = $_POST['fish_first'];
                //$fish_last = $_POST['fish_last'];
                //$fish_idcard = $_POST['fish_idcard'];
            }

        } else {

            $query = "SELECT id, first_name, last_name, idcard, "
                    . "coalesce(similarity(artisanal.fisherman.idcard ,'$fish_idcard'),0) + "
                    . "coalesce(similarity(artisanal.fisherman.first_name ,'$fish_first'),0) + "
                    . "coalesce(similarity(artisanal.fisherman.last_name ,'$fish_last'),0) AS score "
                    . "FROM artisanal.fisherman ORDER BY score DESC LIMIT 10";

            $r_query = pg_query($query);

            print "<p>Similar entries to fisherman <b>$fish_first $fish_last</b> with ID card <b>$fish_idcard</b></p>";

            print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\">";
            print "<input type=\"radio\" name=\"option_c\" value=\"\" checked />none of the options<br/>";

            while ($results = pg_fetch_row($r_query)) {
                print "<input type=\"radio\" name=\"option_c\" value=\"$results[0]\" /><b>".$results[1]." ".$results[2]."</b> - <b>".$results[3]."</b><br/>";
            }

            foot();
            die();
            }
        # try to find pirogue from immatriculation and name
        $controllo = 1;

    } else if ($l_yn == "false" and $c_yn == "false") {

        $id_license = NULL;
        $id_carte = NULL;

        if ($_POST['update'] == "Enregistrer") {

            if ($_POST['option_c'] != "") {
                $id_fish = $_POST['option_c'];
                $query = "SELECT id, first_name, last_name, idcard FROM artisanal.fisherman WHERE id = '$id_fish'";
                $results = pg_fetch_row(pg_query($query));
                $id_fisherman = $results[0];
                $fish_first = $results[1];
                $fish_last = $results[2];
                $fish_idcard = $results[3];
            } else {
                $id_fisherman = NULL;
//                $fish_first = $_POST['fish_first'];
//                $fish_last = $_POST['fish_last'];
//                $fish_idcard = $_POST['fish_idcard'];
            }

            if ($_POST['option_l'] != "") {
                $id_pir = $_POST['option_l'];
                $query = "SELECT id, name, immatriculation FROM artisanal.pirogue WHERE id = '$id_pir'";
                $results = pg_fetch_row(pg_query($query));
                $id_pirogue = $results[0];
                $pir_name = $results[1];
                $immatriculation = $results[2];
            } else {
                $id_pirogue = NULL;
//                $pir_name = $_POST['pir_name'];
//                $immatriculation = $_POST['immatriculation'];
            }

        } else {

            # fisherman

            $query = "SELECT id, first_name, last_name, idcard, "
                    . "coalesce(similarity(artisanal.fisherman.idcard, '$fish_idcard'),0)+ "
                    . "coalesce(similarity(artisanal.fisherman.first_name, '$fish_first'),0)+ "
                    . "coalesce(similarity(artisanal.fisherman.last_name, '$fish_last'),0) AS score "
                    . "FROM artisanal.fisherman ORDER BY score DESC LIMIT 10";

            $r_query = pg_query($query);

            print "<p>Similar entries to fisherman <b>$fish_first $fish_last</b> with ID card <b>$fish_idcard</b></p>";

            print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\">";
            print "<input type=\"radio\" name=\"option_c\" value=\"\" checked />none of the options<br/>";

            while ($results = pg_fetch_row($r_query)) {
                print "<input type=\"radio\" name=\"option_c\" value=\"$results[0]\" /><b>".$results[1]." ".$results[2]."</b> - <b>".$results[3]."</b><br/>";
            }

            # pirogue

            $query = "SELECT id, name, immatriculation, "
                    . "coalesce(similarity(artisanal.pirogue.name ,'$pir_name'),0) + "
                    . "coalesce(similarity(artisanal.pirogue.immatriculation ,'$immatriculation'),0) AS score "
                    . "FROM artisanal.pirogue ORDER BY score DESC LIMIT 10";

            $r_query = pg_query($query);

            print "<p>Similar entries to pirogue <b>$pir_name</b> with immatriculation <b>$immatriculation</b></p>";

            print "<input type=\"radio\" name=\"option_l\" value=\"\" checked />none of the options<br/>";

            while ($results = pg_fetch_row($r_query)) {
                print "<input type=\"radio\" name=\"option_l\" value=\"$results[0]\" /><b>".$results[1]."</b> - <b>".$results[2]."</b><br/>";
            }

            foot();
            die();
            }

        # try to find pirogue from immatriculation and name
        $controllo = 1;

    }

    if ($_POST['new_old']) {

        $query = "INSERT INTO infraction.infraction (username, date_i, id_license, id_fisherman, "
            . "fish_first, fish_last, fish_idcard, id_carte, id_pirogue, pir_name, immatriculation, "
            . "t_org, name, obj_confiscated, amount_infract,  payment, receipt, comments, location) "
            . "VALUES ('$username', '$date_i', '$id_license', '$id_fisherman', "
            . "'$fish_first', '$fish_last', '$fish_idcard', '$id_carte', '$id_pirogue', '$pir_name', '$immatriculation', "
            . "'$t_org', '$name', '$obj_confiscated', '$amount_infract',  '$payment', '$receipt', '$comments', ST_GeomFromText($point,4326)) RETURNING id;";

        $query = str_replace('\'\'', 'NULL', $query);

        //print $query;

        $id_infraction = pg_fetch_row(pg_query($query));

        //print_r($t_infraction);

        foreach($t_infraction as $t_inf) {
            $query = "INSERT INTO infraction.infractions (username, id_infraction, t_infraction) "
            . "VALUES ('$username', '$id_infraction[0]', '$t_inf');";

            $query = str_replace('\'\'', 'NULL', $query);
            pg_query($query);
        //    print $query;
        }

    } else {

        $query = "UPDATE infraction.infraction SET "
            . "datetime = now(), "
            . "username = '$username', "
            . " date_i = '".$date_i."', id_license = '".$id_license."', id_pirogue = '".$id_pirogue."', "
            . " pir_name = '".$pir_name."', immatriculation = '".$immatriculation."', id_carte = '".$id_carte."', id_fisherman = '".$id_fisherman."', "
            . " fish_first = '".$fish_first."', fish_last = '".$fish_last."', fish_idcard = '".$fish_idcard."', t_org = '".$t_org."', "
            . " name = '".$name."', obj_confiscated = '".$obj_confiscated."', amount_infract = '".$amount_infract."', payment = '".$payment."', receipt = '".$receipt."', comments = '".$comments."', location = ST_GeomFromText($point,4326) "
            . " WHERE id = '{".$id."}'";

        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
    //        print $query;
            msg_queryerror();
        }

        print_r($t_infraction);

        $query = "DELETE FROM infraction.infractions WHERE id_infraction = '{".$id."}';";

        pg_query($query);

        foreach($t_infraction as $t_inf) {
            $query = "INSERT INTO infraction.infractions (username, id_infraction, t_infraction) "
            . "VALUES ('$username', '$id', '$t_inf');";

            $query = str_replace('\'\'', 'NULL', $query);
            pg_query($query);
        //    print $query;
        }

        header("Location: ".$_SESSION['http_host']."/artisanal/view_licenses_infraction.php?source=$source&table=infraction&action=show");

    }



}

foot();
