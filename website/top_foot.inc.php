<?php
require('connect.inc.php');
require('functions.inc.php');
require('fpdf/fpdf.php');
require('qrcode/qrcode.class.php');
require('PHPMailer/src/PHPMailer.php');
require('PHPMailer/src/SMTP.php');
//require('PHPMailer/src/Exception.php');

session_cache_limiter("must-revalidate");
session_start();

dbconnect();

#header('Cache-Control: no cache'); //no cache
#session_cache_limiter('private_no_expire'); // works
#session_cache_limiter('public'); // works too

header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header('Cache-Control: no cache');


$path = "http://".$_SERVER['HTTP_HOST'];

if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
    $path = "https://".$_SERVER['HTTP_HOST'];
}

$_SESSION['http_host'] = $path;

function top() {
ob_start();

?>
<!DOCTYPE html>
<html>
<head>
<meta name="verify-v1" content="zVY3Zyz07USJnJcwk/wY6XnLYoY1sYL6ofBi7Hlw3jA=" />
<title>BD GabonBleu</title>
<style type="text/css" media="all">@import "<?php echo $GLOBALS['path'];?>/stile.css";</style>
<link rel="stylesheet" href="<?php print $GLOBALS['path']; ?>/chosen_v1.8.7/chosen.css">
<link rev="made" href="mailto:jean.mensa@gabonbleu.org" />
<link href="https://fonts.googleapis.com/css?family=Montserrat|Raleway|Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<meta name="keywords" content="geospatial database, base de donnee peche" />
<meta name="author" content="Jean A. Mensa" />
<meta name="robots" content="all" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-115673540-1"></script>

<!-- web visualization library -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!--
<script src="/path/to/jquery.js"></script>
<script src="/path/to/cropper.js"></script>
<link  href="/path/to/cropper.css" rel="stylesheet">
<script src="/path/to/jquery-cropper.js"></script>
-->
<link  href="<?php echo $GLOBALS['path']; ?>/cropperjs-master/dist/cropper.css" rel="stylesheet">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="<?php echo $GLOBALS['path']; ?>/cropperjs-master/dist/cropper.js"></script>
<!--<script src="<?php echo $GLOBALS['path']; ?>/jquery-cropper-master/dist/jquery-cropper.js" type="text/javascript" charset="utf-8"></script>-->

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
function cardNumber(id_y,id_n,c_yn){
    if (document.getElementsByName(c_yn)[0].checked === true){
        document.getElementById(id_y).style.display = 'block';
        document.getElementById(id_n).style.display = 'none';
    } else if (document.getElementsByName(c_yn)[0].checked === false){
        document.getElementById(id_y).style.display = 'none';
        document.getElementById(id_n).style.display = 'block';
    }
}
</script>

<script type="text/javascript">
function fishName(id,name,val){
    if (document.form[name].value === val){
        document.getElementById(id).style.display = 'block';
    } else {
        document.getElementById(id).style.display = 'none';
    }
}
</script>

<script type="text/javascript">
    function show(aval,id) {
    var divs = document.getElementsByClassName(id);
    if (aval === "") {
      for (var i = 0; i < divs.length; i++) {
        divs[i].style.display = 'block';
      }
    }
    else{
      for (var i = 0; i < divs.length; i++) {
        divs[i].style.display = 'none';
      }
    }
  }
</script>

<script type="text/javascript">
    function hide(id) {
      var divs = document.getElementsByClassName(id);

      for (var i = 0; i < divs.length; i++) {
        divs[i].style.display = 'none';
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

function menu_pop_species_cat(firstchoice,secondchoice,menu_in,menu_out,table,category) {
    $("select#" + secondchoice).load("../get_menu_species_cat.php?menu_value=" + $("select#"+firstchoice).val() + "&menu_in=" + menu_in + "&menu_out=" + menu_out + "&table=" + table + "&category=" + category);
}

function menu_pop_lance(firstchoice,secondchoice,menu_in,menu_out,table,tout) {
    $("select#" + secondchoice).load("../../get_menu_lance.php?menu_value=" + $("select#"+firstchoice).val() + "&menu_in=" + menu_in + "&menu_out=" + menu_out + "&table=" + table + "&tout=" + tout);
}

function menu_pop_maree(firstchoice,secondchoice,table) {
    $("select#" + secondchoice).load("../../get_menu_maree.php?menu_value=" + $("select#"+firstchoice).val() + "&table=" + table);
}

</script>

<script type="text/javascript">
function check_control() {
$('input[type=checkbox]').on('change', function (e) {
    if ($('input[type=checkbox]:checked').length > 2) {
        $(this).prop('checked', false);
    }
});
}
</script>

<script type="text/javascript">
function ValidateSize(file) {
        var FileSize = file.files[0].size / 1024 / 1024; // in MB
        if (FileSize > 0.5) {
            alert('L\'image est plus grande de 500 KB. Veuillez choisir une autre image');
            $(file).val(''); //for clearing with Jquery
        } else {

        }
    }
</script>


<!-- <script type="text/javascript" src="<?php echo $GLOBALS['path'];?>/webcam.js"></script> -->

<!--<script language="JavaScript">
        function take_snapshot() {
                // take snapshot and get image data
                Webcam.snap( function(data_uri) {
                        // display results in page


                        Webcam.upload( data_uri, 'saveimage.php', function(code, text) {
                                document.getElementById('results').innerHTML =
                                '<h2>Here is your image:</h2>' +
                                '<img src="'+text+'"/>';
                        } );
                } );
        }
</script>-->

</head>
<body>

<div id="header">
<h1>Small Scale Fishery Database Zanzibar</h1>
<h2>Collect and manage small scale fisheries data to support sustainable resource use</h2>

<!--
<table id="no-border">
    <tr>
    <td style="border:1px solid black; width:90%;">
        <h1>Base de Donn&eacute;es de Gabon Bleu</h1>
        <h2>Saisir, modification et visualisation des donn&eacute;es</h2>
    </td>
    <td style="border:1px solid black; padding-right: 5%">
        <img src="./img/logo.png" height="100px"/>
    </td>
    </tr>
</table>
-->

</div>

<div id="navigation">
<a href="<?php echo $GLOBALS['path'];?>/index.php" <?php if ($_SESSION['where'][0] == 'home'){ print " style=\"color: #808080; text-decoration:none;\"";} ?>>Home</a>
&nbsp; <a href="<?php echo $GLOBALS['path'];?>/artisanal/index.php" <?php if ($_SESSION['where'][0] == 'artisanal'){ print " style=\"color: #808080; text-decoration:none;\"";} ?>>Database</a>
&nbsp; <a href="<?php echo $GLOBALS['path'];?>/maintenance/index.php" <?php if ($_SESSION['where'][0] == 'maintenance'){ print " style=\"color: #808080; text-decoration:none;\"";} ?>>Maintainance</a>
</div>

<?php

if ($_SESSION['where'][0] != 'home' and $_SESSION['where'][0] != 'login' and $_SESSION['where'][0] != 'maintenance') {
    print "<div id=\"navigation_small\">";
}

if ($_SESSION['where'][0] == 'artisanal') {
    echo "<a href=\"".$GLOBALS['path']."/artisanal/catches/artisanal_catches.php\"; ";
    if ($_SESSION['where'][1] == 'catches'){ print " style=\"color: #808080; text-decoration:none;\"";}
    print ">Statistiques</a>";

    echo "&nbsp;&nbsp; <a href=\"".$GLOBALS['path']."/artisanal/administration/artisanal_licenses.php\"; ";
    if ($_SESSION['where'][1] == 'autorisation'){ print " style=\"color: #808080; text-decoration:none;\"";}
    print ">Autorisations</a>";

    echo "&nbsp;&nbsp; <a href=\"".$GLOBALS['path']."/artisanal/infractions/artisanal_infractions.php\"; ";
    if ($_SESSION['where'][1] == 'infractions'){ print " style=\"color: #808080; text-decoration:none;\"";}
    print ">Infractions</a>";

    // if(right_write($_SESSION['username'],2,1) OR right_write($_SESSION['username'],3,1) OR right_write($_SESSION['username'],4,1) OR right_write($_SESSION['username'],6,1) OR right_write($_SESSION['username'],10,1)) {
    //   echo "&nbsp;&nbsp; <a href=\"".$GLOBALS['path']."/maintenance/artisanal/artisanal_maintenance.php\"; ";
    //   if ($_SESSION['where'][1] == 'maintenance'){ print " style=\"color: #808080; text-decoration:none;\"";}
    //   print ">Manutention</a>";
    //   //<i class=\"material-icons\">build</i>
    // }

//    echo "&nbsp; <a href=\"$GLOBALS['path']/artisanal/artisanal_tracking.php\"; ";
//    if ($_SESSION['where'][1] == 'pelagic'){ print " style=\"color: #808080; text-decoration:none;\"";}
//    print ">Tracking</a>";

}

if ($_SESSION['where'][0] != 'home') {
    print "</div>";
    print '<div id="content">';
} else {
    print '<div id="content" style="padding-right:5%;width:90%">';
}

if(logged($_SESSION['username'],$_SESSION['password'])) {
        $username = $_SESSION['username'];
        echo '<div style="border:0px solid black; padding-left:0%; margin-top:0px; line-height:30px; font-size:1em; height:30px">';
        echo "Zone d'utilisateur <a href=\"".$GLOBALS['path']."/login.php\">$username</a>";
        echo "</div>";

} else if($_SESSION['where'][0] == 'home' OR $_SESSION['where'][1] == 'home') {
        echo '<div style="border:0px solid black; padding-left:0%; margin-top:0px; line-height:30px; font-size:1em; height:30px">';
        echo "Se <a href=\"".$GLOBALS['path']."/login.php\">connecter</a> &agrave; la base de donn&eacute;es";
        echo "</div>";

} else if ($_SESSION['where'][0] != 'login' AND !logged($_SESSION['username'],$_SESSION['password'])) {
        echo "<p id=login>Veuillez vous <a href=\"".$GLOBALS['path']."/login.php\">connecter</a> pour acc&eacute;der au site.</p>";
        foot();
        die();
        //$radice = filter_input(INPUT_SERVER, 'PHP_SELF');
        //header("Location: ./login.php");
}
}

function foot() {
echo '
    <br/>
    <br/>';
?>
<script src="<?php print $GLOBALS['path']; ?>/chosen_v1.8.7/docsupport/jquery-3.2.1.min.js" type="text/javascript"></script>
<script src="<?php print $GLOBALS['path']; ?>/chosen_v1.8.7/chosen.jquery.js" type="text/javascript"></script>
<script src="<?php print $GLOBALS['path']; ?>/chosen_v1.8.7/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php print $GLOBALS['path']; ?>/chosen_v1.8.7/docsupport/init.js" type="text/javascript" charset="utf-8"></script>
<?php
print '
</div>
<div id="footer" style="height:100%;">
<table>
<tr>
<td>
Partenaires:</br></br>
<img src="'.$GLOBALS['path'].'/img/banner_logo.png" height="40px"/>
</td>
<td>
Sponsors:</br></br>
<img src="'.$GLOBALS['path'].'/img/Perenco_logo.png" height="40px"/><br/>
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
