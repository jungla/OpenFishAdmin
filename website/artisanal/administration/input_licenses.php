<?php
require("../../top_foot.inc.php");

if ($_SERVER['HTTPS'] == "on") {
    $path = "https://".$_SERVER['HTTP_HOST'];
} else {
    $path = "http://".$_SERVER['HTTP_HOST'];
}

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'autorisation';

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

if ($table == 'licenses') {

//se submit = go!
if ($_POST['submit'] == "Enregistrer") {

    $date_v = '01-01-'.htmlspecialchars($_POST['date_v'], ENT_QUOTES);
    $t_license = $_POST['t_license'];
    $t_site = $_POST['t_site'];
    $t_gear = $_POST['t_gear'];
    $t_strata = $_POST['t_strata'];
    $t_license_2 = $_POST['t_license_2'];
    $t_site_obb = $_POST['t_site_obb'];
    $t_site_obb_2 = $_POST['t_site_obb_2'];
    $t_gear_2 = $_POST['t_gear_2'];
    $length = htmlspecialchars($_POST['length'], ENT_QUOTES);
    $length_2 = htmlspecialchars($_POST['length_2'], ENT_QUOTES);
    $mesh_min = htmlspecialchars($_POST['mesh_min'], ENT_QUOTES);
    $mesh_max = htmlspecialchars($_POST['mesh_max'], ENT_QUOTES);
    $mesh_min_2 = htmlspecialchars($_POST['mesh_min_2'], ENT_QUOTES);
    $mesh_max_2 = htmlspecialchars($_POST['mesh_max_2'], ENT_QUOTES);
    $engine_brand = htmlspecialchars($_POST['engine_brand'], ENT_QUOTES);
    $engine_cv = htmlspecialchars($_POST['engine_cv'], ENT_QUOTES);
    $t_coop = $_POST['t_coop'];
    $agasa = htmlspecialchars($_POST['agasa'], ENT_QUOTES);
    $id_pirogue = $_POST['id_pirogue'];
    $comments = htmlspecialchars($_POST['comments'], ENT_QUOTES);

    # check duplicates
    $query = "SELECT pirogue.name, extract(year FROM license.date_v) FROM artisanal.license "
            . "LEFT JOIN artisanal.pirogue ON artisanal.license.id_pirogue = artisanal.pirogue.id "
            . "WHERE id_pirogue='$id_pirogue' AND license.date_v = '$date_v'";

    $nrows = pg_num_rows(pg_query($query));

    $result = pg_fetch_row(pg_query($query));

    //print $query;

    if ($nrows > 0) {
        print "<p>Un autorisation <b>$result[1]</b> pour la pirogue <b>$result[0]</b> existe d&eacute;j&egrave;.<br/>";
        print "<button type=\"button\" onClick=\"goBack()\">Retourner</button></p>";
        foot();
        die();
    }

    $query = "INSERT INTO artisanal.license "
                . "(username, t_strata, t_license, t_license_2, date_v, t_site, t_site_obb, t_site_obb_2, engine_brand, engine_cv, t_gear, t_gear_2, mesh_min, mesh_max, mesh_min_2, mesh_max_2, length, length_2, agasa, t_coop, id_pirogue, comments)"
                . " VALUES ('$username', '$t_strata', '$t_license', '$t_license_2', '$date_v', "
                . "'$t_site', '$t_site_obb', '$t_site_obb_2', '$engine_brand', '$engine_cv', '$t_gear', '$t_gear_2', '$mesh_min', '$mesh_max', '$mesh_min_2', '$mesh_max_2', '$length', '$length_2', '$agasa', '$t_coop', '$id_pirogue','$comments') RETURNING id_pirogue";

    $query = str_replace('\'-- \'', 'NULL', $query);
    $query = str_replace('\'\'', 'NULL', $query);
    print $query;

    $rquery = pg_query($query);

    if(!$rquery) {
//        print $query;
        msg_queryerror();
    } else {
                header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert%20Data&id_dest=artisanal/administration/input_licenses.php?table=licenses");
    }

    $controllo = 1;
}

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ann&eacute;e validit&eacute;</b>
    <br/>
    <input type="text" size="4" name="date_v" value="<?php echo $results[27]; ?>" />
    <br/>
    <br/>
    <b>Strata</b>
    <br/>
    <select name="t_strata">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_strata WHERE active ORDER BY strata");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[28]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <fieldset class="border">
    <legend>Engin de peche</legend>
    <b>Esp&egrave;ces cibles</b>
    <br/>
    <select name="t_license">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_license ORDER BY t_license");
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
    <b>Engin de peche</b>
    <br/>
    <select name="t_gear">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_gear WHERE active ORDER BY t_gear");
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
    <b>Longueur filet</b> [m]
    <br/>
    <input type="text" size="4" name="length" value="<?php echo $results[13];?>" />
    <br/>
    <br/>
    <b>Taille de la maille</b> [de cote en mm]
    <br/>
    min: <input type="text" size="4" name="mesh_min" value="<?php echo $results[11];?>" />
    max: <input type="text" size="4" name="mesh_max" value="<?php echo $results[12];?>" />
    <br/>
    <br/>
    <b>D&eacute;barcad&egrave;re obligatoire</b>
    <br/>
    <select name="t_site_obb"  class="chosen-select" >
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_site_obb WHERE active ORDER BY site");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>

    </fieldset>
    <br/>

    <fieldset class="border">
    <legend>Engin de peche supplementaire</legend>
    <b>Esp&egrave;ces cibles supplementaire</b>
    <br/>
    <select name="t_license_2">
    <option value="" >Aucun</option>
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_license ORDER BY t_license");
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
    <b>Engin de peche supplementaire</b>
    <br/>
    <select name="t_gear_2">
    <option value="" >Aucun</option>
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_gear WHERE active ORDER BY t_gear");
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
    <b>Longueur filet supplementaire</b> [m]
    <br/>
    <input type="text" size="4" name="length_s" value="<?php echo $results[14];?>" />
    <br/>
    <br/>
    <b>Taille de la maille supplementaire</b> [de cote en mm]
    <br/>
    min: <input type="text" size="4" name="mesh_min_2" value="<?php echo $results[15];?>" />
    max: <input type="text" size="4" name="mesh_max_2" value="<?php echo $results[16];?>" />
    <br/>
    <br/>
    <b>D&eacute;barcad&egrave;re obligatoire supplementaire</b>
    <br/>
    <select name="t_site_obb_2"  class="chosen-select" >
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_site_obb WHERE active ORDER BY site");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[10]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    </fieldset>

    <br/>
    <b>Pirogue</b>
    <br/>
    <select name="id_pirogue"  class="chosen-select" >
    <?php
    $result = pg_query("SELECT pirogue.id, name, immatriculation, INITCAP(first_name), UPPER(last_name) FROM artisanal.pirogue "
    . "LEFT JOIN artisanal.owner ON owner.id = pirogue.id_owner ORDER BY name");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[23]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[2]." ".$row[1]." - ".$row[3]." ".$row[4]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[2]." ".$row[1]." - ".$row[3]." ".$row[4]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    Vous ne trouvez pas la pirogue? Ajoutez une nouvelle <a href="input_licenses.php?table=pirogue"> pirogue</a>.
    <br/>
    <br/>
    <b>Site d'attache</b>
    <br/>
    <select name="t_site"  class="chosen-select" >
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_site ORDER BY site");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[9]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Coop&eacute;rative</b>
    <br/>
    <select name="t_coop"  class="chosen-select" >
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_coop WHERE active ORDER BY coop");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[22]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Marque du moteur</b>
    <br/>
    <input type="text" size="20" name="engine_brand" value="<?php echo $results[17];?>" />
    <br/>
    <br/>
    <b>Puissance du moteur total</b> [CV]
    <br/>
    <input type="text" size="5" name="engine_cv" value="<?php echo $results[18];?>" />
    <br/>
    <br/>
    <b>Num&eacute;ro d'agr&eacute;ment AGASA</b>
    <br/>
    <input type="text" size="15" name="agasa" value="<?php echo $results[21];?>" />
    <br/>
    <br/>
    <b>Commentaires</b>
    <br/>
    <textarea name="comments" rows="4" cols="50"><?php echo $results[26];?></textarea>
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>

<?php
}

} else if ($table == 'pirogue') {

//se submit = go!
if ($_POST['submit'] == "Enregistrer") {

    $photo_data_1 = upload_photo($_FILES['photo_file_1']['tmp_name']);
    $photo_data_2 = upload_photo($_FILES['photo_file_2']['tmp_name']);
    $photo_data_3 = upload_photo($_FILES['photo_file_3']['tmp_name']);

    #name, immatriculation, t_pirogue, length, t_site, id_owner

    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $t_immatriculation = $_POST['t_immatriculation'];
    $reg_num = $_POST['reg_num'];
    $reg_year = $_POST['reg_year'];
    $t_pirogue = $_POST['t_pirogue'];
    $length = htmlspecialchars($_POST['length'], ENT_QUOTES);
    $id_owner = $_POST['id_owner'];
    $comments = htmlspecialchars($_POST['comments'], ENT_QUOTES);

    $immatriculation = $t_immatriculation.". ".$reg_num."/".$reg_year;

    # check duplicates
    $query = "SELECT id FROM artisanal.pirogue WHERE immatriculation='$immatriculation'";
    $nrows = pg_num_rows(pg_query($query));

    if ($nrows > 0) {
        print "Une pirogue avec num&eacute;ro d'immatriculation <b>".$immatriculation."</b> existe d&eacute;j&egrave;.<br/>";
        print "<button type=\"button\" onClick=\"goBack()\">Retourner</button>";
        foot();
        die();
    }

    if (trim($id_owner) == "") {
        ?>
        <p>Please fill all mandatory fields (*).</p>

        <form method="POST" target="<?php echo $radice;?>">
        <input type="hidden" name="name" value="<?php echo $name; ?>"/>
        <input type="hidden" name="immatriculation" value="<?php echo $immatriculation; ?>"/>
        <input type="hidden" name="t_pirogue" value="<?php echo $t_pirogue; ?>"/>
        <input type="hidden" name="length" value="<?php echo $length; ?>"/>
        <input type="hidden" name="id_owner" value="<?php echo $id_owner; ?>"/>
        <input type="hidden" name="comments" value="<?php echo $comments; ?>"/>
        <input type="submit" name="Back" value="Back">
        </form>
        <?php
        foot();
        die();

    } else {

        $query = "INSERT INTO artisanal.pirogue "
       . "(username, name, immatriculation, t_pirogue, length, id_owner, comments, photo_data_1, photo_data_2, photo_data_3) "
       . "VALUES ('$username','$name', '$immatriculation', '$t_pirogue', '$length', '$id_owner','$comments', '$photo_data_1', '$photo_data_2', '$photo_data_3') RETURNING id;";

        //echo $query;

        $query = str_replace('\'-- \'', 'NULL', $query);
        $query = str_replace('\'\'', 'NULL', $query);

        $rquery = pg_query($query);

        if(!$rquery) {
            msg_queryerror();
        } else {
            $id = pg_fetch_row($rquery)[0];
            $query = "UPDATE infraction.infraction SET id_pirogue = '$id' WHERE immatriculation='$immatriculation' AND id_pirogue IS NULL";
            //print $query;
            if(!pg_query($query)) {
                print $query;
                msg_queryerror();
            } else {
                header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert%20Data&id_dest=artisanal/administration/input_licenses.php?table=pirogue");
            }
        }

        $controllo = 1;
    }
}

if (!$controllo) {
	?>

	<form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
	<b>Nom de la pirogue</b>
	<br/>
	<input type="text" size="20" name="name" value="<?php echo $_POST['name'];?>" />
	<br/>
	<br/>
        <b>Propri&eacute;taire</b>
        <br/>
	<select name="id_owner" class="chosen-select" >
        <?php
        $result = pg_query("SELECT id, first_name, last_name FROM artisanal.owner ORDER BY last_name");
        while($row = pg_fetch_row($result)) {
            print "<option value=\"$row[0]\">".$row[2]." ".$row[1]."</option>";
        }
        ?>
        </select>
        <br/>
        Vous ne trouvez pas pas le propri&eacute;taire? Ajoutez un nouveau <a href="input_licenses.php?table=owner"> propri&eacute;taire</a>.
        <br/>
        <br/>
        <b>Num&eacute;ro d'immatriculation</b> [format: L. 123/18]
	<br/>
        <select name="t_immatriculation">
        <?php
        $result = pg_query("SELECT * FROM artisanal.t_immatriculation ORDER BY immatriculation");
        while($row = pg_fetch_row($result)) {
            print "<option value=\"$row[1]\">".$row[1]."</option>";
        }
        ?>
        </select>
        <input type="text" size="5" name="reg_num" value="<?php echo $_POST['immatriculation'];?>" /> /
        <input type="text" size="5" name="reg_year" value="<?php echo $_POST['immatriculation'];?>" />
        <br/>
	<br/>
  <!--
  <b>Numero plaque</b>
	<br/>
	<input type="text" size="6" name="plate" value="<?php echo $results[13];?>" />
	<br/>
	<br/>
-->
        <b>Type de pirogue</b>
	<br/>
        <select name="t_pirogue">
        <?php
        $result = pg_query("SELECT * FROM artisanal.t_pirogue ORDER BY t_pirogue");
        while($row = pg_fetch_row($result)) {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
        ?>
        </select>
        <br/>
        <br/>
        <b>Longueur</b> [m]
	<br/>
	<input type="text" size="3" name="length" value="<?php echo $_POST['length'];?>" />
	<br/>
	<br/>
        <b>Ajouter photo 1</b> (format jpg/png/gif, max 500 KB)
        <br/>
        <input type="file" size="40" name="photo_file_1" onchange="ValidateSize(this)" />
        <br/>
        <br/>
        <b>Ajouter photo 2</b> (format jpg/png/gif, max 500 KB)
        <br/>
        <input type="file" size="40" name="photo_file_2" onchange="ValidateSize(this)" />
        <br/>
        <br/>
        <b>Ajouter photo 3</b> (format jpg/png/gif, max 500 KB)
        <br/>
        <input type="file" size="40" name="photo_file_3" onchange="ValidateSize(this)" />
        <br/>
        <br/>
        <b>Commentaires</b>
	<br/>
        <textarea name="comments" rows="4" cols="50"><?php echo $_POST['commments'];?></textarea>
	<br/>
	<br/>
	<input type="submit" value="Enregistrer" name="submit"/>
        </form>
        <br/>
        <br/>

<?php
}


} else if ($table == 'owner') {

//se submit = go!
if ($_POST['submit'] == "Enregistrer") {

    $photo_data = upload_photo($_FILES['photo_file']['tmp_name']);

    #first_name, last_name, bday date,    carte, idcard, active, address, t_nationality, telephon, photo_data

    $first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES);
    $last_name = htmlspecialchars($_POST['last_name'], ENT_QUOTES);
    $bday = $_POST['bday'];
    $wives = htmlspecialchars($_POST['wives'], ENT_QUOTES);
    $children = htmlspecialchars($_POST['children'], ENT_QUOTES);
    $t_card = $_POST['t_card'];
    $idcard = $_POST['idcard'];
    $ycard = $_POST['ycard'];
    $address = htmlspecialchars($_POST['address'], ENT_QUOTES);
    $t_nationality = $_POST['t_nationality'];
    $telephone = htmlspecialchars($_POST['telephone'], ENT_QUOTES);
    $comments = htmlspecialchars($_POST['comments'], ENT_QUOTES);

    # first_name, last_name, bday date, t_card, idcard , address, t_nationality, telephone , photo_data, comments

    # check duplicates
    $query = "SELECT id FROM artisanal.owner WHERE UPPER(last_name)=UPPER('$last_name') AND UPPER(first_name)=UPPER('$first_name') AND bday='$bday'";
    $nrows = pg_num_rows(pg_query($query));

    //print $query;

    if ($nrows > 0) {
        print "Un propri&eacute;taire avec pr&eacute;nom <b>".$first_name."</b> et nom <b>".$last_name."</b> existe d&eacute;j&egrave;.<br/>";
        print "<button type=\"button\" onClick=\"goBack()\">Retourner</button>";
        foot();
        die();
    }

    $query = "INSERT INTO artisanal.owner "
        . "(username, first_name, last_name, bday, wives, children, t_card, idcard, ycard, address, "
        . "t_nationality, telephone, photo_data, comments) "
        . "VALUES ('$username', '$first_name', '$last_name', '$bday', '$wives', '$children', '$t_card', '$idcard', '$ycard', '$address', "
        . "'$t_nationality', '$telephone', '$photo_data', '$comments')";

    $query = str_replace('\'-- \'', 'NULL', $query);
    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
        echo "<p>".$query,"</p>";
        msg_queryerror();
    } else {

      if ($_POST['pec_pro'] == 'on') {
        $query = "INSERT INTO artisanal.fisherman "
            . "(username, first_name, last_name, bday, wives, children, t_card, idcard, ycard, address, "
            . "t_nationality, telephone, photo_data, comments) "
            . "VALUES ('$username', '$first_name', '$last_name', '$bday', '$wives', '$children', '$t_card', '$idcard', '$ycard', '$address', "
            . "'$t_nationality', '$telephone', '$photo_data', '$comments')";

        $query = str_replace('\'-- \'', 'NULL', $query);
        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
            echo "<p>".$query,"</p>";
            msg_queryerror();
        }
      }

      header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert%20Data&id_dest=artisanal/administration/input_licenses.php?table=owner");

    }

    $controllo = 1;

}

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter aussi comme pecheur?</b> <input type="checkbox" name="pec_pro">
    <br/>
    <br/>
    <b>Pr&eacute;nom</b>
    <br/>
    <input type="text" size="20" name="first_name" value="<?php echo $results[3]; ?>" />
    <br/>
    <br/>
    <b>Nom</b>
    <br/>
    <input type="text" size="20" name="last_name" value="<?php echo $results[4]; ?>" />
    <br/>
    <br/>
    <b>Date de naissance</b>
    <br/>
    <input type="date" size="20" name="bday" value="<?php echo $results[5]; ?>" />
    <br/>
    <br/>
    <b>Numero d'&eacute;pouses</b>
    <br/>
    <input type="text" size="3" name="wives"  value="<?php echo $results[6];?>" />
    <br/>
    <br/>
    <b>Numero d'enfants</b>
    <br/>
    <input type="text" size="3" name="children"  value="<?php echo $results[7];?>" />
    <br/>
    <br/>
    <b>Domicile</b>
    <br/>
    <textarea name="address" rows="2" cols="50"><?php echo $results[11];?></textarea><br/>
    <br/>
    <b>Nationalit&eacute;</b>
    <br/>
    <select name="t_nationality">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_nationality WHERE active ORDER BY nationality");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[12]) {
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
    <select name="t_card">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_card WHERE active ORDER BY card");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[8]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    </td><td>
    <input type="text" size="30" name="idcard" value="<?php echo $results[9];?>" />
    </td><td>
    <input type="date" size="30" name="ycard" value="<?php echo $results[10];?>" />
    </td></tr>
    </table>
    <br/>
    <b>Num&eacute;ro de t&eacute;l&eacute;phone</b>
    <br/>
    <input type="text" size="20" name="telephone" value="<?php echo $results[13];?>" />
    <br/>
    <br/>
    <b>Ajouter une photo</b> (format jpg/png/gif, max 500 KB)
    <br/>
    <input type="file" size="40" name="photo_file" onchange="ValidateSize(this)" />
    <br/>
    <br/>
    <b>Commentaires</b>
    <br/>
    <textarea name="comments" rows="4" cols="50"><?php echo $results[15];?></textarea>
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>

