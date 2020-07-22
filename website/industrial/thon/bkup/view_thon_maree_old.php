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

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=thon&table=maree&action=show" enctype="multipart/form-data">
    <fieldset>
    
    <table id="no-border">
    <tr>
    <td><b>Navire</b></td>
    <td><b>Ann&eacute;e maree</b></td>
    </tr>
    <tr>
    <td><input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/></td>
    <td><input type="text" size="20" name="f_s_year" value="<?php echo $_SESSION['filter']['f_s_year']?>"/></td>
    </tr>
    </table>
    <input type="submit" name="Filter" value="filter" />
    </fieldset>
    </form>

    <br/>

    <table>
    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Navire</b></td>
    <td><b>Nationalite Navire</b></td>
    <td><b>Ann&eacute;e maree</b></td>    
    <td><b>Port et date depart</b></td>    
    <td><b>Port et date arrive</b></td>    
    <td><b>Logbook</b></td> 
    <td><b>Fiche entr&eacute;e/sortie</b></td> 
    </tr>
    
    <?php
    
    // fetch data
    
    if ($_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_s_year'] != "" ) {
    
        # id, username, navire, country, port_d, port_a, date_d, date_a, date_c, heure_c, ndays, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, id_species, taille, poids, comment
        
        $_SESSION['start'] = 0;
        
        if ($_SESSION['filter']['f_s_year'] != "") { 
            $query = "SELECT count(maree.id) FROM thon.maree "
                . "WHERE year=".$_SESSION['filter']['f_s_year']." ";
            
            $pnum = pg_fetch_row(pg_query($query))[0];
            
            $query = "SELECT maree.id, maree.username, maree.datetime, navire, country, year, port_d, date_d, port_a, date_a, "
            . " coalesce(similarity(thon.maree.navire, '".$_SESSION['filter']['f_s_year']."'),0) AS score"
            . " FROM thon.maree "
            . "WHERE year=".$_SESSION['filter']['f_s_year']." "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";
            
        } else {
            
            $query = "SELECT count(maree.id) FROM thon.maree "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";
            $pnum = pg_fetch_row(pg_query($query))[0];
            
            $query = "SELECT maree.id, maree.username, maree.datetime, navire, country, year, port_d, date_d, port_a, date_a"
            . " FROM thon.maree "
            . "WHERE year=".$_SESSION['filter']['f_s_year']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step"; 
        }
    } else {
        $query = "SELECT count(maree.id) FROM thon.maree";
        $pnum = pg_fetch_row(pg_query($query))[0];
        
        $query = "SELECT maree.id, maree.username, maree.datetime, navire, country, year, port_d, date_d, port_a, date_a"
        . " FROM thon.maree "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";   
    }
    
    //print $query;
    
    $r_query = pg_query($query);
    
    while ($results = pg_fetch_row($r_query)) {
        
        print "<tr align=\"center\">";
    
        print "<td>"
        . "<a href=\"./view_thon_maree.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_thon_maree.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td>$results[3]</td><td>$results[4]</td><td>$results[5]</td><td>$results[6]<br/>$results[7]</td><td>$results[8]<br/>$results[9]</td><td><a href=\"./view_lance.php?id=$results[0]\">Logbook</a></td><td><a href=\"./view_entree.php?id=$results[0]\">Entree</a><br/><a href=\"./view_sortie.php?id=$results[0]\">Sortie</a></td></tr>";
    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_thon_maree.php?source=thon&table=maree&action=show&f_s_year='.$_SESSION['filter']['f_s_year'].'&f_id_species='.$_SESSION['filter']['f_id_species']);
    
    $controllo = 1;
    
} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";
    
    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT * FROM thon.captures "
        . "WHERE captures.id_maree = '$id'";
    
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);
    
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <h3>Donnees Navire</h3>
    <table>
        <tr>
            <td><b>Nom Navire</b></td>
            <td><b>Nationalite Navire</b></td>
            <td><b>Port depart</b></td>
            <td><b>Date depart</b><br/>[aaaa-mm-jj]</td>
            <td><b>Port arrive</b></td>
            <td><b>Date arrive</b><br/>[aaaa-mm-jj]</td>
            <td><b>Numbre jour maree</b></td>
            </tr>
            <tr>
                <td>
                <input type="text" size="20" name="navire" value="<?php echo $results[3]; ?>"/>
                </td>
                <td><input type="text" size="20" name="country" value="<?php echo $results[4]; ?>"/></td>
                <td><input type="text" size="20" name="port_d" value="<?php echo $results[5]; ?>"/></td>
                <td><input type="text" size="15" name="date_d" value="<?php echo $results[6]; ?>"/></td>
                <td><input type="text" size="20" name="port_a" value="<?php echo $results[7]; ?>"/></td>
                <td><input type="text" size="15" name="date_a" value="<?php echo $results[8]; ?>"/></td>
                <td><input type="text" size="5" name="ndays" value="<?php echo $results[9]; ?>"/></td>
            </tr>
        
    </table>
    <br/>
    <h3>Donnees lanc&eacute;</h3>
    <table>
        <tr>
            <td><b>Date lanc&eacute;</b><br/>[aaaa-mm-jj]</td>
            <td><b>Heure lanc&eacute;</b><br/>[hh:mm:ss]</td>
            <td><b>Numbre lanc&eacute;</b></td>
            <td><b>EEZ</b></td>
            <td><b>Banc libre</b></td>
            <td><b>Code balise</b></td>
            <td><b>Remarque</b></td>
        </tr>
        <tr>
            <td><input type="text" size="15" name="date_c" value="<?php echo $results[10]; ?>"/></td>
            <td><input type="text" size="10" name="heure_c" value="<?php echo $results[11]; ?>"/></td>
            <td><input type="text" size="5" name="lance" value="<?php echo $results[12]; ?>"/></td>
            <td><input type="text" size="10" name="eez" value="<?php echo $results[13]; ?>"/></td>
            <td>
            Oui<input type="radio" name="banclibre" value="TRUE" <?php if ($results[19] == 't') {print 'checked';} ?>>
            No<input type="radio" name="banclibre" value="FALSE" <?php if ($results[19] != 't') {print 'checked';} ?>>
            </td>
            <td><input type="text" size="20" name="balise_id" value="<?php echo $results[20]; ?>"/></td>
            <td><textarea rows="4" cols="20" name="comment"><?php echo $results[25]; ?></textarea></td>
        </tr>
    </table>
    <br/>
    <table>
        <tr>
            <td colspan="2"><b>Point GPS</b></td>
            <td colspan="2"><b>YFT</b></td>
            <td colspan="2"><b>SKJ</b></td>
            <td colspan="2"><b>BET</b></td>
            <td colspan="2"><b>ALB</b></td>
            <td colspan="3"><b>Rejete</b></td>
        </tr>
        <tr>
            <td>Longitude</td>
            <td>Latitude</td>
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
        <tr>
            <td>
            <input type="text" size="3" name="lat_deg" value="<?php echo $lat_deg;?>" /> &deg;
            <input type="text" size="5" name="lat_min" value="<?php echo $lat_min;?>" /> &prime;
            <select name="NS">
                <option value="N" <?php if($lat_deg > 0) {print "selected=\"selected\"";} ?> >N</option>
                <option value="S" <?php if($lat_deg < 0) {print "selected=\"selected\"";} ?> >S</option>
            </select>
            </td>
            <td>
            <input type="text" size="3" name="lon_deg" value="<?php echo abs($lon_deg);?>" /> &deg;
            <input type="text" size="5" name="lon_min" value="<?php echo abs($lon_min);?>" /> &prime;
            <select name="EW">
                <option value="E" <?php if($lon_deg > 0) {print "selected=\"selected\"";} ?> >E</option>
                <option value="W" <?php if($lon_deg < 0) {print "selected=\"selected\"";} ?> >W</option>
            </select>
            </td>
            <td><input type="text" size="5" name="taille_YFT" value="<?php if($results[32] == 'YFT'){ echo $results[23];} ?>"/></td>
            <td><input type="text" size="5" name="poids_YFT" value="<?php if($results[32] == 'YFT'){ echo $results[24];} ?>"/></td>
            <td><input type="text" size="5" name="taille_SKJ" value="<?php if($results[32] == 'SKJ'){ echo $results[23];} ?>"/></td>
            <td><input type="text" size="5" name="poids_SKJ" value="<?php if($results[32] == 'SKJ'){ echo $results[24];} ?>"/></td>
            <td><input type="text" size="5" name="taille_BET" value="<?php if($results[32] == 'BET'){ echo $results[23];} ?>"/></td>
            <td><input type="text" size="5" name="poids_BET" value="<?php if($results[32] == 'BET'){ echo $results[24];} ?>"/></td>
            <td><input type="text" size="5" name="taille_ALB" value="<?php if($results[32] == 'ALB'){ echo $results[23];} ?>"/></td>
            <td><input type="text" size="5" name="poids_ALB" value="<?php if($results[32] == 'ALB'){ echo $results[24];} ?>"/></td>
            <td>
                <select name="id_species_rej">
                <option value="none">Aucun</option>
                <?php 
                    $result = pg_query("SELECT DISTINCT id, FAO FROM fishery.species WHERE FAO is not NULL ORDER BY FAO");
                    while($row = pg_fetch_row($result)) {
                        if ($row[1] == $results[32] AND $results[21] == 't') {
                            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
                        } else {
                            print "<option value=\"$row[0]\">".$row[1]."</option>";
                        }
                    }
                 
            ?>  
            </select>
            </td>
            <td><input type="text" size="5" name="taille_rej" value="<?php if($results[21] == 't'){ echo $results[24];} ?>"/></td>
            <td><input type="text" size="5" name="poids_rej" value="<?php if($results[21] == 't'){ echo $results[24];} ?>"/></td>
        </tr>
    </table>
    <br/>
    <h3>Donnees environmental</h3>
    <table>
        <tr>
            <td><b>Temperature Eau</b></td>
            <td><b>Vitesse vent</b></td>
            <td><b>Direction vent</b></td>
            <td><b>Vitesse courant</b></td>
        </tr>
        <tr>
            <td><input type="text" size="10" name="water_temp" value="<?php echo $results[14]; ?>"/></td>
            <td><input type="text" size="10" name="wind_speed" value="<?php echo $results[15]; ?>"/></td>
            <td><input type="text" size="10" name="wind_dir" value="<?php echo $results[16]; ?>"/></td>
            <td><input type="text" size="10" name="cur_speed" value="<?php echo $results[17]; ?>"/></td>
        </tr>
    </table>
    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

    <?php
    
}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM thon.maree WHERE id = '$id'";
    
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_thon_maree.php?source=$source&table=maree&action=show");
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

    $rejete = $_POST['rejete']; 
    $id_species = $_POST['id_species']; 
    $taille = $_POST['taille']; 
    $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES); 

    $poids[0] = $_POST['poids_YFT'];
    $poids[1] = $_POST['poids_SKJ'];
    $poids[2] = $_POST['poids_BET'];
    $poids[3] = $_POST['poids_ALB'];
    $poids[4] = $_POST['poids_rej'];
    
    $taille[0] = $_POST['taille_YFT'];
    $taille[1] = $_POST['taille_SKJ'];
    $taille[2] = $_POST['taille_BET'];
    $taille[3] = $_POST['taille_ALB'];
    $taille[4] = $_POST['taille_rej'];
    
    $result = pg_query("SELECT id FROM fishery.species WHERE FAO = 'YFT' OR FAO = 'SKJ' OR FAO = 'BET' OR FAO = 'ALB'");
    
    $id_species[0] = pg_fetch_result($result, 0, 0);
    $id_species[1] = pg_fetch_result($result, 1, 0);
    $id_species[2] = pg_fetch_result($result, 2, 0);
    $id_species[3] = pg_fetch_result($result, 3, 0);
    $id_species[4] = $_POST['id_species_rej'];
    
    
    for ($i = 0; $i < 5; $i++) {
        print $id_species[$i] . "<br/>";
        print $taille[$i] . "<br/>";
        print $poids[$i] . "<br/>";
        
        if ($id_species[$i] != '' and $taille[$i] != '' and $poids[$i] != '') {
            
            if ($i == 4) {$rejete = TRUE;}
            
            if ($_POST['new_old']) {
                #navire, country, port_d, port_a, date_d, date_a, ndays, date_c, heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, comment, st_x(location), st_y(location)
                $query = "INSERT INTO thon.maree "
                        . "(username, datetime, navire, country, port_d, port_a, date_d, date_a, ndays, date_c, heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, id_species, taille, poids, comment, location) "
                        . "VALUES ('$username', now(), '$navire', '$country', '$port_d', '$port_a', '$date_d', '$date_a', '$ndays', '$date_c', '$heure_c', '$lance', '$eez', '$water_temp', '$wind_speed', '$wind_dir', '$cur_speed', '$success', '$banclibre', '$balise_id', '$rejete', '$id_species', '$taille', '$poids', '$comment', ST_GeomFromText($point,4326))";

            } else {
                $query = "UPDATE thon.maree SET "
                    . "username = '$username', datetime = now(), "
                    . "navire = '".$_POST['navire']."', country = '".$_POST['country']."', port_d = '".$_POST['port_d']."', "
                    . "port_a = '".$_POST['port_a']."', date_d = '".$_POST['date_d']."', date_a = '".$_POST['date_a']."', "
                    . "ndays = '".$_POST['ndays']."', date_c = '".$_POST['date_c']."', heure_c = '".$_POST['heure_c']."', "
                    . "lance = '".$_POST['lance']."', eez = '".$_POST['eez']."', water_temp = '".$_POST['water_temp']."', "
                    . "wind_speed = '".$_POST['wind_speed']."', wind_dir = '".$_POST['wind_dir']."', "
                    . "cur_speed = '".$_POST['cur_speed']."', success = '".$_POST['success']."', banclibre = '".$_POST['banclibre']."', "
                    . "balise_id = '".$_POST['balise_id']."', rejete = '".$_POST['rejete']."', id_species = '".$id_species[$i]."', "
                    . "taille = '".$taille[$i]."', poids = '".$poids[$i]."', comment = '".$_POST['comment']."', "
                    . " location = ST_GeomFromText($point,4326)"
                    . " WHERE id = '{".$_POST['id']."}'";
            }

            $query = str_replace('\'\'', 'NULL', $query);
            
    //        print $query;
            
            if(!pg_query($query)) {
        //        print $query;
                msg_queryerror();
            }

            }
    
    }
    
        #print $query;
        #header("Location: ".$_SESSION['http_host']."/industrial/view_thon_maree.php?source=$source&table=maree&action=show");
    
}

foot();
