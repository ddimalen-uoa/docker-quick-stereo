<?php
include 'dbc.php';
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$extension = getExtension($_FILES["file"]["name"]);
	$paperID = $_POST["paper_id"];
	if (($extension != "ppt") && ($extension != "pptx") && ($extension != "pdf") && ($extension != "zip") && ($extension != "rar") && ($extension != "7z") && ($extension != "jar")) {
			echo "<script language=javascript>alert(' Wrong extension.'); document.location='http://www.ivs.auckland.ac.nz/ivcnz2011_temp/htdocs/index.php?page=index';</script>";
					$errors=1;
			return false;
					
		}
		
	if (($_FILES["file"]["size"] < 100000000))
	  {
	  if ($_FILES["file"]["error"] > 0)
		{
		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
	  else
		{
		//echo "Upload: " . $_FILES["file"]["name"] . "<br />";
		//echo "Type: " . $_FILES["file"]["type"] . "<br />";
		//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";	
		  {
		  move_uploaded_file($_FILES["file"]["tmp_name"],
		  "upload/" . $paperID .".". $extension);
		  //echo "Stored in: " . "upload/" . $paperID .".". $extension;
		  echo "<script language=javascript>alert('Upload successful.'); document.location='http://www.ivs.auckland.ac.nz/ivcnz2011_temp/htdocs/index.php?page=index';</script>";				
		  }
		}
	  }
	else
	  {
	  echo "<script language=javascript>alert(' Your file is too large.'); document.location='http://www.ivs.auckland.ac.nz/ivcnz2011_temp/htdocs/index.php?page=index';</script>";	  
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
