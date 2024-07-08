<?php
require("top_foot.inc.php");
require("functions.inc.php");

$_SESSION['where'] = 'edit';

top();
unset($_SESSION['path']);
//if ($_GET['method'] != "") {$_SESSION['path'][0] = $_GET['method'];}

?>

<h2>Edit Data</h2>
<h3>Observers Program</h3>
<ul>
    <li><a href="edit_form.php?source=trawlers">Trawlers Observers Program</a></li>
    <li><a href="edit_form.phpedit_form?source=purse%20seiners">Purse Seiners Observers Program</a></li>
    <li><a href="edit_form.php?source=artisanal">Artisanal Fishery Observers Program</a></li>
</ul>

<h3>DGPA Records</h3>
<ul>
    <li><a href="edit_form.php?source=licenses">Licenses</a></li>
    <li><a href="edit_form.php?source=records">Fishing Records</a></li>
</ul>


<?php

foot();