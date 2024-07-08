<?php 
require('top_foot.inc.php');
require("functions.inc.php");

$_SESSION['where'] = 'industrial';

top();

?>

<h3>Content</h3>
<p>This web site presents some of the characteristics of a future <b>Geospatial Database for Gabon Bleu</b>. The database is based on <a href="https://www.postgresql.org">PostgreSQL</a>, <a href="https://postgis.net">PostGIS</a>, <a href="http://apache.org/">Apache</a> and <a href="http://geoserver.org/">Geoserver</a>.
</p>

<h3>Purpose</h3>
<ul>
<li>To <b>centralize</b> fishery datasets (i.e., traditional, industrial, shrimp, lobster, THEMIS, etc...)</li>
<li>To allow <b>synchronous</b> access to the database (and same user from different locations)</li>
<li>To allow data entry via <b>mobile systems</b> (i.e., ODK collect/SMART, email, web page, locus map)</li>
<li>To allow interfacing with <b>other databases</b> (i.e., ODK, metocean data, etc...)</li>
<li>To allow <b>more secure</b> data storage (backed-up and encrypted database)</li> 
</ul>


<h3>Database Components</h3>
<p><img src="./img/GeoSpatial_Server.png" alt="database structure scheme"/>
</p>

<h3>Current Capabilities</h3>
<p>In the current state the web site serves the purpose of showcasing the data entry, edit and visualization workflow. The platform is flexible and will be extended in future developments.
</p>

<ol>
<li>Data is <b>inserted</b> via web interface at the web page <a href="./input_art.php">Input Data</a></li>
<li>Data can be <b>retrieved</b> and edited at the web page <a href="./edit_art.php">Edit Data</a></li> 
<li>Data can be <b>visualized</b> via either the native <a href="./visualize_art.php">Geoserver interface</a> or <a href="./visualize.php">Google Earth</a></li> 
<li>Data is stored on a <b>PostgreSQL</b> and can be accessed <b>simultaneously</b> by multiple users</li>
<li>Data can be manipulated via <a href="./visualize_art.php">QGIS/ArcGIS</a> 
</ol>


<h3>Future Capabilities</h3>
<ul>
<li>Extend to <b>all sources of data</b> (i.e., traditional fishery, THEMIS, lobster, etc...)</li>
<li>Integrate <b>METOCEAN data</b></li>
<li>Allow input via <b>email, csv, ODK</b></li>
</ul>

<!--<h3>Documentation</h3>-->

<br/>

<?php
foot();