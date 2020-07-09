<?php
require("../top_foot.inc.php");


require_once("../jpgraph/src/jpgraph.php");
require_once("../jpgraph/src/jpgraph_pie.php");

$id = $_GET['id'];

$query = "SELECT wgt_tot, wgt_spc, n_ind, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM artisanal.captures LEFT JOIN fishery.species ON fishery.species.id = artisanal.captures.id_species WHERE id_maree = '$id'";
//print $query;

$rquery = pg_query($query);

$vals = array();
$labels = array();

while ($results = pg_fetch_row($rquery)) {
  array_push($vals,round($results[1]));
  array_push($labels,round($results[1]).'kg '.$results[3]);
}

// Setup the graph
$graph = new PieGraph(200,150);

$p1 = new PiePlot($vals);
$p1->SetSize(45);
$p1->SetCenter(0.75,0.65);
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
