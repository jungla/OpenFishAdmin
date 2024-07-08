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
$_SESSION['filter']['f_id_maree'] = $_POST['f_id_maree'];
$_SESSION['filter']['f_observateur'] = $_POST['f_observateur'];

if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}
if ($_GET['f_id_maree'] != "") {$_SESSION['filter']['f_id_maree'] = $_GET['f_id_maree'];}
if ($_GET['f_observateur'] != "") {$_SESSION['filter']['f_observateur'] = $_GET['f_observateur'];}

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
      <table id="no-border"><tr><td><b>Maree</b></td><td><b>Observateur</b></td><!--<td><b>Capture</b></td>--></tr>
        <tr>
          <td>
            <select name="f_id_maree" class="chosen-select" style="width:100%">
              <option value="obs_fish.id_maree" selected="selected">Tous</option>
              <?php
              $result = pg_query("SELECT DISTINCT obs_fish.id_maree, date_d, concat(pirogue.immatriculation, obs_maree.immatriculation) FROM artisanal_catches.obs_fish "
              . "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_fish.id_maree "
              . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
              . "ORDER BY date_d");
              while($row = pg_fetch_row($result)) {
                if ("'".$row[0]."'" == $_SESSION['filter']['f_id_maree']) {
                  print "<option value=\"'$row[0]'\" selected=\"selected\">$row[1] $row[2]</option>";
                } else {
                  print "<option value=\"'$row[0]'\">$row[1] $row[2]</option>";
                }
              }
              ?>
            </select>
          </td>
          <td>
            <input type="text" size="20" name="f_observateur" value="<?php echo $_SESSION['filter']['f_observateur']?>"/>
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
      <td><b>Observateur</b></td>
      <td><b>Maree</b></td>
      <td><b>Commentaires</b></td>
      <td><b>Espece</b></td>
      <td><b>Numero lot</b></td>
      <td><b>Pourcentage</b></td>
      <td><b>Poids lot</b></td>
    </tr>

    <?php

    if ($_SESSION['filter']['f_id_maree'] != "" OR $_SESSION['filter']['f_id_species'] != "" OR $_SESSION['filter']['f_observateur'] != "") {

      //id, datetime, username, id_maree, id_species, n_lot, perc, wgt

      $_SESSION['start'] = 0;

      $query = "SELECT count(DISTINCT obs_fish.id) FROM artisanal_catches.obs_fish "
      . "WHERE obs_fish.id_maree = ".$_SESSION['filter']['f_id_maree']." ";

      $pnum = pg_fetch_row(pg_query($query))[0];

      if ($_SESSION['filter']['f_immatriculation'] != "" OR $_SESSION['filter']['f_observateur'] != "") {
        $query = "SELECT obs_fish.id, obs_fish.datetime::date, obs_fish.username, date_d, "
        . "concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.obs_name, obs_maree.id_pirogue, "
        . "wpt, date_a, time_a, obs_fish.id_maree, id_action, fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, "
        . " t_sex.sex, t_maturity.maturity, LT, wgt, t_status.status, t_action.action, photo_data, obs_fish.comments,  "
        . " coalesce(similarity(obs_name, '".$_SESSION['filter']['f_observateur']."'),0) AS score "
        . "FROM artisanal_catches.obs_fish "
        . "LEFT JOIN fishery.species ON obs_fish.id_species = fishery.species.id "
        . "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_fish.id_maree "
        . "LEFT JOIN artisanal_catches.obs_action ON artisanal_catches.obs_action.id = artisanal_catches.obs_fish.id_action "
        . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
        . "LEFT JOIN artisanal_catches.t_sex ON artisanal_catches.t_sex.id = artisanal_catches.obs_fish.t_sex "
        . "LEFT JOIN artisanal_catches.t_maturity ON artisanal_catches.t_maturity.id = artisanal_catches.obs_fish.t_maturity "
        . "LEFT JOIN artisanal_catches.t_status ON artisanal_catches.t_status.id = artisanal_catches.obs_fish.t_status "
        . "LEFT JOIN artisanal_catches.t_action ON artisanal_catches.t_action.id = artisanal_catches.obs_fish.t_action "
        . "WHERE obs_fish.id_maree = ".$_SESSION['filter']['f_id_maree']." "
        . "ORDER BY score DESC OFFSET $start LIMIT $step";
      } else {
        $query = "SELECT obs_fish.id, obs_fish.datetime::date, obs_fish.username, date_d, "
        . "concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.obs_name, obs_maree.id_pirogue, "
        . "wpt, date_a, time_a, obs_fish.id_maree, id_action, fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, "
        . " t_sex.sex, t_maturity.maturity, LT, wgt, t_status.status, t_action.action, photo_data, obs_fish.comments  "
        . "FROM artisanal_catches.obs_fish "
        . "LEFT JOIN fishery.species ON obs_fish.id_species = fishery.species.id "
        . "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_fish.id_maree "
        . "LEFT JOIN artisanal_catches.obs_action ON artisanal_catches.obs_action.id = artisanal_catches.obs_fish.id_action "
        . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
        . "LEFT JOIN artisanal_catches.t_sex ON artisanal_catches.t_sex.id = artisanal_catches.obs_fish.t_sex "
        . "LEFT JOIN artisanal_catches.t_maturity ON artisanal_catches.t_maturity.id = artisanal_catches.obs_fish.t_maturity "
        . "LEFT JOIN artisanal_catches.t_status ON artisanal_catches.t_status.id = artisanal_catches.obs_fish.t_status "
        . "LEFT JOIN artisanal_catches.t_action ON artisanal_catches.t_action.id = artisanal_catches.obs_fish.t_action "
        . "WHERE obs_fish.id_maree = ".$_SESSION['filter']['f_id_maree']." "
        . "ORDER BY date_d DESC OFFSET $start LIMIT $step";
      }
    } else {
      $query = "SELECT count(obs_fish.id) FROM artisanal_catches.obs_fish";
      $pnum = pg_fetch_row(pg_query($query))[0];

      $query = "SELECT DISTINCT ON (id_maree) obs_fish.id, obs_fish.datetime::date, obs_fish.username, date_d, "
      . "concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.obs_name, obs_maree.id_pirogue, "
      . "obs_fish.id_maree, obs_fish.comments  "
      . "FROM artisanal_catches.obs_fish "
      . "LEFT JOIN fishery.species ON obs_fish.id_species = fishery.species.id "
      . "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_fish.id_maree "
      . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
      . "ORDER BY obs_fish.id_maree, obs_fish.datetime::date DESC OFFSET $start LIMIT $step";
    }

    //    print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

      $query = "SELECT n_lot, perc, wgt, fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species "
      . " FROM artisanal_catches.obs_fish "
      . "LEFT JOIN fishery.species ON obs_fish.id_species = fishery.species.id "
      . "WHERE id_maree = '$results[7]'";

      $l_query = pg_query($query);

      //print $query;
      $nrow = pg_num_rows($l_query)+1;

      print "<tr align=\"center\"><td rowspan=$nrow>";
      if(right_write($_SESSION['username'],3,2)) {
        print "<a href=\"./view_catches_obs_fish.php?source=$source&table=$table&action=edit&id_maree=$results[7]\">Modifier</a><br/>"
        . "<a href=\"./view_catches_obs_fish.php?source=$source&table=$table&action=delete&id_maree=$results[7]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
      }
      print "</td>";

      print "<td nowrap rowspan=$nrow>$results[1]<br/>$results[2]</td><td rowspan=$nrow>$results[5]</td><td nowrap rowspan=$nrow><a href=\"./view_obs_maree.php?id=$results[7]&source=obs_catches&table=maree\">$results[3]<br/>$results[4]</a></td>";
      print "<td rowspan=$nrow>$results[8]</td></tr>";

      while ($results_l = pg_fetch_row($l_query)) {
        print "<tr><td>".formatSpeciesFAO($results_l[4],$results_l[5],$results_l[6],$results_l[7],$results_l[8])."</td>"
        . "<td>$results_l[0]</td><td>$results_l[1]</td><td>$results_l[2]</td></tr>";
      }


      //fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.specie



    }
    print "</tr>";

    print "</table>";

    pages($start,$step,$pnum,'./view_catches_obs_fish.php?source=obs_catches&table=fish&action=show&f_id_maree='.$_SESSION['filter']['f_id_maree'].'&f_observateur='.$_SESSION['filter']['f_observateur']);

    $controllo = 1;

  } else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id_maree = $_GET['id_maree'];

    $q_id = "SELECT * FROM artisanal_catches.obs_fish WHERE id_maree = '$id_maree' ";
    //print $q_id;
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    $comments = $results[8];
    $id_maree_orig = $id_maree;
    ?>

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">

      <b>Maree</b>
      <br/>
      <select name="id_maree">
        <?php
        $result = pg_query("SELECT obs_maree.id, concat(obs_maree.immatriculation, pirogue.immatriculation), date_d FROM artisanal_catches.obs_maree LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue ORDER BY date_d");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[3]) {
            print "<option value=\"$row[0]\" selected>".$row[1]." / ".$row[2]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]." / ".$row[2]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>

      <?php
      $r_id = pg_query($q_id);
      while($results = pg_fetch_row($r_id)) {

        print "<div class=\"capture_$results[0]\"><fieldset class=\"border\">
        <legend>D&eacute;tails Capture</legend>
        <b>Espece</b>
        <br/>
        <select id=\"species\" name=\"id_species[]\" class=\"chosen-select\">";

        $result = pg_query("SELECT DISTINCT id, family, genus, species, francaise FROM fishery.species ORDER BY francaise, species");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[4]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesCommon($row[4],$row[1],$row[2],$row[3])."</option>";
          } else {
            print "<option value=\"$row[0]\">".formatSpeciesCommon($row[4],$row[1],$row[2],$row[3])."</option>";
          }
        }

        print "</select>
        <br/>
        <br/>
        <b>Numero du contenant</b>
        <br/>
        <input type=\"text\" size=\"5\" name=\"n_lot[]\" value=\"$results[5]\" />
        <br/>
        <br/>
        <b>Pourcentage du contenant</b>
        <br/>
        <input type=\"text\" size=\"5\" name=\"perc[]\" value=\"$results[6]\" />
        <br/>
        <br/>
        <b>Poids total contenant</b> [kg]
        <br/>
        <input type=\"text\" size=\"5\" name=\"wgt[]\" value=\"$results[7]\" />
        <br/>
        <br/>
        <button type=\"button\" onclick=\"removeDivCaptureID('$results[0]')\">Supprimer Capture</button>
        </fieldset>
        <br/>
        </div>
        ";
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
      $result = pg_query("SELECT DISTINCT id, family, genus, species, francaise FROM fishery.species ORDER BY francaise, species");
      while($row = pg_fetch_row($result)) {
        print "<option value=\"$row[0]\">".formatSpeciesCommon($row[4],$row[1],$row[2],$row[3])."</option>";
      }
      ?>
      </select>
      <br/>
      <br/>
      <b>Numero du contenant</b>
      <br/>
      <input type="text" size="5" name="n_lot[]" />
      <br/>
      <br/>
      <b>Pourcentage du contenant</b>
      <br/>
      <input type="text" size="5" name="perc[]" />
      <br/>
      <br/>
      <b>Poids total contenant</b> [kg]
      <br/>
      <input type="text" size="5" name="wgt[]" />
      <br/>
      <br/>
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
      <button type="button" onclick="appendDivCapture()">Ajouter Capture</button>
      <button type="button" onclick="removeDivCaptureLast()">Supprimer Capture</button>
      <br/>
      <br/>
      <b>Commentaires</b>
      <br/>
      <textarea cols=30 rows=3 name="comments"><?php echo $comments;?></textarea>
      <br/>
      <br/>

      <input type="hidden" value="<?php echo $id_maree_orig;?>" name="id_maree_orig"/>
      <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>
    <br/>

    <?php

  }  else if ($_GET['action'] == 'delete') {
    $id_maree = $_GET['id_maree'];

    $query = "DELETE FROM artisanal_catches.obs_fish WHERE id_maree = '$id_maree'";
    if(!pg_query($query)) {
      msg_queryerror();
    }

    header("Location: ".$_SESSION['http_host']."/artisanal/catches/view_catches_obs_fish.php?source=$source&table=$table&action=show");

    $controllo = 1;

  }

  if ($_POST['submit'] == "Enregistrer") {

    # id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, LT, wgt, t_status, t_action, photo_data, comments
    $id_maree = $_POST['id_maree'];
    $id_maree_orig = $_POST['id_maree_orig'];
    $username = $_SESSION['username'];
    $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);

    // easire to erase all and re-input all catches

    $query = "DELETE FROM artisanal_catches.obs_fish WHERE id_maree = '$id_maree_orig'";

    if(!pg_query($query)) {
      msg_queryerror();
    }

    for($i = 0; $i < sizeof($_POST['id_species']); $i++) {
      $id_species = $_POST['id_species'][$i];
      $wgt = htmlspecialchars($_POST['wgt'][$i],ENT_QUOTES);
      $perc = htmlspecialchars($_POST['perc'][$i],ENT_QUOTES);
      $n_lot = htmlspecialchars($_POST['n_lot'][$i],ENT_QUOTES);

      $query = "INSERT INTO artisanal_catches.obs_fish "
      . "(username, id_maree, id_species, wgt, n_lot, perc, comments) "
      . "VALUES ('$username', '$id_maree', '$id_species', '$wgt', '$n_lot', '$perc', '$comments');";

      $query = str_replace('\'-- \'', 'NULL', $query);
      $query = str_replace('\'\'', 'NULL', $query);
    //  print $query;

      if(!pg_query($query)) {
        echo "<p>".$query,"</p>";
        msg_queryerror();
        foot();
        die();
      }

    }

    header("Location: ".$_SESSION['http_host']."/artisanal/catches/view_catches_obs_fish.php?source=$source&table=$table&action=show");


  }

  foot();
