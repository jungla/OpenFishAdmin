<?php
require("../top_foot.inc.php");


$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'seiners';

$username = $_SESSION['username'];

top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}

$_SESSION['filter']['f_s_maree'] = $_POST['f_s_maree'];

if ($_GET['f_s_maree'] != "") {$_SESSION['filter']['f_s_maree'] = $_GET['f_s_maree'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];

if ($_GET['action'] == 'show') {
    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    if ($_GET['start'] != "") {$_SESSION['start'] = $_GET['start'];}

    $start = $_SESSION['start'];

    if (!isset($start) OR $start<0) $start = 0;

    $step = 50;

    ?>
    <form method="post" action="<?php echo $self;?>?source=seiners&table=thon_rej_taille&action=show" enctype="multipart/form-data">
    <fieldset>

    <table id="no-border"><tr><td><b>Maree</b></td></tr>
    <tr>
    <td>
    <input type="text" size="20" name="f_s_maree" value="<?php echo $_SESSION['filter']['f_s_maree']?>"/>
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
    <td><b>Route</b></td>
    <td><b>Maree</b></td>
    <td><b># Calee</b></td>
    <td><b>Espece</b></td>
    <td><b>Taille</b></td>
    <td><b>GPS</b></td>
    </tr>

    <?php

    // fetch data

    # id, datetime, username, maree, n_calee, n_route, l_route, id_route, c009, c010, c011, c012, c013, c014, c015, c016, c017, c018, c019, c020, c021, c022, c023, c024, c025, c026, c027, c028, c029, c030, c031, c032, c033, c034, c035, c036, c037, c038, c039, c040, c041, c042, c043, c044, c045, c046, c047, c048, c049, c050, c051, c052, c053, c054, c055, c056, c057, c058, c059, c060, c061, c062, c063, c064, c065, c066, c067, c068, c069, c070, c071, c072, c073, c074, c075, c076, c077, c078, c079, c080, c081, c082, c083, c084, c085, c086, c087, c088, c089, c090, c091, c092, c093, c094, c095, c096, c097, c098, c099, c100, c110, c111, c112, c135, c138, c139, c140, c144, c145, c146, c147, c148, c149, c150, c151, c154, c155, c156, c157, c158, c159, c160, c170 ,


    if ($_SESSION['filter']['f_s_maree'] != "") {

        $_SESSION['start'] = 0;

        $query = "SELECT count(thon_rejete_taille.id) FROM seiners.thon_rejete_taille ";

        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT thon_rejete_taille.datetime::date, *, st_x(route.location), st_y(route.location), fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, route.date, route.time,  "
        . " coalesce(similarity(seiners.thon_rejete_taille.maree, '".$_SESSION['filter']['f_s_maree']."'),0)  AS score"
        . " FROM seiners.thon_rejete_taille "
        . "LEFT JOIN seiners.route ON seiners.route.id = seiners.thon_rejete_taille.id_route "
        . "ORDER BY score DESC OFFSET $start LIMIT $step";


    } else {

        $query = "SELECT count(thon_rejete_taille.id) FROM seiners.thon_rejete_taille";
        $pnum = pg_fetch_row(pg_query($query))[0];

        $query = "SELECT thon_rejete_taille.datetime::date, *, st_x(route.location), st_y(route.location), fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, route.date, route.time  "
        . " FROM seiners.thon_rejete_taille "
        . "LEFT JOIN seiners.route ON seiners.route.id = seiners.thon_rejete_taille.id_route "
        . "LEFT JOIN fishery.species ON fishery.species.id = seiners.thon_rejete_taille.id_species "
        . "ORDER BY thon_rejete_taille.datetime DESC OFFSET $start LIMIT $step";

    }

    #print $query;

    $r_query = pg_query($query);

    while ($results = pg_fetch_row($r_query)) {

        print "<tr align=\"center\">";

        print "<td>";
        if(right_write($_SESSION['username'],5,2)) {
        print "<a href=\"./view_seiners_thon_rej_taille.php?source=$source&table=$table&action=edit&id=$results[1]\">Modifier</a><br/>"
            . "<a href=\"./view_seiners_thon_rej_taille.php?source=$source&table=$table&action=delete&id=$results[1]\">Effacer</a>";
        }
        print "</td>";
        print "<td>$results[0]<br/>$results[3]</td><td><a href=\"./view_route.php?id=$results[9]\">$results[130] $results[133]</a></td><td>$results[4]</td><td>$results[6]</td><td>".formatSpecies($results[155],$results[156],$results[157],$results[158])."</td>"
        . "<td><img src=\"./graph_seiners_thon_rej_taille.php?id=$results[1]\"></td><td><a href=\"view_point.php?X=$results[152]&Y=$results[153]\">".round($results[152],3)."E ".round($results[153],3)."N</td></tr>";
    }
    print "</tr>";
    print "</table>";

    pages($start,$step,$pnum,'./view_seiners_thon_rej_taille.php?source=seiners&table=thon_rej_taille&action=show&f_s_maree='.$_SESSION['filter']['f_s_maree']);

    $controllo = 1;

} else if ($_GET['action'] == 'edit') {

    print "<h2>".label2name($source)." ".label2name($table)."</h2>";

    # id, datetime, username, maree, n_calee, n_route, l_route, id_route, c009, c010, c011, c012, c013, c014, c015, c016, c017, c018, c019, c020, c021, c022, c023, c024, c025, c026, c027, c028, c029, c030, c031, c032, c033, c034, c035, c036, c037, c038, c039, c040, c041, c042, c043, c044, c045, c046, c047, c048, c049, c050, c051, c052, c053, c054, c055, c056, c057, c058, c059, c060, c061, c062, c063, c064, c065, c066, c067, c068, c069, c070, c071, c072, c073, c074, c075, c076, c077, c078, c079, c080, c081, c082, c083, c084, c085, c086, c087, c088, c089, c090, c091, c092, c093, c094, c095, c096, c097, c098, c099, c100, c110, c111, c112, c135, c138, c139, c140, c144, c145, c146, c147, c148, c149, c150, c151, c154, c155, c156, c157, c158, c159, c160, c170 ,
    $labels = ['9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59', '60', '61', '62', '63', '64', '65', '66', '67', '68', '69', '70', '71', '72', '73', '74', '75', '76', '77', '78', '79', '80', '81', '82', '83', '84', '85', '86', '87', '88', '89', '90', '91', '92', '93', '94', '95', '96', '97', '98', '99', '100', '110', '111', '112', '135', '138', '139', '140', '144', '145', '146', '147', '148', '149', '150', '151', '154', '155', '156', '157', '158', '159', '160', '170'];
    $labels_c = ['009', '010', '011', '012', '013', '014', '015', '016', '017', '018', '019', '020', '021', '022', '023', '024', '025', '026', '027', '028', '029', '030', '031', '032', '033', '034', '035', '036', '037', '038', '039', '040', '041', '042', '043', '044', '045', '046', '047', '048', '049', '050', '051', '052', '053', '054', '055', '056', '057', '058', '059', '060', '061', '062', '063', '064', '065', '066', '067', '068', '069', '070', '071', '072', '073', '074', '075', '076', '077', '078', '079', '080', '081', '082', '083', '084', '085', '086', '087', '088', '089', '090', '091', '092', '093', '094', '095', '096', '097', '098', '099', '100', '110', '111', '112', '135', '138', '139', '140', '144', '145', '146', '147', '148', '149', '150', '151', '154', '155', '156', '157', '158', '159', '160', '170'];

    $id = $_GET['id'];

    //find record info by ID
    $q_id = "SELECT *, route.maree, route.date, route.time, "
            . "fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species "
            . "FROM seiners.thon_rejete_taille "
            . "LEFT JOIN fishery.species ON fishery.species.id = seiners.thon_rejete_taille.id_species "
            . "LEFT JOIN seiners.route ON seiners.route.id = seiners.thon_rejete_taille.id_route "
            . "WHERE thon_rejete_taille.id = '$id'";
    #print $q_id;
    $r_id = pg_query($q_id);
    $results = pg_fetch_row($r_id);

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Ajouter comme nouvel enregistrement</b> <input type="checkbox" name="new_old">
    <br/>
    <br/>
    <b>Maree</b>
    <br/>
    <select id="maree" name="maree" onchange="menu_pop_1('maree','date','maree','date','seiners.route')">
    <option value="none">Aucun</option>
    <?php
    $result = pg_query("SELECT DISTINCT maree FROM seiners.route ORDER BY maree");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[3]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <br/>
    <b>Route</b>
    <br/>
    <select id="date" name="date" onchange="menu_pop_2('maree','date','time','maree','date','time','seiners.route')">
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT date FROM seiners.route  WHERE maree = '$results[3]' ORDER BY date");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[152]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>
    <br/>
    <select id="time" name="time" >
    <option  value="none">Veuillez choisir ci-dessus</option>
    <?php
    $result = pg_query("SELECT DISTINCT time FROM seiners.route WHERE maree = '$results[3]' AND date = '$results[152]' ORDER BY time");
    while($row = pg_fetch_row($result)) {
        if ($row[0] == $results[153]) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[0]."</option>";
        } else {
            print "<option value=\"$row[0]\">".$row[0]."</option>";
        }
    }
    ?>
    </select>

    <br/>
    <br/>
    <b># Calee</b>
    <br/>
    <input type="text" size="10" name="n_calee" value="<?php echo $results[5]; ?>"/>
    <br/>
    <br/>
    <b>Espece</b>
    <br/>
    <select name="id_species" class="chosen-select" >
        <?php
        $result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        #$result = pg_query("SELECT DISTINCT fishery.species.id, fishery.species.FAO, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species FROM fishery.species  JOIN seiners.thon_rejete_taille ON fishery.species.id = seiners.thon_rejete_taille.id_species ORDER BY fishery.species.family, fishery.species.genus, fishery.species.species");
        while($row = pg_fetch_row($result)) {
            if ($row[0] == $results[154]) {
                print "<option value=\"$row[0]\" selected=\"selected\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            } else {
                print "<option value=\"$row[0]\">".formatSpeciesFAO($row[1],$row[2],$row[3],$row[4],$row[5])."</option>";
            }
        }
    ?>
    </select>
    <br/>
    <br/>
    <b>Numero individu par taille</b>
    <table border="1">
    <?php

    print "<tr align=\"center\"><td><b>9 cm</b><td></tr>";
    print "<tr align=\"center\"><td><input type=\"text\" size=\"3\" name=\"c009\" value=\"".$results[9]."\"><td></tr>";

    for ($i = 1; $i < 12; $i++) {
      print "<tr align='center'>";
      for ($j = 0; $j < 10; $j++) {
          print "<td><b>";
          print $labels[$i*10+$j-9];
          //print $i*10+$j-1;
          print " cm</b></td>";
      }
      print "</tr>";

      print "<tr align='center'>";
      for ($j = 0; $j < 10; $j++) {
          print "<td><input type=\"text\" size=\"3\" name=\"c".$labels_c[$i*10+$j-9]."\" value=\"".$results[$i*10+$j-1]."\"></td>";
        }
      print "</tr>";
    }

    // last 5 entries
    print "<tr align=\"center\"><td><b>$labels[111] cm</b></td><td><b>$labels[112] cm</b></td><td><b>$labels[113] cm</b></td><td><b>$labels[114] cm</b></td></tr>";
    print "<tr align=\"center\">";
    print "<td><input type=\"text\" size=\"3\" name=\"c158\" value=\"".$results[($i-1)*10+$j]."\"></td>";
    print "<td><input type=\"text\" size=\"3\" name=\"c159\" value=\"".$results[($i-1)*10+$j+1]."\"></td>";
    print "<td><input type=\"text\" size=\"3\" name=\"c160\" value=\"".$results[($i-1)*10+$j+2]."\"></td>";
    print "<td><input type=\"text\" size=\"3\" name=\"c170\" value=\"".$results[($i-1)*10+$j+3]."\"></td>";
    print "</tr>";




    // print '<tr align="center">';
    // foreach ($labels as $label) {
    //     print "<td><b>".$label." cm</b></td>";
    // }
    // print "</tr>";
    //
    // print "<tr>";
    // for ($i = 9; $i < count($results)-33; $i++) {
    //     print "<td><input type=\"text\" size=\"3\" name=\"c".$labels_c[$i-9]."\" value=\"".$results[$i]."\"></td>";
    // }
    // print "</tr>";

    ?>
    </table>
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
    $query = "DELETE FROM seiners.thon_rejete_taille WHERE id = '$id'";

    if(!pg_query($query)) {
        msg_queryerror();
//        print $query;
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_seiners_thon_rej_taille.php?source=$source&table=thon_rej_taille&action=show");
    }
    $controllo = 1;

}

