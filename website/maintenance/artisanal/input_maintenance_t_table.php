<?php
require("../../top_foot.inc.php");

// if ($_SERVER['HTTPS'] == "on") {
//   $path = "https://".$_SERVER['HTTP_HOST'];
// } else {
//   $path = "http://".$_SERVER['HTTP_HOST'];
// }

$_SESSION['where'][0] = 'maintenance';
$_SESSION['where'][1] = 'maintenance';

$username = $_SESSION['username'];

top();

if ($_GET['source'] != "") $_SESSION['path'][0] = $_GET['source'];
if ($_GET['table'] != "") $_SESSION['path'][1] = $_GET['table'];

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

print "<h2>".label2name($source)." ".label2name($table)."</h2>";

$self = filter_input(INPUT_SERVER, 'PHP_SELF');
$radice = filter_input(INPUT_SERVER, 'HTTP_HOST');

//se submit = go!
if ($_POST['submit'] == "Enregistrer") {
  $table = $_POST['table'];
  # find largest ID
  $query = "SELECT max(id) FROM $table";
  $id = pg_fetch_row(pg_query($query))[0];

  if ($table == 'artisanal.t_site_obb') {
    # username, t_site, first_name, last_name, bday, t_nationality, t_card, idcard, telephone, address, photo_path
    $site = htmlspecialchars($_POST['site'], ENT_QUOTES);
    $strata = htmlspecialchars($_POST['strata'], ENT_QUOTES);
    $region = htmlspecialchars($_POST['region'], ENT_QUOTES);
    $code = htmlspecialchars($_POST['code'], ENT_QUOTES);
    $lat = htmlspecialchars($_POST['lat'], ENT_QUOTES);
    $lon = htmlspecialchars($_POST['lon'], ENT_QUOTES);
    $active = $_POST['active'];
    $point = "'POINT($lon $lat)'";

    if ($lon == '' OR $lat == '') {
      $query = "INSERT INTO $table "
      . "(id, site, strata, region, code, location, active) VALUES ($id+1, '$site', '$strata', '$region', '$code', ST_GeomFromText($point,4326), '$active')";
    } else {
      $query = "INSERT INTO $table "
      . "(id, site, strata, region, code, active) VALUES ($id+1, '$site', '$strata', '$region', '$code', '$active')";
    }

  } else {

    # username, t_site, first_name, last_name, bday, t_nationality, t_card, idcard, telephone, address, photo_path
    $valeur = htmlspecialchars($_POST['valeur'], ENT_QUOTES);
    $active = $_POST['active'];

    $query = "INSERT INTO $table "
    . "(id, ". explode('.t_',$table)[1] .", active) VALUES ($id+1, '$valeur', '$active')";

  }

  $query = str_replace('\'\'', 'NULL', $query);

  print $query;

  if(!pg_query($query)) {
    //        print $query;
    msg_queryerror();
  } else {
    #print $query;
    header("Location: ".$_SESSION['http_host']."/artisanal/maintenance/view_maintenance_t_table.php?source=$source&table=$table&action=show");
  }

  $controllo = 1;
}

if (!$controllo) {

  if ($table == 'artisanal.t_site_obb') {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
      <input type="hidden" value="<?php print $table ?>" name="table"/>
      <table><tr><td><b>Actif</b></td><td><b>Site</b></td><td><b>Strata</b></td><td><b>Region</b></td><td><b>Code</b></td><td><b>Latitude</b></td><td><b>Longitude</b></td></tr>
        <tr>
          <td><select name="active"><option value="true" selected>Oui</option><option value="false" selected>Non</option></select></td>
          <td><input type="text" size="20" name="site" /></td>
          <td><input type="text" size="10" name="strata" /></td>
          <td><input type="text" size="10" name="region" /></td>
          <td><input type="text" size="10" name="code" /></td>
          <td><input type="text" size="10" name="lat" /></td>
          <td><input type="text" size="10" name="lon" /></td>
        </tr>
        <tr>
          <td>
            <input type="submit" value="Enregistrer" name="submit"/>
          </td>
        </tr>
      </table>
    </form>
    <?php
  } else {
    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
      <input type="hidden" value="<?php print $table ?>" name="table"/>
      <table><tr><td><b>Actif</b></td><td><b>Valeur</b></td><td></td></tr>
        <tr>
          <td><select name="active"><option value="true" selected>Oui</option><option value="false" selected>Non</option></select></td>
          <td><input type="text" size="20" name="valeur" /></td>
          <td><input type="submit" value="Enregistrer" name="submit"/></td>
        </tr>
      </table>
    </form>

    <?php
  }
}


foot();
