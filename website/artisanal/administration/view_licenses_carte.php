<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'autorisation';

$username = $_SESSION['username'];

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['s_fish_name'] = str_replace('\'','',$_POST['s_fish_name']);
$_SESSION['filter']['f_year'] = $_POST['f_year'];
$_SESSION['filter']['f_active'] = $_POST['f_active'];
$_SESSION['filter']['s_pirogue'] = str_replace('\'','',$_POST['s_pirogue']);

if ($_GET['s_fish_name'] != "") {$_SESSION['filter']['s_fish_name'] = $_GET['s_fish_name'];}
if ($_GET['f_year'] != "") {$_SESSION['filter']['f_year'] = $_GET['f_year'];}
if ($_GET['f_active'] != "") {$_SESSION['filter']['f_active'] = $_GET['f_active'];}
if ($_GET['s_pirogue'] != "") {$_SESSION['filter']['s_pirogue'] = $_GET['s_pirogue'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    top();

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=autorisation&table=carte&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Ann&eacute;e validit&eacute;</b></td><td><b>Nom p&ecirc;cheur</b></td><td><b>Immatriculation pirogue</b></td><td><b>Autorisation Actif</b></td></tr>
    <tr>
    <td>
        <select name="f_year">
        <option value="extract(year from carte.date_v)" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT extract(year from date_v) FROM artisanal.carte WHERE date_v IS NOT NULL ORDER BY extract(year from date_v)");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_year']) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[0]."</option>";
            }
        }
    ?>
    </select></td>
    <td>
    <input type="text" size="20" name="s_fish_name" value="<?php echo $_SESSION['filter']['s_fish_name']?>"/>
    </td>
    <td>
    <input type="text" size="20" name="s_pirogue" value="<?php echo $_SESSION['filter']['s_pirogue']?>"/>
    </td>
    <td>
    <input type="radio" name="f_active" value="TRUE" <?php if($_SESSION['filter']['f_active'] == "TRUE"){ print "checked=\"checked\"";}?> />Oui<br/>
    <input type="radio" name="f_active" value="FALSE" <?php if($_SESSION['filter']['f_active'] == "FALSE"){ print "checked=\"checked\"";}?> />Non<br/>
    <input type="radio" name="f_active" value="license.active" <?php if($_SESSION['filter']['f_active'] == "license.active" OR $_SESSION['filter']['f_active'] == ""){ print "checked=\"checked\"";}?> />tous les deux<br/>
    </td>
    </tr>
    </table>
    <input type="submit" name="Filter" value="filter" />
    </fieldset>
    </form>

    <br/>

    <table>
    <tr align="center"><td></td>
    <td><b>Date et Utilisateur</b></td>
    <td><b>Num&eacute;ro de carte</b></td>
    <td><b>Ann&eacute;e validit&eacute;</b></td>
    <td><b>P&ecirc;cheur</b></td>
    <td><b>Autorisation</b></td>
    <td><b>Photo</b></td>
    <td><b>Pay&eacute;</b></td>
    <td><b>License Actif</b></td>
    <td><b>Infractions</b></td>
    </tr>

    <?php

    // fetch data

    #id, datetime, username, carte, id_fisherman, t_site, payment, receipt, date_d, date_f, id_license, carte_saisie ,

    if ($_SESSION['filter']['f_year'] != "" OR $_SESSION['filter']['s_fish_name'] != "" OR $_SESSION['filter']['s_pirogue'] != "" OR ($_SESSION['filter']['f_active'] != "" AND $_SESSION['filter']['f_active'] != "license.active")) {
        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_year'] != "" OR $_SESSION['filter']['s_fish_name'] != "" OR $_SESSION['filter']['s_pirogue'] != "" OR $_SESSION['filter']['f_active'] != "") {
            $query = "SELECT count(carte.id) FROM artisanal.carte "
                . "LEFT JOIN artisanal.license ON artisanal.license.id = artisanal.carte.id_license "
                . "WHERE license.active=".$_SESSION['filter']['f_active']." "
                . "AND (extract(year from carte.date_v) = ".$_SESSION['filter']['f_year'].") ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT carte.id, carte.datetime::date, carte.username, carte, extract(year from carte.date_v), id_fisherman, fisherman.first_name, fisherman.last_name, id_license, license, carte.paid, license.active, name, immatriculation, photo_data, "
            . " coalesce(similarity(artisanal.pirogue.immatriculation, '".$_SESSION['filter']['s_pirogue']."'),0) + "
            . " coalesce(similarity(artisanal.fisherman.first_name, '".$_SESSION['filter']['s_fish_name']."'),0) + "
            . " coalesce(similarity(artisanal.fisherman.last_name, '".$_SESSION['filter']['s_fish_name']."'),0) AS score"
            . " FROM artisanal.carte "
            . "LEFT JOIN artisanal.license ON artisanal.license.id = artisanal.carte.id_license "
            . "LEFT JOIN artisanal.fisherman ON artisanal.fisherman.id = artisanal.carte.id_fisherman "
            . "LEFT JOIN artisanal.pirogue ON artisanal.license.id_pirogue = artisanal.pirogue.id "
            . "WHERE license.active=".$_SESSION['filter']['f_active']." "
            . "AND (extract(year from carte.date_v) = ".$_SESSION['filter']['f_year'].") "
            . "ORDER BY score DESC,datetime DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT count(carte.id) FROM artisanal.carte";
            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT carte.id, carte.datetime::date, carte.username, carte, extract(year from carte.date_v), id_fisherman, fisherman.first_name, fisherman.last_name, id_license, license, carte.paid, license.active, name, immatriculation, photo_data "
            . " FROM artisanal.carte "
            . "LEFT JOIN artisanal.license ON artisanal.license.id = artisanal.carte.id_license "
            . "LEFT JOIN artisanal.fisherman ON artisanal.fisherman.id = artisanal.carte.id_fisherman "
            . "LEFT JOIN artisanal.pirogue ON artisanal.license.id_pirogue = artisanal.pirogue.id "
            . "WHERE license.active=".$_SESSION['filter']['f_active']." "
            . "AND (extract(year from carte.date_v) = ".$_SESSION['filter']['f_year'].") "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(carte.id) FROM artisanal.carte";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT carte.id, carte.datetime::date, carte.username, carte, extract(year from carte.date_v), id_fisherman, fisherman.first_name, fisherman.last_name, id_license, license, carte.paid, license.active, name, immatriculation, photo_data  "
        . " FROM artisanal.carte "
        . "LEFT JOIN artisanal.fisherman ON artisanal.fisherman.id = artisanal.carte.id_fisherman "
        . "LEFT JOIN artisanal.license ON artisanal.license.id = artisanal.carte.id_license "
        . "LEFT JOIN artisanal.pirogue ON artisanal.license.id_pirogue = artisanal.pirogue.id "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {
        # infractions
        $query_i = "SELECT infraction.id FROM infraction.infraction "
                . "WHERE id_fisherman_1 = '$results[5]' "
                . "OR id_fisherman_2 = '$results[5]' "
                . "OR id_fisherman_3 = '$results[5]' "
                . "OR id_fisherman_4 = '$results[5]' ";

        //print $query_i;

        $res_i = pg_num_rows(pg_query($query_i));

        if ($results[11] == 't') {
            $val = 'Oui';
        } else {
            $val = 'Non';
        }

        if ($results[10] == 't') {
            $paid = 'Oui';
        } else {
            $paid = 'Non';
        }


        if ($results[14] != '') {
            $photo = 'Oui';
        } else {
            $photo = 'Non';
        }


//        if ($val == 'Non') {
//            print "<tr align=\"center\" style=\"font-style: oblique;\">";
//        } else {
              print "<tr align=\"center\">";
//        }

        print "<td><a href=\"./view_carte.php?id=$results[0]\">Voir</a><br/>";
        if(right_write($_SESSION['username'],2,2)) {
          //if($results[11] == 'f' OR right_write($_SESSION['username'],2,1) OR $_SESSION['username'] == 'fcardiec') {
            print "<a href=\"./view_licenses_carte.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>";
            //}
          print "<a href=\"./view_licenses_carte.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";

        if($results[14] != '' and $results[11] == 't' and $results[10] == 't' ) {
            print "<br/><a href=\"./view_licenses_carte.php?source=$source&table=$table&action=imprimer&id=$results[0]\"><i class=\"material-icons\">local_printshop</i></a>";
        }
        }
        print "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td><b>#$results[3]</b></td><td nowrap>$results[4]</td><td><a href=\"./view_fisherman.php?id=$results[5]\">".strtoupper($results[7])."<br/>".ucfirst($results[6])."</a></td>"
        . "<td><a href=\"./view_license.php?id=$results[8]\"><b>$results[9]</b></br>$results[12] - $results[13]</a></td><td>$photo</td><td>$paid</td><td>$val</td><td>$res_i</td>";
    }
    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_licenses_carte.php?source=autorisation&table=carte&action=show&s_fish_name='.$_SESSION['filter']['s_fish_name'].'&f_active='.$_SESSION['filter']['f_active'].'&s_pirogue='.$_SESSION['filter']['s_pirogue'].'&f_year='.$_SESSION['filter']['f_year']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    top();

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT *, extract(year FROM date_v) FROM artisanal.carte WHERE id = '$id' ORDER BY datetime DESC";
    //print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    ?>
    <script>
    function toggle(checkboxID, toggleID) {
         var checkbox = document.getElementById(checkboxID);
         var toggle = document.getElementById(toggleID);
         updateToggle = checkbox.checked ? toggle.disabled=false : toggle.disabled=true;
         toggle.value = <?php print $results[9]; ?>;
    }
    </script>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old" id="new_old"
    <?php if(right_write($_SESSION['username'],2,1) != 1 AND $_SESSION['username'] != 'fcardiec') {
      print "onClick=\"toggle('new_old', 'date_v')\"";
    }
    ?>

    >
    <br/>
    <br/>
    <b>Ann&eacute;e validit&eacute;</b>
    <br/>
    <input type="text" size="4" name="date_v" id="date_v" value="<?php echo $results[9]; ?>"
    <?php if(right_write($_SESSION['username'],2,1) != 1 AND $_SESSION['username'] != 'fcardiec') {
      print "disabled";
    }
     ?> />
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
    <a href="./view_licenses_fisherman.php?source=artisanal&table=fisherman&action=edit&id=<?php print $results[4];?>"><i class="material-icons">edit</i>Modifier p&ecirc;cheur</a>
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
        if ($row[0] == $results[6]) {
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
    <input type="radio" name="paid" value="TRUE" <?php if ($results[8] == 't') {print "checked";} ?>/>Oui<br/>
    <input type="radio" name="paid" value="FALSE" <?php if ($results[8] != 't') {print "checked";} ?>/>Non<br/>
    <br/>
    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="hidden" value="<?php echo $results[3]; ?>" name="carte"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM artisanal.carte WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/administration/view_licenses_carte.php?source=$source&table=carte&action=show");
    }
    $controllo = 1;

}


