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
              <option value="obs_mammals.id_maree" selected="selected">Tous</option>
              <?php
              $result = pg_query("SELECT DISTINCT obs_mammals.id_maree, date_d, concat(pirogue.immatriculation, obs_maree.immatriculation) FROM artisanal_catches.obs_mammals "
              . "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_mammals.id_maree "
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
    <!-- id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, LT, LA, wgt, t_status, t_action, photo_data, comments -->
    <tr align="center"><td></td>
      <td><b>Date & Utilisateur</b></td>
      <td><b>Observateur</b></td>
      <td><b>Maree</b></td>
      <td><b>Action Peche</b></td>
      <td><b>Espece</b></td>
      <td><b>Sex et maturite</b></td>
      <td><b>LT</b> [cm]</td>
      <td><b>Poids</b> [kg]</td>
      <td><b>Etat et Action</b></td>
      <td><b>Photo</b></td>
      <td><b>Commentaires</b></td>
    </tr>

    <?php

    if ($_SESSION['filter']['f_id_maree'] != "" OR $_SESSION['filter']['f_id_species'] != "" OR $_SESSION['filter']['f_observateur'] != "") {

      //id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, LT, LA, wgt, t_status, t_action, photo_data, comments

      $_SESSION['start'] = 0;

      $query = "SELECT count(DISTINCT obs_mammals.id) FROM artisanal_catches.obs_mammals "
      . "WHERE obs_mammals.id_maree = ".$_SESSION['filter']['f_id_maree']." ";

      $pnum = pg_fetch_row(pg_query($query))[0];

      if ($_SESSION['filter']['f_immatriculation'] != "" OR $_SESSION['filter']['f_observateur'] != "") {
        $query = "SELECT obs_mammals.id, obs_mammals.datetime::date, obs_mammals.username, date_d, "
        . "concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.obs_name, obs_maree.id_pirogue, "
        . "wpt, date_a, time_a, obs_mammals.id_maree, id_action, fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, "
        . " t_sex.sex, t_maturity.maturity, LT, wgt, t_status.status, t_action.action, photo_data, obs_mammals.comments,  "
        . " coalesce(similarity(obs_name, '".$_SESSION['filter']['f_observateur']."'),0) AS score "
        . "FROM artisanal_catches.obs_mammals "
        . "LEFT JOIN fishery.species ON obs_mammals.id_species = fishery.species.id "
        . "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_mammals.id_maree "
        . "LEFT JOIN artisanal_catches.obs_action ON artisanal_catches.obs_action.id = artisanal_catches.obs_mammals.id_action "
        . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
        . "LEFT JOIN artisanal_catches.t_sex ON artisanal_catches.t_sex.id = artisanal_catches.obs_mammals.t_sex "
        . "LEFT JOIN artisanal_catches.t_maturity ON artisanal_catches.t_maturity.id = artisanal_catches.obs_mammals.t_maturity "
        . "LEFT JOIN artisanal_catches.t_status ON artisanal_catches.t_status.id = artisanal_catches.obs_mammals.t_status "
        . "LEFT JOIN artisanal_catches.t_action ON artisanal_catches.t_action.id = artisanal_catches.obs_mammals.t_action "
        . "WHERE obs_mammals.id_maree = ".$_SESSION['filter']['f_id_maree']." "
        . "ORDER BY score DESC OFFSET $start LIMIT $step";
      } else {
        $query = "SELECT obs_mammals.id, obs_mammals.datetime::date, obs_mammals.username, date_d, "
        . "concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.obs_name, obs_maree.id_pirogue, "
        . "wpt, date_a, time_a, obs_mammals.id_maree, id_action, fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, "
        . " t_sex.sex, t_maturity.maturity, LT, wgt, t_status.status, t_action.action, photo_data, obs_mammals.comments  "
        . "FROM artisanal_catches.obs_mammals "
        . "LEFT JOIN fishery.species ON obs_mammals.id_species = fishery.species.id "
        . "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_mammals.id_maree "
        . "LEFT JOIN artisanal_catches.obs_action ON artisanal_catches.obs_action.id = artisanal_catches.obs_mammals.id_action "
        . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
        . "LEFT JOIN artisanal_catches.t_sex ON artisanal_catches.t_sex.id = artisanal_catches.obs_mammals.t_sex "
        . "LEFT JOIN artisanal_catches.t_maturity ON artisanal_catches.t_maturity.id = artisanal_catches.obs_mammals.t_maturity "
        . "LEFT JOIN artisanal_catches.t_status ON artisanal_catches.t_status.id = artisanal_catches.obs_mammals.t_status "
        . "LEFT JOIN artisanal_catches.t_action ON artisanal_catches.t_action.id = artisanal_catches.obs_mammals.t_action "
        . "WHERE obs_mammals.id_maree = ".$_SESSION['filter']['f_id_maree']." "
        . "ORDER BY date_d DESC OFFSET $start LIMIT $step";
      }
    } else {
      $query = "SELECT count(obs_mammals.id) FROM artisanal_catches.obs_mammals";
      $pnum = pg_fetch_row(pg_query($query))[0];

      $query = "SELECT obs_mammals.id, obs_mammals.datetime::date, obs_mammals.username, date_d, "
      . "concat(pirogue.immatriculation, obs_maree.immatriculation), obs_maree.obs_name, obs_maree.id_pirogue, "
      . "wpt, date_a, time_a, obs_mammals.id_maree, id_action, fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, "
      . " t_sex.sex, t_maturity.maturity, LT, wgt, t_status.status, t_action.action, photo_data, obs_mammals.comments  "
      . "FROM artisanal_catches.obs_mammals "
      . "LEFT JOIN fishery.species ON obs_mammals.id_species = fishery.species.id "
      . "LEFT JOIN artisanal_catches.obs_maree ON artisanal_catches.obs_maree.id = artisanal_catches.obs_mammals.id_maree "
      . "LEFT JOIN artisanal_catches.obs_action ON artisanal_catches.obs_action.id = artisanal_catches.obs_mammals.id_action "
      . "LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue "
      . "LEFT JOIN artisanal_catches.t_sex ON artisanal_catches.t_sex.id = artisanal_catches.obs_mammals.t_sex "
      . "LEFT JOIN artisanal_catches.t_maturity ON artisanal_catches.t_maturity.id = artisanal_catches.obs_mammals.t_maturity "
      . "LEFT JOIN artisanal_catches.t_status ON artisanal_catches.t_status.id = artisanal_catches.obs_mammals.t_status "
      . "LEFT JOIN artisanal_catches.t_action ON artisanal_catches.t_action.id = artisanal_catches.obs_mammals.t_action "
      . "ORDER BY obs_mammals.datetime::date DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

      print "<tr align=\"center\"><td>";
      //. "<a href=\"./view_owner.php?id=$results[0]\">Voir</a><br/>";
      if(right_write($_SESSION['username'],3,2)) {
        print "<a href=\"./view_catches_obs_mammals.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
        . "<a href=\"./view_catches_obs_mammals.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
      }
      print "</td>";

      print "<td nowrap>$results[1]<br/>$results[2]</td><td>$results[5]</td><td nowrap><a href=\"./view_obs_maree.php?id=$results[10]&source=obs_catches&table=maree\">$results[3]<br/>$results[4]</a></td><td nowrap>$results[8]<br/>$results[9]<br/>WPT $results[7]</td>"
      . "<td>".formatSpeciesFAO($results[13],$results[14],$results[15],$results[16],$results[17])."</td>"
      . "<td>$results[18]</br>$results[19]</td><td>$results[20]</td>";

      print "<td>$results[21]</td><td>$results[22]<br/>$results[23]</td>";

      // photo
      //print "<td>$results[25]</td>";

      if ($results[24] != '') {
        print "<td align=\"center\" width=\"30%\"><img class=\"img_frame\" width=\"100%\" src=\"image.php?id=$results[0]&table=artisanal_catches.obs_mammals&photo_data=photo_data\" /></td>";
      } else {
        print "<td>pas de photo</td>";
      }
      print "<td>$results[25]</td>";
    }
    print "</tr>";

    print "</table>";

    pages($start,$step,$pnum,'./view_catches_obs_mammals.php?source=obs_catches&table=actions&action=show&f_id_maree='.$_SESSION['filter']['f_id_maree'].'&f_observateur='.$_SESSION['filter']['f_observateur']);

    $controllo = 1;

  } else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT *, wpt FROM artisanal_catches.obs_mammals LEFT JOIN artisanal_catches.obs_action ON artisanal_catches.obs_action.id = artisanal_catches.obs_mammals.id_action  WHERE obs_mammals.id = '$id'";
    //print $q_id;
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    if ($results[12] != "") {

?>
    <table>
    <tr>
    <td style="width: 60%">
    <div>
    <img id="cropper" src="<?php print "image.php?id=$id&table=artisanal_catches.obs_mammals&photo_data=photo_data";?>" alt="Picture">
    <br/>
    <button onclick="cropper.zoom(0.1);"><i class="material-icons">zoom_in</i></button>
    <button onclick="cropper.zoom(-0.1);"><i class="material-icons">zoom_out</i></button>
    <button onclick="cropper.rotate(-90);"><i class="material-icons">rotate_90_degrees_ccw</i></button>
    <button id="crop"><i class="material-icons">save</i>Enregistrer</button>
    </div>
    </td>
    <td style="vertical-align: top">
    <fieldset class="border">
    <legend>Apercu</legend>
    <div class="preview"></div>
    </fieldset>
    </td>
    </tr>
    </table>
    <script type="text/javascript">

        const image = document.getElementById('cropper');
        const cropper = new Cropper(image, {
          preview: '.preview',
          crop(event) {
            console.log(event.detail.width);
            console.log(event.detail.height);
            console.log(event.detail.rotate);
            console.log(event.detail.scaleX);
            console.log(event.detail.scaleY);
          },
        });

        $('#crop').click(function() {
        // Get a string base 64 data url
        cropper.getCroppedCanvas().toBlob(function (blob) {
            document.getElementById("crop").disabled = true;

            var formData = new FormData();

            formData.append('data_photo', blob);

            console.log(formData.get('data_photo'));

            $.ajax('<?php echo $GLOBALS['path']; ?>/artisanal/catches/upload.php?table=artisanal_catches.obs_mammals&id=<?php echo $_GET['id']; ?>', {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                  console.log('Upload success');
                  location.reload();
                  document.getElementById("crop").disabled = false;
                },
                error: function () {
                  console.log('Upload error');
                }
              });

            });
        });

    </script>
    <?php

        print "<form method=\"post\" action=\"$self\" enctype=\"multipart/form-data\">"
            . "<button class=\"thin\" name=\"delete\" value=\"Supprimer\" ><i class=\"material-icons\">delete</i>Supprimer</button>&nbsp;"
            . "<input type=\"hidden\" name=\"id\" value=\"$id\" />"
            . "</form>"
            . "<br/>";
    }

    ?>

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data" name="form">
      <b>Maree</b>
      <br/>
      <select name="id_maree" id="id_maree" onchange="menu_pop_1('id_maree','wpt','id_maree','wpt','artisanal_catches.obs_action')">
        <?php
        $result = pg_query("SELECT obs_maree.id, concat(obs_maree.immatriculation, pirogue.immatriculation), date_d FROM artisanal_catches.obs_maree LEFT JOIN artisanal.pirogue ON artisanal.pirogue.id = artisanal_catches.obs_maree.id_pirogue ORDER BY date_d");
        while($row = pg_fetch_row($result)) {
          if ($results[3] == $row[0]) {
            print "<option value=\"$row[0]\" selected>".$row[1]." / ".$row[2]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]." / ".$row[2]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Action peche</b>
      <br/>
      <select name="wpt" id="wpt">
        <option  value="none">Veuillez choisir ci-dessus</option>
        <?php
        $result = pg_query("SELECT wpt FROM artisanal_catches.obs_mammals LEFT JOIN artisanal_catches.obs_action ON artisanal_catches.obs_action.id = artisanal_catches.obs_mammals.id_action WHERE obs_mammals.id = '$results[0]' AND obs_mammals.id_maree = '$results[3]' ORDER BY wpt");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[21]) {
            print "<option value=\"$row[0]\" selected>".$row[0]."</option>";
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
      <select name="id_species" class="chosen-select">
      <?php
      $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species  FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
      while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[5]) {
              print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
          } else {
              print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
          }
      }
      ?>
      </select>
      <br/>
      <br/>
      <b>Sex</b>
      <br/>
      <select name="t_sex">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_sex ORDER BY sex");
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
      <b>Maturite</b>
      <br/>
      <select name="t_maturity">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_maturity ORDER BY maturity");
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
      <b>Poids</b> [kg]
      <br/>
      <input type="text" size="10" name="wgt" value="<?php echo $results[9];?>" />
      <br/>
      <br/>
      <b>LT</b> [cm]
      <br/>
      <input type="text" size="10" name="LT" value="<?php echo $results[8];?>" />
      <br/>
      <br/>
      <b>Status</b>
      <br/>
      <select name="t_status">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_status ORDER BY status");
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
      <b>Action</b>
      <br/>
      <select name="t_action">
        <?php
        $result = pg_query("SELECT * FROM artisanal_catches.t_action ORDER BY action");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[11]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Ajouter/replacer la photo</b> (format jpg/png/gif, max 500 KB)
      <br/>
      <input type="file" size="40" name="photo_file" onchange="ValidateSize(this)" />
      <br/>
      <br/>
      <b>Commentaires</b>
      <br/>
      <textarea cols=30 rows=3 name="comments"><?php echo $results[13];?></textarea>
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

  $query = "DELETE FROM artisanal_catches.obs_mammals WHERE id = '$id'";
  if(!pg_query($query)) {
    msg_queryerror();
  }

  header("Location: ".$_SESSION['http_host']."/artisanal/catches/view_catches_obs_mammals.php?source=$source&table=$table&action=show");

  $controllo = 1;

}

