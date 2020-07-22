<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'home';

top();

?>

<!-- <h2>Project Description</h2>
<p>This web site is the result of a collaboration between WCS and local authorities in Congo and Gabon (DGPA, ANPA and ANPN).
    The website allows registered users to <b>input</b>, <b>edit</b> and <b>visualize</b> records from the artisanal fishery program as collected by observers and DGPA. This work is part of the <b>Geospatial Database for Gabon Bleu</b>.</p>
-->

<h2>Contenu de la base de donn&eacute;es</h2>
<p>Actuellement, la base de donn&eacute;es contient,</p>
<ul>
<!--<li>Les donn&eacute;es sur les captures</li>
<li>Les donn&eacute;es sur l'effort de p&ecirc;che</li>
<li>Les donn&eacute;es sur la flotte</li>
<li>Les donn&eacute;es sur les prix de la ressource</li>-->
<li>Les donn&eacute;es sur les <a href="./artisanal_records.php">statistiques</a> de production</li>
<li>Les donn&eacute;es sur les <a href="./artisanal_licenses.php">autorisations</a> de p&ecirc;che</li>
<li>Les donn&eacute;es sur les <a href="./artisanal_infractions.php">infractions</a></li>
</ul>

<!--
<h2>Website Capabilities</h2>
<p>In the current version, the web site allows data entry, edit and visualization.</p>

<ol>
<li>Data is <b>inserted</b> via web interface at the web page <a href="./input.php">Input Data</a></li>
<li>Data can be <b>retrieved</b> and edited at the web page <a href="./edit.php">Edit Data</a></li>
 <li>Data can be <b>visualized</b> via either the native <a href="./visualize.php">Geoserver interface</a> or <a href="./visualize.php">Google Earth</a></li>
<li>Stored data can be <b>viewed</b> at the web page <a href="./view.php">View Data</a></li>
<li>Data is stored on a <b>PostgreSQL</b> and can be accessed <b>simultaneously</b> by multiple users</li>
<li>Data can be manipulated via <a href="./visualize.php">QGIS/ArcGIS</a>
</ol>-->

<br/>

<?php
foot();
