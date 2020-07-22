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

    $query_t = "SELECT navire, date, ST_Y(location), ST_X(location) FROM trawlers.p_lance";
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
    <form method="post" action="<?php echo $self;?>?source=trawlers&table=p_lance&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Mar&eacute;e</b></td><td><b>Esp&eacute;c&eacute;</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
    </td>
    <td>
    <select name="f_id_species">
        <option value="id_species" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.p_lance ON fishery.species.id = trawlers.p_lance.id_species  ORDER BY  fishery.species.family, fishery.species.genus, fishery.species.species");
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
    <td><b>Mar&eacute;e et Lanc&eacute;</b></td>
    <td><b>Esp&egrave;ce</b></td>
    <td><b>0</b></td>
    <td><b>1</b></td>
    <td><b>2</b></td>
    <td><b>3</b></td>
    <td><b>4</b></td>
    <td><b>5</b></td>
    <td><b>6</b></td>
    <td><b>7</b></td>
    <td><b>8</b></td>
    <td><b>CC</b></td>
    <td><b>G</b></td>
    <td><b>M</b></td>
    <td><b>P</b></td>
    <td><b>F</b></td>
    <td><b>FF</b></td>
    <td><b>Mix</b></td>
    <td><b>Grosse pi&egrave;ce</b></td>
    </tr>

    <?php

    // fetch data

    if ($_SESSION['filter']['f_s_maree'] != "" OR $_SESSION['filter']['f_id_species'] != "") {

        #id, datetime, username, id_route, id_species, maree, lance, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_s_maree'] != "") {
            $query = "SELECT count(p_lance.id) FROM trawlers.p_lance "
                . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT p_lance.id, datetime::date, username, id_route, maree, lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi, "
            . " coalesce(similarity(trawlers.p_lance.maree, '".$_SESSION['filter']['f_s_maree']."'),0) AS score"
            . " FROM trawlers.p_lance "
            . "LEFT JOIN fishery.species ON trawlers.p_lance.id_species = fishery.species.id "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
            . "ORDER BY score DESC, datetime DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT count(p_lance.id) FROM trawlers.p_lance "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." ";
            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT p_lance.id, datetime::date, username, id_route, maree, lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi  "
            . " FROM trawlers.p_lance "
            . "LEFT JOIN fishery.species ON trawlers.p_lance.id_species = fishery.species.id "
            . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(p_lance.id) FROM trawlers.p_lance";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT p_lance.id, datetime::date, username, id_route, maree, lance, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi "
        . " FROM trawlers.p_lance "
        . "LEFT JOIN fishery.species ON trawlers.p_lance.id_species = fishery.species.id "
        . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
    }

    $r_query = pg_query($query);

    //print $query;

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_trawlers_p_lance.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_trawlers_p_lance.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";
        print "<td>$results[1]<br/>$results[2]</td><td><a href=\"./view_route.php?table=route&id=$results[3]\">$results[4]<br/>$results[5]</td><td>".formatSpecies($results[7],$results[8],$results[9],$results[10])."</td>"
        . "<td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td>"
        . "<td>$results[15]</td><td>$results[16]</td><td>$results[17]</td><td>$results[18]</td>"
        . "<td>$results[19]</td><td>$results[20]</td><td>$results[21]</td><td>$results[22]</td>"
        . "<td>$results[23]</td><td>$results[24]</td><td>$results[25]</td><td>$results[26]</td>"
        . "<td>$results[27]</td>"
        . "</tr>";
    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_trawlers_p_lance.php?source=trawlers&table=p_lance&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree'].'&f_id_species='.$_SESSION['filter']['f_id_species']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT *, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM trawlers.p_lance "
        . " LEFT JOIN fishery.species ON fishery.species.id = trawlers.p_lance.id_species "
        . " WHERE p_lance.id = '$id'";

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    # print $q_id;

    # id, datetime, username, id_route, id_species, maree, lance, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Mar&eacute;e</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','lance','maree','lance','trawlers.route')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM trawlers.route ORDER BY maree DESC");
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
    <b>Lanc&eacute;</b>
    <br/>
    <select id="lance" name="lance">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT lance FROM trawlers.route  WHERE maree = '$results[5]' ORDER BY lance");
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
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN trawlers.p_lance ON fishery.species.id = trawlers.p_lance.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[24]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Poids pour categorie taille crevette</b> (kg)
    <br/>
    <table border="1">
        <tr align="center"><td>0</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td><td>8</td><td>CC</td></tr>
        <tr align="center">
            <?php
            for ($i = 0; $i < 10; $i++) {
                print '<td><input type="text" size="3" name="c'.$i.'_cre" value="'.$results[7+$i].'"></td>';
            }
            ?>
        </tr>
    </table>
    <br/>
    <b>Poids pour categorie taille poisson</b> (kg)
    <br/>
    <table border="1">
        <tr  align="center"><td>G</td><td>M</td><td>P</td><td>F</td><td>FF</td><td>Mix</td><td>Grosse pi&egrave;ce</td></tr>
        <tr  align="center">
            <?php
            for ($i = 0; $i < 7; $i++) {
                print '<td><input type="text" size="3" name="c'.$i.'_poi" value="'.$results[7+10+$i].'"></td>';
            }
            ?>
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
    $query = "DELETE FROM trawlers.p_lance WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_p_lance.php?source=$source&table=p_lance&action=show");
    }
    $controllo = 1;

}


