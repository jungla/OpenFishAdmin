<?php
require("./top_foot.inc.php");
require("functions.inc.php");

top();


if(isset($_POST['submit'])) {
    
    $username = $_SESSION['username'];
    $old_pwd = trim($_POST['old_pwd']);
    $email = trim($_POST['email']);
    
    
    if ($old_pwd == "" OR $email == "") {
        echo "<strong><br/>Please fill all fields</strong><br/><br/><input type=\"button\" value=\"Back\" onclick=\"history.back();\">";
        } else {
        
	$query = "UPDATE users.accounts SET email = '$email' WHERE username='$username' AND password='$old_pwd'";
	if(pg_query($query)) {
            echo "<p>Password updated with success <br/>";
            echo "Back to <a href=./login.php>account page</a></p>";

        };
	
        }
        $controllo = 1;

}


if(!$controllo){

$self = $_SERVER['PHP_SELF'];
?>
<!--<p>Recover your password on the email address you used to register the account.</p>-->
<form action="<?php echo "$self";?>" method="post">
<h4>Current password</h4>
<input type="password" name="old_pwd" /><br/><br/>
<h4>New email</h4>
<input type="email" name="email" /><br/><br/>
<input type="submit" value="submit" name="submit" />
</form>

<?php
}

mysql_close();
foot();