<?
include "menu.php";
//if(isset($_GET["orderBy"]) && $_GET["orderBy"] = 'average')
//	$orderBy = "average desc";
//else
	$orderBy = "`stereo_gallery_ivs`.id desc";
?>
<?
$dbname = 'cs_ivs';
//$link = mysql_connect("home.sit.auckland.ac.nz","cs_ivs","sdjh86637") or die("Couldn't make connection.");
$link = mysql_connect("localhost","cs_ivs","sdjh86637") or die("Couldn't make connection.");
$db = mysql_select_db($dbname, $link) or die("Couldn't select database");
$sql = "SELECT * FROM `cs_stereo_scene_files` WHERE name like '%.obj%' and parent <> '../stereogallery/delete' and parent <> '' order by updated desc limit 1000";
$result = mysql_query($sql) or die(mysql_error()); 
$numberTotal = mysql_num_rows($result);
while($row = mysql_fetch_assoc($result)){
	echo "../".str_replace("_coordinates.obj","",$row["name"])." "; 	
}
?>
