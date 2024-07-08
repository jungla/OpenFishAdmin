<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'autorisation';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

if (isset($_POST['f_t_pirogue'])) {
  $_SESSION['filter']['f_t_pirogue'] = $_POST['f_t_pirogue'];
}
if (isset($_POST['s_pir_name'])) {
  $_SESSION['filter']['s_pir_name'] = str_replace('\'','',$_POST['s_pir_name']);
}
if (isset($_POST['s_pir_reg'])){
  $_SESSION['filter']['s_pir_reg'] = str_replace('\'','',$_POST['s_pir_reg']);
}

if ($_GET['f_t_pirogue'] != "") {$_SESSION['filter']['f_t_pirogue'] = $_GET['f_t_pirogue'];}
if ($_GET['s_pir_name'] != "") {$_SESSION['filter']['s_pir_name'] = $_GET['s_pir_name'];}
if ($_GET['s_pir_reg'] != "") {$_SESSION['filter']['s_pir_reg'] = $_GET['s_pir_reg'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $start = $_GET['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>

    <form method="post" action="<?php echo $self;?>?source=autorisation&table=pirogue&action=show" enctype="multipart/form-data">
    <fieldset>
    <table id="no-border"><tr><td><b>Nom du pirogue</b></td><td><b>Num&eacute;ro d'immatriculation</b></td><td><b>Type de pirogue</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="s_pir_name" value="<?php echo $_SESSION['filter']['s_pir_name']?>"/>
    </td>
    <td>
    <input type="text" size="20" name="s_pir_reg" value="<?php echo $_SESSION['filter']['s_pir_reg']?>"/>
    </td>
    <td>
    <select name="f_t_pirogue">
        <option value="t_pirogue" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT id, pirogue FROM artisanal.t_pirogue");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_pirogue']) {
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
    <table><tr><td align="center" bgcolor="yellow"><b>une seule infraction dans l'ann&eacute;e</b></td><td align="center" bgcolor="orange"><b>infractions multiples dans l'ann&eacute;e</b></td></tr></table>
    <br/>

    <table>
    <tr align="center"><td></td>
    <td><b>Date et Utilisateur</b></td>
    <td><b>Nom de la pirogue</b></td>
    <td><b>Immatriculation</b></td>
    <td><b>Plaque</b></td>
    <td><b>Type pirogue</b></td>
    <td><b>Longueur</b></td>
    <td><b>Commentaires</b></td>
    <td><b>Propri&eacute;taire</b></td>
    <td><b>Autorisation</b></td>
    <td><b>Infractions</b></td>
    <td><b>Production annuel</b></td>
    </tr>

    <?php

    # datetime, username, name, immatriculation, t_pirogue, length, t_site, id_owner
    if ($_SESSION['filter']['s_pir_name'] != "" OR $_SESSION['filter']['s_pir_reg'] != "" OR $_SESSION['filter']['f_t_pirogue'] != "") {

        $_SESSION['start'] = 0;

        $query = "SELECT count(pirogue.id) FROM artisanal.pirogue "
        . "WHERE t_pirogue=".$_SESSION['filter']['f_t_pirogue']." ";

        $pnum = pg_fetch_row(pg_query($query))[0];

        if ($_SESSION['filter']['s_pir_name'] != "" OR $_SESSION['filter']['s_pir_reg'] != "") {
            $query = "SELECT pirogue.id, datetime::date, username, name, immatriculation, t_pirogue.pirogue, length, id_owner, comments, plate, "
            . "coalesce(similarity(artisanal.pirogue.immatriculation, '".$_SESSION['filter']['s_pir_reg']."'),0) + "
            . "coalesce(similarity(artisanal.pirogue.name, '".$_SESSION['filter']['s_pir_name']."'),0) AS score"
            . " FROM artisanal.pirogue "
            . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue "
            . "WHERE t_pirogue=".$_SESSION['filter']['f_t_pirogue']." OR t_pirogue IS NULL "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";
        } else {
            $query = "SELECT pirogue.id, datetime::date, username, name, immatriculation, t_pirogue.pirogue, length, id_owner, comments, plate "
            . " FROM artisanal.pirogue "
            . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue "
            . "WHERE t_pirogue=".$_SESSION['filter']['f_t_pirogue']." OR t_pirogue IS NULL "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(pirogue.id) FROM artisanal.pirogue";

        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT pirogue.id, datetime::date, username, name, immatriculation, t_pirogue.pirogue, length, id_owner, comments, plate "
            . " FROM artisanal.pirogue "
            . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
    }

    #print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        # license details
        $query_l = "SELECT count(id) FROM artisanal.license WHERE id_pirogue = '$results[0]'";
        $results_l = pg_fetch_row(pg_query($query_l));

        # infraction details
        $query_i = "SELECT count(id) FROM infraction.infraction WHERE id_pirogue = '$results[0]'";
        $results_i = pg_fetch_row(pg_query($query_i));

        # infractions current year
        $query_i_c = "SELECT id FROM infraction.infraction WHERE id_pirogue='".$results[0]."' AND date_i >
        CURRENT_DATE - INTERVAL '1 year';";
        $res_i_c = pg_num_rows(pg_query($query_i_c));

        # owner details
        $query_o = "SELECT first_name, last_name FROM artisanal.owner WHERE id = '$results[7]'";
        $results_o = pg_fetch_row(pg_query($query_o));

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
        . "<a href=\"./view_pirogue.php?id=$results[0]\">Voir</a><br/>";
        if(right_write($_SESSION['username'],2,2)) {
            print "<a href=\"./view_licenses_pirogue.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_licenses_pirogue.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";

        print "<td>$results[1]<br/>$results[2]</td><td>$results[3]</td><td>$results[4]</td><td>$results[9]</td><td>$results[5]</td><td>$results[6]</td>"
        . "<td>$results[8]</td><td><a href=\"./view_owner.php?id=$results[7]\">".strtoupper($results_o[1])."<br/>".ucfirst($results_o[0])."</td><td>$results_l[0]</td><td>$results_i[0]</td>";

        $query_p = "SELECT SUM(wgt_spc) FROM artisanal.captures "
        . "LEFT JOIN artisanal.maree ON artisanal.maree.id = artisanal.captures.id_maree "
        . "WHERE maree.id_pirogue = '$results[0]' AND datetime_d > CURRENT_DATE - INTERVAL '1 year' AND t_study = '2' ";

        //print $query_p;
        $prod_log = pg_fetch_row(pg_query($query_p));

        $query = "SELECT SUM(wgt_spc) FROM artisanal.captures "
        . "LEFT JOIN artisanal.maree ON artisanal.maree.id = artisanal.captures.id_maree "
        . "WHERE maree.id_pirogue = '$results[20]' AND EXTRACT(year FROM datetime_d) = '$date' AND t_study = '1' "
        . "GROUP BY EXTRACT(year FROM datetime_d) ";

        //print $query;
        $prod_ins = pg_fetch_row(pg_query($query));

        print "<td nowrap>Log: $prod_log[0]<br/>Enq: $prod_ins[0]</td>";
        print "</tr>";

    }


    print "</table>";

    pages($start,$step,$pnum,'./view_licenses_pirogue.php?source=autorisation&table=pirogue&action=show&s_pir_name='.$_SESSION['filter']['s_pir_name'].'&f_t_pirogue='.$_SESSION['filter']['f_t_pirogue'].'&s_pir_reg='.$_SESSION['filter']['s_pir_reg']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT * FROM artisanal.pirogue WHERE id = '$id' ORDER BY datetime DESC";

    //print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    $split = explode('. ',$results[4]);
    $t_immatriculation = $split[0];
    $reg_num = explode('/',$split[1])[0];
    $reg_year = explode('/',$split[1])[1];

    //print $t_immatriculation;
    print "<table><tr>";

    if ($results[10] != "") {
        print "<td><h3>Photo 1</h3><br/>";
        print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\">"
            . "<a href=\"image.php?id=$id&table=artisanal.pirogue&photo_data=photo_data_1\"><img class=\"img_frame\" width=\"300px\" src=\"image.php?id=$id&table=artisanal.pirogue&photo_data=photo_data_1\" /></a>"
            . "<br/>"
            . "<input type=\"submit\" name=\"delete_1\" value=\"Supprimer\" />"
            . "<input type=\"hidden\" name=\"id\" value=\"$id\" />"
            . "</form>"
            . "</td>";
    }
    if ($results[11] != "") {
        print "<td><h3>Photo 2</h3><br/>";
        print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\">"
            . "<a href=\"image.php?id=$id&table=artisanal.pirogue&photo_data=photo_data_2\"><img class=\"img_frame\" width=\"300px\" src=\"image.php?id=$id&table=artisanal.pirogue&photo_data=photo_data_2\" /></a>"
            . "<br/>"
            . "<input type=\"submit\" name=\"delete_2\" value=\"Supprimer\" />"
            . "<input type=\"hidden\" name=\"id\" value=\"$id\" />"
            . "</form></td>";
    }
    if ($results[12] != "") {
        print "<td><h3>Photo 3</h3><br/>";
        print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\">"
            . "<a href=\"image.php?id=$id&table=artisanal.pirogue&photo_data=photo_data_3\"><img class=\"img_frame\" width=\"300px\" src=\"image.php?id=$id&table=artisanal.pirogue&photo_data=photo_data_3\" /></a>"
            . "<br/>"
            . "<input type=\"submit\" name=\"delete_3\" value=\"Supprimer\" />"
            . "<input type=\"hidden\" name=\"id\" value=\"$id\" />"
            . "</form></td>";
    }

    print "</tr></table>";
    ?>

	<form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
        <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
        <br/>
        <br/>
        <b>Nom de la pirogue</b>
	<br/>
	<input type="text" size="20" name="name" value="<?php echo $results[3];?>" />
	<br/>
	<br/>
        <b>Propri&eacute;taire</b>
        <br/>
        <select name="id_owner" class="chosen-select">
        <?php
        $result = pg_query("SELECT id, first_name, last_name FROM artisanal.owner ORDER BY last_name");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[7]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".strtoupper($row[2])." ".ucfirst($row[1])."</option>";
            } else {
                print "<option value=\"$row[0]\">".strtoupper($row[2])." ".ucfirst($row[1])."</option>";
            }
            $i++;
        }
        ?>
        </select>
        <br/>
        You cannot find an owner? Input a new <a href="input_licenses.php?table=owner"> owner</a>.
        <br/>
        <br/>
        <b>Num&eacute;ro d'immatriculation</b>
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
        <b>Numero plaque</b>
	<br/>
	<input type="text" size="6" name="plate" value="<?php echo $results[13];?>" />
	<br/>
	<br/>
        <b>Type de pirogue</b>
	<br/>
        <select name="t_pirogue">
        <?php
        $result = pg_query("SELECT * FROM artisanal.t_pirogue ORDER BY pirogue");
        while($row = pg_fetch_row($result)) {
            if ($results[5] == $i) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
        }
        ?>
        </select>
        <br/>
        <br/>
        <b>Longueur</b> [m]
	<br/>
	<input type="text" size="3" name="length" value="<?php echo $results[6];?>" />
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
        <textarea name="comments" rows="4" cols="50"><?php echo $results[8];?></textarea>
	<br/>
        <br/>
	<input type="hidden" value="<?php echo $results[0];?>" name="id"/>
        <input type="submit" value="Enregistrer" name="submit"/>
        </form>
        <br/>
        <br/>

    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM artisanal.pirogue WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/administration/view_licenses_pirogue.php?source=$source&table=pirogue&action=show");
    }
    $controllo = 1;

}

if ($_POST['delete_1'] == "Supprimer") {
    $id = $_POST['id'];

    $query = "UPDATE artisanal.pirogue SET "
    . " photo_data_1 = NULL "
    . " WHERE id = '{".$id."}'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/administration/view_licenses_pirogue.php?source=$source&table=$table&action=edit&id=$id");
    }

}

