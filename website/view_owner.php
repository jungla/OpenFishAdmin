<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'autorisation';

$username = $_SESSION['username'];

top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
$id = $_GET['id'];

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

#print "<h2>".label2name($source)." ".label2name($table)."</h2>";
print "<h3>D&eacute;tails du propri&eacute;taire</h3>";
    
$query = "SELECT owner.id, datetime, username, first_name, last_name, bday, t_card.card, idcard , address, t_nationality.nationality, telephone, photo_data, comments "
            . " FROM artisanal.owner "
            . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.owner.t_card "
            . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.owner.t_nationality "
            . "WHERE owner.id = '$id'";    

$results = pg_fetch_array(pg_query($query));

?>

<table id="results">
    <tr><td colspan="3" align="center"><h3><?php echo ucfirst($results[3]).", ".strtoupper($results[4]); ?></h3></td></tr>
<?php if ($results[11] != "") {
    print "<tr><td rowspan=\"8\" align=\"center\" width=\"30%\"><img class=\"img_frame\" width=\"100%\" src=\"image.php?id=$id&table=artisanal.owner&photo_data=photo_data\" /></td></tr>";
}
?>
<tr><td><b>Date de naissance</b></td><td><?php echo $results[5]; ?></td></tr>
<tr><td><b>Type de document</b></td><td><?php echo $results[6]; ?></td></tr>
<tr><td><b>Num&eacute;ro de document</b></td><td><?php echo $results[7]; ?></td></tr>
<tr><td><b>Domicile</b></td><td><?php echo $results[8]; ?></td></tr>
<tr><td><b>Nationalit&eacute;</b></td><td><?php echo $results[9]; ?></td></tr>
<tr><td><b>Num&eacute;ro de t&eacute;l&eacute;phone</b></td><td><?php echo $results[10]; ?></td></tr>
<tr><td><b>Commentaires</b></td><td><?php echo $results[12]; ?></td></tr>
</table>

<?php

$query = "SELECT pirogue.id, datetime, username, name, immatriculation, t_pirogue.pirogue, length, id_owner"
            . " FROM artisanal.pirogue "
            . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue "
            . "WHERE id_owner = '$id' "
            . "ORDER BY datetime DESC";  
//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {
   
    ?>
    <h3>D&eacute;tails pirogue</h3>

    <table>
    <tr align="center">
    <td><b>Nom de la pirogue</b></td>
    <td><b>Immatriculation</b></td>
    <td><b>Type pirogue</b></td>
    <td><b>Longueur</b></td>
    </tr>

    <?php

    while ($results = pg_fetch_row($r_query)) {
                print "<tr  align=\"center\"><td>$results[3]</td><td>$results[4]</td><td>$results[5]</td><td>$results[6]</td>";

    }

    print "</table><br/>";
}

$query = "SELECT infraction.username, t_infraction.infraction, id_pv, date_i, t_org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, "
        . "owner_t_card, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_t_nationality_1, "
        . "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, "
        . "fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
        . "fish_idcard_4, fish_t_card_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
        . "amount, payment, n_dep, n_cdc, n_lib, comments FROM infraction.infractions "
        . "LEFT JOIN infraction.infraction ON infraction.infraction.id = infraction.infractions.id_infraction "
        . "LEFT JOIN infraction.t_infraction ON infraction.infractions.t_infraction = infraction.t_infraction.id "
        . "LEFT JOIN infraction.t_org ON infraction.infraction.t_org = infraction.t_org.id "
        . "WHERE id_owner = '$id' AND infraction.id IS NOT NULL";

//print $query;

$r_query = pg_query($query);


if (pg_num_rows($r_query) > 0) {

    ?>
    <h3>Infractions propri&eacute;taire</h3>
    <table>
    <tr align="center">
    <td><b>Date</b></td>
    <td><b>Type infraction</b></td>
    <td><b>Nom du proprietaire</b></td>
    <td><b>Nom du p&ecirc;cheur</b></td>
    <td><b>Organisation</b></td>
    <td><b>Officer name</b></td>
    <td><b>Objets saisis</b></td>
    <td><b>Montant de l'infraction</b></td>
    <td><b>Montant Pay&eacute;</b></td>
    <td><b>Commentaires</b></td>
    </tr>

    <?php

    while ($results = pg_fetch_row($r_query)) {
        print "<tr align=\"center\"><td nowrap>$results[3]</td><td>$results[1]</td>";
        if ($results[9] != '') {
            print "<td><a href=\"./view_owner.php?id=$results[9]\">".strtoupper($results[11])."<br/>".ucfirst($results[10])."</a></td>";
        } else {
            print "<td>".strtoupper($results[11])."<br/>".ucfirst($results[10])."</td>";
        }
        if ($results[16] != '') {
            print "<td><a href=\"./view_fisherman.php?id=$results[16]\">".strtoupper($results[18])."<br/>".ucfirst($results[17])."</a></td>";
        } else {
            print "<td>".strtoupper($results[18])."<br/>".ucfirst($results[17])."</a></td>";
        }
        print "<td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td>"
        . "<td>$results[15]</td><td>$results[16]</td></tr>";
    }

    print "</table><br/>";

}
?>

<br/>
<button onClick="goBack()">Retourner</button>
<br/>
<?php
foot();

