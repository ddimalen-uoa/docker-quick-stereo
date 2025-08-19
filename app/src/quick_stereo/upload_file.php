<?php
//include 'dbc.php';
if($_SERVER['REQUEST_METHOD'] == "POST"){
	if ($_FILES["file"]["error"] > 0)
	{
		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
	}
	else
	{		
		echo "Upload: " . $_FILES["file"]["name"] . "<br />";
		echo "Type: " . $_FILES["file"]["type"] . "<br />";
		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
		
		$filename = stripslashes($_FILES["file"]["name"]);
		$extension = getExtension($filename);
		//echo $extension; return;
		if (($extension == "jpg") || ($extension == "txt")) 
		{
			
			$haveWaited = false;
			for ($i=0; $i <= 20; $i++) {
				$file_existsL=file_exists("upload/".$_FILES["file"]["name"]);	
				$sql = "SELECT * FROM `cs_processing_queue` order by `id`";
				$result = mysql_query($sql);			
				$numRows = mysql_num_rows($result);
				if(!$file_existsL && $numRows == 0) {
					break;					
				}
				else{
					$haveWaited = true;
				}
				sleep(8); // this should halt for 15 seconds for every loop
			}
			if($haveWaited){
				sleep(15);
			}
			
			move_uploaded_file($_FILES["file"]["tmp_name"],"upload/" . $_FILES["file"]["name"]);
      			
			echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
		}
	}
}
function getExtension($str) {
	 $i = strrpos($str,".");
	 if (!$i) { return ""; }

	 $l = strlen($str) - $i;
	 $ext = substr($str,$i+1,$l);
	 return strtolower($ext);
}
?>
	
<form action="" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>