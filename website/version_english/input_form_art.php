<?php
require("top_foot.inc.php");
require("functions.inc.php");

$_SESSION['where'] = 'input';

$username = $_SESSION['username'];

top();

if ($_GET['method'] != "") $_SESSION['path'][0] = $_GET['method'];
if ($_GET['source'] != "") $_SESSION['path'][1] = $_GET['source'];
if ($_GET['table'] != "") $_SESSION['path'][2] = $_GET['table'];

$method = $_SESSION['path'][0];
$source = $_SESSION['path'][1];
$table = $_SESSION['path'][2];

echo "<a href=\"./input.php\">upload</a> > "
. "<a href=\"./input.php?method=$method\">".$method."</a> > "
        . "<a href=\"./input_form.php?method=$method&source=$source\">".label2name($source)."</a> > "
        . "<a href=\"./input_form_art.php?method=$method&source=$source&table=$table\">".label2name($table)."</a>";

$radice = filter_input(INPUT_SERVER, 'PHP_SELF');
$host = filter_input(INPUT_SERVER, 'HTTP_HOST');


if (project($username,'art_obs')) {
    
if ($table == 'captures') {

//se submit = go!
if ($_POST['submit_mission'] == "Submit") {
     # load KML file now.
    if ($_FILES['kml_file']['tmp_name'] != "") {
        $filename = $_FILES['kml_file']['tmp_name'];
        $filename_out = uniqid($username);
        # generate shapefiles
        exec("cp $filename ./files/tracks/$filename_out.kml");
        exec("chmod 0777 ./files/tracks/$filename_out.kml");
        exec("/opt/local/bin/ogr2ogr -f 'ESRI Shapefile' ./files/tracks/$filename_out.shp ./files/tracks/$filename_out.kml");
        exec("/opt/local/lib/postgresql96/bin/shp2pgsql -g geometry ./files/tracks/$filename_out.shp peche_artisanal.track_$filename_out | /opt/local/lib/postgresql96/bin/psql -h localhost -d geospatialdb -U postgres");
        
    }
    # loads shapefile to temporary db table (syncs later)
    # a random table name should be generated here and passed in SESSION for later use

    if ($_POST['species'] == 'no') { # enter only the mission. No fishing.

        # load data
        
        $username = $_SESSION['username']; 
        $datetime_d = $_POST['datetime_d'];
        $datetime_r = $_POST['datetime_r'];
        //$datetime_d = mdy2ymd($_POST['date_d'])." ".$_POST['hour_d'];
        //$datetime_r = mdy2ymd($_POST['date_r'])." ".$_POST['hour_r'];
        $obs_name = $_POST['obs_name'];
        $t_site = $_POST['t_site'];
        $fish_name = $_POST['fish_name'];
        $license = $_POST['license'];
        $t_net = $_POST['t_net'];
        $net_s = $_POST['net_s'];
        $net_l = $_POST['net_l'];
        $n_days = $_POST['n_days'];
        
        if ($_FILES['kml_file']['tmp_name'] != "") {
            # get PGS_track from track table
            $query_t = "SELECT st_astext(geometry) FROM peche_artisanal.track_$filename_out";
            $gps_track = pg_fetch_array(pg_query($query_t));
            $gps_yn = TRUE;
            $gps_file = "./files/tracks/$filename_out.kml";
            
            #echo $gps_track[0];
        } else {
            $gps_track = NULL;
            $gps_yn = 'FALSE';
            $gps_file = 'NULL';
        }
        echo $gps_yn;
        $query = "INSERT INTO peche_artisanal.captures "
       . "(username, datetime_d, datetime_r, obs_name, t_site, fish_name, license, t_net, net_s, net_l, n_days, t_group, t_species, sample_s, n_ind, gps_file, gps_yn, gps_track) "
       . "VALUES ('$username','$datetime_d','$datetime_r', '$obs_name', '$t_site', '$fish_name', '$license', '$t_net', '$net_s', '$net_l', '$n_days', '$t_group', '$t_species', '$sample_s', '$n_ind', '$gps_file', '$gps_yn', ST_GeomFromText('$gps_track[0]',4326));";
        $query = str_replace('\'-- \'', 'NULL', $query);
        $query = str_replace('\'\'', 'NULL', $query);
        
        if(!pg_query($query)) {
            echo "<p>".$query,"</p>";
            msg_queryerror();
        } else {
            pg_query("DROP TABLE peche_artisanal.track_$filename_out");
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=input_form_art.php");
        }
        
    } else {
           
        # fishing done. Store POST variables in SESSION.
        $_SESSION['data'] = $_POST;
        $_SESSION['data']['track_name'] = $filename_out;
        header("Location: ".$_SESSION['http_host']."/input_form_art_1.php");
    }

  $controllo = 1;
 
}

if (!$controllo) {
	?>
	<form method="post" action="<?php echo $radice;?>" enctype="multipart/form-data"><br/>
	<b>Date and time depart</b> (yyyy-mm-dd hh:mm:ss)
	<br/>
	<input type="text" size="20" name="datetime_d" value="<?php echo $datetime_d;?>" />
	<br/>
	<br/>
	<b>Date and time return</b> (yyyy-mm-dd hh:mm:ss)
	<br/>
	<input type="text" size="20" name="datetime_r" value="<?php echo $datetime_r;?>" />
	<br/>
	<br/>
	<b>Observer name</b>
	<br/>
	<input type="text" size="20" name="obs_name" value="<?php echo $obs_name;?>" />
	<br/>
	<br/>
        <b>Landing site</b>
	<br/>
	<input type="text" size="20" name="t_site" value="<?php echo $site;?>" />
	<br/>
	<br/>
	<b>Fisherman name</b>
	<br/>
	<input type="text" size="20" name="fish_name" value="<?php echo $fish_name;?>" />
	<br/>
	<br/>
        <b>License number</b>
	<br/>
	<input type="text" size="30" name="license" value="<?php echo $license;?>" />
	<br/>
	<br/>
        <b>Upload GPS track</b> (KML format)
	<br/>
	<input type="file" size="40" name="kml_file" />
	<br/>
	<br/>
        <b>Type of net</b>
        <br/>
	<input type="text" size="8" name="net_t" value="<?php echo $net_t;?>" />
	<br/>
	<br/>
	<b>Net size</b> (cm)
        <br/>
	<input type="text" size="8" name="net_s" value="<?php echo $net_s;?>" />
	<br/>
	<br/>
        <b>Fishing net Length</b> (m)
        <br/>
        <input type="text" size="4" name="net_l" value="<?php echo $net_l;?>" />
        <br/>
        <br/>
        <b>Number of days at sea</b>
        <br/>
        <input type="text" size="4" name="n_days" value="<?php echo $n_days;?>" />
        <br/>
        <br/>
        <b>Add captures details?</b>
        <br/>
        yes<input type="radio" name="species" value="yes" checked/>
	no<input type="radio" name="species" value="no" />
        <br/>
        <br/>
        <input type="submit" value="Submit" name="submit_mission"/>
        </form>
        
	<br/><br/>
	
<?php
}

} else if ($table == 'effort') {
    
    $username = $_SESSION['username'];
    $date_e = mdy2ymd($_POST['date_e']);
    $obs_name = $_POST['obs_name'];
    $t_site = $_POST['t_site'];
    $DB1 = $_POST['DB1'];
    $DB3 = $_POST['DB3'];
    $DH1 = $_POST['DH1'];
    $DH3 = $_POST['DH3'];
    $PS1 = $_POST['PS1'];
    $PS3 = $_POST['PS3'];
    $PC1 = $_POST['PC1'];
    $PC3 = $_POST['PC3'];
    
    if ($_POST['submit'] == "Submit") {
        $query = "INSERT INTO peche_artisanal.effort (username, date_e, obs_name, t_site, DB1, DB3, DH1, DH3, PS1, PS3, PC1, PC3) "
                . "VALUES ('$username', '$date_e', '$obs_name', '$t_site', '$DB1', '$DB3', '$DH1', '$DH3', '$PS1', '$PS3', '$PC1', '$PC3');";

        $query = str_replace('\'\'', 'NULL', $query,$i);

        if(!pg_query($query)) {
            echo $query."<br/>";
            msg_queryerror();
        } else {
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert%20Data&id_dest=input_form.php");
        }

        $controllo = 1;
 
    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $radice;?>" enctype="multipart/form-data"><br/>
    <b>Date (yyyy-mm-dd)</b>
    <br/>
    <input type="text" size="10" name="date_e" />
    <br/>
    <br/>
    <b>Observer name</b>
    <br/>
    <input type="text" size="20" name="obs_name" />
    <br/>
    <br/>
    <b>Landing site</b>
    <br/>
    <input type="text" size="10" name="t_site" />
    <br/>
    <br/>
    <b>Number of Demersal Bottom Net boats fishing for 1 day (DB1)</b>
    <br/>
    <input type="text" size="4" name="DB1" />
    <br/>
    <br/>
    <b>Number of Demersal Bottom Net boats fishing for 3 days (DB3)</b>
    <br/>
    <input type="text" size="4" name="DB3" />
    <br/>
    <br/>
    <b>Number of Demersal Hand line boats fishing for 1 day (DH1)</b>
    <br/>
    <input type="text" size="4" name="DH1" />
    <br/>
    <br/>
    <b>Number of Demersal Hand line boats fishing for 3 days (DH3)</b>
    <br/>
    <input type="text" size="4" name="DH3" />
    <br/>
    <br/>
    <b>Number of Pelagic Sleeping Net boats fishing for 1 day (PS1)</b>
    <br/>
    <input type="text" size="4" name="PS1" />
    <br/>
    <br/>
    <b>Number of Pelagic Sleeping Net boats fishing for 3 days (PS3)</b>
    <br/>
    <input type="text" size="4" name="PS3" />
    <br/>
    <br/>
    <b>Number of Pelagic Circling Net boats fishing for 1 day (PC1)</b>
    <br/>
    <input type="text" size="4" name="PC1" />
    <br/>
    <br/>
    <b>Number of Pelagic Circling Net boats fishing for 3 days (PC3)</b>
    <br/>
    <input type="text" size="4" name="PC3" />
    <br/>
    <br/>
    <input type="submit" value="Submit" name="submit"/>
    </form>

    <br/><br/>
	
<?php
}


} else if ($table == 'fleet') {

    $username = $_SESSION['username'];
    $date_f = $_POST['date_f'];
    $obs_name = $_POST['obs_name'];
    $year = $_POST['year'];
    $t_site = $_POST['t_site'];
    $source = $_POST['source'];
    $PPB = $_POST['PPB'];
    $GPF = $_POST['GPF'];
    $PPF = $_POST['PPF'];
    $TOT = floatval($PPB) + floatval($GPF) + floatval($PPF);
    
    if ($_POST['submit'] == "Submit") {
    
        $query = "INSERT INTO peche_artisanal.flotille (username, date_f, obs_name, year, t_site, PPB, GPF, PPF, TOT) "
                . "VALUES ('$username', '$date_f', '$obs_name', '$year', '$t_site', '$PPB', '$GPF', '$PPF', '$TOT');";

        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
            echo $query."<br/>";
            msg_queryerror();
        } else {
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert%20Data&id_dest=input_form.php");
        }

        $controllo = 1;
 
    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $radice;?>" enctype="multipart/form-data"><br/>
    <b>Date</b> (mm/dd/yyyy)
    <br/>
    <input type="text" size="10" name="date_f" />
    <br/>
    <br/>
    <b>Observer name</b>
    <br/>
    <input type="text" size="20" name="obs_name" />
    <br/>
    <br/>
    <b>Landing site</b>
    <br/>
    <input type="text" size="10" name="t_site" />
    <br/>
    <br/>
    <b>Data Source</b>
    <br/>
    <input type="text" size="20" name="source" />
    <br/>
    <br/>
    <b>Number of <i>Petite Pirogue en Bois (PPB)</i></b>
    <br/>
    <input type="text" size="4" name="PPB" />
    <br/>
    <br/>
    <b>Number of <i>Grande Pirogue en Fibre de Verre (GPF)</i></b>
    <br/>
    <input type="text" size="4" name="GPF" />
    <br/>
    <br/>
    <b>Number of <i>Petite Pirogue  en Fibre de Verre (PPF)</i></b>
    <br/>
    <input type="text" size="4" name="PPF" />
    <br/>
    <br/>
    <input type="submit" value="Submit" name="submit"/>
    </form>

    <br/><br/>
    <?php
}
} else if ($table == 'market') {

    $username = $_SESSION['username'];

    $date_m = $_POST['date_m'];
    $t_site = $_POST['t_site'];
    
    $bp_f  = $_POST['bp_f'];
    $bp_c  = $_POST['bp_c'];
    $bp_fm  = $_POST['bp_fm'];
    $bp_s  = $_POST['bp_s'];
    $sar_f  = $_POST['sar_f'];
    $sar_c  = $_POST['sar_c'];
    $sar_fm  = $_POST['sar_fm'];
    $sar_s  = $_POST['sar_s'];
    $sl_f  = $_POST['sl_f'];
    $sl_c  = $_POST['sl_c'];
    $sl_fm  = $_POST['sl_fm'];
    $sl_s  = $_POST['sl_s'];
    $mac_f  = $_POST['mac_f'];
    $mac_c  = $_POST['mac_c'];
    $mac_fm  = $_POST['mac_fm'];
    $mac_s  = $_POST['mac_s'];
    $req_f  = $_POST['req_f'];
    $req_c  = $_POST['req_c'];
    $req_fm  = $_POST['req_fm'];
    $req_s  = $_POST['req_s'];
    $ailreq_f  = $_POST['ailreq_f'];
    $ailreq_c  = $_POST['ailreq_c'];
    $ailreq_fm  = $_POST['ailreq_fm'];
    $ailreq_s  = $_POST['ailreq_s'];
    $lang_f  = $_POST['lang_f'];
    $lang_c  = $_POST['lang_c'];
    $lang_fm  = $_POST['lang_fm'];
    $lang_s  = $_POST['lang_s'];
    $crab_f  = $_POST['crab_f'];
    $crab_c  = $_POST['crab_c'];
    $crab_fm  = $_POST['crab_fm'];
    $crab_s  = $_POST['crab_s'];

    if ($_POST['submit'] == "Submit") {
    
        $query = "INSERT INTO peche_artisanal.market (username,date,t_site,bp_f,bp_c,bp_fm,bp_s,sar_f,sar_c,sar_fm,sar_s,sl_f,sl_c,sl_fm,sl_s ,mac_f,mac_c,mac_fm,mac_s,req_f,req_c,req_fm,req_s,ailreq_f,ailreq_c,ailreq_fm,ailreq_s,lang_f,lang_c,lang_fm,lang_s,crab_f,crab_c ,crab_fm,crab_s) "
                . "VALUES ('$username','$date','$t_site','$bp_f','$bp_c','$bp_fm','$bp_s','$sar_f','$sar_c','$sar_fm','$sar_s','$sl_f','$sl_c','$sl_fm','$sl_s','$mac_f','$mac_c','$mac_fm','$mac_s','$req_f','$req_c','$req_fm','$req_s','$ailreq_f','$ailreq_c','$ailreq_fm','$ailreq_s','$lang_f','$lang_c','$lang_fm','$lang_s','$crab_f','$crab_c','$crab_fm','$crab_s');";

        $query = str_replace('\'\'', 'NULL', $query);

        if(!pg_query($query)) {
            echo $query."<br/>";
            msg_queryerror();
        } else {
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert%20Data&id_dest=input_form.php");
        }

        $controllo = 1;
 
    }

if (!$controllo) {
    ?>
    <form method="post" action="<?php echo $radice;?>" enctype="multipart/form-data"><br/>
    <b>Date</b> (mm/dd/yyyy)
    <br/>
    <input type="text" size="10" name="date_f" />
    <br/>
    <br/>
    <b>Observer name</b>
    <br/>
    <input type="text" size="20" name="obs_name" />
    <br/>
    <br/>
    <b>Site</b>
    <br/>
    <input type="text" size="10" name="t_site" />
    <br/>
    <br/>
    <table>
        <tr><td><b>Cat&eacute;gorie de taille</b></td>                            <td><b>frais</b><br/>&agrave; la pirogue (Kg/FCFA)</td> <td><b>congel&eacute;</b><br/>chez la mareyeuse (Kg/FCFA)</td>  <td><b>fum&eacute;</b><br/>(FCFA/kg)</td>           <td><b>sal&eacute;</b><br/>(FCFA/morceau)</td></tr>
        <tr><td>Beaux poissons (Bars, rouge, pagre, etc)</td>   <td><input type="text" size="4" name="bp_f" /></td>     <td><input type="text" size="4" name="bp_c" /></td>             <td><input type="text" size="4" name="bp_fm" /></td> <td><input type="text" size="4" name="bp_s" /></td></tr>
        <tr><td>Sardines/sardinelles</td>                       <td><input type="text" size="4" name="sar_f" /></td>      <td><input type="text" size="4" name="sar_c" /></td>              <td><input type="text" size="4" name="sar_fm" /></td>  <td><input type="text" size="4" name="sar_s" /></td></tr>
        <tr><td>Sole </td>                                      <td><input type="text" size="4" name="sl_f" /></td>      <td><input type="text" size="4" name="sl_c" /></td>              <td><input type="text" size="4" name="sl_fm" /></td>  <td><input type="text" size="4" name="sl_s" /></td></tr>
        <tr><td>Machoiron</td>                                  <td><input type="text" size="4" name="mac_f" /></td>      <td><input type="text" size="4" name="mac_c" /></td>              <td><input type="text" size="4" name="mac_fm" /></td>  <td><input type="text" size="4" name="mac_s" /></td></tr>
        <tr><td>Requin/raie</td>                                <td><input type="text" size="4" name="req_f" /></td>      <td><input type="text" size="4" name="req_c" /></td>              <td><input type="text" size="4" name="req_fm" /></td>  <td><input type="text" size="4" name="req_s" /></td></tr>
        <tr><td>Ailerons de requins</td>                        <td><input type="text" size="4" name="ailreq_f" /></td>      <td><input type="text" size="4" name="ailreq_c" /></td>              <td><input type="text" size="4" name="ailreq_fm" /></td>  <td><input type="text" size="4" name="ailreq_s" /></td></tr>
        <tr><td>Langouste</td>                                  <td><input type="text" size="4" name="lang_f" /></td>      <td><input type="text" size="4" name="lang_c" /></td>              <td><input type="text" size="4" name="lang_fm" /></td>  <td><input type="text" size="4" name="lang_s" /></td></tr>
        <tr><td>Crabes</td>                                     <td><input type="text" size="4" name="crab_f" /></td>      <td><input type="text" size="4" name="crab_c" /></td>              <td><input type="text" size="4" name="crab_fm" /></td>  <td><input type="text" size="4" name="crab_s" /></td></tr>
    </table>
     <br/><br/>
    <input type="submit" value="Submit" name="submit"/>
    </form>

    <br/><br/>
    <?php
}
}
} else {
    msg_noaccess();
}

foot();




