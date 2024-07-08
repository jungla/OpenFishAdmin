<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'maintenance';
$_SESSION['where'][1] = 'maintenance';

$username = $_SESSION['username'];
top();

$self = filter_input(INPUT_SERVER, 'PHP_SELF');
$host = filter_input(INPUT_SERVER, 'HTTP_HOST');

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_family'] = $_POST['f_family'];
$_SESSION['filter']['f_genus'] = $_POST['f_genus'];
$_SESSION['filter']['f_category'] = $_POST['f_category'];
$_SESSION['filter']['f_francaise'] = $_POST['f_francaise'];
$_SESSION['filter']['f_FAO'] = $_POST['f_FAO'];

if ($_GET['f_family'] != "") {$_SESSION['filter']['f_family'] = $_GET['f_family'];}
if ($_GET['f_genus'] != "") {$_SESSION['filter']['f_genus'] = $_GET['f_genus'];}
if ($_GET['f_category'] != "") {$_SESSION['filter']['f_category'] = $_GET['f_category'];}
if ($_GET['f_francaise'] != "") {$_SESSION['filter']['f_francaise'] = $_GET['f_francaise'];}
if ($_GET['f_FAO'] != "") {$_SESSION['filter']['f_FAO'] = $_GET['f_FAO'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {

  if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

  $start = $_SESSION['start'];

  if (!isset($start) OR $start<0) $start = 0;

  $step = 50;

  print "<h2>Listes Especes</h2>";

  ?>
  <form method="post" action="<?php echo $self;?>?source=maintenance&action=show" enctype="multipart/form-data">
    <fieldset>

      <table id="no-border">
        <tr>
          <td><b>Famille</b></td>
          <td><b>Genus</b></td>
          <td><b>Categorie</b></td>
          <td><b>Nom francaise</b></td>
          <td><b>Code FAO</b></td>
        </tr>
        <tr>
          <td>
            <select name="f_family">
              <option value="family" selected="selected">Tous</option>
              <?php
              $result = pg_query("SELECT DISTINCT family FROM fishery.species WHERE family IS NOT NULL ORDER BY family");
              while($row = pg_fetch_row($result)) {
                if ("'".$row[0]."'" == $_SESSION['filter']['f_family']) {
                  print "<option value=\"'$row[0]'\" selected=\"selected\">$row[0]</option>";
                } else {
                  print "<option value=\"'$row[0]'\">$row[0]</option>";
                }
              }
              ?>
            </select>
          </td>
          <td>
            <select name="f_genus">
              <option value="genus">Tous</option>
              <?php
              $result = pg_query("SELECT DISTINCT genus FROM fishery.species WHERE genus IS NOT NULL ORDER BY genus");
              while($row = pg_fetch_row($result)) {
                if ("'".$row[0]."'" == $_SESSION['filter']['f_genus']) {
                  print "<option value=\"'$row[0]'\" selected=\"selected\">$row[0]</option>";
                } else {
                  print "<option value=\"'$row[0]'\">$row[0]</option>";
                }
              }
              ?>
            </select>
          </td>
          <td>
            <select name="f_category">
              <option value="">Tous</option>
              <?php
              $result = pg_query("SELECT split_part(category,',',1) FROM fishery.species WHERE split_part(category,',',1) != ''
              UNION
              SELECT split_part(category,',',2) FROM fishery.species WHERE split_part(category,',',2) != ''
              UNION
              SELECT split_part(category,',',3) FROM fishery.species WHERE split_part(category,',',3) != ''");
              while($row = pg_fetch_row($result)) {
                if ($row[0] == $_SESSION['filter']['f_category']) {
                  print "<option value=\"$row[0]\" selected=\"selected\">$row[0]</option>";
                } else {
                  print "<option value=\"$row[0]\">$row[0]</option>";
                }
              }
              ?>
            </select>
          </td>
          <td>
            <input type="text" size="20" name="f_francaise" value="<?php echo $_SESSION['filter']['f_francaise']?>"/>
          </td>
          <td>
            <input type="text" size="20" name="f_FAO" value="<?php echo $_SESSION['filter']['f_FAO']?>"/>
          </td>

        </tr>
      </table>
      <input type="submit" name="Filter" value="filter" />
    </fieldset>
  </form>


  <br/>

  <table id="small">
    <tr align="center"><td><b>Actif</b></td>
      <td><b>Nom francaise</b></td>
      <td><b>Famille</b></td>
      <td><b>Genus</b></td>
      <td><b>Espece</b></td>
      <td><b>FAO alpha-code</b></td>
      <td><b>Art.</b></td>
      <td><b>Ind.</b></td>
      <td><b>Obs.</b></td>
      <td><b>IUCN status</b></td>
      <td></td></tr>
    </tr>

    <?php

    if ($_SESSION['filter']['f_family'] != "" OR $_SESSION['filter']['f_genus'] != "" OR $_SESSION['filter']['f_category'] != "" OR $_SESSION['filter']['f_francaise'] != "" OR $_SESSION['filter']['f_FAO'] != "") {

      $_SESSION['start'] = 0;

      $query = "SELECT count(*) FROM fishery.species "
      . "WHERE (species.family=".$_SESSION['filter']['f_family']." OR species.family IS NULL) "
      . "AND (species.genus = ".$_SESSION['filter']['f_genus']." OR species.genus IS NULL) "
      . "AND (species.category LIKE '%".$_SESSION['filter']['f_category']."%') ";

      $pnum = pg_fetch_row(pg_query($query))[0];

      if ($_SESSION['filter']['f_francaise'] != "" OR $_SESSION['filter']['f_FAO'] != "") {
        $query = "SELECT *, "
        . " coalesce(similarity(fishery.species.francaise, '".$_SESSION['filter']['f_francaise']."'),0) + "
        . " coalesce(similarity(fishery.species.FAO, '".$_SESSION['filter']['f_FAO']."'),0) as score "
        . "FROM fishery.species "
        . "WHERE (species.family=".$_SESSION['filter']['f_family']." OR species.family IS NULL) "
        . "AND (species.genus = ".$_SESSION['filter']['f_genus']." OR species.genus IS NULL) "
        . "AND (species.category LIKE '%".$_SESSION['filter']['f_category']."%') "
        . "ORDER BY score DESC OFFSET $start LIMIT $step";

      } else {
        $query = "SELECT * FROM fishery.species "
        . "WHERE (species.family=".$_SESSION['filter']['f_family']." OR species.family IS NULL) "
        . "AND (species.genus = ".$_SESSION['filter']['f_genus']." OR species.genus IS NULL) "
        . "AND (species.category LIKE '%".$_SESSION['filter']['f_category']."%') "
        . "ORDER BY family, genus, species OFFSET $start LIMIT $step";
      }

    } else {
      $query = "SELECT count(*) FROM fishery.species";

      $pnum = pg_fetch_row(pg_query($query))[0];

      $query = "SELECT * FROM fishery.species "
      . "ORDER BY family, genus, species OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

      print "<tr align=\"center\">";

      print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\">";
      print "<input type=\"hidden\" value=\"$table\" name=\"table\"/>";
      print "<input type=\"hidden\" value=\"$results[0]\" name=\"id\"/>";

      print "<td>";

      print "<select name=\"active\">";
      if ($results[9] == 't') {
        print "<option value=\"true\" selected=\"selected\">Oui</option><option value=\"false\" >Non</option>";
      } else {
        print "<option value=\"true\" >Oui</option><option value=\"false\" selected=\"selected\">Non</option>";
      }
      print "</td>";

      print "<td><input type=\"text\" size=\"20\" name=\"francaise\" value=\"$results[1]\" /></td>";
      print "<td><input type=\"text\" size=\"15\" name=\"family\" value=\"$results[2]\" /></td>";
      print "<td><input type=\"text\" size=\"15\" name=\"genus\" value=\"$results[3]\" /></td>";
      print "<td><input type=\"text\" size=\"10\" name=\"species\" value=\"$results[4]\" /></td>";
      print "<td><input type=\"text\" size=\"3\" name=\"fao\" value=\"$results[5]\" /></td>";

      print "<td><input type=\"checkbox\" size=\"10\" name=\"art\" ";
      if (strpos($results[7],'artisanal') !== false) {print "checked";}
      print " /></td>";

      print "<td><input type=\"checkbox\" size=\"10\" name=\"ind\" ";
      if (strpos($results[7],'industrial') !== false) {print "checked";}
      print " /></td>";

      print "<td><input type=\"checkbox\" size=\"10\" name=\"obs\" ";
      if (strpos($results[7],'observers') !== false) {print "checked";}
      print " /></td>";

      print "<td><input type=\"text\" size=\"10\" name=\"iucn\" value=\"$results[8]\" /></td>";

      print "<td><input type=\"submit\" value=\"Enregistrer\" name=\"submit\"/></td>";

      print "</form>";
      print "</tr>";

    }
    print "</table>";
    pages($start,$step,$pnum,'./view_maintenance_species.php?source=species&action=show&f_FAO='.$_SESSION['filter']['f_FAO'].'&f_category='.$_SESSION['filter']['f_category'].'&f_family='.$_SESSION['filter']['f_family'].'&f_francaise='.$_SESSION['filter']['f_francaise'].'&f_genus='.$_SESSION['filter']['f_genus']);

  }

  if ($_POST['submit'] == "Effacer") {
    $id = $_POST['id'];
    $query = "DELETE FROM fishery.species WHERE id = '$id'";

    if(!pg_query($query)) {
      msg_queryerror();
    } else {
      header("Location: ".$_SESSION['http_host']."/maintenance/view_maintenance_species.php?source=$source&action=show");
    }
    $controllo = 1;

  }

  if ($_POST['submit'] == "Enregistrer") {

      # username, t_site, first_name, last_name, bday, t_nationality, t_card, idcard, telephone, address, photo_path
      $active = $_POST['active'];
      $family = $_POST['family'];
      $genus = $_POST['genus'];
      $species = $_POST['species'];
      $francaise = $_POST['francaise'];
      $fao = $_POST['fao'];
      $iucn = $_POST['iucn'];


      if ($_POST['ind'] == 'on') {
        $category = 'industrial,';
      }

      if ($_POST['art'] == 'on') {
        $category = $category.'artisanal,';
      }

      if ($_POST['obs'] == 'on') {
        $category = $category.'observers,';
      }

      $category = substr($category,0,-1);

      $query = "UPDATE fishery.species SET "
      . "family = '$family', genus = '$genus', species = '$species', fao = '$fao', category = '$category', francaise = '$francaise', iucn = '$iucn', active = $active "
      . " WHERE id = '".$_POST['id']."'";

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
      //        print $query;
      msg_queryerror();
    } else {
      //print $query;
      header("Location: ".$_SESSION['http_host']."/maintenance/view_maintenance_species.php?source=$source&action=show");
    }
  }

  foot();
