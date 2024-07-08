<?php
require("top_foot.inc.php");
require("functions.inc.php");

$_SESSION['where'] = 'input';

top();

if ($_GET['method'] != "") {$_SESSION['path'][0] = $_GET['method'];}
if ($_GET['source'] != "") {$_SESSION['path'][1] = $_GET['source'];}

$method = $_SESSION['path'][0];
$source = $_SESSION['path'][1];

echo "<a href=\"./input.php\">upload</a> > <a href=\"./input.php?method=$method\">".$method."</a> > <a href=\"./input_form.php?method=$method&source=$source\">".label2name($source)."</a>";

if ($source == 'trawlers') {
    if(right_read($_SESSION['username'],'ind_obs')) {
        ?>
        <h2>Tables</h2>
        <ul>
        <li><a href="input_form_trw.php?method=<?php echo $method; ?>&source=<?php echo $source; ?>&table=capture%20records">Captures records</a></li>
        <li><a href="input_form_trw.php?method=<?php echo $method; ?>&source=<?php echo $source; ?>&table=fishing%20effort">Fishing Effort</a></li>
        <li><a href="input_form_trw.php?method=<?php echo $method; ?>&source=<?php echo $source; ?>&table=fleet">fleet</a></li>
        <li><a href="input_form_trw.php?method=<?php echo $method; ?>&source=<?php echo $source; ?>&table=market%20price">Market price</a></li>
        </ul>
        <?php
    } else {
        msg_noaccess();
    }

} else if ($source == 'purse seiners') {
    if(right_read($_SESSION['username'],'ind_obs')) {
        ?>
        <h2>Tables</h2>
        <ul>
        <li><a href="input_form_snr.php?method=<?php echo $method; ?>&source=<?php echo $source; ?>&table=captures">Captures records</a></li>
        <li><a href="input_form_snr.php?method=<?php echo $method; ?>&source=<?php echo $source; ?>&stable=effort">Fishing Effort</a></li>
        <li><a href="input_form_snr.php?method=<?php echo $method; ?>&source=<?php echo $source; ?>&table=fleet">fleet</a></li>
        <li><a href="input_form_snr.php?method=<?php echo $method; ?>&source=<?php echo $source; ?>&table=market">Economic value</a></li>
        </ul>
        <?php
    } else {
        msg_noaccess();
    }
    
} else if ($source == 'artisanal') {
    if(right_read($_SESSION['username'],'art_obs')) {
        ?>
        <h2>Tables</h2>
        <ul>
        <li><a href="input_form_art.php?method=<?php echo $method; ?>&source=<?php echo $source; ?>&table=captures">Captures records</a></li>
        <li><a href="input_form_art.php?method=<?php echo $method; ?>&source=<?php echo $source; ?>&table=effort">Fishing Effort</a></li>
        <li><a href="input_form_art.php?method=<?php echo $method; ?>&source=<?php echo $source; ?>&table=fleet">Size fleet</a></li>
        <li><a href="input_form_art.php?method=<?php echo $method; ?>&source=<?php echo $source; ?>&table=market">Market Price</a></li>
        </ul>
        <?php
    } else {
        msg_noaccess();
    }
}

?>

<?php
foot();
