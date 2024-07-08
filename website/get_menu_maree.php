<?php

require("./top_foot.inc.php");
#require("./functions.inc.php");

$menu_value = $_GET['menu_value'];
$table = $_GET['table'];

$query = "SELECT DISTINCT id, date_d, date_r FROM $table WHERE id_navire = '$menu_value' ORDER BY date_d";
//$query = "SELECT DISTINCT $menu_out FROM $table WHERE $menu_in = '$menu_value' AND $menu_out IS NOT NULL ORDER BY $menu_out";

#$query = "SELECT DISTINCT lance.id, lance.date_c, lance.heure_c FROM $table LEFT JOIN thon.lance ON thon.captures.id_lance = thon.lance.id WHERE captures.id_maree = $menu_value ORDER BY lance.date_c, lance.heure_c";

print $query;

$result = pg_query($query);

while ($row = pg_fetch_array($result)) {
    print "<option value=\"$row[0]\" >$row[1] - $row[2]</option>";
}

?>
