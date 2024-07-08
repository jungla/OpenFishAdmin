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

#print "<h2>".label2name($source)." ".label2name($table)."</h2>";

        $query = "SELECT license.license, extract(year from date_v), "
            . "l1.license, l2.license, g1.gear, g2.gear, mesh_min, mesh_max, mesh_min_2, mesh_max_2, s1.site, s2.site, engine_brand, engine_cv, receipt, "
            . "CASE WHEN owner.t_nationality=7 THEN '100000' WHEN license.t_gear=4 THEN '200000' ELSE '150000' END, "
            . "agasa, t_coop.coop, id_pirogue, pirogue.name, pirogue.immatriculation, license.active, "
            . "owner.id, first_name, last_name, bday, t_card.card, idcard , address, t_nationality.nationality, telephone, photo_data, owner.comments, s2_2.site "
            . " FROM artisanal.license "
            . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
            . "LEFT JOIN artisanal.t_license l1 ON l1.id = artisanal.license.t_license "
            . "LEFT JOIN artisanal.t_license l2 ON l2.id = artisanal.license.t_license_2 "
            . "LEFT JOIN artisanal.t_gear g1 ON g1.id = artisanal.license.t_gear "
            . "LEFT JOIN artisanal.t_gear g2 ON g2.id = artisanal.license.t_gear_2 "
            . "LEFT JOIN artisanal.t_site s1 ON s1.id = artisanal.license.t_site "
            . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = artisanal.license.t_site_obb "
            . "LEFT JOIN artisanal.t_site_obb s2_2 ON s2_2.id = artisanal.license.t_site_obb_2 "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.license.id_pirogue "
            . "LEFT JOIN artisanal.owner ON artisanal.owner.id = artisanal.pirogue.id_owner "
            . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.owner.t_card "
            . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.owner.t_nationality "
            . "WHERE license.id = '$id'";

//print $query;

$results = pg_fetch_array(pg_query($query));

print "<h3>D&eacute;tails Autorisation P&ecirc;che - $results[0]</h3>";

?>

<table id="results">
<tr><td><b>Autorisation de p&ecirc;che</b></td><td><?php echo $results[0]; ?></td></tr>
<tr><td><b>Ann&eacute;e validit&eacute;</b></td><td><?php echo $results[1]; ?></td></tr>
<tr><td><b>Esp&egrave;ces cibles,<br/>engins et maille</b></td><td><?php echo $results[2]." - ".$results[4]." [$results[6]/$results[7]]"; ?></td></tr>
<tr><td><b>Esp&egrave;ces cibles,<br/>engins et maille supp.</b></td><td><?php echo $results[3]." - ".$results[5]." [$results[8]/$results[9]]"; ?></td></tr>
<tr><td><b>D&eacute;barcad&egrave;res</b></td><td><?php echo $results[11]." - ". $results[33]; ?></td></tr>
<tr><td><b>Site attach</b></td><td><?php echo $results[10]; ?></td></tr>
<tr><td><b>Coop&eacute;rative</b></td><td><?php echo $results[17]; ?></td></tr>
<tr><td><b>Marque et puissance (CV) moteur</b></td><td><?php echo $results[12].", ".$results[13]; ?></td></tr>
<tr><td><b>Quittance et montant du paiement</b></td><td><?php echo $results[14]." CFA:".$results[15]; ?></td></tr>
<tr><td><b>AGASA</b></td><td><?php echo $results[16]; ?></td></tr>
<tr><td><b>Nom et immatriculation pirogue</b></td><td><a href="./view_pirogue.php?id=<?php echo $results[18]; ?>"><?php echo $results[19]." - ".$results[20]; ?></a></td></tr>
</table>

<h3>D&eacute;tails du proprietaire</h3>
<table id="results">
<tr><td><b>Nom et prenom</b></td><td><?php echo "<a href=\"./view_owner.php?id=$results[22]\">". ucfirst($results[23])." ".strtoupper($results[24])."</a>"; ?></td></tr>
<tr><td><b>Date de naissance</b></td><td><?php echo $results[25]; ?></td></tr>
<tr><td><b>Type de pi&egrave;ce d'identit&eacute;</b></td><td><?php echo $results[26]; ?></td></tr>
<tr><td><b>Num&eacute;ro de pi&egrave;ce d'identit&eacute;</b></td><td><?php echo $results[27]; ?></td></tr>
<tr><td><b>Domicile</b></td><td><?php echo $results[28]; ?></td></tr>
<tr><td><b>Nationalit&eacute;</b></td><td><?php echo $results[29]; ?></td></tr>
<tr><td><b>Num&eacute;ro de t&eacute;l&eacute;phone</b></td><td><?php echo $results[30]; ?></td></tr>
</table>

<?php

$query = "SELECT carte.id, carte.datetime::date, carte.username, carte, fisherman.first_name, fisherman.last_name, carte.date_v, t_nationality.nationality, id_license, id_fisherman, license.active "
    . " FROM artisanal.carte "
    . "LEFT JOIN artisanal.license ON artisanal.license.id = artisanal.carte.id_license "
    . "LEFT JOIN artisanal.fisherman ON artisanal.fisherman.id = artisanal.carte.id_fisherman "
    . "LEFT JOIN artisanal.t_nationality ON artisanal.fisherman.t_nationality = artisanal.t_nationality.id "
    . "WHERE id_license = '$id' "
    . "ORDER BY datetime DESC";

