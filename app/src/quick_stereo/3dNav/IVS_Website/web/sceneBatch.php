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

$arrayMPO = array();//print_r($arrayMPO);
echo ":: ============================";
echo "<br/>";
echo ":: STATISTICS RESULTS";
echo "<br/>";

$totalCategories = 0;
$categories = "";
foreach(glob($directory.'/*') as $dir) { 		
	  addString($dir);
	  $totalFiles = 0;
	  $creatThumb1 = 0;	
	  createThumpNails($dir, "");
	  foreach(glob($dir.'/*') as $dir2) {		    		
			addString($dir2);
			$totalFiles++;
			$creatThumb2 = 0;	
			foreach(glob($dir2.'/*') as $dir3) { 				
				addString($dir3);
				$totalFiles++;				
				if($creatThumb2==0){
					if(str_replace("_l.jpg", "", $dir3) != $dir3){
						createThumpNails($dir2, $dir3);
						$creatThumb2 = 1;
					}
				}			
			}
			//create folder thumb nail
			if($creatThumb1==0){
				if(str_replace("_l.jpg", "", $dir2) != $dir2){
					createThumpNails($dir, $dir2);
					$creatThumb1 = 1;
				}
			}
	  }
	  
	  if(str_replace(".jpg", "", $dir) == $dir){
		  $categories=$categories.substr($dir, strrpos($dir, "/")+1)." (".$totalFiles." files), ";	  
		  $totalCategories++;
	  }
}

deleteOriginalLeft();

printArrayMPOToLeftRight();

createFileThumpNails();

printDepthReconstruction();

if($run){
	printAutoStereogram();
}

$page = ob_get_contents();
ob_end_flush();

fclose($fp);	

?>

<?

function addString($string){
	GLOBAL $arrayMPO;GLOBAL $run;
	if (strpos($string, '.MPO') !== false) {
		//found MPO
		array_push($arrayMPO, $string);
	}
}
function createThumpNails($dir, $dir2){
	GLOBAL $folderThumb;
	if($folderThumb){
		if(str_replace(".jpg", "", $dir) == $dir){
			echo ":: ============================";
			echo "<br/>";
			echo ":: Create thumbnail for foder $dir with file $dir2";
			echo "<br/>";
			if($dir2==""){
				echo "createThumb.exe ../stereogallery/noimage_thumb.jpg $dir"."_thumb.jpg 198 150";
			}
			else{
				echo "createThumb.exe $dir2 $dir"."_thumb.jpg 198 150";
			}
			echo "<br/>";
			echo ":: ============================";
			echo "<br/>";
		}
	}
}
function createFileThumpNails(){
	
	GLOBAL $arrayMPO;GLOBAL $fileThumb;
	if($fileThumb){
		echo ":: ============================";
		echo "<br/>";
		echo ":: Create thumbnail for each file";
		echo "<br/>";
		echo ":: ============================";
		echo "<br/>";
		$total = sizeof($arrayMPO);
		$finished = 0;
		foreach ($arrayMPO as $mpo){
			$thumpNails = str_replace(".MPO", "_thumb.jpg", $mpo);				
			if (!file_exists($thumpNails)) 
			{
				echo "createThumb.exe ".str_replace(".MPO", "_l.jpg", $mpo)." $thumpNails 198 150";
				echo "<br/>";
			}
			
		}
		echo ":: Total: ".$total.", finished: ".$finished;
		echo "<br/>";
		echo ":: ============================";
		echo "<br/>";
	}
}

function deleteOriginalLeft(){
	
	GLOBAL $arrayMPO;GLOBAL $run;
	$total = sizeof($arrayMPO);
	$finished = 0;
	foreach ($arrayMPO as $mpo){
		$leftA = str_replace(".MPO", ".JPG", $mpo);		
		$leftB = str_replace(".MPO", ".jpg", $mpo);
		if (file_exists($leftA)) {
			if($run) print("del ".str_replace("/","\\",$leftA)."<br/>");
		}
		else if (file_exists($leftB)) {
			if($run) print("del ".str_replace("/","\\",$leftB)."<br/>");
		}
		else{
			$finished++;
		}			
	}
	//echo ":: Total: ".$total.", finished: ".$finished;
	//echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
}


function printArrayMPOToLeftRight(){
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
	echo ":: Generate left and right images from MPO files";
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
	GLOBAL $arrayMPO;GLOBAL $run;
	$total = sizeof($arrayMPO);
	$finished = 0;
	foreach ($arrayMPO as $mpo){
		$leftA = str_replace(".MPO", "_l.jpg", $mpo);
		$rightA = str_replace(".MPO", "_r.jpg", $mpo);
		if (file_exists($leftA) && file_exists($rightA)) {
			$finished++;
		} else {
			if($run) print("java mpo2jpg $mpo $leftA $rightA<br/>");
		}
		
	}
	echo ":: Created $finished/$total left and $finished/$total right from $total MPO files";
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
}


