<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'trawlers';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_id_species'] = $_POST['f_id_species'];
$_SESSION['filter']['f_s_maree'] = $_POST['f_s_maree'];

if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}
if ($_GET['f_s_maree'] != "") {$_SESSION['filter']['f_s_maree'] = $_GET['f_s_maree'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'map') {

    $query_t = "SELECT navire, date, ST_Y(location), ST_X(location) FROM trawlers.captures_mammal";
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
    <form method="post" action="<?php echo $self;?>?source=trawlers&table=captures_requin&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Maree</b></td><td><b>Esp&eacute;c&eacute;</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <select name="f_id_species" class="chosen-select" >
        <option value="id_species" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM trawlers.captures_mammal LEFT JOIN fishery.species ON captures_mammal.id_species = fishery.species.id ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ("'".$row[0]."'" == $_SESSION['filter']['f_id_species']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"'$row[0]'\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    </td>
    </tr>
    </table>
    <input type="submit" name="Filter" value="filter" />
    </fieldset>
    </form>

    <br/>

    <table id="small">
    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Maree, date & heure</b></td>
    <td><b>Espece</b></td>
    <td><b>Numbre individue et Sexe</b></td>
    <td><b>Taille</b></td>
    <td><b>Condition capture et relache</b></td>
    <td><b>Pr&eacute;l&egrave;vement code</b></td>
    <td><b>Camera/Photo ID</b></td>
    <td><b>Remarque</b></td>
    </tr>

    <?php

    // fetch data

    // id, datetime, username, id_route, maree, date, heure, id_species, n_ind, t_sex, taille, t_capture, t_relache, preleve, camera, photo, remarque ,

    if ($_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_id_species']) {

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(captures_mammal.id) FROM trawlers.captures_mammal "
                . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT captures_mammal.id, captures_mammal.datetime::date, captures_mammal.username, id_route, route_accidentelle.maree, route_accidentelle.date, route_accidentelle.time, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, n_ind, t_sex.sex, taille, t1.condition, t2.condition, preleve, camera, photo, remarque, "
            . " coalesce(similarity(trawlers.captures_mammal.maree, '".$_SESSION['filter']['f_s_maree']."'),0) AS score"
            . " FROM trawlers.captures_mammal "
            . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_mammal.id_route "
            . "LEFT JOIN fishery.species ON trawlers.captures_mammal.id_species = fishery.species.id "
            . "LEFT JOIN trawlers.t_sex ON trawlers.captures_mammal.t_sex = trawlers.t_sex.id "
            . "LEFT JOIN trawlers.t_condition t1 ON trawlers.captures_mammal.t_capture = t1.id "
            . "LEFT JOIN trawlers.t_condition t2 ON trawlers.captures_mammal.t_relache = t2.id "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT count(captures_mammal.id) FROM trawlers.captures_mammal "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";
            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT captures_mammal.id, captures_mammal.datetime::date, captures_mammal.username, id_route, route_accidentelle.maree, route_accidentelle.date, route_accidentelle.time, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, n_ind, t_sex.sex, taille, t1.condition, t2.condition, preleve, camera, photo, remarque "
            . " FROM trawlers.captures_mammal "
            . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_mammal.id_route "
            . "LEFT JOIN fishery.species ON trawlers.captures_mammal.id_species = fishery.species.id "
            . "LEFT JOIN trawlers.t_sex ON trawlers.captures_mammal.t_sex = trawlers.t_sex.id "
            . "LEFT JOIN trawlers.t_condition t1 ON trawlers.captures_mammal.t_capture = t1.id "
            . "LEFT JOIN trawlers.t_condition t2 ON trawlers.captures_mammal.t_relache = t2.id "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
            . "ORDER BY captures_mammal.datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(captures_mammal.id) FROM trawlers.captures_mammal";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT captures_mammal.id, captures_mammal.datetime::date, captures_mammal.username, id_route, route_accidentelle.maree, route_accidentelle.date, route_accidentelle.time, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, n_ind, t_sex.sex, taille, t1.condition, t2.condition, preleve, camera, photo, remarque "
        . " FROM trawlers.captures_mammal "
        . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_mammal.id_route "
            . "LEFT JOIN fishery.species ON trawlers.captures_mammal.id_species = fishery.species.id "
        . "LEFT JOIN trawlers.t_sex ON trawlers.captures_mammal.t_sex = trawlers.t_sex.id "
        . "LEFT JOIN trawlers.t_condition t1 ON trawlers.captures_mammal.t_capture = t1.id "
        . "LEFT JOIN trawlers.t_condition t2 ON trawlers.captures_mammal.t_relache = t2.id "
        . "ORDER BY captures_mammal.datetime DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_trawlers_captures_mammal.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_trawlers_captures_mammal.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td nowrap>$results[1]<br/>$results[2]</td><td nowrap><a href=\"./view_route.php?id=$results[3]&table=route_accidentelle\">$results[4]<br/>$results[5]<br/>$results[6]</td>"
        . "<td>".formatSpecies($results[8],$results[9],$results[10],$results[11])."</td><td>$results[12]<br/>$results[13]</td><td nowrap>$results[14]</td><td>$results[15] - $results[16]</td><td>$results[17]</td><td>$results[18]<br/>$results[19]</td><td>$results[20]</td></tr>";
    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_trawlers_captures_mammal.php?source=trawlers&table=captures_mammal&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_id_species='.$_SESSION['filter']['f_id_species']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT captures_mammal.id, captures_mammal.datetime, captures_mammal.username, id_route, maree, date, time, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, n_ind, t_sex, taille, t_capture, t_relache, preleve, camera, photo, remarque "
            . "FROM trawlers.captures_mammal "
            . "LEFT JOIN fishery.species ON trawlers.captures_mammal.id_species = fishery.species.id "
            . "WHERE captures_mammal.id = '$id'";

    #print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    //

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Maree</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','trawlers.route_accidentelle')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route_accidentelle ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[4]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Date</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','trawlers.route_accidentelle')">
    <?php
    $result = pg_query("SELECT DISTINCT date FROM trawlers.route_accidentelle  WHERE maree = '$results[4]' ORDER BY date");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[5]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Heure</b>
    <br/>
    <select id="time" name="time">
    <?php
    $result = pg_query("SELECT DISTINCT time FROM trawlers.route_accidentelle  WHERE maree = '$results[4]' AND date = '$results[5]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[6]) {
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
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM trawlers.captures_mammal LEFT JOIN fishery.species ON captures_mammal.id_species = fishery.species.id ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[7]) {
              print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
              print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Nombre d&apos;individus</b>
    <br/>
    <input type="text" size="5" name="n_ind" value="<?php echo $results[12]; ?>"/>
    <br/>
    <br/>
    <b>Sexe</b>
    <br/>
    <select name="t_sex">
    <option value="">Indetermine</option>
    <?php
    $result = pg_query("SELECT id, sex FROM trawlers.t_sex ORDER BY sex");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[13]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select><br/>
    <br/>
    <b>Taille</b> (cm)
    <br/>
    <input type="text" size="10" name="taille" value="<?php echo $results[14]; ?>"/>
    <br/>
    <br/>
    <b>Condition capture</b>
    <br/>
    <select name="t_capture">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, condition FROM trawlers.t_condition");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[15]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select><br/>
    <br/>
    <b>Condition relache</b>
    <br/>
    <select name="t_relache">
    <option value="">Aucun</option>
    <?php
    $result = pg_query("SELECT id, condition FROM trawlers.t_condition");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[16]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Pr&eacute;l&egrave;vement code</b>
    <br/>
    <input type="text" size="10" name="preleve" value="<?php echo $results[17]; ?>"/>
    <br/>
    <br/>
    <b>Camera ID</b>
    <br/>
    <input type="text" size="20" name="camera" value="<?php echo $results[18]; ?>"/>
    <br/>
    <br/>
    <b>Photo ID</b>
    <br/>
    <input type="text" size="20" name="photo" value="<?php echo $results[19]; ?>"/>
    <br/>
    <br/>
    <b>Remarque</b>
    <br/>
    <input type="text" size="30" name="remarque" value="<?php echo $results[20];?>" />
    <br/>
    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>

    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM trawlers.captures_mammal WHERE id = '$id'";

    if(!pg_query($query)) {
        print $query;
        msg_queryerror();
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_captures_mammal.php?source=$source&table=captures_requin&action=show");
    }
    $controllo = 1;

}


if ($_POST['submit'] == "Enregistrer") {

    # id, datetime, username, id_route, maree, lance, id_species, n_ind, t_sex, taille, t_capture, t_relache, preleve, camera, photo, remarque ,

    $maree = $_POST['maree'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $id_species = $_POST['id_species'];
    $n_ind = htmlspecialchars($_POST['n_ind'],ENT_QUOTES);
    $t_sex = $_POST['t_sex'];
    $taille = $_POST['taille'];
    $t_capture = $_POST['t_capture'];
    $t_relache = $_POST['t_relache'];
    $preleve = $_POST['preleve'];
    $camera = $_POST['camera'];
    $photo = $_POST['photo'];
    $remarque = htmlspecialchars($_POST['remarque'],ENT_QUOTES);

    $q_id = "SELECT id FROM trawlers.route_accidentelle WHERE maree = '$maree' AND date = '$date' AND time = '$time'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    #print $q_id;

    if ($_POST['new_old']) {
        $query = "INSERT INTO trawlers.captures_mammal "
            . "(datetime, username, id_route, maree, date, time, id_species, n_ind, t_sex, taille, t_capture, t_relache, preleve, camera, photo, remarque) "
            . "VALUES (now(), '$username', '$id_route', '$maree', '$date', '$time', '$id_species', '$n_ind', '$t_sex', '$taille', '$t_capture', '$t_relache', '$preleve', '$camera', '$photo', '$remarque')";

    } else {
        $query = "UPDATE trawlers.captures_mammal SET "
            . "username = '$username', datetime = now(), "
            . "id_route = '".$id_route."', maree = '".$maree."', "
            . "date = '".$date."', time = '".$time."', id_species = '".$id_species."', "
            . "n_ind = '".$n_ind."', t_sex = '".$t_sex."', taille = '".$taille."', t_capture = '".$t_capture."', "
            . "t_relache = '".$t_relache."', preleve = '".$preleve."', camera = '".$camera."', photo = '".$photo."', remarque = '".$remarque."'"
            . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_captures_mammal.php?source=$source&table=captures_requin&action=show");
    }
}

foot();
