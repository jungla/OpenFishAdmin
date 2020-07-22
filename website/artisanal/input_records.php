<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';

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

if (right_write($_SESSION['username'],5,2)) {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";


if ($table == 'captures') {

//se submit = go!
if ($_POST['submit_mission'] == "Enregistrer") {

     # load KML file now.
    if ($_FILES['kml_file']['tmp_name'] != "") {

        $filename = $_FILES['kml_file']['tmp_name'];
        $filename_out = uniqid($username);
        # generate shapefiles

        exec("cp $filename ./files/tracks/$filename_out.kml");
        exec("chmod 0777 ./files/tracks/$filename_out.kml");

        $xml = file_get_contents($filename);

        $string = split("coordinates",$xml)[1];
        $string = str_replace([">","</"],"",$string);
        $coords_array = split(" ",$string);

        $coords = "";

        foreach ($coords_array as $coord) {
         $coords = $coords . str_replace(","," ",$coord) . ",";
        }
        $coords = rtrim($coords,",");
        $track = "'LINESTRING(" . $coords . ")',4326";
        $gps_file = "./files/tracks/$filename_out.kml";

    } else {

        $track = NULL;
        $gps_file = NULL;

    }
    # loads shapefile to temporary db table (syncs later)
    # a random table name should be generated here and passed in SESSION for later use

	#   username, datetime_d, datetime_r, obs_name, t_site, t_study, id_pirogue, immatriculation, t_gear, mesh_max, length, gps_file

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

        if ($track == "") {
            $track = 'NULL';
        }

        $query = "INSERT INTO artisanal.maree "
        . "(username, datetime_d, datetime_r, obs_name, t_site, t_study, id_pirogue, immatriculation, t_gear, mesh_max, mesh_min, length, gps_file, gps_track) "
        . "VALUES ('$username','$datetime_d','$datetime_r', '$obs_name', '$t_site', '$t_study', '$id_pirogue', '$immatriculation', '$t_gear', '$mesh_max', '$mesh_min', '$length', '$gps_file', ST_GeomFromText($track)) RETURNING id;";

        $query = str_replace('\'-- \'', 'NULL', $query);
        $query = str_replace('\'\'', 'NULL', $query);

        $id_maree = pg_fetch_row(pg_query($query));

        print $query;

        for($i = 0; $i < sizeof($_POST['id_species']); $i++) {
          $id_species = $_POST['id_species'][$i];
          $wgt_spc = htmlspecialchars($_POST['wgt_spc'][$i],ENT_QUOTES);
          $n_ind = htmlspecialchars($_POST['n_ind'][$i],ENT_QUOTES);

          if ($wgt_spc != "") {
            $query = "INSERT INTO artisanal.captures "
            . "(username, id_maree, id_species, wgt_spc, n_ind) "
            . "VALUES ('$username', '$id_maree[0]', '$id_species', '$wgt_spc', '$n_ind');";

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
        }

        //header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=artisanal/input_records.php");

  $controllo = 1;

}

if (!$controllo) {
  ?>
  <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">
  <b>Date de d&eacute;part</b>
  <br/>
  <input type="date" size="30" name="datetime_d" value="<?php echo $results[4];?>" />
  <br/>
  <br/>
  <b>Date de retour</b>
  <br/>
  <input type="date" size="30" name="datetime_r" value="<?php echo $results[4];?>" />
  <br/>
  <br/>
  <b>Nom du collecteur</b>
  <br/>
  <input type="text" size="20" name="obs_name" value="<?php echo $obs_name;?>" />
  <br/>
  <br/>
  <b>Type de d&eacute;claration</b>
  <br/>
  <select name="t_study">
  <?php
  $result = pg_query("SELECT * FROM artisanal.t_study ORDER BY study");
  while($row = pg_fetch_row($result)) {
   print "<option value=\"$row[0]\">".$row[1]."</option>";
  }
  ?>
  </select>
  <br/>
  <br/>
  <b>D&eacute;barcad&egrave;re</b>
  <br/>
  <select name="t_site" class="chosen-select" >
  <?php
  $result = pg_query("SELECT * FROM artisanal.t_site_obb ORDER BY site");
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
  <b>D&eacute;tails Pirogue</b>
  <br/>
  <select name="id_pirogue"  class="chosen-select" onchange="java_script_:show(this.options[this.selectedIndex].value,'pir_id')">
  <option value=''>PAS DANS LA LISTE</option>
  <?php
  $result = pg_query("SELECT id, name, immatriculation FROM artisanal.pirogue ORDER BY name");
  while($row = pg_fetch_row($result)) {
      if ($row[0] == $results[7]) {
          print "<option value=\"$row[0]\" selected=\"selected\">".$row[2]." - ".$row[1]."</option>";
      } else {
          print "<option value=\"$row[0]\">".$row[2]." - ".$row[1]."</option>";
      }
  }
  ?>
  </select>
  <br/>
  <br/>
  <div class="pir_id" <?php if($results[7] != "") {print 'style="display:none"';} ?>>
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
  </div>
  <b>Poids capture totale (en cas de statistiques sur un sous-&eacute;chantillon)</b> [kg]
  <br/>
  <input type="text" size="4" name="wgt_tot" value="<?php echo $wgt_tot;?>" />
  <br/>
  <br/>
  <!--<b>Charger le trac&eacute; GPS</b> (KML format)
  <br/>
  <input type="file" size="40" name="kml_file" />
  <br/>
  <br/>-->
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
  <input type="submit" value="Enregistrer" name="submit_mission"/>
  </form>

<?php
}

} else if ($table == 'effort') {

    $username = $_SESSION['username'];
    $date_e = $_POST['date_e'];
    $obs_name = $_POST['obs_name'];
    $t_site = $_POST['t_site'];
    $DB1 = $_POST['DB1'];
    $DB3 = $_POST['DB3'];
    $DH1 = $_POST['DH1'];
    $DH3 = $_POST['DH3'];
    $PS1 = $_POST['PS1'];
    $PS3 = $_POST['PS3'];
    $PC1 = $_POST['PC1'];
    $PC3 = $_POST['PC3'];

    if ($_POST['submit'] == "Enregistrer") {
        $query = "INSERT INTO artisanal.effort (username, date_e, obs_name, t_site, DB1, DB3, DH1, DH3, PS1, PS3, PC1, PC3) "
                . "VALUES ('$username', '$date_e', '$obs_name', '$t_site', '$DB1', '$DB3', '$DH1', '$DH3', '$PS1', '$PS3', '$PC1', '$PC3');";

        $query = str_replace('\'\'', 'NULL', $query,$i);

        if(!pg_query($query)) {
            echo $query."<br/>";
            msg_queryerror();
        } else {
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert%20Data&id_dest=input_form_art.php");
        }

        $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Date (aaaa-mm-jj)</b>
    <br/>
    <input type="text" size="10" name="date_e" />
    <br/>
    <br/>
    <b>Enqu&ecirc;teur</b>
    <br/>
    <input type="text" size="20" name="obs_name" />
    <br/>
    <br/>
    <b>D&eacute;barcad&egrave;re</b>
    <br/>
    <select name="t_site">
        <?php
        $result = pg_query("SELECT * FROM artisanal.t_site");
        while($row = pg_fetch_row($result)) {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
        ?>
    </select>
    <br/>
    <br/>
    <b>Number of Demersal Bottom Net boats fishing for 1 day (DB1)</b>
    <br/>
    <input type="text" size="4" name="DB1" />
    <br/>
    <br/>
    <b>Number of Demersal Bottom Net boats fishing for 3 days (DB3)</b>
    <br/>
    <input type="text" size="4" name="DB3" />
    <br/>
    <br/>
    <b>Number of Demersal Hand line boats fishing for 1 day (DH1)</b>
    <br/>
    <input type="text" size="4" name="DH1" />
    <br/>
    <br/>
    <b>Number of Demersal Hand line boats fishing for 3 days (DH3)</b>
    <br/>
    <input type="text" size="4" name="DH3" />
    <br/>
    <br/>
    <b>Number of Pelagic Sleeping Net boats fishing for 1 day (PS1)</b>
    <br/>
    <input type="text" size="4" name="PS1" />
    <br/>
    <br/>
    <b>Number of Pelagic Sleeping Net boats fishing for 3 days (PS3)</b>
    <br/>
    <input type="text" size="4" name="PS3" />
    <br/>
    <br/>
    <b>Number of Pelagic Circling Net boats fishing for 1 day (PC1)</b>
    <br/>
    <input type="text" size="4" name="PC1" />
    <br/>
    <br/>
    <b>Number of Pelagic Circling Net boats fishing for 3 days (PC3)</b>
    <br/>
    <input type="text" size="4" name="PC3" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/><br/>

<?php
}


} else if ($table == 'fleet') {

    $username = $_SESSION['username'];
    $date_m = $_POST['date_m'];
    $obs_name = $_POST['obs_name'];
    $t_site = $_POST['t_site'];
    $source = $_POST['source'];
    $PPB = $_POST['PPB'];
    $GPF = $_POST['GPF'];
    $PPF = $_POST['PPF'];
    $TOT = floatval($PPB) + floatval($GPF) + floatval($PPF);

    if ($_POST['submit'] == "Enregistrer") {

        $query = "INSERT INTO artisanal.fleet (username, date_m, obs_name, t_site, source, PPB, GPF, PPF, TOT) "
                . "VALUES ('$username', '$date_m', '$obs_name', '$t_site', '$source', '$PPB', '$GPF', '$PPF', '$TOT');";

        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
            echo $query."<br/>";
            msg_queryerror();
        } else {
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert%20Data&id_dest=input_form_art.php?source=artisanal");
        }

        $controllo = 1;

    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Date</b>
    <br/>
    <input type="text" size="10" name="date_m" />
    <br/>
    <br/>
    <b>Enqu&ecirc;teur</b>
    <br/>
    <input type="text" size="20" name="obs_name" />
    <br/>
    <br/>
    <b>D&eacute;barcad&egrave;re</b>
    <br/>
    <select name="t_site">
        <?php
        $result = pg_query("SELECT * FROM artisanal.t_site");
        while($row = pg_fetch_row($result)) {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
        ?>
        </select>
    <br/>
    <br/>
    <b>Data Source</b>
    <br/>
    <input type="text" size="20" name="source" />
    <br/>
    <br/>
    <b>Number of <i>Petite Pirogue en Bois (PPB)</i></b>
    <br/>
    <input type="text" size="4" name="PPB" />
    <br/>
    <br/>
    <b>Number of <i>Grande Pirogue en Fibre de Verre (GPF)</i></b>
    <br/>
    <input type="text" size="4" name="GPF" />
    <br/>
    <br/>
    <b>Number of <i>Petite Pirogue  en Fibre de Verre (PPF)</i></b>
    <br/>
    <input type="text" size="4" name="PPF" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/><br/>
    <?php
}
} else if ($table == 'market') {


    if ($_POST['submit'] == "Enregistrer") {

            $username = $_SESSION['username'];

            $date_m = $_POST['date_m'];
            $obs_name = $_POST['obs_name'];
            $t_site = $_POST['t_site'];

            if ($_POST['cap'] == 'p_s') {
                $p_s = $_POST['prix'];
            } else if ($_POST['cap'] == 'p_p') {
                $p_p = $_POST['prix'];
            } else if ($_POST['cap'] == 'p_c') {
                $p_c = $_POST['prix'];
            } else if ($_POST['cap'] == 'p_m') {
                $p_m = $_POST['prix'];
            } else if ($_POST['cap'] == 'p_f') {
                $p_f = $_POST['prix'];
            }

            $query = "INSERT INTO artisanal.market (username,t_site,date_m,obs_name,id_species,p_s,p_p,p_c,p_m,p_f) "
                    . "VALUES ('$username','$t_site','$date_m','$obs_name','".$_POST['id_species']."','$p_s','$p_p','$p_c','$p_m','$p_f');";

            $query = str_replace('\'\'', 'NULL', $query);

            if(!pg_query($query)) {
                echo $query."<br/>";
                msg_queryerror();
            } else {
                if ($_POST['extra_species'] == 'no') {
                    header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert%20Data&id_dest=input_form_art.php?source=artisanal&table=market");
                } else {
                    # fishing done. Store POST variables in SESSION.
                    $_SESSION['data'] = $_POST;
                    header("Location: ".$_SESSION['http_host']."/artisanal/input_records_1.php?source=artisanal&table=market");
                }
            }

            $controllo = 1;


    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Date</b>
    <br/>
    <input type="text" size="10" name="date_m" />
    <br/>
    <br/>
    <b>Enqu&ecirc;teur</b>
    <br/>
    <input type="text" size="20" name="obs_name" />
    <br/>
    <br/>
    <b>D&eacute;barcad&egrave;re</b>
    <br/>
    <select name="t_site">
        <?php
        $result = pg_query("SELECT * FROM artisanal.t_site");
        while($row = pg_fetch_row($result)) {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
        }
        ?>
        </select>
    <br/>
    <br/>
    <b>Esp&egrave;ce</b>
    <br/>
    <select name="id_species">
    <?php
    $query = pg_query("SELECT * FROM fishery.species ORDER BY family");
    while($row = pg_fetch_row($query)) {
        $species = formatSpecies($row[1],$row[2],$row[3],$row[4]);
        print "<option value=\"".trim($row[0])."\">".$species."</option>";
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Categorie</b>
    <br/>
    <input type="radio" name="cat" value="p_s" checked/>&nbsp;frais<br/>
    <input type="radio" name="cat" value="p_p" />&nbsp;&agrave; la pirogue<br/>
    <input type="radio" name="cat" value="p_c" />&nbsp;congel&eacute;<br/>
    <input type="radio" name="cat" value="p_m" />&nbsp;chez la mareyeuse<br/>
    <input type="radio" name="cat" value="p_f" />&nbsp;fum&eacute;
    <br/>
    <br/>
    <b>Prix (FCFA/kg)</b>
    <br/>
    <input type="text" size="10" name="prix" />
    <br/>
    <br/>
    <b>Ajouter une autre esp&egrave;ce?</b>
    <br/>
    Oui<input type="radio" name="extra_species" value="yes" checked/>
    Non<input type="radio" name="extra_species" value="no" />
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>
    <?php
}
}
} else {
    msg_noaccess();
}

foot();
