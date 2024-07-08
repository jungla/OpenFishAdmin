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
    $query = "SELECT artisanal.market.id, date_trunc('second', datetime), username, date_m, obs_name, t_site.site, bp_f, bp_c, bp_fm, bp_s, "
            . "sar_f, sar_c, sar_fm, sar_s, sl_f, sl_c, sl_fm, sl_s, mac_f, mac_c, mac_fm, mac_s, "
            . "req_f, req_c, req_fm, req_s, ailreq_f, ailreq_c, ailreq_fm, ailreq_s, "
            . "lang_f, lang_c, lang_fm, lang_s, crab_f, crab_c, crab_fm, crab_s "
            . "FROM artisanal.market "
            . "LEFT JOIN artisanal.t_site ON artisanal.t_site.id = artisanal.market.t_site "
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
    <td><b>Esp&egrave;ce</b></td>
    <td><b>prix frais</b><br/>&agrave; la pirogue (Kg/FCFA)</td> 
    <td><b>prix congel&eacute;</b><br/>chez la mareyeuse (Kg/FCFA)</td> 
    <td><b>prix fum&eacute;</b><br/>(FCFA/kg)</td>
    <td><b>prix sal&eacute;</b><br/>(FCFA/morceau)</td></tr>
    </tr>";
    
    while ($results = pg_fetch_row($r_query)) {
        print "<tr align=\"center\"><td rowspan=8>"
        . "<a href=\"./view_records_market.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_records_market.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";
        print "<td rowspan=8>$results[1]</td><td rowspan=8>$results[2]</td><td rowspan=8>$results[3]</td><td rowspan=8>$results[4]</td><td rowspan=8>$results[5]</td>";
        print "<td>Beaux poissons (Bars, rouge, pagre, etc)</td>";
        print "<td>$results[6]</td><td>$results[7]</td><td>$results[8]</td><td>$results[9]</td></tr>";
        print "<tr align=\"center\"><td>Sardines/sardinelles</td>";
        print "<td>$results[10]</td><td>$results[11]</td><td>$results[12]</td><td>$results[13]</td></tr>";
        print "<tr align=\"center\"><td>Sole</td>";
        print "<td>$results[14]</td><td>$results[15]</td><td>$results[16]</td><td>$results[17]</td></tr>";
        print "<tr align=\"center\"><td>Machoiron</td>";
        print "<td>$results[18]</td><td>$results[19]</td><td>$results[20]</td><td>$results[21]</td></tr>";
        print "<tr align=\"center\"><td>Requin/raie</td>";
        print "<td>$results[22]</td><td>$results[23]</td><td>$results[24]</td><td>$results[25]</td></tr>";
        print "<tr align=\"center\"><td>Ailerons de requins</td>";
        print "<td>$results[26]</td><td>$results[27]</td><td>$results[28]</td><td>$results[29]</td></tr>";
        print "<tr align=\"center\"><td>Langouste</td>";
        print "<td>$results[30]</td><td>$results[31]</td><td>$results[32]</td><td>$results[33]</td></tr>";
        print "<tr align=\"center\"><td>Crabes</td>";
        print "<td>$results[34]</td><td>$results[35]</td><td>$results[36]</td><td>$results[37]</td></tr>";
    }
    print "</tr>";
    
    print "</table>";
    $controllo = 1;
    
} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";
    
    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT * FROM artisanal.market WHERE id = '$id' ORDER BY datetime DESC";
    #print $q_id;
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);
    
    ?>
    <form method="post" action="<?php print $self; ?>" enctype="multipart/form-data">
    <br/>
    <b>Date</b>
    <br/>
    <input type="text" size="10" name="date_m" value="<?php print $results[3]; ?>"/>
    <br/>
    <br/>
    <b>Enqu&ecirc;teur</b>
    <br/>
    <input type="text" size="20" name="obs_name" value="<?php print $results[4]; ?>"/>
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
    <table>
        <tr><td><b>Category</b></td>                            <td><b>prix frais</b><br/>&agrave; la pirogue (FCFA/kg)</td> <td><b>prix congel&eacute;</b><br/>chez la mareyeuse (FCFA/kg)</td>  <td><b>prix fum&eacute;</b><br/>(FCFA/kg)</td>           <td><b>prix sal&eacute;</b><br/>(FCFA/morceau)</td></tr>
        <tr><td>Beaux poissons (Bars, rouge, pagre, etc)</td>   <td><input type="text" size="4" name="bp_f" value="<?php print $results[6]; ?>"/></td>     <td><input type="text" size="4" name="bp_c" value="<?php print $results[7]; ?>"/></td>             <td><input type="text" size="4" name="bp_fm" value="<?php print $results[8]; ?>"/></td> <td><input type="text" size="4" name="bp_s" value="<?php print $results[9]; ?>"/></td></tr>
        <tr><td>Sardines/sardinelles</td>                       <td><input type="text" size="4" name="sar_f" value="<?php print $results[10]; ?>"/></td>      <td><input type="text" size="4" name="sar_c" value="<?php print $results[11]; ?>"/></td>              <td><input type="text" size="4" name="sar_fm" value="<?php print $results[12]; ?>"/></td>  <td><input type="text" size="4" name="sar_s" value="<?php print $results[13]; ?>"/></td></tr>
        <tr><td>Sole </td>                                      <td><input type="text" size="4" name="sl_f" value="<?php print $results[14]; ?>"/></td>      <td><input type="text" size="4" name="sl_c" value="<?php print $results[15]; ?>"/></td>              <td><input type="text" size="4" name="sl_fm" value="<?php print $results[16]; ?>"/></td>  <td><input type="text" size="4" name="sl_s" value="<?php print $results[17]; ?>"/></td></tr>
        <tr><td>Machoiron</td>                                  <td><input type="text" size="4" name="mac_f" value="<?php print $results[18]; ?>"/></td>      <td><input type="text" size="4" name="mac_c" value="<?php print $results[19]; ?>"/></td>              <td><input type="text" size="4" name="mac_fm" value="<?php print $results[20]; ?>"/></td>  <td><input type="text" size="4" name="mac_s" value="<?php print $results[21]; ?>"/></td></tr>
        <tr><td>Requin/raie</td>                                <td><input type="text" size="4" name="req_f" value="<?php print $results[22]; ?>"/></td>      <td><input type="text" size="4" name="req_c" value="<?php print $results[23]; ?>"/></td>              <td><input type="text" size="4" name="req_fm" value="<?php print $results[24]; ?>"/></td>  <td><input type="text" size="4" name="req_s" value="<?php print $results[25]; ?>"/></td></tr>
        <tr><td>Ailerons de requins</td>                        <td><input type="text" size="4" name="ailreq_f" value="<?php print $results[26]; ?>"/></td>      <td><input type="text" size="4" name="ailreq_c" value="<?php print $results[27]; ?>"/></td>              <td><input type="text" size="4" name="ailreq_fm" value="<?php print $results[28]; ?>"/></td>  <td><input type="text" size="4" name="ailreq_s" value="<?php print $results[29]; ?>"/></td></tr>
        <tr><td>Langouste</td>                                  <td><input type="text" size="4" name="lang_f" value="<?php print $results[30]; ?>"/></td>      <td><input type="text" size="4" name="lang_c" value="<?php print $results[31]; ?>"/></td>              <td><input type="text" size="4" name="lang_fm" value="<?php print $results[32]; ?>"/></td>  <td><input type="text" size="4" name="lang_s" value="<?php print $results[33]; ?>"/></td></tr>
        <tr><td>Crabes</td>                                     <td><input type="text" size="4" name="crab_f" value="<?php print $results[34]; ?>"/></td>      <td><input type="text" size="4" name="crab_c" value="<?php print $results[35]; ?>"/></td>              <td><input type="text" size="4" name="crab_fm" value="<?php print $results[36]; ?>"/></td>  <td><input type="text" size="4" name="crab_s" value="<?php print $results[37]; ?>"/></td></tr>
    </table>
     <br/><br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    <input type="hidden" name="id" value="<?php print $results[0]; ?>" />
    </form>
    <br/>
    <br/>

    <?php
    
}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM artisanal.market WHERE id = '$id'";
    
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/view_records_market.php?source=$source&table=market&action=show");
    }
    $controllo = 1;
    
}


