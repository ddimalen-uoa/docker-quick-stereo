<?
    $image = ImageCreateFromPNG("upload_stereo/sawtooth-left.bmp");     
    header("Content-Type: image/jpg");
    ImagePng($image, "upload_stereo/yourpngimage.png");
    ImageDestroy($image);
?>