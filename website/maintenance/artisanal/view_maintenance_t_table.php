<?php
require("../../top_foot.inc.php");


$_SESSION['where'][0] = 'maintenance';
$_SESSION['where'][1] = 'maintenance';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_t_nationality'] = $_POST['f_t_nationality'];
$_SESSION['filter']['s_fish_name'] = str_replace('\'','',$_POST['s_fish_name']);
$_SESSION['filter']['s_fish_id'] = str_replace('\'','',$_POST['s_fish_id']);

if ($_GET['f_t_nationality'] != "") {$_SESSION['filter']['f_t_nationality'] = $_GET['f_t_nationality'];}
if ($_GET['s_fish_name'] != "") {$_SESSION['filter']['s_fish_name'] = $_GET['s_fish_name'];}
if ($_GET['s_fish_id'] != "") {$_SESSION['filter']['s_fish_id'] = $_GET['s_fish_id'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)."</h2>";

    ?>

    <?php

    if ($table == 'artisanal.t_site_obb') {
      print "<table id=\"small\">";
      print "<tr align=\"center\"><td></td>";
      print "<td><b>Site</b></td>";
      print "<td><b>Strata</b></td>";
      print "<td><b>Region</b></td>";
      print "<td><b>Code</b></td>";
      print "<td><b>Lat</b></td>";
      print "<td><b>Lon</b></td>";
      print "<td></td>";
      print "</tr>";

      $query = "SELECT *, st_y(location), st_x(location) FROM $table ORDER BY site";

      $r_query = pg_query($query);

      while ($results = pg_fetch_row($r_query)) {
          print "<tr align=\"center\">";

          print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\">";
          print "<input type=\"hidden\" value=\"$table\" name=\"table\"/>";
          print "<input type=\"hidden\" value=\"$results[0]\" name=\"id\"/>";

          print "<td>";

          print "<select name=\"active\">";
          if ($results[6] == 't') {
              print "<option value=\"true\" selected=\"selected\">Oui</option><option value=\"false\" >Non</option>";
          } else {
              print "<option value=\"true\" >Oui</option><option value=\"false\" selected=\"selected\">Non</option>";
          }
          print "</select>";
          print "</td>";

          print "<td><input type=\"text\" size=\"20\" name=\"site\" value=\"$results[1]\" /></td>";
          print "<td><input type=\"text\" size=\"10\" name=\"strata\" value=\"$results[2]\" /></td>";
          print "<td><input type=\"text\" size=\"10\" name=\"region\" value=\"$results[3]\" /></td>";
          print "<td><input type=\"text\" size=\"10\" name=\"code\" value=\"$results[4]\" /></td>";
          print "<td><input type=\"text\" size=\"10\" name=\"lat\" value=\"$results[7]\" /></td>";
          print "<td><input type=\"text\" size=\"10\" name=\"lon\" value=\"$results[8]\" /></td>";
          print "<td><input type=\"submit\" value=\"Enregistrer\" name=\"submit\"/></td>";

          print "</form>";
          print "</tr>";

      }
      print "</table>";
    } else {
      print "<table>";
      print "<tr align=\"center\"><td><b>Actif</b></td>";
      print "<td><b>Value</b></td><td></td>";
      print "</tr>";

      $query = "SELECT * FROM $table ORDER BY ".explode('.t_',$table)[1];
      //print $query;

      $r_query = pg_query($query);


      while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\">";
        print "<input type=\"hidden\" value=\"$table\" name=\"table\"/>";
        print "<input type=\"hidden\" value=\"$results[0]\" name=\"id\"/>";

        print "<td>";

        print "<select name=\"active\">";
        if ($results[2] == 't') {
            print "<option value=\"true\" selected=\"selected\">Oui</option><option value=\"false\" >Non</option>";
        } else {
            print "<option value=\"true\" >Oui</option><option value=\"false\" selected=\"selected\">Non</option>";
        }
        print "</td>";

        print "<td>";
        print "<input type=\"text\" size=\"20\" name=\"valeur\" value=\"$results[1]\" />"
            . "</td><td>"
            . "<input type=\"submit\" value=\"Enregistrer\" name=\"submit\"/></td>";

        print "</form>";
        print "</tr>";

    }
    print "</table>";
    }
}

if ($_POST['submit'] == "Effacer") {
  $id = $_POST['id'];
  $table = $_POST['table'];
  $query = "DELETE FROM $table WHERE id = '$id'";

  print $query;
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/maintenance/view_maintenance_t_table.php?source=$source&table=$table&action=show");
    }
    $controllo = 1;

}

if ($_POST['submit'] == "Enregistrer") {
    $table = $_POST['table'];

    if ($table == 'artisanal.t_site_obb') {
    # username, t_site, first_name, last_name, bday, t_nationality, t_card, idcard, telephone, address, photo_path
    $site = htmlspecialchars($_POST['site'], ENT_QUOTES);
    $strata = htmlspecialchars($_POST['strata'], ENT_QUOTES);
    $region = htmlspecialchars($_POST['region'], ENT_QUOTES);
    $code = htmlspecialchars($_POST['code'], ENT_QUOTES);
    $lat = htmlspecialchars($_POST['lat'], ENT_QUOTES);
    $lon = htmlspecialchars($_POST['lon'], ENT_QUOTES);
    $active = $_POST['active'];
    $point = "'POINT($lon $lat)'";

    if ($lon == '' OR $lat == '') {
      $query = "UPDATE $table SET "
        . "site = '$site', strata = '$strata', region = '$region', code = '$code', active = $active "
        . " WHERE id = '".$_POST['id']."'";

    } else {
      $query = "UPDATE $table SET "
        . "site = '$site', strata = '$strata', region = '$region', code = '$code', location = ST_GeomFromText($point,4326), active = $active "
        . " WHERE id = '".$_POST['id']."'";
    }

    } else {
      # username, t_site, first_name, last_name, bday, t_nationality, t_card, idcard, telephone, address, photo_path
      $active = $_POST['active'];
      $valeur = $_POST['valeur'];

        $query = "UPDATE $table SET "
          . explode('.t_',$table)[1] . " = '$valeur', active = $active "
          . " WHERE id = '".$_POST['id']."'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        //print $query;
        header("Location: ".$_SESSION['http_host']."/artisanal/maintenance/view_maintenance_t_table.php?source=$source&table=$table&action=show");
    }
}

foot();
