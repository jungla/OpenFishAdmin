<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'infractions';

top();

if ($_GET['source'] != "") $_SESSION['path'][0] = $_GET['source'];
if ($_GET['table'] != "") $_SESSION['path'][1] = $_GET['table'];

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if(right_read($_SESSION['username'],5)) {
    print "<h2>Infractions de P&ecirc;che Artisanale Maritime</h2>";
    ?>

    <table id="results">
    <tr>
    <td>Infractions</td>
    <td><a href="view_infractions_infraction.php?source=infractions&table=infraction&action=show"><i class="material-icons">search</i>Voir</a></td>
    <td><?php if(right_write($_SESSION['username'],2,5)) {print '<a href="input_infractions.php?source=infractions&table=infraction"><i class="material-icons">create</i>Saisir</a>';} ?></td>
    <td nowrap>
                <form method="post" action="./download_licenses_year.php" enctype="multipart/form-data">
                <select name="year">
                    <option value="extract(year from date_i)">Tous ann&eacute;es</option>
                    <?php
                    $query = "SELECT DISTINCT extract(year from date_i) FROM infraction.infraction WHERE date_i IS NOT NULL ORDER BY extract(year from date_i)";
                    $r_query = pg_query($query);
                    while ($results = pg_fetch_row($r_query)) {
                        print "<option value = $results[0]>$results[0]</option>";
                    }
                    ?>
                </select>
                    <input type="hidden" name="table" value="infractions" />
                    <button name="format" value="CSV" class="link">
                    <i class="material-icons">file_download</i>CSV
                    </button>
                    <button name="format" value="PDF" class="link">
                    <i class="material-icons">file_download</i>PDF
                    </button>
                </form>

            </td></tr>
    </table>
    <br/>
    <br/>

    <?php
} else {
    msg_noaccess();
}


foot();
