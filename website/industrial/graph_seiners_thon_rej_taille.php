<?php
require("../top_foot.inc.php");


require_once("../jpgraph/src/jpgraph.php");
require_once("../jpgraph/src/jpgraph_bar.php");

$id = $_GET['id'];

$query = "SELECT * FROM seiners.thon_rejete_taille WHERE id = '$id'";

#print $query;

$results = pg_fetch_row(pg_query($query));

$x = array_merge(range(9,101),array(110,111,112,135,138,139,140,144,145,146,147,148,149,150,151,154,155,156,157,158,159,160,170));
$y = array_slice($results,9);

#print $y[0];
#print count($x);

for ($i = 0; $i < count($y)+1; $i++) {
    if ($i%3 != 0 and $x[$i] < 160) {
        $x[$i] = ' ';
    }
//    print $i." ".$x[$i]." ".$y[$i]."<br/>";
}


// Setup the graph
$graph = new Graph(500,250);
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;

$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(false);
#$graph->title->Set('Filled Y-grid');
$graph->SetBox(false);

$graph->img->SetAntiAliasing();

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);
$graph->yaxis->SetTitle('#','center');


$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->SetTitle('Taille','center');

#$graph->xgrid->Show();
#$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels($x);
#$graph->xgrid->SetColor('#E3E3E3');

// Create the first line
$p1 = new BarPlot($y);
$graph->Add($p1);
$p1->SetColor("#6495ED");

$graph->legend->SetFrameWeight(1);

// Output line
$graph->Stroke();