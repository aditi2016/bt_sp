<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 12/13/16
 * Time: 2:36 PM
 */
/*
 *
 *
 *
 *
$png = imagecreatefrompng('./mark.png');
$jpeg = imagecreatefromjpeg('./image.jpg');

list($width, $height) = getimagesize('./image.jpg');
list($newwidth, $newheight) = getimagesize('./mark.png');
$out = imagecreatetruecolor($newwidth, $newheight);
imagecopyresampled($out, $jpeg, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
imagecopyresampled($out, $png, 0, 0, 0, 0, $newwidth, $newheight, $newwidth, $newheight);
imagejpeg($out, 'out.jpg', 100);
*/
//Set the Content Type


$baseImgUrl = $_GET['base_img_url'];
$logoImgUrl = $_GET['logo_img_url'];
$focus = $_GET['focus'];
$target = $_GET['target'];
$link = $_GET['link'];

$path_parts = exif_imagetype($baseImgUrl);
$logImgType = exif_imagetype($logoImgUrl);
//var_dump($path_parts    );

//die();

// Create Image From Existing File
if($path_parts == 2)
    $jpg_image = imagecreatefromjpeg($baseImgUrl);
else if ($path_parts == 3)
    $jpg_image = imagecreatefrompng($baseImgUrl);
else {
    echo "not a png or jpg";
    die();
}
list($bgImgWidth,$bgImgHeight) = getimagesize($baseImgUrl);

if($logImgType == 2)
    $png = imagecreatefromjpeg($logoImgUrl);
else if ($logImgType == 3)
    $png = imagecreatefrompng($logoImgUrl);
else {
    echo "Logo not a png or jpg";
    die();
}


list($newwidth, $newheight) = getimagesize($logoImgUrl);


header('Content-type: image/jpeg');

$thumb = imagecreatetruecolor($newwidth/10, $newheight/10);

$im = @imagecreate($bgImgWidth, $bgImgHeight + $bgImgHeight/5)
or die("Cannot Initialize new GD image stream");

//$newImg = imagecreatetruecolor($bgImgWidth, $bgImgHeight + $bgImgHeight/5);

$black = imagecolorallocate($thumb, 0, 0, 0);
imagecolortransparent($thumb, $black);

imagecopyresized($thumb, $png, 0, 0, 0, 0, $newwidth/10, $newheight/10, $newwidth, $newheight);

    imagecopyresized($im, $jpg_image, 0, $bgImgHeight/5, 0, 0, $bgImgWidth, $bgImgHeight, $bgImgWidth, $bgImgHeight  + $bgImgHeight/5 );

// Allocate A Color For The Text
$white = imagecolorallocate($newImg, 255, 255, 255);

// Set Path to Font File
$font_path = './acme.TTF';




imagecopyresampled($jpg_image, $thumb, 0, 0, 0, 0, $newwidth/10, $newheight/10, $newwidth/10, $newheight/10);

$textZeroHeight = $bgImgHeight - $bgImgHeight/3;
$textZeroWidth = $bgImgWidth - $bgImgWidth/3;
//imagecopyresampled($jpg_image, getTextCenterImg($bgImgWidth/3,$bgImgHeight/3,$bgImgHeight/25,$font_path,$focus), 0, 0, 0, 0, $bgImgWidth/3,$bgImgHeight/3/5, $bgImgWidth/3,$bgImgHeight/3/5);
// Print Text On Image
imagettftext($im, $bgImgHeight/25, 0, 0,0 , $white, $font_path, $focus);
imagettftext($jpg_image, $bgImgHeight/30, 0, $textZeroWidth, $textZeroHeight + $bgImgHeight/5, $white, $font_path, $target);
imagettftext($jpg_image, $bgImgHeight/30, 0, $textZeroWidth, $textZeroHeight + $bgImgHeight/5  + $bgImgHeight/10, $white, $font_path, $link);

// Send Image to Browser
imagejpeg($im);

// Clear Memory
imagedestroy($jpg_image);

function getTextCenterImg($imageWidth,$imageHeight,$fontSize,$font,$text){



    $im = @imagecreate($imageWidth, $imageHeight/5)
    or die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, 0, 0, 0);
    $text_color = imagecolorallocate($im, 255, 255, 255);
    imagestring($im, $fontSize, 5, 5,  $text, $text_color);

    return $im;

    /*// Get Bounding Box Size
    $text_box = imagettfbbox($fontSize,45,$font,$text);

// Get your Text Width and Height
    $text_width = $text_box[2]-$text_box[0];
    $text_height = $text_box[7]-$text_box[1];

    $im = @imagecreate($text_width + 20, $text_height + 20)
    or die("Cannot Initialize new GD image stream");

// Calculate coordinates of the text
    $x = ($imageWidth/2) - ($text_width/2);
    $y = ($imageHeight/2) - ($text_height/2);
    $white = imagecolorallocate($jpg_image, 255, 255, 255);

// Add the text
    imagettftext($im, $fontSize, 0, $x, $y, $white, $font, $text);*/
}
?>