<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';

top();

if ($_GET['source'] != "") {$_SESSION['path'][0] = $_GET['source'];}

$table = $_GET['table'];

if ($_GET['action'] == 'download') {

    $filename = 'WMS_'.$table.'.csv';

    if ($table == 'point') {

      $q_id = "SELECT positions.datetime, date_p, speed, navire.navire, ST_X(location), ST_Y(location) "
          . " FROM vms.positions "
          . "LEFT JOIN vms.navire ON navire.id = positions.id_navire "
          . "WHERE date_p < (now() - '30 day'::interval)"
        . "ORDER BY datetime";

        $header = ['date', 'date position', 'vitesse', 'navire', 'lon', 'lat'];
        print $q_id;
        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'production') {

        $q_id = "SELECT captures.id, captures.datetime, captures.username, id_route, captures.maree, captures.lance, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, fishery.species.OBS, fishery.species.FAO, poids, captures.comment, st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f) "
        . " FROM trawlers.captures "
        . "LEFT JOIN trawlers.route ON trawlers.captures.id_route = trawlers.route.id "
        . "LEFT JOIN fishery.species ON trawlers.captures.id_species = fishery.species.id "
        . "ORDER BY route.date, route.lance";

        $header = ['id', 'datetime', 'username', 'id_route', 'maree', 'lance', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'poids echantillion', 'remarque', 'GPS_x debut', 'GPS_y debut', 'GPS_x fin', 'GPS_y fin'];

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'p_lance') {

        $q_id = "SELECT p_lance.id, p_lance.datetime, p_lance.username, id_route, p_lance.maree, p_lance.lance, st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f), fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, fishery.species.OBS, fishery.species.FAO, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi "
        . " FROM trawlers.p_lance "
        . "LEFT JOIN trawlers.route ON trawlers.p_lance.id_route = trawlers.route.id "
        . "LEFT JOIN fishery.species ON trawlers.p_lance.id_species = fishery.species.id "
        . "ORDER BY route.date, route.lance";

        $header = ['id', 'datetime', 'username', 'id_route', 'maree', 'lance', 'GPS_x debut', 'GPS_y debut', 'GPS_x fin', 'GPS_y fin', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'c0_cre', 'c1_cre', 'c2_cre', 'c3_cre', 'c4_cre', 'c5_cre', 'c6_cre', 'c7_cre', 'c8_cre', 'c9_cre', 'c0_poi', 'c1_poi', 'c2_poi', 'c3_poi', 'c4_poi', 'c5_poi', 'c6_poi'];

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'p_day') {

        $q_id = "SELECT p_day.id, p_day.datetime, p_day.username, p_day.maree, p_day.date_d, p_day.lance_d, p_day.lance_f, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, fishery.species.OBS, fishery.species.FAO, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi "
            . "FROM trawlers.p_day "
            . "LEFT JOIN fishery.species ON trawlers.p_day.id_species = fishery.species.id ORDER BY date_d";

        //print $q_id;

        $header = ['id', 'datetime', 'username', 'maree', 'date', 'lance debut', 'lance fin', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'c0_cre', 'c1_cre', 'c2_cre', 'c3_cre', 'c4_cre', 'c5_cre', 'c6_cre', 'c7_cre', 'c8_cre', 'c9_cre', 'c0_poi', 'c1_poi', 'c2_poi', 'c3_poi', 'c4_poi', 'c5_poi', 'c6_poi'];

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'ft_cre') {

        $q_id = "SELECT ft_cre.id, ft_cre.datetime, ft_cre.username, id_route, ft_cre.maree, ft_cre.lance, st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f), fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, fishery.species.OBS, fishery.species.FAO, t_sex.sex, t_maturity.maturity, poids, ft10_cre, ft11_cre, ft12_cre, ft13_cre, ft14_cre, ft15_cre, ft16_cre, ft17_cre, ft18_cre, ft19_cre, ft20_cre, ft21_cre, ft22_cre, ft23_cre, ft24_cre, ft25_cre, ft26_cre, ft27_cre, ft28_cre, ft29_cre, ft30_cre, ft31_cre, ft32_cre, ft33_cre, ft34_cre, ft35_cre, ft36_cre, ft37_cre, ft38_cre, ft39_cre, ft40_cre, ft41_cre, ft42_cre, ft43_cre, ft44_cre, ft45_cre, ft46_cre, ft47_cre, ft48_cre, ft49_cre, ft50_cre, ft51_cre, ft52_cre, ft53_cre, ft54_cre, ft55_cre, ft56_cre, ft57_cre, ft58_cre, ft59_cre, ft60_cre, ft61_cre, ft62_cre, ft63_cre, ft64_cre, ft65_cre, ft66_cre, ft67_cre, ft68_cre, ft69_cre, ft70_cre "
        . " FROM trawlers.ft_cre "
        . "LEFT JOIN trawlers.route ON trawlers.ft_cre.id_route = trawlers.route.id "
        . "LEFT JOIN fishery.species ON trawlers.ft_cre.id_species = fishery.species.id "
        . "LEFT JOIN trawlers.t_sex ON trawlers.ft_cre.t_sex = trawlers.t_sex.id "
        . "LEFT JOIN trawlers.t_maturity ON trawlers.ft_cre.t_maturity = trawlers.t_maturity.id "
        . "ORDER BY route.date, route.lance";

        $header = ['id', 'datetime', 'username', 'id_route', 'maree', 'lance', 'GPS_x debut', 'GPS_y debut', 'GPS_x fin', 'GPS_y fin', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'sex', 'maturite', 'poids', 'ft10_cre', 'ft11_cre', 'ft12_cre', 'ft13_cre', 'ft14_cre', 'ft15_cre', 'ft16_cre', 'ft17_cre', 'ft18_cre', 'ft19_cre', 'ft20_cre', 'ft21_cre', 'ft22_cre', 'ft23_cre', 'ft24_cre', 'ft25_cre', 'ft26_cre', 'ft27_cre', 'ft28_cre', 'ft29_cre', 'ft30_cre', 'ft31_cre', 'ft32_cre', 'ft33_cre', 'ft34_cre', 'ft35_cre', 'ft36_cre', 'ft37_cre', 'ft38_cre', 'ft39_cre', 'ft40_cre', 'ft41_cre', 'ft42_cre', 'ft43_cre', 'ft44_cre', 'ft45_cre', 'ft46_cre', 'ft47_cre', 'ft48_cre', 'ft49_cre', 'ft50_cre', 'ft51_cre', 'ft52_cre', 'ft53_cre', 'ft54_cre', 'ft55_cre', 'ft56_cre', 'ft57_cre', 'ft58_cre', 'ft59_cre', 'ft60_cre', 'ft61_cre', 'ft62_cre', 'ft63_cre', 'ft64_cre', 'ft65_cre', 'ft66_cre', 'ft67_cre', 'ft68_cre', 'ft69_cre', 'ft70_cre'];

        //print $q_id;

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'ft_poi') {

        $q_id = "SELECT ft_poi.id, ft_poi.datetime, ft_poi.username, id_route, ft_poi.maree, ft_poi.lance, st_x(location_d), st_y(location_d), st_x(location_f), st_y(location_f), fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, fishery.species.OBS, fishery.species.FAO, t_measure.measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi, ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, ft70_poi, ft71_poi, ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, ft109_poi, ft110_poi, ft111_poi, ft112_poi "
        . " FROM trawlers.ft_poi "
        . "LEFT JOIN trawlers.route ON trawlers.ft_poi.id_route = trawlers.route.id "
        . "LEFT JOIN fishery.species ON trawlers.ft_poi.id_species = fishery.species.id "
        . "LEFT JOIN trawlers.t_rejete ON trawlers.ft_poi.t_rejete = trawlers.t_rejete.id "
        . "LEFT JOIN trawlers.t_measure ON trawlers.ft_poi.t_measure = trawlers.t_measure.id "
        . "ORDER BY route.date, route.lance";

        $header = ['id', 'datetime', 'username', 'id_route', 'maree', 'lance', 'GPS_x debut', 'GPS_y debut', 'GPS_x fin', 'GPS_y fin', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'measure', 'poids', 'ft1_poi', 'ft2_poi', 'ft3_poi', 'ft4_poi', 'ft5_poi', 'ft6_poi', 'ft7_poi', 'ft8_poi', 'ft9_poi', 'ft10_poi', 'ft11_poi', 'ft12_poi', 'ft13_poi', 'ft14_poi', 'ft15_poi', 'ft16_poi', 'ft17_poi', 'ft18_poi', 'ft19_poi', 'ft20_poi', 'ft21_poi', 'ft22_poi', 'ft23_poi', 'ft24_poi', 'ft25_poi', 'ft26_poi', 'ft27_poi', 'ft28_poi', 'ft29_poi', 'ft30_poi', 'ft31_poi', 'ft32_poi', 'ft33_poi', 'ft34_poi', 'ft35_poi', 'ft36_poi', 'ft37_poi', 'ft38_poi', 'ft39_poi', 'ft40_poi', 'ft41_poi', 'ft42_poi', 'ft43_poi', 'ft44_poi', 'ft45_poi', 'ft46_poi', 'ft47_poi', 'ft48_poi', 'ft49_poi', 'ft50_poi', 'ft51_poi', 'ft52_poi', 'ft53_poi', 'ft54_poi', 'ft55_poi', 'ft56_poi', 'ft57_poi', 'ft58_poi', 'ft59_poi', 'ft60_poi', 'ft61_poi', 'ft62_poi', 'ft63_poi', 'ft64_poi', 'ft65_poi', 'ft66_poi', 'ft67_poi', 'ft68_poi', 'ft69_poi', 'ft70_poi', 'ft71_poi', 'ft72_poi', 'ft73_poi', 'ft74_poi', 'ft75_poi', 'ft76_poi', 'ft77_poi', 'ft78_poi', 'ft79_poi', 'ft80_poi', 'ft81_poi', 'ft82_poi', 'ft83_poi', 'ft84_poi', 'ft85_poi', 'ft86_poi', 'ft87_poi', 'ft88_poi', 'ft89_poi', 'ft90_poi', 'ft91_poi', 'ft92_poi', 'ft93_poi', 'ft94_poi', 'ft95_poi', 'ft96_poi', 'ft97_poi', 'ft98_poi', 'ft99_poi', 'ft100_poi', 'ft101_poi', 'ft102_poi', 'ft103_poi', 'ft104_poi', 'ft105_poi', 'ft106_poi', 'ft107_poi', 'ft108_poi', 'ft109_poi', 'ft110_poi', 'ft111_poi', 'ft112_poi'];

        //print $q_id;

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'poids_taille') {

        $q_id = "SELECT poids_taille.id, datetime, username, maree, t_measure.measure, fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, fishery.species.OBS,fishery.species.FAO, taille, p1, p2, p3, p4, p5 "
        . " FROM trawlers.poids_taille "
        . "LEFT JOIN seiners.t_measure ON trawlers.poids_taille.t_measure = seiners.t_measure.id "
        . "LEFT JOIN fishery.species ON trawlers.poids_taille.id_species = fishery.species.id "
        . "ORDER BY maree";

        $header = ['id', 'datetime', 'username', 'maree', 'measure', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'taille', 'p1', 'p2', 'p3', 'p4', 'p5'];

        print $q_id;

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'route_accidentelle') {

        $q_id = "SELECT route_accidentelle.id, datetime, username, t_fleet.fleet, navire, maree, date, time, t_co.co, lance, st_x(location), st_y(location)  "
        . " FROM trawlers.route_accidentelle "
        . "LEFT JOIN trawlers.t_co ON trawlers.t_co.id = trawlers.route_accidentelle.t_co "
        . "LEFT JOIN trawlers.t_fleet ON trawlers.t_fleet.id = trawlers.route_accidentelle.t_fleet "
        . "ORDER BY route_accidentelle.date, route_accidentelle.time";

        $header = ['id', 'datetime', 'username', 'fleet', 'navire', 'maree', 'date', 'capture/observe', 'lance', 'heure', 'GPS_x', 'GPS_y'];

        print $q_id;

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'captures_mammal') {

        $q_id = "SELECT captures_mammal.id, captures_mammal.datetime, captures_mammal.username, id_route, route_accidentelle.maree, route_accidentelle.date, route_accidentelle.time, st_x(location), st_y(location), fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, fishery.species.OBS, fishery.species.FAO, n_ind, t_sex.sex, taille, t1.condition, t2.condition, preleve, camera, photo, remarque "
        . " FROM trawlers.captures_mammal "
        . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_mammal.id_route "
        . "LEFT JOIN fishery.species ON trawlers.captures_mammal.id_species = fishery.species.id "
        . "LEFT JOIN trawlers.t_sex ON trawlers.captures_mammal.t_sex = trawlers.t_sex.id "
        . "LEFT JOIN trawlers.t_condition t1 ON trawlers.captures_mammal.t_capture = t1.id "
        . "LEFT JOIN trawlers.t_condition t2 ON trawlers.captures_mammal.t_relache = t2.id "
        . "ORDER BY route_accidentelle.date, route_accidentelle.time";

        $header = ['id', 'datetime', 'username', 'id_route', 'maree', 'date', 'time', 'GPS_x', 'GPS_y', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'nombre individue', 'sex', 'taille', 'condition capture', 'condition relache', 'preleve', 'camera', 'photo', 'remarque'];

        print $q_id;

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'captures_requin') {

        $q_id = "SELECT captures_requin.id, captures_requin.datetime, captures_requin.username, id_route, route_accidentelle.maree, route_accidentelle.date, route_accidentelle.time, st_x(location), st_y(location), fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species, fishery.species.OBS, fishery.species.FAO, n_ind, poids, t_sex.sex, taille, t1.condition, t2.condition, preleve, camera, photo, remarque "
        . " FROM trawlers.captures_requin "
        . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_requin.id_route "
        . "LEFT JOIN fishery.species ON trawlers.captures_requin.id_species = fishery.species.id "
        . "LEFT JOIN trawlers.t_sex ON trawlers.captures_requin.t_sex = trawlers.t_sex.id "
        . "LEFT JOIN trawlers.t_condition t1 ON trawlers.captures_requin.t_capture = t1.id "
        . "LEFT JOIN trawlers.t_condition t2 ON trawlers.captures_requin.t_relache = t2.id "
        . "ORDER BY route_accidentelle.date, route_accidentelle.time";

        $header = ['id', 'datetime', 'username', 'id_route', 'maree', 'date', 'time', 'GPS_x', 'GPS_y', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'nombre individue', 'poids', 'sex', 'taille', 'condition capture', 'condition relache', 'preleve', 'camera', 'photo', 'remarque'];

        //print $q_id;

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    } else if ($table == 'captures_tortue') {

        $q_id = "SELECT captures_tortue.id, captures_tortue.datetime, captures_tortue.username, id_route, route_accidentelle.maree, route_accidentelle.date, route_accidentelle.time, st_x(location), st_y(location), fishery.species.francaise, fishery.species.family, fishery.species.genus, fishery.species.species,  fishery.species.OBS,  fishery.species.FAO, n_ind, t_sex.sex, length, width, captures_tortue.ring, p1.ring, code_1, p2.ring, code_2, t1.condition, t2.condition, resumation, resumation_res, preleve, camera, photo, remarque "
        . " FROM trawlers.captures_tortue "
        . "LEFT JOIN trawlers.route_accidentelle ON trawlers.route_accidentelle.id = trawlers.captures_tortue.id_route "
        . "LEFT JOIN fishery.species ON trawlers.captures_tortue.id_species = fishery.species.id "
        . "LEFT JOIN trawlers.t_sex ON trawlers.captures_tortue.t_sex = trawlers.t_sex.id "
        . "LEFT JOIN trawlers.t_condition t1 ON trawlers.captures_tortue.t_capture = t1.id "
        . "LEFT JOIN trawlers.t_condition t2 ON trawlers.captures_tortue.t_relache = t2.id "
        . "LEFT JOIN trawlers.t_ring p1 ON trawlers.captures_tortue.position_1 = p1.id "
        . "LEFT JOIN trawlers.t_ring p2 ON trawlers.captures_tortue.position_2 = p2.id "
        . "ORDER BY route_accidentelle.date, route_accidentelle.time";

        $header = ['id', 'datetime', 'username', 'id_route', 'maree', 'date', 'time',  'GPS_x', 'GPS_y', 'espece nom francaise', 'famille', 'genus', 'espece', 'code Obs', 'code FAO', 'nombre individue', 'sexe', 'longueur', 'largeur', 'Bague 1', 'Bague 1 position', 'Bague 1 code', 'Bague 2 position', 'Bague 2 code', 'condition capture', 'condition relache', 'tentatif reanimation', 'reanimation reussie', 'preleve', 'camera', 'photo', 'remarque'];

        print $q_id;

        $r_id = pg_query($q_id);

        write2CSV($filename,$r_id,$header);

    }

}
