<?
$fileName = $_GET["name"];

copy('shadow/MidlleBurryImages/'.$fileName.'/view1.png', 'shadow/view1.png'); 
copy('shadow/MidlleBurryImages/'.$fileName.'/disp1.png', 'shadow/disp1.png'); 
copy('shadow/MidlleBurryImages/'.$fileName.'/view1.png', 'shadow/result.png'); 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="0; url=shadow.php">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
