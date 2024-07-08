<?php 
require('top_foot.inc.php');
require('../functions.inc.php');

$_SESSION['where'][0] = 'industrial';

top();


# 1 - UPLOADA FILE

if (filter_input(INPUT_POST,'submit_data') == 'submit') {
 $i = 0;
 foreach ($_SESSION['data'] as $row) {
  $i++;
  $navire = $row[$_SESSION['dataid']['navire']];
  $maree = $row[$_SESSION['dataid']['maree']];
  $datet = $row[$_SESSION['dataid']['date']];
  $lance = check_number($row[$_SESSION['dataid']['lance']]);
  $lon_d = check_number($row[$_SESSION['dataid']['lon_d']]);
  $lat_d = check_number($row[$_SESSION['dataid']['lat_d']]);
  $lon_f = check_number($row[$_SESSION['dataid']['lon_f']]);
  $lat_f = check_number($row[$_SESSION['dataid']['lat_f']]);
  $h_d = $row[$_SESSION['dataid']['h_d']];
  $h_f = $row[$_SESSION['dataid']['h_f']];
  $prof_d = check_number($row[$_SESSION['dataid']['prof_d']]);
  $prof_f = check_number($row[$_SESSION['dataid']['prof_f']]);
  $vitesse = check_number($row[$_SESSION['dataid']['vitesse']]);
  $rejet = check_number($row[$_SESSION['dataid']['rejet']]);
  $ech = check_number($row[$_SESSION['dataid']['ech']]);


  $date = mdy2ymd($datet);
 
  $query = "INSERT INTO peche_trawlers.peche_poi(date, navire, maree, date_lance, lance, h_d, h_f, prof_d, prof_f, vitesse, rejet, ech, point_d, point_f) VALUES (now(), '$navire', '$maree', '$date', '$lance', '$h_d', '$h_f', '$prof_d', '$prof_f', '$vitesse', '$rejet', '$ech',ST_GeomFromText('POINT($lon_d $lat_d)', 4326), ST_GeomFromText('POINT($lon_f $lat_f)', 4326));";

  if(!pg_query($query)) {
   echo "<b>Unknown error.</b><br/>";
   die();
  }
 
 }
 print $i." entries uplodaded with succes.";
 print "Back to <a href=./input_csv.php>Upload CSV</a></p>";

 bottom(); 
 $controllo = 1;
}

# 2 - CHECK FORMAT

