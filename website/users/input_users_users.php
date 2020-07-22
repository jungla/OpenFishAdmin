<?php
require('../top_foot.inc.php');

$_SESSION['where'][0] = 'login';

$username = $_SESSION['username'];
$password = $_SESSION['password'];

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}
if ($_GET['table'] != "") {$_SESSION['path'][1] = $_GET['table'];}
if ($_GET['action'] != "") {$_SESSION['path'][2] = $_GET['action'];}

$source = $_SESSION['path'][0];
$table = $_SESSION['path'][1];
$action = $_SESSION['path'][2];

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if ($_GET['t_role_usr'] != "") {$_SESSION['t_role_usr'] = $_GET['t_role_usr'];}
if ($_GET['t_project_usr'] != "") {$_SESSION['t_project_usr'] = $_GET['t_project_usr'];}

$t_role_usr = $_SESSION['t_role_usr'];
$t_project_usr = $_SESSION['t_project_usr'];

if(logged($username,$password)) {

  if ($_POST['submit'] == "Enregistrer") {

    $username = $_SESSION['username'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $nickname = strtolower(trim(clean($_POST['nickname'])));
    $email = $_POST['email'];
    //$active = $_POST['active'];

    //print_r($_POST);

    $query = "SELECT count(id) FROM users.users WHERE nickname='$nickname'";

    if(pg_fetch_row(pg_query($query))[0] != 0) {

        print "<p><b>$nickname</b> d&eacute;ja en cours d'utilisation. S'il vous pla&icirc;t choisir un autre surnom.</p>";

    } else {

        // insert user first (it should be unique)
        //$data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = substr(str_shuffle($data), 0, 8);

        $query = "INSERT INTO users.users "
            . "(username, datetime, first_name, last_name, nickname, password, email) VALUES "
            . "('$username', now(), '$first_name', '$last_name', '$nickname', '$password', '$email') RETURNING id";

        $query = str_replace('\'\'', 'NULL', $query);

        if(!$rquery = pg_query($query)) {
    //        print $query;
            msg_queryerror();
        }

        $id_user = pg_fetch_row($rquery)[0];

        $query = "SELECT * FROM users.t_project";

        $rquery = pg_query($query);

        while($results = pg_fetch_row($rquery)) {

            $project = str_replace(' ', '_', $results[1]);
            $t_project = $results[0];
            $t_role = $_POST['t_role_'.$project];
            //print 't_role_'.$project;

            if(isset($_POST[$project])) {

                // insert projects and roles

                $query = "INSERT INTO users.project "
                    . "(username, datetime, t_role, t_project, id_user, active) VALUES "
                    . "('$username', now(), '$t_role', '$t_project', '$id_user',TRUE)";

                $query = str_replace('\'\'', 'NULL', $query);

                print $query."</br>";

                if(!pg_query($query)) {
            //        print $query;
                    msg_queryerror();
                }

                $controllo = 1;

            }
        }

        $htmlmessage = "
        <html>
        <head>
        <title>Bienvenue sur data.gabonbleu.org!</title>
        </head>
        <body>
        <p>Bienvenue sur <a href=\"data.gabonbleu.org\">data.gabonbleu.org</a>!<br/>Il suit vos informations de connexion,</p>
        <table width='200px'>
        <tr>
        <th>Surnom</th>
        <th>Mot de passe</th>
        </tr>
        <tr>
        <td>$nickname</td>
        <td>$password</td>
        </tr>
        </table>
        <p>Stockez vos informations d'identification dans un endroit s&ucirc;r et <b>changez le mot de passe apr&egrave;s la premi&egrave;re connexion</b>.</p>
        </body>
        </html>
        ";

        $txtmessage = "Bienvenue sur data.gabonbleu.org!\n Surnom: $nickname, Mot de passe: $password";

        send_email($htmlmessage, $textmessage, 'Bienvenue sur data.gabonbleu.org', $email);
        //send_email($htmlmessage, $textmessage, 'Bienvenue sur data.gabonbleu.org', 'jeanmensa@gmail.com');

        header("Location: ".$_SESSION['http_host']."/login.php");

    }





}

if (!$controllo) {

    print "<h2>Saisir Utilisateur</h2>";

    ?>
    <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
    <b>Prenom utilisateur</b>
    <br/>
    <input type="text" size="20" name="first_name" />
    <br/>
    <br/>
    <b>Nom utilisateur</b>
    <br/>
    <input type="text" size="20" name="last_name" />
    <br/>
    <br/>
    <b>Surnom</b> (Pas d'espaces ou de caract&egrave;res sp&eacute;ciaux)
    <br/>
    <input type="text" size="20" name="nickname" />
    <br/>
    <br/>
    <b>Email</b>
    <br/>
    <input type="text" size="20" name="email" />
    <br/>
    <br/>
    Selon votre niveau d'acc&egrave;s, vous pouvez ajouter des gestionnaires et / ou des visiteurs pour chaque ensemble de donn&eacute;es.
    <br/>
    <br/>
    <?php

    $query = "SELECT t_project.project, t_role, project.t_role, project.t_project FROM users.users "
            . "LEFT JOIN users.project ON project.id_user = users.id "
            . "LEFT JOIN users.t_role ON t_role.id = project.t_role "
            . "LEFT JOIN users.t_project ON t_project.id = project.t_project "
            . "WHERE LOWER(nickname) = LOWER('$username') AND project.id is not NULL AND t_role < 99 ORDER BY t_project.project";

    //print $query;

    $rquery = pg_query($query);

    print "<table>";

    while($results = pg_fetch_row($rquery)) {
        print "<tr><td><input type=\"checkbox\" name=\"$results[0]\"><b>$results[0]</b></td>";

        print "<td><select name=\"t_role_".str_replace(' ', '_', $results[0])."\">";

        if ($results[1] == 1) {
          $result = pg_query("SELECT id, role FROM users.t_role ORDER BY id");
        } else {
          $result = pg_query("SELECT id, role FROM users.t_role WHERE id > $results[1] ORDER BY id");
        }

        while($row = pg_fetch_row($result)) {
            print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
        }
        print "</select></td></tr>";

    }

    print "</table>";
    ?>
    <br/>
    <br/>
    <input type="submit" value="Enregistrer" name="submit"/>
    </form>
    <br/>

<?php
}

}


foot();
