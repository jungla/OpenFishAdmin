<?php
require('top_foot.inc.php');

#unset($_SESSION['where']);

if(isset($_SESSION['where'][0])) {unset($_SESSION['where'][0]);}
if(isset($_SESSION['where'][1])) {unset($_SESSION['where'][1]);}

$_SESSION['where'][0] = 'home';

top();

?>

<h2>Content</h2>
<p>This site allows registered users to access the Zanzibar Small Scale Fishery database managed by the Department of Fisheries Development in Zanzibar. The website contains information on fishers, fishing vessels, licenses and fishers cards.</p>


<!--<iframe height='500px' style='width: 100%; height: 500; border:0' frameborder="0" scrolling="no" src="https://datastudio.google.com/embed/reporting/b9535664-ebd8-49ed-9de9-840a7d8bc800/page/5kVKB" frameborder="0" allowfullscreen></iframe>-->

<h2>Datasets registered on the database</h2>
<ul>
    <li>
    <?php
    $query = "SELECT (SELECT count(*) FROM artisanal.captures) + (SELECT count(*) FROM artisanal.fleet) + (SELECT count(*) FROM artisanal.market) + (SELECT count(*) FROM artisanal.effort)";
    $nrow = pg_fetch_row(pg_query($query))[0];
    print "<b>".number_format($nrow)."</b> ";
    ?>
    fishing vessels registered</li>

    <li>
    <?php
    $query = "SELECT (SELECT count(*) FROM artisanal.carte) + (SELECT count(*) FROM artisanal.fisherman) + (SELECT count(*) FROM artisanal.license) + (SELECT count(*) FROM artisanal.owner) + (SELECT count(*) FROM artisanal.pirogue)";
    $nrow = pg_fetch_row(pg_query($query))[0];
    print "<b>".number_format($nrow)."</b> ";
    ?>
    total number of fishers registered
    </li>
    <li>
    <?php
    $query = "SELECT count(*) FROM infraction.infraction";
    $nrow = pg_fetch_row(pg_query($query))[0];
    print "<b>".number_format($nrow)."</b> ";
    ?>
number of fishing licenses
</li>
</ul>

<!--<li><b><a href="oceanographic/index.php">Donn&eacute;es g&eacute;ospatiales</a></b></li>
<ul>
    <li>Variables oc&eacute;anographiques du <b>mod&egrave;le num&eacute;rique</b> de la sous-r&eacute;gion</b></li>
</ul>-->

<!--
<h2>Structure de la base</h2>
<p>
    <img src="./img/data_structure.png" alt="database structure scheme"/>
</p>
-->

<!--
<h2>Technologie de la base</h2>
<p>
    <img src="./img/GeoSpatial_Server.png" alt="database structure scheme"/>
</p>-->

<!--<h2>Documentation</h2>-->

<br/>

<?php
foot();
