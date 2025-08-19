<?
session_start();
require_once("dbc.php");
$enterpin = $_GET['pin'];
$enterlocation = $_GET['location'];
$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV=Refresh CONTENT="1; URL=gallery.php">
<title>Delete</title></head>
<body>
<h1 align="center"><strong>
<? 
if($enterpin != "") {
	$sql = "update `cs_ivs`.`stereo_gallery` set `comment`='$enterpin' WHERE location = :location ";
	$stmt = $dbc->prepare($sql);
	$name = "%../stereogallery/animals%";
	$stmt->execute(array(':location' => $enterlocation));	
	echo "Update successful!<br>";	
}
?>
</strong></h1>
</body>
</html>