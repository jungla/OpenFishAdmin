<?php
require("top_foot.inc.php");
require("connect.inc.php");
require("functions.inc.php");

odkconnect();

top();

?>
<br/>
loading this page transfer records from ODK to database.<br/>

<?php

$query = 'SELECT * FROM odk_prod."BUILD_PECHE_TRAWLERS_1516287086_CORE"';
if(!pg_query($query)) {
   top();
   msg_queryerror();
  } else {
   $result = pg_query($query);

   while ($row = pg_fetch_array($result)) {
    
    $navire = $row[23];
    $maree = $row[12];
    $date = $row[28];
    $lance = $row[26];
    $lon_d = $row[14];
    $lat_d = $row[13];
    $lon_f = $row[25];
    $lat_f = $row[27];
    $h_d = $row[17];
    $h_f = $row[20];
    $prof_d = $row[24];
    $prof_f = $row[18];
    $vitesse = $row[15];
    $rejet = $row[25];
    $ech = $row[16];

    dbconnect();

    
    $query = "INSERT INTO peche_trawlers.peche_poi(date, navire, maree, date_lance, lance, h_d, h_f, prof_d, prof_f, vitesse, rejet, ech, point_d, point_f) VALUES (now(), '$navire', '$maree', '$date', '$lance', '$h_d', '$h_f', '$prof_d', '$prof_f', '$vitesse', '$rejet', '$ech',ST_GeomFromText('POINT($lon_d $lat_d)', 4326), ST_GeomFromText('POINT($lon_f $lat_f)', 4326));";
 if(!pg_query($query)) {
   top();
   msg_queryerror();
  } else {
   echo $query;
  }
}
  }
foot();