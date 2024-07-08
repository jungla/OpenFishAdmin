<?php

function dbconnect() {

    //$db_host = "62.149.150.53";
    //$db_user = "Sql100561";
    //$db_password = "5a841dd1";
    //$db_name = "Sql100561_1";

    $db_host = "localhost";
    $db_user = "postgres";
    $db_password = "queirolo";
    $db_name = "geospatialdb";

    // connessione al db

    $pgStr = "host=$db_host port=5432 dbname=$db_name user=$db_user";

    $db = pg_connect($pgStr);
}

function odkconnect() {

    //$db_host = "62.149.150.53";
    //$db_user = "Sql100561";
    //$db_password = "5a841dd1";
    //$db_name = "Sql100561_1";

    $db_host = "localhost";
    $db_user = "postgres";
    $db_password = "queirolo";
    $db_name = "odk_prod";


    // connessione al db

    $pgStr = "host=$db_host port=5432 dbname=$db_name user=$db_user";

    $db = pg_connect($pgStr);

}
?>
