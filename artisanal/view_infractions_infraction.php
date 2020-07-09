<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'infractions';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_id_pv'] = $_POST['f_id_pv'];
$_SESSION['filter']['f_t_infraction'] = $_POST['f_t_infraction'];
$_SESSION['filter']['f_user'] = $_POST['f_user'];
$_SESSION['filter']['s_fish_name'] = str_replace('\'','',$_POST['s_fish_name']);
$_SESSION['filter']['s_owner_name'] = str_replace('\'','',$_POST['s_owner_name']);
$_SESSION['filter']['s_pir_name'] = str_replace('\'','',$_POST['s_pir_name']);
$_SESSION['filter']['s_immatriculation'] = str_replace('\'','',$_POST['s_immatriculation']);
$_SESSION['filter']['f_t_org'] = $_POST['f_t_org'];

if ($_GET['f_id_pv'] != "") {$_SESSION['filter']['f_id_pv'] = $_GET['f_id_pv'];}
if ($_GET['f_t_infraction'] != "") {$_SESSION['filter']['f_t_infraction'] = $_GET['f_t_infraction'];}
if ($_GET['f_user'] != "") {$_SESSION['filter']['f_user'] = $_GET['f_user'];}
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
            <td><b>Nom pirogue</b></td>
            <td><b>Immatriculation</b></td></tr>
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
    <input type="text" size="15" name="s_pir_name" value="<?php echo $_SESSION['filter']['s_pir_name']?>"/>
    </td>
    <td>
    <input type="text" size="10" name="s_immatriculation" value="<?php echo $_SESSION['filter']['s_immatriculation']?>"/>
    </td>
  </tr>
  <tr>
  <td><b>Nom p&ecirc;cheur</b></td>
  <td><b>Nom proprietaire</b></td>
  <td><b>Organisation</b></td>