if (filter_input(INPUT_POST, 'check_format') == 'submit') {
 # load updated ids

 $_SESSION['dataid'] = $_POST;
 
 if ($_FILES['csv_file']['tmp_name']!="") {
  $header = filter_input(INPUT_POST, 'header');
  $filename = $_FILES['csv_file']['tmp_name'];
  
  $handle = fopen($filename,"r"); 
  
  for ($i = 0; $i < $header; $i++) {fgetcsv($handle, 1000, ",");}
  
  $r = 0;
  while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
   $_SESSION['data'][$r] = $row;
   $r++;	
   }
  fclose($handle);
 }

 $data = $_SESSION['data'][0];
 
 # reads data at ids
 $_SESSION['navire'] = $data[$_SESSION['dataid']['navire']];
 $_SESSION['maree'] = $data[$_SESSION['dataid']['maree']];
 $_SESSION['date'] = $data[$_SESSION['dataid']['date']];
 $_SESSION['lance'] = $data[$_SESSION['dataid']['lance']];
 $_SESSION['lon_d'] = $data[$_SESSION['dataid']['lon_d']];
 $_SESSION['lat_d'] = $data[$_SESSION['dataid']['lat_d']];
 $_SESSION['lon_f'] = $data[$_SESSION['dataid']['lon_f']];
 $_SESSION['lat_f'] = $data[$_SESSION['dataid']['lat_f']];
 $_SESSION['h_d'] = $data[$_SESSION['dataid']['h_d']];
 $_SESSION['h_f'] = $data[$_SESSION['dataid']['h_f']];
 $_SESSION['prof_d'] = $data[$_SESSION['dataid']['prof_d']];
 $_SESSION['prof_f'] = $data[$_SESSION['dataid']['prof_f']];
 $_SESSION['vitesse'] = $data[$_SESSION['dataid']['vitesse']];
 $_SESSION['rejet'] = $data[$_SESSION['dataid']['rejet']];
 $_SESSION['ech'] = $data[$_SESSION['dataid']['ech']];  

 // check date and time format

 ?>
 <p>
 Verify below the first line of the file before submitting.<br/>
 </p>

 <p>
 <form method="post" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF'); ?>" enctype="multipart/form-data">
 <input type="submit" name="submit" value="Submit data"/>
 <input type="hidden" name="submit_data" value="submit"/>
 </form>

 <p>
 <form method="post" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF'); ?>" enctype="multipart/form-data">
 <input type="submit" name="submit" value="Refresh columns order" />
 <input type="hidden" name="check_format" value="submit"/>

 <br/>
 <br/>

 <?php
 if (!check_date($_SESSION['date'])) {
 print "<p color='red'>*check <i>Date</i> format</p>";
 }

 if (!check_time($_SESSION['h_d'])) {
 print "<p color='red'>*check <i>Time start</i> format</p>";
 }

 if (!check_time($_SESSION['h_f'])) {
 print "<p color='red'>*check <i>Time end</i> format</p>";
 }
 ?>

 <table>
 <tr><td>Ship name</td><td><?php echo $_SESSION['navire'];?></td><td><input type="text" size="2" name="navire" value="<?php echo $_SESSION['dataid']['navire'];?>"/></td></tr>
 <tr><td>Mission</td><td><?php echo $_SESSION['maree'];?></td><td><input type="text" size="2" name="maree" value="<?php echo $_SESSION['dataid']['maree'];?>"/></td></tr>
 <tr><td>Cast number</td><td><?php echo $_SESSION['lance'];?></td><td><input type="text" size="2" name="lance" value="<?php echo $_SESSION['dataid']['lance'];?>"/></td></tr>
 <tr><td>Date (mm/dd/yyyy)</td><td><?php echo $_SESSION['date'];?></td><td><input type="text" size="2" name="date" value="<?php echo $_SESSION['dataid']['date'];?>"/></td></tr>
 <tr><td>Time start (hh:mm:ss)</td><td><?php echo $_SESSION['h_d'];?></td><td><input type="text" size="2" name="h_d" value="<?php echo $_SESSION['dataid']['h_d'];?>"/></td></tr>
 <tr><td>Time end (hh:mm:ss)</td><td><?php echo $_SESSION['h_f'];?></td><td><input type="text" size="2" name="h_f" value="<?php echo $_SESSION['dataid']['h_f'];?>"/></td></tr>
 <tr><td colspan=3>Initial position (positive north and east)</td></tr>
 <tr><td>&nbsp;Longitude decimal degree</td><td><?php echo $_SESSION['lon_d'];?></td><td><input type="text" size="2" name="lon_d" value="<?php echo $_SESSION['dataid']['lon_d'];?>"/></td></tr>
 <tr><td>&nbsp;Latitude decimal degree</td><td><?php echo $_SESSION['lat_d'];?></td><td><input type="text" size="2" name="lat_d" value="<?php echo $_SESSION['dataid']['lat_d'];?>"/></td></tr>
 <tr><td colspan=3>Final position (positive north and east)</td></tr>
 <tr><td>&nbsp;Longitude decimal degree</td><td><?php echo $_SESSION['lon_f'];?></td><td><input type="text" size="2" name="lon_f" value="<?php echo $_SESSION['dataid']['lon_f'];?>"/></td></tr>
 <tr><td>&nbsp;Latitude decimal degree</td><td><?php echo $_SESSION['lat_f'];?></td><td><input type="text" size="2" name="lat_f" value="<?php echo $_SESSION['dataid']['lat_f'];?>"/></td></tr>
 <tr><td>Depth start (m)</td><td><?php echo $_SESSION['prof_d'];?></td><td><input type="text" size="2" name="prof_d" value="<?php echo $_SESSION['dataid']['prof_d'];?>"/></td></tr>
 <tr><td>Depth end (m)</td><td><?php echo $_SESSION['prof_f'];?></td><td><input type="text" size="2" name="prof_f" value="<?php echo $_SESSION['dataid']['prof_f'];?>"/></td></tr>
 <tr><td>Speed (nd)</td><td><?php echo $_SESSION['vitesse'];?></td><td><input type="text" size="2" name="vitesse" value="<?php echo $_SESSION['dataid']['vitesse'];?>"/></td></tr>
 <tr><td>Rejected size (units)</td><td><?php echo $_SESSION['rejet'];?></td><td><input type="text" size="2" name="rejet" value="<?php echo $_SESSION['dataid']['rejet'];?>"/></td></tr>
 <tr><td>Sample (kg)</td><td><?php echo $_SESSION['ech'];?></td><td><input type="text" size="2" name="ech" value="<?php echo $_SESSION['dataid']['ech'];?>"/></td></tr>
 </table>
 </form>
 
 <?php
 
 $controllo = 1;
}

