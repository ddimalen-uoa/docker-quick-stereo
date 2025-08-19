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
	//document.getElementById('display_right').src='images/resultr.jpg?' + new Date();
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
  <td bgcolor="#CCCCCC" width="650px"><div align="center">Inputs</div></td>
  <? } ?>
  <td bgcolor="#CCCCCC"><div align="center">Processed realtime results</div></td>
</tr>
<tr>

<?
if($_GET["result"] != 1){
?>
<td>
<div id="flashArea" class="flashArea" style="height:100%;"><p align="center">This content requires the Adobe Flash Player.<br /><a href="http://www.adobe.com/go/getflashplayer">
						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /><br />
    <a href=http://www.macromedia.com/go/getflash/>Get Flash</a></p>
	</div>

  <div align="center" style="text-align:center">
    <script type="text/javascript">
	var mainswf = new SWFObject("take_picture.swf", "main", "650", "660", "9", "#ffffff");
	mainswf.addParam("scale", "noscale");
	mainswf.addParam("wmode", "window");
	mainswf.addParam("allowFullScreen", "true");
	//mainswf.addVariable("requireLogin", "false");
	mainswf.write("flashArea");

  </script>
    <script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
  </script>
    <script type="text/javascript">
var pageTracker = _gat._getTracker("UA-3097820-1");
pageTracker._trackPageview();
  </script>
  </div>  </td>
  <? } ?>
<td valign="top"><div align="center">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">    
    <tr>
      <td colspan="2"><div align="center"></div></td>
      </tr>
    <tr>
      <td colspan="2"><div align="center"><img src="images/temp.png" name="display_image" width="640" height="480" id="display_image"></div></td>
      </tr>
  </table>
</div>
  <p align="center">
    <input type="button" name="button" id="button" value="Get realtime result" onClick="setInterval('callEverySecond()', 500);"></p></td>
</tr>
</table>

</body>
</html>
