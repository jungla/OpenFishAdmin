<?php
require("../top_foot.inc.php");
require("../functions.inc.php");

$_SESSION['where'] = 'edit';
$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<a href=\"./edit.php\">edit</a> > <a href=\"./edit_form.php?source=$source\">".label2name($source)."</a> > <a href=\"./edit_form.php?source=$source&table=$table&action=show\">".label2name($table)."</a>";

    // fetch data
    $query = "SELECT * FROM peche_$source.$table ORDER BY datetime DESC";
    $r_query = pg_query($query);
    
    print "<br/><br/><font size='3'>";
    print "<table style=\"border:1px solid black;\">";
    print "<tr><td></td>
    <td><b>Date departure</b></td>
    <td><b>Date return</b></td>
    <td><b>Observer name</b></td>
    <td><b>Landing site</b></td>
    <td><b>Fisherman name</b></td>
    <td><b>License number</b></td>
    <td><b>Type of net</b></td>
    <td><b>Net size</b> (cm)</td>
    <td><b>Fishing net Length</b> (m)</td>
    <td><b>Number of days at sea</b></td>
    <td><b>Species group</b></td>
    <td><b>Species</b></td>
    <td><b>Sample size</b> (kg)</td>
    <td><b>Number of individuals</b></td>
    <td><b>GPS track</b></td>
    </tr>";
    
    while ($results = pg_fetch_row($r_query)) {
        print "<tr><td>"
        . "<a href=\"./edit_form.php?source=$source&table=$table&action=edit&id=$results[0]\">Edit</a><br/>"
        . "<a href=\"./edit_form.php?source=$source&table=$table&action=delete&id=$results[0]\">Delete</a>"
        . "</td>";
        print "<td>$results[3]</td><td>$results[4]</td><td>$results[5]</td><td>$results[6]</td><td>$results[7]</td><td>$results[8]</td><td>$results[9]</td><td>$results[10]</td>"
                . "<td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td><td>$results[15]</td><td>$results[16]</td><td>$results[18]</td>";
    }
    print "</tr>";
    
    print "</table></font>";
    $controllo = 1;
    
} else if ($_GET['action'] == 'edit') {
    print "<a href=\"./edit.php\">edit</a> > <a href=\"./edit_form.php?source=$source\">".label2name($source)."</a> > <a href=\"./edit_form.php?source=$source&table=$table&action=show\">".label2name($table)."</a>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT * FROM peche_$source.$table WHERE id = '$id' ORDER BY datetime DESC";
    #print $q_id;
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);
    
    // get and plot geometry
    if ($results[18] == "t") {
        $query_t = "SELECT st_astext(gps_track) FROM peche_$source.$table WHERE id = '$id'";    
        $gps_track = pg_fetch_array(pg_query($query_t));
    
    //plot it in gmaps
    
    $track_points = split(",", $gps_track[0]);
    
    $lat = array();
    $lon = array();
    
    for ($i = 1; $i < count($track_points)-10; $i = $i+10) {
        $lat[] = split(" ",$track_points[$i])[1];
        $lon[] = split(" ",$track_points[$i])[0];
    }
        
    $lat_m = array_sum($lat)/count($lat);
    $lon_m = array_sum($lon)/count($lon);
       
    
    print "<div id=\"map\" style=\"height: 400px; width:50%; border: 1px solid black; float:right; margin-right:5%; margin-top: 5%;\"></div>";

    print "<script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
          center: {lat: $lat_m, lng: $lon_m},
          mapTypeId: 'terrain'
        });

        var flightPlanCoordinates = [";
    
//        for ($i = 1; $i < count($track_points)-100; $i = $i+100) {
//            print "{lat: ".split(" ",$track_points[$i])[1].", lng: ".split(" ",$track_points[$i])[0]."},";
//        }
//        print "{lat: ".split(" ",$track_points[$i])[1].", lng: ".split(" ",$track_points[$i])[0]."}";
//       
        for ($i = 0; $i < count($lat)-2; $i++) {
            print "{lat: ".$lat[$i].", lng: ".$lon[$i]."},";
        }
        
        print "{lat: ".$lat[$i+1].", lng: ".$lon[$i+1]."}";
        
    print "];
        var flightPath = new google.maps.Polyline({
          path: flightPlanCoordinates,
          geodesic: true,
          strokeColor: '#FF0000',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });

        flightPath.setMap(map);
      }

    </script>
    <script async defer
    src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyBI5MQWC4N5SgUXs989_7MTDkQghaiGUuA&callback=initMap\">
    </script>";
    }
    

    if ($results[18] == "t") {
        
        print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\"><br/>"
            . "<b>Delete GPS track</b><br/>"
            . "<input type=\"submit\" name=\"delete\" value=\"Delete Track\" />"
            . "<input type=\"hidden\" name=\"id\" value=\"$id\" />"
            . "</form>";
                
    }
    ?>

    <form method="post" action="<?php print $self; ?>" enctype="multipart/form-data"><br/>
    <b>Upload new GPS track</b> (KML format)
    <br/>
    <input type="file" size="40" name="kml_file" />
    <br/>
    <br/>
    <br/>
    <b>Date and time depart</b> (yyyy-mm-dd hh:mm:ss)
    <br/>
    <input type="text" size="20" name="datetime_d" value="<?php print $results[3];?>" />
    <br/>
    <br/>
    <b>Date and time return</b> (yyyy-mm-dd hh:mm:ss)
    <br/>
    <input type="text" size="20" name="datetime_r" value="<?php print $results[4];?>" />
    <br/>
    <br/>
    <b>Observer name</b>
    <br/>
    <input type="text" size="20" name="obs_name" value="<?php print $results[5];?>" />
    <br/>
    <br/>
    <b>Landing site</b>
    <br/>
    <input type="text" size="20" name="t_site" value="<?php print $results[6];?>" />
    <br/>
    <br/>
    <b>Fisherman name</b>
    <br/>
    <input type="text" size="20" name="fish_name" value="<?php print $results[7];?>" />
    <br/>
    <br/>
    <b>License number</b>
    <br/>
    <input type="text" size="30" name="license" value="<?php print $results[8];?>" />
    <br/>
    <br/>
    <b>Type of net</b>
    <br/>
    <input type="text" size="8" name="net_t" value="<?php print $results[9];?>" />
    <br/>
    <br/>
    <b>Net size</b> (cm)
    <br/>
    <input type="text" size="8" name="net_s" value="<?php print $results[10];?>" />
    <br/>
    <br/>
    <b>Fishing net Length</b> (m)
    <br/>
    <input type="text" size="4" name="net_l" value="<?php print $results[11];?>" />
    <br/>
    <br/>
    <b>Number of days at sea</b>
    <br/>
    <input type="text" size="4" name="n_days" value="<?php print $results[12];?>" />
    <br/>
    <br/>
    <b>Species group</b>
    <br/>
    <input type="text" size="3" name="t_group" value="<?php print $results[13];?>" />
    <br/>
    <br/>
    <b>Species</b>
    <br/>
    <input type="text" size="6" name="t_species" value="<?php print $results[14];?>" />
    <br/>
    <br/>
    <b>Sample size</b> (kg)<br/>
    <input type="text" size="5" name="sample_s" value="<?php print $results[15];?>" />
    <br/>
    <br/>
    <b>Number of individuals</b><br/>
    <input type="text" size="5" name="n_ind" value="<?php print $results[16];?>" />
    <br/>
    <br/>
    <input type="submit" value="Submit" name="submit"/>
    <input type="hidden" name="id" value="<?php print $results[0]; ?>" />
    </form>
    <br/>
    <br/>

    <?php

    $controllo = 1;
    
}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM peche_$source.$table WHERE id = '$id'";
    
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/edit_form.php?source=$source&table=captures&action=show");
    }
    $controllo = 1;
    
}

