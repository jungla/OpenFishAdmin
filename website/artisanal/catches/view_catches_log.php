<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'catches';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_id_species'] = $_POST['f_id_species'];
$_SESSION['filter']['f_t_site'] = $_POST['f_t_site'];
$_SESSION['filter']['f_immatriculation'] = str_replace('\'','',$_POST['f_immatriculation']);
$_SESSION['filter']['f_pir_name'] = str_replace('\'','',$_POST['f_pir_name']);

if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}
if ($_GET['f_t_site'] != "") {$_SESSION['filter']['f_t_site'] = $_GET['f_t_site'];}
if ($_GET['f_immatriculation'] != "") {$_SESSION['filter']['f_immatriculation'] = $_GET['f_immatriculation'];}
if ($_GET['f_pir_name'] != "") {$_SESSION['filter']['f_pir_name'] = $_GET['f_pir_name'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $start = $_GET['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>

    <form method="post" action="<?php echo $self;?>?source=artisanal&table=captures&action=show" enctype="multipart/form-data">
    <fieldset>
    <table id="no-border"><tr><td><b>Debarcadere</b></td><td><b>Immatriculation</b></td><td><b>Nom pirogue</b></td><td><b>Espece</b></td></tr>
    <tr>
    <td>
      <select name="f_t_site" class="chosen-select" style="width:100%">
          <option value="log_maree.t_site" selected="selected">Tous</option>
          <?php
          $result = pg_query("SELECT DISTINCT t_site_obb.id, site FROM artisanal.t_site_obb JOIN artisanal_catches.log_maree ON artisanal_catches.log_maree.t_site = artisanal.t_site_obb.id ORDER BY site");
          while($row = pg_fetch_row($result)) {
              if ($row[0] == $_SESSION['filter']['f_t_site']) {
                  print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
              } else {
                  print "<option value=\"$row[0]\">".$row[1]."</option>";
              }
          }
      ?>
      </select>
    </td>
    <td>
    <input type="text" size="20" name="f_immatriculation" value="<?php echo $_SESSION['filter']['f_immatriculation']?>"/>
    </td>
    <td>
    <input type="text" size="20" name="f_pir_name" value="<?php echo $_SESSION['filter']['f_pir_name']?>"/>
    </td>
    <td>
      <select name="f_id_species" class="chosen-select" >
          <option value="log_catch.id_species" selected="selected">Tous</option>
          <?php
          $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN artisanal_catches.log_catch ON fishery.species.id = artisanal_catches.log_catch.id_species  ORDER BY  fishery.species.family, fishery.species.genus, fishery.species.species");
          while($row = pg_fetch_row($result)) {
              if ("'".$row[0]."'" == $_SESSION['filter']['f_id_species']) {
                  print "<option value=\"'$row[0]'\" selected=\"selected\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
              } else {
                  print "<option value=\"'$row[0]'\">".formatSpecies($row[1],$row[2],$row[3],$row[4])."</option>";
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
    <td><b>Date de d&eacute;part et retour</b></td>
    <td><b>Enqu&ecirc;teur</b></td>
    <td><b>D&eacute;barcad&egrave;re</b></td>
    <td><b>Pirogue</b></td>
    <td><b>Engin p&ecirc;che</b></td>
    <td><b>Composition capture</b></td>
    <td><b>Commentaires</b></td>
    </tr>

    <?php

    if ($_SESSION['filter']['f_immatriculation'] != "" OR $_SESSION['filter']['f_pir_name'] != "" OR $_SESSION['filter']['f_id_species'] != "" OR $_SESSION['filter']['f_t_site'] != "") {

        $_SESSION['start'] = 0;

        $query = "SELECT count(DISTINCT log_maree.id) FROM artisanal_catches.log_maree "
        . "LEFT JOIN artisanal_catches.log_catch ON artisanal_catches.log_maree.id = artisanal_catches.log_catch.id_maree "
        . "WHERE (log_maree.t_site = ".$_SESSION['filter']['f_t_site']." OR log_maree.t_site IS NULL) "
        . "AND (log_catch.id_species = ".$_SESSION['filter']['f_id_species']." OR log_catch.id_species IS NULL) ";

        $pnum = pg_fetch_row(pg_query($query))[0];

        if ($_SESSION['filter']['f_immatriculation'] != "" OR $_SESSION['filter']['f_pir_name'] != "") {

            $query = "SELECT DISTINCT log_maree.id, log_maree.datetime::date, log_maree.username, date_d::date, date_r::date, obs_name, t_site_obb.site, id_pirogue, log_maree.immatriculation, log_maree.pir_name, log_maree.t_gear, log_maree.comments, "
            . " coalesce(similarity(artisanal_catches.log_maree.immatriculation, '".$_SESSION['filter']['f_immatriculation']."'),0) + "
            . " coalesce(similarity(artisanal.pirogue.immatriculation, '".$_SESSION['filter']['f_immatriculation']."'),0) + "
            . " coalesce(similarity(artisanal_catches.log_maree.pir_name, '".$_SESSION['filter']['f_pir_name']."'),0) + "
            . " coalesce(similarity(artisanal.pirogue.name, '".$_SESSION['filter']['f_pir_name']."'),0) AS score "
            . "FROM artisanal_catches.log_maree "
            . "LEFT JOIN artisanal_catches.log_catch ON artisanal_catches.log_maree.id = artisanal_catches.log_catch.id_maree "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.log_maree.id_pirogue "
            . "LEFT JOIN artisanal.t_site_obb ON artisanal.t_site_obb.id = artisanal_catches.log_maree.t_site "
            . "LEFT JOIN artisanal.t_gear ON artisanal_catches.log_maree.t_gear = artisanal.t_gear.id "
            . "WHERE (log_maree.t_site = ".$_SESSION['filter']['f_t_site']." OR log_maree.t_site IS NULL) "
            . "AND (log_catch.id_species = ".$_SESSION['filter']['f_id_species']." OR log_catch.id_species IS NULL) "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";

        } else {

            $query = "SELECT DISTINCT log_maree.id, log_maree.datetime::date, log_maree.username, date_d::date, date_r::date, obs_name, t_site_obb.site, id_pirogue, log_maree.immatriculation, log_maree.pir_name, log_maree.t_gear, log_maree.comments "
            . "FROM artisanal_catches.log_maree "
            . "LEFT JOIN artisanal_catches.log_catch ON artisanal_catches.log_maree.id = artisanal_catches.log_catch.id_maree "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.log_maree.id_pirogue "
            . "LEFT JOIN artisanal.t_site_obb ON artisanal.t_site_obb.id = artisanal_catches.log_maree.t_site "
            . "LEFT JOIN artisanal.t_gear ON artisanal_catches.log_maree.t_gear = artisanal.t_gear.id "
            . "WHERE (log_maree.t_site = ".$_SESSION['filter']['f_t_site']." OR log_maree.t_site IS NULL) "
            . "AND (log_catch.id_species = ".$_SESSION['filter']['f_id_species']." OR log_catch.id_species IS NULL) "
            . "ORDER BY log_maree.datetime::date DESC OFFSET $start LIMIT $step";
            }
    } else {
        $query = "SELECT count(log_maree.id) FROM artisanal_catches.log_maree";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT DISTINCT log_maree.id, log_maree.datetime::date, log_maree.username, date_d::date, date_r::date, obs_name, t_site_obb.site, id_pirogue, log_maree.immatriculation, log_maree.pir_name, log_maree.t_gear, log_maree.comments "
        . "FROM artisanal_catches.log_maree "
        . "LEFT JOIN artisanal_catches.log_catch ON artisanal_catches.log_maree.id = artisanal_catches.log_catch.id_maree "
        . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.log_maree.id_pirogue "
        . "LEFT JOIN artisanal.t_site_obb ON artisanal.t_site_obb.id = artisanal_catches.log_maree.t_site "
        . "LEFT JOIN artisanal.t_gear ON artisanal_catches.log_maree.t_gear = artisanal.t_gear.id "
        . "ORDER BY log_maree.datetime::date DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\"><td>";
        //. "<a href=\"./view_owner.php?id=$results[0]\">Voir</a><br/>";
        if(right_write($_SESSION['username'],3,2)) {
            print "<a href=\"./view_catches_log.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_catches_log.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";

        print "<td nowrap>$results[1]<br/>$results[2]</td><td nowrap>D: $results[3]<br/>R: $results[4]</td><td nowrap>$results[5]</td><td>$results[6]</td>";

        if ($results[7] != '') {
          $query = "SELECT immatriculation, name FROM artisanal.pirogue WHERE id = '$results[7]'";
          $immatriculation = pg_fetch_row(pg_query($query));
          //print $query;
          print "<td nowrap><a href=../administration/view_pirogue.php?id=$results[7]>$immatriculation[0]<br/>$immatriculation[1]</a></td>";
        } else {
          print "<td>$results[8]<br/>$results[9]</td>";
        }

        if ($results[7] != '') {
          $query = "SELECT l1.license, l2.license, g1.gear, g2.gear, mesh_min, mesh_max, mesh_min_2, mesh_max_2, license.length, license.length_2, s1.site, s2.site, engine_brand, "
          . "engine_cv, receipt, payment, agasa, id_pirogue, pirogue.name, pirogue.immatriculation, t_coop.coop, license.active, license.comments, s2_2.site, t_strata.strata "
          . " FROM artisanal.license "
          . "LEFT JOIN artisanal.t_coop ON artisanal.t_coop.id = artisanal.license.t_coop "
          . "LEFT JOIN artisanal.t_strata ON artisanal.t_strata.id = artisanal.license.t_strata "
          . "LEFT JOIN artisanal.t_license l1 ON l1.id = artisanal.license.t_license "
          . "LEFT JOIN artisanal.t_license l2 ON l2.id = artisanal.license.t_license_2 "
          . "LEFT JOIN artisanal.t_gear g1 ON g1.id = artisanal.license.t_gear "
          . "LEFT JOIN artisanal.t_gear g2 ON g2.id = artisanal.license.t_gear_2 "
          . "LEFT JOIN artisanal.t_site s1 ON s1.id = artisanal.license.t_site "
          . "LEFT JOIN artisanal.t_site_obb s2 ON s2.id = artisanal.license.t_site_obb "
          . "LEFT JOIN artisanal.t_site_obb s2_2 ON s2.id = artisanal.license.t_site_obb_2 "
          . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.license.id_pirogue "
          . "WHERE id_pirogue = '$results[7]'";

          //print $query;
          $license = pg_fetch_row(pg_query($query));

          print "<td nowrap>$license[2]<br/>$license[3]</td>";

        } else {
          print "<td nowrap>$results[10]</td>";
        }

        print "<td>";

        $query = "SELECT SUM(wgt), fishery.species.francaise "
        . "FROM artisanal_catches.log_catch "
        . "LEFT JOIN fishery.species ON artisanal_catches.log_catch.id_species = fishery.species.id "
        . "WHERE id_maree = '$results[0]' GROUP BY fishery.species.francaise ORDER BY sum DESC";

        //print $query;

        $q_capture = pg_fetch_all(pg_query($query));
        //print_r($q_capture);
        ?>

        <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

          var data = google.visualization.arrayToDataTable([
            ['Espece', 'Poids [kg]']
            <?php
            foreach($q_capture as $capture){
              //print $capture[0];
              print ",['".str_replace("'","e ",$capture['francaise'])."',".$capture['sum']."]";
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

        print "</td>";

        print "<td nowrap>$results[11]</td>";




    }
    print "</tr>";

    print "</table>";

    pages($start,$step,$pnum,'./view_catches_log.php?source=artisanal&table=captures&action=show&f_immatriculation='.$_SESSION['filter']['f_immatriculation'].'&f_id_species='.$_SESSION['filter']['f_id_species'].'&f_t_site='.$_SESSION['filter']['f_t_site'].'&f_t_study='.$_SESSION['filter']['f_t_study']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT * FROM artisanal_catches.log_maree WHERE id = '$id' ";
    //print $q_id;
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    $split = explode('. ',$results[9]);
    $t_immatriculation = $split[0];
    $reg_num = explode('/',$split[1])[0];
    $reg_year = explode('/',$split[1])[1];

?>

<form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">
<b>Date de d&eacute;part</b>
<br/>
<input type="date" size="30" name="date_d" value="<?php echo $results[4];?>" />
<br/>
<br/>
<b>Date de retour</b>
<br/>
<input type="date" size="30" name="date_r" value="<?php echo $results[5];?>" />
<br/>
<br/>
<b>Nom du collecteur</b>
<br/>
<input type="text" size="20" name="obs_name" value="<?php echo $results[3];?>" />
<br/>
<br/>
<b>D&eacute;barcad&egrave;re</b>
<br/>
<select name="t_site" class="chosen-select" >
<?php
$result = pg_query("SELECT * FROM artisanal.t_site_obb ORDER BY site");
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
<b>D&eacute;tails Pirogue</b>
<br/>
<select name="id_pirogue"  class="chosen-select" onchange="java_script_:show(this.options[this.selectedIndex].value,'pir_id')">
  <option value=''>PAS DANS LA LISTE</option>
  <?php
  $result = pg_query("SELECT id, name, immatriculation FROM artisanal.pirogue ORDER BY name");
  while($row = pg_fetch_row($result)) {
    if ($row[0] == $results[8]) {
      print "<option value=\"$row[0]\" selected=\"selected\">".$row[2]." - ".$row[1]."</option>";
    } else {
      print "<option value=\"$row[0]\">".$row[2]." - ".$row[1]."</option>";
    }
  }
  ?>
</select>
<br/>
<br/>
<div class="pir_id" <?php if($results[8] != "") {print 'style="display:none"';} ?>>
  <b>Num&eacute;ro d'immatriculation</b> [format: L. 123/18]
  <br/>
  <select name="t_immatriculation">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_immatriculation ORDER BY immatriculation");
    while($row = pg_fetch_row($result)) {
      if ($row[1] == $t_immatriculation) {
        print "<option value=\"$row[1]\" selected>".$row[1]."</option>";
      } else {
        print "<option value=\"$row[1]\">".$row[1]."</option>";
      }
    }
    ?>
  </select>
  <input type="text" size="5" name="reg_num" value="<?php echo $reg_num;?>" /> /
  <input type="text" size="5" name="reg_year" value="<?php echo $reg_year;?>" />
  <br/>
  <br/>
  <b>Nom pirogue</b>
  <br/>
  <input type="text" size="15" name="pir_name" value="<?php echo $results[10];?>" />
  <br/>
  <br/>
</div>
<b>Engin de peche</b>
<br/>
<select name="t_gear">
<?php
$result = pg_query("SELECT * FROM artisanal.t_gear ORDER BY t_gear");
while($row = pg_fetch_row($result)) {
    if ($row[0] == $results[7]) {
        print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
    } else {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
}
?>
</select>
<br/>
<br/>
<?php
$query = "SELECT * FROM artisanal_catches.log_catch WHERE id_maree = '$results[0]'";
$result_c = pg_query($query);
//print $query;
while($row_c = pg_fetch_row($result_c)) {
  //print $row;
  ?>
  <div class="<?php print "capture_$row_c[0]"; ?>">
  <fieldset class="border">
  <legend>D&eacute;tails Capture</legend>
  <b>Espece</b>
  <br/>
  <select id="species" name="id_species[]">
  <?php
  $result = pg_query("SELECT DISTINCT id, family, genus, species, francaise FROM fishery.species WHERE category LIKE '%artisanal%' ORDER BY francaise, species");
  while($row = pg_fetch_row($result)) {
      if ($row[0] == $row_c[4]) {
          print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesCommon($row[4],$row[1],$row[2],$row[3])."</option>";
      } else {
          print "<option value=\"$row[0]\">".formatSpeciesCommon($row[4],$row[1],$row[2],$row[3])."</option>";
      }
  }
  ?>
  </select>
  <br/>
  <br/>
  <b>Poids par esp&egrave;ce</b> (kg)<br/>
  <input type="text" size="5" name="wgt[]" value="<?php echo $row_c[5];?>" />
  <br/>
  <br/>
  <b>Commentaires</b><br/>
  <textarea cols="20" rows="4" name="comments_catch[]"><?php echo $row_c[6];?></textarea>
  <br/>
  <button type="button" onclick="removeDivCaptureID('<?php echo $row_c[0]; ?>')">Supprimer Capture</button>
  </fieldset>
  <br/>
  </div>
  <?php
}
?>

<script type='text/javascript'>
var DivCapture = `<div class="capture">
<fieldset class="border">
<legend>D&eacute;tails Capture</legend>
<b>Espece</b>
<br/>
<select id="species" name="id_species[]">
<?php
$result = pg_query("SELECT DISTINCT id, family, genus, species, francaise FROM fishery.species WHERE category LIKE '%artisanal%' ORDER BY francaise, species");
while($row = pg_fetch_row($result)) {
    if ($row[0] == $results[25]) {
        print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesCommon($row[4],$row[1],$row[2],$row[3])."</option>";
    } else {
        print "<option value=\"$row[0]\">".formatSpeciesCommon($row[4],$row[1],$row[2],$row[3])."</option>";
    }
}
?>
</select>
<br/>
<br/>
<b>Poids par esp&egrave;ce</b> (kg)<br/>
<input type="text" size="5" name="wgt[]" />
<br/>
<br/>
<b>Commentaires</b><br/>
<textarea cols="20" rows="4" name="comments_catch[]"></textarea>
</fieldset>
<br/>
</div>
`
function appendDivCapture() {
  $( ".container" ).append(DivCapture)
}

function removeDivCaptureID(id) {
  $( ".capture_"+id).remove()
}

function removeDivCaptureLast() {
  $( ".capture").last().remove()
}

</script>
<div class="container">
</div>
<b>Commentaires</b><br/>
<textarea cols="20" rows="4" name="comments"><?php echo $results[11]; ?></textarea>
<br/>
<button type="button" onclick="appendDivCapture()">Ajouter Capture</button>
<button type="button" onclick="removeDivCaptureLast()">Supprimer Capture</button>
<br/>


<br/>
<input type="hidden" value="<?php echo $results[0];?>" name="id"/>
<input type="submit" value="Enregistrer" name="submit"/>
</form>
    <br/>
    <br/>

    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];

    $query = "DELETE FROM artisanal_catches.log_maree WHERE id = '$id'";
    if(!pg_query($query)) {
        msg_queryerror();
    }

    $query = "DELETE FROM artisanal_catches.log_catch WHERE id_maree = '$id'";
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    }

    header("Location: ".$_SESSION['http_host']."/artisanal/catches/view_catches_log.php?source=$source&table=$table&action=show");

    $controllo = 1;

}

if ($_POST['submit'] == "Enregistrer") {

            $id = $_POST['id'];
            $username = $_SESSION['username'];
            $date_d = $_POST['date_d'];
            $date_r = $_POST['date_r'];
            $obs_name = htmlspecialchars($_POST['obs_name'],ENT_QUOTES);
            $t_site = $_POST['t_site'];
            $id_pirogue = $_POST['id_pirogue'];

            if ($id_pirogue == '') {
              $t_immatriculation = $_POST['t_immatriculation'];
              $reg_num = htmlspecialchars($_POST['reg_num'],ENT_QUOTES);
              $reg_year = htmlspecialchars($_POST['reg_year'],ENT_QUOTES);
              $immatriculation = $t_immatriculation.". ".$reg_num."/".$reg_year;
              $pir_name =  htmlspecialchars($_POST['pir_name'],ENT_QUOTES);
            } else {
              $immatriculation = '';
              $pir_name = '';
            }

            $t_gear = $_POST['t_gear'];
            $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);

            $query = "UPDATE artisanal_catches.log_maree SET "
            . "username = '$username', date_d = '$date_d', date_r = '$date_r', obs_name = '$obs_name', "
            . "t_site = '$t_site', id_pirogue = '$id_pirogue', immatriculation = '$immatriculation', "
            . "pir_name = '$pir_name', t_gear = '$t_gear', comments = '$comments' "
            . "WHERE id = '$id'";

            $query = str_replace('\'-- \'', 'NULL', $query);
            $query = str_replace('\'\'', 'NULL', $query);

            $results = pg_query($query);
            //print $query;

            $query = "DELETE FROM artisanal_catches.log_catch WHERE id_maree = '$id'";
            pg_query($query); //delete and replace previous capture records

            for($i = 0; $i < sizeof($_POST['id_species']); $i++) {
              $id_species = $_POST['id_species'][$i];
              $wgt = htmlspecialchars($_POST['wgt'][$i],ENT_QUOTES);
              $comments_catch = htmlspecialchars($_POST['comments_catch'][$i],ENT_QUOTES);

              $query = "INSERT INTO artisanal_catches.log_catch "
              . "(username, id_maree, id_species, wgt, comments) "
              . "VALUES ('$username', '$id', '$id_species', '$wgt', '$comments_catch');";

              $query = str_replace('\'-- \'', 'NULL', $query);
              $query = str_replace('\'\'', 'NULL', $query);
              print $query;

              if(!pg_query($query)) {
                echo "<p>".$query,"</p>";
                msg_queryerror();
                foot();
                die();
              }
            }

            header("Location: ".$_SESSION['http_host']."/artisanal/catches/view_catches_log.php?source=$source&table=$table&action=show");

}

foot();
