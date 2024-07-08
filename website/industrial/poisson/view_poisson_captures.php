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
$_SESSION['filter']['f_id_species'] = $_POST['f_id_species'];
$_SESSION['filter']['f_id_maree'] = $_POST['f_id_maree'];

if ($_GET['f_id_navire'] != "") {$_SESSION['filter']['f_id_navire'] = $_GET['f_id_navire'];}
if ($_GET['f_id_species'] != "") {$_SESSION['filter']['f_id_species'] = $_GET['f_id_species'];}
if ($_GET['f_id_maree'] != "") {$_SESSION['filter']['f_id_maree'] = $_GET['f_id_maree'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {

  print "<h2>".label2name($source)." ".label2name($table)."</h2>";

  if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

  $start = $_SESSION['start'];

  if (!isset($start) OR $start<0) $start = 0;

  $step = 50;

  ?>
  <form method="post" action="<?php echo $self;?>?source=poisson&table=capture&action=show" enctype="multipart/form-data">
    <fieldset>

      <table id="no-border">
        <tr>
          <td><b>Navire</b></td>
          <td><b>Species</b></td>
          <td><b>Maree</b></td>
        </tr>
        <tr>
          <td>
            <select name="f_id_navire">
              <option value="id_navire" selected="selected">Tous</option>
              <?php
              $result = pg_query("SELECT DISTINCT maree.id_navire, navire FROM poisson.capture LEFT JOIN poisson.maree ON poisson.maree.id = poisson.capture.id_maree LEFT JOIN vms.navire ON vms.navire.id = poisson.maree.id_navire ORDER BY navire");
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
            <select name="f_id_species" class="chosen-select" >
                <option value="id_species" selected="selected">Tous</option>
                <?php
                $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM poisson.capture LEFT JOIN fishery.species ON poisson.capture.id_species = fishery.species.id ORDER BY fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species");
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

          <td>
            <select name="f_id_maree" class="chosen-select" >
                <option value="id_maree" selected="selected">Tous</option>
                <?php
                $result = pg_query("SELECT DISTINCT maree.id, navire, date_d, date_r FROM poisson.maree LEFT JOIN vms.navire ON navire.id = maree.id_navire ORDER BY date_d");
                while($row = pg_fetch_row($result)) {
                    if ("'".$row[0]."'" == $_SESSION['filter']['f_id_maree']) {
                        print "<option value=\"'$row[0]'\" selected=\"selected\"><b>$row[1]: $row[2] / $row[3]</option>";
                    } else {
                        print "<option value=\"'$row[0]'\">$row[1]: $row[2] / $row[3]</option>";
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

  <table>
    <tr align="center"><td></td>
      <td><b>Date & Utilisateur</b></td>
      <td><b>Maree</b></td>
      <td><b>Espece</b></td>
      <td><b>Taille</b></td>
      <td><b>Poids [kg]</b></td>
    </tr>

    <?php

    // fetch data

    if ($_SESSION['filter']['f_id_navire'] != "" OR $_SESSION['filter']['f_id_species'] != "" OR $_SESSION['filter']['f_id_maree'] != "") {


      $_SESSION['start'] = 0;

      $query = "SELECT count(capture.id) FROM poisson.capture "
      . "LEFT JOIN poisson.maree ON poisson.maree.id = poisson.capture.id_maree "
      . "WHERE id_species=".$_SESSION['filter']['f_id_species']." "
      . "AND maree.id_navire=".$_SESSION['filter']['f_id_navire']." "
      . "AND maree.id=".$_SESSION['filter']['f_id_maree']." ";

      $pnum = pg_fetch_row(pg_query($query))[0];

      $query = "SELECT capture.id, capture.username, capture.datetime::date, id_maree, navire, date_d, date_r, id_species, taille, poids, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM poisson.capture "
      . "LEFT JOIN poisson.maree ON poisson.maree.id = poisson.capture.id_maree "
      . "LEFT JOIN vms.navire ON vms.navire.id = poisson.maree.id_navire "
      . "LEFT JOIN poisson.t_taille ON poisson.t_taille.id = poisson.capture.t_taille "
      . "LEFT JOIN fishery.species ON poisson.capture.id_species = fishery.species.id "
      . "WHERE maree.id_navire=".$_SESSION['filter']['f_id_navire']." "
      . "AND id_species=".$_SESSION['filter']['f_id_species']." "
      . "AND id_maree=".$_SESSION['filter']['f_id_maree']." "
      . "ORDER BY capture.datetime DESC OFFSET $start LIMIT $step";

    } else {
      $query = "SELECT count(capture.id) FROM poisson.capture";
      $pnum = pg_fetch_row(pg_query($query))[0];

      $query = "SELECT capture.id, capture.username, capture.datetime::date, id_maree, navire, date_d, date_r, id_species, taille, poids, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM poisson.capture  "
      . "LEFT JOIN poisson.maree ON poisson.maree.id = poisson.capture.id_maree "
      . "LEFT JOIN vms.navire ON vms.navire.id = poisson.maree.id_navire "
      . "LEFT JOIN poisson.t_taille ON poisson.t_taille.id = poisson.capture.t_taille "
      . "LEFT JOIN fishery.species ON poisson.capture.id_species = fishery.species.id "
      . "ORDER BY capture.datetime DESC OFFSET $start LIMIT $step";
    }

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

      print "<tr align=\"center\">";

      print "<td>"
      . "<a href=\"./view_poisson_captures.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
      . "<a href=\"./view_poisson_captures.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>"
      . "</td>";
      print "<td>$results[1]<br/>$results[2]</td><td nowrap><a href=\"../view_navire.php?source=vms&id=$results[3]\">$results[4]</a></br>$results[5]<br/>$results[6]</td>"
      . "<td>".formatSpeciesFAO($results[10],$results[11],$results[12],$results[13],$results[14])."</td><td>$results[8]</td><td>$results[9]</td>";

    }
    print "</tr>";
    print "</table>";
    pages($start,$step,$pnum,'./view_poisson_captures.php?source=poisson&table=captures&action=show&f_id_navire='.$_SESSION['filter']['f_id_navire'].'&f_id_species='.$_SESSION['filter']['f_id_species'].'&f_id_maree='.$_SESSION['filter']['f_id_maree']);

    $controllo = 1;

  } else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT capture.id, capture.datetime, capture.username, id_navire, date_d, id_maree, id_species, poids, t_taille FROM poisson.capture "
    . "LEFT JOIN poisson.maree ON poisson.maree.id = poisson.capture.id_maree "
    . "LEFT JOIN vms.navire ON vms.navire.id = poisson.maree.id_navire "
    . "WHERE capture.id = '$id'";

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
      <select id="id_navire" name="id_navire" onchange="menu_pop_maree('id_navire','date_d','poisson.maree')">
        <option  value="none">Veuillez choisir ci-dessus</option>
        <?php
        $result = pg_query("SELECT DISTINCT id_navire, navire FROM poisson.maree "
        . "LEFT JOIN vms.navire ON poisson.maree.id_navire = vms.navire.id "
        . "WHERE navire IS NOT NULL "
        . "ORDER BY navire");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[3]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Date maree</b>
      <br/>
      <select id="date_d" name="id_maree">
        <option  value="none">Veuillez choisir ci-dessus</option>
        <?php
        $result = pg_query("SELECT DISTINCT id, date_d, date_r FROM poisson.maree WHERE id_navire = '$results[3]' ORDER BY date_d");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[5]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]." - ".$row[2]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]." - ".$row[2]."</option>";
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
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species  FROM fishery.species WHERE fishery.species.category LIKE '%industrial%' ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[6]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
          } else {
            print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Taille</b>
      <br/>
      <select name="t_taille">
        <option  value="">Aucun</option>
        <?php
        $result = pg_query("SELECT id, taille FROM poisson.t_taille ORDER BY id");
        while($row = pg_fetch_row($result)) {
          if ($row[0] == $results[8]) {
            print "<option value=\"$row[0]\" selected=\"selected\">$row[1]</option>";
          } else {
            print "<option value=\"$row[0]\">$row[1]</option>";
          }
        }
        ?>
      </select>
      <br/>
      <br/>
      <b>Poids</b> [kg]
      <br/>
      <input type="text" size="5" name="poids" value="<?php echo $results[7];?>" />
      <br/>
      <br/>
      <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
      <input type="submit" value="Enregistrer" name="submit"/>
    </form>

    <br/>
    <br/>


    <?php

  }  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM poisson.capture WHERE id = '$id'";

    if(!pg_query($query)) {
      msg_queryerror();
      //        print $query;
    } else {
      header("Location: ".$_SESSION['http_host']."/industrial/poisson/view_poisson_captures.php?source=$source&table=capture&action=show");
    }
    $controllo = 1;
  }

  if ($_POST['submit'] == "Enregistrer") {

    $id_navire = $_POST['id_navire'];
    $id_maree = $_POST['id_maree'];

    $id_species = $_POST['id_species'];
    $t_taille = $_POST['t_taille'];
    $poids = htmlspecialchars($_POST['poids'],ENT_QUOTES);

    if ($_POST['new_old']) {
      $query = "INSERT INTO poisson.capture "
      . "(username, datetime, id_maree, id_species, t_taille, poids) "
      . "VALUES ('$username', now(), '$id_maree', '$id_species', '$t_taille', '$poids')";
    } else {
      $query = "UPDATE poisson.capture SET "
      . "username = '$username', datetime = now(), "
      . "id_maree = '$id_maree', id_species = '$id_species', t_taille = '$t_taille', poids = '$poids'"
      . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
      print $query;
      msg_queryerror();
    } else {
      //        print $query;
      header("Location: ".$_SESSION['http_host']."/industrial/poisson/view_poisson_captures.php?source=$source&table=lance&action=show");
    }


  }

  foot();
