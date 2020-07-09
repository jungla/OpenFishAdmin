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

$_SESSION['filter']['f_id_species'] = $_POST['f_id_species'];
$_SESSION['filter']['f_t_site'] = $_POST['f_t_site'];
$_SESSION['filter']['f_immatriculation'] = str_replace('\'','',$_POST['f_immatriculation']);
$_SESSION['filter']['f_t_study'] = $_POST['f_t_study'];

if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}
if ($_GET['f_t_site'] != "") {$_SESSION['filter']['f_t_site'] = $_GET['f_t_site'];}
if ($_GET['f_immatriculation'] != "") {$_SESSION['filter']['f_immatriculation'] = $_GET['f_immatriculation'];}
if ($_GET['f_t_study'] != "") {$_SESSION['filter']['f_t_study'] = $_GET['f_t_study'];}

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
    <table id="no-border"><tr><td><b>Debarcadere</b></td><td><b>Immatriculation</b></td><td><b>Espece</b></td><td><b>Type etude</b></td></tr>
    <tr>
    <td>
      <select name="f_t_site" class="chosen-select" style="width:100%">
          <option value="maree.t_site" selected="selected">Tous</option>
          <?php
          $result = pg_query("SELECT DISTINCT t_site.id, site FROM artisanal.t_site JOIN artisanal.maree ON artisanal.maree.t_site = artisanal.t_site.id ORDER BY site");
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
      <select name="f_id_species" class="chosen-select" >
          <option value="captures.id_species" selected="selected">Tous</option>
          <?php
          $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN artisanal.captures ON fishery.species.id = artisanal.captures.id_species  ORDER BY  fishery.species.family, fishery.species.genus, fishery.species.species");
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
    <td>
      <select name="f_t_study" class="chosen-select" style="width:100%">
          <option value="maree.t_study" selected="selected">Tous</option>
          <?php
          $result = pg_query("SELECT id, study FROM artisanal.t_study ORDER BY study");
          while($row = pg_fetch_row($result)) {
              if ($row[0] == $_SESSION['filter']['f_t_study']) {
                  print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
              } else {
                  print "<option value=\"$row[0]\">".$row[1]."</option>";
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
    <td><b>Type etude</b></td>
    <td><b>Date de d&eacute;part et retour</b></td>
    <td><b>Enqu&ecirc;teur</b></td>
    <td><b>D&eacute;barcad&egrave;re</b></td>
    <td><b>Pirogue</b></td>
    <td><b>Engin p&ecirc;che</b></td>
    <td><b>Taille min/max [cm]<br/>Longueur [m]</b></td>
    <td><b>Composition capture</b></td>
    <td><b>Trace GPS</b></td>
    </tr>


    <?php

    if ($_SESSION['filter']['f_immatriculation'] != "" OR $_SESSION['filter']['f_id_species'] != "" OR $_SESSION['filter']['f_t_study'] != ""OR $_SESSION['filter']['f_t_study'] != "") {

        $_SESSION['start'] = 0;

        if ($_SESSION['filter']['f_immatriculation'] != "") {
            $query = "SELECT count(DISTINCT maree.id) FROM artisanal.maree "
            . "LEFT JOIN artisanal.captures ON artisanal.maree.id = artisanal.captures.id_maree "
            . "WHERE maree.t_site = ".$_SESSION['filter']['f_t_site']." "
            . "AND captures.id_species = ".$_SESSION['filter']['f_id_species']." "
            . "AND maree.t_study = ".$_SESSION['filter']['f_t_study']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT DISTINCT maree.id, maree.datetime::date, maree.username, datetime_d::date, datetime_r::date, obs_name, t_site_obb.site, t_study.study, id_pirogue, maree.immatriculation, t_gear.gear, mesh_min, mesh_max, maree.length, gps_file "
            . " coalesce(similarity(artisanal.maree.immatriculation, '".$_SESSION['filter']['f_immatriculation']."'),0) + "
            . " coalesce(similarity(artisanal.pirogue.immatriculation, '".$_SESSION['filter']['f_immatriculation']."'),0) AS score, maree.datetime "
            . "FROM artisanal.maree "
            . "LEFT JOIN artisanal.captures ON artisanal.maree.id = artisanal.captures.id_maree "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.maree.id_pirogue "
            . "LEFT JOIN artisanal.t_site_obb ON artisanal.maree.t_site_obb = artisanal.t_site.id "
            . "LEFT JOIN artisanal.t_gear ON artisanal.maree.t_gear = artisanal.t_gear.id "
            . "LEFT JOIN artisanal.t_study ON artisanal.maree.t_study = artisanal.t_study.id "
            . "WHERE maree.t_site = ".$_SESSION['filter']['f_t_site']." "
            . "AND captures.id_species = ".$_SESSION['filter']['f_id_species']." "
            . "AND maree.t_study = ".$_SESSION['filter']['f_t_study']." "
            . "ORDER BY score, maree.datetime_d DESC OFFSET $start LIMIT $step";
        } else {
            $query = "SELECT count(DISTINCT maree.id) FROM artisanal.maree "
            . "LEFT JOIN artisanal.captures ON artisanal.maree.id = artisanal.captures.id_maree "
            . "WHERE maree.t_site = ".$_SESSION['filter']['f_t_site']." "
            . "AND captures.id_species = ".$_SESSION['filter']['f_id_species']." "
            . "AND maree.t_study = ".$_SESSION['filter']['f_t_study']." ";

            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT DISTINCT maree.id, maree.datetime::date, maree.username, datetime_d::date, datetime_r::date, obs_name, t_site_obb.site, t_study.study, id_pirogue, maree.immatriculation, t_gear.gear, mesh_min, mesh_max, maree.length, gps_file, maree.datetime "
            . "FROM artisanal.maree "
            . "LEFT JOIN artisanal.captures ON artisanal.maree.id = artisanal.captures.id_maree "
            . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.maree.id_pirogue "
            . "LEFT JOIN artisanal.t_site_obb ON artisanal.maree.t_site = artisanal.t_site_obb.id "
            . "LEFT JOIN artisanal.t_gear ON artisanal.maree.t_gear = artisanal.t_gear.id "
            . "LEFT JOIN artisanal.t_study ON artisanal.maree.t_study = artisanal.t_study.id "
            . "WHERE maree.t_site = ".$_SESSION['filter']['f_t_site']." "
            . "AND captures.id_species = ".$_SESSION['filter']['f_id_species']." "
            . "AND maree.t_study = ".$_SESSION['filter']['f_t_study']." "
            . "ORDER BY datetime_d DESC OFFSET $start LIMIT $step";
            }
    } else {
        $query = "SELECT count(maree.id) FROM artisanal.maree";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT DISTINCT maree.id, maree.datetime::date, maree.username, datetime_d::date, datetime_r::date, obs_name, t_site_obb.site, t_study.study, id_pirogue, maree.immatriculation, t_gear.gear, mesh_min, mesh_max, maree.length, gps_file, maree.datetime "
        . "FROM artisanal.maree "
        . "LEFT JOIN artisanal.captures ON artisanal.maree.id = artisanal.captures.id_maree "
        . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal.maree.id_pirogue "
        . "LEFT JOIN artisanal.t_site_obb ON artisanal.maree.t_site = artisanal.t_site_obb.id "
        . "LEFT JOIN artisanal.t_gear ON artisanal.maree.t_gear = artisanal.t_gear.id "
        . "LEFT JOIN artisanal.t_study ON artisanal.maree.t_study = artisanal.t_study.id "
        . "ORDER BY datetime_d::date DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\"><td>";
        //. "<a href=\"./view_owner.php?id=$results[0]\">Voir</a><br/>";
        if(right_write($_SESSION['username'],3,2)) {
            print "<a href=\"./view_records_capture.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_records_capture.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";

        print "<td nowrap>$results[1]<br/>$results[2]</td><td>$results[7]</td><td nowrap>$results[3]<br/>$results[4]</td><td nowrap>$results[5]</td><td>$results[6]</td>";

        if ($results[8] != '') {
          $query = "SELECT immatriculation FROM artisanal.pirogue WHERE id = '$results[8]'";
          $immatriculation = pg_fetch_row(pg_query($query));
          //print $query;
          print "<td nowrap><a href=./view_pirogue.php?id=$results[8]>$immatriculation[0]</a></td>";
        } else {
          print "<td>$results[9]</td>";
        }

        if ($results[8] != '') {
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
          . "WHERE id_pirogue = '$results[8]'";

          $license = pg_fetch_row(pg_query($query));

          print "<td nowrap>$license[2]<br/>$license[3]</td>";

          print "<td nowrap>";
          if ($license[4] != '' OR $license[5] != '') {
            print "$license[4] $license[5]cm </br>";
          }
          if ($license[8] != '') {
            print "$license[8]m";
          }
          if ($license[6] != '' OR $license[7] != '') {
            print "$license[6] $license[7]cm </br>";
          }
          if ($license[9] != '') {
            print "$license[9]m";
          }
          print "</td>";

        } else {
          print "<td nowrap>$results[10]</td><td>";
          if ($results[11] != '' and $results[12] != '') {
            print "$results[11]-$results[12]cm<br/>";
          } else if ($results[11] != '' or $results[12] != ''){
            print "$results[11]$results[12]cm<br/>";
          }
          if ($results[13] != '') {
            print "$results[13]m";
          }
        }

        print "</td><td><img src=\"./graph_records_captures.php?id=$results[0]\"></td><td></td>";

    }
    print "</tr>";

    print "</table>";

    pages($start,$step,$pnum,'./view_records_capture.php?source=artisanal&table=captures&action=show&f_immatriculation='.$_SESSION['filter']['f_immatriculation'].'&f_id_species='.$_SESSION['filter']['f_id_species'].'&f_t_site='.$_SESSION['filter']['f_t_site'].'&f_t_study='.$_SESSION['filter']['f_t_study']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT maree.id, maree.datetime::date, maree.username, datetime_d::date, datetime_r::date, obs_name, t_study, t_site, id_pirogue, maree.immatriculation, t_gear, mesh_min, mesh_max, maree.length, gps_file FROM artisanal.maree WHERE id = '$id' ";
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
<input type="date" size="30" name="datetime_d" value="<?php echo $results[3];?>" />
<br/>
<br/>
<b>Date de retour</b>
<br/>
<input type="date" size="30" name="datetime_r" value="<?php echo $results[4];?>" />
<br/>
<br/>
<b>Nom du collecteur</b>
<br/>
<input type="text" size="20" name="obs_name" value="<?php echo $results[5];?>" />
<br/>
<br/>
<b>Type de d&eacute;claration</b>
<br/>
<select name="t_study">
<?php
$result = pg_query("SELECT * FROM artisanal.t_study ORDER BY study");
while($row = pg_fetch_row($result)) {
  if ($row[0] == $results[6]) {
      print "<option value=\"$row[0]\" selected>".$row[1]."</option>";
  } else {
      print "<option value=\"$row[0]\">".$row[1]."</option>";
  }
}
?>
</select>
<br/>
<br/>
<b>D&eacute;barcad&egrave;re</b>
<br/>
<select name="t_site" class="chosen-select" >
<?php
$result = pg_query("SELECT * FROM artisanal.t_site ORDER BY site");
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
<div id="pir_id" <?php if($results[8] != "") {print 'style="display:none"';} ?>>
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
</div>
<b>Engin de peche</b>
<br/>
<select name="t_gear">
<?php
$result = pg_query("SELECT * FROM artisanal.t_gear ORDER BY t_gear");
while($row = pg_fetch_row($result)) {
    if ($row[0] == $results[10]) {
        print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
    } else {
        print "<option value=\"$row[0]\">".$row[1]."</option>";
    }
}
?>
</select>
<br/>
<br/>
<b>Longueur filet</b> [m]
<br/>
<input type="text" size="4" name="length" value="<?php echo $results[13];?>" />
<br/>
<br/>
<b>Taille de la maille</b> [de cote en mm]
<br/>
min: <input type="text" size="4" name="mesh_min" value="<?php echo $results[11];?>" />
max: <input type="text" size="4" name="mesh_max" value="<?php echo $results[12];?>" />
<br/>
<br/>
<b>Poids capture totale</b> [kg]
<br/>
<input type="text" size="4" name="wgt_tot" value="<?php echo $results[14];?>" />
<br/>
<br/>
<!-- <b>Charger le trac&eacute; GPS</b> (KML format)
<br/>
<input type="file" size="40" name="kml_file" />
<br/>
<br/>
-->
<?php
$query = "SELECT * FROM artisanal.captures WHERE id_maree = '$results[0]'";
$result_c = pg_query($query);
//print $query;
while($row_c = pg_fetch_row($result_c)) {
  //print $row;
  ?>
  <div class="capture">
  <fieldset class="border">
  <legend>D&eacute;tails Capture</legend>
  <b>Espece</b>
  <br/>
  <select id="species" name="id_species[]">
  <?php
  $result = pg_query("SELECT DISTINCT id, family, genus, species, francaise FROM fishery.species WHERE FAO = 'TGA' OR FAO = 'CKW' OR FAO = 'CKL' OR FAO = 'BAZ' OR FAO = 'GBX' OR FAO = 'AWX' OR FAO = 'YOX' OR FAO = 'SNA' OR FAO = 'TLP' OR FAO = 'BSX' OR FAO = 'RAJ' OR FAO = 'SIC' OR FAO = 'MAS' OR FAO = 'PET' OR FAO = 'POI' OR FAO = 'SOT' OR FAO = 'CGX' OR FAO = 'CGX' OR FAO = 'MUF' OR FAO = 'BOA' OR FAO = 'LOY' OR FAO = 'SWN' OR FAO = 'PAN' ORDER BY francaise, species");
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
  <input type="text" size="5" name="wgt_spc[]" value="<?php echo $row_c[6];?>" />
  <br/>
  <br/>
  <b>Numero des individus</b><br/>
  <input type="text" size="5" name="n_ind[]" value="<?php echo $row_c[7];?>" />
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
$result = pg_query("SELECT DISTINCT id, family, genus, species, francaise FROM fishery.species WHERE FAO = 'TGA' OR FAO = 'CKW' OR FAO = 'CKL' OR FAO = 'BAZ' OR FAO = 'GBX' OR FAO = 'AWX' OR FAO = 'YOX' OR FAO = 'SNA' OR FAO = 'TLP' OR FAO = 'BSX' OR FAO = 'RAJ' OR FAO = 'SIC' OR FAO = 'MAS' OR FAO = 'PET' OR FAO = 'POI' OR FAO = 'SOT' OR FAO = 'CGX' OR FAO = 'CGX' OR FAO = 'MUF' OR FAO = 'BOA' OR FAO = 'LOY' OR FAO = 'SWN' OR FAO = 'PAN' ORDER BY francaise, species");
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
<input type="text" size="5" name="wgt_spc[]" value="<?php echo $ech;?>" />
<br/>
<br/>
<b>Numero des individus</b><br/>
<input type="text" size="5" name="n_ind[]" value="<?php echo $ech;?>" />
</fieldset>
<br/>
</div>
`

function appendDivCapture() {
 $( ".container" ).append(DivCapture)
}

function removeDivCapture() {
 $( ".capture" ).last().remove()
}

</script>
<div class="container">
</div>
<button type="button" onclick="appendDivCapture()">Ajouter Capture</button>
<button type="button" onclick="removeDivCapture()">Supprimer Capture</button>
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

    $query = "DELETE FROM artisanal.maree WHERE id = '$id'";
    if(!pg_query($query)) {
        msg_queryerror();
    }

    $query = "DELETE FROM artisanal.captures WHERE id_maree = '$id'";
    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    }

    header("Location: ".$_SESSION['http_host']."/artisanal/view_records_capture.php?source=$source&table=$table&action=show");

    $controllo = 1;

}

if ($_POST['submit'] == "Enregistrer") {

            $id = $_POST['id'];
            $username = $_SESSION['username'];
            $datetime_d = $_POST['datetime_d'];
            $datetime_r = $_POST['datetime_r'];
            $obs_name = htmlspecialchars($_POST['obs_name'],ENT_QUOTES);
            $t_study = $_POST['t_study'];
            $t_site = $_POST['t_site'];
            $id_pirogue = $_POST['id_pirogue'];

            if ($id_pirogue == '') {
              $t_immatriculation = $_POST['t_immatriculation'];
              $reg_num = htmlspecialchars($_POST['reg_num'],ENT_QUOTES);
              $reg_year = htmlspecialchars($_POST['reg_year'],ENT_QUOTES);
              $immatriculation = $t_immatriculation.". ".$reg_num."/".$reg_year;
            } else {
              $immatriculation = '';
            }

            $t_gear = $_POST['t_gear'];
            $mesh_min = htmlspecialchars($_POST['mesh_min'],ENT_QUOTES);
            $mesh_max = htmlspecialchars($_POST['mesh_max'],ENT_QUOTES);
            $length = htmlspecialchars($_POST['length'],ENT_QUOTES);
            $wgt_tot = htmlspecialchars($_POST['wgt_tot'],ENT_QUOTES);

            $query = "UPDATE artisanal.maree SET "
            . "username = '$username', datetime_d = '$datetime_d', datetime_r = '$datetime_r', obs_name = '$obs_name', "
            . "t_site = '$t_site', t_study = '$t_study', id_pirogue = '$id_pirogue', immatriculation = '$immatriculation', t_gear = '$t_gear', "
            . "mesh_max = '$mesh_max', mesh_min = '$mesh_min', length = '$length', wgt_tot = '$wgt_tot' "
            . "WHERE id = '$id'";

            $query = str_replace('\'-- \'', 'NULL', $query);
            $query = str_replace('\'\'', 'NULL', $query);

            $results = pg_query($query);
            //print $query;

            $query = "DELETE FROM artisanal.captures WHERE id_maree = '$id'";
            pg_query($query); //delete and replace previous capture records

            for($i = 0; $i < sizeof($_POST['id_species']); $i++) {
              $id_species = $_POST['id_species'][$i];
              $wgt_spc = htmlspecialchars($_POST['wgt_spc'][$i],ENT_QUOTES);
              $n_ind = htmlspecialchars($_POST['n_ind'][$i],ENT_QUOTES);

              $query = "INSERT INTO artisanal.captures "
              . "(username, id_maree, id_species, wgt_spc, n_ind) "
              . "VALUES ('$username', '$id', '$id_species', '$wgt_spc', '$n_ind');";

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

            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=artisanal/input_records.php");

}

foot();
