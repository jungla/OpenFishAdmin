<?php
require("../top_foot.inc.php");

require('../jpgraph/src/jpgraph.php');
require('../jpgraph/src/jpgraph_pie.php');
require('../jpgraph/src/jpgraph_scatter.php');

$maree = $_POST['maree'];

if(right_read($_SESSION['username'],5)) {

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',20);
    $pdf->Cell(190,10,'Rapport de maree '.$maree,1,1,'C');

    // Generale

    $query = "SELECT route.id, navire, maree, t_fleet.fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment,  st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f)  "
    . " FROM trawlers.route "
    . "LEFT JOIN trawlers.t_fleet ON trawlers.t_fleet.id = trawlers.route.t_fleet "
    . "LEFT JOIN vms.navire ON trawlers.route.id_navire = vms.navire.id "
    . "WHERE maree='$maree' ";

    $q_route = pg_fetch_all(pg_query($query));

    $query = "SELECT p_day.id, datetime, username, maree, date_d, lance_d, lance_f, fishery.species.id, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi "
    . " FROM trawlers.p_day "
    . "LEFT JOIN fishery.species ON trawlers.p_day.id_species = fishery.species.id "
    . "WHERE maree='$maree' ORDER BY date_d";

    // print $query;

    $q_p_day = pg_fetch_all(pg_query($query));
    $navire = $q_route[0]['navire'];
    $date_d = $q_p_day[0]['date_d'];
    $date_f = $q_p_day[count($q_p_day)-1]['date_d'];

    $pdf->Ln(10);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(100,10,'Generale',0,1);

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(190,10,'Navire: '.$navire,0,1);
    $pdf->Cell(190,10,'Flottille: '.$q_route[0]['fleet'],0,1);
    $pdf->Cell(190,10,'Debut maree: '.$date_d,0,1);
    $pdf->Cell(190,10,'Fin maree: '.$date_f,0,1);

    $datetime_d = date_create($date_d);
    $datetime_f = date_create($date_f);
    $interval = date_diff($datetime_d, $datetime_f);
    //    print $interval->format('%a days');

    $pdf->Cell(190,10,'Duree maree: '.$interval->format('%a days'),0,1);

    // Production

    foreach($q_p_day as $row) {
        foreach(array_slice($row,10) as $value) {
            $poids += $value;
        }

    }

    foreach($q_route as $row) {
            $poids_rej += $row['reject'];
    }


    $pdf->Ln(10);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(100,10,'Production',0,1);

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(190,10,round($poids/1000,1).' tonnes de Production, soit '.round($poids/($poids_rej+$poids)*100,1).' de la capture totale',0,1);
    $pdf->Cell(190,10,round($poids_rej/1000,2).' tonnes de rejet, soit '.round($poids_rejet/($poids_rej+$poids)*100,1).' de la capture totale',0,1);
    $pdf->Cell(190,10,$q_p_day[count($q_p_day)-1]['lance_f'].urldecode(' lanc%E9s total'),0,1);
    $pdf->Cell(190,10,count($q_route).urldecode(' lanc%E9s %E9chantillonn%E9s'),0,1);



// Espece Sensible Capture

# MAMMAL

    $query = "SELECT n_ind, t_capture, t_relache FROM trawlers.captures_mammal "
    . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_mammal.id_route "
    . "WHERE route_accidentelle.maree=".$maree." "
    . "AND route_accidentelle.t_co = 0";

    //print $query;

    $q_mammal = pg_fetch_all(pg_query($query));

    //print_r($q_mammal);

    foreach($q_mammal as $row) {
        switch ($row['t_capture']) {
            case "0":
                $Vc_M += $row['n_ind'];
                $Tc_M += $row['n_ind'];
                break;
            case "1":
                $Cc_M += $row['n_ind'];
                $Tc_M += $row['n_ind'];
                break;
            case "2":
                $Mc_M += $row['n_ind'];
                $Tc_M += $row['n_ind'];
                break;
            case "3":
                $Fc_M += $row['n_ind'];
                $Tc_M += $row['n_ind'];
                break;
            default:
                $NAc_M += $row['n_ind'];
                $Tc_M += $row['n_ind'];
        }

        switch ($row['t_relache']) {
            case "0":
                $Vr_M += $row['n_ind'];
                $Tr_M += $row['n_ind'];
                break;
            case "1":
                $Cr_M += $row['n_ind'];
                $Tr_M += $row['n_ind'];
                break;
            case "2":
                $Mr_M += $row['n_ind'];
                $Tr_M += $row['n_ind'];
                break;
            case "3":
                $Fr_M += $row['n_ind'];
                $Tr_M += $row['n_ind'];
                break;
            default:
                $NAr_M += $row['n_ind'];
                $Tr_M += $row['n_ind'];
        }

    }

# TORTUE

    $query = "SELECT n_ind, t_capture, t_relache FROM trawlers.captures_tortue "
    . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_tortue.id_route "
    . "WHERE route_accidentelle.maree=".$maree." "
    . "AND route_accidentelle.t_co = 0";

    //print $query;

    $q_tortue = pg_fetch_all(pg_query($query));

    //print_r($q_mammal);

    foreach($q_tortue as $row) {
        switch ($row['t_capture']) {
            case "0":
                $Vc_T += $row['n_ind'];
                $Tc_T += $row['n_ind'];
                break;
            case "1":
                $Cc_T += $row['n_ind'];
                $Tc_T += $row['n_ind'];
                break;
            case "2":
                $Mc_T += $row['n_ind'];
                $Tc_T += $row['n_ind'];
                break;
            case "3":
                $Fc_T += $row['n_ind'];
                $Tc_T += $row['n_ind'];
                break;
            default:
                $NAc_T += $row['n_ind'];
                $Tc_T += $row['n_ind'];
        }

        switch ($row['t_relache']) {
            case "0":
                $Vr_T += $row['n_ind'];
                $Tr_T += $row['n_ind'];
                break;
            case "1":
                $Cr_T += $row['n_ind'];
                $Tr_T += $row['n_ind'];
                break;
            case "2":
                $Mr_T += $row['n_ind'];
                $Tr_T += $row['n_ind'];
                break;
            case "3":
                $Fr_T += $row['n_ind'];
                $Tr_T += $row['n_ind'];
                break;
            default:
                $NAr_T += $row['n_ind'];
                $Tr_T += $row['n_ind'];
        }

    }


# REQUIN
# CARCHARHINIDAE
# SPHYRNIDAE
# TRIAKIDAE

# RAI
# DASYATIDAE
# MOBULIDAE
# RHINOBATIDAE

    $query = "SELECT n_ind, t_capture, t_relache, family FROM trawlers.captures_requin "
    . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_requin.id_route "
    . "LEFT JOIN fishery.species ON trawlers.captures_requin.id_species = species.id "
    . "WHERE route_accidentelle.maree=".$_SESSION['filter']['f_s_maree']." "
    . "AND route_accidentelle.t_co = 0 ";

    //print $query;

    $q_requin = pg_fetch_all(pg_query($query));

    //print_r($q_mammal);

    foreach($q_requin as $row) {

        switch ($row['family']) {
            case "CARCHARHINIDAE":
                switch ($row['t_capture']) {
                    case "0":
                        $Vc_RC += $row['n_ind'];
                        $Tc_RC += $row['n_ind'];
                        break;
                    case "1":
                        $Cc_RC += $row['n_ind'];
                        $Tc_RC += $row['n_ind'];
                        break;
                    case "2":
                        $Mc_RC += $row['n_ind'];
                        $Tc_RC += $row['n_ind'];
                        break;
                    case "3":
                        $Fc_RC += $row['n_ind'];
                        $Tc_RC += $row['n_ind'];
                        break;
                    default:
                        $NAc_RC += $row['n_ind'];
                        $Tc_RC += $row['n_ind'];
                }

                switch ($row['t_relache']) {
                    case "0":
                        $Vr_RC += $row['n_ind'];
                        $Tr_RC += $row['n_ind'];
                        break;
                    case "1":
                        $Cr_RC += $row['n_ind'];
                        $Tr_RC += $row['n_ind'];
                        break;
                    case "2":
                        $Mr_RC += $row['n_ind'];
                        $Tr_RC += $row['n_ind'];
                        break;
                    case "3":
                        $Fr_RC += $row['n_ind'];
                        $Tr_RC += $row['n_ind'];
                        break;
                    default:
                        $NAr_RC += $row['n_ind'];
                        $Tr_RC += $row['n_ind'];
                }
                break;

            case "SPHYRNIDAE":
                switch ($row['t_capture']) {
                    case "0":
                        $Vc_RS += $row['n_ind'];
                        $Tc_RS += $row['n_ind'];
                        break;
                    case "1":
                        $Cc_RS += $row['n_ind'];
                        $Tc_RS += $row['n_ind'];
                        break;
                    case "2":
                        $Mc_RS += $row['n_ind'];
                        $Tc_RS += $row['n_ind'];
                        break;
                    case "3":
                        $Fc_RS += $row['n_ind'];
                        $Tc_RS += $row['n_ind'];
                        break;
                    default:
                        $NAc_RS += $row['n_ind'];
                        $Tc_RS += $row['n_ind'];
                }

                switch ($row['t_relache']) {
                    case "0":
                        $Vr_RS += $row['n_ind'];
                        $Tr_RS += $row['n_ind'];
                        break;
                    case "1":
                        $Cr_RS += $row['n_ind'];
                        $Tr_RS += $row['n_ind'];
                        break;
                    case "2":
                        $Mr_RS += $row['n_ind'];
                        $Tr_RS += $row['n_ind'];
                        break;
                    case "3":
                        $Fr_RS += $row['n_ind'];
                        $Tr_RS += $row['n_ind'];
                        break;
                    default:
                        $NAr_RS += $row['n_ind'];
                        $Tr_RS += $row['n_ind'];
                }
                break;

            case "TRIAKIDAE":
                switch ($row['t_capture']) {
                    case "0":
                        $Vc_RT += $row['n_ind'];
                        $Tc_RT += $row['n_ind'];
                        break;
                    case "1":
                        $Cc_RT += $row['n_ind'];
                        $Tc_RT += $row['n_ind'];
                        break;
                    case "2":
                        $Mc_RT += $row['n_ind'];
                        $Tc_RT += $row['n_ind'];
                        break;
                    case "3":
                        $Fc_RT += $row['n_ind'];
                        $Tc_RT += $row['n_ind'];
                        break;
                    default:
                        $NAc_RT += $row['n_ind'];
                        $Tc_RT += $row['n_ind'];
                }

                switch ($row['t_relache']) {
                    case "0":
                        $Vr_RT += $row['n_ind'];
                        $Tr_RT += $row['n_ind'];
                        break;
                    case "1":
                        $Cr_RT += $row['n_ind'];
                        $Tr_RT += $row['n_ind'];
                        break;
                    case "2":
                        $Mr_RT += $row['n_ind'];
                        $Tr_RT += $row['n_ind'];
                        break;
                    case "3":
                        $Fr_RT += $row['n_ind'];
                        $Tr_RT += $row['n_ind'];
                        break;
                    default:
                        $NAr_RT += $row['n_ind'];
                        $Tr_RT += $row['n_ind'];
                }
                break;

            case "DASYATIDAE":
                switch ($row['t_capture']) {
                    case "0":
                        $Vc_RD += $row['n_ind'];
                        $Tc_RD += $row['n_ind'];
                        break;
                    case "1":
                        $Cc_RD += $row['n_ind'];
                        $Tc_RD += $row['n_ind'];
                        break;
                    case "2":
                        $Mc_RD += $row['n_ind'];
                        $Tc_RD += $row['n_ind'];
                        break;
                    case "3":
                        $Fc_RD += $row['n_ind'];
                        $Tc_RD += $row['n_ind'];
                        break;
                    default:
                        $NAc_RD += $row['n_ind'];
                        $Tc_RD += $row['n_ind'];
                }

                switch ($row['t_relache']) {
                    case "0":
                        $Vr_RD += $row['n_ind'];
                        $Tr_RD += $row['n_ind'];
                        break;
                    case "1":
                        $Cr_RD += $row['n_ind'];
                        $Tr_RD += $row['n_ind'];
                        break;
                    case "2":
                        $Mr_RD += $row['n_ind'];
                        $Tr_RD += $row['n_ind'];
                        break;
                    case "3":
                        $Fr_RD += $row['n_ind'];
                        $Tr_RD += $row['n_ind'];
                        break;
                    default:
                        $NAr_RD += $row['n_ind'];
                        $Tr_RD += $row['n_ind'];
                }
                break;

            case "MOBULIDAE":
                switch ($row['t_capture']) {
                    case "0":
                        $Vc_RM += $row['n_ind'];
                        $Tc_RM += $row['n_ind'];
                        break;
                    case "1":
                        $Cc_RM += $row['n_ind'];
                        $Tc_RM += $row['n_ind'];
                        break;
                    case "2":
                        $Mc_RM += $row['n_ind'];
                        $Tc_RM += $row['n_ind'];
                        break;
                    case "3":
                        $Fc_RM += $row['n_ind'];
                        $Tc_RM += $row['n_ind'];
                        break;
                    default:
                        $NAc_RM += $row['n_ind'];
                        $Tc_RM += $row['n_ind'];
                }

                switch ($row['t_relache']) {
                    case "0":
                        $Vr_RM += $row['n_ind'];
                        $Tr_RM += $row['n_ind'];
                        break;
                    case "1":
                        $Cr_RM += $row['n_ind'];
                        $Tr_RM += $row['n_ind'];
                        break;
                    case "2":
                        $Mr_RM += $row['n_ind'];
                        $Tr_RM += $row['n_ind'];
                        break;
                    case "3":
                        $Fr_RM += $row['n_ind'];
                        $Tr_RM += $row['n_ind'];
                        break;
                    default:
                        $NAr_RM += $row['n_ind'];
                        $Tr_RM += $row['n_ind'];
                }
                break;

            case "RHINOBATIDAE":
                switch ($row['t_capture']) {
                    case "0":
                        $Vc_RR += $row['n_ind'];
                        $Tc_RR += $row['n_ind'];
                        break;
                    case "1":
                        $Cc_RR += $row['n_ind'];
                        $Tc_RR += $row['n_ind'];
                        break;
                    case "2":
                        $Mc_RR += $row['n_ind'];
                        $Tc_RR += $row['n_ind'];
                        break;
                    case "3":
                        $Fc_RR += $row['n_ind'];
                        $Tc_RR += $row['n_ind'];
                        break;
                    default:
                        $NAc_RR += $row['n_ind'];
                        $Tc_RR += $row['n_ind'];
                }

                switch ($row['t_relache']) {
                    case "0":
                        $Vr_RR += $row['n_ind'];
                        $Tr_RR += $row['n_ind'];
                        break;
                    case "1":
                        $Cr_RR += $row['n_ind'];
                        $Tr_RR += $row['n_ind'];
                        break;
                    case "2":
                        $Mr_RR += $row['n_ind'];
                        $Tr_RR += $row['n_ind'];
                        break;
                    case "3":
                        $Fr_RR += $row['n_ind'];
                        $Tr_RR += $row['n_ind'];
                        break;
                    default:
                        $NAr_RR += $row['n_ind'];
                        $Tr_RR += $row['n_ind'];
                }
                break;
        }
    }

    $pdf->Ln(10);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(100,10,'Espece Sensible Capture',0,1);

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(35,10,'',0,0);
    $pdf->Cell(40,10,'',0,0);
    $pdf->Cell(50,10,'Peche',1,0,'C');
    $pdf->Cell(62.5,10,'Rejete',1,1,'C');

    $pdf->Cell(35,10,'',0,0);
    $pdf->Cell(40,10,'',0,0);
    $pdf->Cell(12.5,10,'V',1,0,'C');
    $pdf->Cell(12.5,10,'C',1,0,'C');
    $pdf->Cell(12.5,10,'M',1,0,'C');
    $pdf->Cell(12.5,10,'NA',1,0,'C');

    $pdf->Cell(12.5,10,'V',1,0,'C');
    $pdf->Cell(12.5,10,'C',1,0,'C');
    $pdf->Cell(12.5,10,'M',1,0,'C');
    $pdf->Cell(12.5,10,'F',1,0,'C');
    $pdf->Cell(12.5,10,'NA',1,1,'C');

    // Dauphin

    $pdf->Cell(35,10,'Dauphin',1,0,'C');
    $pdf->Cell(40,10,'',1,0);
    $pdf->Cell(12.5,10,$Vc_M,1,0,'C');
    $pdf->Cell(12.5,10,$Cc_M,1,0,'C');
    $pdf->Cell(12.5,10,$Mc_M,1,0,'C');
    $pdf->Cell(12.5,10,$NAc_M,1,0,'C');

    $pdf->Cell(12.5,10,$Vr_M,1,0,'C');
    $pdf->Cell(12.5,10,$Cr_M,1,0,'C');
    $pdf->Cell(12.5,10,$Mr_M,1,0,'C');
    $pdf->Cell(12.5,10,$Fr_M,1,0,'C');
    $pdf->Cell(12.5,10,$NAr_M,1,1,'C');


    // Tortue

    $pdf->Cell(35,10,'Tortue',1,0,'C');
    $pdf->Cell(40,10,'',1,0);
    $pdf->Cell(12.5,10,$Vc_T,1,0,'C');
    $pdf->Cell(12.5,10,$Cc_T,1,0,'C');
    $pdf->Cell(12.5,10,$Mc_T,1,0,'C');
    $pdf->Cell(12.5,10,$NAc_T,1,0,'C');

    $pdf->Cell(12.5,10,$Vr_T,1,0,'C');
    $pdf->Cell(12.5,10,$Cr_T,1,0,'C');
    $pdf->Cell(12.5,10,$Mr_T,1,0,'C');
    $pdf->Cell(12.5,10,$Fr_T,1,0,'C');
    $pdf->Cell(12.5,10,$NAr_T,1,1,'C');

    // Requin

    $pdf->Cell(35,30,'Requin',1,0,'C');
    $pdf->Cell(40,10,'Carcharhinidae',1,0,'C');
    $pdf->Cell(12.5,10,$Vc_RC,1,0,'C');
    $pdf->Cell(12.5,10,$Cc_RC,1,0,'C');
    $pdf->Cell(12.5,10,$Mc_RC,1,0,'C');
    $pdf->Cell(12.5,10,$NAc_RC,1,0,'C');

    $pdf->Cell(12.5,10,$Vr_RC,1,0,'C');
    $pdf->Cell(12.5,10,$Cr_RC,1,0,'C');
    $pdf->Cell(12.5,10,$Mr_RC,1,0,'C');
    $pdf->Cell(12.5,10,$Fr_RC,1,0,'C');
    $pdf->Cell(12.5,10,$NAr_RC,1,1,'C');

    $pdf->Cell(35,10,'',0,0);
    $pdf->Cell(40,10,'Sphyrnidae',1,0,'C');
    $pdf->Cell(12.5,10,$Vc_RS,1,0,'C');
    $pdf->Cell(12.5,10,$Cc_RS,1,0,'C');
    $pdf->Cell(12.5,10,$Mc_RS,1,0,'C');
    $pdf->Cell(12.5,10,$NAc_RS,1,0,'C');

    $pdf->Cell(12.5,10,$Vr_RS,1,0,'C');
    $pdf->Cell(12.5,10,$Cr_RS,1,0,'C');
    $pdf->Cell(12.5,10,$Mr_RS,1,0,'C');
    $pdf->Cell(12.5,10,$Fr_RS,1,0,'C');
    $pdf->Cell(12.5,10,$NAr_RS,1,1,'C');


    $pdf->Cell(35,10,'',0,0);
    $pdf->Cell(40,10,'Triakidae',1,0,'C');
    $pdf->Cell(12.5,10,$Vc_RT,1,0,'C');
    $pdf->Cell(12.5,10,$Cc_RT,1,0,'C');
    $pdf->Cell(12.5,10,$Mc_RT,1,0,'C');
    $pdf->Cell(12.5,10,$NAc_RT,1,0,'C');

    $pdf->Cell(12.5,10,$Vr_RT,1,0,'C');
    $pdf->Cell(12.5,10,$Cr_RT,1,0,'C');
    $pdf->Cell(12.5,10,$Mr_RT,1,0,'C');
    $pdf->Cell(12.5,10,$Fr_RT,1,0,'C');
    $pdf->Cell(12.5,10,$NAr_RT,1,1,'C');

     // Raie

    $pdf->Cell(35,30,'Raie',1,0,'C');
    $pdf->Cell(40,10,'Dasyatidae',1,0,'C');
    $pdf->Cell(12.5,10,$Vc_RD,1,0,'C');
    $pdf->Cell(12.5,10,$Cc_RD,1,0,'C');
    $pdf->Cell(12.5,10,$Mc_RD,1,0,'C');
    $pdf->Cell(12.5,10,$NAc_RD,1,0,'C');

    $pdf->Cell(12.5,10,$Vr_RD,1,0,'C');
    $pdf->Cell(12.5,10,$Cr_RD,1,0,'C');
    $pdf->Cell(12.5,10,$Mr_RD,1,0,'C');
    $pdf->Cell(12.5,10,$Fr_RD,1,0,'C');
    $pdf->Cell(12.5,10,$NAr_RD,1,1,'C');

    $pdf->Cell(35,10,'',0,0);
    $pdf->Cell(40,10,'Mobulidae',1,0,'C');
    $pdf->Cell(12.5,10,$Vc_RM,1,0,'C');
    $pdf->Cell(12.5,10,$Cc_RM,1,0,'C');
    $pdf->Cell(12.5,10,$Mc_RM,1,0,'C');
    $pdf->Cell(12.5,10,$NAc_RM,1,0,'C');

    $pdf->Cell(12.5,10,$Vr_RM,1,0,'C');
    $pdf->Cell(12.5,10,$Cr_RM,1,0,'C');
    $pdf->Cell(12.5,10,$Mr_RM,1,0,'C');
    $pdf->Cell(12.5,10,$Fr_RM,1,0,'C');
    $pdf->Cell(12.5,10,$NAr_RM,1,1,'C');


    $pdf->Cell(35,10,'',0,0);
    $pdf->Cell(40,10,'Rhinobatidae',1,0,'C');
    $pdf->Cell(12.5,10,$Vc_RR,1,0,'C');
    $pdf->Cell(12.5,10,$Cc_RR,1,0,'C');
    $pdf->Cell(12.5,10,$Mc_RR,1,0,'C');
    $pdf->Cell(12.5,10,$NAc_RR,1,0,'C');

    $pdf->Cell(12.5,10,$Vr_RR,1,0,'C');
    $pdf->Cell(12.5,10,$Cr_RR,1,0,'C');
    $pdf->Cell(12.5,10,$Mr_RR,1,0,'C');
    $pdf->Cell(12.5,10,$Fr_RR,1,0,'C');
    $pdf->Cell(12.5,10,$NAr_RR,1,1,'C');


    $pdf->Ln(10);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(100,10,'Composition des Captures',0,1);

    $query = "SELECT SUM(poids), fishery.species.family "
            . "FROM trawlers.captures "
            . "LEFT JOIN fishery.species ON trawlers.captures.id_species = fishery.species.id "
            . "WHERE maree = '$maree' GROUP BY fishery.species.family";

    $q_capture = pg_fetch_all(pg_query($query));

    //print_r($q_capture);

    foreach($q_capture as $row) {
        $poids_sum += $row['sum'];
    }

    foreach($q_capture as $row) {
        #$poids_sum += $row['sum'];
        if (100*$row['sum']/$poids_sum < 2) {
            $poids_res += 100*$row['sum']/$poids_sum;

        } else {
    //        print round(100*$row['sum']/$poids_sum,1)." ".$row['family']."<br/>";
            $a_poids[] = round(100*$row['sum']/$poids_sum,1);
            $a_family[] = $row['family'];

        }
    }

    $a_poids[] = round($poids_res,1);
    $a_family[] = 'Autre';

    $data = $a_poids;

    $graph = new PieGraph(600,500);
    $graph->SetShadow();

    $graph->title->Set("Pourcentage de presence des familles selon leur poids de capture $maree");
    $graph->title->SetFont(FF_FONT1,FS_BOLD);

    $p1 = new PiePlot($data);
    $p1->SetLegends($a_family);

    $graph->Add($p1);
    $graph->Stroke('./plots/pie_family.png');

    $pdf->Image('./plots/pie_family.png',10,30,190);

    // EFFORT DE PECHE

    $pdf->Ln(500);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(100,10,'Effort de peche VMS',0,1);

    DEFINE('WORLDMAP','../jpgraph/src/Examples/Gabon_map.png');

    $query = "SELECT st_x(location), st_y(location) FROM vms.positions "
            . "LEFT JOIN vms.navire ON vms.navire.id = vms.positions.id_navire "
            . "WHERE vms.navire.navire = '$navire' "
            . "AND vms.positions.date_p < '$date_f' "
            . "AND vms.positions.date_p > '$date_d' ";

    //print $query;

    $q_vms = pg_fetch_all(pg_query($query));

    foreach($q_vms as $row) {
        $x[] = 100-100*(12-$row['st_x'])/(12-5);
        $y[] = 100*(5+$row['st_y'])/(5+2);

    }

    // Data arrays
    $datax = $x;
    $datay = $y;

    // Setup the graph
    $graph = new Graph(600,600);

    // We add a small 1pixel left,right,bottom margin so the plot area
    // doesn't cover the frame around the graph.
    $graph->img->SetMargin(1,1,1,1);
    $graph->SetScale('linlin',0,100,0,100);

    // We don't want any axis to be shown
    $graph->xaxis->Hide();
    $graph->yaxis->Hide();

    // Use a worldmap as the background and let it fill the plot area
    $graph->SetBackgroundImage(WORLDMAP,BGIMG_FILLPLOT);

    // Setup a nice title with a striped bevel background
    $graph->title->Set("Effort de peche");
    $graph->title->SetFont(FF_FONT2,FS_BOLD);
    $graph->title->SetColor('black');
    //$graph->SetTitleBackground('darkgreen',TITLEBKG_STYLE1,TITLEBKG_FRAME_BEVEL);
    //$graph->SetTitleBackgroundFillStyle(TITLEBKG_FILLSTYLE_HSTRIPED,'blue','darkgreen');

    // Finally create the scatterplot
    $sp = new ScatterPlot($datay,$datax);

    // We want the markers to be an image
    $sp->mark->SetType(MARK_FILLEDCIRCLE);
    $sp->mark->SetFillColor('green@0.5');
    $sp->mark->SetColor('black@0.5');
    $sp->mark->SetWidth(5);

    // ...  and add it to the graph
    $graph->Add($sp);

    // .. and output to browser
    $graph->Stroke('./plots/map_effort.png');

    $pdf->Image('./plots/map_effort.png',30,30,150);

    $pdf->Ln(500);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(100,10,'Cart des especes sensible capture et observe',0,1);

    # Mammal

    $query = "SELECT DISTINCT st_x(location), st_y(location) FROM trawlers.captures_mammal "
            . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_mammal.id_route "
            . "WHERE trawlers.route_accidentelle.maree = '$maree' AND location IS NOT NULL";

    $q_sensible = pg_fetch_all(pg_query($query));

    foreach($q_sensible as $row) {
        $x_M[] = 100-100*(12-$row['st_x'])/(12-5);
        $y_M[] = 100*(5+$row['st_y'])/(5+2);
    }


    # Tortue

    $query = "SELECT DISTINCT st_x(location), st_y(location) FROM trawlers.captures_tortue "
            . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_tortue.id_route "
            . "WHERE trawlers.route_accidentelle.maree = '$maree' AND location IS NOT NULL";

    $q_sensible = pg_fetch_all(pg_query($query));

    foreach($q_sensible as $row) {
        $x_T[] = 100-100*(12-$row['st_x'])/(12-5);
        $y_T[] = 100*(5+$row['st_y'])/(5+2);
    }


    # Requin

    $query = "SELECT DISTINCT st_x(location), st_y(location) FROM trawlers.captures_requin "
            . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_requin.id_route "
            . "WHERE trawlers.route_accidentelle.maree = '$maree' AND location IS NOT NULL";

    $q_sensible = pg_fetch_all(pg_query($query));

    foreach($q_sensible as $row) {
        $x_R[] = 100-100*(12-$row['st_x'])/(12-5);
        $y_R[] = 100*(5+$row['st_y'])/(5+2);
    }

    // Setup the graph
    $graph = new Graph(500,500);

    // We add a small 1pixel left,right,bottom margin so the plot area
    // doesn't cover the frame around the graph.
    $graph->img->SetMargin(1,1,1,1);
    $graph->SetScale('linlin',0,100,0,100);

    // We don't want any axis to be shown
    $graph->xaxis->Hide();
    $graph->yaxis->Hide();

    // Use a worldmap as the background and let it fill the plot area
    $graph->SetBackgroundImage(WORLDMAP,BGIMG_FILLPLOT);

    // Setup a nice title with a striped bevel background
    $graph->title->Set("Espece sensible");
    $graph->title->SetFont(FF_FONT2,FS_BOLD);
    $graph->title->SetColor('black');
    //$graph->SetTitleBackground('darkgreen',TITLEBKG_STYLE1,TITLEBKG_FRAME_BEVEL);
    //$graph->SetTitleBackgroundFillStyle(TITLEBKG_FILLSTYLE_HSTRIPED,'blue','darkgreen');

    // Finally create the scatterplot
    if (count($y_M)>0 && count($x_M)>0) {
        $sp_M = new ScatterPlot($y_M,$x_M);
        $sp_M->mark->SetType(MARK_FILLEDCIRCLE);
        $sp_M->mark->SetFillColor('red.5');
        $sp_M->mark->SetColor('black@0.5');
        $sp_M->mark->SetWidth(5);
        $sp_M->SetLegend('Mammal');
        $graph->Add($sp_M);
    }

    if (count($y_T)>0 && count($x_T)>0) {
        $sp_T = new ScatterPlot($y_T,$x_T);
        $sp_T->mark->SetType(MARK_FILLEDCIRCLE);
        $sp_T->mark->SetFillColor('green.5');
        $sp_T->mark->SetColor('black@0.5');
        $sp_T->mark->SetWidth(5);
        $sp_T->SetLegend('Tortue');
        $graph->Add($sp_T);
    }

    if (count($y_R)>0 && count($x_R)>0) {
        $sp_R = new ScatterPlot($y_R,$x_R);
        $sp_R->mark->SetType(MARK_FILLEDCIRCLE);
        $sp_R->mark->SetFillColor('blue.5');
        $sp_R->mark->SetColor('black@0.5');
        $sp_R->mark->SetWidth(5);
        $sp_R->SetLegend('Requin');
        $graph->Add($sp_R);
    }

    //$graph->legend->SetShadow('gray@0.4',5);
    $graph->legend->SetPos(0.1,0.1,'right','top');
    $graph->legend->SetFont(FF_FONT2,FS_BOLD);
    $graph->legend->SetMarkAbsSize(10);
    // .. and output to browser
    $graph->Stroke('./plots/map_sensible.png');


    $pdf->Image('./plots/map_sensible.png',30,30,150);
    $pdf->Output('report_maree_'.$maree.'.pdf','I');

    }