function printDepthReconstruction(){
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
	echo ":: Reconstruct depth -> depth maps";
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
	GLOBAL $arrayMPO;
	GLOBAL $run;
	GLOBAL $fp;
	$total = sizeof($arrayMPO);
	$finished_BM = 0;$finished_GC = 0;$finished_CBP = 0;$finished_CD = 0;
	foreach ($arrayMPO as $mpo){
		$place = str_replace(".MPO", "", $mpo);		
		if (file_exists($mpo)) {
			/*
			if(!file_exists($place."_CBP.jpg")){//_l_resized.jpg
				if($run) print("quickStereoLocal.exe ".$place." CBP"."<br/>");
			}
			else{
				$finished_CBP++;
			}
			
			if(!file_exists($place."_GC.jpg")){
				if($run) print("quickStereoLocal.exe ".$place." GC"."<br/>");
			}
			else{
				$finished_GC++;
			}
			
			if(!file_exists($place."_BM.jpg")){
				if($run) print("quickStereoLocal.exe ".$place." BM"."<br/>");
			}
			else{
				$finished_BM++;
			}
			*/
			if(file_exists($place."_CD.jpg") && !file_exists($place."_Colour_CD.jpg")){
				$tempString = "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/stereogalley_run/disparityColourCoded.exe //files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/stereogalley_run/".$place."_CD.jpg //files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/stereogalley_run/".$place."_Colour_CD.jpg";
				print $tempString."<br/>";
				fwrite($fp, $tempString);
				fwrite($fp, "\n");
			}
			/*
			if(!file_exists($place."_CD.jpg")){
				if($run) print("quickStereoLocal.exe ".$place." CD"."<br/>");
				if($run) print("depthMap_to_OBJ.exe ".$place."_CD.jpg ".$place."_coordinates.obj"."<br/>");
			}
			else{
				$finished_BM++;
			}
			*/
			
			/*
			if(!file_exists($place."_l_resized.jpg")){
				if($run) print("quickStereoLocal.exe ".$place." CBP"."<br/>");
				if($run) print("quickStereoLocal.exe ".$place." GC"."<br/>");
				if($run) print("quickStereoLocal.exe ".$place." BM"."<br/>");
			}
			*/
			//if(!file_exists($place."_coordinates.obj") && file_exists($place."_CBP.jpg")){//_l_resized.jpg
			//	if($run) print("depthMap_to_OBJ.exe ".$place."_CD.jpg ".$place."_coordinates.obj"."<br/>");
			//}
			
		} else {
			//if($run) print("java mpo2jpg $mpo $leftA $rightA<br/>");
		}
		
	}
	echo ":: Depthmap created: Block Matching: $finished_BM/$total, Colour Belief Propagation: $finished_CBP/$total, and Graph Cuts: $finished_GC/$total";
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
}

function printAutoStereogram(){
	GLOBAL $arrayMPO;GLOBAL $run;
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
	echo ":: Generate Auto Stereogram for all files";
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
//	autostereogramLocal.exe
	foreach ($arrayMPO as $mpo){
		$place = str_replace(".MPO", "", $mpo);		
		if (file_exists($mpo)) {		
			$sql = "SELECT algorithm FROM `cs_stereo_matching_evaluation` WHERE image = '$place'";
			$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
			if($row = mysql_fetch_assoc($result)){
			   extract($row);	   
			}
			if(file_exists($place."_".$algorithm.".jpg") && !file_exists($place."_stereogram.jpg")){
				//if($run) 
				//print("autostereogramLocalV2.exe $place"."_GC.jpg $place"."_l.jpg //files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/web/images/mask/rainDrop.jpg 1 1  $place"."_stereogram.jpg");
				$newPlace = str_replace("../stereogallery/","//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/stereogallery/",$place);
				print("autostereogramLocal.exe $newPlace"."_".$algorithm.".jpg $newPlace"."_l_resized.jpg //files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/web/images/mask/rainDrop.jpg 1 1  $newPlace"."_stereogram.jpg");
				echo "<br/>";
				print("autostereogramLocal.exe $newPlace"."_".$algorithm.".jpg $newPlace"."_l_resized.jpg //files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/web/images/mask/rainDrop.jpg 0 1  $newPlace"."_p_stereogram.jpg");
				echo "<br/>";
				////files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/stereogallery/animals/DSCF2164_GC.jpg //files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/stereogallery/animals/DSCF2164_l.jpg //files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/web/images/mask/rainDrop.jpg 1 1 //files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/stereogallery/animals/DSCF2164_stereogram.jpg
			}
			else{
				
			}			
			
		} else {
			//if($run) print("java mpo2jpg $mpo $leftA $rightA<br/>");
		}
		
	}

	echo ":: Done stereogram";
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
}
?>
