<?php

$temp = $_FILES['image']['tmp_name'];

$typ = exif_imagetype($temp);
if (!$typ)
    return;
if     ($typ ==  1) $im = imagecreatefromgif($temp);
elseif ($typ ==  2) $im = imagecreatefromjpeg($temp);
elseif ($typ ==  3) $im = imagecreatefrompng($temp);
elseif ($typ ==  6) $im = imagecreatefrombmp($temp);
elseif ($typ == 18) $im = imagecreatefromwebp($temp);
else {
    echo '<div class="lefty"><br>';    
    echo 'Image type not supported: '.mime_content_type($temp);
    echo '<br><br></div>';
    $view->footer();
}

$filename = $pid.'_'.bin2hex(random_bytes(4));
$ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
$image = $filename.'.'.$ext;
move_uploaded_file($temp, 'images/'.$image);

$sx = imagesx($im);
$sy = imagesy($im);
$ts = 200;
$k = min($ts/$sx, $ts/$sy);
$thumb = imagecreatetruecolor($k*$sx, $k*$sy);
imagecopyresampled($thumb, $im, 0, 0, 0, 0, $k*$sx, $k*$sy, $sx, $sy);

imagepng($thumb, 'images/tmb_'.$filename.'.png');
imagedestroy($im);

$db->storeImage($pid, $image);
