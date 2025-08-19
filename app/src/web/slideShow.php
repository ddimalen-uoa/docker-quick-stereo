<?
include "../web/menu.php";
include "../quick_stereo/dbc.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>A web-based stereo vision system for research, educational purposes, and practical applications</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../web/mm_health_nutr.css" type="text/css" />
<script language="JavaScript" src="../js/javascript.js"></script>
<style type="text/css">
<!--
.style1 {color: #CC0000}
-->
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <?
  include_once("../web/header.php");
  returnHeader();
  ?>
  <tr>
    <td colspan="7" bgcolor="#444" ><img src="mm_spacer.gif" alt="line decor" width="4" height="3" border="0" /></td>
  </tr>

  <tr class="extra">
    <td colspan="7" bgcolor="#fff"><img src="mm_spacer.gif" alt="line decor" width="4" height="3" border="0" /></td>
  </tr>

  <tr bgcolor="#444" class="extra">
  	<td colspan="7" id="dateformat" height="20"><?php
    include_once("../web/horizontalMenu.php");
    //echo returnMenu();
  ?></td>
  </tr>
  <tr class="extra">
    <td colspan="7" bgcolor="#fff" ><img src="mm_spacer.gif" alt="line decor" width="4" height="3" border="0" /></td>
  </tr>

  <tr class="extra">
    <td colspan="7" bgcolor="#5C743D"><img src="mm_spacer.gif" alt="" width="1" height="2" border="0" /></td>
  </tr>

 <tr>
    <td width="0%" valign="top" bgcolor="#444">
	<table border="0" cellspacing="0" cellpadding="0" width="100%" id="navigation">
        <?
        //include_once("../web/menu.php");
        //returnMenu();
        ?>
      </table>
 	 <br />
  	&nbsp;<br />
  	&nbsp;<br />
  	&nbsp;<br /> 	</td>
    <td width="50"><img src="mm_spacer.gif" alt="" width="50" height="1" border="0" /></td>
    <td colspan="4" valign="top"><img src="mm_spacer.gif" alt="" width="305" height="1" border="0" /><br />
	  <table width="100%" border="0" cellpadding="0" cellspacing="5">
	    <tr>
	      <td class="pageName">A web-based stereo vision system for <br />
          research, educational purposes, and practical applications</td>
        </tr>
	    <tr>
	      <td class="bodyText"><p align="center">We introduce a new and versatile web-based system which allows users to <strong>dynamically generate surfaces of three-dimensional (3D) scenes</strong> from <strong>stereo image pairs</strong> taken with monocular and stereo cameras or web-cams.  <br />
	        The system uses the Internet to communicates with potential user around the world and accepts data from different imaging sources via direct or indirect uploads  of static individual images or live video sequences. <br />
	        If necessary the uploaded images are automatically co-aligned into stereo pairs using calibrated or uncalibrated rectification techniques depending on the input source. <br />
	        A rectified pair is processed (remotely or locally) by selecting from a number of different stereo matching algorithms in order to reconstruct  the scene and return it to the user as a disparity map, a virtual Java3D/webGL scene, a 3D .OBJ file, or a live depth video. <br />
	        The system is portable, simple to set up and operate, and is currently available online at <a href="https://www.ivs.auckland.ac.nz/quick_stereo"><strong>https://www.ivs.auckland.ac.nz/quick_stereo</strong></a>. <br />
	        Potential applications of the proposed system, e.g. remote camera control, on-line calibration and rectification, simple object and 3D avatar reconstruction, biometrics, collaborative on-line research meta-tools are discussed. </p>
	        <p align="center">
	          <!--<iframe width="425" height="349" src="https://www.youtube.com/embed/KRL3KUBKoxE?hl=en&amp;fs=1" frameborder="0" allowfullscreen="allowfullscreen"></iframe>-->
              <iframe src="https://www.ivs.auckland.ac.nz/web/displayGallery.php" height="660" width="870"></iframe>
            </p>
	        <p align="center">All the <strong>above 3D scenes</strong> are recontructed by <strong>Stereo Vision techniques</strong>, scene is changed <strong>every 10-20s</strong>, or <strong>press F5 </strong>to see a new sample set.<br />
              <a href="scene_gallery.php">Click here to view our 3D photo gallery</a></p>
	        <p align="center"><span class="style1">If the above canvas<strong> does not show 3D properly</strong>, <br />
          please refer to the below section on how to <a href="#enableWebGL" class="style1">enable WebGL on your browsers</a>.</span></p>          </td>
        </tr>
      </table>
	  <hr/> 
	  <h2>How to enable WebGL on browser</h2>
	  <p><strong><a name="enableWebGL" id="enableWebGL"></a>In  Chrome:</strong><br />
	  It <strong>should enables by default.</strong><br />
	  If not:<br />
	  <strong>Right click</strong> on your &quot;<strong>Chrome</strong>&quot; icon.<br />
	  Choose <strong>properties</strong><br />
	  At the end of your<strong> target line</strong>, place these parameters:      <strong>--enable-webgl</strong><br />
	  It should look like: <strong>&quot;chrome.exe --enable-webgl</strong>&quot;.</p>
	  <p><strong>In Firefox:</strong><br />
	    It should <strong>enables by default,</strong> if it <strong>is not:</strong><br />  
	    Type <strong>about:config</strong> into the address bar.<br />
Search for <strong>&quot;webgl&quot;</strong>, <br />
Double-click <strong>&quot;webgl.enabled_for_all_sites&quot;</strong> to set it to <strong>true</strong><br />
Or double-click <strong>&quot;webgl.force-enabled&quot;</strong> to set it to<strong> true</strong>.<br />
	  </p>
	  <p><strong>In Safari:</strong><br />
	    Open the <strong>Safari </strong>menu and select <strong>Preferences</strong>.<br />
	    Click the <strong>Advanced</strong> tab in the Preferences window.<br />
	    At the bottom of the window, check the <strong>Show Develop menu in menu bar</strong> checkbox.<br />
	    Open the <strong>Develop</strong> menu in the menu bar and select <strong>Enable WebGL</strong>.</p>
	  <p><strong>In Opera:</strong><br />
	    Type <strong>opera:config#Enable%20WebGL</strong> into the address bar.<br />
Set default value from<strong> 0</strong> to <strong>1</strong>.</p>
	  <p><strong>In Internet Explorer:</strong><br />
      <strong>No WebGL support </strong>at this momment.</p>
	  <p><br />
          </p></td>
    <td valign="top" class="rightMenu">
    <table width="0" border="0" align="right" cellpadding="0" cellspacing="0" id="leftcol2">
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
