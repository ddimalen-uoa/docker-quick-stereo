<?
session_start();
if (copy("upload_stereo/saveSegment.jpg", "upload_stereo/depthReserved.jpg")) {
    echo "Saved segmented map <br>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV=Refresh CONTENT="0; URL=index.php">
<title>Share 3D</title>
</head>

<body>
</body>
</html>
