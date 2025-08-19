<?
session_start();
include '../../dbc.php';
include '../../top.php';
$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
if(isset($_GET["fmatrix"])){
	$sql = "update `Photo_taken` set `F_matrix` = '".$_GET["fmatrix"]."' where photo_left = '".$_GET["image_url"]."'";
	if($result = mysql_query($sql)){
		//echo "success";
	}
	else{
		//echo "false";
	}
}
?>
<HTML>
<HEAD>
   <TITLE>Applet HTML Page</TITLE>
</HEAD>
<BODY>

<!--
*** GENERATED applet HTML launcher - DO NOT EDIT IN 'BUILD' FOLDER ***

If you need to modify this HTML launcher file (e.g., to add applet parameters),
copy it to where your applet class is found in the SRC folder. If you do this,
the IDE will use it when you run or debug the applet.

Tip: To exclude an HTML launcher from the JAR file, use exclusion filters in
the Packaging page in the Project Properties dialog.

For more information see the online help.
-->
<table width="500" border="0" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr>
    <td><div align="right"><a href="../index.php">Back to take more live photos </a></div></td>
    <td><div align="right"><a href="../site/index-4.php">Back to Gallery</a></div></td>
    <td><div align="right"><a href="../site/index.php">Back to home page</a></div></td>
  </tr>
</table>
<br/>
<APPLET codebase="." code="mainApplet.class" width=100% height=800>
	<param name="image_url" value="http://<? echo $_SERVER['HTTP_HOST']; ?>/stereo/<? echo $_GET["image_url"]; ?>">  	
    <param name="ip" value="<? echo $_GET["ip"]; ?>">
    <param name="matrix" value="<? echo $_GET["fmatrix"]; ?>">
    <param name="image_id" value="<? echo $_GET["image_id"]; ?>">
    
</APPLET>


<HR WIDTH="100%">
<FONT SIZE=-1><I><a href="www.cs.auckland.ac.nz/~mngu012">Minh Nguyen</a> - last update 30/0708 </I></FONT>
</BODY>
</HTML>

