<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'artisanal';
$_SESSION['where'][1] = 'autorisation';

$username = $_SESSION['username'];
top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_t_nationality'] = $_POST['f_t_nationality'];
$_SESSION['filter']['s_fish_name'] = str_replace('\'','',$_POST['s_fish_name']);
$_SESSION['filter']['s_fish_id'] = str_replace('\'','',$_POST['s_fish_id']);

if ($_GET['f_t_nationality'] != "") {$_SESSION['filter']['f_t_nationality'] = $_GET['f_t_nationality'];}
if ($_GET['s_fish_name'] != "") {$_SESSION['filter']['s_fish_name'] = $_GET['s_fish_name'];}
if ($_GET['s_fish_id'] != "") {$_SESSION['filter']['s_fish_id'] = $_GET['s_fish_id'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $start = $_GET['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>

    <form method="post" action="<?php echo $self;?>?source=license&table=fisherman&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Nom p&ecirc;cheur</b></td><td><b>Num&eacute;ro de pi&egrave;ce d'identit&eacute;</b></td><td><b>Nationalit&eacute;</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="s_fish_name" value="<?php echo $_SESSION['filter']['s_fish_name']?>"/>
    </td>
    <td>
    <input type="text" size="20" name="s_fish_id" value="<?php echo $_SESSION['filter']['s_fish_id']?>"/>
    </td>
    <td>
    <select name="f_t_nationality">
        <option value="t_nationality" selected="selected">Tous</option>
        <?php
        $result = pg_query("SELECT id, nationality FROM artisanal.t_nationality ORDER BY nationality");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $_SESSION['filter']['f_t_nationality']) {
                print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
            } else {
                print "<option value=\"$row[0]\">".$row[1]."</option>";
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
    <td><b>Nom et Pr&eacute;nom</b></td>
    <td><b>Date de naissance</b></td>
    <td><b>&Eacute;pouses et enfants</b></td>
    <td><b>Type, num&eacute;ro et validit&eacute; pi&egrave;ce d'identit&eacute;</b></td>
    <td><b>Domicile</b></td>
    <td><b>Nationalit&eacute;</b></td>
    <td><b>Num&eacute;ro de t&eacute;l&eacute;phone</b></td>
    <td><b>Commentaires</b></td>
    <td nowrap><b># Carte</b></td>
    <td nowrap><b># Infractions</b></td>
    <td><b>Photo</b></td>
    </tr>

    <?php

    # username, t_site, first_name, last_name, bday, t_nationality, t_card, idcard, telephone, address, photo_path
    if ($_SESSION['filter']['s_fish_name'] != "" OR $_SESSION['filter']['s_fish_id'] != "" OR $_SESSION['filter']['f_t_nationality'] != "") {

        $_SESSION['start'] = 0;

        $query = "SELECT count(fisherman.id) FROM artisanal.fisherman "
        . "WHERE t_nationality=".$_SESSION['filter']['f_t_nationality']." ";
        $pnum = pg_fetch_row(pg_query($query))[0];

        if ($_SESSION['filter']['s_fish_name'] != "" OR $_SESSION['filter']['s_fish_id'] != "") {
            $query = "SELECT fisherman.id, datetime::date, username, first_name, last_name, bday, wives, children, t_card.card, idcard , ycard, address, t_nationality.nationality, telephone, photo_data, comments, "
            . " coalesce(similarity(artisanal.fisherman.first_name, '".$_SESSION['filter']['s_fish_name']."'),0) + "
            . " coalesce(similarity(artisanal.fisherman.last_name, '".$_SESSION['filter']['s_fish_name']."'),0) + "
            . " coalesce(similarity(artisanal.fisherman.idcard, '".$_SESSION['filter']['s_fish_id']."'),0) AS score"
            . " FROM artisanal.fisherman "
            . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.fisherman.t_card "
            . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.fisherman.t_nationality "
            . "WHERE t_nationality=".$_SESSION['filter']['f_t_nationality']." "
            . "ORDER BY score DESC OFFSET $start LIMIT $step";
        } else {
            $query = "SELECT fisherman.id, datetime::date, username, first_name, last_name, bday, wives, children, t_card.card, idcard , ycard, address, t_nationality.nationality, telephone, photo_data, comments "
            . " FROM artisanal.fisherman "
            . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.fisherman.t_card "
            . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.fisherman.t_nationality "
            . "WHERE t_nationality=".$_SESSION['filter']['f_t_nationality']." "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
        }
    } else {
        $query = "SELECT count(fisherman.id) FROM artisanal.fisherman";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT fisherman.id, datetime::date, username, first_name, last_name, bday, wives, children, t_card.card, idcard , ycard, address, t_nationality.nationality, telephone, photo_data, comments "
            . " FROM artisanal.fisherman "
            . "LEFT JOIN artisanal.t_card ON artisanal.t_card.id = artisanal.fisherman.t_card "
            . "LEFT JOIN artisanal.t_nationality ON artisanal.t_nationality.id = artisanal.fisherman.t_nationality "
            . "ORDER BY datetime DESC OFFSET $start LIMIT $step";
    }

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        # infractions
        $query = "SELECT count(infraction.id) FROM artisanal.pirogue "
                . "LEFT JOIN infraction.infraction ON artisanal.pirogue.id = infraction.infraction.id_pirogue "
                . "WHERE id_fisherman_1 = '$results[0]' "
                . "OR id_fisherman_2 = '$results[0]' "
                . "OR id_fisherman_3 = '$results[0]' "
                . "OR id_fisherman_4 = '$results[0]' ";

        $results_i = pg_fetch_array(pg_query($query));

        # carte
        $query = "SELECT count(carte.id) FROM artisanal.carte "
                . "WHERE id_fisherman = '$results[0]'";

        $results_c = pg_fetch_array(pg_query($query));

        print "<tr align=\"center\"><td>"
        . "<a href=\"./view_fisherman.php?id=$results[0]\">Voir</a><br/>";
        if(right_write($_SESSION['username'],2,2)) {
            print "<a href=\"./view_licenses_fisherman.php?source=$source&table=$table&action=edit&id=$results[0]\">Modifier</a><br/>"
            . "<a href=\"./view_licenses_fisherman.php?source=$source&table=$table&action=delete&id=$results[0]\" onclick=\"return confirm('Voulez-vous vraiment supprimer l\'enregistrement?')\">Effacer</a>";
        }
        print "</td>";

        if ($results[14] == '') {
            $photo_data_bool = 'Non';
        } else {
            $photo_data_bool = 'Oui';
        }

        print "<td nowrap>$results[1]<br/>$results[2]</td><td>".strtoupper($results[4])."<br/>".ucfirst($results[3])."</td><td nowrap>$results[5]</td><td>$results[6]<br/>$results[7]</td>"
        . "<td>$results[8]<br/>$results[9]<br/>$results[10]</td><td>$results[11]</td><td>$results[12]</td><td>$results[13]</td><td>$results[15]</td>"
        . "<td>$results_c[0]</td><td>".$results_i[0]."</td><td>$photo_data_bool</td>";

    }
    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_licenses_fisherman.php?source=license&table=fisherman&action=show&s_fish_name='.$_SESSION['filter']['s_fish_name'].'&s_fish_id='.$_SESSION['filter']['s_fish_id'].'&f_t_nationality='.$_SESSION['filter']['f_t_nationality']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT * FROM artisanal.fisherman WHERE id = '$id' ORDER BY datetime DESC";

    //print $q_id;

    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    if ($results[14] != "") {

?>
    <table width="100%">
    <tr>
    <td style="width: 60%">
    <div>
    <img id="cropper" src="<?php print "image.php?id=$id&table=artisanal.fisherman&photo_data=photo_data";?>" alt="Picture">
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
          aspectRatio: 0.78,
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

            $.ajax('<?php echo $GLOBALS['path']; ?>/artisanal/upload.php?table=fisherman&id=<?php echo $_GET['id']; ?>', {
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

    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <!--<b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>-->
    <b>Pr&eacute;nom</b>
    <br/>
    <input type="text" size="20" name="first_name" value="<?php echo $results[3]; ?>" />
    <br/>
    <br/>
    <b>Nom</b>
    <br/>
    <input type="text" size="20" name="last_name" value="<?php echo $results[4]; ?>" />
    <br/>
    <br/>
    <b>Date de naissance</b>
    <br/>
    <input type="date" size="20" name="bday" value="<?php echo $results[5]; ?>" />
    <br/>
    <br/>
    <b>Numero d'&eacute;pouses</b>
    <br/>
    <input type="text" size="3" name="wives"  value="<?php echo $results[6];?>" />
    <br/>
    <br/>
    <b>Numero d'enfants</b>
    <br/>
    <input type="text" size="3" name="children"  value="<?php echo $results[7];?>" />
    <br/>
    <br/>
    <b>Domicile</b>
    <br/>
    <textarea name="address" rows="2" cols="50"><?php echo $results[11];?></textarea><br/>
    <br/>
    <b>Nationalit&eacute;</b>
    <br/>
    <select name="t_nationality">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_nationality ORDER BY nationality");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[12]) {
          if ($row[2] != 't') {
            print "<option value=\"$row[0]\" disabled selected>".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\" selected>".$row[1]."</option>";
          }
            //print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        } else {
            if ($row[2] != 't') {
              print "<option value=\"$row[0]\" disabled>".$row[1]."</option>";
            } else {
              print "<option value=\"$row[0]\">".$row[1]."</option>";
            }
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <table>
    <tr><td style="font-size:1em;padding:0px"><b>Type de pi&egrave;ce d'identit&eacute;</b></td><td><b>Num&eacute;ro</b></td><td><b>Ann&eacute;e d'expiration</b></td></tr>
    <tr><td>
    <select name="t_card">
    <?php
    $result = pg_query("SELECT * FROM artisanal.t_card ORDER BY card");
    while($row = pg_fetch_row($result)) {
      if ($row[0] == $results[8]) {
        if ($row[2] == 'f') {
          print "<option value=\"$row[0]\" disabled selected>".$row[1]."</option>";
        } else {
          print "<option value=\"$row[0]\" selected>".$row[1]."</option>";
        }
          //print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
      } else {
          if ($row[2] == 'f') {
            print "<option value=\"$row[0]\" disabled>".$row[1]."</option>";
          } else {
            print "<option value=\"$row[0]\">".$row[1]."</option>";
          }
      }
    }
    ?>
    </select>
    </td><td>
    <input type="text" size="30" name="idcard" value="<?php echo $results[9];?>" />
    </td><td>
    <input type="date" size="30" name="ycard" value="<?php echo $results[10];?>" />
    </td></tr>
    </table>
    <br/>
    <b>Num&eacute;ro de t&eacute;l&eacute;phone</b>
    <br/>
    <input type="text" size="20" name="telephone" value="<?php echo $results[13];?>" />
    <br/>
    <br/>
    <b>Ajouter/replacer la photo</b> (format jpg/png/gif, max 500 KB)
    <br/>
    <input type="file" size="40" name="photo_file" onchange="ValidateSize(this)"/>
    <br/>
    <br/>
    <b>Commentaires</b>
    <br/>
    <textarea name="comments" rows="4" cols="50"><?php echo $results[15];?></textarea>
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    <input type="hidden" value="<?php echo $results[0]; ?>" name="id"/>
    </form>
    <br/>
    <br/>

    <?php

}  else if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM artisanal.fisherman WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/view_licenses_fisherman.php?source=$source&table=fisherman&action=show");
    }
    $controllo = 1;

}



if ($_POST['delete'] == "Supprimer") {

    $id = $_POST['id'];

    $query = "UPDATE artisanal.fisherman SET "
    . " photo_data = NULL "
    . " WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/view_licenses_fisherman.php?source=$source&table=$table&action=edit&id=$id");
    }

}

