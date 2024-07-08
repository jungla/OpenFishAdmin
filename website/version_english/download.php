<?php
require("top_foot.inc.php");
require("functions.inc.php");

$_SESSION['where'] = 'download';

top();
unset($_SESSION['path']);
//if ($_GET['method'] != "") {$_SESSION['path'][0] = $_GET['method'];}

?>

<h2>Download Data</h2>
<h3>Observers Program</h3>
<ul>
    <li><a href="download_form.php?source=trawlers">Trawlers Observers Program</a></li>
    <li><a href="download_form.php?source=purse%20seiners">Purse Seiners Observers Program</a></li>
    <li><a href="download_form.php?source=artisanal">Artisanal Fishery Observers Program</a></li>
</ul>

<h3>DGPA Records</h3>
<ul>
    <li><a href="download_form.php?source=licenses">Licenses</a></li>
    <li><a href="download_form.php?source=fishing%20records">Fishing Records</a></li>
</ul>


<?php

foot();