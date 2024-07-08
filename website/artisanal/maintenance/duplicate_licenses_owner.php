<?php
require("../../top_foot.inc.php");

$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'maintenance';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $start = $_GET['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>

    <table id="small">
    <tr align="center">
    <td></td>
    <td></td>
    <td><b>Date & Utilisateur</b></td>
    <td><b>Nom et Pr&eacute;nom</b></td>
    <td><b>Date de naissance</b></td>
    <td><b>&Eacute;pouses et enfants</b></td>
    <td><b>Type, num&eacute;ro et validit&eacute; pi&egrave;ce d'identit&eacute;</b></td>
    <td><b>Domicile</b></td>
    <td><b>Nationalit&eacute;</b></td>
    <td><b>Num&eacute;ro de t&eacute;l&eacute;phone</b></td>
    <td><b>Commentaires</b></td>
    <td nowrap><b># Pirogues</b></td>
    <td nowrap><b># Infractions</b></td>
    <td><b>Photo</b></td>
    </tr>

    <?php

        $_SESSION['start'] = 0;

            $query = "SELECT count(owner.id) FROM artisanal.owner ";
            $pnum = pg_fetch_row(pg_query($query))[0];

            $query = "SELECT similarity(o1.first_name, o2.first_name)*0.4 + similarity(o1.last_name, o2.last_name)*0.6 + "
            . " (similarity(o1.first_name, o2.last_name)*0.5 + similarity(o1.last_name, o2.first_name)*0.5)*0.3 AS sim, "
            . "o1.id, o1.datetime::date, o1.username, o1.first_name, o1.last_name, o1.bday, o1.wives, o1.children, c1.card, o1.idcard, "
            . "o1.ycard, o1.address, n1.nationality, o1.telephone, o1.photo_data, o1.comments, "
            . "o2.id, o2.datetime::date, o2.username, o2.first_name, o2.last_name, o2.bday, o2.wives, o2.children, c2.card, o2.idcard, "
            . "o2.ycard, o2.address, n2.nationality, o2.telephone, o2.photo_data, o2.comments"
            . " FROM artisanal.owner o1"
            . " JOIN artisanal.owner o2 ON o1.first_name <> o2.first_name AND o1.first_name % o2.first_name"
            . " LEFT JOIN artisanal.t_card c1 ON c1.id = o1.t_card "
            . " LEFT JOIN artisanal.t_nationality n1 ON n1.id = o1.t_nationality "
            . " LEFT JOIN artisanal.t_card c2 ON c2.id = o2.t_card "
            . " LEFT JOIN artisanal.t_nationality n2 ON n2.id = o2.t_nationality "
            . " ORDER BY sim DESC OFFSET $start LIMIT $step;";

    //print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {
        # infractions
        $query = "SELECT count(infraction.id) FROM artisanal.pirogue "
                . "LEFT JOIN infraction.infraction ON artisanal.pirogue.id = infraction.infraction.id_pirogue "
                . "WHERE infraction.id_owner = '$results[1]'";

        $results_i_1 = pg_fetch_array(pg_query($query));

        # pirogues
        $query = "SELECT count(id) FROM artisanal.pirogue "
        . " WHERE id_owner = '$results[1]'";

        $results_p_1 = pg_fetch_array(pg_query($query));


        $query = "SELECT count(infraction.id) FROM artisanal.pirogue "
                . "LEFT JOIN infraction.infraction ON artisanal.pirogue.id = infraction.infraction.id_pirogue "
                . "WHERE infraction.id_owner = '$results[17]'";

        $results_i_2 = pg_fetch_array(pg_query($query));

        # pirogues
        $query = "SELECT count(id) FROM artisanal.pirogue "
        . " WHERE id_owner = '$results[17]'";

        $results_p_2 = pg_fetch_array(pg_query($query));

        print "<tr align=\"center\">";

          print "<td rowspan=2 ><a href=\"./duplicate_licenses_owner.php?source=$source&table=$table&action=merge&id_1=$results[1]&id_2=$results[17]\">Merge</a></td>";

        if ($results[15] == '') {
            $photo_data_bool = 'Non';
        } else {
            $photo_data_bool = 'Oui';
        }

        print "<td nowrap><a href=\"../administration/view_owner.php?id=$results[1]\">Voir</a></td><td nowrap>$results[2]<br/>$results[3]</td><td>".strtoupper($results[5])."<br/>".ucfirst($results[4])."</td><td nowrap>$results[6]</td><td>$results[7]<br/>$results[8]</td>"
        . "<td>$results[9]<br/>$results[10]<br/>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[14]</td><td>$results[16]</td>"
        . "<td>$results_p_1[0]</td><td>".$results_i_1[0]."</td><td>$photo_data_bool</td></tr>";


          print "<tr align=\"center\">";

          if ($results[31] == '') {
              $photo_data_bool = 'Non';
          } else {
              $photo_data_bool = 'Oui';
          }

          print "<td nowrap><a href=\"../administration/view_owner.php?id=$results[17]\">Voir</a></td><td nowrap>$results[18]<br/>$results[19]</td><td>".strtoupper($results[21])."<br/>".ucfirst($results[20])."</td><td nowrap>$results[22]</td><td>$results[23]<br/>$results[24]</td>"
          . "<td>$results[25]<br/>$results[26]<br/>$results[27]</td><td>$results[28]</td><td>$results[29]</td><td>$results[30]</td><td>$results[32]</td>"
          . "<td>$results_p_2[0]</td><td>".$results_i_2[0]."</td><td>$photo_data_bool</td>";

          print "</tr>";


    }

    print "</table>";

    pages($start,$step,$pnum,'./duplicate_licenses_owner.php?source=autorisation&table=owner&action=show');

    $controllo = 1;

} else if ($_GET['action'] == 'merge') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id_1 = $_GET['id_1'];
    $id_2 = $_GET['id_2'];

    //find record info by ID
    $q_id = "SELECT owner.id, datetime::date, username, first_name, last_name, bday, wives, children, t_card.card, idcard, "
    . "ycard, address, t_nationality.nationality, telephone, photo_data, comments "
    . "FROM artisanal.owner "
    . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.owner.t_card "
    . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.owner.t_nationality "
    . "WHERE owner.id = '$id_1'";

    //print $q_id;
    $r_id = pg_query($q_id);
    $results_1 = pg_fetch_row($r_id);

    $q_id = "SELECT owner.id, datetime::date, username, first_name, last_name, bday, wives, children, t_card.card, idcard, "
    . "ycard, address, t_nationality.nationality, telephone, photo_data, comments "
    . "FROM artisanal.owner "
    . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.owner.t_card "
    . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.owner.t_nationality "
    . "WHERE owner.id = '$id_2'";

    //print $q_id;
    $r_id = pg_query($q_id);
    $results_2 = pg_fetch_row($r_id);

    ?>

    <form method="post" action="<?php echo $self;?>?source=autorisation&table=owner&action=show" enctype="multipart/form-data">
    <h3>D&eacute;tails Proprietaire</h3>
    <!--
    MERGE CRITERIA
    - take present over absent
    - take most recent when both present
    - add pirogues
    - add infractions
    -->
    <table id="small">
    <tr align="center">
    <td><b>Date & Utilisateur</b></td>
    <td><b>Nom et Pr&eacute;nom</b></td>
    <td><b>Date de naissance</b></td>
    <td><b>&Eacute;pouses et enfants</b></td>
    <td><b>Type, num&eacute;ro et validit&eacute; pi&egrave;ce d'identit&eacute;</b></td>
    <td><b>Domicile</b></td>
    <td><b>Nationalit&eacute;</b></td>
    <td><b>Num&eacute;ro de t&eacute;l&eacute;phone</b></td>
    <td><b>Commentaires</b></td>
    <td nowrap><b># Pirogues</b></td>
    <td nowrap><b># Infractions</b></td>
    <td><b>Photo</b></td>
    </tr>

    <?php

    print "<tr align=\"center\">";

    $results = [];

    for ($i = 0; $i <= count($results_1); $i++) {
      //print $results_1[$i]." - ".$results_2[$i]."<br/>";

      if ($results_1[$i] == '' OR $results_2[$i] == '') {
        $results[$i] = $results_1[$i].$results_2[$i];
      }
      if ($results_1[$i] != '' AND $results_2[$i] != '') {

        if($results_1[1] >= $results_2[1]) {
          $results[$i] = $results_1[$i];
        } else {
          $results[$i] = $results_2[$i];
        }
      }
    }

    if ($results[14] == '') {
        $photo_data_bool = 'Non';
    } else {
        $photo_data_bool = 'Oui';
    }

    print "<td nowrap>$results[1]<br/>$results[2]</td><td>".strtoupper($results[4])."<br/>".ucfirst($results[3])."</td><td nowrap>$results[5]</td><td>$results[6]<br/>$results[7]</td>"
    . "<td>$results[8]<br/>$results[9]<br/>$results[10]</td><td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[15]</td>"
    . "<td>$results_p[0]</td><td>".$results_i[0]."</td><td>$photo_data_bool</td>";
    ?>
    </tr>
    </table>

    <table>
      <h3>Pirogues</h3>
      <table>
      <tr align="center">
      <td></td>
      <td><b>Date et utilizateur</b></td>
      <td><b>Nom de la pirogue</b></td>
      <td><b>Immatriculation</b></td>
      <td><b>Type pirogue</b></td>
      <td><b>Longueur</b></td>
      </tr>

      <?php
      $query = "SELECT DISTINCT pirogue.id, datetime::date, username, name, immatriculation, t_pirogue.pirogue, length, id_owner"
                  . " FROM artisanal.pirogue "
                  . "LEFT JOIN artisanal.t_pirogue ON artisanal.t_pirogue.id = artisanal.pirogue.t_pirogue "
                  . "WHERE id_owner = '$results_1[0]' OR id_owner = '$results_2[0]'"
                  . "ORDER BY datetime DESC";

      $r_query_p = pg_query($query);

      while ($results = pg_fetch_row($r_query_p)) {
                      print "<tr align=\"center\"><td><a href=\"../administration/view_pirogue.php?id=$results[0]&source=license&table=pirogue\">Voir</a></td><td>$results[2]<br/>$results[1]</td><td>$results[3]</td><td>$results[4]</td><td>$results[5]</td><td>$results[6]</td>";
      }

      print "</table><br/>";
      ?>


      <h3>Infractions</h3>
      <table id='small'>
      <tr align="center">
      <td></td>
      <td><b>Date infraction</b></td>
      <td><b>Type infraction</b></td>
      <td><b>Nom du p&ecirc;cheur 1</b></td>
      <td><b>Nom du p&ecirc;cheur 2</b></td>
      <td><b>Nom du p&ecirc;cheur 3</b></td>
      <td><b>Nom du p&ecirc;cheur 4</b></td>
      <td><b>Organisation</b></td>
      <td><b>Objets saisis</b></td>
      <td><b>Montant de l'infraction</b></td>
      <td><b>Montant Pay&eacute;</b></td>
      <td><b>Commentaires</b></td>
      </tr>

    <?php
    $query = "SELECT infraction.id, infraction.username, id_pv, date_i, t_org.org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, "
            . "owner_t_card, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_t_nationality_1, "
            . "fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, "
            . "fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, "
            . "fish_idcard_4, fish_t_card_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, "
            . "amount, payment, n_dep, n_cdc, n_lib, comments FROM infraction.infraction "
            . "LEFT JOIN infraction.t_org ON infraction.infraction.t_org = infraction.t_org.id "
        . "WHERE id_owner = '$results_1[0]' OR id_owner = '$results_2[0]' AND infraction.id IS NOT NULL";

        $r_query_i = pg_query($query);


        //print $query;

            while ($results = pg_fetch_row($r_query_i)) {

                $query_i = "SELECT t_infraction.infraction FROM infraction.infractions LEFT JOIN infraction.t_infraction ON infraction.t_infraction.id = infraction.infractions.t_infraction WHERE id_infraction ='$results[0]'";
                $rquery_i = pg_query($query_i);
                $nrows = pg_num_rows($rquery_i);

                //print $query_i;

                $results_i = pg_fetch_row($rquery_i);

                print "<tr align=center><td rowspan=$nrows><a href=\"../infractions/view_infraction.php?id=$results[0]&source=infractions&table=infraction\">Voir</a></td>";
                print "<td rowspan=$nrows nowrap>$results[3]</td>";

                print "<td>$results_i[0]</td>";

                if ($results[16] != '') {
                    print "<td rowspan=$nrows><a href=\"../administration/view_fisherman.php?id=$results[16]\">".strtoupper($results[18])."<br/>".ucfirst($results[17])."</a></td>";
                } else {
                    print "<td rowspan=$nrows>".strtoupper($results[18])."<br/>".ucfirst($results[17])."</a></td>";
                }
                if ($results[23] != '') {
                    print "<td rowspan=$nrows><a href=\"../administration/view_fisherman.php?id=$results[23]\">".strtoupper($results[25])."<br/>".ucfirst($results[24])."</a></td>";
                } else {
                    print "<td rowspan=$nrows>".strtoupper($results[25])."<br/>".ucfirst($results[24])."</a></td>";
                }
                if ($results[30] != '') {
                    print "<td rowspan=$nrows><a href=\"../administration/view_fisherman.php?id=$results[30]\">".strtoupper($results[32])."<br/>".ucfirst($results[31])."</a></td>";
                } else {
                    print "<td rowspan=$nrows>".strtoupper($results[32])."<br/>".ucfirst($results[31])."</a></td>";
                }
                if ($results[37] != '') {
                    print "<td rowspan=$nrows><a href=\"../administration/view_fisherman.php?id=$results[37]\">".strtoupper($results[39])."<br/>".ucfirst($results[38])."</a></td>";
                } else {
                    print "<td rowspan=$nrows>".strtoupper($results[39])."<br/>".ucfirst($results[38])."</a></td>";
                }
                print "<td rowspan=$nrows>$results[4]</td><td rowspan=$nrows>";
                if ($results[44] !='') {print "[Pirogue: $results[44]]<br/>";}
                if ($results[45] !='') {print "[Moteur: $results[45]]<br/>";}
                if ($results[46] !='') {print "[Filet: $results[46]]<br/>";}
                if ($results[47] !='') {print "[Documents: $results[47]]<br/>";}
                print "</td><td rowspan=$nrows>$results[48]</td><td rowspan=$nrows>$results[49]</td><td rowspan=$nrows>$results[54]</td></tr>";

                while($results_i = pg_fetch_row($rquery_i)) {
                    print "<tr align=center><td>$results_i[0]</td></tr>";
                }

            }


        print "</table><br/>";
        ?>


    <input type="submit" value="Enregistrer" name="submit"/>
    <input type="hidden" value="<?php echo $id_1; ?>" name="id_1"/>
    <input type="hidden" value="<?php echo $id_2; ?>" name="id_2"/>
    </form>
    <br/>
    <br/>

    <?php

}

