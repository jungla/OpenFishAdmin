<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'infractions';
$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
$id = $_GET['id'];

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

print "<h2>".label2name($source)." ".label2name($table)."</h2>";

$query = "SELECT infraction.id, infraction.username, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, immatriculation, "
        . "id_owner, owner_first, owner_last, owner_idcard, "
        . "c1.card, n1.nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, c2.card, n2.nationality, "
        . "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, c3.card, n3.nationality, fish_telephone_2, id_fisherman_3, "
        . "fish_first_3, fish_last_3, fish_idcard_3, c4.card, n4.nationality, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
        . "fish_idcard_4, c5.card, n5.nationality, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
        . "amount, payment, n_dep, n_cdc, n_lib, comments, st_x(location), st_y(location) FROM infraction.infraction "
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
        . "WHERE infraction.id = '$id'";

//print $query;
$results = pg_fetch_array(pg_query($query));

if ($results[6] != '') {
  $query = "SELECT name, immatriculation FROM artisanal.pirogue WHERE id = '$results[6]'";
  $pirogue = pg_fetch_row(pg_query($query));
  print "<h3>$pirogue[0] - $pirogue[1] - d&eacute;tails infraction</h3>";
} else {
  print "<h3>$results[7] - $results[8] - d&eacute;tails infraction</h3>";
}

?>

<table id="results">
    <table>
    <tr><td><b>ID PV</b></td><td><?php print $results[2];?></td></tr>
    <tr><td><b>Date infraction</b></td><td><?php print $results[3];?></td></tr>
    <tr><td><b>Type infraction</b></td>
        <td>
            <?php
            $query = "SELECT t_infraction.infraction FROM infraction.infractions LEFT JOIN infraction.t_infraction ON t_infraction.id = infractions.t_infraction WHERE id_infraction = '$results[0]'";
            $rquery = pg_query($query);
            while($results_i = pg_fetch_row($rquery)) {
                print "$results_i[0]<br/>";
            }
            ?>
        </td>
    </tr>
    <tr><td><b>Agence et agent de contr&ocirc;le</b></td><td><?php print $results[4]." ".$results[5];?></td></tr>
<tr><td><b>D&eacute;tails Pirogue</b></td><td>
  <?php