if ($_POST['delete'] == "Delete Track") {
    $query = "SELECT gps_file FROM peche_$source.$table WHERE id = '{".$_POST['id']."}'";
    $gps_file = pg_fetch_row(pg_query($query));
    
    $query = "UPDATE peche_$source.$table SET "
                . " gps_file = NULL, gps_yn = 'FALSE', gps_track = NULL "
                . " WHERE id = '{".$_POST['id']."}'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        $string = substr($gps_file[0], 0, -4);
        exec("/bin/rm -f $string*");
        header("Location: ".$_SESSION['http_host']."/edit_form.php?source=$source&table=captures&action=edit&id=".$_POST['id']);
    }
    $controllo = 1;
    
}

if ($_POST['submit'] == "Submit") {
    if ($_FILES['kml_file']['tmp_name'] != "") {
        $filename = $_FILES['kml_file']['tmp_name'];
        $filename_out = uniqid($username);
        # generate shapefiles
        exec("cp $filename ./files/tracks/$filename_out.kml");
        exec("chmod 0777 ./files/tracks/$filename_out.kml");
        exec("/opt/local/bin/ogr2ogr -f 'ESRI Shapefile' ./files/tracks/$filename_out.shp ./files/tracks/$filename_out.kml");
        exec("/opt/local/lib/postgresql96/bin/shp2pgsql -g geometry ./files/tracks/$filename_out.shp peche_artisanal.track_$filename_out | /opt/local/lib/postgresql96/bin/psql -h localhost -d geospatialdb -U postgres");

        # get PGS_track from track table
        $query_t = "SELECT st_astext(geometry) FROM peche_artisanal.track_$filename_out";
        $gps_track = pg_fetch_array(pg_query($query_t));
        $gps_yn = TRUE;
        $gps_file = "./files/tracks/$filename_out.kml";

        #echo $gps_track[0];
    }
        
    // fetch header
    $q_hdr = "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME = '$table';";
    $r_hdr = pg_query($q_hdr);
    $header = array();
    while ($line = pg_fetch_row($r_hdr)) {
        $header[] = $line[0];
    }
    echo $_POST['datetime_d'];
    
    if ($_FILES['kml_file']['tmp_name'] != "") {
        $query = "UPDATE peche_$source.$table SET "
                . "datetime = now(), "
                . "username = '$username', datetime_d = '".$_POST['datetime_d']."', datetime_r = '".$_POST['datetime_r']."', "
                . "obs_name = '".$_POST['obs_name']."', t_site = '".$_POST['t_site']."', fish_name = '".$_POST['fish_name']."', "
                . "license = '".$_POST['license']."', t_net = '".$_POST['t_net']."', net_s = '".$_POST['net_s']."', "
                . "net_l = '".$_POST['net_l']."', n_days = '".$_POST['n_days']."', t_group = '".$_POST['t_group']."', "
                . "t_species = '".$_POST['t_species']."', sample_s = '".$_POST['smaple_s']."', n_ind = '".$_POST['n_ind']."'"
                . ", gps_file = '$gps_file', gps_yn = '$gps_yn', gps_track = ST_GeomFromText('$gps_track[0]',4326) "
                . " WHERE id = '{".$_POST['id']."}'";
    } else {
        $query = "UPDATE peche_$source.$table SET "
                . "datetime = now(), "
                . "username = '$username', datetime_d = '".$_POST['datetime_d']."', datetime_r = '".$_POST['datetime_r']."', "
                . "obs_name = '".$_POST['obs_name']."', t_site = '".$_POST['t_site']."', fish_name = '".$_POST['fish_name']."', "
                . "license = '".$_POST['license']."', t_net = '".$_POST['t_net']."', net_s = '".$_POST['net_s']."', "
                . "net_l = '".$_POST['net_l']."', n_days = '".$_POST['n_days']."', t_group = '".$_POST['t_group']."', "
                . "t_species = '".$_POST['t_species']."', sample_s = '".$_POST['smaple_s']."', n_ind = '".$_POST['n_ind']."'"
                . " WHERE id = '{".$_POST['id']."}'";
    }
    
    $query = str_replace('\'\'', 'NULL', $query);
    
    #print $query;

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/edit_form.php?source=$source&table=captures&action=show");
    }

    $controllo = 1;
}


