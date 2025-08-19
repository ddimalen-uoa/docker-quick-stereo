<?
include "menu.php";
include('simpleimage.php');
if(isset($_GET["location"])){
	$location=$_GET["location"];
	$image = new SimpleImage();
	$image->load("upload_stereo/".$location."_l_resized.jpg");
	$image->resize(480,256);
	$image->save("upload_stereo/texture_256.jpg");	
	
	if(file_exists("upload_stereo/".$location."_CD.jpg"))
		$image->load("upload_stereo/".$location."_CD.jpg");
	else if(file_exists("upload_stereo/".$location."_CBP.jpg"))
		$image->load("upload_stereo/".$location."_CBP.jpg");
	$image->resize(480,256);
	$image->save("upload_stereo/result_256.jpg");
	
	
	echo "success!";
}
?>