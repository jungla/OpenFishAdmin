<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'administration';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
$id = $_GET['id'];

$_SESSION['filter']['s_navire'] = $_POST['s_navire'];
$_SESSION['filter']['f_mere'] = $_POST['f_mere'];
$_SESSION['filter']['f_parc'] = $_POST['f_parc'];

if ($_GET['s_navire'] != "") {$_SESSION['filter']['s_navire'] = $_GET['s_navire'];}
if ($_GET['f_mere'] != "") {$_SESSION['filter']['f_mere'] = $_GET['f_mere'];}
if ($_GET['f_parc'] != "") {$_SESSION['filter']['f_parc'] = $_GET['f_parc'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $start = $_GET['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 100;

    ?>

    <form method="post" action="<?php echo $self;?>?source=vms&table=point&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Nom du navire</b></td></tr>
    <tr>
    <td>
    <select name="s_navire">
    <option value="name" selected="selected">Tous</option>
    <?php
    $result = pg_query("SELECT DISTINCT navire.id, navire.navire FROM vms.positions LEFT JOIN vms.navire ON positions.id_navire = navire.id WHERE navire NOT LIKE 'M\_%' ORDER BY navire");
    while($row = pg_fetch_row($result)) {
        print $_SESSION['filter']['s_navire'];
        if ("'".$row[1]."'" == $_SESSION['filter']['s_navire']) {
            print "<option value=\"'$row[1]'\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"'$row[1]'\">".$row[1]."</option>";
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
    <tr align="center">
    <td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Nom navire</b></td>
    <td><b>Autre noms</b></td>
    <td><b>Type navire</b></td>
    <td><b>Nationalit&eacute;</b></td>
    <td><b>Armement</b></td>
    <td><b>Nom complet</b></td>
    <td><b>Radio</b></td>
    <td><b>Immatriculation national / externe / international</b></td>
    <td><b>T&eacute;l&eacute;phone</b></td>
    <td><b>MMSI</b></td>
    <td><b>IMO</b></td>
    <td><b>Port</b></td>
    <td><b>Actif</b></td>
    <td><b>Code Balise</b></td>
    <td><b>Type Balise</b></td>
    <td><b>Inconnue</b></td>
    </tr>

    <?php

    # datetime, username, name, immatriculation, t_pirogue, length, t_site, id_owner

    $query = "SELECT count(navire) FROM vms.navire WHERE navire NOT LIKE 'M\_%'";

    $pnum = pg_fetch_row(pg_query($query))[0];

    $query = "SELECT navire.id, datetime::date, username, navire.navire, other_names, t_navire.navire, flag, "
    . "owners, fullname, radio, registration_ext, registration_int, registration_qrt, mobile, mmsi, imo, port, active, beacon, satellite, unknown "
    . "FROM vms.navire "
    . "LEFT JOIN vms.t_navire ON t_navire.id = vms.navire.t_navire "
    . "WHERE navire.navire NOT LIKE 'M\_%' ORDER BY navire.navire OFFSET $start LIMIT $step";

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {
        print "<tr align=\"center\">";

        print "<td>";

        if(right_write($_SESSION['username'],7,2)) {
          print "<a href=\"./view_licenses_navire.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a>";
        }
        print "</td>";

        print "<td>$results[1]<br/>$results[2]</td><td>$results[3]</td><td>$results[4]</td>"
                . "<td>$results[5]</td><td>$results[6]</td><td>$results[7]</td><td>$results[8]<br/>$results[9]<br/>$results[10]</td>"
                . "<td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td>"
                . "<td>$results[15]</td><td>$results[16]</td><td>$results[17]</td><td>$results[18]</td><td>$results[19]</td>";
    }

    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_licenses_navire.php?source=vms&table=navire&action=show&s_navire='.$_SESSION['filter']['s_navire'].'&f_mere='.$_SESSION['filter']['f_mere'].'&f_parc='.$_SESSION['filter']['f_parc']);

}  else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT navire, other_names, flag, owners, fullname, radio, registration_ext,
    registration_int, registration_qrt, mobile, mmsi, imo, port, active, beacon, satellite, unknown, t_navire FROM vms.navire WHERE navire.id = '$id'";

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Nom navire</b>
    <br/>
    <input type="text" size="20" name="navire" value="<?php echo $results[0]; ?>"/>
    <br/>
    <br/>
    <b>t_navire</b>
    <br/>
    <input type="text" size="30" name="t_navire" value="<?php echo $results[17];?>" />
    <br/>
    <br/>
    <b>Autre noms</b>
    <br/>
    <input type="text" size="20" name="other_names" value="<?php echo $results[1]; ?>"/>
    <br/>
    <br/>
    <b>Pavillon</b>
    <br/>
    <input type="text" size="20" name="flag" value="<?php echo $results[2]; ?>"/>
    <br/>
    <br/>
    <b>Owners</b>
    <br/>
    <input type="text" size="30" name="owners" value="<?php echo $results[3];?>" />
    <br/>
    <br/>
    <b>Fullname</b>
    <br/>
    <input type="text" size="30" name="fullname" value="<?php echo $results[4];?>" />
    <br/>
    <br/>
    <b>Radio</b>
    <br/>
    <input type="text" size="30" name="radio" value="<?php echo $results[5];?>" />
    <br/>
    <br/>
    <b>registration_ext</b>
    <br/>
    <input type="text" size="30" name="registration_ext" value="<?php echo $results[6];?>" />
    <br/>
    <br/>
    <b>registration_int</b>
    <br/>
    <input type="text" size="30" name="registration_int" value="<?php echo $results[7];?>" />
    <br/>
    <br/>
    <b>registration_qrt</b>
    <br/>
    <input type="text" size="30" name="registration_qrt" value="<?php echo $results[8];?>" />
    <br/>
    <br/>
    <b>mobile</b>
    <br/>
    <input type="text" size="30" name="mobile" value="<?php echo $results[9];?>" />
    <br/>
    <br/>
    <b>mmsi</b>
    <br/>
    <input type="text" size="30" name="mmsi" value="<?php echo $results[10];?>" />
    <br/>
    <br/>
    <b>imo</b>
    <br/>
    <input type="text" size="30" name="imo" value="<?php echo $results[11];?>" />
    <br/>
    <br/>
    <b>Port</b>
    <br/>
    <input type="text" size="30" name="port" value="<?php echo $results[12];?>" />
    <br/>
    <br/>
    <b>active</b>
    <br/>
    <input type="text" size="30" name="active" value="<?php echo $results[13];?>" />
    <br/>
    <br/>
    <b>beacon</b>
    <br/>
    <input type="text" size="30" name="beacon" value="<?php echo $results[14];?>" />
    <br/>
    <br/>
    <b>satellite</b>
    <br/>
    <input type="text" size="30" name="satellite" value="<?php echo $results[15];?>" />
    <br/>
    <br/>
    <b>unknown</b>
    <br/>
    <input type="text" size="30" name="unknown" value="<?php echo $results[16];?>" />
    <br/>
    <br/>
    <input type="hidden" value="<?php echo $id; ?>" name="id"/>
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
        header("Location: ".$_SESSION['http_host']."/industrial/view_licenses_navire.php?source=$source&table=$table&action=show");
    }
    $controllo = 1;

}

if ($_POST['submit'] == "Enregistrer") {

    $username = $_SESSION['username'];
    $navire = htmlentities($_POST['navire']);
    $other_names = htmlentities($_POST['other_names']);
    $flag = htmlentities($_POST['flag']);
    $owners = htmlentities($_POST['owners']);
    $fullname = htmlentities($_POST['fullname']);
    $radio = htmlentities($_POST['radio']);
    $registration_ext = htmlentities($_POST['registration_ext']);
    $registration_int = htmlentities($_POST['registration_int']);
    $registration_qrt = htmlentities($_POST['registration_qrt']);
    $mobile = htmlentities($_POST['mobile']);
    $mmsi = htmlentities($_POST['mmsi']);
    $imo = htmlentities($_POST['imo']);
    $port = htmlentities($_POST['port']);
    $active = htmlentities($_POST['active']);
    $beacon = htmlentities($_POST['beacon']);
    $satellite = htmlentities($_POST['satellite']);
    $unknown = htmlentities($_POST['unknown']);
    $t_navire = htmlentities($_POST['t_navire']);

    $query = "UPDATE vms.navire SET "
      . "username = '$username', datetime = now(), "
      . "navire = '$navire', other_names = '$other_names', flag = '$flag', owners = '$owners', fullname = '$fullname', radio = '$radio', registration_ext = '$registration_ext', "
      . "registration_int = '$registration_int', registration_qrt = '$registration_qrt', mobile = '$mobile', mmsi = '$mmsi', imo = '$imo', port = '$port', active = '$active', "
      . "beacon = '$beacon', satellite = '$satellite', unknown = '$unknown', t_navire = '$t_navire' "
      . " WHERE id = '{".$_POST['id']."}'";

    $query = str_replace('\'\'', 'NULL', $query);
    print $query;
    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_licenses_navire.php?source=$source&table=$table&action=show");
    }
}

foot();
?>
