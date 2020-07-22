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
$_SESSION['filter']['f_t_operation'] = $_POST['f_t_operation'];
$_SESSION['filter']['f_t_objet'] = $_POST['f_t_objet'];

if ($_GET['f_s_maree'] != "") {$_SESSION['filter']['f_s_maree'] = $_GET['f_s_maree'];}
if ($_GET['f_t_zee'] != "") {$_SESSION['filter']['f_t_zee'] = $_GET['f_t_zee'];}
if ($_GET['f_t_operation'] != "") {$_SESSION['filter']['f_t_operation'] = $_GET['f_t_operation'];}
if ($_GET['f_t_objet'] != "") {$_SESSION['filter']['f_t_objet'] = $_GET['f_t_objet'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=seiners&table=objet&action=show" enctype="multipart/form-data">
    <fieldset>
    
    <table id="no-border"><tr><td><b>Maree</b></td><td><b>ZEE</b></td><td><b>Objet</b></td><td><b>Operation</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <select name="f_t_zee">
        <option value="t_zee" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT objet.t_zee, t_zee.zee FROM seiners.objet LEFT JOIN seiners.t_zee ON seiners.t_zee.id = seiners.objet.t_zee WHERE t_zee IS NOT NULL ORDER BY t_zee.zee ");
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
    <select name="f_t_objet">
        <option value="t_objet" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT t_objet.id, t_objet.objet FROM seiners.objet LEFT JOIN seiners.t_objet ON seiners.t_objet.id = seiners.objet.t_objet WHERE objet IS NOT NULL ORDER BY objet");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_objet']) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[1]."</option>";
            }  
        }
    ?>
    </select>
    </td>
    <td>
    <select name="f_t_operation">
        <option value="t_operation" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT t_operation.id, t_operation.operation FROM seiners.objet LEFT JOIN seiners.t_operation ON seiners.t_operation.id = seiners.objet.t_operation  WHERE operation IS NOT NULL ORDER BY operation");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_operation']) {
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

    <table>
    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Route</b></td>
    <td><b>Maree</b></td>
    <td><b>ZEE</b></td>
    <td><b>Objet</b></td>
    <td><b>Type balise</b></td>
    <td><b>Code balise</b></td>
    <td><b>Operation</b></td>
    <td><b>Appartenance</b></td>
    <td><b>Devenier</b></td>    
    <td><b>Remarque</b></td>    
    <td><b>Location</b></td>    
    </tr>
    
    <?php
    
    // fetch data
    
   # maree, zee, n_objet, id_route, n_route, l_route, t_objet, type_balise, code_balise, t_operation, t_appartenance, t_devenir, remarque    

    if ($_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_t_zee'] != "" OR $_SESSION['filter']['f_t_objet'] != "" OR $_SESSION['filter']['f_t_operation'] != "") {
        
        $_SESSION['start'] = 0;
        
        if ($_SESSION['filter']['f_s_maree'] != "") { 
            $query = "SELECT count(objet.id) FROM seiners.objet "
                . "WHERE (t_zee IS NULL OR t_zee=".$_SESSION['filter']['f_t_zee'].") "
                . "WHERE t_objet=".$_SESSION['filter']['f_t_objet']." "
                . "AND t_operation=".$_SESSION['filter']['f_t_operation']." ";
                             
            $pnum = pg_fetch_row(pg_query($query))[0];
            
            $query = "SELECT objet.id, objet.datetime, objet.username, objet.maree, t_zee.zee, n_objet, id_route, objet.n_route, objet.l_route, t_objet.objet, type_balise, code_balise, t_operation.operation, t_appartenance.appartenance, t_devenir.devenir, remarque, st_x(route.location), st_y(route.location), route.date, route.time,  "
            . " coalesce(similarity(seiners.objet.maree, '".$_SESSION['filter']['f_s_maree']."'),0)  AS score"
            . " FROM seiners.objet "
            . "LEFT JOIN seiners.t_objet ON seiners.t_objet.id = seiners.objet.t_objet "
            . "LEFT JOIN seiners.t_operation ON seiners.t_operation.id = seiners.objet.t_operation "
            . "LEFT JOIN seiners.t_appartenance ON seiners.t_appartenance.id = seiners.objet.t_appartenance "
            . "LEFT JOIN seiners.t_devenir ON seiners.t_devenir.id = seiners.objet.t_devenir "
            . "LEFT JOIN seiners.t_zee ON seiners.t_zee.id = seiners.objet.t_zee "
            . "LEFT JOIN seiners.route ON seiners.route.id = seiners.objet.id_route "
            . "WHERE (t_zee IS NULL OR t_zee=".$_SESSION['filter']['f_t_zee'].") "
            . "AND t_objet=".$_SESSION['filter']['f_t_objet']." "
            . "AND t_operation=".$_SESSION['filter']['f_t_operation']." "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";
            
        } else {
            
            $query = "SELECT count(objet.id) FROM seiners.objet "
            . "WHERE (t_zee IS NULL OR t_zee=".$_SESSION['filter']['f_t_zee'].") "
            . "AND t_objet=".$_SESSION['filter']['f_t_objet']." "
            . "AND t_operation=".$_SESSION['filter']['f_t_operation']." ";
            
            $pnum = pg_fetch_row(pg_query($query))[0];
            
            $query = "SELECT objet.id, objet.datetime, objet.username, objet.maree, t_zee.zee, n_objet, id_route, objet.n_route, objet.l_route, t_objet.objet, type_balise, code_balise, t_operation.operation, t_appartenance.appartenance, t_devenir.devenir, remarque, st_x(route.location), st_y(route.location), route.date, route.time "
            . " FROM seiners.objet "
            . "LEFT JOIN seiners.t_objet ON seiners.t_objet.id = seiners.objet.t_objet "
            . "LEFT JOIN seiners.t_operation ON seiners.t_operation.id = seiners.objet.t_operation "
            . "LEFT JOIN seiners.t_appartenance ON seiners.t_appartenance.id = seiners.objet.t_appartenance "
            . "LEFT JOIN seiners.t_devenir ON seiners.t_devenir.id = seiners.objet.t_devenir "
            . "LEFT JOIN seiners.t_zee ON seiners.t_zee.id = seiners.objet.t_zee "
            . "LEFT JOIN seiners.route ON seiners.route.id = seiners.objet.id_route "
            . "WHERE (t_zee IS NULL OR t_zee=".$_SESSION['filter']['f_t_zee'].") "
            . "AND t_objet=".$_SESSION['filter']['f_t_objet']." "
            . "AND t_operation=".$_SESSION['filter']['f_t_operation']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step"; 
        }
    } else {
    
        $query = "SELECT count(objet.id) FROM seiners.objet";
        $pnum = pg_fetch_row(pg_query($query))[0];
        
        $query = "SELECT objet.id, objet.datetime, objet.username, objet.maree, t_zee.zee, n_objet, id_route, objet.n_route, objet.l_route, t_objet.objet, type_balise, code_balise, t_operation.operation, t_appartenance.appartenance, t_devenir.devenir, remarque, st_x(route.location), st_y(route.location), route.date, route.time  "
        . " FROM seiners.objet "
        . "LEFT JOIN seiners.t_objet ON seiners.t_objet.id = seiners.objet.t_objet "
        . "LEFT JOIN seiners.t_operation ON seiners.t_operation.id = seiners.objet.t_operation "
        . "LEFT JOIN seiners.t_appartenance ON seiners.t_appartenance.id = seiners.objet.t_appartenance "
        . "LEFT JOIN seiners.t_devenir ON seiners.t_devenir.id = seiners.objet.t_devenir "
        . "LEFT JOIN seiners.t_zee ON seiners.t_zee.id = seiners.objet.t_zee "
            . "LEFT JOIN seiners.route ON seiners.route.id = seiners.objet.id_route "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";   
    
    }
    
    $r_query = pg_query($query);
    
    while ($results = pg_fetch_row($r_query)) {
        
        print "<tr align=\"center\">";
    
        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_seiners_objet.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_seiners_objet.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td><a href=\"./view_route.php?id=$results[6]\">$results[18] $results[19]</a></td><td>$results[3]</td><td>$results[4]</td>"
        . "<td>$results[9]</td><td>$results[10]</td><td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td><td>$results[15]</td><td><a href=\"view_point.php?X=$results[16]&Y=$results[17]\">".round($results[16],3)."E ".round($results[17],3)."N</td></tr>";
    }
    print "</tr>";
    print "</table>";
    
    pages($start,$step,$pnum,'./view_seiners_objet.php?source=seiners&table=objet&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_t_zee='.$_SESSION['filter']['f_t_zee'].'&f_t_objet='.$_SESSION['filter']['f_t_objet'].'&f_t_operation='.$_SESSION['filter']['f_t_operation']);
    
    $controllo = 1;
    
} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";
    
    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT *, route.maree, route.date, route.time FROM seiners.objet "
            . "LEFT JOIN seiners.route ON seiners.route.id = seiners.objet.id_route "
            . "WHERE objet.id = '$id'";
    
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
        if ($row[0] == $results[21]) {
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
    $result = pg_query("SELECT DISTINCT time FROM seiners.route WHERE maree = '$results[3]' AND date = '$results[21]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[24]) {
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
    <b>Objet</b> 
    <br/>
    <select name="t_objet">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, objet FROM seiners.t_objet ORDER BY objet");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[9]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select><br/>
    <br/>    
    <b>Type balise</b>
    <br/>
    <input type="text" size="20" name="type_balise" value="<?php echo $results[10]; ?>"/>
    <br/>
    <br/>
    <b>Code balise</b>
    <br/>
    <input type="text" size="20" name="code_balise" value="<?php echo $results[11]; ?>"/>
    <br/>
    <br/>
    <b>Operation</b>
    <br/>
    <select name="t_operation">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, operation FROM seiners.t_operation ORDER BY operation");
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
    <b>Appartenance</b>
    <br/>
    <select name="t_appartenance">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, appartenance FROM seiners.t_appartenance ORDER BY appartenance");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[13]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Devenir</b>
    <br/>
    <select name="t_devenir">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, devenir FROM seiners.t_devenir ORDER BY devenir");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[14]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" value="<?php echo $results[15];?>" />
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
    $query = "DELETE FROM seiners.objet WHERE id = '$id'";
    
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_seiners_objet.php?source=$source&table=objet&action=show");
    }
    $controllo = 1;
    
}


