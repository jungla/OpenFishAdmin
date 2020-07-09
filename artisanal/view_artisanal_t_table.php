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

    <table>
    <tr align="center"><td><b>Actif</b></td>
    <td><b>Value</b></td><td></td>
    </tr>

    <?php

    $query = "SELECT * FROM artisanal.$table ORDER BY ".substr($table,2);
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

if ($_POST['submit'] == "Effacer") {
  $id = $_POST['id'];
  $table = $_POST['table'];
  $query = "DELETE FROM artisanal.$table WHERE id = '$id'";

  print $query;
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/view_artisanal_t_table.php?source=$source&table=$table&action=show");
    }
    $controllo = 1;

}

if ($_POST['submit'] == "Enregistrer") {

    # username, t_site, first_name, last_name, bday, t_nationality, t_card, idcard, telephone, address, photo_path
    $valeur = htmlspecialchars($_POST['valeur'], ENT_QUOTES);
    $table = $_POST['table'];
    $active = $_POST['active'];

    $query = "UPDATE artisanal.$table SET "
    . substr($table,2) ." = '$valeur', active = $active "
    . " WHERE id = '".$_POST['id']."'";

    $query = str_replace('\'\'', 'NULL', $query);

    print $query;

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/artisanal/view_artisanal_t_table.php?source=$source&table=$table&action=show");
    }
}

foot();