if ($results[6] != '') {
  print "<a href=\"./view_pirogue.php?id=$results[6]\">";
  echo $pirogue[0]." [".$pirogue[1]."]";
} elseif ($results[7] != '' AND $results[8] != '') {
  echo $results[7]." [".$results[8]."]";
}
?></td></tr>
    <tr><td><b>D&eacute;tails Proprietaire</b></td><td>
    <?php
      if ($results[9] != '') {
        $query = "SELECT first_name, last_name FROM artisanal.owner WHERE id ='$results[9]'";
        $owner = pg_fetch_row(pg_query($query));
        print "<a href=\"./view_owner.php?id=$results[9]\">".strtoupper($owner[0])." ".ucfirst($owner[1]);
      } else {
        if ($results[10] == '' AND $results[11] == '') {
          print "Aucun details";
        } else {
          print "Nom : ".strtoupper($results[11])."<br/>Prenom : ".ucfirst($results[10]);
          print "<br/>Card : ".$results[13]." ".$results[12];
          print "<br/>Nationalite : ".$results[14];
          print "<br/>Telephone : ".$results[15];
        }
      }
    ?></td></tr>
    <tr><td><b>D&eacute;tails P&ecirc;cheur 1</b></td><td>
    <?php
      if ($results[16] != '') {
        $query = "SELECT first_name, last_name FROM artisanal.fisherman WHERE id ='$results[16]'";
        $owner = pg_fetch_row(pg_query($query));
        print "<a href=\"./view_fisherman.php?id=$results[16]\">".strtoupper($owner[0])." ".ucfirst($owner[1]);
      } else {
        if ($results[18] == '' AND $results[17] == '') {
          print "Aucun details";
        } else {
          print "Nom : ".strtoupper($results[18])."<br/>Prenom : ".ucfirst($results[17]);
          print "<br/>Card : ".$results[20]." ".$results[19];
          print "<br/>Nationalite : ".$results[21];
          print "<br/>Telephone : ".$results[22];
        }
      }
    ?></td></tr>
    <tr><td><b>D&eacute;tails P&ecirc;cheur 2</b></td><td>
    <?php
      if ($results[23] != '') {
        $query = "SELECT first_name, last_name FROM artisanal.fisherman WHERE id ='$results[23]'";
        $owner = pg_fetch_row(pg_query($query));
        print "<a href=\"./view_fisherman.php?id=$results[23]\">".strtoupper($owner[0])." ".ucfirst($owner[1]);
      } else {
        if ($results[24] == '' AND $results[25] == '') {
          print "Aucun details";
        } else {
          print "Nom : ".strtoupper($results[25])."<br/>Prenom : ".ucfirst($results[24]);
          print "<br/>Card : ".$results[27]." ".$results[26];
          print "<br/>Nationalite : ".$results[28];
          print "<br/>Telephone : ".$results[29];
        }
      }
    ?></td></tr>
    <tr><td><b>D&eacute;tails P&ecirc;cheur 3</b></td><td>
    <?php
      if ($results[30] != '') {
        $query = "SELECT first_name, last_name FROM artisanal.fisherman WHERE id ='$results[30]'";
        $owner = pg_fetch_row(pg_query($query));
        print "<a href=\"./view_fisherman.php?id=$results[30]\">".strtoupper($owner[0])." ".ucfirst($owner[1]);
      } else {
        if ($results[31] == '' AND $results[32] == '') {
          print "Aucun details";
        } else {
          print "Nom : ".strtoupper($results[32])."<br/>Prenom : ".ucfirst($results[31]);
          print "<br/>Card : ".$results[34]." ".$results[33];
          print "<br/>Nationalite : ".$results[35];
          print "<br/>Telephone : ".$results[36];
        }
      }
    ?></td></tr>
    <tr><td><b>D&eacute;tails P&ecirc;cheur 4</b></td><td>
    <?php
      if ($results[37] != '') {
        $query = "SELECT first_name, last_name FROM artisanal.fisherman WHERE id ='$results[37]'";
        $owner = pg_fetch_row(pg_query($query));
        print "<a href=\"./view_fisherman.php?id=$results[37]\">".strtoupper($owner[0])." ".ucfirst($owner[1]);
      } else {
        if ($results[38] == '' AND $results[39] == '') {
          print "Aucun details";
        } else {
          print "Nom : ".strtoupper($results[39])."<br/>Prenom : ".ucfirst($results[38]);
          print "<br/>Card : ".$results[41]." ".$results[40];
          print "<br/>Nationalite : ".$results[42];
          print "<br/>Telephone : ".$results[43];
      }
      }
    ?></td></tr>
    <tr><td><b>Pirogue saisis</b></td><td><?php print $results[44];?></td></tr>
    <tr><td><b>Moteur saisis</b></td><td><?php print $results[45];?></td></tr>
    <tr><td><b>Filet saisis</b></td><td><?php print $results[46];?></td></tr>
    <tr><td><b>Documents saisis</b></td><td><?php print $results[47];?></td></tr>
    <tr><td><b>Autre saisis</b></td><td><?php print $results[48];?></td></tr>
    <tr><td><b>Montant de l'infraction</b></td><td><?php print $results[49];?></td></tr>
    <tr><td><b>Montant pay&eacute;</b></td><td><?php print $results[50];?></td></tr>
    <tr><td><b>N&deg; d'ordre de versamente</b></td><td><?php print $results[51];?></td></tr>
    <tr><td><b>N&deg; Bordereau CDC</b></td><td><?php print $results[52];?></td></tr>
    <tr><td><b>N&deg; d'acte liberatoire</b></td><td><?php print $results[53];?></td></tr>
    <tr><td><b>Point GPS</b></td><td><?php    if ($results[56] != '' and $results[55] != '') {
        print round($results[55],3)."E ".round($results[56],3)."N";
    }
    ?>
    </td></tr>

</table>

<br/>
<?php
if ($results[56] != ''and $results[55] != '') {
  $lon = $results[55];
  $lat = $results[56];

  print "<div id=\"map\" style=\"height: 400px; width:100%; border: 1px solid black; float:right; margin-left:5%; margin-top:1em;\"></div>";

  print "<script>

        function initMap() {
          var myLatLng = {lat: $lat, lng: $lon};

          var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: myLatLng
          });

          map.data.loadGeoJson('https://data.gabonbleu.org/shapefiles/AiresProtegeesAquatiques_20170601_Final_EPSG4326.geojson');
          map.data.loadGeoJson('https://data.gabonbleu.org/shapefiles/Aires_Protegees.geojson');

          var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'Hello World!'
          });
        }
      </script>
  <script async defer
  src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyBI5MQWC4N5SgUXs989_7MTDkQghaiGUuA&callback=initMap\">
  </script>";
}
?>

<button onClick="goBack()">Retourner</button>
<?php
foot();
