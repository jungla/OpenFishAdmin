<?php

function DMS2DD($deg,$min,$sec) {
// Converting DMS ( Degrees / minutes / seconds ) to decimal format
return $deg+((($min*60)+($sec))/3600);
}

function DD2DMS($dec) {
// Converts decimal format to DMS ( Degrees / minutes / seconds )
    $vars = explode(".",$dec);
    $deg = $vars[0];
    $tempma = "0.".$vars[1];

    $tempma = $tempma * 3600;
    $min = floor($tempma / 60);
    $sec = $tempma - ($min*60);

return array("deg"=>$deg,"min"=>$min,"sec"=>$sec);
}

function DM2DD($deg,$min) {
// Converting DMS ( Degrees / minutes / seconds ) to decimal format
return $deg+$min/60;
}

function DD2DM($dec) {
    // Converts decimal format to DMS ( Degrees / minutes / seconds )
    $vars = explode(".",$dec);
    $deg = $vars[0];
    $min = "0.".$vars[1];
return array("deg"=>$deg,"min"=>$min);
}

// DATETIME

function mdy2ymd($date) {
    // converts date from mm/dd/yyyy (Excel format) to yyyy-mm-dd (postgres format)
    $exp = explode('/',$date);
    return trim($exp[2].'-'.$exp[0].'-'.$exp[1]);
}

function check_date($date) {
    // check date to be MM/DD/YYYY
    $date = mdy2ymd($date);
    $exp = explode('/',$date);
    if ($exp[0] > 3000 or $exp[0] < 1000 or $exp[1] < 0 or $exp[1] > 12 or $exp[2] < 0 or $exp[2] > 31) {
        $out = FALSE;
    } else {
        $out = TRUE;
    }
    return $out;
}

function check_time($time) {
// check time format to be HH:MM or HH:MM:SS
    $exp = explode(':',$time);
    if (count($exp)<2 or $exp[0] > 24 or $exp[0] < 0 or $exp[1] < 0 or $exp[1] > 60 or $exp[2] < 0 or $exp[2] > 60) {
    $out = FALSE;
    } else {
    $out = TRUE;
    }
return $out;
}

// DATA FORMATTING

function check_number($val) {
    if (!is_numeric($val)) {
        $out = 'NaN';
    } else {
        $out = $val;
    }
return $out;
}


// PHP functions

function logged($username,$password) {
  if(isset($username) AND isset($password)) {
    $username = trim($username);
    $password = trim($password);
  	$query = "SELECT * FROM users.users
  	WHERE LOWER(nickname) = LOWER('$username')
  	AND password = '$password'";

    $rquery = pg_query($query);
        if(pg_num_rows($rquery) > 0) {
            return True; # username and password found
        } else {
            return False; # username and password not found
        }
    } else {
        return False; # username and password not set
      }
}

function project($username,$t_project) {
    if(isset($username) AND isset($t_project)) {
	$query = "SELECT * FROM users.users WHERE nickname = '$username' AND t_project = $t_project";
        #print $query;
            if(pg_num_rows(pg_query($query))> 0) {
                return True; # username and password found
            } else {
                return False; # username and password not found
            }
        } else {
            return False; # username and password not set
        }
}

function right_write($username,$t_project,$t_role) {
    if(isset($username) AND isset($t_project)) {
	$query = "SELECT * FROM users.users "
        . "LEFT JOIN users.project ON users.project.id_user = users.users.id "
        . "WHERE LOWER(nickname) = LOWER('$username') AND t_project = $t_project AND t_role <= $t_role";
        #print $query;
            if(pg_num_rows(pg_query($query))> 0) {
                return True; # username and password found
            } else {
                return False; # username and password not found
            }
        } else {
            return False; # username and password not set
        }
}

function right_read($username,$t_project) {
    if(isset($username) AND isset($t_project)) {
	$query = "SELECT * FROM users.users "
        . "LEFT JOIN users.project ON users.project.id_user = users.users.id "
        . "WHERE LOWER(nickname) = LOWER('$username') AND t_project = '$t_project' OR t_role != 99";
        #print $query;
            if(pg_num_rows(pg_query($query))> 0) {
                return True; # username and password found
            } else {
                return False; # username and password not found
            }
        } else {
            return False; # username and password not set
        }
}



function msg_noaccess(){
    echo "<h4>Acc&egrave;s refus&eacute;</h4>"
    . "<p>Veuillez v&eacute;rifier vos identifiants de <a href=\"http://".filter_input(INPUT_SERVER, 'HTTP_HOST')."/login.php\">connexion</a> ou contacter <a href=\"mailto:jean.mensa@gabonbleu.org.com?Subject=User%20Access%20Request\" target=\"_top\">l'administrateur</a>.</p>";
}


