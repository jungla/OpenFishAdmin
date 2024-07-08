<?php
require('top_foot.inc.php');
require("functions.inc.php");

top();

$_SESSION['where'] = 'industrial';

// edit record
// 1 - DELETE
// 2 - EDIT
// 3 - DISPLAY 

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

// 1 - DELETE

$id = filter_input(INPUT_POST, 'delete');

if (isset($id)) {
    $query = "DELETE FROM peche_trawlers.peche_poi WHERE id = '$id'";
    print $query;
    if(!pg_query($query)) {
    echo "<strong>Unknown error.</strong>";
    } else {
    header("Location: ".$_SESSION['http_host']."/edit.php");
    }
    $controllo = 1;
}

// 2 - EDIT

$id = filter_input(INPUT_POST, 'edit');

if (isset($id)) {
    //find record id
    $q_id = "SELECT id, navire, maree, date_lance, lance, h_d, h_f,  prof_d, prof_f, vitesse, rejet, ech, ST_X(point_d), ST_Y(point_d),ST_X(point_f), ST_Y(point_f) FROM peche_trawlers.peche_poi WHERE id = '$id'";
    $r_id = pg_query($q_id);
    $record = pg_fetch_array($r_id);

    $navire = $record[1];
    $maree = $record[2];
    $date = $record[3];
    $lance = $record[4];
    $lon_deg_d = DD2DM($record[12])['deg'];
    $lon_dec_d = DD2DM($record[12])['min'];
    $lat_deg_d = DD2DM($record[13])['deg'];
    $lat_dec_d = DD2DM($record[13])['min'];
    $lon_deg_f = DD2DM($record[14])['deg'];
    $lon_dec_f = DD2DM($record[14])['min'];
    $lat_deg_f = DD2DM($record[15])['deg'];
    $lat_dec_f = DD2DM($record[15])['min'];
    $h_d = $record[5];
    $h_f = $record[6];
    $prof_d = $record[7];
    $prof_f = $record[8];
    $vitesse = $record[9];
    $rejet = $record[10];
    $ech = $record[11];

    if(!pg_query($q_id)) {
    echo "<strong>Unknown error.</strong>";
    } else {
    ?>

    <form method="post" action="<?php echo $self?>" enctype="multipart/form-data"><br/>
    <strong>
    Ship<br/>
    <input type="text" size="20" name="navire" value="<?php echo $navire;?>" />
    <br/>
    <br/>
    Mission<br/>
    <input type="text" size="10" name="maree" value="<?php echo $maree;?>" />
    <br/>
    <br/>
    Date (yyyy-mm-dd)<br/>
    <input type="text" size="10" name="date" value="<?php echo $date;?>" />
    <br/>
    <br/>
    Cast<br/>
    <input type="text" size="3" name="lance" value="<?php echo $lance;?>" />
    <br/>
    <br/>
    Initial Position (positive north and east)<br/>
    Longitude: degree <input type="text" size="3" name="lon_deg_d" value="<?php echo $lon_deg_d;?>" />
    decimal <input type="text" size="8" name="lon_dec_d" value="<?php echo $lon_dec_d;?>" />
    <br/>
    Latitude: degree <input type="text" size="3" name="lat_deg_d" value="<?php echo $lat_deg_d;?>" />
    decimal <input type="text" size="8" name="lat_dec_d" value="<?php echo $lat_dec_d;?>" />
    <br/>
    <br/>
    Final Position (positive north and east)<br/>
    Longitude: degree <input type="text" size="3" name="lon_deg_f" value="<?php echo $lon_deg_f;?>" />
    decimal <input type="text" size="8" name="lon_dec_f" value="<?php echo $lon_dec_f;?>" />
    <br/>
    Latitude: degree <input type="text" size="3" name="lat_deg_f" value="<?php echo $lat_deg_f;?>" />
    decimal <input type="text" size="8" name="lat_dec_f" value="<?php echo $lat_dec_f;?>" />
    <br/>
    <br/>
    Hour start (hh:mm:ss)<br/>
    <input type="text" size="8" name="h_d" value="<?php echo $h_d;?>" />
    <br/>
    <br/>
    Hour end (hh:mm:ss)<br/>
    <input type="text" size="8" name="h_f" value="<?php echo $h_f;?>" />
    <br/>
    <br/>
    Depth start (m)<br/>
    <input type="text" size="4" name="prof_d" value="<?php echo $prof_d;?>" />
    <br/>
    <br/>
    Depth End (m)<br/>
    <input type="text" size="4" name="prof_f" value="<?php echo $prof_f;?>" />
    <br/>
    <br/>
    Speed (nd)<br/>
    <input type="text" size="3" name="vitesse" value="<?php echo $vitesse;?>" />
    <br/>
    <br/>
    Reject <br/>
    <input type="text" size="6" name="rejet" value="<?php echo $rejet;?>" />
    <br/>
    <br/>
    Sample size (kg)<br/>
    <input type="text" size="5" name="ech" value="<?php echo $ech;?>" />
    <br/>
    <br/>
    <input type="submit" name="submit" value="Submit" />
    <input type="hidden" name="submit" value="<?php echo $id;?>"/>
    </strong>
    </form>
    <br/><br/>
    <?php
    }

    $controllo = 1;
}	