//print $query;
$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {
    ?>
    <h3>Cartes enregistr&eacute;es sur cette Autorisation de P&ecirc;che</h3>
    <table>
    <tr align="center">
    <td></td>
    <td><b>Date et Utilisateur</b></td>
    <td><b>Card number</b></td>
    <td><b>P&ecirc;cheur</b></td>
    <td><b>Nationalit&eacute;</b></td>
    <td><b>Ann&eacute;e de validit&eacute;</b></td>
    </tr>
    <?php
    while ($results = pg_fetch_row($r_query)) {
        print "<tr align=\"center\"><td>";

        if($results[11] == 't' and $results[6] != '' and $results[7] != '') {
            print "<a href=\"./view_licenses_carte.php?source=$source&table=$table&action=imprimer&id=$results[0]\"><i class=\"material-icons\">local_printshop</i>Imprimer</a>";
        }

        print "</td><td nowrap>$results[1]<br/>$results[2]</td><td>$results[3]</td><td><a href=\"./view_fisherman.php?id=$results[9]\">".ucfirst($results[4])." ".strtoupper($results[5])."</a></td><td>$results[7]</td><td>$results[6]</td>"
        . "</tr>";
    }
    print "</table><br/>";
}

$query = "SELECT infraction.id, infraction.username, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, "
        . "owner_t_card, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_t_nationality_1, "
        . "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, "
        . "fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
        . "fish_idcard_4, fish_t_card_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
        . "amount, payment, n_dep, n_cdc, n_lib, comments FROM infraction.infraction "
        . "LEFT JOIN infraction.t_org ON infraction.infraction.t_org = infraction.t_org.id "
        . "WHERE id_pirogue = '$results[18]' AND infraction.id IS NOT NULL ORDER BY date_i";

//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>
    <h3>Infractions li&eacute;es &agrave; <?php print "$results[19] - [$results[20]]";?></h3>
    <table>
    <tr align="center">
    <td></td>
    <td><b>ID</b></td>
    <td><b>Date infraction</b></td>
    <td><b>Type infraction</b></td>
    <td><b>Nom du proprietaire</b></td>
    <td><b>Nom du p&ecirc;cheur 1</b></td>
    <td><b>Nom du p&ecirc;cheur 2</b></td>
    <td><b>Nom du p&ecirc;cheur 3</b></td>
    <td><b>Nom du p&ecirc;cheur 4</b></td>
    <td><b>Organisation</b></td>
    <td><b>Objets saisis</b></td>
    <td><b>Montant de l'infraction</b></td>
    <td><b>Montant Pay&eacute;</b></td>
    <td><b>Commentaires</b></td>
    </tr>

    <?php

    while ($results = pg_fetch_row($r_query)) {

        $query_i = "SELECT t_infraction.infraction FROM infraction.infractions LEFT JOIN infraction.t_infraction ON infraction.t_infraction.id = infraction.infractions.t_infraction WHERE id_infraction ='$results[0]'";
        $rquery_i = pg_query($query_i);
        $nrows = pg_num_rows($rquery_i);

        //print $query_i;

        $results_i = pg_fetch_row($rquery_i);

        print "<tr align=center><td rowspan=$nrows><a href=\"./view_infraction.php?id=$results[0]&source=infractions&table=infraction\">Voir</a></td>";
        print "<td rowspan=$nrows>$results[2]</td><td rowspan=$nrows nowrap>$results[3]</td>";

        print "<td>$results_i[0]</td>";

        if ($results[9] != '') {
            print "<td rowspan=$nrows><a href=\"./view_owner.php?id=$results[9]\">".strtoupper($results[11])."<br/>".ucfirst($results[10])."</a></td>";
        } else {
            print "<td rowspan=$nrows>".strtoupper($results[11])."<br/>".ucfirst($results[10])."</td>";
        }
        if ($results[16] != '') {
            print "<td rowspan=$nrows><a href=\"./view_fisherman.php?id=$results[16]\">".strtoupper($results[18])."<br/>".ucfirst($results[17])."</a></td>";
        } else {
            print "<td rowspan=$nrows>".strtoupper($results[18])."<br/>".ucfirst($results[17])."</a></td>";
        }
        if ($results[23] != '') {
            print "<td rowspan=$nrows><a href=\"./view_fisherman.php?id=$results[23]\">".strtoupper($results[25])."<br/>".ucfirst($results[24])."</a></td>";
        } else {
            print "<td rowspan=$nrows>".strtoupper($results[25])."<br/>".ucfirst($results[24])."</a></td>";
        }
        if ($results[30] != '') {
            print "<td rowspan=$nrows><a href=\"./view_fisherman.php?id=$results[30]\">".strtoupper($results[32])."<br/>".ucfirst($results[31])."</a></td>";
        } else {
            print "<td rowspan=$nrows>".strtoupper($results[32])."<br/>".ucfirst($results[31])."</a></td>";
        }
        if ($results[37] != '') {
            print "<td rowspan=$nrows><a href=\"./view_fisherman.php?id=$results[37]\">".strtoupper($results[39])."<br/>".ucfirst($results[38])."</a></td>";
        } else {
            print "<td rowspan=$nrows>".strtoupper($results[39])."<br/>".ucfirst($results[38])."</a></td>";
        }
        print "<td rowspan=$nrows>$results[4]</td><td rowspan=$nrows>";
        if ($results[44] !='') {print "[Pirogue: $results[44]]<br/>";}
        if ($results[45] !='') {print "[Moteur: $results[45]]<br/>";}
        if ($results[46] !='') {print "[Filet: $results[46]]<br/>";}
        if ($results[47] !='') {print "[Documents: $results[47]]<br/>";}
        print "</td><td rowspan=$nrows>$results[48]</td><td rowspan=$nrows>$results[49]</td><td rowspan=$nrows>$results[54]</td></tr>";

        while($results_i = pg_fetch_row($rquery_i)) {
            print "<tr align=center><td>$results_i[0]</td></tr>";
        }

    }

    print "</table><br/>";

}





?>

<br/>
<button onClick="goBack()">Retourner</button>
<br/>
<?php
foot();