function msg_queryerror(){
    echo "<p><b>Erreur inconnue</b>. Veuillez signaler le message ci-dessus &agrave; <i>jean.mensa@gabonbleu.org</i>.</p>";
}

function name2label($name) {
    # databases
    if ($name == "artisanal fishery")   $label = "artisanal";
    # tables
    if ($name == "capture recods")      $label = "captures";
    if ($name == "fishing effort")      $label = "effort";
    if ($name == "size flotille")       $label = "flotille";
    if ($name == "market price")        $label = "market";

    return $label;
}

function label2name($label) {
    # databases
    if ($label == "duplicate")      $name = "Liste Doublons";
    if ($label == "maintenance")    $name = "Manutention de Systeme";
    if ($label == "artisanal")      $name = "Statistiques de P&ecirc;che Artisanale Maritime";
    if ($label == "autorisation")   $name = "Autorisations de P&ecirc;che Artisanale Maritime";
    if ($label == "infractions")    $name = "Infractions de P&ecirc;che Artisanale Maritime";
    if ($label == "pelagic")        $name = "VMS P&ecirc;che Artisanale Maritime";
    if ($label == "seiners")        $name = "Programme Observateurs P&ecirc;che a la Senne";
    if ($label == "trawlers")       $name = "Programme Observateurs P&ecirc;che Chalutier";
    if ($label == "thon")           $name = "Declaration Capitaine P&ecirc;che Thoniere";
    if ($label == "crevette")       $name = "Declaration Capitaine P&ecirc;che Crevettier";
    if ($label == "poisson")        $name = "Declaration Capitaine P&ecirc;che Poissonnier";
    if ($label == "vms")            $name = "VMS P&ecirc;che Industrielle";
    if ($label == "themis")         $name = "D&eacute;tails Navire";
    if ($label == "license")        $name = "Autorisations de P&ecirc;che Industrielle";
    if ($label == "obs_catches")    $name = "Programme Observateurs Sanctuaire Requins";


    # tables
    if ($label == "effort")         $name = "- Effort de P&ecirc;che";
    if ($label == "fleet")          $name = "- Informations sur la flotte";
    if ($label == "market")         $name = "- Prix &agrave; la vente";
    if ($label == "licenses")       $name = "- Autorisations";
    if ($label == "validate")       $name = "- Validation";
    if ($label == "pirogue")        $name = "- Pirogues";
    if ($label == "owner")          $name = "- Propri&eacute;taires";
    if ($label == "fisherman")      $name = "- P&ecirc;cheurs";
    if ($label == "carte")          $name = "- Cartes de p&ecirc;cheur";
    if ($label == "infraction")     $name = "- Infractions";
    if ($label == "track")          $name = "- Pistes GPS";
    if ($label == "lkp")            $name = "- Derni&egrave;re position connue";
    if ($label == "point")          $name = "- Points GPS";
    if ($label == "navire")         $name = "- Navires";
    if ($label == "route")          $name = "- Route";
    if ($label == "objet")          $name = "- Objet";
    if ($label == "thon_ret")       $name = "- Thon retenue";
    if ($label == "thon_rej")       $name = "- Thon rejete";
    if ($label == "thon_rej_taille")$name = "- Taille thon rejete";
    if ($label == "prise_access")   $name = "- Capture associ&eacute;e";
    if ($label == "prise_access_taille")   $name = "- Taille capture associ&eacute;e";
    if ($label == "production")     $name = "- Capture par Lanc&eacute;";
    if ($label == "p_lance")        $name = "- Rapport Production par Lanc&eacute;";
    if ($label == "p_day")          $name = "- Rapport Production Quotidien";
    if ($label == "cm_cre")          $name = "- Cat&eacute;gorie de March&eacute; de Crevette";
    if ($label == "cm_poi")          $name = "- Cat&eacute;gorie de March&eacute; de Poisson";
    if ($label == "ft_cre")          $name = "- Fr&eacute;quence de Taille de Crevette";
    if ($label == "ft_poi")          $name = "- Fr&eacute;quence de Taille de Poisson";
    if ($label == "poids_taille")   $name = "- Rapport Poids-Taille";
    if ($label == "route_accidentelle")   $name = "- Esp&eacute;ces sensibles";
    if ($label == "captures_mammal")        $name = "- Capture mammif&eacute;re marin";
    if ($label == "captures_requin")         $name = "- Capture requin, rais, molas";
    if ($label == "maree")         $name = "- Rapport Mar&eacute;e";
    if ($label == "lance")         $name = "- Rapport Lance";
    if ($label == "fishes")        $name = "- Especes Cibles";
    if ($label == "sharks")        $name = "- Requins et Raies";
    if ($label == "turtles")        $name = "- Tortues Marines";
    if ($label == "mammals")        $name = "- Mammiferes Marines";
    if ($label == "actions")        $name = "- Actions de Peche";

    return $name;
}

