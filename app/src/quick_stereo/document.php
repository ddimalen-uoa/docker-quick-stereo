<?
include "../web/menu.php";
include "dbc.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Tutorial</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../web/mm_health_nutr.css" type="text/css" />
<script language="JavaScript" src="../js/javascript.js"></script>
<script language="JavaScript" type="text/javascript">
//--------------- LOCALIZEABLE GLOBALS ---------------
var d=new Date();
var monthname=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
//Ensure correct for language. English is "January 1, 2004"
var TODAY = monthname[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear();
//---------------   END LOCALIZEABLE   ---------------
</script>
<style type="text/css">
.pageName {
	text-align: left;
}
</style>
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
          <td class="pageName"><div align="center">Introduction</div></td>
		</tr>

		<tr>
		  <td class="bodyText" style="text-align:left"><p><a href="http://www.ivs.auckland.ac.nz/quick_stereo">http://www.ivs.auckland.ac.nz/quick_stereo</a><br />
We present a fully featured, web-based, online system that allows Internet  users to upload their stereo photos (from 3D cameras or a single camera) and  return almost instantly 3D results which are displayed directly on the webpages. <br />
It also allows inputs as image URLs of many different types (individual,  cross-eyed, parallel-eyed, anaglyphic, auto stereogram images, etc.) and  different formats (jpg, png, bmp, mpo, etc.).<br />
Using an intelligent image rectification process, it automatically aligns the  photos horizontally. Semi-Automatic selection is also implemented to push the  automatic process.<br />
<br />
<a href="http://www.ivs.auckland.ac.nz/quick_stereo/gallery_ivs.php">http://www.ivs.auckland.ac.nz/quick_stereo/gallery_ivs.php</a><br />
This is a gallery webpage where users shared their processed results. <br />
Images are initially displayed as cross-eyed view. Left/right clicks for  navigating to different images. Click on images to see their 3D generated  scene.<br />
<br />
<a href="http://www.ivs.auckland.ac.nz/web/autostereogram.php">http://www.ivs.auckland.ac.nz/web/autostereogram.php</a><br />
This webpage dynamically generates 3D auto stereogram images from user  selected depth and texture images. Its purpose is to introduce to general users  how 3D illusion is created inside human brain while viewing a flat image. <br />
It also manages to evaluate the improvement of auto stereogram view-ability  with the help of extra features: texture, mask, and edges compare to  traditional random-dot feature.</p></td>
	    </tr>
		<tr>
          <td class="pageName"><div align="center">FAQs (to be completed)</div></td>
		</tr>
		<tr>
          <td class="bodyText" style="text-align:left">
          <p>This Online Stereo Matching system is a complete system which allow Interenet users to directly or indirectly upload left and right stereo image and return 3D information in many different forms.<br />
            The system gets more complex, therefore please read some FAQs below before starting some actions:</p>
            <ol>
              <li>What browser should I use?<br />
                We recommend you to use Firefox or Chrome.
              </li>
              <li>How to process stereo images from Fujifilm W1/W3.</li>
            </ol>
<p>&nbsp;</p>		</td>
        </tr>
		<tr>
		  <td class="bodyText">&nbsp;</td>
	    </tr>
      </table>
	 <br />
	&nbsp;<br />	<img src="mm_spacer.gif" alt="" width="50" height="1" border="0" /><br />
    &nbsp;<br /></td>
    <td valign="top"><table width="190" border="0" align="right" cellpadding="0" cellspacing="0" id="leftcol2">
      <?
	 include_once("../web/right_panel.php");
	 echo returnRightPanel();
	 ?>
    </table></td>
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