// 3 - DISPLAY

$id = filter_input(INPUT_POST, 'submit');

if (isset($id)) {
    $navire = filter_input(INPUT_POST, 'navire');
    $maree = filter_input(INPUT_POST, 'maree');
    $date = filter_input(INPUT_POST, 'date');
    $lance = filter_input(INPUT_POST, 'lance');
    $lon_deg_d = filter_input(INPUT_POST, 'lon_deg_d');
    $lon_dec_d = filter_input(INPUT_POST, 'lon_dec_d');
    $lat_deg_d = filter_input(INPUT_POST, 'lat_deg_d');
    $lat_dec_d = filter_input(INPUT_POST, 'lat_dec_d');
    $lon_deg_f = filter_input(INPUT_POST, 'lon_deg_f');
    $lon_dec_f = filter_input(INPUT_POST, 'lon_dec_f');
    $lat_deg_f = filter_input(INPUT_POST, 'lat_deg_f');
    $lat_dec_f = filter_input(INPUT_POST, 'lat_dec_f');
    $h_d = filter_input(INPUT_POST, 'h_d');
    $h_f = filter_input(INPUT_POST, 'h_f');
    $prof_d = filter_input(INPUT_POST, 'prof_d');
    $prof_f = filter_input(INPUT_POST, 'prof_f');
    $vitesse = filter_input(INPUT_POST, 'vitesse');
    $rejet = filter_input(INPUT_POST, 'rejet');
    $ech = filter_input(INPUT_POST, 'ech');

    if (
    trim($lon_deg_d) == "" OR trim($lon_dec_d) == "" OR trim($lat_deg_d) == ""  OR trim($lat_dec_d) == "" OR
    trim($lon_deg_f) == "" OR trim($lon_dec_f) == "" OR trim($lat_deg_f) == ""  OR trim($lat_dec_f) == ""
    )
    {
    echo "<strong>Coordinates are required</strong><br/>
    <form method=\"POST\" target=\"$self\">
    <input type=\"hidden\" name=\"navire\" value=\"$navire\">
    <input type=\"hidden\" name=\"maree\" value=\"$maree\">
    <input type=\"hidden\" name=\"date\" value=\"$date\">
    <input type=\"hidden\" name=\"lance\" value=\"$lance\">
    <input type=\"hidden\" name=\"lon_deg_d\" value=\"$lon_deg_d\">
    <input type=\"hidden\" name=\"lon_dec_d\" value=\"$lon_dec_d\">
    <input type=\"hidden\" name=\"lat_deg_d\" value=\"$lat_deg_d\">
    <input type=\"hidden\" name=\"lat_dec_d\" value=\"$lat_dec_d\">
    <input type=\"hidden\" name=\"lon_deg_f\" value=\"$lon_deg_f\">
    <input type=\"hidden\" name=\"lon_dec_f\" value=\"$lon_dec_f\">
    <input type=\"hidden\" name=\"lat_deg_f\" value=\"$lat_deg_f\">
    <input type=\"hidden\" name=\"lat_dec_f\" value=\"$lat_dec_f\">
    <input type=\"hidden\" name=\"h_d\" value=\"$h_d\">
    <input type=\"hidden\" name=\"h_f\" value=\"$h_f\">
    <input type=\"hidden\" name=\"prof_d\" value=\"$prof_d\">
    <input type=\"hidden\" name=\"prof_f\" value=\"$prof_f\">
    <input type=\"hidden\" name=\"vitesse\" value=\"$vitesse\">
    <input type=\"hidden\" name=\"rejet\" value=\"$rejet\">
    <input type=\"hidden\" name=\"ech\" value=\"$ech\">
    <br/><br/>      
    <input type=\"submit\" name=\"Back\" value=\"Back\">
    </form>";
    foot();
    die();

    } else {

    //find record id

    $lon_d = $lon_deg_d+$lon_dec_d;
    $lat_d = $lat_deg_d+$lat_dec_d;
    $lon_f = $lon_deg_f+$lon_dec_f;
    $lat_f = $lat_deg_f+$lat_dec_f;

    $query = "UPDATE peche_trawlers.peche_poi SET id='$id', date=now(), navire='$navire', maree='$maree', date_lance='$date', lance='$lance', h_d='$h_d', h_f='$h_f', prof_d='$prof_d', prof_f='$prof_f', vitesse='$vitesse', rejet='$rejet', ech='$ech', point_d=ST_GeomFromText('POINT($lon_d $lat_d)', 4326), point_f=ST_GeomFromText('POINT($lon_f $lat_f)', 4326) WHERE id='$id';";

    print $query;

    if(!pg_query($query))
    {
    echo "<strong>Unknown error.</strong>";
    }
    else
    {
    header("Location: ".$_SESSION['http_host']."/edit.php");
    }

    $controllo = 1;
    }
}

