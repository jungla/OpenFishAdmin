<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'fishery';
$_SESSION['where'][1] = 'species';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>

    <table>
    <tr align="center">
      <td><b>Francaise</b></td>
      <td><b>Famille</b></td>
      <td><b>Genus</b></td>
      <td><b>Espece</b></td>
      <td><b>FAO</b></td>
      <td><b>OBS</b></td>
      <td><b>IUCN</b></td>
      <td></td>
    </tr>

    <?php
    $_SESSION['start'] = 0;

    $query = "SELECT count(*) FROM fishery.species";
    $pnum = pg_fetch_row(pg_query($query))[0];

    $query = "SELECT id, francaise, family, genus, species, fao, obs, iucn FROM fishery.species
    ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species
    OFFSET $start LIMIT $step";
    //print $query;

    $r_query = pg_query($query);


    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\">";
        print "<input type=\"hidden\" value=\"$results[0]\" name=\"id\"/>";
        print "<td><input type=\"text\" size=\"20\" name=\"francaise\" value=\"$results[1]\" /></td>";
        print "<td><input type=\"text\" size=\"10\" name=\"family\" value=\"$results[2]\" /></td>";
        print "<td><input type=\"text\" size=\"10\" name=\"genus\" value=\"$results[3]\" /></td>";
        print "<td><input type=\"text\" size=\"10\" name=\"species\" value=\"$results[4]\" /></td>";
        print "<td><input type=\"text\" size=\"5\" name=\"fao\" value=\"$results[5]\" /></td>";
        print "<td><input type=\"text\" size=\"5\" name=\"obs\" value=\"$results[6]\" /></td>";
        print "<td><input type=\"text\" size=\"10\" name=\"iucn\" value=\"$results[7]\" /></td>";
        print "<td><input type=\"submit\" value=\"Enregistrer\" name=\"submit\"/></td>";
        print "</form>";
        print "</tr>";

    }
    print "</table>";

    pages($start,$step,$pnum,'./view_fishery_species.php?source=&table=&action=show');

}

if ($_POST['submit'] == "Effacer") {
  $id = $_POST['id'];
  $query = "DELETE FROM fishery.species WHERE id = '$id'";

  print $query;
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/fishery/view_fishery_species.php?source=$source&table=$table&action=show");
    }
    $controllo = 1;

}

if ($_POST['submit'] == "Enregistrer") {

    # username, t_site, first_name, last_name, bday, t_nationality, t_card, idcard, telephone, address, photo_path
    $francaise = htmlspecialchars($_POST['francaise'], ENT_QUOTES);
    $family = htmlspecialchars($_POST['family'], ENT_QUOTES);
    $genus = htmlspecialchars($_POST['genus'], ENT_QUOTES);
    $species = htmlspecialchars($_POST['species'], ENT_QUOTES);
    $fao = htmlspecialchars($_POST['fao'], ENT_QUOTES);
    $obs = htmlspecialchars($_POST['obs'], ENT_QUOTES);
    $iucn = htmlspecialchars($_POST['iucn'], ENT_QUOTES);

    $query = "UPDATE fishery.species SET "
    . " francaise = '$francaise', "
    . " family = '$family', "
    . " genus = '$genus', "
    . " species = '$species', "
    . " fao = '$fao', "
    . " obs = '$obs', "
    . " iucn = '$iucn' "
    . " WHERE id = '".$_POST['id']."'";

    $query = str_replace('\'\'', 'NULL', $query);

    print $query;

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/fishery/view_fishery_species.php?source=$source&table=$table&action=show");
    }
}

foot();
