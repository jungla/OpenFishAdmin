<?php
require("../../top_foot.inc.php");

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

$query = "SELECT fisherman.id, datetime, username, first_name, last_name, bday, t_card.card, idcard , address, t_nationality.nationality, telephone, photo_data "
            . " FROM artisanal.fisherman "
            . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.fisherman.t_card "
            . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.fisherman.t_nationality "
            . "WHERE fisherman.id = '$id'";

$results = pg_fetch_array(pg_query($query));

?>

<table id="results">
    <tr><td colspan="3" align="center"><h3><?php echo ucfirst($results[3]).", ".strtoupper($results[4]); ?></h3></td></tr>
<?php if ($results[11] != "") {
    print "<tr><td rowspan=\"7\" align=\"center\" width=\"30%\"><img class=\"img_frame\" width=\"100%\" src=\"image.php?id=$id&table=artisanal.fisherman&photo_data=photo_data\" /></td></tr>";
}
?>
<tr><td><b>Date de naissance</b></td><td><?php echo $results[5]; ?></td></tr>
<tr><td><b>Type de pi&egrave;ce d'identit&eacute;</b></td><td><?php echo $results[6]; ?></td></tr>
<tr><td><b>Num&eacute;ro de pi&egrave;ce d'identit&eacute;</b></td><td><?php echo $results[7]; ?></td></tr>
<tr><td><b>Domicile</b></td><td><?php echo $results[8]; ?></td></tr>
<tr><td><b>Nationalit&eacute;</b></td><td><?php echo $results[9]; ?></td></tr>
<tr><td><b>Num&eacute;ro de t&eacute;l&eacute;phone</b></td><td><?php echo $results[10]; ?></td></tr>

</table>


<?php

$query = "SELECT carte, first_name, last_name, license, extract(year FROM carte.date_v), *  FROM artisanal.carte "
        . "LEFT JOIN artisanal.fisherman ON artisanal.fisherman.id = artisanal.carte.id_fisherman "
        . "LEFT JOIN artisanal.license ON artisanal.license.id = artisanal.carte.id_license "
        . "WHERE id_fisherman = '$id' "
        . "ORDER BY carte.datetime DESC";

//print $query;
$r_query = pg_query($query);


if (pg_num_rows($r_query) > 0) {

    ?>
    <h3>Cartes li&eacute;es &agrave; ce p&ecirc;cheur</h3>

    <table>
    <tr align="center">
    <td><b>Num&eacute;ro carte</b></td>
    <td><b>Date validit&eacute;</b></td>
    <td><b>Autorisation de p&ecirc;che</b></td>
    </tr>

    <?php

    while ($results = pg_fetch_row($r_query)) {
        print "<tr align=\"center\"><td>$results[0]</td><td>$results[4]</td>";

        if ($results[12] != "") {
            print "<td><a href=\"./view_license.php?id=$results[11]\">$results[3]</a></td>";
        } else {
            print "<td>Aucun</td>";

        }
        print "</tr>";
  }
    print "</table><br/>";
}

$query = "SELECT DISTINCT infraction.id, infraction.username, infraction.datetime::date, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, "
. "owner_t_card, owner_ycard, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_ycard_1, fish_t_nationality_1, "
. "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_ycard_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, "
. "fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_ycard_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
. "fish_idcard_4, fish_t_card_4, fish_ycard_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
. "amount, payment, n_dep, n_cdc, n_lib, comments, ST_X(location), ST_Y(location), settled, infraction.datetime "
. " FROM infraction.infraction "
        . "LEFT JOIN infraction.t_org ON infraction.infraction.t_org = infraction.t_org.id "
        . "LEFT JOIN artisanal.t_card c1 ON c1.id = infraction.infraction.owner_t_card "
        . "LEFT JOIN artisanal.t_nationality n1 ON n1.id = infraction.infraction.owner_t_nationality "
        . "LEFT JOIN artisanal.t_card c2 ON c2.id = infraction.infraction.fish_t_card_1 "
        . "LEFT JOIN artisanal.t_nationality n2 ON n2.id = infraction.infraction.fish_t_nationality_1 "
        . "LEFT JOIN artisanal.t_card c3 ON c3.id = infraction.infraction.fish_t_card_2 "
        . "LEFT JOIN artisanal.t_nationality n3 ON n3.id = infraction.infraction.fish_t_nationality_2 "
        . "LEFT JOIN artisanal.t_card c4 ON c4.id = infraction.infraction.fish_t_card_3 "
        . "LEFT JOIN artisanal.t_nationality n4 ON n4.id = infraction.infraction.fish_t_nationality_3 "
        . "LEFT JOIN artisanal.t_card c5 ON c5.id = infraction.infraction.fish_t_card_4 "
        . "LEFT JOIN artisanal.t_nationality n5 ON n5.id = infraction.infraction.fish_t_nationality_4 "
        . "WHERE id_fisherman_1 = '$id' "
        . "OR id_fisherman_2 = '$id' "
        . "OR id_fisherman_3 = '$id' "
        . "OR id_fisherman_4 = '$id' "
        . "AND infraction.id IS NOT NULL";

