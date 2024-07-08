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
    $new_pwd = trim($_POST['new_pwd']);

    if ($old_pwd == "" OR $new_pwd == "") {
        echo "<strong><p>Veuillez ins&eacute;rer le nom d'utilisateur et le mot de passe</strong><br/><button onclick=\"history.back();\">Retourner</button></p>";

    } else {

    	  $query = "UPDATE users.users SET password = '$new_pwd' WHERE nickname='$username' AND password='$old_pwd'";

        if(pg_query($query)) {
            $_SESSION['password'] = $new_pwd;
            $password = $_SESSION['password'];
            $nickname = $_SESSION['username'];

            echo "<p><b>Votre mot de passe a &eacute;t&eacute; modifi&eacute; avec succ&egrave;s.</b><br/>"
                . "<button onclick=\"history.back();\">Retourner</button></p>";

            $query = "SELECT email FROM users.users WHERE LOWER(nickname)=LOWER('$username')";
            $rquery = pg_query($query);
            $email = pg_fetch_row($rquery)[0];

            $htmlmessage = "
            <html>
            <head>
            <title>Bienvenue sur data.gabonbleu.org!</title>
            </head>
            <body>
            <p>Bienvenue sur data.gabonbleu.org!<br/>Il suit vos informations de connexion,</p>
            <table width='200px'>
            <tr align='center'>
            <th>Surnom</th>
            <th>Mot de passe</th>
            </tr>
            <tr align='center'>
            <td>$nickname</td>
            <td>$password</td>
            </tr>
            </table>
            <p>Stockez vos informations d'identification dans un endroit s&ucirc;r.</p>
            </body>
            </html>
            ";

            $txtmessage = "Bienvenue sur data.gabonbleu.org! Username: $nickname, Password: $password";

            send_email($htmlmessage,$txtmessage, 'Bienvenue sur data.gabonbleu.org', $email);
            }
        }
        $controllo = 1;

}

if(!$controllo) {

$self = $_SERVER['PHP_SELF'];
?>
<!--<p>Recover your password on the email address you used to register the account.</p>-->
<br/>
<form action="<?php echo "$self";?>" method="post">
<h4>Mot de passe actuel</h4>
<input type="password" name="old_pwd" /><br/>
<h4>Nouveau mot de passe</h4>
<input type="password" name="new_pwd" /><br/><br/>
<input type="submit" value="Enregistrer" name="submit" />
</form>

<?php
}

} elseif ((isset($_GET['key_one']) and isset($_GET['key_two'])) OR (isset($_POST['key_one']) and isset($_POST['key_two']))) {

  if (isset($_GET['key_one'])) {$key_one = $_GET['key_one'];}
  if (isset($_GET['key_two'])) {$key_two = $_GET['key_two'];}
  if (isset($_POST['key_one'])) {$key_one = $_POST['key_one'];}
  if (isset($_POST['key_two'])) {$key_two = $_POST['key_two'];}

  # if key is correct, we can reset password for that email...
  $email = openssl_decrypt($key_one, "AES-128-CTR", "jeanmensa");
  $nickname = openssl_decrypt($key_two, "AES-128-CTR", "jeanmensa");

  if(isset($_POST['submit'])) {

      $new_pwd = trim($_POST['new_pwd']);

      if ($new_pwd == "") {

          echo "<strong><p>Veuillez ins&eacute;rer le nom d'utilisateur et le mot de passe</strong><br/><button onclick=\"history.back();\">Retourner</button></p>";

      } else {

      	  $query = "UPDATE users.users SET password = '$new_pwd' WHERE nickname='$nickname' AND email='$email'";

          if(pg_query($query)) {
              $_SESSION['password'] = $new_pwd;
              $_SESSION['username'] = $nickname;

              echo "<p><b>Votre mot de passe a &eacute;t&eacute; modifi&eacute; avec succ&egrave;s.</b><br/>"
                  . "<button onclick=\"window.location.href='./index.php'\">Retourner</button></p>";

              $htmlmessage = "
              <html>
              <head>
              <title>Bienvenue sur data.gabonbleu.org!</title>
              </head>
              <body>
              <p>Bienvenue sur data.gabonbleu.org!<br/>Il suit vos informations de connexion,</p>
              <table width='200px'>
              <tr align='center'>
              <th>Surnom</th>
              <th>Mot de passe</th>
              </tr>
              <tr align='center'>
              <td>$nickname</td>
              <td>$new_pwd</td>
              </tr>
              </table>
              <p>Stockez vos informations d'identification dans un endroit s&ucirc;r.</p>
              </body>
              </html>
              ";

              $txtmessage = "Bienvenue sur data.gabonbleu.org! Username: $nickname, Password: $new_pwd";

              send_email($htmlmessage, $txtmessage, 'Bienvenue sur data.gabonbleu.org', $email);
              }
          }
          $controllo = 1;

  }

  if(!$controllo) {

  $self = $_SERVER['PHP_SELF'];
  ?>
  <!--<p>Recover your password on the email address you used to register the account.</p>-->
  <br/>
  <form action="<?php echo "$self";?>" method="post">
  <h4>Nouveau mot de passe</h4>
  <input type="password" name="new_pwd" /><br/><br/>
  <input type="submit" value="Enregistrer" name="submit" />
  <input type="hidden" value="<?php print $key_one; ?>" name="key_one" />
  <input type="hidden" value="<?php print $key_two; ?>" name="key_two" />
  </form>

  <?php
  }

} else {
  msg_noaccess();
}




foot();
