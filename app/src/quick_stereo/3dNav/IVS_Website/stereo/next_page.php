<?
session_start();
include '../dbc.php';
include '../top.php';


if(isset($_GET["process_images"])){
	$_SESSION["filename"] = $_GET["process_images"];
	//echo "http://www.ivs.auckland.ac.nz/stereo/images/".$_SESSION["filename"]."";
}


$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

$sql = "select * from `Photo_taken` where photo_left = '".$_SESSION["filename"]."'";
//echo $sql;
$result = mysql_query($sql);
if(mysql_num_rows($result) > 0){
	$row = mysql_fetch_assoc($result);
	if($row["F_matrix"] == '' || $row["F_matrix"] == '0'){
		$sql = "select * from F_Minoru where ipaddress = '".$ipaddress."' order by date_entered desc limit 1";
		$result = mysql_query($sql);
		if(mysql_num_rows($result) > 0){
			$foundMatrix = true;
			$row = mysql_fetch_assoc($result);
			$matrix = $row["F_matrix"];
		}
		else{
			$foundMatrix = false;
			$matrix = "0";
		}
	}
	else{
		$foundMatrix = true;			
		$matrix = $row["F_matrix"];
	}
	$image_id = $row["id"];	
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div align="center">
  <h1>Please confirm that you are processing the following photo.</h1>
  <!-- <p><? echo $_SESSION["temp"]; ?>&nbsp;</p> -->
  <p>Search for Retification matrix...<br />
    <? 
								  
	$matrixItems = explode(" ", $matrix);
	if($foundMatrix){
	?>
	Our record stated that you already have your Minoru configurated with F Matrix<br />
  </p>
  <table width="50%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><div align="center">|</div></td>
      <td><div align="center"><? echo $matrixItems[0]; ?></div></td>
      <td><div align="center"><? echo $matrixItems[1]; ?></div></td>
      <td><div align="center"><? echo $matrixItems[2]; ?></div></td>
      <td><div align="center">|</div></td>
    </tr>
    <tr>
      <td><div align="center">|</div></td>
      <td><div align="center"><? echo $matrixItems[3]; ?></div></td>
      <td><div align="center"><? echo $matrixItems[4]; ?></div></td>
      <td><div align="center"><? echo $matrixItems[5]; ?></div></td>
      <td><div align="center">|</div></td>
    </tr>
    <tr>
      <td><div align="center">|</div></td>
      <td><div align="center"><? echo $matrixItems[6]; ?></div></td>
      <td><div align="center"><? echo $matrixItems[7]; ?></div></td>
      <td><div align="center"><? echo $matrixItems[8]; ?></div></td>
      <td><div align="center">|</div></td>
    </tr>
  </table>
  <p><br>
									  
                                      <? } else { ?>
									  Our record stated that you have not configurate your Minoru webcam with us<br>
									  
    <? } ?>
  </p>
  <p><a href="stereo_App/process.php?image_id=<? echo $image_id; ?>&image_url=<? echo $_SESSION["filename"]; ?>&ip=<? echo $ipaddress; ?>&fmatrix=<? echo $matrix; ?>">Click on the photo</a> to process it
  or <a href="stereo_App/process.php?image_id=<? echo $image_id; ?>&image_url=<? echo $_SESSION["filename"]; ?>&ip=<? echo $ipaddress; ?>&fmatrix=0">click here to process without</a> the pre-configuration above</p>
</div>
<p align="center"><a href="stereo_App/process.php?image_id=<? echo $image_id; ?>&image_url=<? echo $_SESSION["filename"]; ?>&ip=<? echo $ipaddress; ?>&fmatrix=<? echo $matrix; ?>"><img src="<? echo $_SESSION["filename"]; ?>"/></a></p>
<p align="center"><? echo $_SESSION["filename"]; ?></p>
</body>
</html>