function cols2name($label,$table) {
    if ($table == "peche_artisanal.captures") {
        if ($label == "datetime_d") $name = "<b>Date and time depart</b> (yyyy-mm-dd hh:mm:ss)";
        if ($label == "datetime_r") $name = "<b>Date and time return</b> (yyyy-mm-dd hh:mm:ss)";
        if ($label == "obs_name") $name = "<b>Enqu&ecirc;teur</b>";
        if ($label == "t_site") $name = "<b>D&eacute;barcad&egrave;re</b>";
        if ($label == "fish_name") $name = "<b>Nom du p&ecirc;cheur</b>";
        if ($label == "license") $name = "<b>Autorisation de p&ecirc;che</b>";
        if ($label == "t_net") $name = "<b>Type of net</b>";
        if ($label == "net_s") $name = "<b>Fishing net size</b> (cm)";
        if ($label == "net_l") $name = "<b>Fishing net Length</b> (m)";
        if ($label == "n_days") $name = "<b>Number of days at sea</b>";
        if ($label == "t_group") $name = "<b>Species group</b>";
        if ($label == "t_species") $name = "<b>Species</b>";
        if ($label == "sample_s") $name = "<b>Sample size</b> (kg)";
        if ($label == "n_ind") $name = "<b>Number of individuals</b>";

    }

    return $name;
}

function write2CSV($filename,$r_id,$header) {
    function stripFunction(&$item, $key) {
      $item = html_entity_decode($item, ENT_QUOTES, 'UTF-8');
    }

    ob_end_clean();
    header('Content-Type:text/csv');
    header('Content-Disposition:attachment;filename='.$filename);

    $output = fopen("php://output",'w') or die("Can't open php://output");

    fputs($output, $bom = (chr(0xEF).chr(0xBB).chr(0xBF)));

    //writes table's header
    //$str = htmlspecialchars_decode($str, 'ENT_QUOTES');

    array_walk_recursive($header , 'stripFunction');

    fputcsv($output, $header, ";");
    //fputcsv($output, array_map('html_entity_decode',array_values($header)), ";");

//html_entity_decode($string, ENT_QUOTES | ENT_XML1, 'UTF-8')

    //writes table's content
    while ($record = pg_fetch_row($r_id)) {
      array_walk_recursive($record , 'stripFunction');

      fputcsv($output, $record, ";");
    //  fputcsv($output, array_map('html_entity_decode',array_values($record)), ";");
    }
    fclose($output) or die("Can't close php://output");

    die();
}

function write_Array2CSV($filename,$array,$header) {
    ob_end_clean();
    header('Content-Type:application/csv');
    header('Content-Disposition:attachment;filename='.$filename);

    $output = fopen("php://output",'w') or die("Can't open php://output");

    //writes table's header
    fputcsv($output, $header);

    //writes table's content
    foreach ($array as $record) {
        fputcsv($output, $record);
    }
    fclose($output) or die("Can't close php://output");

    die();
}

function formatSpecies($common_name,$family,$genus,$species) {
    if ($common_name == "") {
        return $family." ".$genus." ".$species;
    } else {
        return $family." ".$genus." ".$species." [".$common_name."]";
    }
}

function formatSpeciesFAO($FAO,$common_name,$family,$genus,$species) {
    if ($common_name == "") {
      if ($FAO != '') {
        return $FAO." - ".$family." ".$genus." ".$species;
      } else {
        return $family." ".$genus." ".$species;
      }
    } else {
      if ($FAO != '') {
        return $FAO." - ".$family." ".$genus." ".$species." [".$common_name."]";
      } else {
        return $family." ".$genus." ".$species." [".$common_name."]";
      }

    }
}

function formatSpeciesAnalysis($FAO,$common_name) {
      if ($FAO != "") {
        return $FAO;
      } else {
        return $common_name;
      }
}

function formatSpeciesCommon($common_name,$family,$genus,$species) {
        return $common_name." [".$family." ".$genus." ".$species."]";
}

