<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'administration';

top();

if(right_read($_SESSION['username'],5)) {
    ?>

    <h2>Licenses P&ecirc;che Industrielle</h2>
    <table id="results">
    <tr>
    <td>Navire</td>
    <td><a href="view_licenses_navire.php?source=license&table=navire&action=show"><i class="material-icons">search</i>Voir</a></td>
    <td><a href="maintain_licenses_license.php?source=license&table=navire&action=show"><i class="material-icons">build</i>Maintenance</a></td>
    <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_form_navire.php?source=license&table=navire"><i class="material-icons">create</i>Saisir</a>';} ?></td>
    <td><a href="download_form_vms.php?source=license&table=navire&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    </table>
    <!--
    <ul>
    <li>Infractions <a href="view_licenses_infraction.php?source=seiners&table=infraction&action=show"><i class="material-icons">search</i>Voir</a> - <a href="input_licenses.php?source=seiners&table=infraction"><i class="material-icons">create</i>Saisir</a> - <a href="download_form.php?source=seiners&table=infraction&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></li>
    </ul>
    -->


    <?php
} else {
    msg_noaccess();
}


foot();
