<?
include 'function.resize.php';
$settings = array('w'=>1024,'h'=>1024,'scale'=>true);
$location = $_GET["location"];
$newdir = "upload_stereo";
$olddir = "upload_stereo/".$location;
if (copy($olddir."/gl_texture.jpg", $newdir."/gl_texture.jpg")) {
    //echo "Restore left image <br>";
}
else{
	copy(resize($olddir."/texture.jpg",$settings), $newdir."/gl_texturea.jpg");

	$filename = $olddir."/texture.jpg";
	list($width, $height) = getimagesize($filename);
	$newwidth = 512;
	$newheight = 512;
	
	// Load
	$thumb = imagecreatetruecolor($newwidth, $newheight);
	$source = imagecreatefromjpeg($filename);
	
	// Resize
	imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	imagejpeg($thumb, $newdir."/gl_texture.jpg"); 
	//return;
}

if (copy($olddir."/coordinates.obj", $newdir."/coordinates.obj")) {
    //echo "Restore left image <br>";
}

if (copy($olddir."/result.jpg", $newdir."/result.jpg")) {
    //echo "Restore right image <br>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<title>HTML5 3D display is showing up</title>
<style type="text/css">
<!--
.style1 {font-style: italic}
-->
</style>
</head>

<body bgcolor="#F4FFE4" onLoad="detectRequired();">
<h1 align="center">HTML5 3D display is popping up... Please wait...</h1>
<p align="center"><strong>Notice: if HTML5 3D panel is not showing up...</strong></p>
<p align="center"><strong>Your browser might not support WebGL, please try again with Google Chrome or FireFox</strong><strong><a href="http://java3d.dev.java.net/binary-builds.html"></a></strong></p>
<p align="center"><strong>Click here to go back to <a href="gallery.php">gallery page</a> or to <a href="index.php">upload page</a></strong></p>
<p align="center">Hold and move mouse's left and right button to navigate the 3D scene, scroll mouse wheel for zooming in and out.
</p>
<div align="center"></div>
<div align="center"></div>
<center>
<iframe src="http://www.ivs.auckland.ac.nz/web/webgl/test2.php" width="820" height="520">
  <p align="center">Your browser does not support iframes.</p>
</iframe>
</center>
<div align="center"></div>
</body>
</html>
