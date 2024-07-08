<?php
require("top_foot.inc.php");
require("functions.inc.php");

$_SESSION['where'] = 'input';

top();

if ($_GET['method'] != "") {$_SESSION['path'][0] = $_GET['method'];}
if ($_GET['source'] != "") {$_SESSION['path'][1] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][2] = $_GET['table'];}

$method = $_SESSION['path'][0];
$source = $_SESSION['path'][1];
$table = $_SESSION['path'][2];

echo "<a href=\"./input.php\">upload</a> > "
. "<a href=\"./input.php?method=$method\">".$method."</a> > "
        . "<a href=\"./input_form.php?method=$method&source=$source\">".$source."</a> > "
        . "<a href=\"./input_form_art.php?method=$method&source=$source&table=$table\">".$table."</a>";

$radice = filter_input(INPUT_SERVER, 'PHP_SELF');
$host = filter_input(INPUT_SERVER, 'HTTP_HOST');

if ($table == 'captures') {

//se submit = go!
if ($_POST['submit_species'] == "Submit") {
    
    $username = $_SESSION['username']; 
    //$datetime_d = mdy2ymd($_SESSION['data']['date_d'])." ".$_SESSION['data']['hour_d']; 
    //$datetime_r = mdy2ymd($_SESSION['data']['date_r'])." ".$_SESSION['data']['hour_r'];
    $datetime_d = $_SESSION['data']['datetime_d']; 
    $datetime_r = $_SESSION['data']['datetime_r'];
    $obs_name = $_SESSION['data']['obs_name'];
    $t_site = $_SESSION['data']['t_site'];
    $fish_name = $_SESSION['data']['fish_name'];
    $license = $_SESSION['data']['license'];
    $t_net = $_SESSION['data']['t_net'];
    $net_s = $_SESSION['data']['net_s'];
    $net_l = $_SESSION['data']['net_l'];
    $n_days = $_SESSION['data']['n_days'];
    $t_group = $_POST['t_group'];
    $t_species = $_POST['t_species'];
    $sample_s = $_POST['sample_s'];
    $n_ind = htmlspecialchars($_POST['n_ind'],ENT_QUOTES);
    $filename_out = $_SESSION['data']['track_name'];
    
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
    
    $query = "INSERT INTO peche_artisanal.captures "
   . "(username, datetime_d, datetime_r, obs_name, t_site, fish_name, license, t_net, net_s, net_l, n_days, t_group, t_species, sample_s, n_ind, gps_file, gps_yn, gps_track) "
   . "VALUES ('$username','$datetime_d','$datetime_r', '$obs_name', '$t_site', '$fish_name', '$license', '$t_net', '$net_s', '$net_l', '$n_days', '$t_group', '$t_species', '$sample_s', '$n_ind', '$gps_file', '$gps_yn', ST_GeomFromText('$gps_track[0]',4326));";
    $query = str_replace('\'-- \'', 'NULL', $query);
    $query = str_replace('\'\'', 'NULL', $query);
    
    if(!pg_query($query)) {
        echo $query."<br/>";
        echo $i."<br/>";
        msg_queryerror();
    } else {
        if ($_POST['extra_species'] == "no") {
            //echo "<p>Done</p>";
            # delete temp track folder
            pg_query("DROP TABLE peche_artisanal.track_$filename_out");
            header("Location: ".$_SESSION['http_host']."/executed.php?dest=Insert Data&id_dest=input_form_art.php");
            //$controllo = 1;
        }
    }

}

if (!$controllo) {
	?>
	<form method="post" action="<?php echo $radice;?>" enctype="multipart/form-data"><br/>
        <b>Species group</b>
        <br/>
        <input type="text" size="3" name="t_group" value="<?php echo $vitesse;?>" />
        <br/>
        <br/>
        <b>Species</b>
        <br/>
        <input type="text" size="6" name="t_species" value="<?php echo $rejet;?>" />
        <br/>
        <br/>
        <b>Sample size</b> (kg)<br/>
        <input type="text" size="5" name="sample_s" value="<?php echo $ech;?>" />
        <br/>
        <br/>
	<b>Number of individuals</b><br/>
        <input type="text" size="5" name="n_ind" value="<?php echo $ech;?>" />
        <br/>
        <br/>
        <b>Add more species?</b>
        <br/>
        yes<input type="radio" name="extra_species" value="yes" checked/>
	no<input type="radio" name="extra_species" value="no" />
        <br/>
        <br/>
	<input type="submit" value="Submit" name="submit_species"/>
	</form>
        
	<br/><br/>
	
<?php
}

}

foot();
