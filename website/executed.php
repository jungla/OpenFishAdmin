<?php
require('top_foot.inc.php');
top();

$dest = $_GET['dest'];
$id_dest = $_GET['id_dest'];

echo "<p id=login><b>Succ&egrave;s!</b><br/><br/><a href=./$id_dest>Ajout&eacute;r un nouvel enregistrement.</a></p>";

foot();

?>
