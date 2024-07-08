<?php
require("../../top_foot.inc.php");


require_once("../../jpgraph/src/jpgraph.php");
require_once("../../jpgraph/src/jpgraph_pie.php");

$id = $_GET['id'];

$query = "SELECT rejete, fishery.species.FAO, taille, poids "
    . " FROM thon.captures "
    . "LEFT JOIN thon.lance ON thon.captures.id_lance = thon.lance.id "
    . "LEFT JOIN fishery.species ON thon.captures.id_species = fishery.species.id "
    . "WHERE id_lance = '$id'";

//print $query;
$rquery = pg_query($query);

$vals = array();
$labels = array();


while ($results = pg_fetch_row($rquery)) {
  array_push($vals,$results[3]);
  if ($results[0] == 't') {
    array_push($labels,$results[1].' '.$results[3].'Ton, '.$results[2].'kg, REJ');
  } else {
    array_push($labels,$results[1].' '.$results[3].'Ton, '.$results[2].'kg, RET');
  }

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
