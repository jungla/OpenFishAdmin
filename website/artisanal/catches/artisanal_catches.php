<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'catches';

top();

if(right_write($_SESSION['username'],3,2)) {
?>

<h2>Cahier de Bord de Captaine</h2>
<table id="results">
  <tr><td>Catches</td><td><a href="./view_catches_log.php?source=artisanal&table=catches&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_catches_log.php?source=artisanal&table=maree"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=captures&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
</table>

<h2>Donnees des Enqueteurs au Debarcadere</h2>
<table id="results">
  <tr><td>Catches</td><td><a href="./view_catches_enq.php?source=artisanal&table=catches&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_catches_enq.php?source=artisanal&table=maree"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=captures&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
</table>

<?php

}

if(right_write($_SESSION['username'],10,2)) {

?>

<h2>Donnees des Programme Observateurs Sanctuaire des Requins</h2>
<table id="results">
    <tr><td>Maree </td><td><a href="./view_catches_obs_maree.php?source=obs_catches&table=maree&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_catches_obs.php?source=obs_catches&table=maree"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=captures&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
    <tr><td>Actions de peche </td><td><a href="view_catches_obs_actions.php?source=obs_catches&table=actions&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_catches_obs.php?source=obs_catches&table=actions"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=effort&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
    <tr><td>Captures requins/rais</td><td> <a href="view_catches_obs_sharks.php?source=obs_catches&table=sharks&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_catches_obs.php?source=obs_catches&table=sharks"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=fleet&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
    <tr><td>Captures tortues</td><td> <a href="view_catches_obs_turtles.php?source=obs_catches&table=turtles&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_catches_obs.php?source=obs_catches&table=turtles"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=fleet&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
    <tr><td>Captures mammiferes marines</td><td> <a href="view_catches_obs_mammals.php?source=obs_catches&table=mammals&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_catches_obs.php?source=obs_catches&table=mammals"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=fleet&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
    <tr><td>Captures especes cibles</td><td> <a href="view_catches_obs_fish.php?source=obs_catches&table=fishes&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_catches_obs.php?source=obs_catches&table=fish"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=fleet&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
    <tr><td>Relation poids-taille especes cibles</td><td> <a href="view_catches_obs_poids_taille.php?source=obs_catches&table=poids_taille&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_catches_obs.php?source=obs_catches&table=poids_taille"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=fleet&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
</table>


<!--
<ul>
    <li>Captures <a href="view_records_capture.php?source=artisanal&table=captures&action=show"><i class="material-icons">search</i>Voir</a> - <a href="input_form_art.php?source=artisanal&table=captures"><i class="material-icons">create</i>Saisir</a> - <a href="download_form.php?source=artisanal&table=captures&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></li>
    <li>Effort de p&ecirc;che <a href="view_records_effort.php?source=artisanal&table=effort&action=show"><i class="material-icons">search</i>Voir</a> - <a href="input_form_art.php?source=artisanal&table=effort"><i class="material-icons">create</i>Saisir</a> - <a href="download_form.php?source=artisanal&table=effort&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></li>
    <li>Informations sur la flotte <a href="view_records_fleet.php?source=artisanal&table=fleet&action=show"><i class="material-icons">search</i>Voir</a> - <a href="input_form_art.php?source=artisanal&table=fleet"><i class="material-icons">create</i>Saisir</a> - <a href="download_form.php?source=artisanal&table=fleet&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></li>
    <li>Prix &agrave; la vente <a href="view_records_market.php?source=artisanal&table=market&action=show"><i class="material-icons">search</i>Voir</a> - <a href="input_form_art.php?source=artisanal&table=market"><i class="material-icons">create</i>Saisir</a> - <a href="download_form.php?source=artisanal&table=market&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></li>
</ul>
<br/>
-->
<!--<ul>
    <li>Observers database <a href="view_records_capture.php?source=artisanal&table=captures&action=show">View</a> - <a href="input_form_art.php?source=artisanal&table=observer">Upload</a> - <a href="download_form.php?source=artisanal&table=captures&action=download">Download</a></li>
</ul>-->


<?php
} else {
    msg_noaccess();
}

foot();
