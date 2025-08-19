<?
session_start();
$SM = $_GET["SM"];
if($SM == "BMDPP") $SM = "BMDP";
$dbname = 'cs_ivs';
$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
//$link = mysql_connect("localhost","d34tixj5_vision","patrice123") or die("Couldn't make connection.");
//$link = mysql_connect("home.sit.auckland.ac.nz","cs_ivs","sdjh86637") or die("Couldn't make connection.");
$link = mysql_connect("localhost","cs_ivs","sdjh86637") or die("Couldn't make connection.");
$db = mysql_select_db($dbname, $link) or die("Couldn't select database");

$randomNumber = rand(100000,999999);
//$randomNumber = 429878;

$olddir = "./upload_stereo";
if(isset($_SESSION["postSession"])){
	$olddir = "./upload_stereo/StoreResults/".$_SESSION["postSession"]."";
}

$newdir = "./../stereogallery/usersubmit/USER".$randomNumber;
unlink("$olddir"."/depth_BMDP.jpg");
unlink("$olddir"."/depth_BMDPP.jpg");
unlink("$olddir"."/depth_GCDP.jpg");
unlink("$olddir"."/depth_BMDP1.jpg");
unlink("$olddir"."/depth_CBP.jpg");
unlink("$olddir"."/depth_CSDPS.jpg");
unlink("$olddir"."/depth_DP.jpg");
unlink("$olddir"."/depth_GC.jpg");
unlink("$olddir"."/depth_BM.jpg");
unlink("$olddir"."/depth_SAD.jpg");
unlink("$olddir"."/depth_SSD.jpg");

copy("$olddir"."/depthReserved.jpg", "$olddir"."/depth_".$SM.".jpg");
//depthReserved.jpg $SM
$stringArrayFrom = array();
array_push($stringArrayFrom,"$olddir/mpo.mpo");
array_push($stringArrayFrom,"$olddir"."/depth_CBP.jpg");
array_push($stringArrayFrom,"$olddir"."/right_raw_resized.jpg");
array_push($stringArrayFrom,"$olddir"."/thumb.jpg");
array_push($stringArrayFrom,"$olddir"."/parallelEye.jpg");
array_push($stringArrayFrom,"$olddir"."/fundamental_matrix.xml");
array_push($stringArrayFrom,"$olddir"."/anaglyph.jpg");
array_push($stringArrayFrom,"$olddir"."/depth_BMDPP.jpg");
array_push($stringArrayFrom,"$olddir"."/depth_BMDP.jpg");
array_push($stringArrayFrom,"$olddir"."/depth_GCDP.jpg");
array_push($stringArrayFrom,"$olddir"."/depth_BMDP1.jpg");
array_push($stringArrayFrom,"$olddir"."/depth_CBP.jpg");
array_push($stringArrayFrom,"$olddir"."/depth_CSDPS.jpg");
array_push($stringArrayFrom,"$olddir"."/depth_DP.jpg");
array_push($stringArrayFrom,"$olddir"."/depth_GC.jpg");
array_push($stringArrayFrom,"$olddir"."/depth_BM.jpg");
array_push($stringArrayFrom,"$olddir"."/depth_SAD.jpg");
array_push($stringArrayFrom,"$olddir"."/depth_SSD.jpg");

array_push($stringArrayFrom,"$olddir"."/bigCoor.obj");
array_push($stringArrayFrom,"$olddir"."/returnParameters.txt");
array_push($stringArrayFrom,"$olddir"."/autostereogram.jpg");
array_push($stringArrayFrom,"$olddir"."/crossEye.jpg");
array_push($stringArrayFrom,"$olddir"."/left_raw_resized.jpg");
array_push($stringArrayFrom,"$olddir"."/p_stereogram.jpg");
array_push($stringArrayFrom,"$olddir"."/left_raw_resized.jpg");

$stringArrayTo = array();
array_push($stringArrayTo,"$newdir.MPO");
array_push($stringArrayTo,"$newdir"."_CBP.jpg");
array_push($stringArrayTo,"$newdir"."_r.jpg");
array_push($stringArrayTo,"$newdir"."_thumb.jpg");
array_push($stringArrayTo,"$newdir"."_parallelEye.jpg");
array_push($stringArrayTo,"$newdir"."_fundamental_matrix.xml");
array_push($stringArrayTo,"$newdir"."_anaglyph.jpg");
array_push($stringArrayTo,"$newdir"."_BMDPP.jpg");
array_push($stringArrayTo,"$newdir"."_BMDP.jpg");
array_push($stringArrayTo,"$newdir"."_GCDP.jpg");
array_push($stringArrayTo,"$newdir"."_BMDP1.jpg");
array_push($stringArrayTo,"$newdir"."_CBP.jpg");
array_push($stringArrayTo,"$newdir"."_CSDPS.jpg");
array_push($stringArrayTo,"$newdir"."_DP.jpg");
array_push($stringArrayTo,"$newdir"."_GC.jpg");
array_push($stringArrayTo,"$newdir"."_BM.jpg");
array_push($stringArrayTo,"$newdir"."_SAD.jpg");
array_push($stringArrayTo,"$newdir"."_SSD.jpg");

array_push($stringArrayTo,"$newdir"."_coordinates.obj");
array_push($stringArrayTo,"$newdir"."_parameters.txt");
array_push($stringArrayTo,"$newdir"."_stereogram.jpg");
array_push($stringArrayTo,"$newdir"."_crossEye.jpg");
array_push($stringArrayTo,"$newdir"."_l_resized.jpg");
array_push($stringArrayTo,"$newdir"."_p_stereogram.jpg");
array_push($stringArrayTo,"$newdir"."_l.jpg");

for($i = 0; $i < sizeof($stringArrayFrom); $i++){	
	copyAndInsertDB($stringArrayFrom[$i], $stringArrayTo[$i]);
}
//return;


//echo "Updated database, you will be returned to gallery page in 5 seconds<br>";
//echo "</center>";

function copyAndInsertDB($from, $to){
	
	if (copy($from, $to)) {
		//echo "Saved $from to $to<br>";		
		$sql = "INSERT INTO `cs_ivs`.`cs_stereo_scene_files` (
		`id`,`name`,`parent`,`updated`
		)
		VALUES (
		NULL , '".substr($to, 2)."', '../stereogallery/usersubmit', NOW());";	
		
		$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	}
	else{
		//echo "Not saved $from to $to<br>";	
	}
	
	//mysql_query($sql);	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV=Refresh CONTENT="0; URL=http://www.ivs.auckland.ac.nz/web/scene_gallery.php?d=<? echo substr($newdir, 2);?>">
<title>Share Scene</title>
</head>

<body>
</body>
</html>
