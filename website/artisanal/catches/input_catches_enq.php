<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'catches';

$username = $_SESSION['username'];

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
if ($_GET['action'] != "") {$_SESSION['path'][2] = $_GET['action'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];
$action = $_SESSION['path'][2];

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if (right_write($_SESSION['username'],10,2)) {
  print "<h2>".label2name($source)." ".label2name($table)."</h2>";

  if ($table == 'maree') {

    //se submit = go!
    if ($_POST['submit'] == "Enregistrer") {

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

      $query = "INSERT INTO artisanal_catches.log_maree "
      . "(username, datetime, date_d, date_r, obs_name, t_site, id_pirogue, immatriculation, pir_name, t_gear, comments)"
      . "VALUES ('$username', NOW(), '$date_d', '$date_r', '$obs_name', '$t_site', '$id_pirogue', '$immatriculation', '$pir_name', '$t_gear', '$comments') "
      . "RETURNING id ";

      $query = str_replace('\'-- \'', 'NULL', $query);
      $query = str_replace('\'\'', 'NULL', $query);

      $id_maree = pg_fetch_row(pg_query($query));
      print $query;

      for($i = 0; $i < sizeof($_POST['id_species']); $i++) {
        $id_species = $_POST['id_species'][$i];
        $wgt = htmlspecialchars($_POST['wgt'][$i],ENT_QUOTES);
        $comments_catch = htmlspecialchars($_POST['comments_catch'][$i],ENT_QUOTES);

        $query = "INSERT INTO artisanal_catches.log_catch "
        . "(username, id_maree, id_species, wgt, comments) "
        . "VALUES ('$username', '$id_maree[0]', '$id_species', '$wgt', '$comments_catch');";

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

      header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=artisanal/catches/input_catches_log.php");

      $controllo = 1;
    }

    if (!$controllo) {
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

      <?php
    }
  }

} else {
  msg_noaccess();
}

foot();
