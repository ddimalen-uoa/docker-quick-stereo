<?
include "../quick_stereo/dbc.php";
//$directory = '../stereogallery/humans/face';

$directory = $_GET["d"];
if($directory != ""){
	$sql = "delete from cs_stereo_scene_files where parent = '".$directory."'";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
}
else{
	return;	
}
	
$totalCategories = 0;
$categories = "";
foreach(glob($directory.'/*') as $dir) { 		
	  addString($directory,$dir);	  
	  //foreach(glob($dir.'/*') as $dir2) {		    		
		//	addString($dir, $dir2);			
			//foreach(glob($dir2.'/*') as $dir3) { 				
				//addString($dir2, $dir3);				
			//}			
	  //}	 
}

echo "Updated!";

function addString($stringParent, $stringURL){
	//echo "Parent: ".$stringParent."; son: ".$stringURL."<br>";
	$sql = "INSERT INTO `cs_ivs`.`cs_stereo_scene_files` (
				`id` ,
				`name` ,
				`updated` ,
				`parent`
				)
				VALUES (
				NULL , '".$stringURL."', NOW(), '".$stringParent."'
				);";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
}
?>