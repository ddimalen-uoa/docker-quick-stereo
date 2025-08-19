<?
include "menu.php";

$directory = $_GET["d"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Download page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="mm_health_nutr.css" type="text/css" />
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
  include_once("header.php");
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
        include_once("menu.php");
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
          <td class="pageName"><div align="center">Download page </div></td>
		</tr>

		<tr>
          <td class="bodyText"><p>&nbsp;</p>
            <ol>
              <li><a href="http://www.ivs.auckland.ac.nz/web/manual3DStereoApp.php">3D Stereo App (PC version)</a> - updated on 2013-04-05
                <ol>
                  <li><a href="http://www.ivs.auckland.ac.nz/web/manual3DStereoApp.php">Download here.</a></li>
                  <li><a href="http://www.ivs.auckland.ac.nz/web/manual3DStereoApp.php">3D Stereo App</a> allows users to <strong>extract depth from Stereo images</strong> such as MPO files.</li>
                  <li>It automatically rectifies images using uncalibrated rectification method.</li>
                  <li>It automatically estimates all parameters.</li>
                  <li>A number of stereo matching techniques are provided.</li>
                  <li>It returns left and right stereo images, correspondence set between images, grey-scaled and colour-coded disparity-maps.</li>
                </ol>
              </li>
              <li><a href="http://www.ivs.auckland.ac.nz/web/manual3DStereoApp/StereoVision.apk">Android Stereo Vision Ap</a>p - updated on 2013-08-30
                <ol>
                  <li><a href="http://www.ivs.auckland.ac.nz/web/manual3DStereoApp/StereoVision.apk">Download here</a>.</li>
                  <li>Install it to your Android phone.</li>
                  <li>Take 2 snapshots and receive quickly a 3D scene.</li>
                </ol>
              </li>
          </ol></td>
        </tr>
		<tr>
		  <td class="bodyText">&nbsp;</td>
	    </tr>
      </table>
	 <br />
	&nbsp;<br />	<img src="mm_spacer.gif" alt="" width="50" height="1" border="0" /><br />
    &nbsp;<br /></td>
    <td valign="top" bgcolor="#5D743C"><table width="190" border="0" align="right" cellpadding="0" cellspacing="0" id="leftcol2">
      <?
	 include_once("right_panel.php");
	 echo returnRightPanel();
	 ?>
    </table></td>
  </tr>
  <?
	include_once("footer.php");
	echo footerControl();
  ?>
</table>
<?
include_once("footer.php");
echo returnFooter();
?>
</body>
</html>
