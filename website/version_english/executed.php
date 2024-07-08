<?php
require('top_foot.inc.php');
require("functions.inc.php");
top();

$dest = $_GET['dest'];
$id_dest = $_GET['id_dest'];

echo "<p id=login><b>Success!</b><br/><br/>Insert another record: <a href=./$id_dest>$dest</a></p>";

foot();
?>