if ($_POST['submit'] == "Enregistrer") {

    # id, datetime, username, maree, n_calee, id_route, c009, c010, c011, c012, c013, c014, c015, c016, c017, c018, c019, c020, c021, c022, c023, c024, c025, c026, c027, c028, c029, c030, c031, c032, c033, c034, c035, c036, c037, c038, c039, c040, c041, c042, c043, c044, c045, c046, c047, c048, c049, c050, c051, c052, c053, c054, c055, c056, c057, c058, c059, c060, c061, c062, c063, c064, c065, c066, c067, c068, c069, c070, c071, c072, c073, c074, c075, c076, c077, c078, c079, c080, c081, c082, c083, c084, c085, c086, c087, c088, c089, c090, c091, c092, c093, c094, c095, c096, c097, c098, c099, c100, c110, c111, c112, c135, c138, c139, c140, c144, c145, c146, c147, c148, c149, c150, c151, c154, c155, c156, c157, c158, c159, c160, c170 ,

    $username = $_SESSION['username'];
    $maree = $_POST['maree'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $n_calee = $_POST['n_calee'];
    $id_species = $_POST['id_species'];

    $q_id = "SELECT id FROM seiners.route WHERE maree = '$maree' AND date = '$date' AND time = '$time'";
    $id_route = pg_fetch_row(pg_query($q_id))[0];

    if ($_POST['new_old']) {


    $query = "INSERT INTO seiners.thon_rejete_taille "
            . "(username, datetime, maree, n_calee, id_species, id_route, c009, c010, c011, c012, c013, c014, c015, c016, c017, c018, c019, c020, c021, c022, c023, c024, c025, c026, c027, c028, c029, c030, c031, c032, c033, c034, c035, c036, c037, c038, c039, c040, c041, c042, c043, c044, c045, c046, c047, c048, c049, c050, c051, c052, c053, c054, c055, c056, c057, c058, c059, c060, c061, c062, c063, c064, c065, c066, c067, c068, c069, c070, c071, c072, c073, c074, c075, c076, c077, c078, c079, c080, c081, c082, c083, c084, c085, c086, c087, c088, c089, c090, c091, c092, c093, c094, c095, c096, c097, c098, c099, c100, c110, c111, c112, c135, c138, c139, c140, c144, c145, c146, c147, c148, c149, c150, c151, c154, c155, c156, c157, c158, c159, c160, c170) "
            . "VALUES ('$username', 'now()', '$maree', '$n_calee', '$id_species', '$id_route', '".$_POST['c009']."', '".$_POST['c010']."', '".$_POST['c011']."', '".$_POST['c012']."', '".$_POST['c013']."', '".$_POST['c014']."', '".$_POST['c015']."', '".$_POST['c016']."', '".$_POST['c017']."', '".$_POST['c018']."', '".$_POST['c019']."', '".$_POST['c020']."', '".$_POST['c021']."', '".$_POST['c022']."', '".$_POST['c023']."', '".$_POST['c024']."', '".$_POST['c025']."', '".$_POST['c026']."', '".$_POST['c027']."', '".$_POST['c028']."', '".$_POST['c029']."', '".$_POST['c030']."', '".$_POST['c031']."', '".$_POST['c032']."', '".$_POST['c033']."', '".$_POST['c034']."', '".$_POST['c035']."', '".$_POST['c036']."', '".$_POST['c037']."', '".$_POST['c038']."', '".$_POST['c039']."', '".$_POST['c040']."', '".$_POST['c041']."', '".$_POST['c042']."', '".$_POST['c043']."', '".$_POST['c044']."', '".$_POST['c045']."', '".$_POST['c046']."', '".$_POST['c047']."', '".$_POST['c048']."', '".$_POST['c049']."', '".$_POST['c050']."', '".$_POST['c051']."', '".$_POST['c052']."', '".$_POST['c053']."', '".$_POST['c054']."', '".$_POST['c055']."', '".$_POST['c056']."', '".$_POST['c057']."', '".$_POST['c058']."', '".$_POST['c059']."', '".$_POST['c060']."', '".$_POST['c061']."', '".$_POST['c062']."', '".$_POST['c063']."', '".$_POST['c064']."', '".$_POST['c065']."', '".$_POST['c066']."', '".$_POST['c067']."', '".$_POST['c068']."', '".$_POST['c069']."', '".$_POST['c070']."', '".$_POST['c071']."', '".$_POST['c072']."', '".$_POST['c073']."', '".$_POST['c074']."', '".$_POST['c075']."', '".$_POST['c076']."', '".$_POST['c077']."', '".$_POST['c078']."', '".$_POST['c079']."', '".$_POST['c080']."', '".$_POST['c081']."', '".$_POST['c082']."', '".$_POST['c083']."', '".$_POST['c084']."', '".$_POST['c085']."', '".$_POST['c086']."', '".$_POST['c087']."', '".$_POST['c088']."', '".$_POST['c089']."', '".$_POST['c090']."', '".$_POST['c091']."', '".$_POST['c092']."', '".$_POST['c093']."', '".$_POST['c094']."', '".$_POST['c095']."', '".$_POST['c096']."', '".$_POST['c097']."', '".$_POST['c098']."', '".$_POST['c099']."', '".$_POST['c100']."', '".$_POST['c110']."', '".$_POST['c111']."', '".$_POST['c112']."', '".$_POST['c135']."', '".$_POST['c138']."', '".$_POST['c139']."', '".$_POST['c140']."', '".$_POST['c144']."', '".$_POST['c145']."', '".$_POST['c146']."', '".$_POST['c147']."', '".$_POST['c148']."', '".$_POST['c149']."', '".$_POST['c150']."', '".$_POST['c151']."', '".$_POST['c154']."', '".$_POST['c155']."', '".$_POST['c156']."', '".$_POST['c157']."', '".$_POST['c158']."', '".$_POST['c159']."', '".$_POST['c160']."', '".$_POST['c170']."') ";

    } else {
        $query = "UPDATE seiners.thon_rejete_taille SET "
            . "username = '$username', datetime = now(), "
            . "maree = '$maree', id_route = '$id_route', id_species = '$id_species', "
            . "c009 = '".$_POST['c009']."', c010 = '".$_POST['c010']."', c011 = '".$_POST['c011']."', "
                . "c012 = '".$_POST['c012']."', c013 = '".$_POST['c013']."', c014 = '".$_POST['c014']."', "
                . "c015 = '".$_POST['c015']."', c016 = '".$_POST['c016']."', c017 = '".$_POST['c017']."', "
                . "c018 = '".$_POST['c018']."', c019 = '".$_POST['c019']."', c020 = '".$_POST['c020']."', "
                . "c021 = '".$_POST['c021']."', c022 = '".$_POST['c022']."', c023 = '".$_POST['c023']."', "
                . "c024 = '".$_POST['c024']."', c025 = '".$_POST['c025']."', c026 = '".$_POST['c026']."', "
                . "c027 = '".$_POST['c027']."', c028 = '".$_POST['c028']."', c029 = '".$_POST['c029']."', "
                . "c030 = '".$_POST['c030']."', c031 = '".$_POST['c031']."', c032 = '".$_POST['c032']."', "
                . "c033 = '".$_POST['c033']."', c034 = '".$_POST['c034']."', c035 = '".$_POST['c035']."', "
                . "c036 = '".$_POST['c036']."', c037 = '".$_POST['c037']."', c038 = '".$_POST['c038']."', "
                . "c039 = '".$_POST['c039']."', c040 = '".$_POST['c040']."', c041 = '".$_POST['c041']."', "
                . "c042 = '".$_POST['c042']."', c043 = '".$_POST['c043']."', c044 = '".$_POST['c044']."', "
                . "c045 = '".$_POST['c045']."', c046 = '".$_POST['c046']."', c047 = '".$_POST['c047']."', "
                . "c048 = '".$_POST['c048']."', c049 = '".$_POST['c049']."', c050 = '".$_POST['c050']."', "
                . "c051 = '".$_POST['c051']."', c052 = '".$_POST['c052']."', c053 = '".$_POST['c053']."', "
                . "c054 = '".$_POST['c054']."', c055 = '".$_POST['c055']."', c056 = '".$_POST['c056']."', "
                . "c057 = '".$_POST['c057']."', c058 = '".$_POST['c058']."', c059 = '".$_POST['c059']."', "
                . "c060 = '".$_POST['c060']."', c061 = '".$_POST['c061']."', c062 = '".$_POST['c062']."', "
                . "c063 = '".$_POST['c063']."', c064 = '".$_POST['c064']."', c065 = '".$_POST['c065']."', "
                . "c066 = '".$_POST['c066']."', c067 = '".$_POST['c067']."', c068 = '".$_POST['c068']."', "
                . "c069 = '".$_POST['c069']."', c070 = '".$_POST['c070']."', c071 = '".$_POST['c071']."', "
                . "c072 = '".$_POST['c072']."', c073 = '".$_POST['c073']."', c074 = '".$_POST['c074']."', "
                . "c075 = '".$_POST['c075']."', c076 = '".$_POST['c076']."', c077 = '".$_POST['c077']."', "
                . "c078 = '".$_POST['c078']."', c079 = '".$_POST['c079']."', c080 = '".$_POST['c080']."', "
                . "c081 = '".$_POST['c081']."', c082 = '".$_POST['c082']."', c083 = '".$_POST['c083']."', "
                . "c084 = '".$_POST['c084']."', c085 = '".$_POST['c085']."', c086 = '".$_POST['c086']."', "
                . "c087 = '".$_POST['c087']."', c088 = '".$_POST['c088']."', c089 = '".$_POST['c089']."', "
                . "c090 = '".$_POST['c090']."', c091 = '".$_POST['c091']."', c092 = '".$_POST['c092']."', "
                . "c093 = '".$_POST['c093']."', c094 = '".$_POST['c094']."', c095= '".$_POST['c095']."', "
                . "c096 = '".$_POST['c096']."', c097 = '".$_POST['c097']."', c098 = '".$_POST['c098']."', "
                . "c099 = '".$_POST['c099']."', c100 = '".$_POST['c100']."', c110 = '".$_POST['c110']."', "
                . "c111 = '".$_POST['c111']."', c112 = '".$_POST['c112']."', c135 = '".$_POST['c135']."', "
                . "c138 = '".$_POST['c138']."', c139 = '".$_POST['c139']."', c140 = '".$_POST['c140']."', "
                . "c144 = '".$_POST['c144']."', c145 = '".$_POST['c145']."', c146 = '".$_POST['c146']."', "
                . "c147 = '".$_POST['c147']."', c148 = '".$_POST['c148']."', c149 = '".$_POST['c149']."', "
                . "c150 = '".$_POST['c150']."', c151 = '".$_POST['c151']."', c154 = '".$_POST['c154']."', "
                . "c155 = '".$_POST['c155']."', c156 = '".$_POST['c156']."', c157 = '".$_POST['c157']."', "
                . "c158 = '".$_POST['c158']."', c159 = '".$_POST['c159']."', c160 = '".$_POST['c160']."', "
                . "c170 = '".$_POST['c170']."' "
            . " WHERE id = '{".$_POST['id']."}'";
    }

    $query = str_replace('\'\'', 'NULL', $query);
    #print $query;
    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    } else {
        #print $query;
        header("Location: ".$_SESSION['http_host']."/industrial/view_seiners_thon_rej_taille.php?source=$source&table=thon_rej_taille&action=show");
    }
}

foot();
