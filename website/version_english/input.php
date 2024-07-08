<?php
require("top_foot.inc.php");
require("functions.inc.php");

$_SESSION['where'] = 'input';

top();

//if ($_GET['method'] != "") {$_SESSION['path'][0] = $_GET['method'];}
unset($_SESSION['path']);

$_SESSION['path'][0] = $_GET['method'];

$method = $_SESSION['path'][0];

if ($method == '') {
?>
<h2>Upload Methods</h2>
<ul>
<li>Upload individual fishing records via <a href="input.php?method=form">form</a></li>
<li>Upload fishing records from <a href="input.php?method=upload%20CSV">CSV file</a></li>
<li>Configure <a href="input_ODK.php">ODK</a> for data collection</li> 
</ul>
<?php
} else {
    echo "<a href=\"./input.php\">upload</a> > <a href=\"./input.php?method=$method\">".$method."</a>";
}

if ($method == 'form') {

?>
<h2>Data Sources</h2>
<h3>Observers Program</h3>
<ul>
    <li><a href="input_form.php?method=<?php echo $method; ?>&source=trawlers">Trawlers Observers Program</a></li>
    <li><a href="input_form.php?method=<?php echo $method; ?>&source=purse%20seiners">Purse Seiners Observers Program</a></li>
    <li><a href="input_form.php?method=<?php echo $method; ?>&source=artisanal">Artisanal Fishery Observers Program</a></li>
</ul>

<h3>DGPA Records</h3>
<ul>
    <li><a href="input_form.php?method=<?php echo $method; ?>&source=licenses">Licenses</a></li>
    <li><a href="input_form.php?method=<?php echo $method; ?>&source=fishing%20records">Fishing Records</a></li>
</ul>

<?php
} else if ($method == 'upload CSV') {
?>
<h2>Data Sources</h2>
<ul>
    <li><a href="input_CSV.php?method=<?php echo $method; ?>&source=trawlers">Trawlers Observers Program</a></li>
    <li><a href="input_CSV.php?method=<?php echo $method; ?>&source=purse%20seiners">Purse Seiners Observers Program</a></li>
    <li><a href="input_CSV.php?method=<?php echo $method; ?>&source=artisanal">Artisanal Fishery Observers Program</a></li>
</ul>
<?php    
}

?>



<?php

foot();