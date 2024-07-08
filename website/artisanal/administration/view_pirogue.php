<?php
require("../../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
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

$query = "SELECT pirogue.id, pirogue.datetime, pirogue.username, name, immatriculation, t_pirogue.pirogue, length, id_owner, owner.first_name, owner.last_name, plate FROM artisanal.pirogue "
        . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue "
        . "LEFT JOIN artisanal.owner ON artisanal.owner.id = artisanal.pirogue.id_owner "
        . "WHERE pirogue.id = '$id'";

#print $query;

$results = pg_fetch_array(pg_query($query));


print "<h3>D&eacute;tails Pirogue - $results[3] $results[4]</h3>";
?>

<table id="results">
    <tr><td><b>Nom de la pirogue</b></td><td><?php echo $results[3]; ?></td></tr>
    <tr><td><b>Num&eacute;ro d'immatriculation</b></td><td><?php echo $results[4]; ?></td></tr>
    <tr><td><b>Num&eacute;ro plaque</b></td><td><?php echo $results[10]; ?></td></tr>
    <tr><td><b>Type</b></td><td><?php echo $results[5]; ?></td></tr>
    <tr><td><b>Longueur</b></td><td><?php echo $results[6]; ?></td></tr>
    <tr><td><b>Propri&eacute;taire</b></td><td><a href="./view_owner.php?id=<?php echo $results[7]; ?>"><?php echo $results[8]." ".$results[9]; ?></a></td></tr>
</table>

<?php

$query = "SELECT license.id, license.datetime::date, license.username, license.license, extract(year from date_v), "
            . "l1.license, l2.license, g1.gear, g2.gear, mesh_min, mesh_max, mesh_min_2, mesh_max_2, license.length, license.length_2, s1.site, s2.site, engine_brand, "
            . "engine_cv, receipt, payment, agasa, id_pirogue, pirogue.name, pirogue.immatriculation, t_coop.coop, license.active"
            . " FROM artisanal.license "
            . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
            . "LEFT JOIN artisanal.t_license l1 ON l1.id = artisanal.license.t_license "
            . "LEFT JOIN artisanal.t_license l2 ON l2.id = artisanal.license.t_license_2 "
            . "LEFT JOIN artisanal.t_gear g1 ON g1.id = artisanal.license.t_gear "
            . "LEFT JOIN artisanal.t_gear g2 ON g2.id = artisanal.license.t_gear_2 "
            . "LEFT JOIN artisanal.t_site s1 ON s1.id = artisanal.license.t_site "
            . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = artisanal.license.t_site_obb "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.license.id_pirogue "
            . "WHERE id_pirogue = '$id' ";

//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>
    <h3>Autorisations enregistr&eacute;es sur Pirogue</h3>

    <table id='small'>
    <tr align="center">
    <td><b>Autorisation de p&ecirc;che</b></td>
    <td><b>Ann&eacute;e validit&eacute;</b></td>
    <td nowrap><b>Esp&egrave;ces cibles,<br/>engins et filet</b></td>
    <td nowrap><b>Esp&egrave;ces cibles,<br/> engins et filet supp.</b></td>
    <td><b>D&eacute;barcad&egrave;res</b></td>
    <td><b>Marque et puissance du moteur</b></td>
    <td><b>Quittance, Montant et AGASA</b></td>
    <td><b>Coop&eacute;rative</b></td>
    <td><b>Actif</b></td>
    </tr>

    <?php


    while ($results = pg_fetch_row($r_query)) {
        if ($results[26] == 't') {
            $val = 'Oui';
        } else {
            $val = 'Non';
        }

        print "<tr align=\"center\"><td><b>#$results[3]</b></td><td nowrap>$results[4]</td>";
        print "<td nowrap>$results[5]<br/>$results[7]<br/>";
        if ($results[13] != '') {
          print "$results[13]:";
        }
        print "$results[9]";
        if ($results[10] != '') {
          print "-$results[10]";
        }
        print "</td>";

        print "<td nowrap>$results[6]<br/>$results[8]<br/>";
        if ($results[14] != '') {
          print "$results[14]:";
        }
        print "$results[11]";
        if ($results[12] != '') {
          print "-$results[12]";
        }
        print "</td>";

        print "<td nowrap>$results[15]<br/>$results[16]</td><td>$results[17]<br/>$results[18]</td><td nowrap>quittance : $results[19]<br/>CFA : $results[20]<br/>AGASA : $results[21]</td>"
        . "<td>$results[25]</td><td>$val</td>";


        }

    print "</table><br/>";
}

// INFRACTIONS

