<?php
require("../top_foot.inc.php");
#require("../fpdf/fpdf.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'trawlers';

top();

if(right_read($_SESSION['username'],5)) {

$_SESSION['filter']['f_s_maree'] = $_POST['f_s_maree'];

if ($_GET['f_s_maree'] != "") {$_SESSION['filter']['f_s_maree'] = $_GET['f_s_maree'];}

?>

<h2>Rapport Maree Peche Chalutier</h2>
<form method="post" action="<?php echo $self;?>?source=trawlers&table=captures_requin&action=show" enctype="multipart/form-data">
    <fieldset>
        <legend><b>Filtre Maree</b></legend>
    <table id="no-border">
    <tr>
    <td>
    <select name="f_s_maree">
        <option value="f_s_maree" selected="selected">Select</option>
        <?php
        $result = pg_query("SELECT DISTINCT maree FROM trawlers.route ORDER BY maree DESC");
        while($row = pg_fetch_row($result)) {
            if ("'".$row[0]."'" == $_SESSION['filter']['f_s_maree']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">$row[0]</option>";
            } else {
                print "<option value=\"'$row[0]'\">$row[0]</option>";
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

    <?php

$maree = $_SESSION['filter']['f_s_maree'];

if ($maree != "") {

    $query = "SELECT route.id, navire, maree, t_fleet.fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment,  st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f)  "
    . " FROM trawlers.route "
    . "LEFT JOIN trawlers.t_fleet ON trawlers.t_fleet.id = trawlers.route.t_fleet "
    . "LEFT JOIN vms.navire ON trawlers.route.id_navire = vms.navire.id "
    . "WHERE maree=$maree ";

    $q_route = pg_fetch_all(pg_query($query));

    $query = "SELECT p_day.id, datetime, username, maree, date_d, lance_d, lance_f, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi "
    . " FROM trawlers.p_day "
    . "LEFT JOIN fishery.species ON trawlers.p_day.id_species = fishery.species.id "
    . "WHERE maree=$maree ORDER BY date_d";

    //print $query;

    $q_p_day = pg_fetch_all(pg_query($query));
    $navire = $q_route[0]['navire'];
    $date_d = $q_p_day[0]['date_d'];
    $date_f = $q_p_day[count($q_p_day)-1]['date_d'];
    ?>

    <h2>Generale</h2>
    <ul>
        <li>Navire: <b><?php print $navire; ?></b></li>
        <li>Flottille: <b><?php print $q_route[0]['fleet']; ?></b></li>
        <li>Debut maree: <b><?php print $date_d; ?></b></li>
        <li>Fin maree: <b><?php print $date_f; ?></b></li>
        <li>Duree maree: <b><?php
        $datetime_d = date_create($date_d);
        $datetime_f = date_create($date_f);
        $interval = date_diff($datetime_d, $datetime_f);
        print $interval->format('%a days');
        ?>
        </b></li>
    </ul>

    <h2>Production</h2>
    <?php

    foreach($q_p_day as $row) {
        foreach(array_slice($row,10) as $value) {
            $poids += $value;
        }

    }

    foreach($q_route as $row) {
            $poids_rej += $row['reject'];
    }

?>

<ul>
<li><b><?php print round($poids/1000,1); ?></b> tonnes de Production, soit <?php print round($poids/($poids_rej+$poids)*100,1); ?>% de la capture totale;</li>
<li><b><?php print round($poids_rej/1000,2); ?></b> tonnes de rejets soit <?php print round($poids_rej/($poids_rej+$poids)*100,1); ?>% de la capture totale.</li>
<li><b><?php print $q_p_day[count($q_p_day)-1]['lance_f']; ?></b> lanc&eacute;s total </li>
<li><b><?php print count($q_route); ?></b> lanc&eacute;s &eacute;chantillonn&eacute;s</li>
</ul>


<!-- List of Lancee per DAY -->
<!-- for each day, show: lance, captures, p_lance, p_day, ft, cm -->

<?php
$query = "SELECT MIN(date), MAX(date) FROM trawlers.route WHERE maree = $maree ";

$q_dates = pg_fetch_all(pg_query($query))[0];

$begin = new DateTime( $q_dates['min'] );
$end = new DateTime( $q_dates['max'] );
$end = $end->modify( '+1 day' );

$interval = new DateInterval('P1D');
$daterange = new DatePeriod($begin, $interval ,$end);

foreach($daterange as $date) {
    $period[] = $date->format("Y-m-d");
}

print "<h2>List Lances par Jour</h2>";

print "<table>";
print "<tr><td><b>Date</b></td><td><b># lance</b></td><td><b>Poids captures ech</b></td><td><b>Composition Captures</b></td><td><b>Prod Quotidienne</b></td></tr>";

foreach($period as $day) {
 print "<tr>";
 print "<td>$day</td>";

 $query = "SELECT SUM(poids), COUNT(DISTINCT captures.lance) "
 . "FROM trawlers.captures "
 . "LEFT JOIN trawlers.route ON trawlers.route.id = trawlers.captures.id_route "
 . "WHERE captures.maree = $maree AND route.date = '$day'";

 //print $query;
 $q_lance = pg_fetch_all(pg_query($query))[0];

 //print_r($q_captures);

 // FT_CRE, FT_POI and CM_CRE, CM_POI

 // FT_CRE
 $query = "SELECT COUNT(DISTINCT ft_cre.lance) FROM trawlers.ft_cre "
 . "LEFT JOIN trawlers.route ON trawlers.route.id = trawlers.ft_cre.id_route "
 . "WHERE route.maree = $maree AND route.date = '$day'";

 $q_ft_cre = pg_fetch_all(pg_query($query))[0];

 // FT_POI
 $query = "SELECT COUNT(DISTINCT ft_poi.lance) FROM trawlers.ft_poi "
 . "LEFT JOIN trawlers.route ON trawlers.route.id = trawlers.ft_poi.id_route "
 . "WHERE route.maree = $maree AND route.date = '$day'";

 $q_ft_poi = pg_fetch_all(pg_query($query))[0];

 // CM_CRE
 $query = "SELECT COUNT(DISTINCT cm_cre.lance) FROM trawlers.cm_cre "
 . "LEFT JOIN trawlers.route ON trawlers.route.id = trawlers.cm_cre.id_route "
 . "WHERE route.maree = $maree AND route.date = '$day'";

 $q_cm_cre = pg_fetch_all(pg_query($query))[0];

 // CM_POI
 $query = "SELECT COUNT(DISTINCT cm_poi.lance) FROM trawlers.cm_poi "
 . "LEFT JOIN trawlers.route ON trawlers.route.id = trawlers.cm_poi.id_route "
 . "WHERE route.maree = $maree AND route.date = '$day'";

 $q_cm_poi = pg_fetch_all(pg_query($query))[0];

 print "<td>Captures: ".$q_lance['count']." <br/>FT CRE: ".$q_ft_cre['count'].", FT POI: ".$q_ft_poi['count']."<br/>";
 print "CM CRE: ".$q_cm_cre['count'].", CM POI: ".$q_cm_poi['count']."</td>";
 print "<td>".$q_lance['sum']." </td>";

 $query = "SELECT SUM(poids), fishery.species.FAO, fishery.species.francaise "
     . "FROM trawlers.captures "
     . "LEFT JOIN trawlers.route ON trawlers.route.id = trawlers.captures.id_route "
     . "LEFT JOIN fishery.species ON trawlers.captures.id_species = fishery.species.id "
     . "WHERE route.maree = $maree AND route.date = '$day' GROUP BY fishery.species.id ORDER BY sum DESC";
 //print $query;
 $q_captures = pg_fetch_all(pg_query($query));

  //print "</tr>";

 ?>

  <script type="text/javascript">
      google.charts.load("current", {packages:['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ["Espece", "Poids"],
          <?php
          foreach($q_captures as $catch){
            print "[\"".formatSpeciesAnalysis($catch['fao'],$catch['francaise'])."\",".$catch['sum']."],";
          }
          ?>
        ]);

        var view = new google.visualization.DataView(data);

        var options = {
          bar: {groupWidth: "95%"},
          legend: { position: "none" },
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values_<?php print $day;?>"));
        chart.draw(view, options);
    }
    </script>

  <td>
  <div id="columnchart_values_<?php print $day;?>" style="width: 400px; height: 150px;"></div>
</td>

<?php

$query = "SELECT date_d, "
        . "COALESCE(SUM(c0_cre),0) + COALESCE(SUM(c1_cre),0) + COALESCE(SUM(c2_cre),0) + COALESCE(SUM(c3_cre),0) + COALESCE(SUM(c4_cre),0) + COALESCE(SUM(c5_cre),0) + COALESCE(SUM(c6_cre),0) + COALESCE(SUM(c7_cre),0) + COALESCE(SUM(c8_cre),0) + COALESCE(SUM(c9_cre),0) as cre, "
        . "COALESCE(SUM(c0_poi),0) + COALESCE(SUM(c1_poi),0) + COALESCE(SUM(c2_poi),0) + COALESCE(SUM(c3_poi),0) + COALESCE(SUM(c4_poi),0) + COALESCE(SUM(c5_poi),0) + COALESCE(SUM(c6_poi),0) as poi "
        . "FROM trawlers.p_day "
        . "WHERE maree = $maree AND date_d = '$day' GROUP BY date_d";

//print $query;
$q_day = pg_fetch_all(pg_query($query))[0];

//print_r($q_captures);

print "<td>CRE: ".$q_day['cre']."kg, POI: ".$q_day['poi']."kg</td>";

print "</tr>";

}

print "</table>";

?>


<!-- CAPTURES PAR LANCE -->

<?php /*

<h2>Captures par Lance</h2>

<?php

$query = "SELECT DISTINCT lance "
    . "FROM trawlers.captures "
    . "WHERE maree = $maree ORDER BY lance ASC";

//print $query;

$q_lance = pg_fetch_all(pg_query($query));
//print_r($q_capture);

print "<table>";
print "<tr><td>Lance</td><td>Composition</td></tr>";

foreach($q_lance as $lance) {
  $query = "SELECT SUM(poids), fishery.species.FAO, fishery.species.francaise "
      . "FROM trawlers.captures "
      . "LEFT JOIN fishery.species ON trawlers.captures.id_species = fishery.species.id "
      . "WHERE maree = $maree AND lance = ".$lance['lance']." GROUP BY fishery.species.id ORDER BY sum DESC";

  $q_catch = pg_fetch_all(pg_query($query));

  print "<tr><td>".$lance['lance']."</td><td>";
?>
<script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Espece", "Poids"],
        <?php
        foreach($q_catch as $catch){
          print "[\"".formatSpeciesAnalysis($catch['fao'],$catch['francaise'])."\",".$catch['sum']."],";
        }
        ?>
      ]);

      var view = new google.visualization.DataView(data);

      var options = {
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values_<?php print $lance['lance'];?>"));
      chart.draw(view, options);
  }
  </script>

<div id="columnchart_values_<?php print $lance['lance'];?>" style="width: 400px; height: 150px;"></div>
</td></tr>


<?php
}

print "</table>";

*/
?>

<!-- COMPOSITION CAPTURES -->

<h2>Composition Capture Totale</h2>

<?php
$query = "SELECT SUM(poids), fishery.species.family "
        . "FROM trawlers.captures "
        . "LEFT JOIN fishery.species ON trawlers.captures.id_species = fishery.species.id "
        . "WHERE maree = $maree GROUP BY fishery.species.family ORDER BY sum DESC";

//print $query;

$q_capture = pg_fetch_all(pg_query($query));
//print_r($q_capture);
?>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Famille', 'Poids [kg]'],
          <?php
          foreach($q_capture as $capture){
            print "['".$capture['family']."',".$capture['sum']."],";
          }
          ?>
        ]);

        var options = {
          title: 'Composition des Captures en kg'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_captures'));

        chart.draw(data, options);
      }
    </script>

<div id="piechart_captures" style="width: 900px; height: 500px;"></div>

<!-- EFFORT DE PECHE -->


<h2>Effort de Peche</h2>

<?php

$query = "SELECT DISTINCT ON (ROUND(cast(st_x(location) as numeric),2), ROUND(cast(st_x(location) as numeric),2)) st_x(location), st_y(location), date_p FROM vms.positions "
        . "LEFT JOIN vms.navire ON vms.navire.id = vms.positions.id_navire "
        . "WHERE vms.navire.navire = '$navire' "
        . "AND vms.positions.date_p < '$date_f' "
        . "AND vms.positions.date_p > '$date_d' ";

//print $query;

$q_vms = pg_fetch_all(pg_query($query));
//print_r($q_capture);

$query = "SELECT st_x(location_d)*0.5 + st_x(location_f)*0.5 as st_x, st_y(location_d)*0.5 + st_y(location_f)*0.5 as st_y, date, h_d, h_f FROM trawlers.route "
        . "WHERE maree = $maree ";

//print $query;

$q_route = pg_fetch_all(pg_query($query));

?>

<script>
    google.charts.load('current', {
    'packages': ['map'],
    // Note: you will need to get a mapsApiKey for your project.
    // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
    'mapsApiKey': 'AIzaSyBI5MQWC4N5SgUXs989_7MTDkQghaiGUuA'

    });
    google.charts.setOnLoadCallback(drawMap);

    function drawMap() {
      var data = google.visualization.arrayToDataTable([
        ['Lat', 'Long', 'Date', 'Type'],
        <?php
        foreach($q_vms as $vms){
          print "[".$vms['st_y'].",".$vms['st_x'].", '".$vms['date_p']."', 'VMS'],";
        }

        foreach($q_route as $route){
          print "[".$route['st_y'].",".$route['st_x'].", '".$route['date']."', 'route'],";
        }
        ?>
      ]);


    var options = {
      mapType: 'terrain',
      showTooltip: true,
      showInfoWindow: true,
      icons: {
        VMS: {
          normal: 'https://storage.googleapis.com/support-kms-prod/SNP_2752125_en_v0',
          selected: 'https://storage.googleapis.com/support-kms-prod/SNP_2752125_en_v0'
        },
        route: {
          normal: 'https://storage.googleapis.com/support-kms-prod/SNP_2752068_en_v0',
          selected: 'https://storage.googleapis.com/support-kms-prod/SNP_2752068_en_v0'
        },

    }
    };

    // Red circle -https://storage.googleapis.com/support-kms-prod/SNP_2752125_en_v0
    // Blue circle - https://storage.googleapis.com/support-kms-prod/SNP_2752068_en_v0
    // Pink circle - https://storage.googleapis.com/support-kms-prod/SNP_2752264_en_v0
    // Yellow circle - https://storage.googleapis.com/support-kms-prod/SNP_2752063_en_v0
    // Green circle - https://storage.googleapis.com/support-kms-prod/SNP_2752129_en_v0


    var map = new google.visualization.Map(document.getElementById('chart_div'));

    map.draw(data, options);
  };
  </script>
  <div id="chart_div"></div>

<h2>Espece Sensible Capture</h2>
<?php

# MAMMAL

    $query = "SELECT n_ind, t_capture, t_relache FROM trawlers.captures_mammal "
    . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_mammal.id_route "
    . "WHERE route_accidentelle.maree=".$maree." "
    . "AND route_accidentelle.t_co = 0";

    //print $query;

    $q_mammal = pg_fetch_all(pg_query($query));

    //print_r($q_mammal);

    foreach($q_mammal as $row) {
        switch ($row['t_capture']) {
            case "0":
                $Vc_M += $row['n_ind'];
                $Tc_M += $row['n_ind'];
                break;
            case "1":
                $Cc_M += $row['n_ind'];
                $Tc_M += $row['n_ind'];
                break;
            case "2":
                $Mc_M += $row['n_ind'];
                $Tc_M += $row['n_ind'];
                break;
            case "3":
                $Fc_M += $row['n_ind'];
                $Tc_M += $row['n_ind'];
                break;
            default:
                $NAc_M += $row['n_ind'];
                $Tc_M += $row['n_ind'];
        }

        switch ($row['t_relache']) {
            case "0":
                $Vr_M += $row['n_ind'];
                $Tr_M += $row['n_ind'];
                break;
            case "1":
                $Cr_M += $row['n_ind'];
                $Tr_M += $row['n_ind'];
                break;
            case "2":
                $Mr_M += $row['n_ind'];
                $Tr_M += $row['n_ind'];
                break;
            case "3":
                $Fr_M += $row['n_ind'];
                $Tr_M += $row['n_ind'];
                break;
            default:
                $NAr_M += $row['n_ind'];
                $Tr_M += $row['n_ind'];
        }

    }

# TORTUE

    $query = "SELECT n_ind, t_capture, t_relache FROM trawlers.captures_tortue "
    . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_tortue.id_route "
    . "WHERE route_accidentelle.maree=".$maree." "
    . "AND route_accidentelle.t_co = 0";

    //print $query;

    $q_tortue = pg_fetch_all(pg_query($query));

    //print_r($q_mammal);

    foreach($q_tortue as $row) {
        switch ($row['t_capture']) {
            case "0":
                $Vc_T += $row['n_ind'];
                $Tc_T += $row['n_ind'];
                break;
            case "1":
                $Cc_T += $row['n_ind'];
                $Tc_T += $row['n_ind'];
                break;
            case "2":
                $Mc_T += $row['n_ind'];
                $Tc_T += $row['n_ind'];
                break;
            case "3":
                $Fc_T += $row['n_ind'];
                $Tc_T += $row['n_ind'];
                break;
            default:
                $NAc_T += $row['n_ind'];
                $Tc_T += $row['n_ind'];
        }

        switch ($row['t_relache']) {
            case "0":
                $Vr_T += $row['n_ind'];
                $Tr_T += $row['n_ind'];
                break;
            case "1":
                $Cr_T += $row['n_ind'];
                $Tr_T += $row['n_ind'];
                break;
            case "2":
                $Mr_T += $row['n_ind'];
                $Tr_T += $row['n_ind'];
                break;
            case "3":
                $Fr_T += $row['n_ind'];
                $Tr_T += $row['n_ind'];
                break;
            default:
                $NAr_T += $row['n_ind'];
                $Tr_T += $row['n_ind'];
        }

    }


# REQUIN
# CARCHARHINIDAE
# SPHYRNIDAE
# TRIAKIDAE

# RAI
# DASYATIDAE
# MOBULIDAE
# RHINOBATIDAE

    $query = "SELECT n_ind, t_capture, t_relache, family FROM trawlers.captures_requin "
    . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_requin.id_route "
    . "LEFT JOIN fishery.species ON trawlers.captures_requin.id_species = species.id "
    . "WHERE route_accidentelle.maree=$maree "
    . "AND route_accidentelle.t_co = 0 ";

    //print $query;

    $q_requin = pg_fetch_all(pg_query($query));

    //print_r($q_mammal);

    foreach($q_requin as $row) {

        switch ($row['family']) {
            case "CARCHARHINIDAE":
                switch ($row['t_capture']) {
                    case "0":
                        $Vc_RC += $row['n_ind'];
                        $Tc_RC += $row['n_ind'];
                        break;
                    case "1":
                        $Cc_RC += $row['n_ind'];
                        $Tc_RC += $row['n_ind'];
                        break;
                    case "2":
                        $Mc_RC += $row['n_ind'];
                        $Tc_RC += $row['n_ind'];
                        break;
                    case "3":
                        $Fc_RC += $row['n_ind'];
                        $Tc_RC += $row['n_ind'];
                        break;
                    default:
                        $NAc_RC += $row['n_ind'];
                        $Tc_RC += $row['n_ind'];
                }

                switch ($row['t_relache']) {
                    case "0":
                        $Vr_RC += $row['n_ind'];
                        $Tr_RC += $row['n_ind'];
                        break;
                    case "1":
                        $Cr_RC += $row['n_ind'];
                        $Tr_RC += $row['n_ind'];
                        break;
                    case "2":
                        $Mr_RC += $row['n_ind'];
                        $Tr_RC += $row['n_ind'];
                        break;
                    case "3":
                        $Fr_RC += $row['n_ind'];
                        $Tr_RC += $row['n_ind'];
                        break;
                    default:
                        $NAr_RC += $row['n_ind'];
                        $Tr_RC += $row['n_ind'];
                }
                break;

            case "SPHYRNIDAE":
                switch ($row['t_capture']) {
                    case "0":
                        $Vc_RS += $row['n_ind'];
                        $Tc_RS += $row['n_ind'];
                        break;
                    case "1":
                        $Cc_RS += $row['n_ind'];
                        $Tc_RS += $row['n_ind'];
                        break;
                    case "2":
                        $Mc_RS += $row['n_ind'];
                        $Tc_RS += $row['n_ind'];
                        break;
                    case "3":
                        $Fc_RS += $row['n_ind'];
                        $Tc_RS += $row['n_ind'];
                        break;
                    default:
                        $NAc_RS += $row['n_ind'];
                        $Tc_RS += $row['n_ind'];
                }

                switch ($row['t_relache']) {
                    case "0":
                        $Vr_RS += $row['n_ind'];
                        $Tr_RS += $row['n_ind'];
                        break;
                    case "1":
                        $Cr_RS += $row['n_ind'];
                        $Tr_RS += $row['n_ind'];
                        break;
                    case "2":
                        $Mr_RS += $row['n_ind'];
                        $Tr_RS += $row['n_ind'];
                        break;
                    case "3":
                        $Fr_RS += $row['n_ind'];
                        $Tr_RS += $row['n_ind'];
                        break;
                    default:
                        $NAr_RS += $row['n_ind'];
                        $Tr_RS += $row['n_ind'];
                }
                break;

            case "TRIAKIDAE":
                switch ($row['t_capture']) {
                    case "0":
                        $Vc_RT += $row['n_ind'];
                        $Tc_RT += $row['n_ind'];
                        break;
                    case "1":
                        $Cc_RT += $row['n_ind'];
                        $Tc_RT += $row['n_ind'];
                        break;
                    case "2":
                        $Mc_RT += $row['n_ind'];
                        $Tc_RT += $row['n_ind'];
                        break;
                    case "3":
                        $Fc_RT += $row['n_ind'];
                        $Tc_RT += $row['n_ind'];
                        break;
                    default:
                        $NAc_RT += $row['n_ind'];
                        $Tc_RT += $row['n_ind'];
                }

                switch ($row['t_relache']) {
                    case "0":
                        $Vr_RT += $row['n_ind'];
                        $Tr_RT += $row['n_ind'];
                        break;
                    case "1":
                        $Cr_RT += $row['n_ind'];
                        $Tr_RT += $row['n_ind'];
                        break;
                    case "2":
                        $Mr_RT += $row['n_ind'];
                        $Tr_RT += $row['n_ind'];
                        break;
                    case "3":
                        $Fr_RT += $row['n_ind'];
                        $Tr_RT += $row['n_ind'];
                        break;
                    default:
                        $NAr_RT += $row['n_ind'];
                        $Tr_RT += $row['n_ind'];
                }
                break;

            case "DASYATIDAE":
                switch ($row['t_capture']) {
                    case "0":
                        $Vc_RD += $row['n_ind'];
                        $Tc_RD += $row['n_ind'];
                        break;
                    case "1":
                        $Cc_RD += $row['n_ind'];
                        $Tc_RD += $row['n_ind'];
                        break;
                    case "2":
                        $Mc_RD += $row['n_ind'];
                        $Tc_RD += $row['n_ind'];
                        break;
                    case "3":
                        $Fc_RD += $row['n_ind'];
                        $Tc_RD += $row['n_ind'];
                        break;
                    default:
                        $NAc_RD += $row['n_ind'];
                        $Tc_RD += $row['n_ind'];
                }

                switch ($row['t_relache']) {
                    case "0":
                        $Vr_RD += $row['n_ind'];
                        $Tr_RD += $row['n_ind'];
                        break;
                    case "1":
                        $Cr_RD += $row['n_ind'];
                        $Tr_RD += $row['n_ind'];
                        break;
                    case "2":
                        $Mr_RD += $row['n_ind'];
                        $Tr_RD += $row['n_ind'];
                        break;
                    case "3":
                        $Fr_RD += $row['n_ind'];
                        $Tr_RD += $row['n_ind'];
                        break;
                    default:
                        $NAr_RD += $row['n_ind'];
                        $Tr_RD += $row['n_ind'];
                }
                break;

            case "MOBULIDAE":
                switch ($row['t_capture']) {
                    case "0":
                        $Vc_RM += $row['n_ind'];
                        $Tc_RM += $row['n_ind'];
                        break;
                    case "1":
                        $Cc_RM += $row['n_ind'];
                        $Tc_RM += $row['n_ind'];
                        break;
                    case "2":
                        $Mc_RM += $row['n_ind'];
                        $Tc_RM += $row['n_ind'];
                        break;
                    case "3":
                        $Fc_RM += $row['n_ind'];
                        $Tc_RM += $row['n_ind'];
                        break;
                    default:
                        $NAc_RM += $row['n_ind'];
                        $Tc_RM += $row['n_ind'];
                }

                switch ($row['t_relache']) {
                    case "0":
                        $Vr_RM += $row['n_ind'];
                        $Tr_RM += $row['n_ind'];
                        break;
                    case "1":
                        $Cr_RM += $row['n_ind'];
                        $Tr_RM += $row['n_ind'];
                        break;
                    case "2":
                        $Mr_RM += $row['n_ind'];
                        $Tr_RM += $row['n_ind'];
                        break;
                    case "3":
                        $Fr_RM += $row['n_ind'];
                        $Tr_RM += $row['n_ind'];
                        break;
                    default:
                        $NAr_RM += $row['n_ind'];
                        $Tr_RM += $row['n_ind'];
                }
                break;

            case "RHINOBATIDAE":
                switch ($row['t_capture']) {
                    case "0":
                        $Vc_RR += $row['n_ind'];
                        $Tc_RR += $row['n_ind'];
                        break;
                    case "1":
                        $Cc_RR += $row['n_ind'];
                        $Tc_RR += $row['n_ind'];
                        break;
                    case "2":
                        $Mc_RR += $row['n_ind'];
                        $Tc_RR += $row['n_ind'];
                        break;
                    case "3":
                        $Fc_RR += $row['n_ind'];
                        $Tc_RR += $row['n_ind'];
                        break;
                    default:
                        $NAc_RR += $row['n_ind'];
                        $Tc_RR += $row['n_ind'];
                }

                switch ($row['t_relache']) {
                    case "0":
                        $Vr_RR += $row['n_ind'];
                        $Tr_RR += $row['n_ind'];
                        break;
                    case "1":
                        $Cr_RR += $row['n_ind'];
                        $Tr_RR += $row['n_ind'];
                        break;
                    case "2":
                        $Mr_RR += $row['n_ind'];
                        $Tr_RR += $row['n_ind'];
                        break;
                    case "3":
                        $Fr_RR += $row['n_ind'];
                        $Tr_RR += $row['n_ind'];
                        break;
                    default:
                        $NAr_RR += $row['n_ind'];
                        $Tr_RR += $row['n_ind'];
                }

                break;

        }



    }


?>

<table>
    <tr><td></td><td></td><td colspan="4">Peche</td><td colspan="5">Rejete</td></tr>
    <tr>
        <td></td><td></td><td>V</td><td>C</td><td>M</td><td>NA</td><td>V</td><td>C</td><td>M</td><td>F</td><td>NA</td>
    </tr>
    <tr>
        <td>Dauphin</td><td></td><td><?php print $Vc_M;?></td><td><?php print $Cc_M;?></td><td><?php print $Mc_M;?></td><td><?php print $NAc_M;?></td><td><?php print $Vr_M;?></td><td><?php print $Cr_M;?></td><td><?php print $Mr_M;?></td><td><?php print $Fr_M;?></td><td><?php print $NAr_M;?></td>
    </tr>
    <tr>
        <td>Tortue</td><td></td><td><?php print $Vc_T;?></td><td><?php print $Cc_T;?></td><td><?php print $Mc_T;?></td><td><?php print $NAc_T;?></td><td><?php print $Vr_T;?></td><td><?php print $Cr_T;?></td><td><?php print $Mr_T;?></td><td><?php print $Fr_T;?></td><td><?php print $NAr_T;?></td>
    </tr>

    <tr><td rowspan="3">Requin</td><td>Carcharhinidae</td><td><?php print $Vc_RC;?></td><td><?php print $Cc_RC;?></td><td><?php print $Mc_RC;?></td><td><?php print $NAc_RC;?></td><td><?php print $Vr_RC;?></td><td><?php print $Cr_RC;?></td><td><?php print $Mr_RC;?></td><td><?php print $Fr_RC;?></td><td><?php print $NAr_RC;?></td>
    <tr><td>Sphyrnidae</td><td><?php print $Vc_RS;?></td><td><?php print $Cc_RS;?></td><td><?php print $Mc_RS;?></td><td><?php print $NAc_RS;?></td><td><?php print $Vr_RS;?></td><td><?php print $Cr_RS;?></td><td><?php print $Mr_RS;?></td><td><?php print $Fr_RS;?></td><td><?php print $NAr_RS;?></td>
    <tr><td>Triakidae</td><td><?php print $Vc_RT;?></td><td><?php print $Cc_RT;?></td><td><?php print $Mc_RT;?></td><td><?php print $NAc_RT;?></td><td><?php print $Vr_RT;?></td><td><?php print $Cr_RT;?></td><td><?php print $Mr_RT;?></td><td><?php print $Fr_RT;?></td><td><?php print $NAr_RT;?></td>
    <tr><td rowspan="3">Raie</td><td>Dasyatidae</td><td><?php print $Vc_RD;?></td><td><?php print $Cc_RD;?></td><td><?php print $Mc_RD;?></td><td><?php print $NAc_RD;?></td><td><?php print $Vr_RD;?></td><td><?php print $Cr_RD;?></td><td><?php print $Mr_RD;?></td><td><?php print $Fr_RD;?></td><td><?php print $NAr_RD;?></td>
    <tr><td>Mobulidae</td><td><?php print $Vc_RM;?></td><td><?php print $Cc_RM;?></td><td><?php print $Mc_RM;?></td><td><?php print $NAc_RM;?></td><td><?php print $Vr_RM;?></td><td><?php print $Cr_RM;?></td><td><?php print $Mr_RM;?></td><td><?php print $Fr_RM;?></td><td><?php print $NAr_RM;?></td>
    <tr><td>Rhinobatidae</td><td><?php print $Vc_RR;?></td><td><?php print $Cc_RR;?></td><td><?php print $Mc_RR;?></td><td><?php print $NAc_RR;?></td><td><?php print $Vr_RR;?></td><td><?php print $Cr_RR;?></td><td><?php print $Mr_RR;?></td><td><?php print $Fr_RR;?></td><td><?php print $NAr_RR;?></td>
</table>

<h2>Cart des especes sensible capture et observe</h2>

<?php

# Mammal

$query = "SELECT DISTINCT st_x(location), st_y(location), trawlers.route_accidentelle.date FROM trawlers.captures_mammal "
        . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_mammal.id_route "
        . "WHERE trawlers.route_accidentelle.maree = $maree AND location IS NOT NULL";

$q_sensible = pg_fetch_all(pg_query($query));

foreach($q_sensible as $row) {
  $x[] = $row['st_x'];
  $y[] = $row['st_y'];
  $datec[] = $row['date'];
  $species[] = 'mammal';
}

# Tortue

$query = "SELECT DISTINCT st_x(location), st_y(location), trawlers.route_accidentelle.date FROM trawlers.captures_tortue "
        . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_tortue.id_route "
        . "WHERE trawlers.route_accidentelle.maree = $maree AND location IS NOT NULL";

$q_sensible = pg_fetch_all(pg_query($query));

foreach($q_sensible as $row) {
  $x[] = $row['st_x'];
  $y[] = $row['st_y'];
  $datec[] = $row['date'];
  $species[] = 'turtle';
}


# Requin

$query = "SELECT DISTINCT st_x(location), st_y(location), trawlers.route_accidentelle.date FROM trawlers.captures_requin "
        . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_requin.id_route "
        . "WHERE trawlers.route_accidentelle.maree = $maree AND location IS NOT NULL";

//print $query;

$q_sensible = pg_fetch_all(pg_query($query));

foreach($q_sensible as $row) {
   $x[] = $row['st_x'];
   $y[] = $row['st_y'];
   $datec[] = $row['date'];
   $species[] = 'shark';
}

?>

<script>

    google.charts.load('current', {
    'packages': ['map'],
    // Note: you will need to get a mapsApiKey for your project.
    // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
    'mapsApiKey': 'AIzaSyBI5MQWC4N5SgUXs989_7MTDkQghaiGUuA'
    });

    google.charts.setOnLoadCallback(drawMap);

    function drawMap() {
      var data = new google.visualization.DataTable();

            data.addColumn('number', 'Lat');
            data.addColumn('number', 'Long');
            data.addColumn('string', 'Date');
            data.addColumn('string', 'Species');

            data.addRows([
              <?php
              for($i = 0; $i < count($x)-1; $i++){
                print "[".$y[$i].",".$x[$i].", '".$datec[$i]."', '".$species[$i]."'], ";
              }
              print "[".$y[count($x)-1].",".$x[count($x)-1].", '".$datec[count($x)-1]."', '".$species[count($x)-1]."'] ";
              ?>
            ]);


    var url = 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/';

    var options = {
      mapType: 'terrain',
      showTooltip: true,
      showInfoWindow: true,

      icons: {
        shark: {
          normal: 'https://icons.iconarchive.com/icons/google/noto-emoji-animals-nature/48/22296-shark-icon.png',
          selected: 'https://icons.iconarchive.com/icons/google/noto-emoji-animals-nature/48/22296-shark-icon.png'
        },
        mammal: {
          normal: 'https://icons.iconarchive.com/icons/google/noto-emoji-animals-nature/48/22292-dolphin-icon.png',
          selected: 'https://icons.iconarchive.com/icons/google/noto-emoji-animals-nature/48/22292-dolphin-icon.png'
        },
        turtle: {
          normal: 'https://icons.iconarchive.com/icons/google/noto-emoji-animals-nature/48/22283-turtle-icon.png',
          selected: 'https://icons.iconarchive.com/icons/google/noto-emoji-animals-nature/48/22283-turtle-icon.png'
        }
    }
    };

    var map = new google.visualization.Map(document.getElementById('map_sensitive'));

    map.draw(data, options);
  };
  </script>

    <div id="map_sensitive"></div>

    <?php
    $controllo = 1;

    }
    }
foot();
