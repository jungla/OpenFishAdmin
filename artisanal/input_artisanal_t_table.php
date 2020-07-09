<?php
require("../top_foot.inc.php");

if ($_SERVER['HTTPS'] == "on") {
    $path = "https://".$_SERVER['HTTP_HOST'];
} else {
    $path = "http://".$_SERVER['HTTP_HOST'];
}

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'autorisation';

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

  # username, t_site, first_name, last_name, bday, t_nationality, t_card, idcard, telephone, address, photo_path
  $valeur = htmlspecialchars($_POST['valeur'], ENT_QUOTES);
  $table = $_POST['table'];
  $active = $_POST['active'];

  # find largest ID
  $query = "SELECT max(id) FROM artisanal.$table";
  $id = pg_fetch_row(pg_query($query))[0];

  $query = "INSERT INTO artisanal.$table "
  . "(id, ". substr($table,2) .", active) VALUES ($id+1, '$valeur', '$active')";

  $query = str_replace('\'\'', 'NULL', $query);

  print $query;

  if(!pg_query($query)) {
//        print $query;
      msg_queryerror();
  } else {
      #print $query;
      header("Location: ".$_SESSION['http_host']."/artisanal/view_artisanal_t_table.php?source=$source&table=$table&action=show");
  }

    $controllo = 1;
}

if (!$controllo) {
  ?>
  <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
  <input type="hidden" value="<?php print $table ?>" name="table"/>
  <table><tr><td><b>Actif</b></td><td><b>Valeur</b></td><td></td></tr>
  <tr>
  <td><select name="active"><option value="true" selected>Oui</option><option value="false" selected>Non</option></select></td>
  <td><input type="text" size="20" name="valeur" /></td>
  <td>
  <input type="submit" value="Enregistrer" name="submit"/>
  </td>
</tr></table>
  </form>

    <?php
    }


foot();