function pages($start,$step,$num,$pself) {
    echo'<br/><table width="100%" id="no-border"><tr><td width="30%" align="right">';
    //devo sapere dove sono per sapere dove devo andare...

    if ($start>$step) {
        echo "<a href=\"$pself&start=0\">d&eacute;but</a>";
    }

    echo '</td><td width="10%" align="right">' ;

    if ($start>0) {
        $start_back = $start - $step;
        echo "<a href=\"$pself&start=$start_back\">pr&eacute;c&eacute;dent</a>";
    }

    echo '</td><td width="20%" align="center">';

    if ($_GET['start'] == 0) {
        if ($step >= $num) {
            echo "<b>$num de $num</b>";
        } else {
            echo "<b>$step de $num</b>";
        }
    } else {
        $var = (intval($start/$step))*$step + 1;
        $var2 = $var + $step - 1;
        if ($var2 > $num) {
            echo "<b>de $var &agrave; $num de $num</b>";
        } else {
        echo "<b>de $var &agrave; $var2 de $num</b>";
        }
    }

    echo '</td>
    <td width="10%" align="left">';

    if ($start + $step < $num) {
        $start_next = $start + $step;
        echo "<a href=\"$pself&start=$start_next\">ensuite</a>";
    }

    echo '</td>
    <td width="30%" align="left">';

    if ($start + $step < $num) {
        $pages = intval(($num-1) / $step)+1;
        $end = intval($pages -1)*$step;
        echo "<a href=\"$pself&start=$end\">fin</a>";
    }

    echo '
    </td>
    </tr>
    </table>
    ';
}

function comma2dot($string) {
    return str_replace(',', '.', $string);
}

function clean($string) {
   return preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $string)); // Removes special chars.
}

function send_email($htmlmessage, $textmessage, $subject, $email_to) {

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsMAIL(); // enable SMTP

    $mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
    $mail->Host = "smtps.aruba.it";
    $mail->IsHTML(true);
    $mail->CharSet = "text/html; charset=UTF-8;";
    $mail->Subject   = $subject;
    $mail->setFrom('postmaster@gabonbleu.org','GabonBleu.org');

    $mail->Priority = 1;                        //    NULL → NO-SET, 1 → HIGH, 3 → NORMAL, 5 → LOW..
    $mail->Username = 'postmaster@gabonbleu.org';                 // SMTP username
    $mail->Password = 'alpha8etA123';                           // SMTP password

    $mail->DKIM_domain = 'gabonbleu.org';
    //See the DKIM_gen_keys.phps script for making a key pair -
    //here we assume you've already done that.
    //Path to your private key:
    $mail->DKIM_private = 'dkim_private.pem';
    $mail->DKIM_selector = '1531237392.gabonbleu';
    //Put your private key's passphrase in here if it has one
    #$mail->DKIM_passphrase = '';
    //The identity you're signing as - usually your From address
    $mail->DKIM_identity = $mail->From;
    //Suppress listing signed header fields in signature, defaults to true for debugging purpose
    #$this->mailer->DKIM_copyHeaderFields = false;
    //Optionally you can add extra headers for signing to meet special requirements
    #$this->mailer->DKIM_extraHeaders = ['List-Unsubscribe', 'List-Help'];

    $mail->AltBody  =  $textmessage;

    $mail->Body      = $htmlmessage;
    $mail->AddAddress($email_to);
    $mail->AddReplyTo('postmaster@gabonbleu.org','postmaster@gabonbleu.org');

//    $file_to_attach = './reports/latest.kml';
//    $mail->AddAttachment( $file_to_attach , 'latest.kml' );

    $mail->Send();
}

function upload_photo($file_name) {
    clearstatcache();
    if ($file_name != "") {

        if (in_array(mime_content_type($file_name),['image/png', 'image/jpeg', 'image/gif', 'image/bmp', 'image/tiff'])) {
            if (filesize($file_name)/1024/1024 < 0.5) {

                $image = imagecreatefromstring(file_get_contents($file_name));
//                $exif = exif_read_data($file_name);
//                //print_r($exif);
//                if(!empty($exif['Orientation'])) {
//                    switch($exif['Orientation']) {
//                        case 8:
//                            $image = imagerotate($image,90,0);
//                            break;
//                        case 3:
//                            $image = imagerotate($image,180,0);
//                            break;
//                        case 6:
//                            $image = imagerotate($image,-90,0);
//                            break;
//                    }
//                }
                //$img = fopen(file_get_contents($image), 'r') or die("cannot read image\n");
                //$photo_data = pg_escape_bytea(fread($img, filesize($file_name)));
                //$photo_data = pg_escape_bytea(file_get_contents($image));
                //fclose($img);

                //ob_start();
                clearstatcache();
                imagepng($image, $file_name."out");
                $img = fopen($file_name."out", 'r') or die("cannot read image\n");
                $photo_data = pg_escape_bytea(fread($img, filesize($file_name."out")));
                fclose($img);
                //$image =  ob_get_clean();
                //$photo_data = base64_encode($image);

            } else {
              print "<p><b>Error</b>. L'image est plus grande que <b>500kb</b>.<br/></p>";
              print "<button onClick=\"goBack()\">Retourner</button>";
              foot();
              die();
            }
        } else {
            print "<p><b>Error</b>. Type d'image non support&eacute;<br/></p>";
            print "<button onClick=\"goBack()\">Retourner</button>";
            foot();
            die();
        }


        } else {
            $photo_data = NULL;
        }

    return $photo_data;
}

