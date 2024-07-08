<?php 
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'oceanographic';

#if(isset($_SESSION['where'][1])) {unset($_SESSION['where'][1]);}

top();

//top_login();

?>

<!-- <h2>Project Description</h2>
<p>This web site is the result of a collaboration between WCS and local authorities in Congo and Gabon (DGPA, ANPA and ANPN).
    The website allows registered users to <b>input</b>, <b>edit</b> and <b>visualize</b> records from the artisanal fishery program as collected by observers and DGPA. This work is part of the <b>Geospatial Database for Gabon Bleu</b>.</p>
-->

<h2>Donn&eacute;es oc&eacute;anographiques &agrave; t&eacute;l&eacute;charger</h2>

<h3>Title of the dataset</h3>
<iframe width="560" height="315" src="https://www.youtube.com/embed/jAHriVtyj7g" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe><p>description</p>
<ul><li><a href="">link for download</a></li></ul>

<h3>Title of the dataset</h3>
<iframe width="560" height="315" src="https://www.youtube.com/embed/MpvVzvOFPeI" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
<ul><li><a href="">link for download</a></li></ul>

<h3>Title of the dataset</h3>    
<iframe width="560" height="315" src="https://www.youtube.com/embed/vAH7P67D7wU" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
<ul><li><a href="">link for download</a></li></ul>

<h3>Title of the dataset</h3>
<iframe width="560" height="315" src="https://www.youtube.com/embed/pRKGZi8_ey8" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
<ul><li><a href="">link for download</a></li></ul>

<h3>Title of the dataset</h3>
<iframe width="560" height="315" src="https://www.youtube.com/embed/VMo9KXSZ7W4" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
<ul><li><a href="">link for download</a></li></ul>

<h3>Title of the dataset</h3>
<iframe width="560" height="315" src="https://www.youtube.com/embed/dQqgbmhF6t0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
<ul><li><a href="">link for download</a></li></ul>

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
