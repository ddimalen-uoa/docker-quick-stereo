<?
session_start();
$id = $_GET['id'];
$rating = $_GET['rating'];
$dbname = 'cs_ivs';
$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
//$link = mysql_connect("home.sit.auckland.ac.nz","cs_ivs","sdjh86637") or die("Couldn't make connection.");
$link = mysql_connect("localhost","cs_ivs","sdjh86637") or die("Couldn't make connection.");
$db = mysql_select_db($dbname, $link) or die("Couldn't select database");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV=Refresh CONTENT="1; URL=gallery.php">
<title>Delete</title></head>
<body>
<h1 align="center"><strong>
<? 
if(intval($id) != 0 && intval($rating) != 0) {

	$sql = "insert into `cs_ivs`.`stereo_gallery_rating` (`id`,`rating`) values ('$id','$rating')";
	mysql_query($sql);	
	echo "Update successful!<br>";
	
}
?>
</strong></h1>
</body>
</html>
