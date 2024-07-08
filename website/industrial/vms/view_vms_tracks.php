<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'pelagic';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_t_pirogue'] = $_POST['f_t_pirogue'];
$_SESSION['filter']['s_pir_name'] = $_POST['s_pir_name'];
$_SESSION['filter']['s_pir_reg'] = $_POST['s_pir_reg'];

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

    <form method="post" action="<?php echo $self;?>?source=license&table=pirogue&action=show" enctype="multipart/form-data">
    <fieldset>
    
    <table id="no-border"><tr><td><b>Nom du pirogue</b></td></tr>
    <tr>
    <td>
    <select name="f_pir_name">
        <option value="f_pir_name" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT name FROM artisanal.trackers_pelagic ORDER BY name");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_pir_name']) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[0]."</option>";
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
    <td><b>Date</b></td>
    <td><b>Date et l'heure</b></td>
    <td><b>Nom pirogue</b></td>
    <td><b>Vitesse</b></td>
    <td><b>Distance</b></td>
    <td><b>Direction</b></td>
    <td><b>Position</b></td>
    </tr>
    
    <?php
    
    # datetime, username, name, immatriculation, t_pirogue, length, t_site, id_owner
    if ($_SESSION['filter']['s_pir_name'] != "" OR $_SESSION['filter']['s_pir_reg'] != "" OR $_SESSION['filter']['f_t_pirogue'] != "") {

        $_SESSION['start'] = 0;

        $query = "SELECT count(pirogue.id) FROM artisanal.pirogue "
        . "WHERE t_pirogue=".$_SESSION['filter']['f_t_pirogue']." ";
            
        $pnum = pg_fetch_row(pg_query($query))[0];
        
        if ($_SESSION['filter']['s_pir_name'] != "" OR $_SESSION['filter']['s_pir_reg'] != "") {

            $query = "SELECT pirogue.id, datetime, username, name, immatriculation, t_pirogue.pirogue, length, id_owner, comments, "
            . "coalesce(similarity(artisanal.pirogue.immatriculation, '".$_SESSION['filter']['s_pir_reg']."'),0) + "
            . "coalesce(similarity(artisanal.pirogue.name, '".$_SESSION['filter']['s_pir_name']."'),0) AS score"
            . " FROM artisanal.pirogue "
            . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue "
            . "WHERE t_pirogue=".$_SESSION['filter']['f_t_pirogue']." "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";
        } else {
            
            $query = "SELECT pirogue.id, datetime, username, name, immatriculation, t_pirogue.pirogue, length, id_owner, comments "
            . " FROM artisanal.pirogue "
            . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue "
            . "WHERE t_pirogue=".$_SESSION['filter']['f_t_pirogue']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";   
        }
    } else {
        $query = "SELECT count(pirogue.id) FROM artisanal.pirogue";

        $pnum = pg_fetch_row(pg_query($query))[0];
        
        $query = "SELECT pirogue.id, datetime, username, name, immatriculation, t_pirogue.pirogue, length, id_owner, comments"
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
        
        # owner details
        $query_o = "SELECT first_name, last_name FROM artisanal.owner WHERE id = '$results[7]'";
        $results_o = pg_fetch_row(pg_query($query_o));
        
        print "<tr align=\"center\"><td>"
        . "<a href=\"./view_pirogue.php?id=$results[0]\">Voir</a><br/>"
        . "<a href=\"./view_licenses_pirogue.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_licenses_pirogue.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";

        print "<td>$results[1]</td><td>$results[2]</td><td>$results[3]</td><td>$results[4]</td><td>$results[5]</td><td>$results[6]</td>"
        . "<td>$results[8]</td><td><a href=\"./view_owner.php?id=$results[7]\">".$results_o[0]." ".$results_o[1]."</td><td>$results_l[0]</td><td>$results_i[0]</td>";
        
    }
    print "</tr>";
    
    print "</table>";
    
    pages($start,$step,$pnum,'./view_licenses_pirogue.php?source=license&table=pirogue&action=show&s_pir_name='.$_SESSION['filter']['s_pir_name'].'&f_t_pirogue='.$_SESSION['filter']['f_t_pirogue'].'&s_pir_reg='.$_SESSION['filter']['s_pir_reg']);
    
    $controllo = 1;
    
} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";
    
    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT * FROM artisanal.pirogue WHERE id = '$id' ORDER BY datetime DESC";
    #print $q_id;
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);
    
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
        <select name="id_owner">
        <?php
        $result = pg_query("SELECT id, first_name, last_name FROM artisanal.owner ORDER BY last_name");
        $i = 0;
        while($row = pg_fetch_row($result)) {
            if ($results[8] == $i) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[2]." ".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[2]." ".$row[1]."</option>";
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
	<input type="text" size="30" name="immatriculation" value="<?php echo $results[4];?>" />
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
	<input type="text" size="10" name="length" value="<?php echo $results[6];?>" />
	<br/>
	<br/>
        <b>Commentaires</b>
	<br/>
        <textarea name="comments" rows="4" cols="50"><?php echo $results[9];?></textarea>
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
        header("Location: ".$_SESSION['http_host']."/artisanal/view_licenses_pirogue.php?source=$source&table=pirogue&action=show");
    }
    $controllo = 1;
    
}


if ($_POST['submit'] == "Enregistrer") {
    
    if ($_POST['new_old']) {
    
        $name = $_POST['name']; 
        $immatriculation = $_POST['immatriculation']; 
        $t_pirogue = $_POST['t_pirogue'];
        $length = $_POST['length']; 
        $id_owner = $_POST['id_owner']; 
        $comments = $_POST['comments'];
        
        $query = "INSERT INTO artisanal.pirogue "
                . "(username, name, immatriculation, t_pirogue, length, id_owner, comments) "
                . " VALUES ('$username', '$name', '$immatriculation', '$t_pirogue', '$length', '$id_owner', '$comments')";
        
    } else {

        $query = "UPDATE artisanal.pirogue SET "
        . "datetime = now(), "
        . "username = '$username', "
        . "name = '".$_POST['name']."', immatriculation = '".$_POST['immatriculation']."', t_pirogue = '".$_POST['t_pirogue']."',length = '".$_POST['length']."', id_owner = '".$_POST['id_owner']."', comments = '".$_POST['comments']. "' "
        . "WHERE id = '{".$_POST['id']."}'";

    }
    
    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/artisanal/view_licenses_pirogue.php?source=$source&table=pirogues&action=show");
    }

}

foot();
