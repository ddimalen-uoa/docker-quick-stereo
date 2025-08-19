<?
session_start();
include "../quick_stereo/dbc.php";
$from = $_GET["from"];
$to = $_GET["to"];

$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
if($to == "" || $from == "") {
	return;
}

//if($to != "del"){
if($to != "../stereogallery/delete"){
	//update database: cs_stereo_matching_evaluation
	$sql = "update cs_stereo_matching_evaluation
	set image = '".$to.substr($from, strrpos($from, "/"))."'
	where image = '".$from."'
	";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	//echo "ehere";
	//update database: cs_stereo_rectification_evaluation
	$sql = "update cs_stereo_rectification_evaluation
	set image = '".$to.substr($from, strrpos($from, "/"))."'
	where image = '".$from."'
	";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());

	//echo $sql; return;
	$sql = "select * from `cs_ivs`.`cs_stereo_scene_files`
	where name like '".$from."%' or name like './".$from."%'
	";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	//echo $sql;
	//return;
	while($row = mysql_fetch_assoc($result)){
		extract($row);
		//echo "copy('".$row["name"]."', '".$to.substr($row["name"], strrpos($row["name"], "/"))."');<br/>";
		copy("$name",$to.substr($row["name"], strrpos($row["name"], "/")));

		//update database: cs_stereo_rectification_evaluation
		$sql = "update cs_stereo_scene_files
		set name = '".$to.substr($row["name"], strrpos($row["name"], "/"))."', parent = '".$to."'
		where name = '".$row["name"]."'
		";
		//echo "<br>";
		//echo $sql; echo "<br>";
		unlink("$name");
		if($res=mysql_query($sql)){
		//echo "Successfully update from cs_stereo_scene_files at ".$row["name"]."<br/>";
		}
	}
}
//else if($to == "del" && $_COOKIE["user_name"] != ""){
else if($to == "../stereogallery/delete" && $_COOKIE["user_name"] != ""){
	//update database: cs_stereo_matching_evaluation
	$sql = "delete from cs_stereo_matching_evaluation
	where image = '".$from."' limit 1
	";
	//echo $sql."<br/>";
	if($res=mysql_query($sql)){
	//echo "Successfully delete from cs_stereo_matching_evaluation"."<br/>";
	}

	//update database: cs_stereo_rectification_evaluation
	$sql = "delete from cs_stereo_rectification_evaluation
	where image = '".$from."' limit 1
	";
	if($res=mysql_query($sql)){
	//echo "Successfully delete from cs_stereo_rectification_evaluation"."<br/>";
	}

	//echo $sql; return;
	$sql = "select * from `cs_ivs`.`cs_stereo_scene_files`
	where name like '".$from."%'
	";
	//echo $sql; echo "<br/>";
	//return;
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	while($row = mysql_fetch_assoc($result)){
		extract($row);
		//echo "copy('".$row["name"]."', '".$to.substr($row["name"], strrpos($row["name"], "/"))."');<br/>";
		//copy("$name",$to.substr($row["name"], strrpos($row["name"], "/")));

		//update database: cs_stereo_rectification_evaluation
		$sql = "delete from cs_stereo_scene_files
		where name = '".$row["name"]."' limit 1
		";
		//echo "$name"."<br/>";
		//unlink("$name");
		mysql_query($sql);
		if($res=mysql_query($sql)){
		//echo "Successfully delete from cs_stereo_scene_files at ".$row["name"]."<br/>";
		}
	}
}
?>
Successful!!! Move your mouse here to view the next dataset!
<!--
<meta http-equiv="refresh" content="0; url=http://www.ivs.auckland.ac.nz/web/scene_gallery.php?d=<? if($to!= "del") echo $to; else echo "./"; ?>">
-->