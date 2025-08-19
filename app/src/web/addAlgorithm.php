<?
include "../quick_stereo/dbc.php";
$add = $_GET["add"];
$dir = $_GET["dir"];
$alg = $_GET["alg"];

if($add == "add"){
	$sql = "INSERT INTO `cs_ivs`.`cs_stereo_matching_evaluation` (
			`image` ,
			`algorithm` ,
			`id`
			)
			VALUES (
			'$dir', '$alg', NULL
			);
			";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
}
else if($add == "del"){
	$sql = "DELETE FROM `cs_stereo_matching_evaluation` WHERE `image` = '$dir' AND `algorithm` = '$alg'
			";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
}
?>