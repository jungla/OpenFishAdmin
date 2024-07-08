<?php

require("./top_foot.inc.php");
#require("./functions.inc.php");

$menu_in = $_GET['menu_in'];
$menu_out = $_GET['menu_out'];
$menu_value = $_GET['menu_value'];
$table = $_GET['table'];
$tout = $_GET['tout'];

$query = "SELECT DISTINCT id, date_c, heure_c FROM $table WHERE id_maree = $menu_value ORDER BY date_c, heure_c";

#$query = "SELECT DISTINCT lance.id, lance.date_c, lance.heure_c FROM $table LEFT JOIN thon.lance ON thon.captures.id_lance = thon.lance.id WHERE captures.id_maree = $menu_value ORDER BY lance.date_c, lance.heure_c";

print $query;

$result = pg_query($query);

if ($tout == 'TRUE') {
    print "<option value=\"id_lance\">Tous</option>";
}

while ($row = pg_fetch_array($result)) {
    print "<option value=\"$row[0]\" >$row[1] - $row[2]</option>";
}

?>