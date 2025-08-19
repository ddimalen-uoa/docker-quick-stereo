<?
if(isset($_GET["location"])){
	$location = $_GET["location"];
	copy('upload_stereo/'.$location.'/texture.jpg', '../web/shadow/view1.png'); 
	copy('upload_stereo/'.$location.'/result.jpg', '../web/shadow/disp1.png'); 
	copy('upload_stereo/'.$location.'/texture.jpg', '../web/shadow/result.png');
}
else{
	copy('upload_stereo/texture.jpg', '../web/shadow/view1.png'); 
	copy('upload_stereo/result.jpg', '../web/shadow/disp1.png'); 
	copy('upload_stereo/texture.jpg', '../web/shadow/result.png');
} 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="0; url=../web/shadow.php">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
