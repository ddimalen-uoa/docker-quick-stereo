<?

//include("../php_script/password_protect.php");

include "menu.php";

$directory = $_GET["d"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Manuals for 3D Stereo App</title>
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
          <td class="pageName"><div align="center">Instruction Manual for <strong>3D Stereo App</strong> - beta version </div></td>
		</tr>

		<tr>
          <td class="bodyText"><ul>
            <li><strong>Introduction:
              </strong>
              <ul>
                <li>3D Stereo App allows users to <strong>extract depth from Stereo images</strong> such as MPO files.</li>
                <li>It automatically rectifies images using uncalibrated rectification method.</li>
                <li>It automatically estimates all parameters.</li>
                <li>A number of stereo matching techniques are provided.</li>
                <li>It returns left and right stereo images, correspondence set between images, grey-scaled and colour-coded disparity-maps.<br />
                  <br />
                </li>
              </ul>
            </li>
            <li><strong>Requirements:
              </strong>
              <ul>
                <li>It runs on Windows 7 operating system.</li>
                <li>All  MPO input images must be stored in <strong>INPUTS</strong> folder.</li>
                <li>All outputs are saved to <strong>OUTPUTS</strong> folder<br />
                  <br />
                </li>
              </ul>
            </li>
            <li><strong>Download link:</strong> <a href="http://www.ivs.auckland.ac.nz/web/manual3DStereoApp/3DStereoApp.rar"><strong>click here to download</strong></a><br />
              <br />
            </li>
            <li><strong>Password to extract: ivsnz2013</strong><br />
              <br />
            </li>
            <li><strong>Step by step instruction manual:
              </strong>
              <ul>
                <li>Click on <strong>MPO2DepthApp.exe</strong> <br />
                  <img src="manual3DStereoApp/start.png" width="113" height="143" /><br />
                  to start the application.<br />
                  <br />
                </li>
                <li>The initial interface will be displayed:<br />
                    <img src="manual3DStereoApp/interface.png" width="799" height="533" /><br />
                    Notice that a few functionalities are disable by default in this version.<br />
                  <br />
                </li>
                <li>Assume there are some MPO files located in <strong>INPUTS </strong>folder:<br />
                    <img src="manual3DStereoApp/inputFolder.png" width="665" height="453" /><br />
                  <br />
                </li>
                <li>Click<strong> Initialise button</strong> to collect all files in <strong>INPUTS</strong> foder, which names will be shown in middle text-box:<br />
                    <img src="manual3DStereoApp/collectInput.png" width="799" height="533" /><br />
                  <br />
                </li>
                <li>Click <strong>Start button</strong> to begin depth extraction from all input images. <br />
                  The process would take a few minutes depending on the number of inputs. <br />
                  Progress bar is updated with the number of images processed and to be processed:<br />
                    <img src="manual3DStereoApp/progress.png" width="799" height="533" /><br />
                    <br />
                </li>
                <li>During and after the processing, <br />
                  all results are stored in<strong> OUTPUT folders</strong> include <br />
                  grey-scaled and colour-coded disparity maps, left/right images, left/right rectified images, correpondence.csv file.<br />
                    <img src="manual3DStereoApp/output.png" width="959" height="804" /><br />
                  <br />
                </li>
                <li>Enjoy our product, there may be bugs or suggestions to improve the interface, <br />
                  please let us know.                </li>
                </ul>
            </li>
            </ul>            
            <p><hr/></p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
          <p>&nbsp;</p>		</td>
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