if (!$controllo) { 
 unset($_SESSION['data']);

 ?>
 <h2>Upload CSV file</h2>
 A CSV file can be used here to load data on the server. The CSV file has to be formatted as specified below.
 <p>

 <form method="post" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF'); ?>" enctype="multipart/form-data">
 <input type="file" size="20" name="csv_file" />
 
 <table>
 <tr><td><h4>Number of lines in header:</h4></td><td><input type="text" size="2" name="header" value="0"/></td></tr>
 </table>
 <table>
 <tr><td colspan=2><h4>Columns order in CSV file:</h4></td></tr>
 <tr><td>Ship name</td><td><input type="text" size="2" name="navire" value="0"/></td></tr>
 <tr><td>Mission</td><td><input type="text" size="2" name="maree" value="1"/></td></tr>
 <tr><td>Cast number</td><td><input type="text" size="2" name="lance" value="2"/></td></tr>
 <tr><td>Date (mm/dd/yyyy)</td><td><input type="text" size="2" name="date" value="3"/></td></tr>
 <tr><td>Time start (hh:mm:ss)</td><td><input type="text" size="2" name="h_d" value="4"/></td></tr>
 <tr><td>Time end (hh:mm:ss)</td><td><input type="text" size="2" name="h_f" value="5"/></td></tr>
 <tr><td colspan=2>Initial position (positive north and east)</td></tr>
 <tr><td>&nbsp;Longitude decimal degree</td><td><input type="text" size="2" name="lon_d" value="6"/></td></tr>
 <tr><td>&nbsp;Latitude decimal degree</td><td><input type="text" size="2" name="lat_d" value="7"/></td></tr>
 <tr><td colspan=2>Final position (positive north and east)</td></tr>
 <tr><td>&nbsp;Longitude decimal degree</td><td><input type="text" size="2" name="lon_f" value="8"/></td></tr>
 <tr><td>&nbsp;Latitude decimal degree</td><td><input type="text" size="2" name="lat_f" value="9"/></td></tr>
 <tr><td>Depth start (m)</td><td><input type="text" size="2" name="prof_d" value="10"/></td></tr>
 <tr><td>Depth end (m)</td><td><input type="text" size="2" name="prof_f" value="11"/></td></tr>
 <tr><td>Speed (nd)</td><td><input type="text" size="2" name="vitesse" value="12"/></td></tr>
 <tr><td>Rejected size (units)</td><td><input type="text" size="2" name="rejet" value="13"/></td></tr>
 <tr><td>Sample (kg)</td><td><input type="text" size="2" name="ech" value="14"/></td></tr>
 </table>
 
 <br/>
 <input type="hidden" name="check_format" value="submit" />
 <input type="submit" name="submit" value="Submit" />
 </form>
 
 <?php

}

foot();