if (!$controllo) {
    
if ($source == 'trawlers') {
    if(project($username,'ind_obs')) {
        ?>
        <h2>Tables</h2>
        <table>
        <tr><td>Captures records</td><td><a href="edit_form.php?source=<?php print $source; ?>&table=captures&action=show">Donwload CSV</a></td></tr>
        <tr><td>Fishing Effort</td><td><a href="download_form.php?source=<?php print $source; ?>&table=fishing%20effort&action=show">Donwload CSV</a></td></tr>
        <tr><td>fleet</td><td><a href="download_form.php?source=<?php print $source; ?>&table=fleet&action=show">Donwload CSV</a></td></tr>
        <tr><td>Market price</td><td><a href="download_form.php?source=<?php print $source; ?>&table=market%20price&action=show">Donwload CSV</a></td></tr>
        </table>
        <?php
    } else {
        msg_noaccess();
    }

} else if ($source == 'purse seiners') {
    if(project($username,'ind_obs')) {
        ?>
        <h2>Tables</h2>
        <table>
        <tr><td>Captures records</td><td><a href="download_form.php?source=<?php print $source; ?>&table=captures&action=download">Donwload</a></td></tr>
        <tr><td>Fishing Effort</td><td><a href="download_form.php?source=<?php print $source; ?>&table=fishing%20effort&action=download">Donwload</a></td></tr>
        <tr><td>fleet</td><td><a href="download_form.php?source=<?php print $source; ?>&table=fleet&action=download">Donwload</a></td></tr>
        <tr><td>Market price</td><td><a href="download_form.php?source=<?php print $source; ?>&table=market%20price&action=download">Donwload</a></td></tr>
        </table>
        <?php
    } else {
        msg_noaccess();
    }
} else if ($source == 'artisanal') {
    if(project($username,'art_obs')) {
        print "<a href=\"./edit.php\">edit</a> > <a href=\"./edit_form.php?source=$source\">".label2name($source)."</a>";
        ?>
        <h2>Tables</h2>
        <table>
        <tr><td>Captures records</td><td><a href="edit_form.php?source=<?php print $source; ?>&table=captures&action=show">Edit</a></td></tr>
        <tr><td>Fishing Effort</td><td><a href="edit_form.php?source=<?php print $source; ?>&table=effort&action=show">Edit</a></td></tr>
        <tr><td>fleet</td><td><a href="edit_form.php?source=<?php print $source; ?>&table=fleet&action=show">Edit</a></td></tr>
        <tr><td>Market price</td><td><a href="edit_form.php?source=<?php print $source; ?>&table=market&action=show">Edit</a></td></tr>
        </table>
        <?php
    } else {
        msg_noaccess();
    }
}
}
foot();
