<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'vms';

top();

if(right_read($_SESSION['username'],5)) {
    ?>

    <h2>Donn&eacute;es VMS</h2>
    <table id="results">
    <tr>
    <td>Derni&egrave;re position connue</td>
    <td><a href="vms/view_vms_LKP.php?source=vms&table=lkp&action=show"><i class="material-icons">search</i>Voir</a></td>
    <td><a href="vms/view_vms_LKP.php?source=vms&table=lkp&action=map"><i class="material-icons">location_on</i>Carte</a></td>
    <td><!--<a href="download_form.php?source=vms&table=lkp&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a>--></td>
    </tr>
    <tr>
    <td>Points GPS</td>
    <td><a href="vms/view_vms_points.php?source=vms&table=point&action=show"><i class="material-icons">search</i>Voir</a></td>
    <td><a href="vms/view_vms_points.php?source=vms&table=point&action=map"><i class="material-icons">location_on</i>Carte</a></td>
    <td nowrap>
        <form method="post" action="./download_vms_year.php" enctype="multipart/form-data">
        <select name="year">
            <option value="extract(year from date_p)">Tous ann&eacute;es</option>
            <?php
            $query = "SELECT DISTINCT extract(year from date_p) FROM vms.positions WHERE date_p IS NOT NULL ORDER BY extract(year from date_p)";
            $r_query = pg_query($query);
            while ($results = pg_fetch_row($r_query)) {
                print "<option value = $results[0]>$results[0]</option>";
            }
            ?>
        </select>
            <input type="hidden" name="table" value="point" />
            <button name="format" value="CSV" class="link">
            <i class="material-icons">file_download</i>CSV
            </button>
        </form>

    </td>
    </tr>
<!--    <tr>
    <td>Pistes GPS</td>
    <td>Voir<a href="view_tracking_tracks.php?source=vms&table=track&action=show"></a></td>
    <td>Carte<a href="view_tracking_tracks.php?source=vms&table=lkp&action=show"></a></td>
    <td>T&eacute;l&eacute;charger<a href="download_form.php?source=vms&table=track&action=download"></a></td>
    </tr>  -->
    <tr>
    </table>



    <?php
} else {
    msg_noaccess();
}


foot();