</tr>
<tr>
    <td>
    <input type="text" size="15" name="s_fish_name" value="<?php echo $_SESSION['filter']['s_fish_name']?>"/>
    </td>
    <td>
    <input type="text" size="15" name="s_owner_name" value="<?php echo $_SESSION['filter']['s_owner_name']?>"/>
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
    <tr>
      <td><b>Utilisateur</b></td>
      <td><b>ID PV</b></td>
      <td></td>
    </tr>
    <tr>
      <td>
        <select name="f_user">
            <option value="infraction.username" selected="selected">Tous</option>
            <?php
            $result = pg_query("SELECT DISTINCT username FROM infraction.infraction ORDER BY username");
            while($row = pg_fetch_row($result)) {
                if ("'".$row[0]."'" == $_SESSION['filter']['f_user']) {
                    print "<option value=\"'$row[0]'\" selected=\"selected\">".$row[0]."</option>";
                } else {
                    print "<option value=\"'$row[0]'\">".$row[0]."</option>";
                }
            }
        ?>
        </select></td>
      <td><input type="text" size="15" name="f_id_pv" value="<?php echo $_SESSION['filter']['f_id_pv']?>"/>
      </td>
      <td></td>
    </tr>

    </table>
    <input type="submit" name="Filter" value="filter" />
    </fieldset>
    </form>

    <br/>

    <table id="small">
    <tr align="center"><td></td>
    <td><b>Date et Utilisateur</b></td>
    <td><b>ID et date</b></td>
    <td><b>Type infraction</b></td>
    <td><b>Pirogue et Proprietaire</b></td>
    <td nowrap><b>P&ecirc;cheur 1 et 2</b></td>
    <td nowrap><b>Montant de l'infraction</b></td>
    <td nowrap><b>Montant pay&eacute;</b></td>
    <td><b>Commentaires</b></td>
    <td><b>Regl&eacute;e</b></td>
    <td><b>Organisation</b></td>
    <td><b>Point GPS</b></td>
    </tr>

    <?php

    # id, date_i, t_infraction, id_license, id_pirogue, pir_name, immatriculation, id_carte, id_fisherman, fish_first, fish_last, fish_idcard, t_org, name, obj_confiscated, amount_infract, payment, comments

    if ($_SESSION['filter']['f_t_infraction'] != "" OR $_SESSION['filter']['s_fish_name'] != "" OR $_SESSION['filter']['s_owner_name'] != ""
            OR $_SESSION['filter']['s_pir_name'] != "" OR $_SESSION['filter']['s_immatriculation'] != "" OR $_SESSION['filter']['f_t_org'] != ""
            OR $_SESSION['filter']['f_user'] != "" OR $_SESSION['filter']['f_id_pv'] != "" ) {

        $_SESSION['start'] = 0;

        $query = "SELECT DISTINCT count(infraction.id) FROM infraction.infraction "
            . "LEFT JOIN infraction.infractions ON infraction.infraction.id = infraction.infractions.id_infraction "
            . "LEFT JOIN infraction.t_org ON infraction.t_org.id = infraction.infraction.t_org "
            . "LEFT JOIN infraction.t_infraction ON infraction.t_infraction.id = infractions.t_infraction "
            . "WHERE t_org=".$_SESSION['filter']['f_t_org']." "
            . "AND infractions.username=".$_SESSION['filter']['f_user']." "
            . "AND t_infraction=".$_SESSION['filter']['f_t_infraction']." ";

        $pnum = pg_fetch_row(pg_query($query))[0];

        if ($_SESSION['filter']['s_fish_name'] != "" OR $_SESSION['filter']['s_owner_name'] != ""
                OR $_SESSION['filter']['s_pir_name'] != "" OR $_SESSION['filter']['s_immatriculation'] != "" OR $_SESSION['filter']['f_id_pv'] != "") {
        $query = "SELECT DISTINCT infraction.id, infraction.username, infraction.datetime::date, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, infraction.immatriculation, infraction.id_owner, owner_first, owner_last, owner_idcard, "
        . "owner_t_card, owner_ycard, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_ycard_1, fish_t_nationality_1, "
        . "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_ycard_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, "
        . "fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_ycard_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
        . "fish_idcard_4, fish_t_card_4, fish_ycard_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
        . "amount, payment, n_dep, n_cdc, n_lib, infraction.comments, ST_X(location), ST_Y(location), settled, "
        . " coalesce(similarity(infraction.infraction.id_pv, '".$_SESSION['filter']['f_id_pv']."'),0) + "
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
            . " coalesce(similarity(artisanal.pirogue.name, '".$_SESSION['filter']['s_pir_name']."'),0) + "
            . " coalesce(similarity(infraction.infraction.pir_name, '".$_SESSION['filter']['s_pir_name']."'),0) + "
            . " coalesce(similarity(artisanal.pirogue.immatriculation, '".$_SESSION['filter']['s_immatriculation']."'),0) + "
            . " coalesce(similarity(infraction.infraction.immatriculation, '".$_SESSION['filter']['s_immatriculation']."'),0) AS score, infraction.datetime"
            . " FROM infraction.infraction "
            . "LEFT JOIN infraction.infractions ON infraction.infraction.id = infraction.infractions.id_infraction "
            . "LEFT JOIN infraction.t_org ON infraction.t_org.id = infraction.infraction.t_org "
            . "LEFT JOIN infraction.t_infraction ON infraction.t_infraction.id = infractions.t_infraction "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = infraction.infraction.id_pirogue "
            . "WHERE (t_org=".$_SESSION['filter']['f_t_org']." OR t_org IS NULL) "
            . "AND infractions.username=".$_SESSION['filter']['f_user']." "
            . "AND (infractions.t_infraction=".$_SESSION['filter']['f_t_infraction']." OR infractions.t_infraction IS NULL) "
            . "ORDER BY score DESC, date_i DESC OFFSET $start LIMIT $step";
        } else {
        $query = "SELECT DISTINCT infraction.id, infraction.username, infraction.datetime::date, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, "
        . "owner_t_card, owner_ycard, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_ycard_1, fish_t_nationality_1, "
        . "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_ycard_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, "
        . "fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_ycard_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
        . "fish_idcard_4, fish_t_card_4, fish_ycard_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
        . "amount, payment, n_dep, n_cdc, n_lib, comments, ST_X(location), ST_Y(location), settled, infraction.datetime "
        . " FROM infraction.infraction "
        . "LEFT JOIN infraction.infractions ON infraction.infraction.id = infraction.infractions.id_infraction "
        . "LEFT JOIN infraction.t_org ON infraction.t_org.id = infraction.infraction.t_org "
        . "LEFT JOIN infraction.t_infraction ON infraction.t_infraction.id = infractions.t_infraction "
        . "WHERE (t_org=".$_SESSION['filter']['f_t_org']." OR t_org IS NULL) "
        . "AND (infractions.t_infraction=".$_SESSION['filter']['f_t_infraction']." OR infractions.t_infraction IS NULL) "
        . "AND (infractions.username=".$_SESSION['filter']['f_user']." ) "
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
        . "amount, payment, n_dep, n_cdc, n_lib, comments, ST_X(location), ST_Y(location), settled, infraction.datetime "
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
        $results_inf = pg_fetch_row($r_query_inf);

        print "<tr align=center>";
        print "<td rowspan=$nrows>";
        print "<a href=\"./view_infraction.php?source=$source&table=$table&action=edit&id=$results[0]\">Voir</a>";
            if(right_write($_SESSION['username'],2,5)) {
            print "<br/><a href=\"./view_infractions_infraction.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_infractions_infraction.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";

        print "<td rowspan=$nrows nowrap>$results[1]<br/>$results[2]</td><td rowspan=$nrows nowrap>$results[3]<br/>$results[4]</td>";

        print "<td>$results_inf[0]</td>";

        if ($results[7] != '') {
            $query = "SELECT name, immatriculation FROM artisanal.pirogue WHERE id = '$results[7]'";
            $pirogue_name = pg_fetch_row(pg_query($query));

            print "<td rowspan=$nrows><a href=\"./view_pirogue.php?id=$results[7]\">$pirogue_name[0]<br/>$pirogue_name[1]</a>";
        } else {
            print "<td rowspan=$nrows>$results[8]<br/>$results[9]";
        }

        if (($results[7] != '' OR $results[8] != '' OR $results[9] != '') AND ($results[10] != '' OR $results[11] != '' OR $results[12] != '')) {
                print "<hr>";
        }

        if ($results[10] != '') {
            $query = "SELECT first_name, last_name FROM artisanal.owner WHERE id = '$results[10]'";
            $owner_name = pg_fetch_row(pg_query($query));
            print "<a href=\"./view_owner.php?id=$results[10]\">".strtoupper($owner_name[1])."<br/>".ucfirst($owner_name[0])."</a></td>";
        } else {
            print strtoupper($results[12])."<br/>".ucfirst($results[11])."</td>";
        }

        if ($results[18] != '') {
            $query = "SELECT first_name, last_name FROM artisanal.fisherman WHERE id = '$results[18]'";
            $fisherman_name = pg_fetch_row(pg_query($query));
            print "<td rowspan=$nrows><a href=\"./view_fisherman.php?id=$results[18]\">".strtoupper($fisherman_name[1])."<br/>".ucfirst($fisherman_name[0])."</a>";
        } else {
            print "<td rowspan=$nrows>".strtoupper($results[20])."<br/>".ucfirst($results[19])."</a>";
        }

        if (($results[18] != '' OR $results[19] != '' OR $results[20] != '') AND ($results[27] != '' OR $results[28] != '' OR $results[29] != '')) {
                print "<hr>";
        }

        if ($results[26] != '') {
            $query = "SELECT first_name, last_name FROM artisanal.fisherman WHERE id = '$results[26]'";
            $fisherman_name = pg_fetch_row(pg_query($query));
            print "<a href=\"./view_fisherman.php?id=$results[26]\">".strtoupper($fisherman_name[1])."<br/>".ucfirst($fisherman_name[0])."</a></td>";
        } else {
            print strtoupper($results[28])."<br/>".ucfirst($results[27])."</a></td>";
        }

        print "<td rowspan=$nrows>$results[55]</td>";
        print "<td rowspan=$nrows>$results[56]</td>";

        print "<td rowspan=$nrows>$results[60]</td>";

        #Comment $results[61];
        if ($results[63] == "t") {
            $val = '<b>REGLEE</b>';
        } else {
            $val = '';
        }

        print "<td rowspan=$nrows>$val</td>";

        print "<td rowspan=$nrows>$results[5]</td><td rowspan=$nrows>";
        if ($results[61] != '' and $results[62] != '') {
            print "<a href=\"view_point.php?X=$results[61]&Y=$results[62]\">".round($results[61],3)."E ".round($results[62],3)."N</a>";
        }
        print "</td></tr>";

        while($results_inf = pg_fetch_row($r_query_inf)) {
            print "<tr align=center><td>$results_inf[0]</td></tr>";
        }

    }

    print "</table>";

    pages($start,$step,$pnum,'./view_infractions_infraction.php?source=license&table=infraction&action=show&f_t_infraction='.$_SESSION['filter']['f_t_infraction'].'&s_fish_name='.$_SESSION['filter']['s_fish_name'].'&s_owner_name='.$_SESSION['filter']['s_owner_name'].'&s_pir_name='.$_SESSION['filter']['s_pir_name'].'&s_immatriculation='.$_SESSION['filter']['s_immatriculation'].'&f_user='.$_SESSION['filter']['f_user'].'&f_t_org='.$_SESSION['filter']['f_t_org']);

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

    //print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    $lat = $results[62];
    $lon = $results[61];
    if ($lat > 0) {$NS = 'N';} else {$lat = -1*$lat; $NS = 'S';}
    if ($lon > 0) {$EO = 'E';} else {$lon = -1*$lon; $EO = 'O';}

    $lat_deg_d = $lat;
    $lon_deg_d = $lon;

    $lat_deg_dm = intval($lat_deg_d);
    $lat_min_dm = ($lat_deg_d - intval($lat_deg_d))*60;
    $lon_deg_dm = intval($lon_deg_d);
    $lon_min_dm = ($lon_deg_d - intval($lon_deg_d))*60;

    $lat_deg_dms = $lat_deg_dm;
    $lat_min_dms = intval($lat_min_dm);
    $lat_sec_dms = ($lat_min_dm - intval($lat_min_dm))*60;
    $lon_deg_dms = $lon_deg_dm;
    $lon_min_dms = intval($lon_min_dm);
    $lon_sec_dms = ($lon_min_dm - intval($lon_min_dm))*60;

    if ($results[9] != '') {
      $split = explode('. ',$results[9]);
      $t_immatriculation = $split[0];
      $reg_num = explode('/',$split[1])[0];
      $reg_year = explode('/',$split[1])[1];
    }

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">
    <b>Ajouter comme nouvel enregistrement</b><input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>ID PV</b>
    <br/>
    <input type="text" size="30" name="id_pv" value="<?php echo $results[3];?>" />
    <br/>
    <br/>
    <b>Infraction R&eacute;gl&eacute;e</b>
    <br/>
    Oui<input type="radio" name="settled" value="true" <?php if ($results[63] == 't') {print 'checked';} ?>/>
    Non<input type="radio" name="settled" value="false" <?php if ($results[63] != 't') {print 'checked';} ?> />
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
            if ($row[0] == $results[5]) {
                print "<option value=\"$row[0]\" selected>".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
        }
        ?>
        </select>
    <br/>
    <br/>
    <b>Nom et pr&eacute;nom agent de surveillance</b>
    <br/>
    <input type="text" size="30" name="name_org" value="<?php echo $results[6];?>" />
    <br/>
    <br/>
    <b>Point GPS</b>
    <br/>
    <input type="radio" name="t_coord" value="DDMMSS" onchange="show('','DDMMSS');hide('DDMM');hide('DD')">DD&deg;MM&prime;SS.SS&prime;&prime;
    <input type="radio" name="t_coord" value="DDMM" checked onchange="show('','DDMM');hide('DDMMSS');hide('DD')">DD&deg;MM.MM&prime;
    <input type="radio" name="t_coord" value="DD" onchange="show('','DD');hide('DDMM');hide('DDMMSS')">DD.DD&deg;
    <br/>
    <br/>

    <div class="DDMMSS" style="display:none">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_dms" value="<?php print $lat_deg_dms;?>"/>&deg;
    <input type="text" size="5" name="lat_min_dms" value="<?php print $lat_min_dms;?>"/>&prime;
    <input type="text" size="5" name="lat_sec_dms" value="<?php print $lat_sec_dms;?>"/>&prime;&prime;
    </div>

    <div class="DDMM">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_dm" value="<?php print $lat_deg_dm;?>"/>&deg;
    <input type="text" size="5" name="lat_min_dm" value="<?php print $lat_min_dm;?>"/>&prime;
    </div>

    <div class="DD" style="display:none">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_d"  value="<?php print $lat_deg_d;?>"/>&deg;
    </div>

    <select name="NS">
        <option value="N" <?php if($NS == 'N') {print 'selected';} ?>>N</option>
        <option value="S" <?php if($NS == 'S') {print 'selected';} ?>>S</option>
    </select>

    <div class="DDMMSS" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dms" value="<?php print $lon_deg_dms;?>"/>&deg;
    <input type="text" size="5" name="lon_min_dms" value="<?php print $lon_min_dms;?>"/>&prime;
    <input type="text" size="5" name="lon_sec_dms" value="<?php print $lon_sec_dms;?>"/>&prime;&prime;
    </div>

    <div class="DDMM">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dm" value="<?php print $lon_deg_dm;?>"/>&deg;
    <input type="text" size="5" name="lon_min_dm" value="<?php print $lon_min_dm;?>"/>&prime;
    </div>

    <div class="DD" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_d"  value="<?php print $lon_deg_d;?>"/>&deg;
    </div>

    <select name="EO">
        <option value="E" <?php if($EO == 'E') {print 'selected';} ?> >E</option>
        <option value="O" <?php if($EO == 'O') {print 'selected';} ?>>O</option>
    </select>

    <br/><br/>

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
        <option value=''>PAS DANS LA LISTE</option>
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
        <option value=''>PAS DANS LA LISTE</option>
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
    <input type="text" size="20" name="owner_telephone" value="<?php echo $results[17];?>" />
    <br/>
    <br/>
    </div>

    <fieldset class="border">
    <legend>P&ecirc;cheur 1</legend>
    <b>D&eacute;tails P&ecirc;cheur</b><br/>
    <select name="id_fisherman_1"  class="chosen-select" onchange="java_script_:show(this.options[this.selectedIndex].value,'fisherman_1_id')">
        <option value=''>PAS DANS LA LISTE</option>
    <?php
    $result = pg_query("SELECT id, first_name, UPPER(last_name), idcard FROM artisanal.fisherman ORDER BY last_name");
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
        <option value=''>PAS DANS LA LISTE</option>
    <?php
    $result = pg_query("SELECT id, first_name, UPPER(last_name), idcard FROM artisanal.fisherman ORDER BY last_name");
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
        <option value=''>PAS DANS LA LISTE</option>
    <?php
    $result = pg_query("SELECT id, first_name, UPPER(last_name), idcard FROM artisanal.fisherman ORDER BY last_name");
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
        <option value=''>PAS DANS LA LISTE</option>
    <?php
    $result = pg_query("SELECT id, first_name, UPPER(last_name), idcard FROM artisanal.fisherman ORDER BY last_name");
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
        header("Location: ".$_SESSION['http_host']."/artisanal/view_infractions_infraction.php?source=$source&table=infraction&action=show");
    }
    $controllo = 1;

}

