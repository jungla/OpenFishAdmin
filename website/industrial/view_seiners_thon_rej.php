<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'seiners';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_s_maree'] = $_POST['f_s_maree'];
$_SESSION['filter']['f_t_zee'] = $_POST['f_t_zee'];
$_SESSION['filter']['f_t_type'] = $_POST['f_t_type'];
$_SESSION['filter']['f_id_species'] = $_POST['f_id_species'];
$_SESSION['filter']['f_t_categorie'] = $_POST['f_t_categorie'];

if ($_GET['f_s_maree'] != "") {$_SESSION['filter']['f_s_maree'] = $_GET['f_s_maree'];}
if ($_GET['f_t_zee'] != "") {$_SESSION['filter']['f_t_zee'] = $_GET['f_t_zee'];}
if ($_GET['f_t_type'] != "") {$_SESSION['filter']['f_t_type'] = $_GET['f_t_type'];}
if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}
if ($_GET['f_t_categorie'] != "") {$_SESSION['filter']['f_t_categorie'] = $_GET['f_t_categorie'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=seiners&table=thon_rej&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Maree</b></td><td><b>ZEE</b></td><td><b>Type calee</b></td><td><b>Espece</b></td><td><b>Categorie</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <select name="f_t_zee">
        <option value="t_zee" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT thon_rejete.t_zee, t_zee.zee FROM seiners.thon_rejete LEFT JOIN seiners.t_zee ON seiners.t_zee.id = seiners.thon_rejete.t_zee WHERE t_zee IS NOT NULL ORDER BY t_zee.zee ");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_zee']) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
        }
    ?>
    </select>
    </td>
    <td>
    <select name="f_t_type">
        <option value="t_type" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT id, type FROM seiners.t_type ORDER BY type");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_type']) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
        }
    ?>
    </select>
    </td>
    <td>
    <select name="f_id_species">
        <option value="f_id_species" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN seiners.thon_rejete ON fishery.species.id = seiners.thon_rejete.id_species  ORDER BY  fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ("'".$row[0]."'" == $_SESSION['filter']['f_id_species']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"'$row[0]'\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    </td>
    <td>
    <select name="f_t_categorie">
        <option value="t_categorie" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT t_categorie.id, t_categorie.categorie FROM seiners.thon_rejete LEFT JOIN seiners.t_categorie ON seiners.t_categorie.id = seiners.thon_rejete.t_categorie  WHERE categorie IS NOT NULL ORDER BY categorie");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_categorie']) {
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
    <td><b>Route</b></td>
    <td><b>Maree</b></td>
    <td><b>ZEE</b></td>
    <td><b># Calee</b></td>
    <td><b>Type calee</b></td>
    <td><b>Heure debut/fin calee</b></td>
    <td><b>Vitesse, direction et profondeur</b></td>
    <td><b>Espece</b></td>
    <td><b>Categorie</b></td>
    <td><b>Raison</b></td>
    <td><b>Poids</b></td>
    <td><b>Monte</b></td>
    <td><b>Photo/Video</b></td>
    <td><b>Remarque</b></td>
    <td><b>GPS</b></td>
    </tr>
    <?php

    // fetch data

    #thon_rejete.id, thon_rejete.datetime, thon_rejete.username, thon_rejete.maree, t_zee, n_calee, t_type, id_route, n_route, l_route, h_d, h_c, h_f, vitesse, direction, d_max, id_species, t_categorie, t_raison, poids, monte, photo, remarque


    if ($_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_t_zee'] != "" OR $_SESSION['filter']['f_t_type'] != "" OR $_SESSION['filter']['f_id_species'] != "" OR $_SESSION['filter']['f_t_categorie'] != "") {

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(thon_rejete.id) FROM seiners.thon_rejete "
                . "WHERE (thon_rejete.t_zee IS NULL OR t_zee=".$_SESSION['filter']['f_t_zee'].") "
                . "AND (thon_rejete.t_type IS NULL OR t_type=".$_SESSION['filter']['f_t_type'].") "
                . "AND (thon_rejete.id_species IS NULL OR id_species=".$_SESSION['filter']['f_id_species'].") "
                . "AND (thon_rejete.t_categorie IS NULL OR t_categorie=".$_SESSION['filter']['f_t_categorie'].") ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT thon_rejete.id, thon_rejete.datetime::date, thon_rejete.username, thon_rejete.maree, t_zee.zee, n_calee, t_type.type, id_route, h_d, h_c, h_f, vitesse, direction, d_max, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_categorie.categorie, t_raison.raison, poids, monte, photo, remarque, st_x(route.location), st_y(route.location), route.date, route.time,  "
            . " coalesce(similarity(seiners.thon_rejete.maree, '".$_SESSION['filter']['f_s_maree']."'),0)  AS score"
            . " FROM seiners.thon_rejete "
            . "LEFT JOIN seiners.t_zee ON seiners.t_zee.id = seiners.thon_rejete.t_zee "
            . "LEFT JOIN seiners.t_type ON seiners.t_type.id = seiners.thon_rejete.t_type "
            . "LEFT JOIN fishery.species ON fishery.species.id = seiners.thon_rejete.id_species "
            . "LEFT JOIN seiners.t_categorie ON seiners.t_categorie.id = seiners.thon_rejete.t_categorie "
            . "LEFT JOIN seiners.t_raison ON seiners.t_raison.id = seiners.thon_rejete.t_raison "
            . "LEFT JOIN seiners.route ON seiners.route.id = seiners.thon_rejete.id_route "
            . "WHERE (thon_rejete.t_zee IS NULL OR t_zee=".$_SESSION['filter']['f_t_zee'].") "
            . "AND (thon_rejete.t_type IS NULL OR t_type=".$_SESSION['filter']['f_t_type'].") "
            . "AND (thon_rejete.id_species IS NULL OR id_species=".$_SESSION['filter']['f_id_species'].") "
            . "AND (thon_rejete.t_categorie IS NULL OR t_categorie=".$_SESSION['filter']['f_t_categorie'].") "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT count(thon_rejete.id) FROM seiners.thon_rejete "
            . "WHERE (t_zee IS NULL OR t_zee=".$_SESSION['filter']['f_t_zee'].") "
            . "AND (t_type IS NULL OR t_type=".$_SESSION['filter']['f_t_type'].") "
            . "AND (id_species IS NULL OR id_species=".$_SESSION['filter']['f_id_species'].") "
            . "AND (t_categorie IS NULL OR t_categorie=".$_SESSION['filter']['f_t_categorie'].") ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT thon_rejete.id, thon_rejete.datetime::date, thon_rejete.username, thon_rejete.maree, t_zee.zee, n_calee, t_type.type, id_route, h_d, h_c, h_f, vitesse, direction, d_max, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_categorie.categorie, t_raison.raison, poids, monte, photo, remarque, st_x(route.location), st_y(route.location), route.date, route.time "
            . " FROM seiners.thon_rejete "
            . "LEFT JOIN seiners.t_zee ON seiners.t_zee.id = seiners.thon_rejete.t_zee "
            . "LEFT JOIN seiners.t_type ON seiners.t_type.id = seiners.thon_rejete.t_type "
            . "LEFT JOIN fishery.species ON fishery.species.id = seiners.thon_rejete.id_species "
            . "LEFT JOIN seiners.t_categorie ON seiners.t_categorie.id = seiners.thon_rejete.t_categorie "
            . "LEFT JOIN seiners.t_raison ON seiners.t_raison.id = seiners.thon_rejete.t_raison "
            . "LEFT JOIN seiners.route ON seiners.route.id = seiners.thon_rejete.id_route "
            . "WHERE (t_zee IS NULL OR t_zee=".$_SESSION['filter']['f_t_zee'].") "
            . "AND (t_type IS NULL OR t_type=".$_SESSION['filter']['f_t_type'].") "
            . "AND (id_species IS NULL OR id_species=".$_SESSION['filter']['f_id_species'].") "
            . "AND (t_categorie IS NULL OR t_categorie=".$_SESSION['filter']['f_t_categorie'].") "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
        }
    } else {

        $query = "SELECT count(thon_rejete.id) FROM seiners.thon_rejete";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT thon_rejete.id, thon_rejete.datetime::date, thon_rejete.username, thon_rejete.maree, t_zee.zee, n_calee, t_type.type, id_route, h_d, h_c, h_f, vitesse, direction, d_max, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_categorie.categorie, t_raison.raison, poids, monte, photo, remarque, st_x(route.location), st_y(route.location), route.date, route.time  "
        . " FROM seiners.thon_rejete "
        . "LEFT JOIN seiners.t_zee ON seiners.t_zee.id = seiners.thon_rejete.t_zee "
        . "LEFT JOIN seiners.t_type ON seiners.t_type.id = seiners.thon_rejete.t_type "
        . "LEFT JOIN fishery.species ON fishery.species.id = seiners.thon_rejete.id_species "
        . "LEFT JOIN seiners.t_categorie ON seiners.t_categorie.id = seiners.thon_rejete.t_categorie "
        . "LEFT JOIN seiners.t_raison ON seiners.t_raison.id = seiners.thon_rejete.t_raison "
        . "LEFT JOIN seiners.route ON seiners.route.id = seiners.thon_rejete.id_route "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";

    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_seiners_thon_rej.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_seiners_thon_rej.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td><a href=\"./view_route.php?id=$results[7]\">$results[27] $results[28]</a></td><td>$results[3]</td><td>$results[4]</td>"
        . "<td>$results[5]</td><td>$results[6]</td><td>$results[8] $results[9] $results[10]</td><td>$results[11]<br/>$results[12]<br/>$results[13]</td><td>".formatSpecies($results[15],$results[16],$results[17],$results[18])."</td><td>$results[19]</td><td>$results[20]</td><td>$results[21]</td><td>$results[22]</td><td>$results[23]</td><td>$results[24]</td><td><a href=\"view_point.php?X=$results[25]&Y=$results[26]\">".round($results[25],3)."E ".round($results[26],3)."N</td></tr>";
    }
    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_seiners_thon_rej.php?source=seiners&table=thon_rej&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_t_zee='.$_SESSION['filter']['f_t_zee'].'&f_t_type='.$_SESSION['filter']['f_t_type'].'&f_id_species='.$_SESSION['filter']['f_id_species'].'&f_t_categorie='.$_SESSION['filter']['f_t_categorie']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    #thon_rejete.id, thon_rejete.datetime, thon_rejete.username, thon_rejete.maree, t_zee, n_calee, t_type, id_route, n_route, l_route, h_d, h_c, h_f, vitesse, direction, d_max, id_species, t_categorie, t_raison, poids, monte, photo, remarque

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT thon_rejete.id, thon_rejete.datetime, thon_rejete.username, thon_rejete.maree, t_zee, n_calee, t_type, id_route, h_d, h_c, h_f, vitesse, direction, d_max, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, t_categorie, t_raison, poids, monte, photo, remarque, st_x(route.location), st_y(route.location), route.date, route.time "
        . " FROM seiners.thon_rejete "
        . "LEFT JOIN fishery.species ON fishery.species.id = seiners.thon_rejete.id_species "
        . "LEFT JOIN seiners.route ON seiners.route.id = seiners.thon_rejete.id_route "
        . "WHERE thon_rejete.id = '$id'";

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Maree</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','seiners.route')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM seiners.route ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[3]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Route</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','seiners.route')">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT date FROM seiners.route  WHERE maree = '$results[3]' ORDER BY date");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[27]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <select id="time" name="time" >
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT time FROM seiners.route WHERE maree = '$results[3]' AND date = '$results[27]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[28]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>ZEE</b>
    <br/>
    <select name="t_zee">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, zee FROM seiners.t_zee ORDER BY zee");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[4]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Numero Calee</b>
    <br/>
    <input type="text" size="20" name="n_calee" value="<?php echo $results[5]; ?>"/>
    <br/>
    <br/>
    <b>Type peche</b>
    <br/>
    <select name="t_type">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, type FROM seiners.t_type ORDER BY type");
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
    <b>Heure de debut</b>
    <br/>
    <input type="time" size="20" name="h_d" value="<?php echo $results[8]; ?>"/>
    <br/>
    <br/>
    <b>Fin de coulissage</b>
    <br/>
    <input type="time" size="20" name="h_c" value="<?php echo $results[9]; ?>"/>
    <br/>
    <br/>
    <b>Heure de fin</b>
    <br/>
    <input type="time" size="20" name="h_f" value="<?php echo $results[10]; ?>"/>
    <br/>
    <br/>
    <b>Vitesse Courant</b> (nd)
    <br/>
    <input type="text" size="10" name="vitesse" value="<?php echo $results[11]; ?>"/>
    <br/>
    <br/>
    <b>Direction Courant</b>
    <br/>
    <input type="text" size="10" name="direction" value="<?php echo $results[12]; ?>"/>
    <br/>
    <br/>
    <b>Profondeur maximale</b> (m)
    <br/>
    <input type="text" size="10" name="d_max" value="<?php echo $results[13]; ?>"/>
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN seiners.thon_rejete ON fishery.species.id = seiners.thon_rejete.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[14]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>

    <br/>
    <br/>
    <b>Categorie taille</b>
    <br/>
    <select name="t_categorie">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, categorie FROM seiners.t_categorie ORDER BY categorie");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[19]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Raison rejet</b>
    <br/>
    <select name="t_raison">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, raison FROM seiners.t_raison ORDER BY raison");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[20]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select><br/>
    <br/>
    <b>Poids</b> (kg)
    <br/>
    <input type="text" size="10" name="poids" value="<?php echo $results[21];?>" />
    <br/>
    <br/>
    <b>Mont&eacute; sur pont</b>
    <br/>
    <?php
    if($results[22]=="t") {
        print "<input type=\"radio\" name=\"monte\" value=\"TRUE\" checked=\"checked\"/>Oui<br/>";
        print "<input type=\"radio\" name=\"monte\" value=\"FALSE\" />Non";
    } else {
        print "<input type=\"radio\" name=\"monte\" value=\"TRUE\" />Oui<br/>";
        print "<input type=\"radio\" name=\"monte\" value=\"FALSE\" checked=\"checked\"/>Non";
    }
    ?><br/>
    <br/>
    <b>Photo/Video</b>
    <br/>
    <input type="text" size="30" name="photo" value="<?php echo $results[23];?>" />
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" value="<?php echo $results[24];?>" />
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
    $query = "DELETE FROM seiners.thon_rejete WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_seiners_thon_rej.php?source=$source&table=thon_rej&action=show");
    }
    $controllo = 1;

}

