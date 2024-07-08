<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'poisson';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_id_navire'] = $_POST['f_id_navire'];
$_SESSION['filter']['f_s_year'] = $_POST['f_s_year'];

if ($_GET['f_id_navire'] != "") {$_SESSION['filter']['f_id_navire'] = $_GET['f_id_navire'];}
if ($_GET['f_s_year'] != "") {$_SESSION['filter']['f_s_year'] = $_GET['f_s_year'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=poisson&table=maree&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border">
    <tr>
      <td><b>Navire</b></td>
      <td><b>Annee</b></td>
      </tr>
    <tr>
    <td>
    <select name="f_id_navire">
    <option value="id_navire" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT maree.id_navire, navire FROM poisson.maree LEFT JOIN vms.navire ON vms.navire.id = poisson.maree.id_navire ORDER BY navire");
        while($row = pg_fetch_row($result)) {
        if ("'".$row[0]."'" == $_SESSION['filter']['f_id_navire']) {
                print "<option value=\"'$row[0]'\" selected=\"selected\">$row[1]</option>";
            } else {
                print "<option value=\"'$row[0]'\">$row[1]</option>";
            }
        }
    ?>
    </select>
    </td>
    <td>
    <select name="f_s_year">
        <option value="EXTRACT(year FROM maree.date_d)">Tous</option>
        <?php
        $result = pg_query("SELECT DISTINCT EXTRACT(year FROM maree.date_d) as year FROM poisson.maree WHERE maree.date_d IS NOT NULL UNION SELECT DISTINCT EXTRACT(year FROM maree.date_r) as year FROM poisson.maree WHERE maree.date_r IS NOT NULL ORDER BY year");
        while($row = pg_fetch_row($result)) {
            if ("'".$row[0]."'" == $_SESSION['filter']['f_s_year']) {
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

    <br/>

    <table id="small">

    <tr align="center"><td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Navire</b></td>
    <td><b>Date debut et fin maree</b></td>
    <td><b>Capitaine</b></td>
    <td><b>Numero Lance</b></td>
    <td><b>Port depart et arrive</b></td>
    <td><b>Captures</b></td>
    <td><b>Zone</b></td>
    <td><b>Rejets (kg)</b></td>
    </tr>

    <?php

    // fetch data

    if ($_SESSION['filter']['f_id_navire'] != "" OR $_SESSION['filter']['f_s_year'] != "") {

        # id_maree, date_c, heure_c, lance, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment ,

        $_SESSION['start'] = 0;

        $query = "SELECT count(maree.id) FROM poisson.maree "
        . "LEFT JOIN vms.navire ON vms.navire.id = poisson.maree.id_navire "
        . "WHERE maree.id_navire=".$_SESSION['filter']['f_id_navire']." "
        . "AND (EXTRACT(year FROM maree.date_d) =".$_SESSION['filter']['f_s_year']." OR EXTRACT(year FROM maree.date_r) = ".$_SESSION['filter']['f_s_year'].")";

        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT maree.id, maree.datetime::date, maree.username, id_navire, navire, date_d, date_r, captain, nlance, port_d, port_r, zone, rejets "
        . " FROM poisson.maree "
        . "LEFT JOIN vms.navire ON vms.navire.id = poisson.maree.id_navire "
        . "LEFT JOIN poisson.t_zone ON poisson.t_zone.id = poisson.maree.t_zone "
        . "WHERE maree.id_navire=".$_SESSION['filter']['f_id_navire']." "
        . "AND (EXTRACT(year FROM maree.date_d) =".$_SESSION['filter']['f_s_year']." OR EXTRACT(year FROM maree.date_r) = ".$_SESSION['filter']['f_s_year'].")"
        . "ORDER BY maree.date_d DESC OFFSET $start LIMIT $step";

    } else {
        $query = "SELECT count(maree.id) FROM poisson.maree";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT maree.id, maree.datetime::date, maree.username, id_navire, navire, date_d, date_r, captain, nlance, port_d, port_r, zone, rejets "
        . " FROM poisson.maree "
        . "LEFT JOIN vms.navire ON vms.navire.id = poisson.maree.id_navire "
        . "LEFT JOIN poisson.t_zone ON poisson.t_zone.id = poisson.maree.t_zone "
        . "ORDER BY maree.date_d DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {


        print "<tr align=\"center\">";

        print "<td>"
        . "<a href=\"./view_poisson_maree.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_poisson_maree.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
        . "</td>";

        print "<td>$results[1]<br/>$results[2]</td><td nowrap><a href=\"../view_navire.php?source=vms&id=$results[3]\">$results[4]</a></td>"
                . "<td nowrap>$results[5]<br/>$results[6]</td><td>$results[7]</td><td>$results[8]</td>"
                . "<td>$results[9]<br/>$results[10]</td><td>";

                $query = "SELECT SUM(poids), fishery.species.family "
                . "FROM poisson.capture "
                . "LEFT JOIN fishery.species ON poisson.capture.id_species = fishery.species.id "
                . "WHERE id_maree = '$results[0]' GROUP BY fishery.species.family ORDER BY sum DESC";

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

                  var chart = new google.visualization.PieChart(document.getElementById('piechart_captures_<?php print $results[0]; ?>'));

                  chart.draw(data, options);
                }
                </script>

                <div id="piechart_captures_<?php print $results[0]; ?>" style="width: 300px; height: 130px;"></div>

                <?php

        print "</td><td>$results[11]</td><td>$results[12]</td>";

    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_poisson_maree.php?source=poisson&table=maree&action=show&f_id_navire='.$_SESSION['filter']['f_id_navire'].'&f_s_year='.$_SESSION['filter']['f_s_year']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    // id, datetime, username, id_maree, date_c, heure_c, lance, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment,

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT * FROM poisson.maree WHERE maree.id = '$id'";

    //print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    ?>

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Navire</b>
    <br/>
    <select name="id_navire">
    <?php
        $result = pg_query("SELECT id, navire FROM vms.navire WHERE navire NOT LIKE 'M\_%' ORDER BY navire");
        while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[3]) {
                print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
            } else {
                print "<option value=\"$row[0]\">$row[1]</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Date debut maree</b>
    <br/>
    <input type="date" size="10" name="date_d" value="<?php echo $results[4]; ?>"/>
    <br/>
    <br/>
    <b>Date fin maree</b>
    <br/>
    <input type="date" size="10" name="date_r" value="<?php echo $results[5]; ?>"/>
    <br/>
    <br/>
    <b>Nom et prenom capitaine</b>
    <br/>
    <input size="50" name="captain" value="<?php echo $results[6]; ?>"/>
    <br/>
    <br/>
    <b>Nombre de lacee totale</b>
    <br/>
    <input size="4" name="nlance" value="<?php echo $results[7]; ?>"/>
    <br/>
    <br/>
    <b>Port depart</b>
    <br/>
    <input size="20" name="port_d" value="<?php echo $results[8]; ?>"/>
    <br/>
    <br/>
    <b>Port retour</b>
    <br/>
    <input size="20" name="port_r" value="<?php echo $results[9]; ?>"/>
    <br/>
    <br/>
    <b>Zone</b>
    <br/>
    <select name="t_zone">
    <option value="">Aucun</option>
    <?php
        $result = pg_query("SELECT id, zone FROM poisson.t_zone ORDER BY zone");
        while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[10]) {
                print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
            } else {
                print "<option value=\"$row[0]\">$row[1]</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Rejet totale [kg]</b>
    <br/>
    <input type="text" size="10" name="rejets" value="<?php echo $results[11];?>" />
    <br/>
    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/><br/>


    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM poisson.maree WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/industrial/poisson/view_poisson_maree.php?source=$source&table=maree&action=show");
    }
    $controllo = 1;
}

if ($_POST['submit'] == "Enregistrer") {

$id_navire = $_POST['id_navire'];
$date_d = $_POST['date_d'];
$date_r = $_POST['date_r'];
$captain = $_POST['captain'];
$nlance = $_POST['nlance'];
$port_d = $_POST['port_d'];
$port_r = $_POST['port_r'];
$t_zone = $_POST['t_zone'];
$rejets = $_POST['rejets'];

# id_navire, date_d, date_r, captain, nlance, port_d, port_r, t_zone, rejets

    if ($_POST['new_old']) {
        $query = "INSERT INTO poisson.maree "
                . "(username, datetime, id_navire, date_d, date_r, captain, nlance, port_d, port_r, t_zone, rejets) "
                . "VALUES ('$username', now(), '$id_navire', '$date_d', '$date_r', '$captain', '$nlance', '$port_d', '$port_r', '$t_zone', '$rejets')";

    } else {
        $query = "UPDATE poisson.maree SET "
            . "username = '$username', datetime = now(), "
            . "id_navire = '$id_navire', date_d = '$date_d', date_r = '$date_r', captain = '$captain', "
            . "nlance = '$nlance', port_d = '$port_d', port_r = '$port_r', t_zone = '$t_zone', rejets = '$rejets' "
            . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
        print $query;
        msg_queryerror();
    } else {
//        print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/poisson/view_poisson_maree.php?source=$source&table=maree&action=show");
    }


}

foot();
