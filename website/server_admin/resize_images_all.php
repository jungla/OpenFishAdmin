<?php
require("../top_foot.inc.php");

$query = 'SELECT id, photo_data FROM artisanal.fisherman WHERE photo_data IS NOT NULL';
#$query = 'SELECT id, photo_data FROM artisanal.owner WHERE photo_data IS NOT NULL';


#$query = 'SELECT id, photo_data_1 FROM artisanal.pirogue WHERE photo_data_1 IS NOT NULL';
#$query = 'SELECT id, photo_data_2 FROM artisanal.pirogue WHERE photo_data_2 IS NOT NULL';
#$query = 'SELECT id, photo_data_3 FROM artisanal.pirogue WHERE photo_data_3 IS NOT NULL';

print $query."</br>";

$rquery = pg_query($query);

while($result = pg_fetch_row($rquery)) {
    $photo_data = $result[1];
    $id = $result[0];
    print $id."<br/>";

    $image = imagecreatefromstring(pg_unescape_bytea($photo_data));

    $size = getimagesizefromstring(pg_unescape_bytea($photo_data));
//    $sizex = imagesx($image);
//    $sizey = imagesy($image);
    $sizex = $size[0];
    $sizey = $size[1];

    $new_width = 600;
    $new_height = $sizey/$sizex*600;

    // Resample

    print $sizex." : ".$sizey."</br>";

    if ($sizex > 600) {
//            $image_s = imagescale($image,300,$sizey/$sizex*300,$mode=IMG_BICUBIC);
//            imagedestroy($image);
        $image_s = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($image_s, $image, 0, 0, 0, 0, $new_width, $new_height, $sizex, $sizey);

        $file_name = sys_get_temp_dir()."/file.png";
        imagepng($image_s, $file_name);

        $img = fopen($file_name, 'r') or die("cannot read image\n");
        $photo_data = fread($img, filesize($file_name));
        fclose($img);
        clearstatcache();

        //Echo the data inline in an img tag with the common src-attribute
        echo '<img src="data:image/png;base64,'.base64_encode($photo_data).'" width="50px"/><br/>';

//        imagedestroy($image_s);
//
//        $img = fopen($file_name, 'r') or die("cannot read image\n");
//        $photo_data = pg_escape_bytea(fread($img, filesize($file_name)));
//        fclose($img);
//
//        print "<img class=\"img_frame\" width=\"300px\" src=\"../artisanal/image.php?id=$id&table=artisanal.owner&photo_data=photo_data\" /><br/>";


        $query = "UPDATE artisanal.fisherman SET photo_data =  '".pg_escape_bytea($photo_data)."' WHERE id = '$id'";
        pg_query($query);
        //print $query;

    }
}


//    print "<img class=\"img_frame\" width=\"300px\" src=\"../artisanal/image.php?id=$id&table=artisanal.owner&photo_data=photo_data\" /><br/>";



/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
