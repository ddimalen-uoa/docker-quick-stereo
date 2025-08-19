<?
session_start();
$pin = '2991984';
$enterpin = $_GET['pin'];
$enterlocation = $_GET['location'];
$dbname = 'cs_ivs';
$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$universityIP = '130.216.37.27';
//$link = mysql_connect("home.sit.auckland.ac.nz","cs_ivs","sdjh86637") or die("Couldn't make connection.");
$link = mysql_connect("localhost","cs_ivs","sdjh86637") or die("Couldn't make connection.");
$db = mysql_select_db($dbname, $link) or die("Couldn't select database");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV=Refresh CONTENT="1; URL=gallery_ivs.php">
<title>Delete</title></head>

<body>

<h1 align="center"><strong>
<? 
if($enterpin == $pin) {
	if($ipaddress == $universityIP){
		$sql = "Select * FROM `cs_ivs`.`stereo_gallery_ivs` WHERE location = '".$enterlocation."'";	
	}
	else{
		$sql = "Select * FROM `cs_ivs`.`stereo_gallery_ivs` WHERE location = '".$enterlocation."' and ipAddress = '$ipaddress';";
	}
	//echo $sql; return;
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0){	
		$sql = "DELETE FROM `cs_ivs`.`stereo_gallery_ivs` WHERE location = '".$enterlocation."'";
		mysql_query($sql);
		echo "Delete database information successful!<br>";		
		delete_directory("upload_stereo/".$enterlocation);		
		echo "Delete files and folders successful!<br>";	
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
<?
function delete_directory($dirname) {
   if (is_dir($dirname))
      $dir_handle = opendir($dirname);
   if (!$dir_handle)
      return false;
   while($file = readdir($dir_handle)) {
      if ($file != "." && $file != "..") {
         if (!is_dir($dirname."/".$file))
            unlink($dirname."/".$file);
         else
            delete_directory($dirname.'/'.$file);    
      }
   }
   closedir($dir_handle);
   rmdir($dirname);
   return true;
}
?>
