<?php
require('top_foot.inc.php');

$_SESSION['where'][0] = 'login';

top();

$username = trim($_SESSION['username']);
$password = trim($_SESSION['password']);

$self = $_SERVER['PHP_SELF'];

if(logged($username,$password)) {

  if ($_POST['submit'] == 'Deconnexion') {
	unset($_SESSION['username']);
	unset($_SESSION['password']);

	$radice = $_SERVER['HTTP_HOST'];

        header("Location: ".$_SESSION['http_host']."/login.php");

    } else {

//header columns

        $query = "SELECT * FROM users.users "
                . "LEFT JOIN users.project ON project.id_user = users.id "
                . "LEFT JOIN users.t_role ON t_role.id = project.t_role "
                . "LEFT JOIN users.t_project ON t_project.id = project.t_project "
                . "WHERE LOWER(nickname) = LOWER('$username')";

        //print $query;

        $r_user = pg_fetch_row(pg_query($query));

        //echo "<table><tr><td>";

        echo "<h4>D&eacute;tails personnels</h4>
        <ul>
        <li>Nom et pr&eacute;nom d'utilisateur: <b>".ucfirst(strtolower($r_user[3]))." ".strtoupper($r_user[4])."</b></li>
        <li>Surnom: <b>$r_user[5]</b></li>
        <li>Adresse email: <b>$r_user[6]</b></li>
        </ul>";

        //echo "</div>";

        print "<p>Selon votre niveau d'acc&egrave;s, vous pouvez ajouter des <b>Administrateurs</b>, <b>Gestionnaires</b> et / ou des <b>Utilisateurs</b> pour chaque ensemble de donn&eacute;es.</p>";

        print "<a href=\"./users/view_users_users.php?action=show\"><i class=\"material-icons\">search</i>Voir Utilisateurs</a><br/>";
        print "<a href=\"./users/input_users_users.php\"><i class=\"material-icons\">create</i>Saisir Utilisateurs</a>";

        print "
        <h4>Actions</h4>
        <ul>
        <li><a href=\"./login-chng-pwd.php\">Changer de mot de passe</a></li>
        <li><a href=\"./login-resc-pwd.php\">R&eacute;cup&eacute;rer mot de passe</a></li>
        <li><a href=\"./login-chng-email.php\">Changer d'adresse email</a></li>
        <!--<li>View activity logs</li>-->
        </ul>
        <br/>
        <form action=\"$self\" method=\"post\">
        <input type=\"submit\" value=\"Deconnexion\" name=\"submit\" />
        </form>";

        //echo "</td>";
        //echo "<td>";
        // print "<h4>Gestion utlisateurs base des donn&eacute;es</h4>
        // <p>La base de donn&eacute;es prend en charge quatre types de comptes: les <b>Administrateurs</b>, <b>Gestionnaires</b>, <b>Utilisateurs</b> et <b>Utilisateurs externe</b>.</p>
        // <ol>
        //
        // <li><b>Administrateurs</b></li>
        // <ul>
        // <li><i>ajouter</i> et <i>supprimer</i> des <b>Gestionnaires</b> et <b>Utilisateurs</b>;</li>
        // <li><i>ajouter</i>, <i>supprimer</i>, <i>visualiser</i> et <i>t&eacute;l&eacute;charger</i> des donn&eacute;es;</li>
        // </ul>
        //
        // <li><b>Gestionnaires</b></li>
        // <ul>
        // <li><i>ajouter</i> et <i>supprimer</i> des <b>Utilisateurs</b>;</li>
        // <li><i>ajouter</i>, <i>supprimer</i>, <i>visualiser</i> et <i>t&eacute;l&eacute;charger</i> des donn&eacute;es;</li>
        // </ul>
        //
        // <li><b>Utilisateurs</b></li>
        // <ul>
        // <li><i>visualiser</i> et <i>t&eacute;l&eacute;charger</i> des donn&eacute;es;</li>
        // </ul>
        //
        // <li><b>Utilisateurs externe</b></li>
        // <ul>
        // <li>Comme les <b>utilisateurs</b>, mais ne peut afficher que les donn&eacute;es de l'ensemble de donn&eacute;es du Gestionnaire. <i>Ces comptes sont destin&eacute;s aux collaborateurs externes</i>.</li>
        // </ul>
        //
        // </ol>";
        // echo "</td></tr></table>";


        foot();

        }

} else {

    if ($_POST['submit'] == 'Connexion') {

        $username = $_POST['username'];
        $password = $_POST['password'];


        if (trim($username) == "" OR trim($password) == "") {
            echo "<b><br/>Veuillez ins&eacute;rer le Surnom et le Mot de passe.</b>"
            . "<br/><br/>"
            . "<input type=\"button\" value=\"Connexion\" onclick=\"history.back();\">";

            $controllo = 1;
        } else {
            $username = htmlentities($username, ENT_QUOTES, 'UTF-8');
            $password = htmlentities($password, ENT_QUOTES, 'UTF-8');


                    //print "Submitted: ".$username;
                    //print "Submitted: ".$password;

            if(logged($username,$password)) {
                $_SESSION['username'] = trim(strtolower($username));
                $_SESSION['password'] = trim($password);

                $radice = $_SERVER['HTTP_HOST'];

                header("Location: ".$_SESSION['http_host']."/login.php");
                $controllo = 1;
            } else {
                echo '<br/><b>Compte non trouv&eacute;.</b><br/><a href="./login.php">Connexion</a><br/><br/>';
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
        <h4>Surnom</h4>
        <input type=\"text\" name=\"username\" />
        <br/><br/>
        <h4>Mot de passe</h4>
        <input type=\"password\" name=\"password\" /><br/><br/>
        <input type=\"submit\" value=\"Connexion\" name=\"submit\" />
        </form>
        <p>
        <ul>
        <li><a href=\"./login-resc-pwd.php\">R&eacute;cup&eacute;rer mot de passe</a></li>
        </ul>
        </p>
        <!--<br/><a href=\"login-resc-pwd.php\">Recover password</a><br/>-->
        <br/><br/>";

        foot();
    }
}
