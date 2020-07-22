<?php
require("../top_foot.inc.php");

require_once("../jpgraph/src/jpgraph.php");
require_once("../jpgraph/src/jpgraph_line.php");
require_once("../jpgraph/src/jpgraph_gantt.php");
    
$id_navire = $_GET['id_navire'];
$date_d = $_GET['date_d'];
$date_f = $_GET['date_f'];

  
$query = "SELECT navire FROM vms.navire WHERE id = '$id_navire'";

$navire = pg_fetch_row(pg_query($query))[0];



$query = "SELECT * FROM crevette.lance WHERE crevette.lance.id_navire = '$id_navire' AND crevette.lance.date_l > '$date_d' AND crevette.lance.date_l < '$date_f'";
//print pg_num_rows(pg_query($query))." lance peche crevettier (logbook)";

$query = "SELECT * FROM seiners.route WHERE id_navire = '$id_navire' AND date > '$date_d' AND date < '$date_f'";
//print pg_num_rows(pg_query($query))." seiner route points (programme observateurs)";

$query = "SELECT * FROM trawlers.route WHERE id_navire = '$id_navire' AND date > '$date_d' AND date < '$date_f'";
//print pg_num_rows(pg_query($query))." trawlers route points (programme observateurs)";

#print $query;
// A new graph with automatic size
$graph = new GanttGraph(600);

$result = pg_query("SELECT MIN(date_p::timestamp::date), MAX(date_p::timestamp::date) FROM vms.positions WHERE id_navire = '$id_navire' AND date_p > '$date_d' AND date_p < '$date_f' ");
$row = pg_fetch_row($result);

$vms = new GanttBar(0,"VMS",$row[0],$row[1]);

//  A new activity on row '0'
$graph->Add($vms);

//print pg_num_rows(pg_query($query))." captures peche tonnier (logbook)";

$query = "SELECT MIN(thon.lance.date_c::timestamp::date), MAX(thon.lance.date_c::timestamp::date) FROM thon.captures LEFT JOIN thon.maree ON maree.id = captures.id_maree LEFT JOIN thon.lance ON lance.id = captures.id_lance WHERE thon.maree.id_navire = '$id_navire' AND thon.lance.date_c > '$date_d' AND thon.lance.date_c < '$date_f'";
print $query;
$result = pg_query($query);
$row = pg_fetch_row($result);

$graph->scale->month->grid->SetColor('gray');
$graph->scale->month->grid->Show(true);
$graph->scale->year->grid->SetColor('gray');
$graph->scale->year->grid->Show(true);
$graph->ShowHeaders(GANTT_HMONTH | GANTT_HYEAR);

//
//$data_1 = new GanttBar(1,"Data 1","2001-12-21","2002-01-19");
//$graph->Add($data_1);
//$data_2 = new GanttBar(2,"Data 2","2001-12-21","2002-01-19");
//$graph->Add($data_2);
//$data_3 = new GanttBar(3,"Data 3","2001-12-21","2002-01-19");
//$graph->Add($data_3);
//$data_4 = new GanttBar(4,"Data 4","2001-12-21","2002-01-19");
//$graph->Add($data_4);
 
// Display the Gantt chart
//$graph->Stroke();
?>