if ($_POST['rotate'] == "Rotate") {
    $id = $_POST['id'];

    $query = "SELECT photo_data FROM artisanal.fisherman "
    . " WHERE id = '$id'";

    $photo_data = pg_fetch_row(pg_query($query))[0];
    $photo_data = rotate_photo($photo_data);

    $query = "UPDATE artisanal.fisherman SET "
    . " photo_data = '$photo_data' "
    . " WHERE id = '$id'";


    if(!pg_query($query)) {
        msg_queryerror();
    } else {
        header("Location: ".$_SESSION['http_host']."/artisanal/view_licenses_fisherman.php?source=$source&table=$table&action=edit&id=$id");
    }
}

if ($_POST['submit'] == "Enregistrer") {

    if ($_FILES['photo_file']['tmp_name'] != "") {
        $photo_data = upload_photo($_FILES['photo_file']['tmp_name']);
    } else {
        $photo_data = pg_fetch_result(pg_query("SELECT photo_data FROM artisanal.fisherman WHERE id = '{".$_POST['id']."}'"),'photo_data');
    }

    # username, t_site, first_name, last_name, bday, t_nationality, t_card, idcard, telephone, address, photo_path
    $first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES);
    $last_name = htmlspecialchars($_POST['last_name'], ENT_QUOTES);
    $bday = $_POST['bday'];
    $wives = htmlspecialchars($_POST['wives'], ENT_QUOTES);
    $children = htmlspecialchars($_POST['children'], ENT_QUOTES);
    $t_nationality = $_POST['t_nationality'];
    $t_card = $_POST['t_card'];
    $idcard = $_POST['idcard'];
    $ycard = $_POST['ycard'];
    $telephone = htmlspecialchars($_POST['telephone'], ENT_QUOTES);
    $address = htmlspecialchars($_POST['address'], ENT_QUOTES);


    if ($_POST['new_old']) {
        # check duplicates
        $query = "SELECT id FROM artisanal.fisherman WHERE last_name='$last_name' AND first_name='$first_name'";
        $nrows = pg_num_rows(pg_query($query));

        //print $query;

        if ($nrows > 0) {
            top();
            print "<p>Un p&ecirc;cheur avec pr&eacute;nom <b>".$first_name."</b> et nom <b>".$last_name."</b> existe d&eacute;j&egrave;.<br/>";
            print "<button type=\"button\" onClick=\"goBack()\">Retourner</button></p>";
            foot();
            die();
        }

        $query = "INSERT INTO artisanal.fisherman "
            . "(username, first_name, last_name, bday, wives, children, address, t_nationality, t_card, "
            . "idcard, ycard, telephone, photo_data) "
            . " VALUES ('$username', '$first_name', '$last_name', '$bday', '$wives', '$children', '$address', '$t_nationality', '$t_card', '$ycard', "
            . "'$idcard', '$telephone', '$photo_data')";

    } else {
        $query = "UPDATE artisanal.fisherman SET "
        . "datetime = now(), "
        . "username = '$username', "
        . " first_name = '".$first_name."', last_name = '".$last_name."', bday = '".$bday."', "
        . " wives = '".$wives."', children = '".$children."', t_nationality = '".$t_nationality."', t_card = '".$t_card."', "
        . " idcard = '".$idcard."', ycard = '".$ycard."', telephone = '".$telephone."', address = '".$address."', photo_data = '$photo_data' "
        . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);

    //print $query;

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/artisanal/view_licenses_fisherman.php?source=$source&table=fisherman&action=show");
    }
}

foot();
