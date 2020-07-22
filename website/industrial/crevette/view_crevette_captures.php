<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'crevette';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_id_navire'] = $_POST['f_id_navire'];
$_SESSION['filter']['f_s_year'] = $_POST['f_s_year'];

if ($_GET['f_id_navire'] != "") {$_SESSION['filter']['f_id_navire'] = $_GET['f_id_navire'];}
if ($_GET['f_s_year'] != "") {$_SESSION['filter']['f_s_year'] = $_GET['f_s_year'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=crevette&table=lance&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border">
    <tr>
    <td><b>Navire</b></td>
    </tr>
    <tr>
    <td>
    <select name="f_id_navire">
    <option value="id_navire" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT lance.id_navire, navire FROM crevette.capture LEFT JOIN crevette.lance ON crevette.lance.id = crevette.capture.id_lance LEFT JOIN vms.navire ON vms.navire.id = crevette.lance.id_navire ORDER BY navire");
        while($row = pg_fetch_row($result)) {
        if ("'".$row[0]."'" == $_SESSION['filter']['f_id_navire']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">$row[1]</option>";
            } else {
                print "<option value=\"'$row[0]'\">$row[1]</option>";
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

    <table>
    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Navire</b></td>
    <td><b>Date et Lanc&eacute;</b></td>
    <td><b>Espece</b></td>
    <td><b>Taille</b></td>
    <td><b>Poids</b></td>
    </tr>

    <?php

    // fetch data

    if ($_SESSION['filter']['f_id_navire'] != "") {

        # id_maree, date_c, heure_c, lance, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment ,

        $_SESSION['start'] = 0;

        $query = "SELECT count(capture.id) FROM crevette.capture "
        . "LEFT JOIN crevette.lance ON crevette.lance.id = crevette.capture.id_lance "
        . "WHERE lance.id_navire=".$_SESSION['filter']['f_id_navire']." ";

        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT capture.id, capture.username, capture.datetime::date, navire, id_navire, lance.date_l, lance.lance, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_taille.taille, poids FROM crevette.capture "
        . "LEFT JOIN crevette.lance ON crevette.lance.id = crevette.capture.id_lance "
        . "LEFT JOIN vms.navire ON vms.navire.id = crevette.lance.id_navire "
        . "LEFT JOIN crevette.t_zone ON crevette.t_zone.id = crevette.lance.t_zone "
        . "LEFT JOIN crevette.t_taille ON crevette.t_taille.id = crevette.capture.t_taille "
        . "LEFT JOIN fishery.species ON crevette.capture.id_species = fishery.species.id "
        . "WHERE lance.id_navire=".$_SESSION['filter']['f_id_navire']." "
        . "ORDER BY capture.datetime DESC OFFSET $start LIMIT $step";

    } else {
        $query = "SELECT count(capture.id) FROM crevette.capture";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT capture.id, capture.username, capture.datetime::date, navire, id_navire, lance.date_l, lance.lance, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_taille.taille, poids FROM crevette.capture "
        . "LEFT JOIN crevette.lance ON crevette.lance.id = crevette.capture.id_lance "
        . "LEFT JOIN vms.navire ON vms.navire.id = crevette.lance.id_navire "
        . "LEFT JOIN crevette.t_zone ON crevette.t_zone.id = crevette.lance.t_zone "
        . "LEFT JOIN crevette.t_taille ON crevette.t_taille.id = crevette.capture.t_taille "
        . "LEFT JOIN fishery.species ON crevette.capture.id_species = fishery.species.id "
        . "ORDER BY capture.datetime DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {


        print "<tr align=\"center\">";

        print "<td>"
            . "<a href=\"./view_crevette_captures.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_crevette_captures.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
            . "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td nowrap><a href=\"../view_navire.php?source=vms&id=$results[4]\">$results[3]</a></td>"
            . "<td>$results[5]<br/>$results[6]</td><td>".formatSpecies($results[7],$results[8],$results[9],$results[10])."</td><td>$results[11]</td><td>$results[12]</td>";

    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_crevette_captures.php?source=crevette&table=captures&action=show&f_id_navire='.$_SESSION['filter']['f_id_navire']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    // id, datetime, username, id_maree, date_c, heure_c, lance, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment,

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT capture.id, capture.username, capture.datetime, id_navire, lance.date_l, lance.lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_taille, poids FROM crevette.capture "
            . "LEFT JOIN fishery.species ON fishery.species.id = crevette.capture.id_species "
            . "LEFT JOIN crevette.lance ON crevette.lance.id = crevette.capture.id_lance "
            . "WHERE capture.id = '$id'";

    //print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    ?>

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Navire</b>
    <br/>
    <select id="id_navire" name="id_navire" onchange="menu_pop_1('id_navire','date_l','id_navire','date_l','crevette.lance')">
    <?php
    $result = pg_query("SELECT DISTINCT id_navire, navire FROM crevette.lance "
            . "LEFT JOIN vms.navire ON crevette.lance.id_navire = vms.navire.id "
            . "ORDER BY navire");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[3]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Date lance</b>
    <br/>
    <select id="date_l" name="date_l" onchange="menu_pop_2('id_navire','date_l','lance','id_navire','date_l','lance','crevette.lance')">
    <?php
    $result = pg_query("SELECT DISTINCT date_l FROM crevette.lance "
            . "WHERE id_navire = '$results[3]' ORDER BY date_l");
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
    <b>Lance</b>
    <br/>
    <select id="lance" name="lance" >
    <?php
    $result = pg_query("SELECT DISTINCT lance FROM crevette.lance WHERE id_navire = '$results[3]' AND date_l = '$results[4]' ORDER BY lance");
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
    Vous ne pouvez pas trouver un lance? Ajoutez un nouveau <a href="input_crevette.php?source=crevette&table=lance">lance</a>.
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select id="species" name="id_species">
    <?php
    $result = pg_query("SELECT id, francaise FROM fishery.species WHERE category = 'crevette' ORDER BY francaise");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[6]) {
            print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
        } else {
            print "<option value=\"$row[0]\">$row[1]</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Taille</b>
    <br/>
    <select name="t_taille">
    <option  value="">Aucun</option>
    <?php
        $result = pg_query("SELECT id, taille FROM crevette.t_taille ORDER BY id");
        while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[11]) {
                print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
            } else {
                print "<option value=\"$row[0]\">$row[1]</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Poids</b> [kg]
    <br/>
    <input type="text" size="5" name="poids" value="<?php echo $results[12];?>" />
    <br/>
    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>


    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM crevette.lance WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/crevette/view_crevette_captures.php?source=$source&table=lance&action=show");
    }
    $controllo = 1;
}

if ($_POST['submit'] == "Enregistrer") {

    $id_navire = $_POST['id_navire'];
    $date_l = $_POST['date_l'];
    $lance = $_POST['lance'];

    $id_species = $_POST['id_species'];
    $t_taille = $_POST['t_taille'];
    $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);

    $q_id = "SELECT id FROM crevette.lance "
            . "WHERE id_navire = '$id_navire' AND date_l = '$date_l' AND lance = '$lance'";
    $id_lance = pg_fetch_row(pg_query($q_id))[0];

    if ($_POST['new_old']) {
        #navire, country, port_d, port_a, date_d, date_a, ndays, date_c, heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, comment, st_x(location), st_y(location)
        $query = "INSERT INTO crevette.capture "
                . "(username, datetime, id_lance, id_species, t_taille, poids) "
                . "VALUES ('$username', now(), '$id_lance', '$id_species', '$t_taille', '$poids')";
    } else {
        $query = "UPDATE crevette.capture SET "
            . "username = '$username', datetime = now(), "
            . "id_lance = '".$id_lance."', id_species = '".$id_species."', t_taille = '".$_POST['t_taille']."', "
            . "poids = '".$poids."' "
            . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
//        print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/crevette/view_crevette_captures.php?source=$source&table=lance&action=show");
    }


}

foot();
