<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'records';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";
    
    // fetch data
    $query = "SELECT artisanal.fleet.id, date_trunc('second', datetime), username, date_f, obs_name, t_site.site, source, PPB, GPF, PPF, TOT "
            . "FROM artisanal.fleet "
            . "LEFT JOIN artisanal.t_site ON artisanal.t_site.id = artisanal.fleet.t_site";
    
    $r_query = pg_query($query);
    
    print "<table>";
    print "<tr align=\"center\"><td></td>
    <td><b>Date</b></td>
    <td><b>Utilisateur</b></td>
    <td><b>Date observation</b></td>
    <td><b>Enqu&ecirc;teur</b></td>
    <td><b>D&eacute;barcad&egrave;re</b></td>
    <td><b>Data Source</b></td>
    <td><b>PPB</b></td>
    <td><b>GPF</b></td>
    <td><b>PPF</b></td>
    <td><b>Total</b></td>
    </tr>";
    
    while ($results = pg_fetch_row($r_query)) {
        print "<tr align=\"center\"><td>"
        . "<a href=\"./view_records_fleet.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_records_fleet.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";
        print "<td>$results[1]</td><td>$results[2]</td><td>$results[3]</td><td>$results[4]</td><td>$results[5]</td><td>$results[6]</td>"
        . "<td>$results[7]</td><td>$results[8]</td><td>$results[9]</td><td>$results[10]</td>";
    }
    print "</tr>";
    
    print "</table>";
    $controllo = 1;
    
} else if ($_GET['action'] == 'edit') {
    print "<a href=\"./view.php\">view</a> > <a href=\"./view_form.php?source=$source\">".label2name($source)."</a> > <a href=\"./view_records_fleet.php?source=$source&table=$table&action=show\">".label2name($table)."</a>";

    $id = $_GET['id'];

    
    
    //find record info by ID
    $q_id = "SELECT * FROM artisanal.fleet WHERE id = '$id' ORDER BY datetime DESC";
    #print $q_id;
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);
    
    ?>

    <form method="post" action="<?php print $self; ?>" enctype="multipart/form-data"><br/>
    <br/>
    <b>Date observation</b> (aaaa-mm-jj)
    <br/>
    <input type="text" size="20" name="date_f" value="<?php print $results[3];?>" />
    <br/>
    <br/>
    <b>Enqu&ecirc;teur</b>
    <br/>
    <input type="text" size="20" name="obs_name" value="<?php print $results[4];?>" />
    <br/>
    <br/>
    <b>D&eacute;barcad&egrave;re</b>
    <br/>
    <select name="t_site">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_site");
    $i = 0;
    while($row = pg_fetch_row($result)) {
        if ($results[5] == $i) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
        $i++;
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Data source</b>
    <br/>
    <input type="text" size="20" name="source" value="<?php print $results[6];?>" />
    <br/>
    <br/>
    <b>Number of <i>Petite Pirogue en Bois (PPB)</i></b>
    <br/>
    <input type="text" size="20" name="ppb" value="<?php print $results[7];?>" />
    <br/>
    <br/>
    <b>Number of <i>Grande Pirogue en Fibre de Verre (GPF)</i></b>
    <br/>
    <input type="text" size="20" name="gpf" value="<?php print $results[8];?>" />
    <br/>
    <br/>
    <b>Number of <i>Petite Pirogue  en Fibre de Verre (PPF)</i></b>
    <br/>
    <input type="text" size="20" name="ppf" value="<?php print $results[9];?>" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    <input type="hidden" name="id" value="<?php print $results[0]; ?>" />
    </form>
    <br/>
    <br/>

    <?php
    
}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM artisanal.fleet WHERE id = '$id'";
    
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/view_records_fleet.php?source=$source&table=fleet&action=show");
    }
    $controllo = 1;
    
}


if ($_POST['submit'] == "Enregistrer") {
    
    $PPB = $_POST['ppb'];
    $GPF = $_POST['gpf'];
    $PPF = $_POST['ppf'];
    $TOT = floatval($PPB) + floatval($GPF) + floatval($PPF);
  
    $query = "UPDATE artisanal.fleet SET "
            . "datetime = now(), "
            . "username = '$username', date_f = '".$_POST['date_f']."', obs_name = '".$_POST['obs_name']."', t_site = '".$_POST['t_site']."'"
            . ", ppb = '".$_POST['ppb']."', gpf = '".$_POST['gpf']."', ppf = '".$_POST['ppf']."', tot = '".$TOT."'"
            . " WHERE id = '{".$_POST['id']."}'";

    $query = str_replace('\'\'', 'NULL', $query);
    
    #print $query;

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/artisanal/view_records_fleet.php?source=$source&table=fleet&action=show");
    }

}

foot();
