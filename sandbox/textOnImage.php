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
  header('Content-type: image/jpeg');

  // Create Image From Existing File
  $jpg_image = imagecreatefromjpeg('services4.jpeg');

$png = imagecreatefrompng('./oie_png.png');
list($newwidth, $newheight) = getimagesize('./oie_png.png');

$thumb = imagecreatetruecolor($newwidth/10, $newheight/10);

imagecopyresized($thumb, $png, 0, 0, 0, 0, $newwidth/10, $newheight/10, $newwidth, $newheight);
  // Allocate A Color For The Text
  $white = imagecolorallocate($jpg_image, 255, 255, 255);

  // Set Path to Font File
  $font_path = './acme.TTF';

  // Set Text to Be Printed On Image
  $text = "This is a sunset!";

imagecopyresampled($jpg_image, $thumb, 0, 0, 0, 0, $newwidth/10, $newheight/10, $newwidth/10, $newheight/10);

  // Print Text On Image
  imagettftext($jpg_image, 12, 0, 75, 300, $white, $font_path, $text);

  // Send Image to Browser
  imagejpeg($jpg_image);

  // Clear Memory
  imagedestroy($jpg_image);
?>