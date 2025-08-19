<?php 
session_start();//clearstatcache();
include 'dbc.php';
include('simpleimage.php');

//header("Pragma: no-cache");

$imageResizeTo = $_GET["imageResizeTo"];

$imageFile="upload_stereo/left";
$image = new SimpleImage();
$image->load($imageFile);
$ImageWidth = $image->getWidth();
$ImageHeight = $image->getHeight();
if($ImageWidth > $imageResizeTo){	
	$ImageHeight = $ImageHeight*($imageResizeTo/$ImageWidth);
	$ImageWidth = $imageResizeTo;
}
$imageSize = $ImageWidth * $ImageHeight;
$processingRatio = $imageSize/$averageResolution;
//return;

$algorithm = $_GET["algorithm"];


$sql = "SELECT AVG(resolution) AS averageResolution, AVG(processingTime) AS average,
   AVG(processingTime*processingTime) - AVG(processingTime)*AVG(processingTime) AS variance,
   SQRT(AVG(processingTime*processingTime) - AVG(processingTime)*AVG(processingTime)) AS stdev,
   MIN(processingTime) AS minTime,
   MAX(processingTime) AS maxTime
FROM `cs_stereo_processing_time` 
WHERE `algorithm` = '$algorithm' and resolution > ".intval(0.8*$imageSize)." AND resolution < ".intval(1.2*$imageSize)."";
//echo $sql;
$result = mysql_query($sql);
if(mysql_num_rows($result) > 0){
	if($row = mysql_fetch_assoc($result)){
		extract($row);
	}
}
if($average == 0) $average = 30;
if($stdev == 0) $stdev = 10;

//slow down abit with the VM
$average *= 1.5;
//echo "Algorithgm is : ".$algorithm;
// open the current directory by opendir
$result_file="upload_stereo/shareResults.jpg"; 
//echo $result_file; echo "<Br/>File exist: ".file_exists($result_file);
//return;

$result_segmented_file="upload_stereo/result_segmented.jpg";

$upload_file = "upload_stereo/uploaded.txt";
$number = $_GET["number"];
$counter = $_GET["counter"];

$maxTime = intval($average);
$textString[] = "Images are received at server...";
$textString[] = "Images are resized at server...";
$textString[] = "Feature points detected, images are aligned at server...";
$textString[] = "Disparity range is estimated...";
$textString[] = "Depth map is reconstructing at server, please wait...";
$textStringSize = 5;
//echo $needToFind;

$resultShownOff[]="result.jpg";
$resultShownOff[]="left_resized.jpg";
$resultShownOff[]="right_resized.jpg";
$resultShownOff[]="anaglyph.jpg";
$resultShownOffNumber=4;



