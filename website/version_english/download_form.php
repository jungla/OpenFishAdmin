<?php
require("top_foot.inc.php");
require("functions.inc.php");

$_SESSION['where'] = 'input';

top();


if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}

$source = $_SESSION['path'][0];
$table = $_GET['table'];

echo "<a href=\"./download.php\">download</a> > <a href=\"./download_form.php?source=$source\">".label2name($source)."</a>";

if ($_GET['action'] == 'download') {
    // find tables' column name and writes it to array
    
    $q_hdr = "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME = '$table';";
    $r_hdr = pg_query($q_hdr);
    $header = array();
    while ($line = pg_fetch_row($r_hdr)) {
        $header[] = $line[0];
    }

    $q_id = "SELECT * FROM peche_$source.$table";
    $r_id = pg_query($q_id);
    
    ob_end_clean();
    
    header('Content-Type:application/csv'); 
    header('Content-Disposition:attachment;filename='.$source.'_'.$table.'.csv'); 
    
    $output = fopen("php://output",'w') or die("Can't open php://output");

    //writes table's header 
    fputcsv($output, $header);
    
    //writes table's content
    while ($record = pg_fetch_row($r_id)) {
        fputcsv($output, $record);
    }
    fclose($output) or die("Can't close php://output");
    die();
    
}


if (!$controllo){
    
if ($source == 'trawlers') {
?>
<h2>Tables</h2>
<table>
<tr><td>Captures records</td><td><a href="download_form.php?source=<?php echo $source; ?>&table=captures&action=download">Donwload CSV</a></td></tr>
<tr><td>Fishing Effort</td><td><a href="download_form.php?source=<?php echo $source; ?>&table=fishing%20effort&action=download">Donwload CSV</a></td></tr>
<tr><td>fleet</td><td><a href="download_form.php?source=<?php echo $source; ?>&table=fleet&action=download">Donwload CSV</a></td></tr>
<tr><td>Market price</td><td><a href="download_form.php?source=<?php echo $source; ?>&table=market%20price&action=download">Donwload CSV</a></td></tr>
</table>
<?php
} else if ($source == 'purse seiners') {
?>
<h2>Tables</h2>
<table>
<tr><td>Captures records</td><td><a href="download_form.php?source=<?php echo $source; ?>&table=captures&action=download">Donwload</a></td></tr>
<tr><td>Fishing Effort</td><td><a href="download_form.php?source=<?php echo $source; ?>&table=fishing%20effort&action=download">Donwload</a></td></tr>
<tr><td>fleet</td><td><a href="download_form.php?source=<?php echo $source; ?>&table=fleet&action=download">Donwload</a></td></tr>
<tr><td>Market price</td><td><a href="download_form.php?source=<?php echo $source; ?>&table=market%20price&action=download">Donwload</a></td></tr>
</table>
<?php
} else if ($source == 'artisanal') {
?>
<h2>Tables</h2>
<table>
<tr><td>Captures records</td><td><a href="download_form.php?source=<?php echo $source; ?>&table=captures&action=download">CSV</a></td></tr>
<tr><td>Fishing Effort</td><td><a href="download_form.php?source=<?php echo $source; ?>&table=effort&action=download">CSV</a></td></tr>
<tr><td>fleet</td><td><a href="download_form.php?source=<?php echo $source; ?>&table=fleet&action=download">CSV</a></td></tr>
<tr><td>Market price</td><td><a href="download_form.php?source=<?php echo $source; ?>&table=market&action=download">CSV</a></td></tr>
</table>
<?php
}
}
foot();