if ($_POST['delete_2'] == "Supprimer") {
    $id = $_POST['id'];

    $query = "UPDATE artisanal.pirogue SET "
    . " photo_data_2 = NULL "
    . " WHERE id = '{".$id."}'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/administration/view_licenses_pirogue.php?source=$source&table=$table&action=edit&id=$id");
    }

}

if ($_POST['delete_3'] == "Supprimer") {
    $id = $_POST['id'];

    $query = "UPDATE artisanal.pirogue SET "
    . " photo_data_3 = NULL "
    . " WHERE id = '{".$id."}'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/administration/view_licenses_pirogue.php?source=$source&table=$table&action=edit&id=$id");
    }

}

if ($_POST['submit'] == "Enregistrer") {

    if ($_FILES['photo_file_1']['tmp_name'] != "") {
        $photo_data_1 = upload_photo($_FILES['photo_file_1']['tmp_name']);
    } else {
        $photo_data_1 = pg_fetch_result(pg_query("SELECT photo_data_1 FROM artisanal.pirogue WHERE id = '{".$_POST['id']."}'"), 'photo_data_1');
    }

    if ($_FILES['photo_file_2']['tmp_name'] != "") {
        $photo_data_2 = upload_photo($_FILES['photo_file_2']['tmp_name']);
    } else {
        $photo_data_2 = pg_fetch_result(pg_query("SELECT photo_data_2 FROM artisanal.pirogue WHERE id = '{".$_POST['id']."}'"), 'photo_data_2');
    }

    if ($_FILES['photo_file_3']['tmp_name'] != "") {
        $photo_data_3 = upload_photo($_FILES['photo_file_3']['tmp_name']);
    } else {
        $photo_data_3 = pg_fetch_result(pg_query("SELECT photo_data_3 FROM artisanal.pirogue WHERE id = '{".$_POST['id']."}'"), 'photo_data_3');
    }

    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $t_immatriculation = $_POST['t_immatriculation'];
    $reg_num = $_POST['reg_num'];
    $reg_year = $_POST['reg_year'];
    $t_pirogue = $_POST['t_pirogue'];
    $length = htmlspecialchars($_POST['length'], ENT_QUOTES);
    $id_owner = $_POST['id_owner'];
    $comments = htmlspecialchars($_POST['comments'], ENT_QUOTES);
    $plate = htmlspecialchars($_POST['plate'], ENT_QUOTES);

    $immatriculation = $t_immatriculation.". ".$reg_num."/".$reg_year;


    # find infractions on this pirogue

    if ($_POST['new_old']) {

        # check duplicates [new records only!]
        $query = "SELECT id FROM artisanal.pirogue WHERE immatriculation='$immatriculation'";
        $nrows = pg_num_rows(pg_query($query));

        if ($nrows > 0) {
            top();
            print "<p>Une pirogue avec num&eacute;ro d'immatriculation <b>".$immatriculation."</b> existe d&eacute;j&egrave;.<br/>";
            print "<button type=\"button\" onClick=\"goBack()\">Retourner</button></p>";
            foot();
            die();
        }

        $query = "INSERT INTO artisanal.pirogue "
                . "(username, name, immatriculation, t_pirogue, length, id_owner, comments, photo_data_1, photo_data_2, photo_data_3, plate) "
                . " VALUES ('$username', '$name', '$immatriculation', '$t_pirogue', '$length', '$id_owner', '$comments', '$photo_data_1', '$photo_data_2', '$photo_data_3', '$plate') RETURNING id";

    } else {

        $query = "UPDATE artisanal.pirogue SET "
            . "datetime = now(), "
            . "username = '$username', "
            . "name = '".$name."', immatriculation = '".$immatriculation."', t_pirogue = '".$t_pirogue."', "
            . "photo_data_1 = '".$photo_data_1."', photo_data_2 = '".$photo_data_2."', photo_data_3 = '".$photo_data_3. "', "
            . "length = '".$length."', id_owner = '".$id_owner."', comments = '".$comments. "', plate = '".$plate. "' "
            . "WHERE id = '{".$_POST['id']."}' RETURNING id";

    }

    $query = str_replace('\'\'', 'NULL', $query);

    $rquery = pg_query($query);

    if(!$rquery) {
//        print $query;
        msg_queryerror();
    } else {
        $id = pg_fetch_row($rquery)[0];
        $query = "UPDATE infraction.infraction SET id_pirogue = '$id' WHERE immatriculation='$immatriculation' AND id_pirogue IS NULL";
        //print $query;
        if(!pg_query($query)) {
            print $query;
            msg_queryerror();
        } else {
            header("Location: ".$_SESSION['http_host']."/artisanal/administration/view_licenses_pirogue.php?source=$source&table=pirogues&action=show");
        }
    }

}

foot();