if ($_POST['submit'] == "Enregistrer") {
    
    $query = "UPDATE artisanal.market SET "
            . "datetime = now(), "
            . "username = '$username', date_m = '".$_POST['date_m']."', obs_name = '".$_POST['obs_name']."', t_site = '".$_POST['t_site']."'"
            . ", bp_f='".$_POST['bp_f']."', bp_c='".$_POST['bp_c']."', bp_fm='".$_POST['bp_fm']."', bp_s='".$_POST['bp_s']."'"
            . ", sar_f='".$_POST['sar_f']."', sar_c='".$_POST['sar_c']."', sar_fm='".$_POST['sar_fm']."', sar_s='".$_POST['sar_s']."'"
            . ", sl_f='".$_POST['sl_f']."', sl_c='".$_POST['sl_c']."', sl_fm='".$_POST['sl_fm']."', sl_s='".$_POST['sl_s']."'"
            . ", mac_f='".$_POST['mac_f']."', mac_c='".$_POST['mac_c']."', mac_fm='".$_POST['mac_fm']."', mac_s='".$_POST['mac_s']."'"
            . ", req_f='".$_POST['req_f']."', req_c='".$_POST['req_c']."', req_fm='".$_POST['req_fm']."', req_s='".$_POST['req_s']."'"
            . ", ailreq_f='".$_POST['ailreq_f']."', ailreq_c='".$_POST['ailreq_c']."', ailreq_fm='".$_POST['ailreq_fm']."',  ailreq_s='".$_POST['ailreq_s']."'"
            . ", lang_f='".$_POST['lang_f']."', lang_c='".$_POST['lang_c']."', lang_fm='".$_POST['lang_fm']."', lang_s='".$_POST['lang_s']."'"
            . ", crab_f='".$_POST['crab_f']."', crab_c='".$_POST['crab_c']."', crab_fm='".$_POST['crab_fm']."', crab_s='".$_POST['crab_s']."'"
            
            . " WHERE id = '{".$_POST['id']."}'";

    $query = str_replace('\'\'', 'NULL', $query);
    
    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/artisanal/view_records_market.php?source=$source&table=market&action=show");
    }

}

foot();
