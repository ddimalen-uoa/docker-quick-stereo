<?
include "../quick_stereo/dbc.php";
$dir = $_GET["dir"];

if($dir != ""){
	$sql = "UPDATE `cs_ivs`.`cs_stereo_rectification_evaluation` set rectification_quality = 'fail' where image = '$dir';
			";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
}
?>