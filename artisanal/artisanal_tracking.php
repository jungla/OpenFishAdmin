<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'pelagic';

top();

if(right_read($_SESSION['username'],5)) {
    ?>

    <h2>Pelagic VTS system</h2>
    <table id="results">
    <tr>
    <td>Pirogues</td>
    <td>Voir<a href="view_tracking_tracks.php?source=pelagic&table=pirogue&action=show"></a></td>
    <td>T&eacute;l&eacute;charger<a href="download_form.php?source=pelagic&table=pirogue&action=download"></a></td>
    </tr>  
    <tr>
    <td>Derni&egrave;re position connue</td>
    <td><a href="view_tracking_LKP.php?source=pelagic&table=lkp&action=show"><i class="material-icons">search</i>Voir</a></td>
    <td><a href="view_tracking_LKP.php?source=pelagic&table=lkp&action=map"><i class="material-icons">location_on</i>Carte</a></td>
    <td>T&eacute;l&eacute;charger<a href="download_form.php?source=pelagic&table=lkp&action=download"></a></td>
    </tr>  
    <tr>
    <td>Points GPS</td>
    <td><a href="view_tracking_points.php?source=pelagic&table=point&action=show"><i class="material-icons">search</i>Voir</a></td>
    <td><a href="view_tracking_points.php?source=pelagic&table=point&action=map"><i class="material-icons">location_on</i>Carte</a></td>
    <td>T&eacute;l&eacute;charger<a href="download_form.php?source=pelagic&table=point&action=download"></a></td>
    </tr>  
    <tr>
    <td>Pistes GPS</td>
    <td>Voir<a href="view_tracking_tracks.php?source=pelagic&table=track&action=show"></a></td>
    <td>Carte<a href="view_tracking_tracks.php?source=pelagic&table=lkp&action=show"></a></td>
    <td>T&eacute;l&eacute;charger<a href="download_form.php?source=pelagic&table=track&action=download"></a></td>
    </tr>  
    <tr>
    </table>
    
    
    
    <?php
} else {
    msg_noaccess();
}


foot();