if ($_POST['submit'] == "Enregistrer") {
    # id, datetime, username, id_route, id_species, maree, lance, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi

    $maree = $_POST['maree'];
    $lance = $_POST['lance'];
    $id_species = $_POST['id_species'];

    $c0_cre = $_POST['c0_cre'];
    $c1_cre = $_POST['c1_cre'];
    $c2_cre = $_POST['c2_cre'];
    $c3_cre = $_POST['c3_cre'];
    $c4_cre = $_POST['c4_cre'];
    $c5_cre = $_POST['c5_cre'];
    $c6_cre = $_POST['c6_cre'];
    $c7_cre = $_POST['c7_cre'];
    $c8_cre = $_POST['c8_cre'];
    $c9_cre = $_POST['c9_cre'];
    $c0_poi = $_POST['c0_poi'];
    $c1_poi = $_POST['c1_poi'];
    $c2_poi = $_POST['c2_poi'];
    $c3_poi = $_POST['c3_poi'];
    $c4_poi = $_POST['c4_poi'];
    $c5_poi = $_POST['c5_poi'];
    $c6_poi = $_POST['c6_poi'];

    $q_id = "SELECT id FROM trawlers.route WHERE maree = '$maree' AND lance = '$lance'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    if ($_POST['new_old']) {
        $query = "INSERT INTO trawlers.p_lance "
            . "(datetime, username, id_route, maree, lance, id_species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi) "
            . "VALUES (now(), '$username', '$id_route', '$maree', '$lance', '$id_species', '$c0_cre', '$c1_cre', '$c2_cre', '$c3_cre', '$c4_cre', '$c5_cre', '$c6_cre', '$c7_cre', '$c8_cre', '$c9_cre', '$c0_poi', '$c1_poi', '$c2_poi', '$c3_poi', '$c4_poi', '$c5_poi', '$c6_poi')";


    } else {
        $query = "UPDATE trawlers.p_lance SET "
            . "username = '$username', datetime = now(), "
            . "id_route = '".$id_route."', maree = '".$maree."', lance = '".$lance."', "
            . "lance = '".$lance."', id_species = '".$id_species."', "
            . "c0_cre = '".$c0_cre."', c1_cre = '".$c1_cre."', c2_cre = '".$c2_cre."', c3_cre = '".$c3_cre."', c4_cre = '".$c4_cre."', c5_cre = '".$c5_cre."', c6_cre = '".$c6_cre."', c7_cre = '".$c7_cre."', c8_cre = '".$c8_cre."', c9_cre = '".$c9_cre."', c0_poi = '".$c0_poi."', c1_poi = '".$c1_poi."', c2_poi = '".$c2_poi."', c3_poi = '".$c3_poi."', c4_poi = '".$c4_poi."', c5_poi = '".$c5_poi."', c6_poi = '".$c6_poi."' "
            . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_trawlers_p_lance.php?source=$source&table=p_lance&action=show");
    }
}

foot();
