<?php
require("top_foot.inc.php");
require("functions.inc.php");

$_SESSION['where'] = 'industrial';

top();

if ($_GET['table'] == 'captures') {

$navire = $_POST['navire'];
$maree = $_POST['maree'];
$date = $_POST['date'];
$lance = $_POST['lance'];
$lon_d = $_POST['lon_d'];
$lat_d = $_POST['lat_d'];
$lon_f = $_POST['lon_f'];
$lat_f = $_POST['lat_f'];
$h_d = $_POST['h_d'];
$h_f = $_POST['h_f'];
$prof_d = $_POST['prof_d'];
$prof_f = $_POST['prof_f'];
$vitesse = $_POST['vitesse'];
$rejet = $_POST['rejet'];
$ech = $_POST['ech'];


//se submit = go!
if ($_POST['submit'] == "Submit") {
 if (trim($lon_d) == "" OR trim($lat_d) == "" OR trim($lon_f) == ""  OR trim($lat_f) == "") {
  ?>
  <b>Coordinates are required</b><br/>

  <form method="POST" target="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF');?>">
  <input type="hidden" name="navire" value="<?php echo $navire; ?>"/>
  <input type="hidden" name="maree" value="<?php echo $maree; ?>"/>
  <input type="hidden" name="date" value="<?php echo $date; ?>"/>
  <input type="hidden" name="lance" value="<?php echo $lance; ?>"/>
  <input type="hidden" name="lon_d" value="<?php echo $lon_d; ?>"/>
  <input type="hidden" name="lat_d" value="<?php echo $lat_d; ?>"/>
  <input type="hidden" name="lon_f" value="<?php echo $lon_f; ?>"/>
  <input type="hidden" name="lat_f" value="<?php echo $lat_f; ?>"/>
  <input type="hidden" name="h_d" value="<?php echo $h_d; ?>"/>
  <input type="hidden" name="h_f" value="<?php echo $h_f; ?>"/>
  <input type="hidden" name="prof_d" value="<?php echo $prof_d; ?>"/>
  <input type="hidden" name="prof_f" value="<?php echo $prof_f; ?>"/>
  <input type="hidden" name="vitesse" value="<?php echo $vitesse; ?>"/>
  <input type="hidden" name="rejet" value="<?php echo $rejet; ?>"/>
  <input type="hidden" name="ech" value="<?php echo $ech; ?>"/>
  <br/><br/>	
  <input type="submit" name="Back" value="Back">
  </form>
  <?php
  foot();
  die();
 } else {
 
 echo $date;
  $date = mdy2ymd($date);
echo $date;
  $query = "INSERT INTO peche_trawlers.peche_poi(date, navire, maree, date_lance, lance, h_d, h_f, prof_d, prof_f, vitesse, rejet, ech, point_d, point_f) VALUES (now(), '$navire', '$maree', '$date', '$lance', '$h_d', '$h_f', '$prof_d', '$prof_f', '$vitesse', '$rejet', '$ech',ST_GeomFromText('POINT($lon_d $lat_d)', 4326), ST_GeomFromText('POINT($lon_f $lat_f)', 4326));";
  if(!pg_query($query)) {
   echo $query;
   msg_queryerror();
  } else {
   $radice = filter_input(INPUT_SERVER, 'HTTP_HOST');
   header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=input_form.php");
  }
  $controllo = 1;
 }
}	


if (!$controllo) {
	?>
	<form method="post" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF');?>" enctype="multipart/form-data"><br/>
	<b>Ship</b>
	<br/>
	<input type="text" size="20" name="navire" value="<?php echo $navire;?>" />
	<br/>
	<br/>
	<b>Mission</b>
	<br/>
	<input type="text" size="10" name="maree" value="<?php echo $maree;?>" />
	<br/>
	<br/>
	<b>Date</b> (mm/dd/yyyy)
	<br/>
	<input type="text" size="10" name="date" value="<?php echo $date;?>" />
	<br/>
	<br/>
	<b>Cast</b>
	<br/>
	<input type="text" size="3" name="lance" value="<?php echo $lance;?>" />
	<br/>
	<br/>
        <b>Initial Position</b> (positive north and east)<br/>
        Longitude decimal degree <input type="text" size="3" name="lon_d" value="<?php echo $lon_d;?>" />
        <br/>
	Latitude decimal degree <input type="text" size="3" name="lat_d" value="<?php echo $lat_d;?>" />
        <br/>
        <br/>
        <b>Final Position</b> (positive north and east)<br/>
        Longitude decimal degree <input type="text" size="3" name="lon_f" value="<?php echo $lon_f;?>" />
        <br/>
        Latitude decimal degree <input type="text" size="3" name="lat_f" value="<?php echo $lat_f;?>" />
        <br/>
        <br/>
	<b>Time start</b> (hh:mm:ss)<br/>
	<input type="text" size="8" name="h_d" value="<?php echo $h_d;?>" />
	<br/>
	<br/>
	<b>Time end</b> (hh:mm:ss)<br/>
	<input type="text" size="8" name="h_f" value="<?php echo $h_f;?>" />
	<br/>
	<br/>
        <b>Depth start</b> (m)<br/>
        <input type="text" size="4" name="prof_d" value="<?php echo $prof_d;?>" />
        <br/>
        <br/>
        <b>Depth end</b> (m)<br/>
        <input type="text" size="4" name="prof_f" value="<?php echo $prof_f;?>" />
        <br/>
        <br/>
        <b>Speed</b> (nd)<br/>
        <input type="text" size="3" name="vitesse" value="<?php echo $vitesse;?>" />
        <br/>
        <br/>
        <b>Rejected</b> (units)<br/>
        <input type="text" size="6" name="rejet" value="<?php echo $rejet;?>" />
        <br/>
        <br/>
        <b>Sample size</b> (kg)<br/>
        <input type="text" size="5" name="ech" value="<?php echo $ech;?>" />
        <br/>
        <br/>
	<input type="submit" value="Submit" name="submit"/>
	</form>
	<br/><br/>
	
<?php
}

} else if ($_GET['table'] == 'effort') {
?>
     Table Fishing Effort   
        
<?php
}

foot();
