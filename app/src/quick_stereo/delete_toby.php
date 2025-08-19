<?
if($_GET['delete'] == 1){
	unlink('upload/left.jpg'); 
	unlink('upload/right.jpg'); 	
	unlink('upload/upload.txt'); 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Untitled Document</title>
</head>

<body>
<button type="button" onclick="document.location='delete_toby.php?delete=1';">Delete files</button> 
<button type="button" onclick="document.location='delete_toby.php';">Refresh page</button> 
</body>
</html>