//se non ancora submitted

if (!$controllo) {
    $query = "SELECT id,navire, maree, date_lance, lance,h_d, h_f,  prof_d, prof_f, vitesse, rejet, ech, ST_AsLatLonText(point_d,'Dd M.MMm C'), ST_AsLatLonText(point_f,'Dd M.MMm C') ,date FROM peche_trawlers.peche_poi ORDER BY date DESC";
    $result = pg_query($query);
    
    print "<font size='1'>";
    print "<table>";
    print "<tr><td></td><td><b>Ship</b></td><td><b>Mission</b></td><td><b>Date</b></td><td><b>Cast</b></td><td><b>time start</b></td><td><b>time end</b></td>";
    print "<td><b>Depth Start</b></td><td><b>Depth End</b></td><td><b>Speed</b></td><td><b>Rejected</b></td><td><b>Sample (kg)</b></td><td><b>Point Start</b></td><td><b>Point End</b></td><td><b>Date Inserted</b></td></tr>";

    while ($row = pg_fetch_array($result)) {	
        print "<tr>";
        ?>
        <td>
        <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
        <input type="submit" name="edit" value="edit"/>
        <input type="hidden" name="edit" value="<?php echo $row[0];?>"/>
        </form>
        <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
        <input type="submit" name="delete" value="delete"/>
        <input type="hidden" name="delete" value="<?php echo $row[0];?>"/>
        </form>
        </td>
        <?php
        print "<td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td><td>$row[6]</td>";
        print "<td>$row[7]</td><td>$row[8]</td><td>$row[9]</td><td>$row[10]</td><td>$row[11]</td><td>$row[12]</td><td>$row[13]</td><td>".substr($row[14],0,16)."</td>";
        print "</tr>";
        }
    print "</table></font>";

}
	
foot();