<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'thon';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_id_species'] = $_POST['f_id_species'];
$_SESSION['filter']['f_s_year'] = $_POST['f_s_year'];

if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}
if ($_GET['f_s_year'] != "") {$_SESSION['filter']['f_s_year'] = $_GET['f_s_year'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'map') {

    $query_t = "SELECT navire, date_c, ST_Y(location), ST_X(location) FROM thon.maree";
    $query_r = pg_query($query_t);

    //plot it in gmaps

    $lon_m = 0;
    $lat_m = 0;

    while($row = pg_fetch_array($query_r) ){
            $LKP[] = $row;
        }

    for ($i = 0; $i < count($LKP); $i++) {
        $lon_m = $lon_m + $LKP[$i][3];
        $lat_m = $lat_m + $LKP[$i][2];
    }

    $lon_m = $lon_m/count($LKP);
    $lat_m = $lat_m/count($LKP);

    print "<div id=\"map\" style=\"height: 400px; width:100%; border: 1px solid black; float:right; margin-left:5%; margin-top:1em;\"></div>";

    print "<script  type=\"text/javascript\">

        var locations = [";

        for ($i = 0; $i < count($LKP)-2; $i++) {
            print "['".$LKP[$i][0]." ".$LKP[$i][1]."', ".$LKP[$i][2].", ".$LKP[$i][3]."],";
        }

        print "['".$LKP[count($LKP)-1][0]." ".$LKP[count($LKP)-1][1]."', ".$LKP[count($LKP)-1][2].", ".$LKP[count($LKP)-1][3]."]";

        print "];

    function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: new google.maps.LatLng($lat_m, $lon_m),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/AiresProtegeesAquatiques_20170601_Final_EPSG4326.geojson');
        map.data.loadGeoJson('http://data.gabonbleu.org/shapefiles/ZoneTamponParcsMarins_20170601_Final.geojson');

        map.data.setStyle({
          fillColor: 'green',
          strokeWeight: 1
        });


        var infowindow = new google.maps.InfoWindow();

        var circle = {
        path: google.maps.SymbolPath.CIRCLE,
        fillColor: 'red',
        fillOpacity: .4,
        scale: 4.5,
        strokeColor: 'white',
        strokeWeight: 1
        };
        
        var marker, i;

        for (i = 0; i < locations.length; i++) {  
            marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: 'https://storage.googleapis.com/support-kms-prod/SNP_2752125_en_v0'
          });

          google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
              infowindow.setContent(locations[i][0]);
              infowindow.open(map, marker);
            }
          })(marker, i));
        }
    }    
        </script>

    <script async defer
    src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyBI5MQWC4N5SgUXs989_7MTDkQghaiGUuA&callback=initMap\">
    </script>";

    print "<br/><a href=\"javascript:history.back()\">Retourner</a>";

} else if ($_GET['action'] == 'show') {
    
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    // find maree

    ?>

    <table>
        <tr>
            <td></td>
            <td><b>Utilisateur & date</b></td>
            <td nowrap><b>Date lanc&eacute;</b><br/>[aaaa-mm-jj]</td>
            <td><b>Heure lanc&eacute;</b><br/>[hh:mm:ss]</td>
            <td><b>Numbre lanc&eacute;</b></td>
            <td><b>Point GPS</b></td>
            <td><b>EEZ</b></td>
            <td><b>Banc libre</b></td>
            <td><b>Code balise</b></td>
            <td><b>Temperature et vitesse courant</b></td>
            <td><b>Vitesse et direction vent</b></td>
            <td><b>Remarque</b></td>
        </tr>
        
        <?php

        $query_lance = "SELECT *, st_x(location), st_y(location) FROM thon.lance ";
        
        $r_query_lance = pg_query($query_lance);

        while ($results_lance = pg_fetch_row($r_query_lance)) {
            $lon = $results_lance[17];
            $lat = $results_lance[18];

            $lon_deg = intval($lon);
            $lat_deg = intval($lat);

            $lon_min = round(($lon - $lon_deg)*60);
            $lat_min = round(($lat - $lat_deg)*60);

           
        ?>

        <!-- loop this line with results -->
        <tr>
            <td>
            <a href="./view_thon_lance.php?id_lance=<?php print $results_lance[0]; ?>&source=thon&table=lance&action=edit">Modifier</a><br/>
            <a href="./view_thon_lance.php?id_lance=<?php print $results_lance[0]; ?>&source=thon&table=lance&action=delete">Effacer</a>
            </td>
            <td><?php echo $results_lance[2]."<br/>".$results_lance[1]; ?></td>
            
            <td nowrap><?php echo $results_lance[4]; ?></td>
            <td><?php echo $results_lance[5]; ?></td>
            <td><?php echo $results_lance[6]; ?></td>

            <td nowrap>
            <?php echo abs($lat_deg);?>&deg;
            <?php echo abs($lat_min);?>&prime;
                <?php if($lat_deg >= 0) {print "N";} ?></option>
                <?php if($lat_deg < 0) {print "S";} ?></option>
            <br/>
            <?php echo abs($lon_deg);?>&deg;
            <?php echo abs($lon_min);?>&prime;
                <?php if($lon_deg >= 0) {print "E";} ?></option>
                <?php if($lon_deg < 0) {print "W";} ?></option>
            </td>
            <td><?php echo $results_lance[7]; ?></td>
            <td><?php echo $results_lance[9]; ?></td>
            <td><?php echo $results_lance[10]; ?></td>
            <td><?php echo $results_lance[11]." - ".$results_lance[14]; ?></td>
            <td><?php echo $results_lance[12]." - ".$results_lance[13];  ?></td>
            <td><?php echo $results_lance[15]; ?></td>
        </tr>

        <?php
        }
        ?>
    </table>
    <br/>

<?php

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";
    
    $id = $_GET['id_lance'];

    //find record info by ID
    $query = "SELECT * FROM thon.captures "
            . "LEFT JOIN fishery.species ON fishery.species.id = captures.id_species "
        . "WHERE captures.id_lance = '$id' ORDER BY captures.datetime DESC";
    
    $r_c = pg_query($query);
    
    $query = "SELECT *, st_x(location), st_y(location) FROM thon.lance "
        . "LEFT JOIN thon.captures ON captures.id_lance = lance.id "
        . "WHERE lance.id = '$id' ORDER BY lance.datetime DESC";
    
    $r_l = pg_query($query);
    $results_l = pg_fetch_row($r_l);
    
    $lon = $results_l[26];
    $lat = $results_l[27];
    
    $lon_deg = intval($lon);
    $lat_deg = intval($lat);

    $lon_min = round(($lon - $lon_deg)*60);
    $lat_min = round(($lat - $lat_deg)*60);
    
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <table>
        <tr>
            <td colspan="2"><b>Point GPS</b></td>
            <td rowspan="2"><b>Date lanc&eacute;</b><br/>[aaaa-mm-jj]</td>
            <td rowspan="2"><b>Heure lanc&eacute;</b><br/>[hh:mm:ss]</td>
            <td rowspan="2"><b>Numbre lanc&eacute;</b></td>
            <td rowspan="2"><b>EEZ</b></td>
            <td rowspan="2"><b>Banc libre</b></td>
            <td rowspan="2"><b>Code balise</b></td>
            <td rowspan="2"><b>Remarque</b></td>
            
        </tr>
        <tr>
            <td><b>Latitude</b></td>
            <td><b>Longitude</b></td>
        </tr>
        <tr>
            <td>
            <input type="text" size="3" name="lat_deg" value="<?php echo abs($lat_deg);?>" /> &deg;
            <input type="text" size="5" name="lat_min" value="<?php echo abs($lat_min);?>" /> &prime;
            <select name="NS">
                <option value="N" <?php if($lat_deg > 0) {print "selected=\"selected\"";} ?> >N</option>
                <option value="S" <?php if($lat_deg < 0) {print "selected=\"selected\"";} ?> >S</option>
            </select>
            </td>
            <td>
            <input type="text" size="3" name="lon_deg" value="<?php echo abs(abs($lon_deg));?>" /> &deg;
            <input type="text" size="5" name="lon_min" value="<?php echo abs(abs($lon_min));?>" /> &prime;
            <select name="EW">
                <option value="E" <?php if($lon_deg > 0) {print "selected=\"selected\"";} ?> >E</option>
                <option value="W" <?php if($lon_deg < 0) {print "selected=\"selected\"";} ?> >W</option>
            </select>
            </td>
            
            <td><input type="text" size="15" name="date_c" value="<?php echo $results_l[4]; ?>"/></td>
            <td><input type="text" size="10" name="heure_c" value="<?php echo $results_l[5]; ?>"/></td>
            <td><input type="text" size="5" name="lance" value="<?php echo $results_l[6]; ?>"/></td>
            <td><input type="text" size="10" name="eez" value="<?php echo $results_l[7]; ?>"/></td>
            <td>
            Oui<input type="radio" name="banclibre" value="TRUE" <?php if ($results_l[9] == 't') {print 'checked';} ?>>
            No<input type="radio" name="banclibre" value="FALSE" <?php if ($results_l[9] != 't') {print 'checked';} ?>>
            </td>
            <td><input type="text" size="20" name="balise_id" value="<?php echo $results_l[10]; ?>"/></td>
            <td><textarea rows="4" cols="20" name="comment"><?php echo $results_l[15]; ?></textarea></td>
            
            
        </tr>
    </table>
    <br/>
    <table>
        <tr>
            <td colspan="2"><b>YFT</b></td>
            <td colspan="2"><b>SKJ</b></td>
            <td colspan="2"><b>BET</b></td>
            <td colspan="2"><b>ALB</b></td>
            <td colspan="3"><b>Autre</b></td>
            <td rowspan="2"><b>Rejete</b></td>
        </tr>
        <tr>
            <td>Taille [kg]</td>
            <td>Poids [t]</td>
            <td>Taille [kg]</td>
            <td>Poids [t]</td>
            <td>Taille [kg]</td>
            <td>Poids [t]</td>
            <td>Taille [kg]</td>
            <td>Poids [t]</td>
            <td>Espece</td>
            <td>Taille [kg]</td>
            <td>Poids [t]</td>
        </tr>
        <?php 
        $i = 0;
        
        while($results_c = pg_fetch_row($r_c)) 
        {
        ?>
        
        <tr>
            <input type="hidden" value="<?php echo $results_c[0]; ?>" name="id_captures_<?php print $i; ?>"/>
 
            <td><input type="text" size="5" name="taille_YFT_<?php print $i; ?>" value="<?php if($results_c[14] == 'YFT'){ echo $results_c[7];} ?>"/></td>
            <td><input type="text" size="5" name="poids_YFT_<?php print $i; ?>" value="<?php if($results_c[14] == 'YFT'){ echo $results_c[8];} ?>"/></td>
            <td><input type="text" size="5" name="taille_SKJ_<?php print $i; ?>" value="<?php if($results_c[14] == 'SKJ'){ echo $results_c[7];} ?>"/></td>
            <td><input type="text" size="5" name="poids_SKJ_<?php print $i; ?>" value="<?php if($results_c[14] == 'SKJ'){ echo $results_c[8];} ?>"/></td>
            <td><input type="text" size="5" name="taille_BET_<?php print $i; ?>" value="<?php if($results_c[14] == 'BET'){ echo $results_c[7];} ?>"/></td>
            <td><input type="text" size="5" name="poids_BET_<?php print $i; ?>" value="<?php if($results_c[14] == 'BET'){ echo $results_c[8];} ?>"/></td>
            <td><input type="text" size="5" name="taille_ALB_<?php print $i; ?>" value="<?php if($results_c[14] == 'ALB'){ echo $results_c[7];} ?>"/></td>
            <td><input type="text" size="5" name="poids_ALB_<?php print $i; ?>" value="<?php if($results_c[14] == 'ALB'){ echo $results_c[8];} ?>"/></td>
            <td>
                <select name="id_species_new_<?php print $i; ?>">
                <option value="none">Aucun</option>
                <?php 
                    $result = pg_query("SELECT DISTINCT id, FAO FROM fishery.species WHERE FAO is not NULL ORDER BY FAO");
                    while($row = pg_fetch_row($result)) {
                        if ($row[1] == $results_c[14] and $results_c[14] != 'YFT' and $results_c[14] != 'SKJ' and $results_c[14] != 'BET' and $results_c[14] != 'ALB') {
                            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
                        } else {
                            print "<option value=\"$row[0]\">".$row[1]."</option>";
                        }
                    }
                 
            ?>  
            </select>
            </td>
            <td><input type="text" size="5" name="taille_new_<?php print $i; ?>" value="<?php if($results_c[14] != 'YFT' and $results_c[14] != 'SKJ' and $results_c[14] != 'BET' and $results_c[14] != 'ALB'){ echo $results_c[7];} ?>"/></td>
            <td><input type="text" size="5" name="poids_new_<?php print $i; ?>" value="<?php if($results_c[14] != 'YFT' and $results_c[14] != 'SKJ' and $results_c[14] != 'BET' and $results_c[14] != 'ALB'){ echo $results_c[8];} ?>"/></td>
            <td>
                Oui<input type="radio" name="rejete_<?php print $i; ?>" <?php if($results_c[5] == 't'){ echo "checked";} ?>/>
                No<input type="radio" name="rejete_<?php print $i; ?>" <?php if($results_c[5] == 'f'){ echo "checked";} ?>/>
            </td>
        </tr>
        <?php 
        $i++;
        }
        ?>
        
    </table>
    <br/>
    <table>
        <tr>
            <td><b>Temperature Eau</b></td>
            <td><b>Vitesse vent</b></td>
            <td><b>Direction vent</b></td>
            <td><b>Vitesse courant</b></td>
        </tr>
        <tr>
            <td><input type="text" size="10" name="water_temp" value="<?php echo $results_l[11]; ?>"/></td>
            <td><input type="text" size="10" name="wind_speed" value="<?php echo $results_l[12]; ?>"/></td>
            <td><input type="text" size="10" name="wind_dir" value="<?php echo $results_l[13]; ?>"/></td>
            <td><input type="text" size="10" name="cur_speed" value="<?php echo $results_l[14]; ?>"/></td>
        </tr>
    </table>
    <br/>
    <input type="hidden" value="<?php echo $results_l[0]; ?>" name="id_lance"/>
    <input type="hidden" value="<?php echo $results_l[3]; ?>" name="id_maree"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

    <?php
    
}  else if ($_GET['action'] == 'delete') {
    $id_capture = $_GET['id_capture'];
    $id_maree = $_GET['id_maree'];
    
    $query = "DELETE FROM thon.captures WHERE id = '$id_capture'";
    
    print $query;
    
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/thon/view_thon_lance.php?id_maree=$id_maree&source=$source&table=lance&action=show");
    }
    $controllo = 1;
}