<?php
}

} else if ($table == 'fisherman') {

    #username, t_site, first_name, last_name, bday, t_nationality, t_card, idcard, telephone, address, photo_data

    if ($_POST['submit'] == "Enregistrer") {

        $photo_data = upload_photo($_FILES['photo_file']['tmp_name']);

        $first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES);
        $last_name = htmlspecialchars($_POST['last_name'], ENT_QUOTES);
        $bday = $_POST['bday'];
        $wives = htmlspecialchars($_POST['wives'], ENT_QUOTES);
        $children = htmlspecialchars($_POST['children'], ENT_QUOTES);
        $t_nationality = $_POST['t_nationality'];
        $t_card = $_POST['t_card'];
        $idcard = $_POST['idcard'];
        $ycard = $_POST['ycard'];
        $telephone = htmlspecialchars($_POST['telephone'], ENT_QUOTES);
        $address = htmlspecialchars($_POST['address'], ENT_QUOTES);

        # check duplicates
        $query = "SELECT id FROM artisanal.fisherman WHERE UPPER(last_name)=UPPER('$last_name') AND UPPER(first_name)=UPPER('$first_name') AND bday='$bday'";
        $nrows = pg_num_rows(pg_query($query));

        //print $query;

        if ($nrows > 0) {
            print "Un p&ecirc;cheur avec pr&eacute;nom <b>".$first_name."</b> et nom <b>".$last_name."</b> existe d&eacute;j&egrave;.<br/>";
            print "<button type=\"button\" onClick=\"goBack()\">Retourner</button>";
            foot();
            die();
        }

        $query = "INSERT INTO artisanal.fisherman "
            . "(username, first_name, last_name, bday, wives, children, address, t_nationality, t_card, "
            . "idcard, ycard, telephone, photo_data) "
            . " VALUES ('$username', '$first_name', '$last_name', '$bday', '$wives', '$children', '$address', '$t_nationality', '$t_card', "
            . "'$idcard', '$ycard', '$telephone', '$photo_data')";

        $query = str_replace('\'-- \'', 'NULL', $query);
        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
            echo $query."<br/>";
            msg_queryerror();
        } else {

          if ($_POST['pec_pro'] == 'on') {
            $query = "INSERT INTO artisanal.owner "
                . "(username, first_name, last_name, bday, wives, children, t_card, idcard, ycard, address, "
                . "t_nationality, telephone, photo_data, comments) "
                . "VALUES ('$username', '$first_name', '$last_name', '$bday', '$wives', '$children', '$t_card', '$idcard', '$ycard', '$address', "
                . "'$t_nationality', '$telephone', '$photo_data', '$comments')";

            $query = str_replace('\'-- \'', 'NULL', $query);
            $query = str_replace('\'\'', 'NULL', $query);

            if(!pg_query($query)) {
                echo "<p>".$query,"</p>";
                msg_queryerror();
            }
          }

          header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert%20Data&id_dest=artisanal/administration/input_licenses.php?source=autorisation&table=fisherman");

        }

        $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter aussi comme proprietaire?</b> <input type="checkbox" name="pec_pro">
    <br/>
    <br/>
    <b>Pr&eacute;nom</b>
    <br/>
    <input type="text" size="20" name="first_name" value="<?php echo $results[3]; ?>" />
    <br/>
    <br/>
    <b>Nom</b>
    <br/>
    <input type="text" size="20" name="last_name" value="<?php echo $results[4]; ?>" />
    <br/>
    <br/>
    <b>Date de naissance</b>
    <br/>
    <input type="date" size="20" name="bday" value="<?php echo $results[5]; ?>" />
    <br/>
    <br/>
    <b>Numero d'&eacute;pouses</b>
    <br/>
    <input type="text" size="3" name="wives"  value="<?php echo $results[6];?>" />
    <br/>
    <br/>
    <b>Numero d'enfants</b>
    <br/>
    <input type="text" size="3" name="children"  value="<?php echo $results[7];?>" />
    <br/>
    <br/>
    <b>Domicile</b>
    <br/>
    <textarea name="address" rows="2" cols="50"><?php echo $results[11];?></textarea><br/>
    <br/>
    <b>Nationalit&eacute;</b>
    <br/>
    <select name="t_nationality">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_nationality ORDER BY nationality");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[12]) {
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
        <select name="t_card">
        <?php
        $result = pg_query("SELECT * FROM artisanal.t_card WHERE active ORDER BY card");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[8]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
        }
        ?>
        </select>
        </td><td>
        <input type="text" size="30" name="idcard" value="<?php echo $results[9];?>" />
        </td><td>
        <input type="date" size="30" name="ycard" value="<?php echo $results[10];?>" />
        </td></tr>
    </table>
    <br/>
    <b>Num&eacute;ro de t&eacute;l&eacute;phone</b>
    <br/>
    <input type="text" size="20" name="telephone" value="<?php echo $results[13];?>" />
    <br/>
    <br/>
    <b>Ajouter une photo</b> (format jpg/png/gif, max 500 KB)
    <br/>
    <input type="file" size="40" name="photo_file" onchange="ValidateSize(this)"/>
    <br/>
    <br/>
    <b>Commentaires</b>
    <br/>
    <textarea name="comments" rows="4" cols="50"><?php echo $results[15];?></textarea>
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>
    <?php
    }

} else if ($table == "carte") {

    # username, id_fisherman, t_site, payment, receipt, date_d, date_f, id_license, carte_saisie

    if ($_POST['submit'] == "Enregistrer") {

       # id, datetime, username, carte, id_fisherman, payment, receipt, date_d, date_f, id_license, carte_saisie
        if ($_POST['date_v'] == '') {
            $date_v = '01-01-'.date("Y");
        } else {
            $date_v = '01-01-'.htmlspecialchars($_POST['date_v'], ENT_QUOTES);
        }
        $id_fisherman = $_POST['id_fisherman'];
        $id_license = $_POST['id_license'];
        $paid = $_POST['paid'];
        //$payment = htmlspecialchars(preg_replace("/[^0-9]{1,4}/", '', $payment), ENT_QUOTES);
        //$receipt = htmlspecialchars($_POST['receipt'], ENT_QUOTES);

        # check duplicates
        $query = "SELECT first_name, last_name, license, extract(year FROM carte.date_v) FROM artisanal.carte "
                . "LEFT JOIN artisanal.license ON artisanal.carte.id_license = artisanal.license.id "
                . "LEFT JOIN artisanal.fisherman ON artisanal.carte.id_fisherman = artisanal.fisherman.id "
                . "WHERE id_fisherman='$id_fisherman' AND carte.date_v = '$date_v'";

        $nrows = pg_num_rows(pg_query($query));

        $result = pg_fetch_row(pg_query($query));

        //print $query;

        if ($nrows > 0) {
            print "Un carte de p&ecirc;cheur <b>$result[3]</b> avec pr&eacute;nom <b>$result[0]</b> et nom <b>$result[1]</b> et autorisation <b>$result[2]</b> existe d&eacute;j&egrave;.<br/>";
            print "<button type=\"button\" onClick=\"goBack()\">Retourner</button>";
            foot();
            die();
        }

        $query = "INSERT INTO artisanal.carte "
                . "(username, id_fisherman, date_v, id_license, paid, active) "
                . "VALUES ('$username', '$id_fisherman', '$date_v', '$id_license', '$paid', 'TRUE')";

        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
            echo $query."<br/>";
            msg_queryerror();
        } else {
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert%20Data&id_dest=artisanal/administration/input_licenses.php?source=autorisation&table=carte");
        }

        $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ann&eacute;e validit&eacute;</b>
    <br/>
    <input type="text" size="4" name="date_v" value="<?php echo $results[5]; ?>" />
    <br/>
    <br/>
    <b>Nom du p&ecirc;cheur</b>
    <br/>
    <select name="id_fisherman" class="chosen-select">
    <?php
    $result = pg_query("SELECT id, first_name, last_name FROM artisanal.fisherman ORDER BY last_name");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[4]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".strtoupper($row[2])." ".ucfirst($row[1])."</option>";
        } else {
            print "<option value=\"$row[0]\">".strtoupper($row[2])." ".ucfirst($row[1])."</option>";
        }
    }
    ?>
    </select>
    <br/>
    Vous ne pouvez pas trouver un p&ecirc;cheur? Ajoutez un nouveau <a href="input_licenses.php?source=autorisation&table=fisherman"> p&ecirc;cheur</a>.
    <br/>
    <br/>
    <b>Autorisation de p&ecirc;che</b>
    <br/>
    <select name="id_license" class="chosen-select">
    <?php
    $result = pg_query("SELECT license.id, license, extract(year from date_v), name, immatriculation FROM artisanal.license LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = id_pirogue WHERE extract(year from date_v) IS NOT NULL ORDER BY extract(year from date_v) DESC, license");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[8]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".str_pad($row[1], 4, '0', STR_PAD_LEFT)."/".$row[2]." - ".$row[3]." [$row[4]]</option>";
        } else {
            print "<option value=\"$row[0]\">".str_pad($row[1], 4, '0', STR_PAD_LEFT)."/".$row[2]." - ".$row[3]." [$row[4]]</option>";
        }
    }
    ?>
    </select>
    <br/>
    Vous ne pouvez pas trouver un autorisation de p&ecirc;che? Ajoutez un nouvelle <a href="input_licenses.php?source=autorisation&table=licenses"> autorisation</a>.
    <br/>
    <br/>
    <b>Pay&eacute;</b> [10.000 CFA]
    <br/>
    <input type="radio" name="paid" value="TRUE" />Oui<br/>
    <input type="radio" name="paid" value="FALSE" checked/>Non<br/>
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
    }

}

} else {
    msg_noaccess();
}

foot();
