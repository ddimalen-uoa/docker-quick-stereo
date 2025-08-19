<?
include "../quick_stereo/dbc.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>

<title>A web-based stereo vision system for research, educational purposes, and practical applications</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../web/mm_health_nutr.css" type="text/css" />
<script language="JavaScript" src="../js/javascript.js"></script>
<script language="JavaScript" type="text/javascript">
//--------------- LOCALIZEABLE GLOBALS ---------------
var d=new Date();
var monthname=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
//Ensure correct for language. English is "January 1, 2004"
var TODAY = monthname[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear();
//---------------   END LOCALIZEABLE   ---------------

var images = [];
<?
$sql = "SELECT * FROM `cs_stereo_scene_files` WHERE name like '%.MPO%' and parent <> '../stereogallery/delete' and private = 0 and parent <> '' order by rand() limit 1000";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result); $firstImage = str_replace(".MPO","",$row["name"]);
while($row = mysql_fetch_assoc($result)){
	echo "images.push(\"".str_replace(".MPO","",$row["name"])."\");";
}
?>

function rotateImages(){
	var setImage = images.pop();
	document.getElementById('WebGL').src='https://www.ivs.auckland.ac.nz/web/webgl/sceneGLTrans.php?location='+setImage;
}
</script>
</head>
<body bgcolor="#000000" onload="setInterval('rotateImages();',20000);">
<iframe id="WebGL" src="https://www.ivs.auckland.ac.nz/web/webgl/sceneGLTrans.php?location=<? echo $firstImage; ?>" height="650" width="860" style="border:hidden"></iframe>
</body>
</html>