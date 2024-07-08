<?php
require("./top_foot.inc.php");
require("functions.inc.php");

top();


if(isset($_POST['submit']) AND $_POST['email'] != ""){
	
	$email = $_POST['mail'];

	$query = "SELECT username, password FROM users.accounts WHERE email='$email'";
	$rquery = pg_query($query);
	
	while($row = pg_fetch_array($rquery))
	{
	$username = $row[username];
	$password = $row[password];
	
	// parametri mail
	
	// costruiamo alcune intestazioni generali
	
	
	// costruiamo le intestazioni specifiche per il formato HTML
		//variabili mail

$oggetto = 'Gabon Bleu Geospatial Database - Password Recovery';
$intestazioni  = "MIME-Version: 1.0\n \n";
$intestazioni .= "Content-type: text/html; charset=iso-8859-1\n \n";
$intestazioni .= "From: Gabon Bleu Administration <jeanmensa@gmail.com>\n \n";
	
	$messaggio = "
	<html bgcolor=\"white\">
	<head>
	<title>Gabon Bleu Geospatial Database - Password Recovery</title></head>
	<body>
	<strong>Welcome,</strong><br/>
	<br/>it follows username and password linked to this email addrees.<br/><br/>
	<strong>username: $nickname<br/>
	password: $password<br/><br/>
	<br/>
	</body>
	</html>";
	
	mail($mail, $oggetto, $messaggio, $intestazioni);
	}
	
	top(); 
	$controllo = 1;
	echo "<b>Email sent.</b><br/> <br/>You will receive an email on <b>$email</b><br/><br/><input type=\"button\" value=\"GoBack\" onclick=\"history.back();\">";
	

	

}


if(!$controllo){

$self = $_SERVER['PHP_SELF'];
?>
<p>Recover your password on the email address you used to register the account.</p>
<form action="<?php echo "$self";?>" method="post">
<h4>email</h4>
<input type="text" name="mail" /><br/><br/>
<input type="submit" value="submit" name="submit" />
</form>

<?php
}

mysql_close();
foot();