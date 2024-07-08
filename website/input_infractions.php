<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'infractions';

$username = $_SESSION['username'];

top();

if ($_GET['source'] != "") $_SESSION['path'][0] = $_GET['source'];
if ($_GET['table'] != "") $_SESSION['path'][1] = $_GET['table'];

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

print "<h2>".label2name($source)." ".label2name($table)."</h2>";

$self = filter_input(INPUT_SERVER, 'PHP_SELF');
$radice = filter_input(INPUT_SERVER, 'HTTP_HOST');

if (right_read($username,'2')) {

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

            // print $query;

            $id_infraction = pg_fetch_row(pg_query($query));

            //print_r($t_infraction);

            foreach($t_infraction as $t_inf) {
                $query = "INSERT INTO infraction.infractions (username, id_infraction, t_infraction) "
                . "VALUES ('$username', '$id_infraction[0]', '$t_inf');";

                $query = str_replace('\'\'', 'NULL', $query);
                pg_query($query);
            //    print $query;
            }

        }

        header("Location: ".$_SESSION['http_host']."/artisanal/artisanal_infractions.php?source=$source&table=infraction&action=show");

        $controllo = 1;
    }

if (!$controllo) {
    # date_i, t_infraction, id_license, id_pirogue, id_carte, id_fisherman
    # pir_name, immatriculation, t_org, name, obj_confiscated, amount_infract,  payment, comments
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">
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
    <input type="text" size="5" name="lat_deg_dms" />&deg;
    <input type="text" size="5" name="lat_min_dms" />&prime;
    <input type="text" size="5" name="lat_sec_dms" />&prime;&prime;
    </div>

    <div class="DDMM">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_dm" />&deg;
    <input type="text" size="5" name="lat_min_dm" />&prime;
    </div>

    <div class="DD" style="display:none">
    <b>Latitude</b><br/>
    <input type="text" size="5" name="lat_deg_d" />&deg;
    </div>

    <select name="NS">
        <option value="N" >N</option>
        <option value="S" >S</option>
    </select>

    <div class="DDMMSS" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dms" />&deg;
    <input type="text" size="5" name="lon_min_dms" />&prime;
    <input type="text" size="5" name="lon_sec_dms" />&prime;&prime;
    </div>

    <div class="DDMM">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_dm" />&deg;
    <input type="text" size="5" name="lon_min_dm" />&prime;
    </div>

    <div class="DD" style="display:none">
    <b>Longitude</b><br/>
    <input type="text" size="5" name="lon_deg_d" />&deg;
    </div>
    <select name="EO">
        <option value="E" >E</option>
        <option value="O" >O</option>
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

    <div class="pir_id" <?php if($results[7] != "") {print 'style="display:none"';} ?>>
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
    <div class='owner_id' <?php if($results[10] != "") {print 'style="display:none"';} ?>>
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
    </form>

    <br/>
    <br/>
    <?php
    }

} else {
    msg_noaccess();
}

foot();
