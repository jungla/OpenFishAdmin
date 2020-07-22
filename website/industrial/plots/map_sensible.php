<?php // content="text/plain; charset=utf-8"
require_once ('../../jpgraph/src/jpgraph.php');
require_once ('../../jpgraph/src/jpgraph_scatter.php');
require('../../top_foot.inc.php');

DEFINE('WORLDMAP','../../jpgraph/src/Examples/Gabon_map.png');

$maree = $_GET['maree'];


# Mammal

$query = "SELECT DISTINCT st_x(location), st_y(location) FROM trawlers.captures_mammal "
        . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_mammal.id_route "
        . "WHERE trawlers.route_accidentelle.maree = $maree AND location IS NOT NULL";
        
$q_sensible = pg_fetch_all(pg_query($query));

foreach($q_sensible as $row) {
    $x_M[] = 100-100*(12-$row['st_x'])/(12-5);
    $y_M[] = 100*(5+$row['st_y'])/(5+2);   
}


# Tortue

$query = "SELECT DISTINCT st_x(location), st_y(location) FROM trawlers.captures_tortue "
        . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_tortue.id_route "
        . "WHERE trawlers.route_accidentelle.maree = $maree AND location IS NOT NULL";
        
$q_sensible = pg_fetch_all(pg_query($query));

foreach($q_sensible as $row) {
    $x_T[] = 100-100*(12-$row['st_x'])/(12-5);
    $y_T[] = 100*(5+$row['st_y'])/(5+2);   
}


# Requin

$query = "SELECT DISTINCT st_x(location), st_y(location) FROM trawlers.captures_requin "
        . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_requin.id_route "
        . "WHERE trawlers.route_accidentelle.maree = $maree AND location IS NOT NULL";
        
$q_sensible = pg_fetch_all(pg_query($query));

foreach($q_sensible as $row) {
    $x_R[] = 100-100*(12-$row['st_x'])/(12-5);
    $y_R[] = 100*(5+$row['st_y'])/(5+2);   
}


function markCallback($y,$x) {
    // Return array width
    // width,color,fill color, marker filename, imgscale
    // any value can be false, in that case the default value will
    // be used.
    // We only make one pushpin another color
    if( $x == 54 ) 
    return array(false,false,false,'red',0.8);
    else
    return array(false,false,false,'green',0.8);
}
 
// Setup the graph
$graph = new Graph(600,600);
 
// We add a small 1pixel left,right,bottom margin so the plot area
// doesn't cover the frame around the graph.
$graph->img->SetMargin(1,1,1,1);
$graph->SetScale('linlin',0,100,0,100);
 
// We don't want any axis to be shown
$graph->xaxis->Hide();
$graph->yaxis->Hide();
 
// Use a worldmap as the background and let it fill the plot area
$graph->SetBackgroundImage(WORLDMAP,BGIMG_FILLPLOT);
 
// Setup a nice title with a striped bevel background
$graph->title->Set("Espece sensible");
$graph->title->SetFont(FF_FONT2,FS_BOLD);
$graph->title->SetColor('black');
//$graph->SetTitleBackground('darkgreen',TITLEBKG_STYLE1,TITLEBKG_FRAME_BEVEL);
//$graph->SetTitleBackgroundFillStyle(TITLEBKG_FILLSTYLE_HSTRIPED,'blue','darkgreen');
 
// Finally create the scatterplot
if (count($y_M)>0 && count($x_M)>0) {
    $sp_M = new ScatterPlot($y_M,$x_M);
    $sp_M->mark->SetType(MARK_FILLEDCIRCLE);
    $sp_M->mark->SetFillColor('red.5');
    $sp_M->mark->SetColor('black@0.5');
    $sp_M->mark->SetWidth(5);
    $sp_M->SetLegend('Mammal');
    $sp_M->mark->SetCallbackYX('markCallback');
    $graph->Add($sp_M);    
}

if (count($y_T)>0 && count($x_T)>0) {
    $sp_T = new ScatterPlot($y_T,$x_T);
    $sp_T->mark->SetType(MARK_FILLEDCIRCLE);
    $sp_T->mark->SetFillColor('green.5');
    $sp_T->mark->SetColor('black@0.5');
    $sp_T->mark->SetWidth(5);
    $sp_T->SetLegend('Tortue');
    $sp_T->mark->SetCallbackYX('markCallback');
    $graph->Add($sp_T);    
}

if (count($y_R)>0 && count($x_R)>0) {
    $sp_R = new ScatterPlot($y_R,$x_R);
    $sp_R->mark->SetType(MARK_FILLEDCIRCLE);
    $sp_R->mark->SetFillColor('blue.5');
    $sp_R->mark->SetColor('black@0.5');
    $sp_R->mark->SetWidth(5);
    $sp_R->SetLegend('Requin');
    $sp_R->mark->SetCallbackYX('markCallback');
    $graph->Add($sp_R);    
}  
 
//$graph->legend->SetShadow('gray@0.4',5);
$graph->legend->SetPos(0.1,0.1,'right','top');
$graph->legend->SetFont(FF_FONT2,FS_BOLD);
$graph->legend->SetMarkAbsSize(10);
// .. and output to browser
$graph->Stroke();
 
?>