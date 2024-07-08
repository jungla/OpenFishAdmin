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

$_SESSION['filter']['f_duplicate'] = $_POST['f_duplicate'];

if ($_GET['f_duplicate'] != "") {$_SESSION['filter']['f_duplicate'] = $_GET['f_duplicate'];}
$f_duplicate = $_SESSION['filter']['f_duplicate'];

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $start = $_GET['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>

    <table id="small">
    <tr align="center">
    <td>
    <form method="get" action="<?php echo $self;?>?source=<?php echo $source;?>&table=<?php echo $table;?>&action=show" enctype="multipart/form-data">
      <input type="submit" name="action" value="Merge" />

    </td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Nom navire</b></td>
    <td><b>Autre noms</b></td>
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
    <td><b>Code & Type Balise</b></td>
    <td><b>Inconnue</b></td>
    </tr>

    <?php

    # datetime, username, name, immatriculation, t_pirogue, length, t_site, id_owner

    $query = "SELECT count(navire) FROM vms.navire WHERE navire NOT LIKE 'M\_%'";

    $pnum = pg_fetch_row(pg_query($query))[0];

    $query = "SELECT id, datetime::date, username, navire, other_names, flag, owners, fullname, radio, registration_ext,
    registration_int, registration_qrt, mobile, mmsi, imo, port, active, beacon, satellite, unknown
    FROM vms.navire WHERE navire NOT LIKE 'M\_%' ORDER BY navire OFFSET $start LIMIT $step";

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {
        print "<tr align=\"center\">";

        print "<td>";

        print "<input type='checkbox' class='single-checkbox' name='id[]' value='$results[0]' onclick='check_control()'>";

        print "</td>";

        print "<td>$results[1]<br/>$results[2]</td><td>$results[3]</td><td>$results[4]</td>"
                . "<td>$results[5]</td><td>$results[6]</td><td>$results[7]</td><td>$results[8]<br/>$results[9]<br/>$results[10]</td>"
                . "<td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td>"
                . "<td>$results[15]</td><td>$results[16]</td><td>$results[17]<br/>$results[18]</td><td>$results[19]</td>";

      print "</tr>";
    }

    print "</table></form>";

    pages($start,$step,$pnum,"./maintain_licenses_license.php?source=$source&table=$table&action=show");

    $controllo = 1;

} else if ($_GET['action'] == 'Merge') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id_1 = $_GET['id'][0];
    $id_2 = $_GET['id'][1];

    //find record info by ID
    $q_id = "SELECT id, datetime::date, username, navire, other_names, flag, owners, fullname, radio, registration_ext,
    registration_int, registration_qrt, mobile, mmsi, imo, port, active, beacon, satellite, unknown "
    . "FROM vms.navire "
    . "WHERE navire.id = '$id_1'";

    //print $q_id;
    $r_id = pg_query($q_id);
    $results_1 = pg_fetch_row($r_id);

    $q_id = "SELECT id, datetime::date, username, navire, other_names, flag, owners, fullname, radio, registration_ext,
    registration_int, registration_qrt, mobile, mmsi, imo, port, active, beacon, satellite, unknown "
    . "FROM vms.navire "
    . "WHERE navire.id = '$id_2'";

    //print $q_id;
    $r_id = pg_query($q_id);
    $results_2 = pg_fetch_row($r_id);

    ?>

    <form method="post" action="<?php echo $self;?>?source=<?php echo $source;?>&table=<?php echo $table;?>" enctype="multipart/form-data">
    <h3>D&eacute;tails Navire</h3>
    <!--
    MERGE CRITERIA
    - take present over absent
    - take most recent when both present
    - add cartes
    - add infractions
    -->
    <table id="small">
    <tr align="center">
    <td><b>Date & Utilisateur</b></td>
    <td><b>Nom navire</b></td>
    <td><b>Autre noms</b></td>
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
    <td><b>Code & Type Balise</b></td>
    <td><b>Inconnue</b></td>
    </tr>

    <?php

    print "<tr align=\"center\">";

    $results = [];

    for ($i = 0; $i <= count($results_1); $i++) {
      //print $results_1[$i]." - ".$results_2[$i]."<br/>";

      if ($results_1[$i] == '' OR $results_2[$i] == '') {
        $results[$i] = $results_1[$i].$results_2[$i];
      }
      if ($results_1[$i] != '' AND $results_2[$i] != '') {

        if($results_1[1] >= $results_2[1]) {
          $results[$i] = $results_1[$i];
        } else {
          $results[$i] = $results_2[$i];
        }
      }
    }

    print "<td>$results[1]<br/>$results[2]</td><td>$results[3]</td><td>$results[4]</td>"
            . "<td>$results[5]</td><td>$results[6]</td><td>$results[7]</td><td>$results[8]<br/>$results[9]<br/>$results[10]</td>"
            . "<td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td>"
            . "<td>$results[15]</td><td>$results[16]</td><td>$results[17]<br/>$results[18]</td><td>$results[19]</td>";
            ?>

    </tr>
    </table>

    <input type="submit" value="Enregistrer" name="submit"/>
    <input type="hidden" value="<?php echo $results; ?>" name="results[]"/>
    <input type="hidden" value="<?php echo $results_1[0]; ?>" name="id_new"/>
    <input type="hidden" value="<?php echo $results_2[0]; ?>" name="id_old"/>
    </form>
    <br/>
    <br/>

    <?php

}


