<?php
require("../../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'autorisation';

top();

if(right_read($_SESSION['username'],5)) {
    ?>
<h2>Autorisations de P&ecirc;che Artisanale Maritime</h2>

<div style="float: left; width:70%; border: 0px solid black;">
    <table id="results">
    <tr>
    <td>1 - Propri&eacute;taires</td>
    <td><a href="view_licenses_owner.php?source=autorisation&table=owner&action=show"><i class="material-icons">search</i>Voir</a></td>
    <td><?php if(right_write($_SESSION['username'],2,2)) {print '<a href="input_licenses.php?source=autorisation&table=owner"><i class="material-icons">create</i>Saisir</a>';} ?></td>
    <!-- <td><?php if(right_write($_SESSION['username'],2,2)) {print '<a href="../maintenance/duplicate_licenses_owner.php?source=autorisation&table=owner&action=show"><i class="material-icons">build</i>Manutention</a>';} ?></td> -->
    <td><a href="download_licenses.php?source=autorisation&table=owner"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
    <td>2 - Pirogues</td>
    <td><a href="view_licenses_pirogue.php?source=autorisation&table=pirogue&action=show"><i class="material-icons">search</i>Voir</a></td>
    <td><?php if(right_write($_SESSION['username'],2,2)) {print '<a href="input_licenses.php?source=autorisation&table=pirogue"><i class="material-icons">create</i>Saisir</a>';} ?></td>
    <td><a href="download_licenses.php?source=autorisation&table=pirogue"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
    <td>3 - Autorisations de P&ecirc;che</td>
    <td><a href="view_licenses_license.php?source=autorisation&table=licenses&action=show"><i class="material-icons">search</i>Voir</a></td>
    <td><?php if(right_write($_SESSION['username'],2,2)) {print '<a href="input_licenses.php?source=autorisation&table=licenses"><i class="material-icons">create</i>Saisir</a>';} ?></td>
<!--    <td><a href="download_form.php?source=autorisation&table=licenses"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    -->
            <td nowrap>
                <form method="post" action="./download_licenses_year.php" enctype="multipart/form-data">
                <select name="year">
                    <option value="extract(year from date_v)">Tous ann&eacute;es</option>
                    <?php
                    $query = "SELECT DISTINCT extract(year from date_v) FROM artisanal.license WHERE date_v IS NOT NULL ORDER BY extract(year from date_v)";
                    $r_query = pg_query($query);
                    while ($results = pg_fetch_row($r_query)) {
                        print "<option value = $results[0]>$results[0]</option>";
                    }
                    ?>
                </select>
                    <input type="hidden" name="table" value="licenses" />
                    <button name="format" value="CSV" class="link">
                    <i class="material-icons">file_download</i>T&eacute;l&eacute;charger
                    </button>
                </form>

            </td>
    </tr>
    <tr>
    <td>4 - P&ecirc;cheurs</td>
    <td><a href="view_licenses_fisherman.php?source=autorisation&table=fisherman&action=show"><i class="material-icons">search</i>Voir</a></td>
    <td><?php if(right_write($_SESSION['username'],2,2)) {print '<a href="input_licenses.php?source=autorisation&table=fisherman"><i class="material-icons">create</i>Saisir</a>';} ?></td>
    <!-- <td><?php if(right_write($_SESSION['username'],2,2)) {print '<a href="maintain_licenses_fisherman.php?source=autorisation&table=fisherman&action=show"><i class="material-icons">build</i>Manutention</a>';} ?></td> -->
    <td><a href="download_licenses.php?source=autorisation&table=fisherman"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
    <td>5 - Cartes de p&ecirc;cheur</td>
    <td><a href="view_licenses_carte.php?source=autorisation&table=carte&action=show"><i class="material-icons">search</i>Voir</a></td>
    <td><?php if(right_write($_SESSION['username'],2,2)) {print '<a href="input_licenses.php?source=autorisation&table=carte"><i class="material-icons">create</i>Saisir</a>';} ?></td>
    <td><a href="download_licenses.php?source=autorisation&table=carte"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>

    <?php if(right_write($_SESSION['username'],2,1)) {
    ?>
    <tr>
    <td colspan=4>
      <a href="view_licenses_validate.php?source=autorisation&table=validate&action=show"><i class="material-icons">assignment_turned_in</i><b>Validation Autorisations de Peche</b></a>
    </td>
    </tr>
    <?php
    }
    ?>

    </table>
    <!-- <?php if(right_write($_SESSION['username'],2,1)) {
        ?>
    <br/>
    <a href="view_licenses_validate.php?source=autorisation&table=validate&action=show"><i class="material-icons">assignment_turned_in</i><b>Valider Authorisations de Peche</b></a>
    <br/>
    <!-- <a href="../maintenance/maintenance_t_table.php?source=table"><i class="material-icons">build</i><b>Modifier Tables</b></a>
    <br/>
    <!--<a href="view_licenses_owner.php?source=autorisation&table=owner&action=show"><i class="material-icons">local_printshop</i><b>Emprimer Authorisations de Peche</b></a>-->
    <?php
    }
    ?>

    <br/>
    <br/>
    </div>
    <div style="float: right;">
    <img src="../img/licenses_scheme.png"/>
    </div>

    <?php
} else {
    msg_noaccess();
}


foot();
