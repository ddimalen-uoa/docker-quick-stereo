<?php 
session_start();
include 'dbc.php';
// open the current directory by opendir
$flag_file="upload_stereo/segmentation_boundary.txt";
$result_segmented_file="upload_stereo/saveSegment.jpg";
{ 
	//echo "here";
	if (file_exists($flag_file)) { 
		echo '<p><h1>Segmentation is carried out, please wait...</h1></p><p><img src="../images/waiting.gif"></p>';		
	}
	else
	{			
		if (file_exists($result_segmented_file) && !file_exists($flag_file)) 
		{							
			?>
            <img src="<? echo $result_segmented_file; ?>" name="result_segmented_file" id ="result_segmented_file" class="textureResultShown">	<br />
            <input type="button" name="button" id="button" value="Save this to home page" onclick="document.location='saveSegmentToHomePage.php';" />
<?
		}
		else{
			echo '<p><h1>Segmentation is carried out, please wait...</h1></p><p><img src="../images/waiting.gif"></p>';	
		}		
	}
}
?>

