<?
session_start();
$SM = $_GET["SM"]; if($SM == "BMDPP") $SM = "BMDP";
$location = $_GET["location"];
$newdir = $location;
copy("./upload_stereo/depthReserved.jpg", $location."_".$SM.".jpg");
copy("./upload_stereo/bigCoor.obj", $location."_coordinates.obj");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV=Refresh CONTENT="0; URL=http://www.ivs.auckland.ac.nz/web/scene_gallery.php?d=<? echo $location;?>">
<title>Share Scene Existed</title>
</head>
<body>
</body>
</html>
