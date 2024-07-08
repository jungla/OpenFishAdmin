<?php

require("./top_foot.inc.php");
#require("./functions.inc.php");

$menu_in = $_GET['menu_in'];
$menu_out = $_GET['menu_out'];
$menu_value = $_GET['menu_value'];
$table = $_GET['table'];

$query = "SELECT DISTINCT $menu_out FROM $table WHERE $menu_in = '$menu_value' AND $menu_out IS NOT NULL ORDER BY $menu_out";

print $query;

$result = pg_query($query);

print "<option  value=\"none\">Veuillez choisir ci-dessus</option>";

while ($row = pg_fetch_array($result)) {
    print "<option value=\"$row[0]\" >" . $row[0] . "</option>";
}

?>