<?php
require("../../top_foot.inc.php");


$_SESSION['where'][0] = 'maintenance';
$_SESSION['where'][1] = 'maintenance';

top();

if ($_GET['source'] != "") $_SESSION['path'][0] = $_GET['source'];
if ($_GET['table'] != "") $_SESSION['path'][1] = $_GET['table'];

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if(right_write($_SESSION['username'],2,1)) {

    print "<h2>".label2name($source)."</h2>";
    ?>
      <h3>Autorisations Peche</h3>
      <table id="results">
      <tr>
      <td>Cooperatives</td>
      <td><a href="view_maintenance_t_table.php?source=table&table=artisanal.t_coop&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="input_maintenance_t_table.php?source=table&table=artisanal.t_coop"><i class="material-icons">create</i>Saisir</a></td>
      </tr>
      <tr>
      <td>Strata</td>
      <td><a href="view_maintenance_t_table.php?source=table&table=artisanal.t_strata&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="input_maintenance_t_table.php?source=table&table=artisanal.t_strata"><i class="material-icons">create</i>Saisir</a></td>
      </tr>
      <tr>
      <td>Debarcadere obbligatoire</td>
      <td><a href="view_maintenance_t_table.php?source=table&table=artisanal.t_site_obb&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="input_maintenance_t_table.php?source=table&table=artisanal.t_site_obb"><i class="material-icons">create</i>Saisir</a></td>
      </tr>
      <tr>
      <td>Documents identification Pecheur Proprietaire</td>
      <td><a href="view_maintenance_t_table.php?source=table&table=artisanal.t_card&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="input_maintenance_t_table.php?source=table&table=artisanal.t_card"><i class="material-icons">create</i>Saisir</a></td>
      </tr>
      <tr>
      <td>Nationalite Pecheur Proprietaire</td>
      <td><a href="view_maintenance_t_table.php?source=table&table=artisanal.t_nationality&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="input_maintenance_t_table.php?source=table&table=artisanal.t_nationality"><i class="material-icons">create</i>Saisir</a></td>
      </tr>
      <tr>
      <td>Engines de peche</td>
      <td><a href="view_maintenance_t_table.php?source=table&table=artisanal.t_gear&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="input_maintenance_t_table.php?source=table&table=artisanal.t_gear"><i class="material-icons">create</i>Saisir</a></td>
      </tr>
      <tr>
      <td>Especes Ciblees</td>
      <td><a href="view_maintenance_t_table.php?source=table&table=artisanal.t_license&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="input_maintenance_t_table.php?source=table&table=artisanal.t_license"><i class="material-icons">create</i>Saisir</a></td>
      </tr>
    </table>
<?php
}

if(right_write($_SESSION['username'],4,1)) {
?>
<h3>Infractions Peche</h3>
    <table id="results">
      <tr>
      <td>Organization</td>
      <td><a href="view_maintenance_t_table.php?source=table&table=infraction.t_org&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="input_maintenance_t_table.php?source=table&table=infraction.t_org"><i class="material-icons">create</i>Saisir</a></td>
      </tr>
      </table>
    <br/>
    <br/>

    <?php
} else {
    msg_noaccess();
}


foot();
