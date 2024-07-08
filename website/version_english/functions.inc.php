<?php

// GEOGRAPHY

function DMS2DD($deg,$min,$sec) {
 // Converting DMS ( Degrees / minutes / seconds ) to decimal format
 return $deg+((($min*60)+($sec))/3600);
 }

function DD2DMS($dec) {
 // Converts decimal format to DMS ( Degrees / minutes / seconds ) 
 $vars = explode(".",$dec);
 $deg = $vars[0];
 $tempma = "0.".$vars[1];
 
 $tempma = $tempma * 3600;
 $min = floor($tempma / 60);
 $sec = $tempma - ($min*60);
 
 return array("deg"=>$deg,"min"=>$min,"sec"=>$sec);
 }

function DM2DD($deg,$min) {
 // Converting DMS ( Degrees / minutes / seconds ) to decimal format
 return $deg+$min/60;
 }

function DD2DM($dec) {
 // Converts decimal format to DMS ( Degrees / minutes / seconds ) 
 $vars = explode(".",$dec);
 $deg = $vars[0];
 $min = "0.".$vars[1];
 return array("deg"=>$deg,"min"=>$min);
 }


// DATETIME


function mdy2ymd($date) {
 // converts date from mm/dd/yyyy (Excel format) to yyyy-mm-dd (postgres format)
 $exp = explode('/',$date);
 return trim($exp[2].'-'.$exp[0].'-'.$exp[1]);
 }

function check_date($date) {
 // check date to be MM/DD/YYYY
 $date = mdy2ymd($date);
 $exp = explode('/',$date);
 if ($exp[0] > 3000 or $exp[0] < 1000 or $exp[1] < 0 or $exp[1] > 12 or $exp[2] < 0 or $exp[2] > 31) {
  $out = FALSE;
 } else {
  $out = TRUE;
 }
 return $out;
 }

function check_time($time) {
 // check time format to be HH:MM or HH:MM:SS
 $exp = explode(':',$time);
 if (count($exp)<2 or $exp[0] > 24 or $exp[0] < 0 or $exp[1] < 0 or $exp[1] > 60 or $exp[2] < 0 or $exp[2] > 60) {
  $out = FALSE;
 } else {
  $out = TRUE;
 }
 return $out;
 }

// DATA FORMATTING

function check_number($val) {
 if (!is_numeric($val)) {
  $out = 'NaN';
 } else {
  $out = $val;
 }
 return $out;
 }

 
// PHP functions
 
 function logged($username,$password) {
    if(isset($username) AND isset($password)) {
	$loggedtest = "SELECT * FROM users.accounts 
	WHERE username = '$username'   
	AND password = '$password'";
        $ris_loggedtest = pg_query($loggedtest);
            if(pg_num_rows($ris_loggedtest) == 1) {
                return True; # username and password found	
            } else {
                return False; # username and password not found 
            }
        } else {
            return False; # username and password not set
        }
}

 
 function project($username,$project) {
    if(isset($username) AND isset($project)) {
	$query = "SELECT $project FROM users.accounts WHERE username = '$username'";
        //echo $query;
            if(pg_fetch_row(pg_query($query))[0] == 't') {
                return True; # username and password found	
            } else {
                return False; # username and password not found 
            }
        } else {
            return False; # username and password not set
        }
}

function msg_noaccess(){
        echo "<p>You have no access this page. <br/>Please check your <a href=\"./login.php\">login</a> credentials or contact <a href=\"\">administration</a></p>";
}

function name2label($name) {
    # databases
    if ($name == "artisanal fishery") $label = "artisanal";
    # tables
    if ($name == "capture recods") $label = "captures";
    if ($name == "fishing effort") $label = "effort";
    if ($name == "size flotille") $label = "flotille";
    if ($name == "market price") $label = "market";
    
    return $label;
}

function label2name($label) {
    # databases
    if ($label == "artisanal") $name = "artisanal fishery";
    # tables
    if ($label == "captures") $name = "capture records";
    if ($label == "effort") $name = "fishing effort";
    if ($label == "flotille") $name = "size flotille";
    if ($label == "market") $name = "market price";
  
    # admin tables
    if ($label == "ind_obs") $name = "Industrial fishery observers program";
    if ($label == "art_obs") $name = "Artisanal fishery observers program";
    if ($label == "csp") $name = "Centre Surveillance Peche";
    if ($label == "dgpa_lcn") $name = "DGPA Licenses records";
    if ($label == "dgpa_fsh") $name = "DGPA Fishing records";
    
    
    return $name;
}

function cols2name($label,$table) {
    if ($table == "peche_artisanal.captures") {
        if ($label == "datetime_d") $name = "<b>Date and time depart</b> (yyyy-mm-dd hh:mm:ss)";
        if ($label == "datetime_r") $name = "<b>Date and time return</b> (yyyy-mm-dd hh:mm:ss)";
        if ($label == "obs_name") $name = "<b>Observer name</b>";
        if ($label == "t_site") $name = "<b>Landing site</b>";
        if ($label == "fish_name") $name = "<b>Fisherman name</b>";
        if ($label == "license") $name = "<b>License number</b>";
        if ($label == "t_net") $name = "<b>Type of net</b>";
        if ($label == "net_s") $name = "<b>Fishing net size</b> (cm)";
        if ($label == "net_l") $name = "<b>Fishing net Length</b> (m)";
        if ($label == "n_days") $name = "<b>Number of days at sea</b>";
        if ($label == "t_group") $name = "<b>Species group</b>";
        if ($label == "t_species") $name = "<b>Species</b>";
        if ($label == "sample_s") $name = "<b>Sample size</b> (kg)";
        if ($label == "n_ind") $name = "<b>Number of individuals</b>";
        
    }
    
    
    return $name;
}

?>
