<?
include "../web/menu.php";
include "dbc.php";
if (!file_exists("images/stereogram.txt")) {
	file_put_contents("images/stereogram.txt", "images/stereogram.txt");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Shadow generation on 2D images</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../web/mm_health_nutr.css" type="text/css" />
<script language="JavaScript" src="../js/javascript.js"></script>
<script src="swfobject.js" language="javascript"></script>
<script type="text/javascript">
function callEverySecond(){
	//document.getElementById('display_left').src='images/resultl.jpg?' + new Date();
	//document.getElementById('display_right').src='images/resultr.jpg?' + new Date();
	document.getElementById('display_image').src='images/result.jpg?' + new Date();
}
</script>
<script language="JavaScript" type="text/javascript">
//--------------- LOCALIZEABLE GLOBALS ---------------
var d=new Date();
var monthname=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
//Ensure correct for language. English is "January 1, 2004"
var TODAY = monthname[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear();
//---------------   END LOCALIZEABLE   ---------------
</script>
</head>
<body bgcolor="#F4FFE4">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <?
  include_once("../web/header.php");
  returnHeader();
  ?>
  <tr>
    <td colspan="7" bgcolor="#5C743D"><img src="mm_spacer.gif" alt="" width="1" height="2" border="0" /></td>
  </tr>

  <tr>
    <td colspan="7" bgcolor="#99CC66" background="mm_dashed_line.gif"><img src="mm_dashed_line.gif" alt="line decor" width="4" height="3" border="0" /></td>
  </tr>

  <tr bgcolor="#99CC66">
  	<td colspan="7" id="dateformat" height="20">&nbsp;&nbsp;<script language="JavaScript" type="text/javascript">
      document.write(TODAY);	</script>	</td>
  </tr>
  <tr>
    <td colspan="7" bgcolor="#99CC66" background="mm_dashed_line.gif"><img src="mm_dashed_line.gif" alt="line decor" width="4" height="3" border="0" /></td>
  </tr>

  <tr>
    <td colspan="7" bgcolor="#5C743D"><img src="mm_spacer.gif" alt="" width="1" height="2" border="0" /></td>
  </tr>

 <tr>
    <td width="10%" valign="top" bgcolor="#5C743D">
	<table border="0" cellspacing="0" cellpadding="0" width="165" id="navigation">
        <?
        include_once("../web/menu.php");
        returnMenu();
        ?>
      </table>
 	 <br />
  	&nbsp;<br />
  	&nbsp;<br />
  	&nbsp;<br /> 	</td>
    <td width="50"><img src="mm_spacer.gif" alt="" width="50" height="1" border="0" /></td>
    <td colspan="4" valign="top"><img src="mm_spacer.gif" alt="" width="305" height="1" border="0" /><br />
	&nbsp;<br />
	&nbsp;<br />
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="pageName"><div align="center">Online Real-time stereogram creation on Minoru webcam<br />
            <br />
          </div></td>
		</tr>

		<tr>
          <td class="bodyText"><table width="100%" border="0">
<tr>
	<?
    if($_GET["result"] != 1){
    ?>
  <td bgcolor="#CCCCCC" width="650px"><div align="center">Live Input - Click on <strong>''Capture'' </strong>first to start sending live frame</div></td>
  <? } ?>
  <td bgcolor="#CCCCCC"><div align="center">Depth results - Click on<strong> ''Get Result''</strong> for real-time depthmap shown</div></td>
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
	var mainswf = new SWFObject("take_picture.swf", "main", "660", "660", "9", "#ffffff");
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
    <input type="button" name="button" id="button" value="Get Result" onClick="setInterval('callEverySecond()', 1000);" style="width:200px; height:50px">
  </p>
  <p align="center"><a href="../quick_stereo/index.php">Click here to go back to Control page</a></p></td>
</tr>
</table></td>
        </tr>
		<tr>
		  <td class="bodyText">&nbsp;</td>
	    </tr>
      </table>
	 <br />
	&nbsp;<br />	<img src="mm_spacer.gif" alt="" width="50" height="1" border="0" /><br />
    &nbsp;<br /></td>
    <td valign="top"></td>
  </tr>
  <?
	include_once("../web/footer.php");
	echo footerControl();
  ?>
</table>
<?
include_once("../web/footer.php");
echo returnFooter();
?>
</body>
</html>