function rotate_photo($photo_data) {
    clearstatcache();
    $image = imagecreatefromstring(pg_unescape_bytea($photo_data));
    $image = imagerotate($image,90,0);
    //$img = fopen(file_get_contents($image), 'r') or die("cannot read image\n");
    //$photo_data = pg_escape_bytea(fread($img, filesize($file_name)));
    //$photo_data = pg_escape_bytea(file_get_contents($image));
    //fclose($img);
    print $image;

    //ob_start();

    $file_name = sys_get_temp_dir()."/file.png";
    imagepng($image, $file_name);
    $img = fopen($file_name, 'r') or die("cannot read image\n");
    $photo_data = pg_escape_bytea(fread($img, filesize($file_name)));
    fclose($img);
    //$image =  ob_get_clean();
    //$photo_data = base64_encode($image);

    return $photo_data;
}


function print_license($results) {

        //print $q_id;
        $pdf = new FPDF();

        //$filename = 'peche_artisanal_'.$table.'.csv';
        $pdf->AddPage();

        $W = $pdf->GetPageWidth();
        $H = $pdf->GetPageHeight();

        //HEADER

        //$filename = 'peche_artisanal_'.$table.'.csv';

        $pdf->SetFont('Times','B',18);
        $pdf->SetXY($W/3, 10);
        $pdf->MultiCell($W/3,10,'AUTORISATION DE PECHE ARTISANALE',0,'C');
        $pdf->Image('../../img/logo_dgpa.png',30,30,150,120);

        $pdf->SetFont('Times','',8);
        $pdf->SetXY(0, 10);
        $pdf->MultiCell($W/3,4,
        utf8_decode("MINISTERE DE L'AGRICULTURE, DE L'ELEVAGE,\n"
        ."DE LA PECHE ET DE L'ALIMENTATION\n"
        ."-------------------------\n"
        ."SECRETARIAT GENERAL\n"
        ."-------------------------\n"
        ."DIRECTION GENERALE DES PÊCHES\n"
        ."ET DE L'AQUACULTURE\n"
        ."--------------------------"),0,'C');

        $pdf->SetXY($W/3*2, 4);
        $pdf->SetFont('Times','',10);
        //$pdf->MultiCell($W/3,7,utf8_decode("REPUBLIQUE GABONAISE\nUnion - Travail - Justice"),0,'C');
        $pdf->Image('../../img/maternite.png',158,10,34);

        // HEADER license

        $pdf->SetFont('Times','',25);
        $pdf->SetTextColor(0,0,255);

        //$pdf->MultiCell(50,10,"#".$results[0]."/".$results[1]."\n".$results[16],0,'C');

        if ($results[28] != '') {
            $pdf->SetXY($W/3, 35);
            $pdf->MultiCell($W/3,10,'N'.utf8_decode('°').$results[0].' ('.$results[28].')',0,'C');
        } else {
            $pdf->SetXY($W/3, 40);
            $pdf->MultiCell($W/3,10,'N'.utf8_decode('°').$results[0],0,'C');
        }

//        $pdf->MultiCell($W/3,10,'N'.utf8_decode('°').$results[0],0,'C');

        // HEADER Proprietaire

        $pdf->SetXY(145, 40);
        $pdf->SetFont('Times','',10);
        $pdf->MultiCell(60,7,"ANNEE DE VALIDITE ".$results[1],0,'L');

        $pdf->SetXY(145, 47);
        $pdf->MultiCell(60,4,"ACTEUR ",0,'L');

        if ($results[23] == 'Gabonaise') {
            $check_GB = "4";
            $check_ET = "";
        } else {
            $check_GB = "";
            $check_ET = "4";
        }

        $pdf->SetFont('ZapfDingbats','', 13);
        $pdf->SetXY(162, 47);
        $pdf->Cell(4, 4, $check_GB, 1, 0);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(5, 4, 'NATIONAL', 0, 0);

        $pdf->SetXY(162, 52);
        $pdf->SetFont('ZapfDingbats','', 13);
        $pdf->Cell(4, 4, $check_ET, 1, 0);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(5, 4, 'ETRANGER', 0, 0);

        $pdf->SetTextColor(0,0,0);

        $margin_left = 45;

        // box EMBARCATION
        $pdf->SetXY($margin_left, 57);
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(155,10,"CARACTERISTIQUES DE L'EMBARCATION",'','L');
        $pdf->Rect($margin_left, 66, 155, 36);

        $pdf->SetXY($margin_left, 66);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(85,7,
                iconv('UTF-8', 'windows-1252', html_entity_decode("Nom: $results[14]",ENT_QUOTES))."\n"
                . "Type de pirogue: $results[16]\n"
                . "Motrice : $results[8] / $results[9] CV\n"
                . "Cooperative : $results[12]",0,'L');

        $pdf->SetXY(120, 66);
        $pdf->MultiCell(85,7,"N".utf8_decode('°')." d'immatriculation : $results[15]\n"
                . "Debarcadere d'attache : $results[6]\n"
                . "Debarquement obligatoire 1 : $results[7]\n"
                . "Debarquement obligatoire 2 : $results[27]");

//              . "Numero plaque : $results[27]",0,'L');

//        Nom : La Grace De Dieu                      N° d’immatriculation : L. 021/13
//        Type de pirogue : Bois                      Site de débarquement obligatoire : Ambowè
//        Marque / Puissance motrice: Yamaha/ 40 CV   Débarcadère d’attache : Akiliba
//        Numero de pêcheurs minimum à bord : 1       Coopérative : Indépendant

        // box PROPRIETAIRE
        $pdf->SetXY($margin_left, 103);
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(155,10,"IDENTIFICATION DU PROPRIETAIRE",'','L');
        $pdf->Rect($margin_left, 112,155,27);

        $pdf->SetXY($margin_left, 112);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(85,7,iconv('UTF-8', 'windows-1252', html_entity_decode("Nom : ".strtoupper($results[18])." ".ucfirst($results[17]),ENT_QUOTES))."\n"
                . "ID : $results[20] $results[21]",0,'L');

        $pdf->SetXY(120, 112);
        $pdf->MultiCell(85,7,"Nationalite : $results[23] \n"
                . "Tel. : $results[24] \n"
                . iconv('UTF-8', 'windows-1252', html_entity_decode("Residence : $results[6]",ENT_QUOTES)),0,'L');

//        Nom du propriétaire : ININGOUET Isabelle                Nationalité : Gabonaise
//        Type et numéro de pièce d’identification : CNI/  N° 1032-000465857-2,7
//        Résidence : Haut de Gué gué                     Tel. 07 51 72 20

        // box TECHNIQUES
        $pdf->SetXY($margin_left, 144);
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(153,7,"TECHNIQUES ET ENGINS DE PECHE AUTORISEES",'','L');
        $pdf->Rect($margin_left, 151, 155,54);

        //$pdf->SetXY($margin_left, 155);
        //$pdf->SetFont('Times','B',12);
        //$pdf->MultiCell(55,7,"CODE BARRE",'B','L');

        //              Numoro license | Nom pirogue | Immatriculation | Debarquadere obb | Nom Proprietaire | Prenom Prop | Nationalite | Type document | Numore Carte ID | Num telephone | Espece sible 1 / 2 | Engine 1 /2 | Coop
        // $string_in = $results[0]."|".$results[14]."|".$results[15]."|".$results[5]."|".$results[17]."|".$results[18]."|".$results[23]."|".$results[21]."|".$results[20]."|".$results[24]."|".$results[4]."|".$results[5]."|".$results[2]."|".$results[3]."|".$results[12];

        //              Numoro license | Coop | Nom pirogue | Immatriculation | Debarquadere obb | Nom Proprietaire | Prenom Prop | Nationalite | Espece sible 1 / 2 | Engine 1 /2
        $string_in =    $results[0]."|".$results[12]."|".$results[14]."|".$results[15]."|".$results[5]."|".$results[17]."|".$results[18]."|".$results[23]."|".$results[4]."|".$results[5]."|".$results[2]."|".$results[3];


        $key_in = ['|','Q','W','E','R','T','Y','U','I','O','P','A','S','D','F','G','H','J','K','L','Z','X','C','V','B','N','M','q',
            'w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m','[',']','!','@',
            '#','$','%','^','&','*','(',')','1','2','3','4','5','6','7','8','9','0','-','_','=','+','~'];

        $key_out = ['|','S','-','W','9','7','Q','m','x','^','w','q','f','E','0','C','R','U','!','V','N','8','6','H','O','=','_',')',
            'D','1','i','e','a','Z','~','Y','5','c','F','s','G','T','M','d','g','o','*','P','b','X','j','+','K','(','$','t',
            '2','h','u','r','[','n','J','z','p','4','@','k','A','y','&','L','#','l','3',']','B','I','%','v'];


//        | Q W E R T Y U I O P A S D F G H J K L Z X C V B N M q w e r t y u i o p a s d f g h j k l z x c v b n m [ ] ! @ # $ % ^ & * ( ) 1 2 3 4 5 6 7 8 9 0 - _ = + ~

//        | S - W 9 7 Q m x ^ w q f E 0 C R U ! V N 8 6 H O = _ ) D 1 i e a Z ~ Y 5 c F s G T M d g o * P b X j + K ( $ t 2 h u r [ n J z p 4 @ k A y & L # l 3 ] B I % v

//        $string_out = '';

//        foreach (str_split($string_in) as $char) {
//            $string_out = $string_out.$key_out[array_search($char,$key_in)];
//            print $char."</br>";
//            print array_search($char,$key_in)."</br>";
//            print $key_out[array_search($char,$key_in)]."</br>";
//        }

//        print $string_out;

        $qrcode = new QRcode($string_in, 'H'); // error level : L, M, Q, H
        $qrcode->displayFPDF($pdf, $margin_left+2, 153, 50);

        $pdf->SetXY(105, 153);
//        $pdf->MultiCell(110,7,"TYPES ET ZONES DE PECHE AUTORISEES",'B','L');
//        $pdf->SetXY(95, 180);
        $pdf->SetFont('Times','',12);
//
        //Engin de pêche 1 : Filet maillant de fond
        //Engin de pêche 2 :
        //Espèces cibles 1 : Gros poissons
        //Espèces cibles 2 :
        //Zone de pêche :

        $pdf->MultiCell(110,7,
                "Engin de peche 1 : ".$results[4]."\n"
                . "Especes cibles 1 : ".$results[2],'','L');

        if ($results[5] != '' OR $results[3] != '') {
            $pdf->SetXY(105, 165);
            $pdf->MultiCell(110,7, "Engin de peche 2 : ".$results[5]."\n"
                . "Especes cibles 2 : ".$results[3],'','L');
        }

        $pdf->SetXY($margin_left, 207);
        $pdf->SetFont('Times','B',12);

        $pdf->SetTextColor(0,0,255);
        $pdf->MultiCell(155,6,"PERIODE DE VALIDITE DE L'AUTORISATION : DU 1/1/".$results[1]. " AU 31/12/".$results[1],1);
        $pdf->SetTextColor(0,0,0);
//
        //$pdf->SetTextColor(0,0,255);

        $pdf->SetXY($margin_left, 214);
        $pdf->SetFont('Times','B',12);
        //$pdf->SetTextColor(255,0,0);
        $pdf->MultiCell(155,6,"MONTANT DE L'AUTORISATION : ".$results[11]. " FCFA",1);

        $pdf->SetXY($margin_left, 221);
        $pdf->SetFont('Times','B',12);
        //$pdf->SetTextColor(255,0,0);
        $pdf->MultiCell(155,6,"NUMERO QUITTANCE : ".$results[10],1);
        $pdf->SetTextColor(0,0,0);

        $pdf->SetXY(135, 232);
        $pdf->SetFont('Times','',10);
        $pdf->MultiCell(100,7,utf8_decode('Fait à Libreville, le'),0,'L');
        $pdf->SetXY(110, 245);
        $pdf->MultiCell(100,4,"P. Le Ministre\nP.O. Le Directeur ".utf8_decode('Général des Pêches')." et de l'Aquaculture",0,'L');
        $pdf->SetXY(120, 272);
        $pdf->SetFont('Times','B',10);
        $pdf->MultiCell(70,4,"Micheline SCHUMMER GNANDJI",'','L');

        // LEFT BOXES
        $pdf->SetXY(7, 65);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(30,10,"VISA\nDP",0,'C');

        $pdf->SetXY(7, 150);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(30,10,"VISA\nCSPA",0,'C');

        $pdf->Output('./autorisations_peche_artisanale_'.$results[0].'.pdf','I');
}

