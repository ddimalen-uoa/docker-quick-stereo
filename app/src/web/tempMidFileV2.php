<?
include "../quick_stereo/dbc.php";
$directory = '../stereogallery';

$file = '../stereogallery/middlebury/dirlist.txt';	

$f = fopen($file, "r");
//while ( $line = fgets($f, 1000) ) {
//print $line;
//}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?
while ( $line = fgets($f, 1000) ) {
   //echo "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/".str_replace("../","",$row["name"])."<br/>";	   
   echo "<img width=\"140\" height=\"100\" src=\"../stereogallery/middlebury/".$line."/view1.png\" />";
   echo "<img width=\"140\" height=\"100\" src=\"../stereogallery/middlebury/".$line."/view5.png\" />";

}
?>
</body>
</html>