if ($_POST['submit'] == "Enregistrer") {

  $id_old = $_POST['id_old'];
  $id_new = $_POST['id_new'];

  //find record info by ID
  $q_id = "SELECT id, datetime::date, username, navire, other_names, flag, owners, fullname, radio, registration_ext,
  registration_int, registration_qrt, mobile, mmsi, imo, port, active, beacon, satellite, unknown "
  . "FROM vms.navire "
  . "WHERE navire.id = '$id_new'";

  //print $q_id;
  $r_id = pg_query($q_id);
  $results_1 = pg_fetch_row($r_id);

  $q_id = "SELECT id, datetime::date, username, navire, other_names, flag, owners, fullname, radio, registration_ext,
  registration_int, registration_qrt, mobile, mmsi, imo, port, active, beacon, satellite, unknown "
  . "FROM vms.navire "
  . "WHERE navire.id = '$id_old'";

  //print $q_id;
  $r_id = pg_query($q_id);
  $results_2 = pg_fetch_row($r_id);

  $results = [];

  for ($i = 0; $i <= count($results_1); $i++) {
    //print $results_1[$i]." - ".$results_2[$i]."<br/>";

    if ($results_1[$i] == '' OR $results_2[$i] == '') {
      $results[$i] = $results_1[$i].$results_2[$i];
    }
    if ($results_1[$i] != '' AND $results_2[$i] != '') {

      if($results_1[1] >= $results_2[1]) {
        $results[$i] = $results_1[$i];
      } else {
        $results[$i] = $results_2[$i];
      }
    }
  }

  # UPDATE vms records
  # In the POINTS, we change name of the vessel
  # In the VESSELS, we change name of the vessel

  # OTHER TABLES that need to be updated are: seiners.route, seiners.accidentelle, trawlers.route, trawlers.route_accidentelle, crevette.lance, thon.lance

  # vms.navire
  # update navire
  $query = "UPDATE vms.navire SET "
  . "username = '$username', navire = '$results[3]', other_names = '$results[4]', flag = '$results[5]', owners = '$results[6]', fullname = '$results[7]', radio = '$results[8]', registration_ext = '$results[9]',"
  . "registration_int = '$results[10]', registration_qrt = '$results[11]', mobile = '$results[12]', mmsi = '$results[13]', imo = '$results[14]', port = '$results[15]', active = '$results[16]', beacon = '$results[17]', satellite = '$results[19]', unknown = '$results[20]' "
  . "WHERE navire.id = '$id_new'";
  $query = str_replace('\'\'', 'NULL', $query);
  print($query);
  pg_query($query);

  # delete
  $query = "DELETE FROM vms.navire WHERE navire.id = '$id_old'";
  $query = str_replace('\'\'', 'NULL', $query);
  print($query);
  pg_query($query);

  # vms.points
  $query = "UPDATE vms.points SET "
  . "navire_id = '$id_new' WHERE navire_id = '$id_old'";
  $query = str_replace('\'\'', 'NULL', $query);
  print($query);
  pg_query($query);

  # crevette.lance
  $query = "UPDATE crevette.lance SET "
  . "id_navire = '$id_new' WHERE id_navire = '$id_old'";
  $query = str_replace('\'\'', 'NULL', $query);
  print($query);
  pg_query($query);

  # thon.lance
  $query = "UPDATE thon.lance SET "
  . "id_navire = '$id_new' WHERE id_navire = '$id_old'";
  $query = str_replace('\'\'', 'NULL', $query);
  print($query);
  pg_query($query);

  # seiners.route
  $query = "UPDATE seiners.route SET "
  . "id_navire = '$id_new' WHERE id_navire = '$id_old'";
  $query = str_replace('\'\'', 'NULL', $query);
  print($query);
  pg_query($query);

  # seiners.route_accidentelle
  $query = "UPDATE seiners.route_accidentelle SET "
  . "id_navire = '$id_new' WHERE id_navire = '$id_old'";
  $query = str_replace('\'\'', 'NULL', $query);
  print($query);
  pg_query($query);

  # trawlers.route
  $query = "UPDATE trawlers.route SET "
  . "id_navire = '$id_new' WHERE id_navire = '$id_old'";
  $query = str_replace('\'\'', 'NULL', $query);
  print($query);
  pg_query($query);

  # trawlers.route_accidentelle
  $query = "UPDATE trawlers.route_accidentelle SET "
  . "id_navire = '$id_new' WHERE id_navire = '$id_old'";
  $query = str_replace('\'\'', 'NULL', $query);
  print($query);
  pg_query($query);

  header("Location: ".$_SESSION['http_host']."/industrial/maintain_licenses_license.php?source=$source&table=$table&action=show");

}

foot();