if ($_POST['delete'] == "Supprimer") {

    $id = $_POST['id'];

    $query = "UPDATE artisanal_catches.mammals SET "
    . " photo_data = NULL "
    . " WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/catches/view_catches_obs_mammals.php?source=$source&table=$table&action=edit&id=$id");
    }

}

if ($_POST['submit'] == "Enregistrer") {

    if ($_FILES['photo_file']['tmp_name'] != "") {
        $photo_data = upload_photo($_FILES['photo_file']['tmp_name']);
    } else {
        $photo_data = pg_fetch_result(pg_query("SELECT photo_data FROM artisanal_catches.obs_mammals WHERE id = '{".$_POST['id']."}'"), 'photo_data');
    }

  $id = $_POST['id'];
  # id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, LT, wgt, t_status, t_action, photo_data, comments

  $username = $_SESSION['username'];
  $id_maree = $_POST['id_maree'];
  $wpt = htmlspecialchars($_POST['wpt'],ENT_QUOTES);

  $query = "SELECT id FROM artisanal_catches.obs_action WHERE id_maree = '$id_maree' AND wpt = '$wpt'";
  $id_action = pg_fetch_row(pg_query($query))[0];

  $id_species = $_POST['id_species'];

  $t_sex = $_POST['t_sex'];
  $t_maturity = $_POST['t_maturity'];
  $LT = htmlspecialchars($_POST['LT'],ENT_QUOTES);
  $wgt = htmlspecialchars($_POST['wgt'],ENT_QUOTES);
  $t_status = $_POST['t_status'];
  $t_action = $_POST['t_action'];

  $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES);

  $query = "UPDATE artisanal_catches.obs_mammals SET "
  . "username = '$username', datetime = NOW(), id_maree = '$id_maree', id_action = '$id_action', id_species = '$id_species', t_sex = '$t_sex', t_maturity = '$t_maturity', "
  . "LT = '$LT', wgt = '$wgt', t_status = '$t_status', t_action = '$t_action', photo_data = '$photo_data', comments = '$comments' "
  . "WHERE id = '$id'";

  $query = str_replace('\'-- \'', 'NULL', $query);
  $query = str_replace('\'\'', 'NULL', $query);

  if(!pg_query($query)) {
    echo "<p>".$query,"</p>";
    msg_queryerror();
    foot();
    die();
  } else {
    //print $query;
    header("Location: ".$_SESSION['http_host']."/artisanal/catches/view_catches_obs_mammals.php?source=$source&table=$table&action=show");

  }


}

foot();
