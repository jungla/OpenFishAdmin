<?php

require("../top_foot.inc.php");
#require("./functions.inc.php");

$menu_in = $_GET['menu_in'];
$menu_out = $_GET['menu_out'];
$menu_value = $_GET['menu_value'];
$category = $_GET['category'];
$table = $_GET['table'];

$query = "SELECT DISTINCT id, francaise, family, genus, species FROM $table WHERE family = '$menu_value' AND category = '$category' AND category IS NOT Null ORDER BY $menu_out";

print $query;

$result = pg_query($query);

while ($row = pg_fetch_array($result)) {
    print "<option value=\"$row[0]\" >".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
}

?>