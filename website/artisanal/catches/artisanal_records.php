<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'records';

top();

if(right_write($_SESSION['username'],3,2)) {
?>

<h2>Cahier de Bord de Captaine</h2>
<table id="results">
    <tr><td>Captures </td><td><a href="./view_records_capture.php?source=artisanal&table=captures&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_records.php?source=artisanal&table=captures"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=captures&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
</table>

<h2>Donnees des Enqueteurs au Debarcadere</h2>
<table id="results">
    <tr><td>Captures </td><td><a href="./view_records_capture.php?source=artisanal&table=captures&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_records.php?source=artisanal&table=captures"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=captures&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
</table>

<h2>Donnees des Programme Observateurs Sanctuaire des Requins</h2>
<table id="results">
    <tr><td>Captures </td><td><a href="./view_records_capture.php?source=artisanal&table=captures&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_records.php?source=artisanal&table=captures"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=captures&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
    <tr><td>Effort de p&ecirc;che </td><td><a href="view_records_effort.php?source=artisanal&table=effort&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_records.php?source=artisanal&table=effort"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=effort&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
    <tr><td>Informations sur la flotte</td><td> <a href="view_records_fleet.php?source=artisanal&table=fleet&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_records.php?source=artisanal&table=fleet"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=fleet&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
    <tr><td>Prix &agrave; la vente</td><td> <a href="view_records_market.php?source=artisanal&table=market&action=show"><i class="material-icons">search</i>Voir</a> </td><td> <a href="input_records.php?source=artisanal&table=market"><i class="material-icons">create</i>Saisir</a> </td><td> <a href="download_form.php?source=artisanal&table=market&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td></tr>
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
