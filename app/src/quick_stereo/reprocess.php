<?
$location = $_GET["location"];
$newdir = "upload_stereo";
$olddir = "upload_stereo/".$location;
if (copy($olddir."/left", $newdir."/left")) {
    echo "Restore left image <br>";
}

if (copy($olddir."/right", $newdir."/right")) {
    echo "Restore right image <br>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV=Refresh CONTENT="1; URL=index.php">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
