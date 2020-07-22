<?php

require("../top_foot.inc.php");
#require("./functions.inc.php");

$menu_in_1 = $_GET['menu_in_1'];
$menu_in_2 = $_GET['menu_in_2'];
$menu_out = $_GET['menu_out'];
$menu_value_1 = $_GET['menu_value_1'];
$menu_value_2 = $_GET['menu_value_2'];
$table = $_GET['table'];

$query = "SELECT DISTINCT $menu_out FROM $table WHERE $menu_in_1 = '$menu_value_1' AND $menu_in_2 = '$menu_value_2' ORDER BY $menu_out";

print $query;

$result = pg_query($query);

print "<option  value=\"none\">Please choose one</option>";

while ($row = pg_fetch_array($result)) {
    print "<option value=\"$row[0]\" >" . $row[0] . "</option>";
}

?>