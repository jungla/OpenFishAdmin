<?php
require('connect.inc.php');
require('functions.inc.php');

session_start();

dbconnect();

#header('Cache-Control: no cache'); //no cache
#session_cache_limiter('private_no_expire'); // works
#session_cache_limiter('public'); // works too

header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter("must-revalidate");

function top_report() {
ob_start();

if ($_SERVER['HTTPS'] == "on") {
    $path = "https://".$_SERVER['HTTP_HOST'];
} else {
    $path = "http://".$_SERVER['HTTP_HOST'];
}

$_SESSION['http_host'] = $path;

?>
<!DOCTYPE html> 
<html>
<head>
<meta name="verify-v1" content="zVY3Zyz07USJnJcwk/wY6XnLYoY1sYL6ofBi7Hlw3jA=" />
<title>BD GabonBleu</title>
<style type="text/css" media="all">@import "<?php echo $path;?>/stile.css";</style>
<link rev="made" href="mailto:jean.mensa@gabonbleu.org" />
<link href="https://fonts.googleapis.com/css?family=Montserrat|Raleway|Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<meta name="keywords" content="geospatial database, base de donnee peche" />
<meta name="author" content="Jean A. Mensa" />
<meta name="robots" content="all" />

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-115673540-1"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.js"></script>

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-115673540-1');
</script>

<script type="text/javascript">
function licNumber(id_y,id_n){
    if (document.form.l_yn[0].checked === true){
        document.getElementById(id_y).style.display = 'block';
        document.getElementById(id_n).style.display = 'none';
    } else if (document.form.l_yn[0].checked === false){
        document.getElementById(id_y).style.display = 'none';
        document.getElementById(id_n).style.display = 'block';
    }
}
</script>

<script type="text/javascript">
function cardNumber(id_y,id_n){
    if (document.form.c_yn[0].checked === true){
        document.getElementById(id_y).style.display = 'block';
        document.getElementById(id_n).style.display = 'none';
    } else if (document.form.c_yn[0].checked === false){
        document.getElementById(id_y).style.display = 'none';
        document.getElementById(id_n).style.display = 'block';
    }
}
</script>

<script type="text/javascript">
function fishName(id,name,val){
    if (document.form[name].value == val){
        document.getElementById(id).style.display = 'block';
    } else {
        document.getElementById(id).style.display = 'none';
    }
}
</script>

<script>
function goBack() {
    window.history.back();
}
</script>

<script type="text/javascript">
function menu_pop_1(firstchoice,secondchoice,menu_in,menu_out,table) {
    $("select#" + secondchoice).load("../get_menu_1.php?menu_value=" + $("select#"+firstchoice).val() + "&menu_in=" + menu_in + "&menu_out=" + menu_out + "&table=" + table);
}

function menu_pop_2(firstchoice,secondchoice,thirdchoice,menu_in_1,menu_in_2,menu_out,table) {
    $("select#" + thirdchoice).load("../get_menu_2.php?menu_value_1=" + $("select#"+firstchoice).val() + "&menu_value_2=" + $("select#"+secondchoice).val() + "&menu_in_1=" + menu_in_1 + "&menu_in_2=" + menu_in_2 + "&menu_out=" + menu_out + "&table=" + table);
}

function menu_pop_species(firstchoice,secondchoice,menu_in,menu_out,table) {
    $("select#" + secondchoice).load("../get_menu_species.php?menu_value=" + $("select#"+firstchoice).val() + "&menu_in=" + menu_in + "&menu_out=" + menu_out + "&table=" + table);
}


function menu_pop_lance(firstchoice,secondchoice,menu_in,menu_out,table,tout) {
    $("select#" + secondchoice).load("../../get_menu_lance.php?menu_value=" + $("select#"+firstchoice).val() + "&menu_in=" + menu_in + "&menu_out=" + menu_out + "&table=" + table + "&tout=" + tout);
}

</script>

</head>
<body>
<div id="content" style="padding-right:5%;width:90%">

<?php
}

function foot_report() {
echo '
    <br/>
    <br/>
    
</div>
<div id="footer" style="height:100%;">
<table id="no-border">
<tr>
<td>
Partenaires:</br></br>
<img src="'.$path.'/img/banner_logo.png" height="60px"/>
</td>
<td>
Sponsors:</br></br>
<img src="'.$path.'/img/Perenco_logo.png" height="60px"/><br/>
</td>
</tr>
<tr>
<td>
Administrateur de site web: <a href="mailto:jean.mensa@gabonbleu.org">jean.mensa@gabonbleu.org</a>
</td>
</tr>
</div>
</body>
</html>
';
}



?>
