<?php
header('Content-type: image/jpeg');
require_once ('../../jpgraph/src/jpgraph.php');
require_once ('../../jpgraph/src/jpgraph_pie.php');
require("../../top_foot.inc.php");

$maree = $_GET['maree'];

$query = "SELECT SUM(poids), fishery.species.family "
        . "FROM trawlers.captures "
        . "LEFT JOIN fishery.species ON trawlers.captures.id_species = fishery.species.id "
        . "WHERE maree = $maree GROUP BY fishery.species.family";

$q_capture = pg_fetch_all(pg_query($query));

//print_r($q_capture);

foreach($q_capture as $row) {
    $poids_sum += $row['sum'];
}

foreach($q_capture as $row) {
    #$poids_sum += $row['sum'];
    if (100*$row['sum']/$poids_sum < 2) {
        $poids_res += 100*$row['sum']/$poids_sum;

    } else {
//        print round(100*$row['sum']/$poids_sum,1)." ".$row['family']."<br/>";
        $a_poids[] = round(100*$row['sum']/$poids_sum,1);
        $a_family[] = $row['family'];
        
    }
}

$a_poids[] = round($poids_res,1);
$a_family[] = 'Autre';

$data = $a_poids;

$graph = new PieGraph(600,500);
$graph->SetShadow();
 
$graph->title->Set("Pourcentage de presence des familles selon leur poids de capture $maree");
$graph->title->SetFont(FF_FONT1,FS_BOLD);
 
$p1 = new PiePlot($data);
$p1->SetLegends($a_family);
 
$graph->Add($p1);
$graph->Stroke('./pie_family.png');
$graph->Stroke();
 