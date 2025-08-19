<?
include "../quick_stereo/dbc.php";
$directory = '../stereogallery';
$leftImageArray = array();
$rightImageArray = array();

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
name not like \"%delete%\" and updated < '2013-06-10' order by newId desc limit 200";
$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
//echo "total: ".mysql_num_rows($result)."<br/>";
$counter = 0;
while($row = mysql_fetch_assoc($result)){
  // echo "resizeNew //files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/".str_replace("../","",$row["name"])." 1024 768 //files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/".str_replace("../","",str_replace("_l","_l_1024",$row["name"]))."<br/>";	
   //echo "resizeNew //files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/".str_replace("../","",str_replace("_l","_r",$row["name"]))." 1024 768 //files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/".str_replace("../","",str_replace("_l","_r_1024",$row["name"]))."<br/>";	
   $leftImageArray[$counter] = str_replace("_l","_l_1024",str_replace("../","http://www.ivs.auckland.ac.nz/",$row["name"]));
   $rightImageArray[$counter] = str_replace("_l","_r_1024",str_replace("../","http://www.ivs.auckland.ac.nz/",$row["name"]));
   $counter++;
   //echo "sift.exe left.png right.png point time >> timeRealSIFT.txt<br/>";  
}
			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>rectification Eval Real Images</title></head>

<body>
<h1 align="center">
Uncalibrated Image Rectification Evaluation on <? echo sizeof($leftImageArray); ?> Real Images.</h1>
	<?	
	if(isset($_GET["view"])){
	?>
<p align="center"><a href="rectificationEvalRealImages.php">Back to top page</a></p>
    <? } ?>
<p align="center">
  <input type="button" name="button3" id="button3" value=" &lt;&lt; LAST &lt;&lt;" onclick="
   		document.location='?view='+(parseInt(document.getElementById('select').value)-1);
   " />
  View rectified dataset number 
  <select name="select" id="select">
  	<? for($i = 0; $i < sizeof($leftImageArray); $i++){ ?>
    <option value="<? echo ($i+1); ?>" <? if($_GET["view"] == ($i+1)) echo 'selected="selected"'; ?>><? echo ($i+1); ?></option>
    <? } ?>
  </select>
   <input type="button" name="button" id="button" value="View now" onclick="
   		document.location='?view='+document.getElementById('select').value;
   " />
   <input type="button" name="button2" id="button2" value=" &gt;&gt; NEXT &gt;&gt;" onclick="
   		document.location='?view='+(parseInt(document.getElementById('select').value)+1);
   " />
</p>
<table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <td width="50%" bgcolor="#CCCCCC"><div align="center"><strong>Original left image</strong></div></td>
    <td width="50%" bgcolor="#CCCCCC"><div align="center"><strong>Original right image</strong></div></td>
  </tr>
  <tr>
    <td><div align="center">
    <?	
	if(isset($_GET["view"])){
		echo "<img src=\"".$leftImageArray[intval($_GET["view"])-1]."\" width=\"100%\" />";
	}
	else{
		for($i = 0; $i < sizeof($leftImageArray); $i++){		
			echo "<a href=\"".$leftImageArray[$i]."\" target='_blank'>[".($i+1)."]</a> ";
		}
	}
	?> 
	</div></td>
    <td><div align="center">
    <?
	if(isset($_GET["view"])){
		echo "<img src=\"".$rightImageArray[intval($_GET["view"])-1]."\" width=\"100%\" />";
	}
	else{
		for($i = 0; $i < sizeof($rightImageArray); $i++){		
			echo "<a href=\"".$rightImageArray[$i]."\" target='_blank'>[".($i+1)."]</a> ";
		}
	}
	?>
    </div></td>
  </tr>
