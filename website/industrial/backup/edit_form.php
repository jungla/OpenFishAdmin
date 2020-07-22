<?php
require("../top_foot.inc.php");
require("../functions.inc.php");

$_SESSION['where'] = 'edit';
$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];
    
if ($source == 'trawlers') {
    if(project($username,'ind_obs')) {
        ?>
        <h2>Tables</h2>
        <table>
        <tr><td>Captures records</td><td><a href="edit_form.php?source=<?php print $source; ?>&table=captures&action=show">Donwload CSV</a></td></tr>
        <tr><td>Fishing Effort</td><td><a href="download_form.php?source=<?php print $source; ?>&table=fishing%20effort&action=show">Donwload CSV</a></td></tr>
        <tr><td>fleet</td><td><a href="download_form.php?source=<?php print $source; ?>&table=fleet&action=show">Donwload CSV</a></td></tr>
        <tr><td>Market price</td><td><a href="download_form.php?source=<?php print $source; ?>&table=market%20price&action=show">Donwload CSV</a></td></tr>
        </table>
        <?php
    } else {
        msg_noaccess();
    }

} else if ($source == 'purse seiners') {
    if(project($username,'ind_obs')) {
        ?>
        <h2>Tables</h2>
        <table>
        <tr><td>Captures records</td><td><a href="download_form.php?source=<?php print $source; ?>&table=captures&action=download">Donwload</a></td></tr>
        <tr><td>Fishing Effort</td><td><a href="download_form.php?source=<?php print $source; ?>&table=fishing%20effort&action=download">Donwload</a></td></tr>
        <tr><td>fleet</td><td><a href="download_form.php?source=<?php print $source; ?>&table=fleet&action=download">Donwload</a></td></tr>
        <tr><td>Market price</td><td><a href="download_form.php?source=<?php print $source; ?>&table=market%20price&action=download">Donwload</a></td></tr>
        </table>
        <?php
    } else {
        msg_noaccess();
    }
} else if ($source == 'artisanal') {
    if(project($username,'art_obs')) {
        print "<a href=\"./edit.php\">edit</a> > <a href=\"./edit_form.php?source=$source\">".label2name($source)."</a>";
        ?>
        <h2>Tables</h2>
        <ul>
        <li><a href="edit_form_art.php?source=<?php print $source; ?>&table=captures&action=show">Captures records</a></li>
        <li><a href="edit_form_art.php?source=<?php print $source; ?>&table=effort&action=show">Fishing Effort</a></li>
        <li><a href="edit_form_art.php?source=<?php print $source; ?>&table=fleet&action=show">fleet</a></li>
        <li><a href="edit_form_art.php?source=<?php print $source; ?>&table=market&action=show">Market price</a></li>
        </ul>
        <?php
    } else {
        msg_noaccess();
    }
}
foot();
