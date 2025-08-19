<?
session_start();
include '../dbc.php';
include '../top.php';
$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
include('images.php');

//This project is done by vamapaull: http://blog.vamapaull.com/
//The php code is done with some help from Mihai Bojin: http://www.mihaibojin.com/

if(isset($GLOBALS["HTTP_RAW_POST_DATA"])){
	$jpg = $GLOBALS["HTTP_RAW_POST_DATA"];
	$img = $_GET["img"];
	$temp = "images/poza_".mktime();
	$filename = $temp.".jpg";
	$thumbname = $temp."_thumb.jpg";
	file_put_contents($filename, $jpg);
	$image = new SimpleImage();
   	$image->load($filename);
   	$image->resizeToWidth(190);
  	$image->save($thumbname);

	$_SESSION["filename"] = $filename;
	if(isset($_SESSION["username"])){
		$user_name = $_SESSION["username"];
	}
	else{
		$user_name = $ipaddress;
	}
	
	$sql = 
"INSERT INTO `cs_ivs`.`Photo_taken` (
`id` ,
`user_name` ,
`ip_address` ,
`photo_left` ,
`photo_right` ,
`thumb_left` ,
`thumb_right` ,
`comment` ,
`date_taken`
)
VALUES (
NULL , '$user_name', '$ipaddress', '$filename', '$filename', '$thumbname', '$thumbname', 'nothing', NOW()
);";
	//$_SESSION["temp"] = $sql;
	$result = mysql_query($sql);
} 
else{
	echo "Encoded JPEG information not received.";
}
?>