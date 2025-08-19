<?
include "../quick_stereo/dbc.php";
$directory = '../stereogallery';

$fp = fopen('../quick_stereo/upload_stereo/queueJob.txt', 'w');							
			
$run = intval($_GET["run"]);
$folderThumb = intval($_GET["folderThumb"]);
$fileThumb = intval($_GET["fileThumb"]);
if($run) {
	//$folderThumb = 1;
	$fileThumb = 1;
}

$sql = "SELECT name, md5(id) as newId FROM `cs_stereo_scene_files` 
WHERE 
name like \"%_l.jpg%\" and 
name like \"%../stereogallery/%\" and
name not like \"%DSCF3780%\" and 
name not like \"%DSCF3787%\" and 
name not like \"%delete%\" and updated < '2013-06-10'

order by newId desc limit 200";
$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
//echo "total: ".mysql_num_rows($result)."<br/>";

//$tracker = 'sift';
//$method = 'LMEDS';
	$tracker = array('klt', 'surf', 'sift');
	$method = array('RANSAC','LMEDS');
	while($row = mysql_fetch_assoc($result)){ 
		for($t = 0; $t < sizeof($tracker); $t++){
			for($m = 0; $m < sizeof($method); $m++){			 
			   echo "sift.exe ";
			   echo "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/".str_replace("../","",str_replace("_l","_l_1024_".$tracker[$t]."_".$method[$m]."",$row["name"]))." ";
			   echo "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/".str_replace("../","",str_replace("_l","_r_1024_".$tracker[$t]."_".$method[$m]."",$row["name"]))." point >> point_".$tracker[$t]."_".$method[$m].".txt<br/>";			    
			}
		}
	}

			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>


