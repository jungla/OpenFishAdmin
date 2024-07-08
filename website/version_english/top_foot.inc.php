<?php
require('connect.inc.php');

dbconnect();

session_start();
    
function top_login(){

    if(logged($_SESSION['username'],$_SESSION['password'])) {
        $radice = filter_input(INPUT_SERVER, 'PHP_SELF');
        $username = $_SESSION['username'];
        echo '<div style="border:1px solid black; margin-top:5px">';
        echo "Welcome <a href=\"./login.php\">$username</a>";
        echo "</div><div id=\"content\">";
        } else {
        echo '<p id=login>Please <a href="./login.php">login</a> to access the website.</p>';
        foot();
        die();
        //$radice = filter_input(INPUT_SERVER, 'PHP_SELF');
        //header("Location: ./login.php");
        }
}

function top() {
echo '
<!DOCTYPE html> 
<html>
<head>
<meta name="verify-v1" content="zVY3Zyz07USJnJcwk/wY6XnLYoY1sYL6ofBi7Hlw3jA=" />
<title>GEO DB Gabon Bleu</title>
<style type="text/css" media="all">@import "./stile.css";</style>
<link rev="made" href="mailto:jeanmensa@gmail.com" />
<meta name="keywords" content="geospatial database, gabon bleu, fishery, mapping" />
<meta name="author" content="Jean Mensa" />
<meta name="robots" content="all" />
</head>
<body>

<div id="header">
<h1>Geospatial Database for Gabon Bleu v0.2</h1>

<h2>Web Interface for Data Entry, Editing and Visualization of Fishery Data</h2>
</div>
<div id="navigation">
<a href="index.php">Home</a> &nbsp; <a href="input.php">Upload</a> &nbsp; <a href="download.php">Download</a> &nbsp; <a href="edit.php">Edit</a>
&nbsp; <a href="visualize.php">Visualize</a> &nbsp; <a href="statistics.php">Statistics</a>

</div>';

if(logged($_SESSION['username'],$_SESSION['password'])) {
        $radice = filter_input(INPUT_SERVER, 'PHP_SELF');
        $username = $_SESSION['username'];
        echo '<div style="border:0px solid black; padding-left:5%; margin-top:0px; line-height:30px; font-size:0.8em; height:30px">';
        echo "Welcome <a href=\"./login.php\">$username</a>";
        echo "</div>";
       
    
} else if ($_SESSION['where'] != 'login' AND !logged($_SESSION['username'],$_SESSION['password'])) {
        echo '<p id=login>Please <a href="./login.php">login</a> to access the website.</p>';
        foot();
        die();
        //$radice = filter_input(INPUT_SERVER, 'PHP_SELF');
        //header("Location: ./login.php");
}
echo '</div><div id="content">';

}

function top_2cols() {
echo '
<!DOCTYPE html> 
<html>
<head>
<meta name="verify-v1" content="zVY3Zyz07USJnJcwk/wY6XnLYoY1sYL6ofBi7Hlw3jA=" />
<title>GEO DB Gabon Bleu</title>
<style type="text/css" media="all">@import "./stile_2_cols.css";</style>
<link rev="made" href="mailto:jeanmensa@gmail.com" />
<meta name="keywords" content="geospatial database, gabon bleu, fishery, mapping" />
<meta name="author" content="Jean Mensa" />
<meta name="robots" content="all" />
</head>
<body>

<div id="header">
<h1>Geospatial Database for Gabon Bleu v0.1</h1>

<h2>Web Interface for Data Entry, Editing and Visualization of Fishery Data</h2>
</div><div id="navigation">
<a href="index.php">Home</a> &nbsp; <a href="index_art.php">Artisanal Fishery</a> &nbsp; <a href="index_ind.php">Industrial Fishery</a> &nbsp; <a href="index.php">CSP</a></div>';

echo '<div id="left">';

if(logged($_SESSION['username'],$_SESSION['password']) AND $_SESSION['where'] != 'login') {
        $radice = filter_input(INPUT_SERVER, 'PHP_SELF');
        $username = $_SESSION['username'];
        echo '<div style="border:0px solid black; margin-top:30px; line-height:10px; font-size:0.8em; height:30px">';
        echo "Welcome <a href=\"./login.php\">$username</a>";
        echo "</div>";
       
if ($_SESSION['where'] == 'artisanal') {
    echo '<ul>
        <li><a href="input_art.php">Input</a></li>
        <li><a href="edit_art.php">Edit</a></li>
        <li><a href="download_art.php">Download</a></li>
        <li><a href="visualize_art.php">Visualize</a></li>
        <li><a href="statistics_art.php">Statistics</a></li>
        </ul>';
        
} else if ($_SESSION['where'] == 'industrial') {
    echo '<ul>
        <li><a href="input_ind.php">Input</a></li>
        <li><a href="edit_ind.php">Edit</a></li>
        <li><a href="download_ind.php">Download</a></li>
        <li><a href="visualize_ind.php">Visualize</a></li>
        <li><a href="statistics_ind.php">Statistics</a></li>
        </ul>';    
}
    echo '</div><div id="content">';
    
} else if ($_SESSION['where'] == 'login') {
    echo '</div><div id="content">';
    
} else {
        echo '<p id=login>Please <a href="./login.php">login</a> to access the website.</p>';
        foot();
        die();
        //$radice = filter_input(INPUT_SERVER, 'PHP_SELF');
        //header("Location: ./login.php");
}


}

function foot() {
echo '
    <br/>
    <br/>
    
</div>
<div id="footer">
<a href="mailto:jeanmensa@gmail.com">jean.mensa@gmail.com</a> 
</div>

</body>
</html>
';
};

?>
