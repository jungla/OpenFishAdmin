<?php
require('top_foot.inc.php');
require("functions.inc.php");

$_SESSION['where'] = 'login';

top();

$username = $_SESSION['username'];
$password = $_SESSION['password']; 

if(logged($username,$password)) {

    if ($_POST['submit'] == 'Deconnexion') {
	unset($_SESSION['username']);
	unset($_SESSION['password']);
	
	$radice = $_SERVER['HTTP_HOST'];
	
        header("Location: ".$_SESSION['http_host']."/login.php");
	
    } else {
        //header columns
        $q_hdr = "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME = 'accounts';";
        $r_hdr = pg_query($q_hdr);
        $header = array();
        while ($line = pg_fetch_row($r_hdr)) {
            $header[] = $line[0];
        }
        
        $header = array_slice($header, 3,5);
        
        $query = "SELECT * FROM users.accounts WHERE username = '$username'";
        
        $r_query = pg_fetch_row(pg_query($query));
        
        $self = $_SERVER['PHP_SELF'];
        echo "<h4>Personal details</h4>
        <ul>
        <li>username: <b>$username</b></li>
        <li>email address: <b>$r_query[2]</b></li>
        <li>projects:</li>
            <ol>";
            for($i = 0; $i < 5; $i++) {
                if ($r_query[$i+3] == 't') {
                    echo "<li><b>".label2name($header[$i])."</b></li>";
                }
            }
        echo "</ol>
        </ul>
        <h4>Actions</h4>
        <ul>
        <li><a href=\"./login-chng-pwd.php\">Change password</a></li>
        <li><a href=\"./login-resc-pwd.php\">Recover password</a></li>
        <li><a href=\"./login-chng-email.php\">Change email address</a></li>
        <!--<li>View activity logs</li>-->
        </ul>
        <p/>
        <form action=\"$self\" method=\"post\">
        <input type=\"submit\" value=\"Deconnexion\" name=\"submit\" />
        </form>";
    }
    
} else {
    
    if ($_POST['submit'] == 'Connexion') {

        $username = $_POST['username'];
        $password = $_POST['password'];

        if (trim($username) == "" OR trim($password) == "") {
            echo "<strong><br/>Please insert Username and Password</strong><br/><br/><input type=\"button\" value=\"Connexion\" onclick=\"history.back();\">";
            $controllo = 1;
        } else {
            $username = htmlentities($username, ENT_QUOTES, 'UTF-8');
            $password = htmlentities($password, ENT_QUOTES, 'UTF-8');

            if(logged($username,$password)) {
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
               
                $radice = $_SERVER['HTTP_HOST'];
	
                header("Location: ".$_SESSION['http_host']."/login.php");
                $controllo = 1;
            } else {
                echo '<br/><b>Account not found.</b><br/><a href="./login.php">Connexion</a><br/><br/>';
                foot();
                $controllo = 1;
            }
            }

    }

//se nulla di tutto il precedente e vero

    if (!$controllo) {
        $self = $_SERVER['PHP_SELF'];
        echo "
        <form action=\"$self\" method=\"post\">
        <h4>Username</h4>
        <input type=\"text\" name=\"username\" />
        <br/><br/>
        <h4>Password</h4>
        <input type=\"password\" name=\"password\" /><br/><br/>
        <input type=\"submit\" value=\"Connexion\" name=\"submit\" />
        </form>
        <br/><a href=\"ins-utenti.php\">Request account</a><br/>
        <a href=\"resc-password.php\">Recover password</a><br/>
        <br/><br/>";

        foot();
    }
}