if ($_POST['submit'] == "Enregistrer") {
    $lon_deg = $_POST['lon_deg'];
    $lat_deg = $_POST['lat_deg'];
    $lon_min = $_POST['lon_min'];
    $lat_min = $_POST['lat_min'];
    
    $lon = $lon_deg+$lon_min/60;
    $lat = $lat_deg+$lat_min/60;
    
    if ($lon == "" OR $lat == "") {
        $point = "NULL";
    } else {
        if ($_POST['NS'] == 'S') {$lat = -1*$lat;}
        if ($_POST['EW'] == 'W') {$lon = -1*$lon;}
        $point = "'POINT($lon $lat)'";
    }

    $id_maree = $_POST['id_maree'];
    $navire = $_POST['navire']; 
    $country = $_POST['country']; 
    $port_d = $_POST['port_d']; 
    $port_a = $_POST['port_a']; 
    $date_d = $_POST['date_d']; 
    $date_a = $_POST['date_a']; 
    $ndays = $_POST['ndays']; 
    $date_c = $_POST['date_c']; 
    $heure_c = $_POST['heure_c']; 
    $lance = $_POST['lance']; 
    $eez = $_POST['eez']; 
    $water_temp = $_POST['water_temp']; 
    $wind_speed = $_POST['wind_speed']; 
    $wind_dir = $_POST['wind_dir']; 
    $cur_speed = $_POST['cur_speed']; 
    $success = $_POST['success']; 
    $banclibre = $_POST['banclibre']; 
    $balise_id = $_POST['balise_id']; 
    $comment = htmlspecialchars($_POST['comment'],ENT_QUOTES); 

    // update lance first
    
    if ($_POST['new_old']) {
        // username, datetime, id_maree,   date_c,   heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, comment, location
        $query_l = "INSERT INTO thon.lance "
            . "(username, datetime, id_maree,   date_c,   heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, comment, location) "
            . "VALUES ('$username', now(), '$id_maree', '$date_c', '$heure_c', '$lance', '$eez', '$water_temp', '$wind_speed', '$wind_dir', '$cur_speed', '$comment', ST_GeomFromText($point,4326)) RETURNING id";
    } else {
        $query_l = "UPDATE thon.lance SET "
            . "username = '$username', datetime = now(), "
            . "id_maree = '".$_POST['id_maree']."', date_c = '".$_POST['date_c']."', heure_c = '".$_POST['heure_c']."', "
            . "lance = '".$_POST['lance']."', eez = '".$_POST['eez']."', water_temp = '".$_POST['water_temp']."', "
            . "wind_speed = '".$_POST['wind_speed']."', wind_dir = '".$_POST['wind_dir']."', "
            . "cur_speed = '".$_POST['cur_speed']."', comment = '".$_POST['comment']."', "
            . " location = ST_GeomFromText($point,4326)"
            . " WHERE id = '{".$_POST['id_lance']."}'";    
    }
    
    $query_l = str_replace('\'\'', 'NULL', $query_l);

    print $query_l. "<br/>";
    
    if ($_POST['new_old']) {
        $id_lance = pg_fetch_row(pg_query($query_l))[0];
    } else {
        $id_lance = $_POST['id_lance'];
    }
    
    if(!pg_query($query_l)) {
        print $query_l."<br/>";
        msg_queryerror();
    }
    
    // get number of rows that we need to modify
    
    $query_capture = "SELECT COUNT(id) FROM thon.captures "
        . "WHERE captures.id_lance = '".$_POST['id_lance']."' ";

    print $query_capture;

    $ncols = pg_fetch_row(pg_query($query_capture))[0];

    print $ncols;
    
    // if NOT new, delete old captures from this lance
    
    if (!$_POST['new_old']) {
        // update captures
        $query_delete = "DELETE FROM thon.captures WHERE captures.id_lance = '{".$_POST['id_lance']."}'";
        pg_query($query_delete);
    }
    
    print $query_delete;
    
    for ($i = 0; $i < $ncols; $i++) {
//        foreach(array_keys($_POST) as $key) {
//            print $key."<br/>";
//        }
        
        $rejete[$i] = $_POST['rejete_'.$i]; 

        $poids[0][$i] = $_POST['poids_YFT_'.$i];
        $poids[1][$i] = $_POST['poids_SKJ_'.$i];
        $poids[2][$i] = $_POST['poids_BET_'.$i];
        $poids[3][$i] = $_POST['poids_ALB_'.$i];
        $poids[4][$i] = $_POST['poids_new_'.$i];
        
        $taille[0][$i] = $_POST['taille_YFT_'.$i];
        $taille[1][$i] = $_POST['taille_SKJ_'.$i];
        $taille[2][$i] = $_POST['taille_BET_'.$i];
        $taille[3][$i] = $_POST['taille_ALB_'.$i];
        $taille[4][$i] = $_POST['taille_new_'.$i];

        $result = pg_query("SELECT id FROM fishery.species WHERE FAO = 'YFT' OR FAO = 'SKJ' OR FAO = 'BET' OR FAO = 'ALB' ORDER BY FAO");

        $id_species[0][$i] = pg_fetch_result($result, 0, 0);
        $id_species[1][$i] = pg_fetch_result($result, 1, 0);
        $id_species[2][$i] = pg_fetch_result($result, 2, 0);
        $id_species[3][$i] = pg_fetch_result($result, 3, 0);
        $id_species[4][$i] = $_POST['id_species_new_'.$i];

        print 'col:'.$i. "<br/>";
        
        for ($j = 0; $j < 5; $j++) {
            print 'i'.$i.'j'.$j. "<br/>";
            print "id_species: ".$id_species[$j][$i] . "<br/>";
            print "taille: ".$taille[$j][$i] . "<br/>";
            print "poids: ".$poids[$j][$i] . "<br/>";

            if ($id_species[$j][$i] != '' and $taille[$j][$i] != '' and $poids[$j][$i] != '') {
                // username, datetime, id_maree, id_lance, rejete, id_species, taille, poids
                $query_c = "INSERT INTO thon.captures "
                    . "(username, datetime, id_maree, id_lance, rejete, id_species, taille, poids) "
                    . "VALUES ('$username', now(), '$id_maree', '$id_lance', '$rejete[$i]', '".$id_species[$j][$i]."', '".$taille[$j][$i]."', '".$poids[$j][$i]."')";

                $query_c = str_replace('\'\'', 'NULL', $query_c);

                print $query_c;

                if(!pg_query($query_c)) {
                    print $query_c;
                    msg_queryerror();
                }

            }

        }
    }
//        print $query;
        #header("Location: ".$_SESSION['http_host']."/industrial/thon/view_thon_lance.php?id_maree=".$_POST['id_maree']."&source=$source&table=lance&action=show");
    
}

foot();
