<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'records';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $start = $_GET['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    $query = "SELECT count(captures.id) FROM artisanal.captures";

    $pnum = pg_fetch_row(pg_query($query))[0];

    $query = "SELECT artisanal.captures.id, captures.datetime::date, username, datetime_d, datetime_r, obs_name, t_site.site, "
            . "license_id, immatriculation, t_gear.gear, net_s, net_l, cap_tot, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, "
            . "sample_s, n_ind, st_astext(gps_track) FROM artisanal.captures "
            . "LEFT JOIN fishery.species ON fishery.species.id = artisanal.captures.id_species "
            . "LEFT JOIN artisanal.t_site ON artisanal.t_site.id = artisanal.captures.t_site "
            . "LEFT JOIN artisanal.t_gear ON artisanal.t_gear.id = artisanal.captures.t_gear "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";

    #print $query;

    $r_query = pg_query($query);

    print "<table id=\"small\">";
    print "<tr align=\"center\"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Date de d&eacute;part</b></td>
    <td><b>Date de retour</b></td>
    <td><b>Enqu&ecirc;teur</b></td>
    <td><b>D&eacute;barcad&egrave;re</b></td>
    <td><b>Autorisation de p&ecirc;che</b></td>
    <td><b>Engin p&ecirc;che</b></td>
    <td><b>Taille filet [cm]</b></td>
    <td><b>Longueur filet [m]</b></td>
    <td><b>Esp&egrave;ce</b></td>
    <td><b>Capture total [kg]</b></td>
    <td><b>&Eacute;chantillon [kg]</b></td>
    <td><b>Numbre des individue</b></td>
    <td><b>trace GPS</b></td>
    </tr>";

    while ($results = pg_fetch_row($r_query)) {
        if ($results[7] != "") {
            $query = "SELECT id, license FROM artisanal.license WHERE id = '$results[7]'";
            $res_l = "<a href=\"./view_license.php?id=$results[7]\">".pg_fetch_row(pg_query($query))[1]."</a>";
        } else {
            $res_l = $results[8];
        }

        print "<tr align=\"center\"><td>";

        if (right_write($_SESSION['username'],5,2)) {
            print "<a href=\"./view_records_capture.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_records_capture.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }

        print "</td>";

        if ($results[20] != "") {
            $gps_track = "<a href=\"./view_track.php?id=$results[0]\">Yes</a>";
        } else {
            $gps_track = "No";
        }

        $species = formatSpecies($results[14],$results[15],$results[16],$results[17]);

        print "<td>$results[1]<br/>$results[2]</td><td>$results[3]</td><td>$results[4]</td><td>$results[5]</td><td>$results[6]</td><td>$res_l</td><td>$results[9]</td>"
        . "<td>$results[10]</td><td>$results[11]</td><td>$species</td><td>$results[12]</td><td>$results[18]</td><td>$results[19]</td><td>$gps_track</td>";

    }

    print "</tr>";

    print "</table>";

    pages($start,$step,$pnum,"./view_records_capture.php?source=$source&table=$table&action=show");

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT *, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM artisanal.captures "
        . "LEFT JOIN fishery.species ON fishery.species.id = artisanal.captures.id_species "
        . "WHERE captures.id = '$id'";

    print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    // get and plot geometry

    if ($results[17] != "") {
        $query_t = "SELECT st_astext(gps_track) FROM artisanal.captures WHERE id = '$id'";
        $gps_track = pg_fetch_array(pg_query($query_t));

    //plot it in gmaps

    $gps_track_points = split(",", $gps_track[0]);

    $lat = array();
    $lon = array();

    for ($i = 1; $i < count($gps_track_points)-10; $i = $i+10) {
        $lat[] = split(" ",$gps_track_points[$i])[1];
        $lon[] = split(" ",$gps_track_points[$i])[0];
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

    print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\"><br/>"
        . "<b>Supprimer la trace GPS</b><br/>"
        . "<input type=\"submit\" name=\"delete\" value=\"Delete Track\" />"
        . "<input type=\"hidden\" name=\"id\" value=\"$id\" />"
        . "</form><br/>";

    }
    ?>

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Importer une nouvelle trace GPS</b> (KML format)
    <br/>
    <input type="file" size="40" name="kml_file" />
    <br/>
    <br/>
    <b>Date et heure de d&eacute;part</b> (aaaa-mm-jj hh:mm:ss)
    <br/>
    <input type="text" size="20" name="datetime_d" value="<?php print $results[3];?>" />
    <br/>
    <br/>
    <b>Date et heure de retur</b> (aaaa-mm-jj hh:mm:ss)
    <br/>
    <input type="text" size="20" name="datetime_r" value="<?php print $results[4];?>" />
    <br/>
    <br/>
    <b>Nom du collecteur</b>
    <br/>
    <input type="text" size="20" name="obs_name" value="<?php print $results[5];?>" />
    <br/>
    <br/>
    <b>D&eacute;barcad&egrave;re</b>
    <br/>

    <select name="t_site">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_site ORDER BY site");
    while($row = pg_fetch_row($result)) {
    if ($row[0] == $results[6]) {
        print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
    } else {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Autorisation de p&ecirc;che</b>
    <?php
    if ($results[7] != "") {
        ?>
	<br/>
	<select name="license_id" onchange="fishName('licnum','license_id','')">
        <option value="" >-- Not in the list --</option>
        <?php
        $result = pg_query("SELECT id, license FROM artisanal.license WHERE active IS TRUE ORDER BY license");
        while($row = pg_fetch_row($result)) {
            if ($results[7] == $row[0]) {
                print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
            } else {
                print "<option value=\"$row[0]\">$row[1]</option>";
            }
        }
        ?>
        </select>
        <br/>
        <br/>
        <div id="licnum" style="display:none">
        <input type="text" size="10" name="immatriculation" />
        </div>
        <?php
    } else {
        ?>
        <br/>
	<select name="license_id" onchange="fishName('licnum','license_id','')">
        <option value="">-- Pas dans la liste --</option>
        <?php
        $result = pg_query("SELECT id, license FROM artisanal.license WHERE active IS TRUE ORDER BY license");
        while($row = pg_fetch_row($result)) {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
        ?>
        </select>
        <br/>
        <br/>
        <div id="licnum" style="display:block">
        <input type="text" size="10" name="immatriculation" value="<?php echo $results[8];?>"/>
        </div>
        <?php
    }

    ?>
    <br/>
    <b>Engin de p&ecirc;che</b>
    <br/>
    <select name="t_gear">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_gear ORDER BY gear");
    while($row = pg_fetch_row($result)) {
        if ($results[9] == $row[0]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Taille de maille</b> (de c&ocirc;t&eacute;, en mm)
    <br/>
    <input type="text" size="8" name="net_s" value="<?php print $results[10];?>" />
    <br/>
    <br/>
    <b>Longueur de filet</b> (m)
    <br/>
    <input type="text" size="4" name="net_l" value="<?php print $results[11];?>" />
    <br/>
    <br/>
    <b>Capture total </b> (kg)
    <br/>
    <input type="text" size="4" name="cap_tot" value="<?php echo $results[12];?>" />
    <br/>
    <br/>
    <b>Famille</b>
    <br/>
    <select id="family" name="family" onchange="menu_pop_species('family','species','family','species','fishery.species')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT family FROM fishery.species ORDER BY family");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[20]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select id="species" name="id_species">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT id, family, genus, species, francaise FROM fishery.species WHERE family = '$results[20]' ORDER BY genus, species");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[18]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
        } else {
            print "<option value=\"$row[0]\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>&Egrave;chantillon </b> (kg)<br/>
    <input type="text" size="5" name="sample_s" value="<?php print $results[14];?>" />
    <br/>
    <br/>
    <b>Numbre des individue</b><br/>
    <input type="text" size="5" name="n_ind" value="<?php print $results[15];?>" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    <input type="hidden" name="id" value="<?php print $results[0]; ?>" />
    </form>
    <br/>
    <br/>

    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM artisanal.captures WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/view_records_capture.php?source=$source&table=captures&action=show");
    }

    $controllo = 1;

}

if ($_POST['delete'] == "Delete Track") {
    $query = "SELECT gps_file FROM artisanal.captures WHERE id = '{".$_POST['id']."}'";
    $gps_file = pg_fetch_row(pg_query($query));

    $query = "UPDATE artisanal.captures SET "
                . " gps_file = NULL, gps_track = NULL "
                . " WHERE id = '{".$_POST['id']."}'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        $string = substr($gps_file[0], 0, -4);
        exec("/bin/rm -f $string*");
        header("Location: ".$_SESSION['http_host']."/artisanal/view_records_capture.php?source=$source&table=captures&action=edit&id=".$_POST['id']);
    }

}

if ($_POST['submit'] == "Enregistrer") {

    # get current track info

    $query = "SELECT gps_file, st_astext(gps_track) FROM artisanal.captures WHERE id = '{".$_POST['id']."}'";
    $gps_file = pg_fetch_row(pg_query($query))[0];
    $gps_track = "'".pg_fetch_row(pg_query($query))[1]."',4326";

    # if new track is uploaded

    if ($_FILES['kml_file']['tmp_name'] != "") {
        $filename = $_FILES['kml_file']['tmp_name'];
        $filename_out = uniqid($username);
        # generate shapefiles

        exec("cp $filename ./files/tracks/$filename_out.kml");
        exec("chmod 0777 ./files/tracks/$filename_out.kml");

        $xml = file_get_contents($filename);

        $string = split("coordinates",$xml)[1];
        $string = str_replace([">","</"],"",$string);
        $coords_array = split(" ",$string);

        $coords = "";

        foreach ($coords_array as $coord) {
         $coords = $coords . str_replace(","," ",$coord) . ",";
        }
        $coords = rtrim($coords,",");
        $gps_track = "'LINESTRING Z(" . $coords . ")',4326";
        $gps_file = "./files/tracks/$filename_out.kml";
    }

    if ($gps_track == "") {
        $gps_track = 'NULL';
    }

    if ($_POST['new_old']) {
        $query = "INSERT INTO artisanal.captures "
        . "(username, datetime_d, datetime_r, obs_name, t_site, license_id, immatriculation, t_gear, "
        . "net_s, net_l, cap_tot, id_species, sample_s, n_ind, gps_file, gps_track) "
        . "VALUES ('$username', '".$_POST['datetime_d']."', '".$_POST['datetime_r']."', '".$_POST['obs_name']."', "
        . "'".$_POST['t_site']."', '".$_POST['license_id']."', '".$_POST['immatriculation']."', '".$_POST['t_gear']."', "
        . "'".$_POST['net_s']."', '".$_POST['net_l']."', '".$_POST['cap_tot']."', '".$_POST['id_species']."', "
        . "'".$_POST['sample_s']."', '".$_POST['n_ind']."', '".$gps_file."', ST_GeomFromText($gps_track)) ";
    } else {
        $query = "UPDATE artisanal.captures SET "
        . "username = '$username', datetime_d = '".$_POST['datetime_d']."', datetime_r = '".$_POST['datetime_r']."', "
        . "obs_name = '".$_POST['obs_name']."', t_site = '".$_POST['t_site']."', "
        . "license_id = '".$_POST['license_id']."', "
        . "immatriculation = '".$_POST['immatriculation']."', t_gear = '".$_POST['t_gear']."', net_s = '".$_POST['net_s']."', "
        . "net_l = '".$_POST['net_l']."', cap_tot = '".$_POST['cap_tot']."',"
        . "id_species = '".$_POST['id_species']."', sample_s = '".$_POST['sample_s']."', n_ind = '".$_POST['n_ind']."', "
        . "gps_file = '$gps_file', gps_track = ST_GeomFromText($gps_track) "
        . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/artisanal/view_records_capture.php?source=$source&table=captures&action=show");
    }

}

foot();
