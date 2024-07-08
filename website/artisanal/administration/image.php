<?php
require("../../top_foot.inc.php");

$table = $_GET['table'];
$photo_data = $_GET['photo_data'];
$id = $_GET['id'];

// select the image
//$query = "SELECT $photo_data FROM $table WHERE id = $id";
$result = pg_query("SELECT $photo_data FROM $table WHERE id = '$id'");

//print "SELECT $photo_data FROM $table WHERE id = '$id'";

//print pg_num_rows($result);

if(pg_num_rows($result) > 0){
    // if found
    $data = pg_fetch_result($result, $photo_data);
    $unes_image = pg_unescape_bytea($data);

    // specify header with content type,
    // you can do header("Content-type: image/jpg"); for jpg,
    // header("Content-type: image/gif"); for gif, etc.
    header("Content-type: image/png");
    //header('Content-Disposition: attachment; filename="'.$name.'"');
    //display the image data
    print $unes_image;
    //print $data;
    exit;
}

?>