function print_carte($results) {

        //print $q_id;
        $pdf = new FPDF();
        $pdf->AddPage('L',array(85,55),'mm');
        $pdf->SetAutoPageBreak(false,0);

        $W = $pdf->GetPageWidth();
        $H = $pdf->GetPageHeight();
        //$filename = 'peche_artisanal_'.$table.'.csv';
        $pdf->SetFont('Arial','B',6);
        $pdf->SetXY(12, 3);
        $pdf->MultiCell($W/3,5,'REPUBLIQUE',0,'C');
        $pdf->SetXY(43, 3);
        $pdf->MultiCell($W/3,5,'GABONAISE',0,'C');
        //print $width;
        $pdf->Image('../../img/logo_republique.jpg',$W/2-5,2,10);

        $pdf->SetFont('Arial','B',4);
        $pdf->SetXY(0, 5);
        $pdf->MultiCell($W,10,'Union - Travaille - Justice',0,'C');

        $pdf->SetFont('Arial','B',5);
        $pdf->SetFillColor(78,140,246);
        $pdf->SetXY(0, 13);
        $pdf->MultiCell($W,4,'MINISTERE DE L\'AGRICULTURE, DE L\'ELEVAGE, DE LA PECHE ET DE L\'ALIMENTATION',0,'C','true');
        $pdf->SetXY(0, 16);
        $pdf->SetTextColor(255,255,255);
        $pdf->MultiCell($W,4,'DIRECTION GENERALE DES PECHES ET DE L\'AQUACULTURE',0,'C','true');

        $start = 22;
        $row = 3.5;
        $font = 6;
        $b = 0;

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(5, $start);
        $pdf->MultiCell(150, $row, strtoupper($results[5])." ".ucfirst($results[4]),$b,'L');

        $pdf->SetXY(5, $start+$row);

        $pdf->SetFont('Arial','',$font);
        $pdf->Cell(10,$row, 'Ne le:',$b,'L');
        $pdf->SetFont('Arial','B',$font);
        $pdf->Cell(10,$row, $results[6],$b,'L');

        $pdf->SetXY(5, $start+2*$row);

        $pdf->SetFont('Arial','',$font);
        $pdf->Cell(11.5,$row, 'Nationalite:',$b,'L');
        $pdf->SetFont('Arial','B',$font);
        $pdf->Cell(20,$row, $results[9],$b,'L');

        $pdf->SetXY(5, $start+3*$row);

        $pdf->SetFont('Arial','',$font);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(13,$row, 'Cooperative:',$b,'L');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',$font);
        $pdf->Cell(90,$row, $results[2],$b,'L');

        $pdf->SetXY(5, $start+4*$row);

        $pdf->SetFont('Arial','',$font);
        $pdf->Cell(23,$row, 'Immatriculation pirogue:',$b,'L');
        $pdf->SetFont('Arial','B',$font);
        $pdf->Cell(100,$row, $results[3],$b,'L');

        $pdf->SetXY(5, $start+5*$row);

        $pdf->SetFont('Arial','',$font);
        $pdf->Cell(5, $row, 'ID:',$b,'L');
        $pdf->SetFont('Arial','B',$font);
        $pdf->Multicell(120, $row, $results[7]."\n".$results[8],$b,'L');

        $pdf->SetTextColor(255,255,255);
        $pdf->SetXY(0,$H-5);
        $pdf->SetMargins(0,0,0);
        $pdf->MultiCell($W+15,5, "  CARTE DE PECHEUR - ".$results[0]."/".$results[1],0,'L','true');

        $file_name = sys_get_temp_dir()."/photo.png";

        if(file_put_contents($file_name, pg_unescape_bytea($results[10]))) {
            $pdf->Image($file_name,55,21,24);
        }

        // SECOND PAGE
        $pdf->AddPage('L',array(85,53),'mm');

        $pdf->SetTextColor(0,0,0);

        $pdf->SetFont('Arial','',12);
        $pdf->SetXY($W/8,4);
        $pdf->MultiCell($W*3/4,8, "Le Directeur General",0,'C');

        $pdf->SetFont('Arial','',5);
        $pdf->SetXY($W/8,$H-10);
        $pdf->MultiCell($W*3/4,2, "Cette carte est strictement personnelle. \n En cas de perte, veuillez signaler a l'administration de tutelle\nLibreville, Gabon",0,'C');

        //$pdf->SetXY(120,0);
        $qrcode = new QRcode($results[0]."|".$results[14]."|".$results[15]."|".$results[5]."|".$results[17]."|".$results[18]."|".$results[23]."|".$results[21]."|".$results[20]."|".$results[24]."|".$results[4]."|".$results[5]."|".$results[2]."|".$results[3]."|".$results[12], 'H'); // error level : L, M, Q, H

        $qrcode->displayFPDF($pdf, $W/3, 12, $W/3);
        $pdf->Output('./carte_pecheur_artisanale_'.$results[0].'.pdf','I');
}
