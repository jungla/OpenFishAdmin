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
    $query = "SELECT effort.id, date_trunc('second', datetime), username, date_e, obs_name, T_site.site, DB1, DH1, DB3, DH3, PS1, PC1, PS3, PC3"
            . " FROM artisanal.effort "
            . "LEFT JOIN artisanal.t_site ON artisanal.t_site.id = artisanal.effort.t_site "
            . "ORDER BY datetime DESC";
    #print $query;
    $r_query = pg_query($query);
    
    print "<table>";
    print "<tr align=\"center\"><td></td>
    <td><b>Date</b></td>
    <td><b>Utilisateur</b></td>
    <td><b>Date observation</b></td>
    <td><b>Enqu&ecirc;teur</b></td>
    <td><b>D&eacute;barcad&egrave;re</b></td>
    <td><b>DB1</b></td>
    <td><b>DB3</b></td>
    <td><b>DH1</b></td>
    <td><b>DH3</b></td>
    <td><b>PS1</b></td>
    <td><b>PS3</b></td>
    <td><b>PC1</b></td>
    <td><b>PC3</b></td>
    </tr>";
    
    while ($results = pg_fetch_row($r_query)) {
        print "<tr align=\"center\"><td>"
        . "<a href=\"./view_records_effort.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_records_effort.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";
        print "<td>$results[1]</td><td>$results[2]</td><td>$results[3]</td><td>$results[4]</td><td>$results[5]</td><td>$results[6]</td>"
        . "<td>$results[7]</td><td>$results[8]</td><td>$results[9]</td><td>$results[10]</td><td>$results[11]</td><td>$results[12]</td><td>$results[13]</td>";
    }
    print "</tr>";
    
    print "</table>";
    $controllo = 1;
    
} else if ($_GET['action'] == 'edit') {
    print "<a href=\"./view.php\">view</a> > <a href=\"./view_form.php?source=$source\">".label2name($source)."</a> > <a href=\"./view_records_effort.php?source=$source&table=$table&action=show\">".label2name($table)."</a>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT * FROM artisanal.effort WHERE id = '$id' ORDER BY datetime DESC";
    #print $q_id;
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);
    
    ?>

    <form method="post" action="<?php print $self; ?>" enctype="multipart/form-data"><br/>
    <br/>
    <b>Date observation</b> (aaaa-mm-jj)
    <br/>
    <input type="text" size="20" name="date_e" value="<?php print $results[3];?>" />
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
    <b>Number of Demersal Bottom Net boats fishing for 1 day</b>
    <br/>
    <input type="text" size="20" name="db1" value="<?php print $results[6];?>" />
    <br/>
    <br/>
    <b>Number of Demersal Bottom Net boats fishing for 3 days</b>
    <br/>
    <input type="text" size="20" name="db3" value="<?php print $results[7];?>" />
    <br/>
    <br/>
    <b>Number of Demersal Hand line boats fishing for 1 day</b>
    <br/>
    <input type="text" size="20" name="dh1" value="<?php print $results[8];?>" />
    <br/>
    <br/>
    <b>Number of Demersal Hand line boats fishing for 3 days</b>
    <br/>
    <input type="text" size="20" name="dh3" value="<?php print $results[9];?>" />
    <br/>
    <br/>
    <b>Number of Pelagic Sleeping Net boats fishing for 1 day</b>
    <br/>
    <input type="text" size="20" name="ps1" value="<?php print $results[10];?>" />
    <br/>
    <br/>
    <b>Number of Pelagic Sleeping Net boats fishing for 3 days</b>
    <br/>
    <input type="text" size="20" name="ps3" value="<?php print $results[11];?>" />
    <br/>
    <br/>
    <b>Number of Pelagic Circling Net boats fishing for 1 day</b>
    <br/>
    <input type="text" size="20" name="pc1" value="<?php print $results[12];?>" />
    <br/>
    <br/>
    <b>Number of Pelagic Circling Net boats fishing for 3 days</b>
    <br/>
    <input type="text" size="20" name="pc3" value="<?php print $results[13];?>" />
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
    $query = "DELETE FROM artisanal.effort WHERE id = '$id'";
    
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/view_records_effort.php?source=$source&table=effort&action=show");
    }
    $controllo = 1;
    
}


if ($_POST['submit'] == "Enregistrer") {
        
        $query = "UPDATE artisanal.effort SET "
                . "datetime = now(), "
                . "username = '$username', date_e = '".$_POST['date_e']."', obs_name = '".$_POST['obs_name']."', t_site = '".$_POST['t_site']."'"
                . ", db1 = '".$_POST['db1']."', db3 = '".$_POST['db3']."', dh1 = '".$_POST['dh1']."', dh3 = '".$_POST['dh3']."'"
                . ", ps1 = '".$_POST['ps1']."', ps3 = '".$_POST['ps3']."', pc1 = '".$_POST['pc1']."', pc3 = '".$_POST['pc3']."'"
                . " WHERE id = '{".$_POST['id']."}'";
    
    $query = str_replace('\'\'', 'NULL', $query);
    
    #print $query;

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/artisanal/view_records_effort.php?source=$source&table=effort&action=show");
    }

}

foot();
