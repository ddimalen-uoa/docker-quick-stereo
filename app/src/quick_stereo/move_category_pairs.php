<?
session_start();
include_once 'dbc.php';
$pin = '2991984';
$enterpin = $_GET['pin'];
$enterlocation = $_GET['location'];
$category = $_GET['category'];
$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$universityIP = '130.216.37.27';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV=Refresh CONTENT="0; URL=gallery_ivs.php">
<title>Delete</title></head>

<body bgcolor="#000000">

<h1 align="center"><strong>
<? 
if($enterpin == $pin) {
	$sql = "Select * FROM `cs_ivs`.`stereo_gallery_ivs` WHERE location = '".$enterlocation."' and (ipAddress = '".$ipaddress."' or '".$ipaddress."'='".$universityIP."')";		
	//echo $sql; return;
	$result = mysql_query($sql);
	if(mysql_num_rows($result) >= 1){
		$row = mysql_fetch_assoc($result);	
		$sql = "Update `cs_ivs`.`stereo_gallery_ivs` set category = '$category' WHERE id = '".$row["id"]."'";
		mysql_query($sql);
		echo "Update category successful!<br>";				
	}
	else{
		echo "Wrong IP address!";
	}
	
}
else echo "Something wrong detected!";
?>
</strong></h1>
</body>
</html>