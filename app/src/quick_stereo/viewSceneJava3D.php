<?
$directory = $_GET["location"];
$newdir = "upload_stereo";

copy($directory."_l_resized.jpg", $newdir."/texture.jpg");
copy($directory."_CBP.jpg", $newdir."/result.jpg");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script src="plugins.js">

//Detect Plugin (Flash, Java, RealPlayer etc) script- By Frederic (fw4@tvd.be)
//Visit http://javascriptkit.com for this script and more

//SAMPLE USAGE- detect "Flash"
//if (pluginlist.indexOf("Flash")!=-1)
//document.write("You have flash installed")

</script>
<script type="text/javascript" language="javascript">
function detectRequired(){
	//detect fash installed
	if (pluginlist.indexOf("Shockwave Flash")!=-1){
		//alert("You have Shockwave Flash installed!")
	}
	else{
		document.getElementById('flash_detection').innerHTML = 'Flash is not installed';
	}
		//alert("You do not have Shockwave Flash installed!");
	if(!navigator.javaEnabled())
	{
		document.getElementById('java_detection').innerHTML = 'Java is NOT installed, please <a href="http://www.java.com/en/download/" target="_blank">click here</a> to download and install it';
	}
	else{
		document.getElementById('java_detection').innerHTML = 'Java is installed';
	}
}
</script>

<title>Java 3D display is showing up</title>
<style type="text/css">
<!--
.style1 {font-style: italic}
-->
</style>
</head>

<body bgcolor="#F4FFE4" onLoad="detectRequired();">
<h1 align="center">Java 3D display is popping up... Please wait...</h1>
<p align="center"><strong>Notice: if Java 3D panel is not showing up...</strong></p>
<p align="center"><strong>We detected that: <span class="style1" id="java_detection">Nothing happened</span></strong></p>
<p align="center"><strong> and Java3D must be installed as well, please <a href="http://java3d.java.net/binary-builds.html">click here to install Java3D</a></strong></p>
<p align="center"><strong>Click here to go back to <a href="gallery.php">gallery page</a> or to <a href="index.php">upload page</a></strong></p>
<table width="100%" border="1" cellspacing="2" cellpadding="2" style="display:none">
  <tr>
    <td bgcolor="#CCCCCC"><div align="center">From depthmap</div></td>
    <td bgcolor="#CCCCCC"><div align="center">From texture</div></td>
  </tr>
  <tr>
    <td><div align="center"><img src="upload_stereo/result.jpg?<? echo rand(); ?>" /></div></td>
    <td><div align="center"><img src="upload_stereo/texture.jpg?<? echo rand(); ?>" /></div></td>
  </tr>
</table>

<p align="center">Hold and move mouse's left and right button to navigate the 3D scene, scroll mouse wheel for zooming in and out.
<p align="center"><em>(*) If Java 3D used to show before but not now, it might be caused that Java virtual machine is running out of memory, <br />
  you can either wait for 1-2 mins for it to slowly release unused memory or manually force Java virtual machine to terminate.
</em>

<hr></p>
<iframe src="http://www.ivs.auckland.ac.nz/quick_stereo/3d/" width="0" height="0">
  <p>Your browser does not support iframes.</p>
</iframe>
</body>
</html>
