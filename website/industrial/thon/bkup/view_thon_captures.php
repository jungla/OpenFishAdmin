<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'thon';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_s_navire'] = $_POST['f_s_navire'];
$_SESSION['filter']['f_s_year'] = $_POST['f_s_year'];
$_SESSION['filter']['f_id_species'] = $_POST['f_id_species'];

if ($_GET['f_s_navire'] != "") {$_SESSION['filter']['f_s_navire'] = $_GET['f_s_navire'];}
if ($_GET['f_s_year'] != "") {$_SESSION['filter']['f_s_year'] = $_GET['f_s_year'];}
if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=thon&table=captures&action=show" enctype="multipart/form-data">
    <fieldset>
    
    <table id="no-border">
    <tr>
    <td><b>Navire</b></td>
    <td><b>Ann&eacute;e lance</b></td>
    <td><b>Esp&eacute;c&eacute;</b></td>
    </tr>
    <tr>
    <td>
    <select name="f_s_navire">
        <option value="maree.navire">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT maree.navire FROM thon.lance "
                . "LEFT JOIN thon.maree ON thon.lance.id_maree = maree.id "
                . "WHERE navire IS NOT NULL "
                . "ORDER BY maree.navire ");
        while($row = pg_fetch_row($result)) {
            if ("'".$row[0]."'" == $_SESSION['filter']['f_s_navire']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">$row[0]</option>";
            } else {
                print "<option value=\"'$row[0]'\">$row[0]</option>";
            }
        }
        ?>
    </select>
    </td>
    <td>
    <select name="f_s_year">
        <option value="maree.year">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT maree.year FROM thon.maree "
                . "ORDER BY year");
        while($row = pg_fetch_row($result)) {
            if ("'".$row[0]."'" == $_SESSION['filter']['f_s_year']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">$row[0]</option>";
            } else {
                print "<option value=\"'$row[0]'\">$row[0]</option>";
            }
        }
        ?>
    </select>
    </td>
    
    <td>
    <select name="f_id_species">
        <option value="id_species" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species "
                . "FROM fishery.species  JOIN thon.captures ON fishery.species.id = thon.captures.id_species "
                . "WHERE species IS NOT NULL "
                . "ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        
        while($row = pg_fetch_row($result)) {
            if ("'".$row[0]."'" == $_SESSION['filter']['f_id_species']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
            } else {
                print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
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
    <td><b>Mar&eacute;e</b></td>
    <td><b>Lanc&eacute;</b></td>    
    <td><b>Rejete</b></td>
    <td><b>Esp&egrave;ce</b></td>
    <td><b>Taille</b> [kg]</td>
    <td><b>Poids</b> [t]</td>
    </tr>
   
    <?php
    
    // fetch data
    
    if ($_SESSION['filter']['f_s_navire'] != "" OR $_SESSION['filter']['f_id_species'] != "" OR $_SESSION['filter']['f_s_year'] != "" ) {
                   
        # id, username, id_maree, id_lance, rejete, id_species, taille, poids,
           
        $_SESSION['start'] = 0;
        
        $query = "SELECT count(captures.id) FROM thon.captures "
        . "LEFT JOIN thon.maree ON thon.captures.id_maree = thon.maree.id "
        . "WHERE maree.year=".$_SESSION['filter']['f_s_year']." "
        . "AND maree.navire=".$_SESSION['filter']['f_s_navire']." "
        . "AND (captures.id_species IS NULL OR captures.id_species=".$_SESSION['filter']['f_id_species'].") ";

        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT captures.id, captures.username, captures.datetime, captures.id_maree, maree.navire, maree.year, captures.id_lance, lance.date_c ,lance.heure_c ,rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids"
        . " FROM thon.captures "
        . "LEFT JOIN thon.maree ON thon.captures.id_maree = thon.maree.id "
        . "LEFT JOIN thon.lance ON thon.captures.id_lance = thon.lance.id "
        . "LEFT JOIN fishery.species ON thon.captures.id_species = fishery.species.id "
        . "WHERE maree.year=".$_SESSION['filter']['f_s_year']." "
        . "AND maree.navire=".$_SESSION['filter']['f_s_navire']." "
        . "AND (captures.id_species IS NULL OR captures.id_species=".$_SESSION['filter']['f_id_species'].") "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step"; 
        
    } else {
        $query = "SELECT count(captures.id) FROM thon.captures";
        $pnum = pg_fetch_row(pg_query($query))[0];
        
        $query = "SELECT captures.id, captures.username, captures.datetime, captures.id_maree, maree.navire, maree.year, captures.id_lance, lance.date_c ,lance.heure_c, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids "
        . " FROM thon.captures "
        . "LEFT JOIN thon.maree ON thon.captures.id_maree = thon.maree.id "
        . "LEFT JOIN thon.lance ON thon.captures.id_lance = thon.lance.id "
        . "LEFT JOIN fishery.species ON thon.captures.id_species = fishery.species.id "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";   
    }
    
    
    $r_query = pg_query($query);
    
    while ($results = pg_fetch_row($r_query)) {
        
        print "<tr align=\"center\">";
    
        print "<td>"
        . "<a href=\"./view_thon_captures.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_thon_captures.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td><a href=\"./view_maree.php?id=$results[3]&source=thon&table=maree&action=show\">$results[4]<br/>$results[5]</a></td><td><a href=\"./view_lance.php?id=$results[6]&source=thon&table=lance&action=show\">$results[7]<br/>$results[8]</a></td><td>$results[9]</td><td>".formatSpecies($results[11],$results[12],$results[13],$results[14])."</td>"
        . "<td>$results[15]</td><td>$results[16]</td></tr>";
    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_thon_captures.php?source=thon&table=captures&action=show&f_s_navire='.$_SESSION['filter']['f_s_navire'].'&f_s_year='.$_SESSION['filter']['f_s_year'].'&f_id_species='.$_SESSION['filter']['f_id_species']);
    
    $controllo = 1;
    
} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";
    
    // id, datetime, username, navire, country, year, port_d, port_a, date_d, date_a, ndays
    
    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT *  FROM thon.captures "
        . "WHERE captures.id = '$id'";
    
    #print $q_id;
    
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);
    
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Mar&eacute;e</b>
    <br/>
    <select id="maree" name="id_maree" onchange="menu_pop_lance('maree','lance','maree','lance','thon.lance','FALSE')">
    <?php
    $result = pg_query("SELECT DISTINCT maree.id, maree.navire, maree.year, maree.country FROM thon.captures "
            . "LEFT JOIN thon.maree ON thon.captures.id_maree = maree.id "
            . "ORDER BY maree.navire, maree.year");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[3]) {
            print "<option value='\"'$row[0]'\" selected=\"selected\">$row[1] ($row[3]) - $row[2]</option>";
        } else {
            print "<option value=\"'$row[0]'\">$row[1] ($row[3]) - $row[2]</option>";
        }
    }
    ?>
    </select>
    <br/>
    Vous ne pouvez pas trouver un maree? Ajoutez un nouveau <a href="input_form_thon.php?source=thon&table=maree">maree</a>.
    <br/>
    <b>Lanc&eacute;</b>
    <br/>
    <select id="lance" name="id_lance">
    <?php
    $result = pg_query("SELECT DISTINCT lance.id, lance.date_c, lance.heure_c FROM thon.lance 
            WHERE lance.id_maree = '$results[3]' ORDER BY lance.date_c, lance.heure_c");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[4]) {
            print "<option value=\"'$row[0]'\" selected=\"selected\">$row[1] - $row[2]</option>";
        } else {
            print "<option value=\"'$row[0]'\">$row[1] - $row[2]</option>";
        }
    }
    ?>
    </select>
    <br/>
    Vous ne pouvez pas trouver un lance? Ajoutez un nouveau <a href="input_form_thon.php?source=thon&table=lance">lance</a>.
    <br/>
    <br/>
    <b>Rejete</b>
    <br/>
    Oui<input type="radio" name="rejete" value="TRUE" <?php if($results[5] == 't') {print "checked";} ?>/><br/>
    No<input type="radio" name="rejete" value="FALSE" <?php if($results[5] == 'f') {print "checked";} ?>/>
    <br/>
    <br/>
    <b>Esp&egrave;ce</b> [FAO code, nome commun, nom scientifique]
    <br/>
    <select id="species" name="id_species">
    <?php
    $result = pg_query("SELECT DISTINCT id, FAO, francaise, family, genus, species FROM fishery.species WHERE FAO IS NOT NULL ORDER BY FAO");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[6]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
        } else {
            print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Taille</b> [kg]
    <br/>
    <input type="text" size="10" name="taille" value="<?php echo $results[7]; ?>"/>
    <br/>
    <br/>
    <b>Poids</b> [tonnes]
    <br/>
    <input type="text" size="10" name="poids" value="<?php echo $results[8];?>" />
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
    $query = "DELETE FROM thon.captures WHERE id = '$id'";
    
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/thon/view_thon_captures.php?source=$source&table=captures&action=show");
    }
    $controllo = 1;
}


if ($_POST['submit'] == "Enregistrer") {
 
    $id_maree = str_replace("'","",$_POST['id_maree']); 
    $id_lance = str_replace("'","",$_POST['id_lance']); 
    $rejete = $_POST['rejete']; 
    $id_species = $_POST['id_species']; 
    $taille = comma2dot($_POST['taille']); 
    $poids = comma2dot($_POST['poids']); 
    
    if ($_POST['new_old']) {
        #id_maree, date_c, heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, comment, st_x(location), st_y(location)
        $query = "INSERT INTO thon.captures "
                . "(username, datetime, id_maree, id_lance, rejete, id_species, taille, poids) "
                . "VALUES ('$username', now(), '$id_maree', '$id_lance', '$rejete', '$id_species', '$taille', '$poids')";

    } else {
        $query = "UPDATE thon.captures SET "
            . "username = '$username', datetime = now(), "
            . "id_maree = '".$id_maree."', id_lance = '".$id_lance."', "
            . "rejete = '".$_POST['rejete']."', id_species = '".$id_species."', "
            . "taille = '".$taille."', poids = '".$poids."' "
            . "WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    #print $query;

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    }

//        print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/thon/view_thon_captures.php?source=$source&table=captures&action=show");
    
}

foot();
