<?php
require("./top_foot.inc.php");

top();

$username = $_SESSION['username'];
$password = $_SESSION['password'];

$self = $_SERVER['PHP_SELF'];

if(logged($username,$password)) {

if(isset($_POST['submit'])) {

    $username = $_SESSION['username'];
    $old_pwd = trim($_POST['old_pwd']);
    $email = trim($_POST['email']);

    //print $username;
    //print $old_pwd;

    if ($old_pwd == "" OR $email == "") {
        echo "<strong><p>Veuillez ins&eacute;rer mot de passe et email</strong><br/><button onclick=\"history.back();\">Retourner</button></p>";
        } else {

        $query = "SELECT nickname, password FROM users.users WHERE nickname='$username' AND password='$old_pwd'";
        $rquery = pg_query($query);

        //print $query;

        if (pg_num_rows($rquery) == 0) {
            print "<p>Nous n'avons pas trouv&eacute; le compte associ&eacute; &agrave; l'e-mail <b>".$email."</b><br/><br/>"
                    . "<button onclick=\"history.back();\">Retourner</button></p>";
            foot();
            die();

        } else {
            $query = "UPDATE users.users SET email = '$email' WHERE nickname='$username' AND password='$old_pwd'";

            //print $query;

            if(pg_query($query)) {
                echo "<p><b>Votre email a &eacute;t&eacute; modifi&eacute; avec succ&egrave;s.</b><br/>";
                echo "<button onclick=\"window.location.href='./login.php'\">Retourner</button></p>";

                $htmlmessage = "
                <html>
                <head>
                <title>Bienvenue sur data.gabonbleu.org!</title>
                </head>
                <body>
                <p>Bienvenue sur <a href=\"data.gabonbleu.org\">data.gabonbleu.org</a>!<br/>
                Votre email a &eacute;t&eacute; modifi&eacute; avec succ&egrave;s.
                </body>
                </html>
                ";

                $txtmessage = "Bienvenue sur data.gabonbleu.org!";

                send_email($htmlmessage,$textmessage, 'Bienvenue sur data.gabonbleu.org', $email);

            }
        }
        }

        $controllo = 1;

}


if(!$controllo){

$self = $_SERVER['PHP_SELF'];
?>
<form action="<?php echo "$self";?>" method="post">
<h4>Mot de passe</h4>
<input type="password" name="old_pwd" /><br/><br/>
<h4>Nouveau email</h4>
<input type="email" name="email" /><br/><br/>
<input type="submit" value="Enregistrer" name="submit" />
</form>

<?php
}
} else {
  msg_noaccess();
}

foot();