if ($_POST['submit'] == "Enregistrer") {

    # id, datetime, username, carte, id_fisherman, payment, receipt, date_d, date_f, id_license, carte_saisie
    $date_v = '01-01-'.htmlspecialchars($_POST['date_v'], ENT_QUOTES);
    $id_fisherman = $_POST['id_fisherman'];
    $id_license = $_POST['id_license'];
    $paid = $_POST['paid'];
    $carte = $_POST['carte'];

    if ($_POST['new_old']) {

        # check duplicates
        $query = "SELECT first_name, last_name, license, extract(year FROM carte.date_v) FROM artisanal.carte "
                . "LEFT JOIN artisanal.license ON artisanal.carte.id_license = artisanal.license.id "
                . "LEFT JOIN artisanal.fisherman ON artisanal.carte.id_fisherman = artisanal.fisherman.id "
                . "WHERE id_fisherman='$id_fisherman' AND id_license='$id_license' AND carte.date_v = '$date_v'";

        $nrows = pg_num_rows(pg_query($query));
        $result = pg_fetch_row(pg_query($query));

        //print $query;

        if ($nrows > 0) {
            top();
            print "<p>Un carte de p&ecirc;cheur <b>$result[3]</b> avec pr&eacute;nom <b>$result[0]</b> et nom <b>$result[1]</b> et autorisation <b>$result[2]</b> existe d&eacute;j&egrave;.<br/>";
            print "<button type=\"button\" onClick=\"goBack()\">Retourner</button></p>";
            foot();
            die();
        }

        $query = "INSERT INTO artisanal.carte "
                . "(username, id_fisherman, date_v, id_license, paid, active) "
                . "VALUES ('$username', '$id_fisherman', '$date_v', '$id_license', '$paid', 'TRUE')";

    } else {

        # id, datetime, username, carte, id_fisherman, t_site, payment, receipt, date_d, date_f, id_license, carte_saisie

        $query = "UPDATE artisanal.carte SET "
                . "datetime = now(), "
                . "username = '$username', "
                . " carte = '".$carte."', id_fisherman = '".$id_fisherman."', date_v = '$date_v', "
                . " id_license = '".$id_license."', paid = '".$paid."', active = 'TRUE' "
                . " WHERE id = '{".$_POST['id']."}'";

    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/artisanal/administration/view_licenses_carte.php?source=$source&table=carte&action=show");
    }

}   else if ($_GET['action'] == 'imprimer') {

    $query = "SELECT carte.carte, extract(year from carte.date_v), t_coop.coop, pirogue.immatriculation, fisherman.first_name, fisherman.last_name, "
        . "bday, t_card.card, idcard, t_nationality.nationality, fisherman.photo_data "
        . "FROM artisanal.carte "
        . "LEFT JOIN artisanal.license ON artisanal.license.id = artisanal.carte.id_license "
        . "LEFT JOIN artisanal.fisherman ON artisanal.fisherman.id = artisanal.carte.id_fisherman "
        . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
        . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.license.id_pirogue "
        . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.fisherman.t_card "
        . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.fisherman.t_nationality "
        . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue "
        . "WHERE carte.id = '{".$_GET['id']."}'";

    //print $query;
    $r_query = pg_query($query);
    $results = pg_fetch_row($r_query);

    print_carte($results);

}

foot();
