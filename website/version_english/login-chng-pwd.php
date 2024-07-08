<?php
require("./top_foot.inc.php");
require("functions.inc.php");

top();


if(isset($_POST['submit'])) {
    
    $username = $_SESSION['username'];
    $old_pwd = trim($_POST['old_pwd']);
    $new_pwd = trim($_POST['new_pwd']);
    
    
    if ($old_pwd == "" OR $new_pwd == "") {
        echo "<strong><br/>Please insert both old and new password</strong><br/><br/><input type=\"button\" value=\"Back\" onclick=\"history.back();\">";
        } else {
        
	$query = "UPDATE users.accounts SET password = '$new_pwd' WHERE username='$username' AND password='$old_pwd'";
	if(pg_query($query)) {
            $_SESSION['password'] = $new_pwd;
            echo "Password updated with success";
        };
	
	// parametri mail
	
	// costruiamo alcune intestazioni generali
	
	
	// costruiamo le intestazioni specifiche per il formato HTML
		//variabili mail
        }
        $controllo = 1;

}


if(!$controllo){

$self = $_SERVER['PHP_SELF'];
?>
<!--<p>Recover your password on the email address you used to register the account.</p>-->
<form action="<?php echo "$self";?>" method="post">
<h4>old password</h4>
<input type="password" name="old_pwd" /><br/><br/>
<h4>new password</h4>
<input type="password" name="new_pwd" /><br/><br/>
<input type="submit" value="submit" name="submit" />
</form>

<?php
}

mysql_close();
foot();