if ($_POST['submit'] == "Enregistrer") {
    $id = $_POST['id'];
    $id_pv = htmlspecialchars($_POST['id_pv'],ENT_QUOTES);
    $date_i = $_POST['date_i'];
    $t_org = $_POST['t_org'];
    $name_org = htmlspecialchars($_POST['name_org'],ENT_QUOTES);
    $t_infraction = $_POST['t_infraction'];

    $id_pirogue = $_POST['id_pirogue'];
    $pir_name = htmlspecialchars($_POST['pir_name'],ENT_QUOTES);
    $t_immatriculation = $_POST['t_immatriculation'];
    $reg_num = htmlspecialchars($_POST['reg_num'],ENT_QUOTES);
    $reg_year = htmlspecialchars($_POST['reg_year'],ENT_QUOTES);
    $immatriculation = $t_immatriculation.". ".$reg_num."/".$reg_year;

    $id_owner = $_POST['id_owner'];
    $owner_first = htmlspecialchars($_POST['owner_first'],ENT_QUOTES);
    $owner_last = htmlspecialchars($_POST['owner_last'],ENT_QUOTES);
    $owner_idcard = htmlspecialchars($_POST['owner_idcard'],ENT_QUOTES);
    $owner_t_card = $_POST['owner_t_card'];
    $owner_ycard = $_POST['owner_ycard'];
    $owner_t_nationality = $_POST['owner_t_nationality'];
    $owner_telephone = htmlspecialchars($_POST['owner_telephone'],ENT_QUOTES);

    $id_fisherman_1 = $_POST['id_fisherman_1'];
    $fish_first_1 = htmlspecialchars($_POST['fish_first_1'],ENT_QUOTES);
    $fish_last_1 = htmlspecialchars($_POST['fish_last_1'],ENT_QUOTES);
    $fish_idcard_1 = htmlspecialchars($_POST['fish_idcard_1'],ENT_QUOTES);
    $fish_t_card_1 = $_POST['fish_t_card_1'];
    $fish_ycard_1 = $_POST['fish_ycard_1'];
    $fish_t_nationality_1 = $_POST['fish_t_nationality_1'];
    $fish_telephone_1 = htmlspecialchars($_POST['fish_telephone_1'],ENT_QUOTES);

    $id_fisherman_2 = $_POST['id_fisherman_2'];
    $fish_first_2 = htmlspecialchars($_POST['fish_first_2'],ENT_QUOTES);
    $fish_last_2 = htmlspecialchars($_POST['fish_last_2'],ENT_QUOTES);
    $fish_idcard_2 = htmlspecialchars($_POST['fish_idcard_2'],ENT_QUOTES);
    $fish_t_card_2 = $_POST['fish_t_card_2'];
    $fish_ycard_2 = $_POST['fish_ycard_2'];
    $fish_t_nationality_2 = $_POST['fish_t_nationality_2'];
    $fish_telephone_2 = htmlspecialchars($_POST['fish_telephone_2'],ENT_QUOTES);

    $id_fisherman_3 = $_POST['id_fisherman_3'];
    $fish_first_3 = htmlspecialchars($_POST['fish_first_3'],ENT_QUOTES);
    $fish_last_3 = htmlspecialchars($_POST['fish_last_3'],ENT_QUOTES);
    $fish_idcard_3 = htmlspecialchars($_POST['fish_idcard_3'],ENT_QUOTES);
    $fish_t_card_3 = $_POST['fish_t_card_3'];
    $fish_ycard_3 = $_POST['fish_ycard_3'];
    $fish_t_nationality_3 = $_POST['fish_t_nationality_3'];
    $fish_telephone_3 = htmlspecialchars($_POST['fish_telephone_3'],ENT_QUOTES);

    $id_fisherman_4 = $_POST['id_fisherman_4'];
    $fish_first_4 = htmlspecialchars($_POST['fish_first_4'],ENT_QUOTES);
    $fish_last_4 = htmlspecialchars($_POST['fish_last_4'],ENT_QUOTES);
    $fish_idcard_4 = htmlspecialchars($_POST['fish_idcard_4'],ENT_QUOTES);
    $fish_t_card_4 = $_POST['fish_t_card_4'];
    $fish_ycard_4 = $_POST['fish_ycard_4'];
    $fish_t_nationality_4 = $_POST['fish_t_nationality_4'];
    $fish_telephone_4 = htmlspecialchars($_POST['fish_telephone_4'],ENT_QUOTES);

    $pir_conf = htmlspecialchars($_POST['pir_conf'],ENT_QUOTES);
    $eng_conf = htmlspecialchars($_POST['eng_conf'],ENT_QUOTES);
    $net_conf = htmlspecialchars($_POST['net_conf'],ENT_QUOTES);
    $doc_conf = htmlspecialchars($_POST['doc_conf'],ENT_QUOTES);
    $other_conf = htmlspecialchars($_POST['other_conf'],ENT_QUOTES);
    $amount = htmlspecialchars($_POST['amount'],ENT_QUOTES);
    $payment = htmlspecialchars($_POST['payment'],ENT_QUOTES);
    $n_dep = htmlspecialchars($_POST['n_dep'],ENT_QUOTES);
    $n_cdc = htmlspecialchars($_POST['n_cdc'],ENT_QUOTES);
    $n_lib = htmlspecialchars($_POST['n_lib'],ENT_QUOTES);
    $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);
    $settled = $_POST['settled'];

    $lon_deg_dms = htmlspecialchars($_POST['lon_deg_dms'],ENT_QUOTES);
    $lat_deg_dms = htmlspecialchars($_POST['lat_deg_dms'],ENT_QUOTES);
    $lon_min_dms = htmlspecialchars($_POST['lon_min_dms'],ENT_QUOTES);
    $lat_min_dms = htmlspecialchars($_POST['lat_min_dms'],ENT_QUOTES);
    $lon_sec_dms = htmlspecialchars($_POST['lon_sec_dms'],ENT_QUOTES);
    $lat_sec_dms = htmlspecialchars($_POST['lat_sec_dms'],ENT_QUOTES);
    $lon_deg_dm = htmlspecialchars($_POST['lon_deg_dm'],ENT_QUOTES);
    $lat_deg_dm = htmlspecialchars($_POST['lat_deg_dm'],ENT_QUOTES);
    $lon_min_dm = htmlspecialchars($_POST['lon_min_dm'],ENT_QUOTES);
    $lat_min_dm = htmlspecialchars($_POST['lat_min_dm'],ENT_QUOTES);
    $lon_deg_d = htmlspecialchars($_POST['lon_deg_d'],ENT_QUOTES);
    $lat_deg_d = htmlspecialchars($_POST['lat_deg_d'],ENT_QUOTES);

    if($_POST['t_coord'] == 'DDMMSS') {
      $lon = $lon_deg_dms+$lon_min_dms/60+$lon_sec_dms/3600;
      $lat = $lat_deg_dms+$lat_min_dms/60+$lon_sec_dms/3600;
    } elseif ($_POST['t_coord'] == 'DDMM') {
      $lon = $lon_deg_dm+$lon_min_dm/60;
      $lat = $lat_deg_dm+$lat_min_dm/60;
    } elseif ($_POST['t_coord'] == 'DD') {
      $lon = $lon_deg_d;
      $lat = $lat_deg_d;
    }

    if ($lon == "" OR $lat == "") {
        $point = "NULL";
    } else {
        if ($_POST['NS'] == 'S') {$lat = -1*$lat;}
        if ($_POST['EO'] == 'O') {$lon = -1*$lon;}
        $point = "'POINT($lon $lat)'";
    }

    if (sizeof($t_infraction) > 0) {

        if ($_POST['new_old']) {

            $query = "INSERT INTO infraction.infraction (username, id_pv, date_i, t_org, name_org, id_pirogue, pir_name, immatriculation, "
                    . "id_owner, owner_first, owner_last, owner_idcard, owner_t_card, owner_ycard, owner_t_nationality, owner_telephone, "
                    . "id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_ycard_1, fish_t_nationality_1, fish_telephone_1, "
                    . "id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_ycard_2, fish_t_nationality_2, fish_telephone_2, "
                    . "id_fisherman_3, fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_ycard_3, fish_t_nationality_3, fish_telephone_3, "
                    . "id_fisherman_4, fish_first_4, fish_last_4, fish_idcard_4, fish_t_card_4, fish_ycard_4, fish_t_nationality_4, fish_telephone_4, "
                    . "pir_conf, eng_conf, net_conf, doc_conf, other_conf, amount, payment, n_dep, n_cdc, n_lib, comments, settled, location) VALUES "
                    . "('$username', '$id_pv', '$date_i', '$t_org', '$name_org', '$id_pirogue', '$pir_name', '$immatriculation', "
                    . "'$id_owner', '$owner_first', '$owner_last', '$owner_idcard', '$owner_t_card', '$owner_ycard', '$owner_t_nationality', '$owner_telephone', "
                    . "'$id_fisherman_1', '$fish_first_1', '$fish_last_1', '$fish_idcard_1', '$fish_t_card_1', '$fish_ycard_1', '$fish_t_nationality_1', '$fish_telephone_1', "
                    . "'$id_fisherman_2', '$fish_first_2', '$fish_last_2', '$fish_idcard_2', '$fish_t_card_2', '$fish_ycard_2', '$fish_t_nationality_2', '$fish_telephone_2', "
                    . "'$id_fisherman_3', '$fish_first_3', '$fish_last_3', '$fish_idcard_3', '$fish_t_card_3', '$fish_ycard_3', '$fish_t_nationality_3', '$fish_telephone_3', "
                    . "'$id_fisherman_4', '$fish_first_4', '$fish_last_4', '$fish_idcard_4', '$fish_t_card_4', '$fish_ycard_4', '$fish_t_nationality_4', '$fish_telephone_4', "
                    . "'$pir_conf', '$eng_conf', '$net_conf', '$doc_conf', '$other_conf', '$amount', '$payment', '$n_dep', '$n_cdc', '$n_lib', '$comments', '$settled', ST_GeomFromText($point,4326)) RETURNING id;";

            $query = str_replace('\'\'', 'NULL', $query);

//            print $query;

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
                    . "id_pv = '$id_pv', date_i = '$date_i', t_org = '$t_org', name_org = '$name_org', id_pirogue = '$id_pirogue', pir_name = '$pir_name', immatriculation = '$immatriculation', "
                    . "id_owner = '$id_owner', owner_first = '$owner_first', owner_last = '$owner_last', owner_idcard = '$owner_idcard', owner_t_card = '$owner_t_card', owner_ycard = '$owner_ycard', owner_t_nationality = '$owner_t_nationality', owner_telephone = '$owner_telephone', "
                    . "id_fisherman_1 = '$id_fisherman_1', fish_first_1 = '$fish_first_1', fish_last_1 = '$fish_last_1', fish_idcard_1 = '$fish_idcard_1', fish_t_card_1 = '$fish_t_card_1', fish_ycard_1 = '$fish_ycard_1', fish_t_nationality_1 = '$fish_t_nationality_1', fish_telephone_1 = '$fish_telephone_1', "
                    . "id_fisherman_2 = '$id_fisherman_2', fish_first_2 = '$fish_first_2', fish_last_2 = '$fish_last_2', fish_idcard_2 = '$fish_idcard_2', fish_t_card_2 = '$fish_t_card_2', fish_ycard_2 = '$fish_ycard_2', fish_t_nationality_2 = '$fish_t_nationality_2', fish_telephone_2 = '$fish_telephone_2', "
                    . "id_fisherman_3 = '$id_fisherman_3', fish_first_3 = '$fish_first_3', fish_last_3 = '$fish_last_3', fish_idcard_3 = '$fish_idcard_3', fish_t_card_3 = '$fish_t_card_3', fish_ycard_3 = '$fish_ycard_3', fish_t_nationality_3 = '$fish_t_nationality_3', fish_telephone_3 = '$fish_telephone_3', "
                    . "id_fisherman_4 = '$id_fisherman_4', fish_first_4 = '$fish_first_4', fish_last_4 = '$fish_last_4', fish_idcard_4 = '$fish_idcard_4', fish_t_card_4 = '$fish_t_card_4', fish_ycard_4 = '$fish_ycard_4', fish_t_nationality_4 = '$fish_t_nationality_4', fish_telephone_4 = '$fish_telephone_4', "
                    . "pir_conf = '$pir_conf', eng_conf = '$eng_conf', net_conf = '$net_conf', doc_conf = '$doc_conf', other_conf = '$other_conf', amount = '$amount', payment = '$payment', n_dep = '$n_dep', n_cdc = '$n_cdc', n_lib = '$n_lib', comments = '$comments', settled = '$settled', location = ST_GeomFromText($point,4326) "
                    . "WHERE id = '{".$id."}'";

            $query = str_replace('\'\'', 'NULL', $query);

//            print $query;

            if(!pg_query($query)) {
                print $query;
                msg_queryerror();
                foot();
                die();
            }

            //print_r($t_infraction);

            $query = "DELETE FROM infraction.infractions WHERE id_infraction = '{".$id."}';";

            pg_query($query);

            foreach($t_infraction as $t_inf) {
                $query = "INSERT INTO infraction.infractions (username, id_infraction, t_infraction) "
                . "VALUES ('$username', '$id', '$t_inf');";

                $query = str_replace('\'\'', 'NULL', $query);
                pg_query($query);
//                print $query;
            }

        }

    }

    header("Location: ".$_SESSION['http_host']."/artisanal/view_infractions_infraction.php?source=$source&table=infraction&action=show");

}

foot();
