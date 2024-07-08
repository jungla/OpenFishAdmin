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

if ($_GET['f_s_navire'] != "") {$_SESSION['filter']['f_s_navire'] = $_GET['f_s_navire'];}
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
    <form method="post" action="<?php echo $self;?>?source=thon&table=maree&action=show" enctype="multipart/form-data">
    <fieldset>
    
    <table id="no-border">
    <tr>
    <td><b>Navire</b></td>
    <td><b>Ann&eacute;e maree</b></td>
    </tr>
    <tr>
    <td>
    <select name="f_s_navire">
        <option value="navire" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT navire FROM thon.maree WHERE navire IS NOT NULL ORDER BY navire");
        
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
        <option value="year" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT year FROM thon.maree WHERE year IS NOT NULL ORDER BY year");
        
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
    <td><b>Nationalite Navire</b></td>
    <td><b>Ann&eacute;e maree</b></td>    
    <td><b>Port et date depart</b></td>    
    <td><b>Port et date arrive</b></td>    
    <td><b>Lanc&eacute;</b></td> 
    <td><b>Fiche entr&eacute;e/sortie</b></td> 
    </tr>
    
    <?php
    
    // fetch data
    
    if ($_SESSION['filter']['f_s_navire'] != "" OR $_SESSION['filter']['f_s_year'] != "" ) {
    
        # id, username, navire, country, port_d, port_a, date_d, date_a, date_c, heure_c, ndays, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, navire, taille, poids, comment
        
        $_SESSION['start'] = 0;
        
        if ($_SESSION['filter']['f_s_year'] != "") { 
            $query = "SELECT count(maree.id) FROM thon.maree "
                . "WHERE year=".$_SESSION['filter']['f_s_year']." ";
            
            $pnum = pg_fetch_row(pg_query($query))[0];
            
            $query = "SELECT maree.id, maree.username, maree.datetime, navire, country, year, port_d, date_d, port_a, date_a, "
            . " coalesce(similarity(thon.maree.navire, '".$_SESSION['filter']['navire']."'),0) AS score"
            . " FROM thon.maree "
            . "WHERE year=".$_SESSION['filter']['f_s_year']." "
            . "AND navire=".$_SESSION['filter']['f_s_navire']." "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";
            
        } else {
            $query = "SELECT count(maree.id) FROM thon.maree "
            . "WHERE navire=".$_SESSION['filter']['f_s_navire']." ";
            
            $pnum = pg_fetch_row(pg_query($query))[0];
            
            $query = "SELECT maree.id, maree.username, maree.datetime, navire, country, year, port_d, date_d, port_a, date_a"
            . " FROM thon.maree "
            . "WHERE navire=".$_SESSION['filter']['f_s_navire']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step"; 
        }
    } else {
        $query = "SELECT count(maree.id) FROM thon.maree";
        $pnum = pg_fetch_row(pg_query($query))[0];
        
        $query = "SELECT maree.id, maree.username, maree.datetime, navire, country, year, port_d, date_d, port_a, date_a"
        . " FROM thon.maree "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";   
    }
    
    #print $query;
    
    $r_query = pg_query($query);
    
    while ($results = pg_fetch_row($r_query)) {
        
        print "<tr align=\"center\">";
    
        print "<td>"
        . "<a href=\"./view_maree.php?source=$source&table=$table&id=$results[0]\">Voir</a><br/>"
        . "<a href=\"./view_thon_maree.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_thon_maree.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td>$results[3]</td><td>$results[4]</td><td>$results[5]</td><td>$results[6]<br/>$results[7]</td><td>$results[8]<br/>$results[9]</td><td><a href=\"./view_thon_lance.php?id_maree=$results[0]&source=thon&table=lance&action=show\">Lanc&eacute;</a></td><td><a href=\"./view_entree.php?id=$results[0]\">Entree</a><br/><a href=\"./view_sortie.php?id=$results[0]\">Sortie</a></td></tr>";
    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_thon_maree.php?source=thon&table=maree&action=show&f_s_year='.$_SESSION['filter']['f_s_year'].'&f_s_navire='.$_SESSION['filter']['f_s_navire']);
    
    $controllo = 1;
    
} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";
    
    // id, datetime, username, navire, country, year, port_d, port_a, date_d, date_a, ndays
    
    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT *  FROM thon.maree "
        . "WHERE maree.id = '$id'";
    
    #print $q_id;
    
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);
    
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Nom navire</b>
    <br/>
    <input type="text" size="20" name="navire" value="<?php echo $results[3]; ?>"/>
    <br/>
    <br/>
    <b>Nationalit&eacute; navire</b>
    <br/>
    <input type="text" size="30" name="country" value="<?php echo $results[4];?>" />
    <br/>
    <br/>
    <b>Ann&eacute;e maree</b>
    <br/>
    <input type="text" size="6" name="year" value="<?php echo $results[5];?>" />
    <br/>
    <br/>
    <b>Port depart</b>
    <br/>
    <input type="text" size="30" name="port_d" value="<?php echo $results[6];?>" />
    <br/>
    <br/>
    <b>Date depart</b> [mm/jj/aaaa]
    <br/>
    <input type="date" size="15" name="date_d" value="<?php echo $results[7];?>" />
    <br/>
    <br/>
    <b>Port arrive</b>
    <br/>
    <input type="date" size="30" name="port_a" value="<?php echo $results[8];?>" />
    <br/>
    <br/>
    <b>Date arrive</b> [mm/jj/aaaa]
    <br/>
    <input type="text" size="15" name="date_a" value="<?php echo $results[9];?>" />
    <br/>
    <br/>
    <b>Numero de jours en mer</b>
    <br/>
    <input type="text" size="5" name="ndays" value="<?php echo $results[10];?>" />
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
    $query = "DELETE FROM thon.maree WHERE id = '$id'";
    
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/thon/view_thon_maree.php?source=$source&table=maree&action=show");
    }
    $controllo = 1;
}


if ($_POST['submit'] == "Enregistrer") {
    
    $navire = $_POST['navire']; 
    $country = $_POST['country']; 
    $year = $_POST['year'];
    $port_d = $_POST['port_d']; 
    $port_a = $_POST['port_a']; 
    $date_d = $_POST['date_d']; 
    $date_a = $_POST['date_a']; 
    $ndays = $_POST['ndays']; 
    
    
    if ($_POST['new_old']) {
        #navire, country, port_d, port_a, date_d, date_a, ndays, date_c, heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, comment, st_x(location), st_y(location)
        $query = "INSERT INTO thon.maree "
            . "(username, datetime, navire, country, year, port_d, port_a, date_d, date_a, ndays) "
            . "VALUES ('$username', now(), '$navire', '$country', '$year', '$port_d', '$port_a', '$date_d', '$date_a', '$ndays')";

    } else {
        $query = "UPDATE thon.maree SET "
            . "username = '$username', datetime = now(), "
            . "navire = '".$_POST['navire']."', country = '".$_POST['country']."', year = '".$_POST['year']."', port_d = '".$_POST['port_d']."', "
            . "port_a = '".$_POST['port_a']."', date_d = '".$_POST['date_d']."', date_a = '".$_POST['date_a']."', "
            . "ndays = '".$_POST['ndays']."' "
            . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);
    
    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/thon/view_thon_maree.php?source=$source&table=maree&action=show");
    }
    
    
}

foot();
