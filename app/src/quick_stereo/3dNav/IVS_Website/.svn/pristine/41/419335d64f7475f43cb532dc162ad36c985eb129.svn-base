<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<meta name="description" content="vamapaull - flash designer and developer - vamapaull's portfolio" />
<meta name="keywords" content="flash,web,design,development,developer,designer,coder,code,actionscript,script,animation,animations,interactive" />
<title>Photo booth</title>
<script src="swfobject.js" language="javascript"></script>
<script type="text/javascript">
function callEverySecond(){
	//document.getElementById('display_left').src='images/resultl.jpg?' + new Date();
	document.getElementById('display_right').src='images/temp.jpg?' + new Date();
	document.getElementById('display_image').src='images/result.jpg?' + new Date();
}
</script>
</head>
<body>
<table width="100%" border="1">
<tr>
	<?
    if($_GET["result"] != 1){
    ?>
  <? } ?>
  <td bgcolor="#CCCCCC"><div align="center">Processed realtime results</div></td>
</tr>
<tr>

<?
if($_GET["result"] != 1){
?>
<? } ?>
<td valign="top"><div align="center">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><div align="center"><img src="images/temp.jpg" name="display_image" id="display_right"></div></td>
      </tr>
    <tr>
      <td><div align="center">
        <input type="button" name="button" id="button" value="Get realtime result" onClick="setInterval('callEverySecond()', 500);">
      </div></td>
      </tr>
    <tr>
      <td><div align="center"><img src="images/temp.png" name="display_image" width="640" height="480" id="display_image"></div></td>
      </tr>
  </table>
</div>
  <p align="center">If you found the above depth image is not correctly displayed, you may have not done Camera Calibration properly. <br>
    <a href="../calibrate_minoru/index.php">Click here</a> to do the Camera Calibration</p></td>
</tr>
</table>

</body>
</html>