//clearstatcache(); 
clearstatcache(); 
if (is_file($result_file)) { 
	//echo "<p>FOUND FILE NOW</p>";
	/*
	if (file_exists($upload_file) && $counter < 2) { 
		echo '<p><h1>'.$textString[$_SESSION["counteringText"]].'</h1></p><p><img src="../images/waiting.gif"></p>';
		if($counter < ($textStringSize-1)) $_SESSION["counteringText"]++;
	}
	else
	*/
	{		
		//$_SESSION["counteringText"]++;		
		/*
				
		*/
		{
			?>
            <div id="resultDIV">
            <center><iframe src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/quick_stereo/3dNav/showImmediate.php" height="800" width="600"></iframe>            
            
            </center> 
            </div>
            <hr/>
            <? $actualProcessingTime = 4.5*$counter; ?>
            <p><strong>Your processed image resolution is <? echo $imageSize ?> px, processed by '<? echo $algorithm ?>'.<br/>
            Result is ready, your actual waiting time is <? echo $actualProcessingTime; ?> seconds excluding upload and download time!<br/>
            This time is recorded for future estimation.</strong></p>
            <?
			//update processing time:
			$sql = "INSERT INTO `cs_ivs`.`cs_stereo_processing_time` (
					`id` ,
					`resolution` ,
					`algorithm` ,
					`processingTime` ,
					`datePosted`
					)
					VALUES (
					NULL , '$imageSize', '".$algorithm."', '$actualProcessingTime', NOW()
					);";
			//if(intval($maxTime - 4.9*$counter)+intval(3*$stdev) > 0)
			{
				mysql_query($sql);
			}
			mysql_query("Delete FROM `cs_processing_queue` WHERE ip_address = '".$_SESSION["postSession"]."'");
			/*
			$date = date("Y-m-d-h-i");
			copy('upload_stereo/result.jpg', 'upload_stereo/StoreResults/'.$date.'.jpg'); 
			$_SESSION["cached_image"] = 'upload_stereo/StoreResults/'.$date.'.jpg';
			copy('upload_stereo/texture.jpg', 'upload_stereo/StoreResults/'.$date.'_texture.jpg'); 
			$_SESSION["cached_texture"] = 'upload_stereo/StoreResults/'.$date.'_texture.jpg';
			*/
		
			?>
<?
			//return all download materials
			echo returnDownloadMaterials();
			
			
			if(isset($_SESSION["reProcessedURL"])) {
				$extraButton = '<input type="button" name="button2" id="ShareButtonADD" value="Add this depth to `'.str_replace("../stereogallery/","",$_SESSION["reProcessedURL"]).'`" class="saveButton" onclick="setTimeout(function(){document.location=\'shareSceneExisted.php?location='.$_SESSION["reProcessedURL"].'&SM=\'+document.getElementById(\'algorithm\').value;},3000); this.value=\'Results are sending to server, please wait.........\'" /> or ';
			}
			else{
				$extraButton = '';
			}
			echo '		
			<p class = "extra">
			If you are not satisfied with the above result, possibly our Automatic Alignment does not work properly.<br> 
			You can either select <strong>Manual Rectification</strong> or<br>
			Manually <strong>change the disparity range</strong> to improve the result.<br>
			</p>
			<!--
			<br/>
			You can also manually segment objects from depth image, <a href="manualSegmentation.php">click here.</a>
			<br/>
			-->
			<p>
			'.$extraButton.'<input type="button" name="button" id="ShareButton" value="Save as a NEW stereo set" class="saveButton" onclick="setTimeout(function(){document.location=\'shareScene.php?SM=\'+document.getElementById(\'algorithm\').value;},3000); this.value=\'Results are sending to server, please wait.........\'" />
			<br/>and enjoy more 3D effects.
			</p>
			';	
			//read return parematers:
			$file = "upload_stereo/returnParameters.txt";
			$f = fopen($file, "r");
			$parameterReturned = fgets($f, 1000);	
			fclose($file);
			$interestString = substr($parameterReturned, strpos($parameterReturned, "min disparity ")+strlen("min disparity "));
			$interestString = substr($interestString, 0, strpos($interestString, " px, disparity"));
			$interestParameters = explode(" px, max disparity ",$interestString);
			echo '<input name="minDispHidden" type="hidden" id="minDispHidden" value="'.$interestParameters[0].'" /><input name="maxDispHidden" type="hidden" id="maxDispHidden" value="'.$interestParameters[1].'" />';
		}			
	}
}
else
{	
	?>
                    </p>
<div style="background:url(<?
	if(file_exists("upload_stereo/".($_SESSION["counteringText"]+1).".jpg")){
    	echo "upload_stereo/".($_SESSION["counteringText"]+1).".jpg?".$number;
		$_SESSION["counteringText"]++;
	}
	else
		echo "upload_stereo/processGoal.jpg";
	?>); background-repeat:no-repeat; background-position:center; text-align:center">
    <h1 align="center" style="color:#000; background:#CCCCCC; border:medium; border-color:#000">Processing now, it should be finished in <br />
<? echo max(0, intval($maxTime - 4.9*$counter)); ?> &#177; <? echo intval($stdev); ?> seconds
<?
if(intval($maxTime - 4.9*$counter)+intval(3*$stdev) < 0) echo "<br/>If it takes longer than expected, please <a href=\"http://".$_SERVER['HTTP_HOST']."/quick_stereo/index.php\">click here</a>!";
?>
</h1>
    <img class="intermediatedResult" src="../images/waiting.gif">
    </div>
    <?
}
//clearstatcache();
?>   