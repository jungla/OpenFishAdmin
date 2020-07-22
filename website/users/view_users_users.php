<?php
require('../top_foot.inc.php');


$_SESSION['where'][0] = 'login';

$username = $_SESSION['username'];
$password = $_SESSION['password'];

top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

//if ($_GET['t_role_usr'] != "") {$_SESSION['t_role_usr'] = $_GET['t_role_usr'];}
//if ($_GET['t_project_usr'] != "") {$_SESSION['t_project_usr'] = $_GET['t_project_usr'];}
//
//$t_role_usr = $_SESSION['t_role_usr'];
//$t_project_usr = $_SESSION['t_project_usr'];

if(logged($username,$password)) {

//header columns

    if ($_GET['action'] == 'show') {

        // check if user has right to edit
//
//        print "<h4>D&eacute;tails personnels</h4>"
//            . "<ul>"
//            . "<li>Nom et pr&eacute;nom d'utilisateur: <b>$results[3] $results[4]</b></li>"
//            . "<li>Surnom: <b>$results[5]</b></li>"
//            . "<li>Adresse email: <b>$results[6]</b></li>"
//        . "</ul>";

       ?>

        <h4>Liste des utilisateurs</h4>
        <?php

        $query = "SELECT DISTINCT users.id, users.datetime::date, users.username, INITCAP(first_name), UPPER(last_name), nickname, email FROM users.users "
                . "LEFT JOIN users.project ON project.id_user = users.id "
                . "LEFT JOIN users.t_role ON t_role.id = project.t_role "
                . "LEFT JOIN users.t_project ON t_project.id = project.t_project "
                . "WHERE project.id is not NULL "
                . "ORDER BY users.datetime DESC";

        //print $query;

        $r_query = pg_query($query);

        ?>

        <table>
        <tr align="center">
            <td></td>
            <td><b>Date & Utilisateur</b></td>
            <td><b>Nom utilisateur</b></td>
            <td><b>Surnom</b></td>
            <td><b>Email</b></td>
            <td><b>Projets</b></td>
            <td><b>Role</b></td>

        </tr>

        <?php

        while($results = pg_fetch_row($r_query)) {

            $query = "SELECT project, role FROM users.project "
                    . "LEFT JOIN users.t_project ON project.t_project = t_project.id "
                    . "LEFT JOIN users.t_role ON project.t_role = t_role.id "
                    . "WHERE project.id_user = '$results[0]'";

            //print $query;

            $rquery = pg_query($query);

            $nrow = pg_num_rows($rquery);

            print "<tr align=\"center\">";
            print "<td rowspan=$nrow>";

            if($_SESSION['username'] == $results[2]) {
              print "<a href=\"./view_users_users.php?t_role=$t_role_usr&t_project=$t_project_usr&id=$results[0]&action=edit\">Modifier</a><br/>"
                . "<a href=\"./view_users_users.php?t_role=$t_role_usr&t_project=$t_project_usr&id=$results[0]&action=delete\">Effacer</a>";
            }

            print "</td>";

            $results_prj = pg_fetch_row($rquery);

            print "<td rowspan=$nrow>$results[1]<br/>$results[2]</td><td rowspan=$nrow>$results[3]<br/>$results[4]</td><td rowspan=$nrow>$results[5]</td>"
                . "<td rowspan=$nrow>$results[6]</td><td>$results_prj[0]</td><td>$results_prj[1]</td></tr>";

            while($results_prj = pg_fetch_row($rquery)) {
                print "<tr align=\"center\"><td>$results_prj[0]</td><td>$results_prj[1]</td></tr>";
            }

        }
        print "</table>";


    } else if ($_GET['action'] == 'edit') {

        $id = $_GET['id'];

        //find record info by ID
        $q_id = "SELECT users.id, users.first_name, users.last_name, users.email FROM users.users "
                //. "LEFT JOIN users.project ON project.id_user = users.id "
                //. "LEFT JOIN users.t_role ON t_role.id = project.t_role "
                //. "LEFT JOIN users.t_project ON t_project.id = project.t_project "
                . "WHERE users.id = '$id' ";

        //print $q_id;

        $r_id = pg_query($q_id);

        $results_usr = pg_fetch_row($r_id);

        print "<h2>$results_usr[1] $results_usr[2]</h2>";

        ?>
        <form method="post" action="<?php echo $self;?>" enctype="multipart/form-data">
        <b>Pr&eacute;nom utilisateur</b>
        <br/>
        <input type="text" size="20" name="first_name" value="<?php echo $results_usr[1]; ?>"/>
        <br/>
        <br/>
        <b>Nom de famille</b>
        <br/>
        <input type="text" size="20" name="last_name" value="<?php echo $results_usr[2]; ?>"/>
        <br/>
        <br/>
        <b>Email</b>
        <br/>
        <input type="text" size="20" name="email" value="<?php echo $results_usr[3]; ?>"/>
        <br/>
        <br/>
        <p>Selon votre niveau d'acc&egrave;s, vous pouvez ajouter des gestionnaires et / ou des visiteurs pour chaque ensemble de donn&eacute;es.</p>
        <ol>
        <?php

        // list user projects that can be edited by current user

        $query = "SELECT t_project.id, t_role.id, t_project.project, project.id FROM users.users "
                . "LEFT JOIN users.project ON project.id_user = users.id "
                . "LEFT JOIN users.t_role ON t_role.id = project.t_role "
                . "LEFT JOIN users.t_project ON t_project.id = project.t_project "
                . "WHERE users.nickname = '$username' AND project.id is not NULL AND t_role > 0";

        //print $query;

        $rquery = pg_query($query);

        print "<table>";

        while($results = pg_fetch_row($rquery)) {

            // list of projects currently activated for the given user (not same as current user)
            // it's an empty list if user is not in a given project

            $query = "SELECT t_role, t_project.project, t_project.id FROM users.project "
                . "LEFT JOIN users.users ON users.id = project.id_user "
                . "LEFT JOIN users.t_project ON project.t_project = t_project.id "
                . "WHERE users.id = '$id' AND project.t_project = '$results[0]'";

            //print $query;

            $results_prj = pg_fetch_row(pg_query($query));

            print "<tr><td>";
            if ($results_prj[1] == $results[2]) {
                print "<input type=\"checkbox\" name=\"t_project_$results[0]\" checked><b>$results[2]</b>";
            } else {
                print "<input type=\"checkbox\" name=\"t_project_$results[0]\"><b>$results[2]</b>";
            }

            print "</td>";
            //print "<a href=\"./users/input_users_users.php?t_role_usr=$results[2]&t_project_usr=$results[3]\">Saisir Utilisateurs</a>";

            print "<td><select name=\"t_role_$results[0]\">";

            if ($results[1] == 1) {
              $results_rl = pg_query("SELECT id, role FROM users.t_role ORDER BY id");
            } else {
              $results_rl = pg_query("SELECT id, role FROM users.t_role WHERE id > $results[1] ORDER BY id");
            }

            while($row = pg_fetch_row($results_rl)) {

                if ($row[0] == $results_prj[0]) {
                    print "<option value=\"$row[0]\" selected=\"selected\">".$row[1]."</option>";
                } else {
                    print "<option value=\"$row[0]\">".$row[1]."</option>";
                }

            }
            print "</select></td></tr>";

        }

        print "</table>";
        ?>
        <br/>
        <br/>
<!--        <b>Actif</b>
        <br/>
        Oui<input type="radio" name="active" value="TRUE" <?php if ($results_usr[4] == 't') {print 'checked';} ?>>
        No<input type="radio" name="active" value="FALSE" <?php if ($results_usr[4] == 'f') {print 'checked';} ?>>
        <br/>
        <br/>
        -->
        <input type="hidden" value="<?php echo $results_usr[0]; ?>" name="id_user"/>
        <input type="submit" value="Enregistrer" name="submit"/>
        </form>

        <br/>
        <br/>

        <?php

    } else if ($_GET['action'] == 'delete') {

        $id = $_GET['id'];
        $query = "DELETE FROM users.users WHERE id = '$id'";

        //print $query;

        if(!pg_query($query)) {
            msg_queryerror();
    //        print $query;
        }

        $query = "DELETE FROM users.project WHERE id_user = '$id'";

        //print $query;

        if(!pg_query($query)) {
            msg_queryerror();
    //        print $query;
        } else {
            header("Location: ".$_SESSION['http_host']."/users/view_users_users.php?source=users&table=users&action=show&t_role=$t_role&t_project=$t_project");
        }
        $controllo = 1;
    }


if ($_POST['submit'] == "Enregistrer") {

    $username = $_SESSION['username'];
    //$t_project_usr = $_SESSION['t_project']; # this overwrite GET[t_role]

    $first_name = htmlspecialchars($_POST['first_name'],ENT_QUOTES);
    $last_name = htmlspecialchars($_POST['last_name'],ENT_QUOTES);
    $email = htmlspecialchars($_POST['email'],ENT_QUOTES);
    //$active = $_POST['active'];

    $query = "UPDATE users.users SET "
        . "username = '$username', datetime = now(), "
        . "first_name = '$first_name', last_name = '$last_name', "
        . "email = '$email' "
        . "WHERE id = '{".$_POST['id_user']."}'";

    $query = str_replace('\'\'', 'NULL', $query);

    if(!pg_query($query)) {
//        print $query;
        msg_queryerror();
    }

    $query = "SELECT * FROM users.t_project";

    $rquery = pg_query($query);

    while($results = pg_fetch_row($rquery)) {

        $t_project = $results[0];
        $t_role = $_POST['t_role_'.$t_project];
        $id_user = $_POST['id_user'];

        if(isset($_POST['t_project_'.$t_project])) {

          // if it's a new project, add entry, otherwise update roles Optionally

          // check if user exists for that project
          $query = "SELECT count(*) FROM users.project"
              . " WHERE id_user = '{".$id_user."}' AND t_project = '$t_project'";

          $query = str_replace('\'\'', 'NULL', $query);

          print $query."</br>";

          $nrow = pg_fetch_row(pg_query($query))[0];

          if($nrow == 0) {

            $query = "INSERT INTO users.project "
                . "(username, datetime, id_user, t_role, t_project) "
                . "VALUES ('$username', now(), '$id_user', '$t_role', '$t_project')";

            $query = str_replace('\'\'', 'NULL', $query);

            print $query."</br>";

            if(!pg_query($query)) {
        //        print $query;
                msg_queryerror();
            }

          } else {

            // update existing projects and roles

            $query = "UPDATE users.project SET "
                . "username = '$username', datetime = now(), "
                . "t_role = '$t_role' "
                . " WHERE id_user = '{".$id_user."}' AND t_project = '$t_project'";

            $query = str_replace('\'\'', 'NULL', $query);

            print $query."</br>";

            if(!pg_query($query)) {
        //        print $query;
                msg_queryerror();
            }
          }

        } else {

            // delete if project is not set

            $query = "DELETE FROM users.project "
                . " WHERE id_user = '{".$_POST['id_user']."}' AND t_project = '$t_project'";

    //        print $query;
            $query = str_replace('\'\'', 'NULL', $query);

            if(!pg_query($query)) {
        //        print $query;
                msg_queryerror();
            }


        }

//                // send confirmation email with random password
//
//                // the message
//
//                $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
//                $password = substr(str_shuffle($data), 0, 12);
//
//                // use wordwrap() if lines are longer than 70 characters
//
//                $htmlmessage = "
//                <html>
//                <head>
//                <title>Bienvenue sur data.gabonbleu.org!</title>
//                </head>
//                <body>
//                <p>Bienvenue sur <a href=\"data.gabonbleu.org\">data.gabonbleu.org</a>!<br/>Il suit vos informations de connexion,</p>
//                <table>
//                <tr>
//                <th>Username</th>
//                <th>Password</th>
//                </tr>
//                <tr>
//                <td>$nickname</td>
//                <td>$password</td>
//                </tr>
//                </table>
//                <p>Stockez vos informations d'identification dans un endroit s&ucirc;r et <b>changez le mot de passe apr&egrave;s la premi&egrave;re connexion</b>.</p>
//                </body>
//                </html>
//                ";
//
//                $txtmessage = "Bienvenue sur data.gabonbleu.org! Username: $nickname, Password: $password";
//
//                send_email($htmlmessage,$textmessage, 'Bienvenue sur data.gabonbleu.org', $email);

                header("Location: ".$_SESSION['http_host']."/login.php");

            $controllo = 1;

    }
}

        foot();


}