if ($_POST['submit'] == "Enregistrer") {

#thon_rejete.id, thon_rejete.datetime, thon_rejete.username, thon_rejete.maree, t_zee, n_calee, t_type, id_route, n_route,
#l_route, h_d, h_c, h_f, vitesse, direction, d_max, id_species, t_categorie, t_raison, poids, monte, photo, remarque

    $username = $_SESSION['username'];
    $maree = $_POST['maree'];
    $t_zee = $_POST['t_zee'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $n_calee = $_POST['n_calee'];
    $t_type = $_POST['t_type'];
    $h_d = $_POST['h_d'];
    $h_c = $_POST['h_c'];
    $h_f = $_POST['h_f'];
    $vitesse = $_POST['vitesse'];
    $direction = $_POST['direction'];
    $d_max = $_POST['d_max'];
    $t_raison = $_POST['t_raison'];
    $id_species = $_POST['id_species'];
    $t_categorie = $_POST['t_categorie'];
    $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);
    $monte = $_POST['monte'];
    $photo = $_POST['photo'];
    $remarque = htmlspecialchars($_POST['remarque'],ENT_QUOTES);

    $q_id = "SELECT id FROM seiners.route WHERE maree = '$maree' AND date = '$date' AND time = '$time'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    if ($_POST['new_old']) {

    #thon_rejete.id, thon_rejete.datetime, thon_rejete.username, thon_rejete.maree, t_zee, n_calee, t_type, id_route, n_route,
    #l_route, h_d, h_c, h_f, vitesse, direction, d_max, id_species, t_categorie, t_raison, poids, monte, photo, remarque

    $query = "INSERT INTO seiners.thon_rejete "
            . "(username, datetime, maree, t_zee, n_calee, t_type, id_route, h_d, h_c, h_f, vitesse, direction, d_max, t_raison, id_species, t_categorie, poids, monte, photo, remarque) "
            . "VALUES ('$username', now(), '$maree', '$t_zee', '$n_calee', '$t_type', '$id_route', '$h_d', '$h_c', '$h_f', '$vitesse', '$direction', '$d_max', '$t_raison', '$id_species', '$t_categorie', '$poids', '$monte', '$photo', '$remarque')";

    } else {
        $query = "UPDATE seiners.thon_rejete SET "
            . "username = '$username', datetime = now(), "
            . "maree = '$maree', t_zee = '$t_zee', n_calee = '$n_calee', t_type = '$t_type', id_route = '$id_route', "
            . "h_d = '$h_d', h_c = '$h_c', h_f = '$h_f', vitesse = '$vitesse', direction = '$direction', "
            . "d_max = '$d_max', t_raison = '$t_raison', id_species = '$id_species', t_categorie = '$t_categorie', "
            . "poids = '$poids', monte = '$monte', photo = '$photo', remarque = '$remarque'"
            . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);
    print $query;
    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_seiners_thon_rej.php?source=$source&table=$table&action=show");
    }
}

foot();