if ($_POST['submit'] == "Enregistrer") {
    
    $username = $_SESSION['username'];
    $maree = $_POST['maree'];
    $t_zee = $_POST['t_zee'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $t_objet = $_POST['t_objet'];
    $type_balise = $_POST['type_balise'];
    $code_balise = $_POST['code_balise'];
    $t_operation = $_POST['t_operation'];
    $t_appartenance = $_POST['t_appartenance'];
    $t_devenir = $_POST['t_devenir'];
    $remarque = htmlspecialchars($_POST['remarque'],ENT_QUOTES);

    $q_id = "SELECT id FROM seiners.route WHERE maree = '$maree' AND date = '$date' AND time = '$time'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];
    
    if ($_POST['new_old']) {
    #username, maree, t_zee, id_route, t_objet, type_balise, code_balise, t_operation, t_appartenance, t_devenir, remarque
        $query = "INSERT INTO seiners.objet "
            . "(username, datetime, maree, t_zee, id_route, t_objet, type_balise, code_balise, t_operation, t_appartenance, t_devenir, remarque) "
            . "VALUES ('$username', now(), '$maree', '$t_zee', '$id_route', '$t_objet', '$type_balise', '$code_balise', '$t_operation', '$t_appartenance', '$t_devenir', '$remarque')";

    } else {
        $query = "UPDATE seiners.objet SET "
            . "username = '$username', datetime = now(), "
            . "maree = '$maree', t_zee = '$t_zee', id_route = '$id_route', t_objet = '$t_objet', type_balise = '$type_balise', "
            . "code_balise = '$code_balise', t_operation = '$t_operation', t_appartenance = '$t_appartenance', t_devenir = '$t_devenir', remarque = '$remarque'"
            . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);
    print $query;
    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_seiners_objet.php?source=$source&table=$table&action=show");
    }
}

foot();