<?	
	if(!isset($_GET["view"])){
?>
  <tr>
    <td colspan="2" bgcolor="#CCCCCC"><div align="center"><strong>KLT Correspondences</strong></div>      </td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <?
	for($i = 0; $i < sizeof($leftImageArray); $i++){
		if(file_exists(str_replace("http://www.ivs.auckland.ac.nz/","../",str_replace("_l_1024.jpg","_klt.txt",$leftImageArray[$i]))))
			echo "<a href=\"".str_replace("_l_1024.jpg","_klt.txt",$leftImageArray[$i])."\" target='_blank'>[".($i+1)."]</a> ";
	}
	?>
    </div></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#CCCCCC"><div align="center"><strong>SURF Correspondences</strong></div>      </td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <?
	for($i = 0; $i < sizeof($leftImageArray); $i++){
		if(file_exists(str_replace("http://www.ivs.auckland.ac.nz/","../",str_replace("_l_1024.jpg","_surf.txt",$leftImageArray[$i]))))
			echo "<a href=\"".str_replace("_l_1024.jpg","_surf.txt",$leftImageArray[$i])."\" target='_blank'>[".($i+1)."]</a> ";
	}
	?>
    </div></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#CCCCCC"><div align="center"><strong>SIFT Correspondences</strong></div>      </td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <?
	for($i = 0; $i < sizeof($leftImageArray); $i++){
		if(file_exists(str_replace("http://www.ivs.auckland.ac.nz/","../",str_replace("_l_1024.jpg","_sift.txt",$leftImageArray[$i]))))
			echo "<a href=\"".str_replace("_l_1024.jpg","_sift.txt",$leftImageArray[$i])."\" target='_blank'>[".($i+1)."]</a> ";		
	}
	?>
    </div>
  </td>
  <? } ?>
  
  	<?
  	$tracker = array('sift', 'surf', 'klt');
	$method = array('RANSAC','LMEDS');
	for($t = 0; $t < sizeof($tracker); $t++){
		for($m = 0; $m < sizeof($method); $m++){
	?>    
  <tr>
    <td width="50%" bgcolor="#CCCCCC"><div align="center"><strong>Rectified left image by <? echo $tracker[$t]; ?> + <? echo $method[$m]; ?></strong></div></td>
    <td width="50%" bgcolor="#CCCCCC"><div align="center"><strong>Rectified right image by <? echo $tracker[$t]; ?> + <? echo $method[$m]; ?></strong></div></td>
  </tr>
  <tr>
    <td><div align="center">
    <?
	if(isset($_GET["view"])){
		echo "<img src=\"".str_replace("_l_1024","_l_1024_".$tracker[$t]."_".$method[$m]."",$leftImageArray[intval($_GET["view"])-1])."\" width=\"100%\" />";
	}
	else{
		for($i = 0; $i < sizeof($leftImageArray); $i++){
			if(file_exists(str_replace("http://www.ivs.auckland.ac.nz/","../",str_replace("_l_1024","_l_1024_".$tracker[$t]."_".$method[$m],$leftImageArray[$i]))))
				echo "<a href=\"".str_replace("_l_1024","_l_1024_".$tracker[$t]."_".$method[$m]."",$leftImageArray[$i])."\" target='_blank'>[".($i+1)."]</a> ";
		}
	}
	?> 
	</div></td>
    <td><div align="center">
    <?
	if(isset($_GET["view"])){
		echo "<img src=\"".str_replace("_l_1024","_r_1024_".$tracker[$t]."_".$method[$m]."",$leftImageArray[intval($_GET["view"])-1])."\" width=\"100%\" />";
	}
	else{
		for($i = 0; $i < sizeof($leftImageArray); $i++){
			if(file_exists(str_replace("http://www.ivs.auckland.ac.nz/","../",str_replace("_l_1024","_r_1024_".$tracker[$t]."_".$method[$m],$leftImageArray[$i]))))
				echo "<a href=\"".str_replace("_l_1024","_r_1024_".$tracker[$t]."_".$method[$m]."",$leftImageArray[$i])."\" target='_blank'>[".($i+1)."]</a> ";
		}
	}
	?>
    </div></td>
  </tr> 
  <? }} ?> 
</table>
<p>&nbsp;</p>
</body>
</html>


