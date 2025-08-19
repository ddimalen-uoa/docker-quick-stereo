<?
//return;
$left = $_GET["left"];
$right = $_GET["right"];
$newdir = "upload_stereo";
$olddir = "../calibrate_minoru/save_data/".$location;
if (copy($olddir.$left, $newdir."/left")) {
    //echo "Restore left image <br>";
}
if (copy($olddir.$right, $newdir."/right")) {
    //echo "Restore left image <br>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="0; url=index.php?rectification=1&minDisp=0&maxDisp=150">
<title>Process rectified Minoru results</title>
</head>

<body>
</body>
</html>