$query = "SELECT infraction.id, infraction.username, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, "
        . "owner_t_card, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_t_nationality_1, "
        . "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, "
        . "fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
        . "fish_idcard_4, fish_t_card_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
        . "amount, payment, n_dep, n_cdc, n_lib, comments FROM infraction.infraction "
        . "LEFT JOIN infraction.t_org ON infraction.infraction.t_org = infraction.t_org.id "
        . "WHERE id_pirogue = '$id' AND infraction.id IS NOT NULL ORDER BY date_i";

//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>
    <h3>Infractions li&eacute;es &agrave; cette Pirogue</h3>
    <table id='small'>
    <tr align="center">
    <td></td>
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
        print "<td rowspan=$nrows nowrap>$results[3]</td>";

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

// CAPTURES LOGBOOKS

$query = "SELECT SUM(wgt_spc), EXTRACT(year FROM datetime_d) FROM artisanal.captures "
. "LEFT JOIN artisanal.maree ON artisanal.maree.id = artisanal.captures.id_maree "
. "WHERE maree.id_pirogue = '$id' AND t_study ='1' "
. "GROUP BY EXTRACT(year FROM datetime_d) ORDER BY EXTRACT(year FROM datetime_d) ";

//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>
    <h3>Production Annuelle Pirogue Logbook</h3>
    <table>
    <tr align="center">
    <td></td>
    <td><b>Annee</b></td>
    <td><b>Production [kg]</b></td>
    <td><b>Composition</b></td>
    </tr>

    <?php
    while ($results = pg_fetch_row($r_query)) {
      print "<tr align=\"center\">";

      print "<td></td><td>$results[1]</td><td nowrap>$results[0]kg</td>";

      if ($results[1] != '') {
        print "<td><img src=\"./graph_records_captures_year.php?id=$id&year=$results[1]\"></td>";
      } else {
        print "<td></td>";
      }

    }
    print "</table><br/>";


}

// CAPTURES LOGBOOKS

$query = "SELECT SUM(wgt_spc), EXTRACT(year FROM datetime_d) FROM artisanal.captures "
. "LEFT JOIN artisanal.maree ON artisanal.maree.id = artisanal.captures.id_maree "
. "WHERE maree.id_pirogue = '$id' AND t_study ='2' AND EXTRACT(year FROM datetime_d) IS NOT NULL "
. "GROUP BY EXTRACT(year FROM datetime_d) ORDER BY EXTRACT(year FROM datetime_d) ";

//print $query;

$r_query = pg_query($query);

if (pg_num_rows($r_query) > 0) {

    ?>
    <h3>Production Annuelle Pirogue Enqueteurs</h3>
    <table>
    <tr align="center">
    <td><b>Annee</b></td>
    <td><b>Production total [kg]</b></td>
    <td><b>Composition mensuel</b></td>
    </tr>

    <?php
    while ($results = pg_fetch_row($r_query)) {
      print "<tr align=\"center\">";
      print "<td><b>$results[1]</b></td>";

      print "<td nowrap>";
      if ($results[0] != '') {
        print "<b>$results[0]kg</b>";
        print "</br><img src=\"./graph_records_captures_year_large.php?id=$id&year=$results[1] \">";
      }
      print "</td>";

      if ($results[0] != '') {

        print "<td><table>";

        $query_m = "SELECT SUM(wgt_spc), EXTRACT(month FROM maree.datetime_d) "
        . " FROM artisanal.captures "
        . " LEFT JOIN fishery.species ON fishery.species.id = artisanal.captures.id_species "
        . " LEFT JOIN artisanal.maree ON artisanal.maree.id = artisanal.captures.id_maree "
        . " WHERE id_pirogue = '$id' AND EXTRACT(year FROM maree.datetime_d) = '$results[1]' AND fishery.species.francaise IS NOT NULL"
        . " GROUP BY EXTRACT(month FROM datetime_d) ORDER BY EXTRACT(month FROM datetime_d) ASC";

        //print $query_m;
        $r_query_m = pg_query($query_m);

        while ($results_m = pg_fetch_row($r_query_m)) {

          print "<tr><td>";

          if($results_m[0] != '') {

            print "<td>";

            print "<img src=\"./graph_records_captures_month.php?id=$id&year=$results[1]&month=$results_m[1] \"></td>";


          }
          print "</td></tr>";

        }

        print "</table></td>";
        } else {
        print "<td></td>";
      }
      print "</tr>";
    }
    print "</table><br/>";


}
?>

<br/>
<button onClick="goBack()">Retourner</button>
<?php
foot();
