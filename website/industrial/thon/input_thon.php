<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'trawlers';

$username = $_SESSION['username'];

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
if ($_GET['action'] != "") {$_SESSION['path'][2] = $_GET['action'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];
$action = $_SESSION['path'][2];

$self = filter_input(INPUT_SERVER, 'PHP_SELF');
$host = filter_input(INPUT_SERVER, 'HTTP_HOST');

print "<h2>".label2name($source)." ".label2name($table)."</h2>";

if ($table == 'lance') {

//se submit = go!
if ($_POST['submit'] == "Enregistrer") {

    function query_captures($taille,$poids,$FAO,$id_lance,$rejete) {

      global $username;

      for($i = 0; $i < sizeof($taille); $i++) {
        $taille_s = htmlspecialchars($taille[$i],ENT_QUOTES);
        $poids_s = htmlspecialchars($poids[$i],ENT_QUOTES);

        if ($poids_s != '') {
          if (is_array($FAO)) {
            $q_s = "SELECT id FROM fishery.species WHERE FAO = '$FAO[$i]'";
          } else {
            $q_s = "SELECT id FROM fishery.species WHERE FAO = '$FAO'";
          }

          print $q_s;
          $r_s = pg_query($q_s);
          $species_id = pg_fetch_row($r_s)[0];

          $query = "INSERT INTO thon.captures"
          . "(username, id_lance, id_species, poids, taille, rejete) "
          . "VALUES ('$username', '$id_lance', '$species_id', '$poids_s', '$taille_s', '$rejete');";

          print $query;

          $query = str_replace('\'-- \'', 'NULL', $query);
          $query = str_replace('\'\'', 'NULL', $query);
          //print $query;

          if(!pg_query($query)) {
            echo "<p>".$query,"</p>";
            msg_queryerror();
            foot();
            die();
          }
        }
      }
  }


      $lon_deg = htmlspecialchars($_POST['lon_deg'],ENT_QUOTES);
      $lat_deg = htmlspecialchars($_POST['lat_deg'],ENT_QUOTES);
      $lon_min = htmlspecialchars($_POST['lon_min'],ENT_QUOTES);
      $lat_min = htmlspecialchars($_POST['lat_min'],ENT_QUOTES);

      $lon = $lon_deg+$lon_min/60;
      $lat = $lat_deg+$lat_min/60;

      if ($lon == "" OR $lat == "") {
          $point = "NULL";
      } else {
          if ($_POST['NS'] == 'S') {$lat = -1*$lat;}
          if ($_POST['EW'] == 'W') {$lon = -1*$lon;}
          $point = "'POINT($lon $lat)'";
      }

      $id_navire = str_replace("'","",$_POST['id_navire']);
      $date_c = htmlspecialchars($_POST['date_c'],ENT_QUOTES);
      $heure_c = htmlspecialchars($_POST['heure_c'],ENT_QUOTES);
      $eez = htmlspecialchars($_POST['eez'],ENT_QUOTES);
      $water_temp = comma2dot($_POST['water_temp']);
      $wind_speed = comma2dot($_POST['wind_speed']);
      $wind_dir = comma2dot($_POST['wind_dir']);
      $cur_speed = comma2dot($_POST['cur_speed']);
      $success = $_POST['success'];
      $banclibre = $_POST['banclibre'];
      $balise_id = $_POST['balise_id'];
      $comment = htmlspecialchars($_POST['comment'],ENT_QUOTES);

      $taille_YFT = $_POST['taille_YFT'];
      $poids_YFT = $_POST['poids_YFT'];
      $taille_BET = $_POST['taille_BET'];
      $poids_BET = $_POST['poids_BET'];
      $taille_SKJ = $_POST['taille_SKJ'];
      $poids_SKJ = $_POST['poids_SKJ'];
      $FAO_a = $_POST['FAO_a'];
      $taille_a = $_POST['taille_a'];
      $poids_a = $_POST['poids_a'];
      $FAO_r = $_POST['FAO_r'];
      $taille_r = $_POST['taille_r'];
      $poids_r = $_POST['poids_r'];

      #navire, country, port_d, port_a, date_d, date_a, ndays, date_c, heure_c, lance, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, comment, st_x(location), st_y(location)
      $query = "INSERT INTO thon.lance "
              . "(username, datetime, id_navire, date_c, heure_c, eez, water_temp, wind_speed, wind_dir, cur_speed, success, banclibre, balise_id, comment, location) "
              . "VALUES ('$username', now(), '$id_navire', '$date_c', '$heure_c', '$eez', '$water_temp', '$wind_speed', '$wind_dir', '$cur_speed', '$success', '$banclibre', '$balise_id', '$comment', ST_GeomFromText($point,4326)) RETURNING id";
      $query = str_replace('\'\'', 'NULL', $query);
      $id_lance = pg_fetch_row(pg_query($query))[0];

      //print $query;
      //print $id_lance;

      query_captures($taille_YFT,$poids_YFT,'YFT',$id_lance,'FALSE');
      query_captures($taille_BET,$poids_BET,'BET',$id_lance,'FALSE');
      query_captures($taille_SKJ,$poids_SKJ,'SKJ',$id_lance,'FALSE');
      query_captures($taille_a,$poids_a,$FAO_a,$id_lance,'FALSE');
      query_captures($taille_r,$poids_r,$FAO_r,$id_lance,'TRUE');

      header("Location: ".$_SESSION['http_host']."/industrial/thon/view_thon_lance.php?source=$source&table=lance&action=show");

  $controllo = 1;

}

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <br/>
    <table id='small'>
      <tr>
        <td><b>Navire</b></td><td><b>EEZ</b></td><td><b>Date lance</b></td><td><b>Heure lance</b></td>
        <td><b>Temp eau</b> [C]</td>
        <td><b>Vitesse vent</b> [m/s]</td>
        <td><b>Direction vent</b> [degree]</td>
        <td><b>Vitesse courant</b> [nd]</td>
        </tr>
      <tr>
        <td><select name="id_navire">
            <?php
            $result = pg_query("SELECT DISTINCT id, navire FROM vms.navire "
                    . "ORDER BY navire");
            while($row = pg_fetch_row($result)) {
                if ($row[0] == $results[3]) {
                    print "<option value=\"'$row[0]'\" selected=\"selected\">$row[1]</option>";
                } else {
                    print "<option value=\"'$row[0]'\">$row[1]</option>";
                }
            }
            ?>
        </select>
        </td>
        <td><input type="text" size="6" name="eez" value="<?php echo $results[7];?>" /></td>
        <td><input type="date" size="20" name="date_c" value="<?php echo $results[4]; ?>"/></td>
        <td><input type="time" size="20" name="heure_c" value="<?php echo $results[5];?>" /></td>
        <td><input type="text" size="3" name="water_temp" value="<?php echo $results[11];?>" /></td>
        <td><input type="text" size="3" name="wind_speed" value="<?php echo $results[12];?>" /></td>
        <td><input type="text" size="3" name="wind_dir" value="<?php echo $results[13];?>" /></td>
        <td><input type="text" size="3" name="cur_speed" value="<?php echo $results[14];?>" /></td>
        </tr>

</table>
<table id='small'>
      <tr>
        <td><b>Portant</b></td><td><b>Banc libre</b>
        </td><td><b>Code balise</b></td>
        <td><b>Latitude</b></td><td><b>Longitude</b></td><td><b>Remarques</b></td>
      </tr>
      <tr>
        <td nowrap>
          <input type="radio" name="success" value="TRUE" checked  <?php if($results[8] == 't') {print "checked";} ?>/>Oui
          <input type="radio" name="success" value="FALSE" <?php if($results[8] == 'f') {print "checked";} ?>/>No
        </td>
        <td nowrap>
          <input type="radio" name="banclibre" value="TRUE" checked  <?php if($results[9] == 't') {print "checked";} ?>/>Oui
          <input type="radio" name="banclibre" value="FALSE" <?php if($results[9] == 'f') {print "checked";} ?>/>No<br/>
          <input type="radio" name="banclibre" value="" <?php if($results[9] == '') {print "checked";} ?>/>Pas precis&eacute;
        </td>
        <td><input type="text" size="10" name="balise_id" value="<?php echo $results[10];?>" /></td>
        <td nowrap>
          <input type="text" size="3" name="lat_deg" value="<?php echo abs($lat_deg);?>" />&deg;
          <input type="text" size="3" name="lat_min" value="<?php echo abs($lat_min);?>" />&prime;
          <select name="NS">
            <option value="N" <?php if($lat_deg >= 0){print "selected";} ?>>N</option>
            <option value="S" <?php if($lat_deg < 0){print "selected";} ?>>S</option>
          </select>
          </td>
        <td nowrap>
          <input type="text" size="3" name="lon_deg" value="<?php echo abs($lon_deg);?>" />&deg;
          <input type="text" size="3" name="lon_min" value="<?php echo abs($lon_min);?>" />&prime;
          <select name="EW">
            <option value="E" <?php if($lon_deg >= 0){print "selected";} ?>>E</option>
            <option value="W" <?php if($lon_deg < 0){print "selected";} ?>>W</option>
          </select>
        </td>
        <td><textarea cols="20" rows="3" name="comment"><?php echo $results[15];?></textarea></td>
    </tr>
    </table>

<table id="small" >
  <tr align="center" >
    <td colspan="2" style="border: 1px solid black"><b>YFT</b></td><td colspan="2" style="border: 1px solid black"><b>BET</b></td><td colspan="2" style="border: 1px solid black"><b>SKJ</b></td>
    <td colspan="3" style="border: 1px solid black"><b>Prise accessoires</b></td><td colspan="3" style="border: 1px solid black"><b>Rejets</b></td>
  </tr>
  <tr align="center">
    <td width="9%">Taille</td><td width="9%">Capture [T]</td>
    <td width="9%">Taille</td><td width="9%">Capture [T]</td>
    <td width="9%">Taille</td><td width="9%">Capture [T]</td>
    <td width="7.6%">FAO</td><td width="7.6%">Taille</td><td width="7.6%">Capture [T]</td>
    <td width="7.6%">FAO</td><td width="7.6%">Taille</td><td width="7.6%">Capture [T]</td>
  </tr>
    <tr align="center">
      <td width="9%"><input type="text" size="1" name="taille_YFT[]" /></td>
      <td width="9%"><input type="text" size="1" name="poids_YFT[]" /></td>
      <td width="9%"><input type="text" size="1" name="taille_BET[]" /></td>
      <td width="9%"><input type="text" size="1" name="poids_BET[]" /></td>
      <td width="9%"><input type="text" size="1" name="taille_SKJ[]" /></td>
      <td width="9%"><input type="text" size="1" name="poids_SKJ[]" /></td>

      <td width="7.6%"><input type="text" size="1" name="FAO_a[]" /></td>
      <td width="7.6%"><input type="text" size="1" name="taille_a[]" /></td>
      <td width="7.6%"><input type="text" size="1" name="poids_a[]" /></td>

      <td width="7.6%"><input type="text" size="1" name="FAO_r[]" /></td>
      <td width="7.6%"><input type="text" size="1" name="taille_r[]" /></td>
      <td width="7.6%"><input type="text" size="1" name="poids_r[]" /></td>
  </tr>


  <script type='text/javascript'>
  var DivCapture = `<tr class="capture" align="center">
      <td width="9%"><input type="text" size="1" name="taille_YFT[]" /></td>
      <td width="9%"><input type="text" size="1" name="poids_YFT[]" /></td>
      <td width="9%"><input type="text" size="1" name="taille_BET[]" /></td>
      <td width="9%"><input type="text" size="1" name="poids_BET[]" /></td>
      <td width="9%"><input type="text" size="1" name="taille_SKJ[]" /></td>
      <td width="9%"><input type="text" size="1" name="poids_SKJ[]" /></td>

      <td width="7.6%"><input type="text" size="1" name="FAO_a[]" /></td>
      <td width="7.6%"><input type="text" size="1" name="taille_a[]" /></td>
      <td width="7.6%"><input type="text" size="1" name="poids_a[]" /></td>

      <td width="7.6%"><input type="text" size="1" name="FAO_r[]" /></td>
      <td width="7.6%"><input type="text" size="1" name="taille_r[]" /></td>
      <td width="7.6%"><input type="text" size="1" name="poids_r[]" /></td>
    </tr>
  `

  function appendDivCapture() {
   $( ".container" ).append(DivCapture)
  }

  function removeDivCapture() {
   $( ".capture" ).last().remove()
  }

  </script>
  <table class="container">
  </table>
  <button type="button" onclick="appendDivCapture()" style="height:30px; width:30px;"><b>+</b></button>&nbsp;
  <button type="button" onclick="removeDivCapture()" style="height:30px; width:30px;"><b>-</b></button>
  <br/>


  </table>
    <br/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    <input type="submit" value="Enregistrer" name="submit"/>
    <button type="button" onclick="goBack()">Retourner</button>
    </form>

    <br/>
    <br/>

<?php
}

} else if ($table = 'entreesortie') {

  //se submit = go!
  if ($_POST['submit'] == "Enregistrer") {
    $lon_deg = htmlspecialchars($_POST['lon_deg'],ENT_QUOTES);
    $lat_deg = htmlspecialchars($_POST['lat_deg'],ENT_QUOTES);
    $lon_min = htmlspecialchars($_POST['lon_min'],ENT_QUOTES);
    $lat_min = htmlspecialchars($_POST['lat_min'],ENT_QUOTES);

    $lon = $lon_deg+$lon_min/60;
    $lat = $lat_deg+$lat_min/60;

    if ($lon == "" OR $lat == "") {
        $point = "NULL";
    } else {
        if ($_POST['NS'] == 'S') {$lat = -1*$lat;}
        if ($_POST['EW'] == 'W') {$lon = -1*$lon;}
        $point = "'POINT($lon $lat)'";
    }

    $id_navire = str_replace("'","",$_POST['id_navire']);
    $date_e = htmlspecialchars($_POST['date_e'],ENT_QUOTES);
    $heure_e = htmlspecialchars($_POST['heure_e'],ENT_QUOTES);
    $eez = htmlspecialchars($_POST['eez'],ENT_QUOTES);
    $YFT = htmlspecialchars(comma2dot($_POST['YFT'],ENT_QUOTES));
    $BET = htmlspecialchars(comma2dot($_POST['BET'],ENT_QUOTES));
    $SKJ = htmlspecialchars(comma2dot($_POST['SKJ'],ENT_QUOTES));
    $FRI = htmlspecialchars(comma2dot($_POST['FRI'],ENT_QUOTES));

    $entree = $_POST['entree'];
    $remarques = htmlspecialchars($_POST['remarques'],ENT_QUOTES);

        #navire, country, port_d, port_a, date_d, date_a, ndays, date_e, heure_e, entreesortie, eez, BET, SKJ, FRI,  entree, banclibre, YFT, rejete, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, taille, poids, remarques, st_x(location), st_y(location)
        $query = "INSERT INTO thon.entreesortie "
                . "(username, datetime, id_navire, date_e, heure_e, eez, YFT, BET, SKJ, FRI, entree, remarques, location) "
                . "VALUES ('$username', now(), '$id_navire', '$date_e', '$heure_e', '$eez', '$YFT', '$BET', '$SKJ', '$FRI', '$entree', '$remarques', ST_GeomFromText($point,4326)) RETURNING id";
        $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
      echo "<p>".$query,"</p>";
      msg_queryerror();
      foot();
      die();
    } else {
      header("Location: ".$_SESSION['http_host']."/industrial/thon/view_thon_entreesortie.php?source=$source&table=entreesortie&action=show");
    }
  }

  if (!$controllo) {
      ?>
      <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
      <br/>
      <b>Navire</b><br/>
      <select name="id_navire">
              <?php
              $result = pg_query("SELECT DISTINCT id, navire FROM vms.navire "
                      . "ORDER BY navire");
              while($row = pg_fetch_row($result)) {
                  if ($row[0] == $results[3]) {
                      print "<option value=\"'$row[0]'\" selected=\"selected\">$row[1]</option>";
                  } else {
                      print "<option value=\"'$row[0]'\">$row[1]</option>";
                  }
              }
              ?>
      </select>
      <br/>
      <br/>
      <b>EEZ</b><br/>
      <input type="text" size="6" name="eez" value="<?php echo $results[6];?>" />
      <br/>
      <br/>
      <b>Date entree/sortie</b><br/>
      <input type="date" size="20" name="date_e" value="<?php echo $results[4]; ?>"/>
      <br/>
      <br/>
      <b>Heure entree/sortie</b><br/>
      <input type="time" size="20" name="heure_e" value="<?php echo $results[5];?>" />
      <br/>
      <br/>
      <b>Entree/Sortie</b><br/>
      <input type="radio" name="entree" value="TRUE" checked <?php if($results[7] == 't') {print "checked";} ?>/>Entree
      <input type="radio" name="entree" value="FALSE" <?php if($results[7] == 'f') {print "checked";} ?>/>Sortie
      <br/>
      <br/>
      <table>
        <tr><td><b>YFT</b> [ton]</td><td><b>BET</b> [ton]</td><td><b>SKJ</b> [ton]</td><td><b>FRI</b> [ton]</td></tr>
        <tr>
          <td><input type="text" size="3" name="YFT" value="<?php echo $results[8];?>" /></td>
          <td><input type="text" size="3" name="BET" value="<?php echo $results[9];?>" /></td>
          <td><input type="text" size="3" name="SKJ" value="<?php echo $results[10];?>" /></td>
          <td><input type="text" size="3" name="FRI" value="<?php echo $results[11];?>" /></td>
        </tr>
      </table>
      <br/>

          <b>Latitude</b><br/>
          <input type="text" size="3" name="lat_deg" value="<?php echo abs($lat_deg);?>" />&deg;
          <input type="text" size="3" name="lat_min" value="<?php echo abs($lat_min);?>" />&prime;
          <select name="NS">
            <option value="N" <?php if($lat_deg >= 0){print "selected";} ?>>N</option>
            <option value="S" <?php if($lat_deg < 0){print "selected";} ?>>S</option>
          </select>
  <br/>
  <br/>
          <b>Longitude</b><br/>
          <input type="text" size="3" name="lon_deg" value="<?php echo abs($lon_deg);?>" />&deg;
          <input type="text" size="3" name="lon_min" value="<?php echo abs($lon_min);?>" />&prime;
          <select name="EW">
            <option value="E" <?php if($lon_deg >= 0){print "selected";} ?>>E</option>
            <option value="W" <?php if($lon_deg < 0){print "selected";} ?>>W</option>
          </select>
          <br/>
          <br/>
        <b>Remarques</b><br/>
        <textarea cols="20" rows="3" name="remarques"><?php echo $results[12];?></textarea>

      <br/>
      <br/>
      <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
      <input type="submit" value="Enregistrer" name="submit"/>
      <button type="button" onclick="goBack()">Retourner</button>
      </form>

      <br/>
      <br/>

  <?php
}
}

foot();
