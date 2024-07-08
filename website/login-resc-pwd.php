<?php
require("./top_foot.inc.php");

$username = $_SESSION['username'];
$password = $_SESSION['password'];

top();

$self = $_SERVER['PHP_SELF'];
$radice = $_SERVER['HTTP_HOST'];

if($_POST['submit'] == "Enregistrer" AND $_POST['email'] != ""){

    $email = $_POST['email'];

    $query = "SELECT nickname, password FROM users.users WHERE email='$email'";

    $rquery = pg_query($query);

    //print $query;

    if (pg_num_rows($rquery) == 0) {
        print "<p>Nous n'avons pas trouv&eacute; le compte associ&eacute; &agrave; l'e-mail <b>".$email."</b><br/><br/>"
                . "<button onclick=\"history.back();\">Retourner</button></p>";
        foot();
        die();

    } else {

        while($row = pg_fetch_row($rquery)) {
            $nickname = $row[0];

            // use wordwrap() if lines are longer than 70 characters

            $htmlmessage = "
            <html>
            <head>
            <title>Bienvenue sur data.gabonbleu.org!</title>
            </head>
            <body>
            <p>Bienvenue sur data.gabonbleu.org!<br/>"
            . "<a href=\"$radice/login-chng-pwd.php?action=reset&key_one="
            . openssl_encrypt($email, "AES-128-CTR", "jeanmensa")
            . "&key_two="
            . openssl_encrypt($nickname, "AES-128-CTR", "jeanmensa")
            . "\">Réinitialiser le mot de passe</a> pour l'utilisateur <b>$nickname</b></p>
            </body>
            </html>
            ";

            $txtmessage = "Bienvenue sur data.gabonbleu.org! Username: $nickname, Password: $password";

            send_email($htmlmessage,$textmessage, 'Bienvenue sur data.gabonbleu.org', $email);

        }

    $controllo = 1;

    echo "<p>Email envoy&eacute; sur <b>$email</b><br/><input type=\"submit\" value=\"Retourner\" onclick=\"history.back();\"></p>";

    //echo $htmlmessage;
        }

}


if(!$controllo) {

?>
<!--<p>R&eacute;cup&eacute;rez votre mot de passe sur l'adresse e-mail que vous avez utilis&eacute;e pour enregistrer le compte.</p>-->
<form action="<?php echo "$self";?>" method="post">
<h4>e-mail compte</h4>
<input type="text" name="email" /><br/><br/>
<input type="submit" value="Enregistrer" name="submit" />
</form>

<?php
}

foot();
