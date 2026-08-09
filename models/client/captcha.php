<?php
// statically decompiled from captcha.php  [structured; all 1 record(s) structured]

session_start();
$captchaCode = rand(100, 999);
$_SESSION['captcha'] = $captchaCode;
header('Content-Type: image/png');
$image = imagecreatetruecolor(150, 60);
$bgColor = imagecolorallocate($image, 255, 255, 255);
$textColor = imagecolorallocate($image, 255, 0, 0);
imagefilledrectangle($image, 0, 0, 150, 60, $bgColor);
$fontFile = realpath($_SERVER['DOCUMENT_ROOT']) . '/assets/fonts/Roboto-Regular.ttf';
$fontSize = 32;
$x = 37;
$y = 47;
imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontFile, $captchaCode);
imagepng($image);
imagedestroy($image);