if ($_POST['submit'] == "Enregistrer") {
  $id_1 = $_POST['id_1'];
  $id_2 = $_POST['id_2'];

  //find record info by ID
  $q_id = "SELECT * FROM artisanal.owner WHERE owner.id = '$id_1'";
  print $q_id."<br/>";
  $r_id = pg_query($q_id);
  $results_1 = pg_fetch_row($r_id);

  $q_id = "SELECT * FROM artisanal.owner WHERE owner.id = '$id_2'";
  print $q_id."<br/>";
  $r_id = pg_query($q_id);
  $results_2 = pg_fetch_row($r_id);

  $results = [];

  for ($i = 0; $i <= count($results_1); $i++) {
    //print $results_1[$i]." - ".$results_2[$i]."<br/>";

    if ($results_1[$i] == '' OR $results_2[$i] == '') {
      $results[$i] = $results_1[$i].$results_2[$i];
    }
    if ($results_1[$i] != '' AND $results_2[$i] != '') {

      if($results_1[1] >= $results_2[1]) {
        $results[$i] = $results_1[$i];
      } else {
        $results[$i] = $results_2[$i];
      }
    }
  }

  # DELETE old OWNERS, both view_records_capture
  # INSERT new OWNER with updated records

  $query = "DELETE FROM artisanal.owner WHERE id = '$id_1' OR id = '$id_2'";
  pg_query($query);
  //print $query."<br/>";

  $query = "INSERT INTO artisanal.owner VALUES (";

  for ($i = 0; $i < count($results)-2; $i++) {
    $query = $query."'".$results[$i]."', ";
  }
  $query = $query."'".$results[count($results)]."'); ";

  # UPDATE all records with new records in OWNER, PIROGUES (infractions are linked to pirogue)

  $query = str_replace('\'\'', 'NULL', $query);
  pg_query($query);
  //print $query."<br/>";


  # UPDATE pirogues and Infractions
  $query = "UPDATE artisanal.pirogue SET id_owner = '$results[0]' WHERE id_owner = '$id_1' OR id_owner = '$id_2'; ";
  pg_query($query);
  //print $query."<br/>";


  header("Location: ".$_SESSION['http_host']."/artisanal/maintenance/duplicate_licenses_owner.php?source=$source&table=owners&action=show");

}

foot();