//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>
    <h3>Infractions li&eacute;es &agrave; ce p&ecirc;cheur</h3>
    <table id="small">
    <tr align="center"><td></td>
    <td><b>Date et Utilisateur</b></td>
    <td><b>ID</b></td>
    <td><b>Date infraction</b></td>
    <td><b>Type infraction</b></td>
    <td><b>Pirogue et Proprietaire</b></td>
    <td nowrap><b>P&ecirc;cheur 1 et 2</b></td>
    <td nowrap><b>Montant de l'infraction</b></td>
    <td nowrap><b>Montant pay&eacute;</b></td>
    <td><b>Regl&eacute;e</b></td>
    <td><b>Organisation</b></td>
    <td><b>Point GPS</b></td>
    </tr>

    <?php

    while ($results = pg_fetch_row($r_query)) {
        # infractions

        $query_inf = "SELECT t_infraction.infraction FROM infraction.infractions "
                . "LEFT JOIN infraction.t_infraction ON infraction.infractions.t_infraction = infraction.t_infraction.id "
                . "WHERE id_infraction = '$results[0]'";

        $r_query_inf = pg_query($query_inf);
        $nrows = pg_num_rows($r_query_inf);
        $results_inf = pg_fetch_row($r_query_inf);

        print "<tr align=center>";
        print "<td rowspan=$nrows>";
        print "<a href=\"./view_infraction.php?source=$source&table=$table&action=edit&id=$results[0]\">Voir</a>";
            if(right_write($_SESSION['username'],2,5)) {
            print "<br/><a href=\"./view_infractions_infraction.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_infractions_infraction.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";

        print "<td rowspan=$nrows nowrap>$results[1]<br/>$results[2]</td><td rowspan=$nrows nowrap>$results[3]</td><td rowspan=$nrows nowrap>$results[4]</td>";

        print "<td>$results_inf[0]</td>";

        if ($results[7] != '') {
            $query = "SELECT name, immatriculation FROM artisanal.pirogue WHERE id = '$results[7]'";
            $pirogue_name = pg_fetch_row(pg_query($query));

            print "<td rowspan=$nrows><a href=\"./view_pirogue.php?id=$results[7]\">$pirogue_name[0]<br/>$pirogue_name[1]</a>";
        } else {
            print "<td rowspan=$nrows>$results[8]<br/>$results[9]";
        }

        if (($results[7] != '' OR $results[8] != '' OR $results[9] != '') AND ($results[10] != '' OR $results[11] != '' OR $results[12] != '')) {
                print "<hr>";
        }

        if ($results[10] != '') {
            $query = "SELECT first_name, last_name FROM artisanal.owner WHERE id = '$results[10]'";
            $owner_name = pg_fetch_row(pg_query($query));
            print "<a href=\"./view_owner.php?id=$results[10]\">".strtoupper($owner_name[1])."<br/>".ucfirst($owner_name[0])."</a></td>";
        } else {
            print strtoupper($results[12])."<br/>".ucfirst($results[11])."</td>";
        }

        if ($results[18] != '') {
            $query = "SELECT first_name, last_name FROM artisanal.fisherman WHERE id = '$results[18]'";
            $fisherman_name = pg_fetch_row(pg_query($query));
            print "<td rowspan=$nrows><a href=\"./view_fisherman.php?id=$results[18]\">".strtoupper($fisherman_name[1])."<br/>".ucfirst($fisherman_name[0])."</a>";
        } else {
            print "<td rowspan=$nrows>".strtoupper($results[20])."<br/>".ucfirst($results[19])."</a>";
        }

        if (($results[19] != '' OR $results[20] != '' OR $results[21] != '') AND ($results[26] != '' OR $results[27] != '' OR $results[28] != '')) {
                print "<hr>";
        }

        if ($results[26] != '') {
            $query = "SELECT first_name, last_name FROM artisanal.fisherman WHERE id = '$results[26]'";
            $fisherman_name = pg_fetch_row(pg_query($query));
            print "<a href=\"./view_fisherman.php?id=$results[26]\">".strtoupper($fisherman_name[1])."<br/>".ucfirst($fisherman_name[0])."</a></td>";
        } else {
            print strtoupper($results[28])."<br/>".ucfirst($results[27])."</a></td>";
        }

        print "<td rowspan=$nrows>$results[55]</td>";
        print "<td rowspan=$nrows>$results[56]</td>";

        if ($results[63] == "t") {
            $val = '<b>REGLEE</b>';
        } else {
            $val = '';
        }

        print "<td rowspan=$nrows>$val</td>";

        print "<td rowspan=$nrows>$results[5]</td><td rowspan=$nrows>";
        if ($results[61] != '' and $results[62] != '') {
            print "<a href=\"view_point.php?X=$results[61]&Y=$results[62]\">".round($results[61],3)."E ".round($results[62],3)."N</a>";
        }
        print "</td></tr>";

        while($results_inf = pg_fetch_row($r_query_inf)) {
            print "<tr align=center><td>$results_inf[0]</td></tr>";
        }

    }

    print "</table><br/>";

}
?>

<br/>
<button onClick="goBack()">Retourner</button>
<?php
foot();
