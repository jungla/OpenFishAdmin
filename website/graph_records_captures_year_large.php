<?php
require("../top_foot.inc.php");


require_once("../jpgraph/src/jpgraph.php");
require_once("../jpgraph/src/jpgraph_pie.php");

$id = $_GET['id'];
$year = $_GET['year'];


$query = "SELECT SUM(wgt_spc), fishery.species.francaise "
. " FROM artisanal.captures "
. " LEFT JOIN fishery.species ON fishery.species.id = artisanal.captures.id_species "
. " LEFT JOIN artisanal.maree ON artisanal.maree.id = artisanal.captures.id_maree "
. " WHERE id_pirogue = '$id' AND EXTRACT(year FROM maree.datetime_d) = '$year' AND fishery.species.francaise IS NOT NULL"
. " GROUP BY fishery.species.francaise ORDER BY SUM(wgt_spc) DESC LIMIT 20";

//print $query;
$rquery = pg_query($query);

$vals = array();
$labels = array();

while ($results = pg_fetch_row($rquery)) {
  array_push($vals,round($results[0]));
  array_push($labels,round($results[0]).'kg '.$results[1]);
}

//print_r($vals);

// Setup the graph
$graph = new PieGraph(600,400);

$p1 = new PiePlot($vals);
$p1->SetSize(150);
$p1->SetCenter(0.7,0.5);
$p1->SetLegends($labels);
$p1->SetLabelPos(0.6);
$p1->value->SetFont(FF_FONT0);

$graph->Add($p1);
$graph->legend->SetPos(0,0,'left','top');
$graph->legend->SetColumns(1);

$graph->img->SetAntiAliasing();
#$graph->title->Set('Filled Y-grid');
$graph->SetBox(false);


// Output line
$graph->Stroke();
