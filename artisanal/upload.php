<?php
require("../top_foot.inc.php");

$photo_data = upload_photo($_FILES['data_photo']['tmp_name']);
$table = $_GET['table'];

$query = "UPDATE artisanal.".$table." SET photo_data = '".$photo_data."' WHERE id = '{".$_GET['id']."}'";

$query = str_replace('\'\'', 'NULL', $query);
error_log($query, 0);
pg_query($query);

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
