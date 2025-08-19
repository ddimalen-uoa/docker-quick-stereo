<?
include "menu.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Minh Nguyen s new project</title>
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
          <td class="pageName">3D Stereo Camera Calibration</td>
        </tr>
        <tr>
          <td  class="bodyText"><p>This page, we allow users to do camera calibration of single camera, stereo camera and rectification on calibrated camera.</p>
          <p>Camera calibration extracts intrinsic and extrinsic parameters of cameras such as focus length. Using the extracted parameters, we can apply some transformation to correct the image such as lens distortion effects and align images.</p>
          <p>Please choose an action:            
            <label>
            <select name="calibration" id="calibration">
              <option value="any">Calibrate and process Any Side By Side Camera System</option>
              <option value="minoru">Calibrate and process 3D Minoru web cam</option>
              <option value="dualCam">Calibrate and process side by side web cams</option>
              </select>
            </label>
            <strong><br />
            </strong><em class="smallText">(*) Before going to calibration page, remember that you need to accquire a few images of the following calibration pattern. 
            If ever username/password is asked, you can use temp/512 for a quick access.</em><br />
            <input type="button" name="button" id="button" value="Start calibration now" onclick="
            if(confirm('Please make sure you have the below check board ready, it has to be 9x7 square board. Thanks! ')){ 
            	if(document.getElementById('calibration').value == 'dualCam'){
                	document.location='../calibrate_minoru/capture_images.php?source=dualCam';
                }
                else if(document.getElementById('calibration').value == 'any'){
                	document.location='../calibrate_minoru/capture_images.php?source=any';
                }
                else{
            		document.location='../calibrate_minoru/capture_images.php?source=minoru';
                }
            }
            " /></p>
          <p><img src="Resources/check-108.png" width="90%" /></p></td>
        </tr>
        <tr>
          <td class="pageName"><a href="http://code.google.com/p/sentience/wiki/CameraCalibration">Camera Calibration</a> and <a href="http://en.wikipedia.org/wiki/Image_rectification">Rectification</a></td>
		</tr>

		<tr>
          <td class="bodyText"><p align="justify">The overall goal of this research is to create a number of applications related to Stereo Vision techniques. 
            Generally, such applications require inputs as images captured by stereo camera, which produces left and right image respectively. 
            In this research, I will use images produced by Fujifilm W1 camera <a href="http://www.fujifilm.com/products/3d/camera/finepix\_real3dw1/">[footnote]</a>, 
            Minoru 3D webcam <a href="http://www.minoru3d.com/">[footnote]</a>, Ueye cameras <a href="http://www.ids-imaging.com/">[footnote]</a>, and possibly Nintendo 3DS <a href="http://www.nintendo.com/3ds">[footnote]</a>. 
            It must start with camera calibration and rectification, which is unavoidable process to align captured image horizontally. Camera calibration is implemented 
            with openCV <a href="http://opencv.willowgarage.com/wiki/">[footnote]</a> using Zhang’s method <a href="bib.php#zhangCalib">[ref]</a> to reconstruct the fundamental matrix <a href="bibi.php#fundamentalMatrix">[ref]</a> 
            for rectification of stereo camera. Only when images are correctly rectified, stereo matching algorithms can reconstruct good 3D information from the depicted left 
            and right mages. In this research, we will use Dynamic Programming (SDPS) <a href="bib.php#sdps">[ref]</a>, Belief Propagation <a href="#">[ref]</a>, or Graph Cut <a href="#">[ref]</a> for stereo matching.
            The following will describe in more detail of how we build the camera calibration and rectification.
          <p align="justify"><a href="http://www.ivs.auckland.ac.nz/calibrate_minoru/">Click here to start the process.</a></td>
        </tr>
		<tr>
          <td class="pageName">Why camera calibration necessary</td>
	    </tr>
		<tr>
          <td class="bodyText"><p align="justify">Camera calibration process helps finding quantities of internal and external conditions that affect the image processing. Those are position of image centre, focal length, 
scaling facters, skew factor and lense distortion. This is  a necessary step to recontruct the world model from the captured images, this makes the interaction with the world possible. 
Amongth them, lense distortion are created mainly by inexpensive single lenses system, forming distortion to image at its periphery <a href="bib.php">[ref]</a>.

The below picture shows an example of camera distortion appeared on the left image and the correction of that after camera rectification showing on the right picture. 
Basically, after camera calibration process, paralelled lines in the real world remain paralelled in the camera image.</p></td>
	    </tr>
		<tr>
		  <td class="bodyText"><img src="Resources/raw-image-w-lens-distortion-300x225.png" width="50%" /><img src="Resources/corrected-image-300x225.png" width="50%" /></td>
	    </tr>
		<tr>
		  <td class="bodyText"><div align="center">An example of raw image with lens distortion and its correction <a href="bib.php">[ref]</a>, making paralleled lines in real world paralleled in the image.</div></td>
	    </tr>
		<tr>
          <td class="pageName">Stereo Camera Rectification</td>
	    </tr>
		<tr>
          <td class="bodyText">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><p><strong>Image rectification</strong> is a transformation process used to   project two-or-more images onto a common image plane. It requires that image   distortion is corrected (by the above camera calibration) and then transforms the image into a standard coordinate system. It is widely used in Stereo Matching process  to simplify the problem of finding matching points between images <a href="http://en.wikipedia.org/wiki/Image_rectification">[ref]</a>.</p>
                  <p>In real life, setting up a stereo pair of camera is trivial but the following two problems have to be faced: </p>
                  <ul>
                    <li>impracticality or impossibility of perfectly aligning cameras</li>
                    <li>perfectly aligned cameras may become misaligned over time</li>
                  </ul>
                  <p>The diagram on the right hand side demonstrate the process. <a href="http://en.wikipedia.org/wiki/Stereo_vision" title="Stereo vision">Stereo vision</a> uses triangulation based on <a href="http://en.wikipedia.org/wiki/Epipolar_geometry" title="Epipolar geometry">epipolar geometry</a> to determine distance to an object. Between two cameras there is a problem of finding a corresponding   point viewed by one camera in the image of the other camera (known as   the <a href="http://en.wikipedia.org/wiki/Correspondence_problem" title="Correspondence problem">correspondence problem</a>).   In most camera configurations, finding correspondences requires a   search in two-dimensions. However, if the two cameras are aligned to be <a href="http://en.wikipedia.org/wiki/Coplanar" title="Coplanar">coplanar</a>, the search is simplified to one dimension - a horizontal line parallel to the <a href="http://en.wikipedia.org/wiki/Baseline" title="Baseline">baseline</a> between the cameras. Furthermore, if the location of a point in the   left image is known, it can be searched for in the right image by   searching left of this location along the line, and vice versa (see <a href="http://en.wikipedia.org/wiki/Binocular_disparity" title="Binocular disparity">binocular disparity</a>). Image rectification is an equivalent (and more often used <a href="http://en.wikipedia.org/wiki/Image_rectification#cite_note-ORAM2001-0">[1]</a>) alternative to perfect camera alignment. Image rectification is usually performed regardless of camera precision due to</p>                </td>
                <td width="30%"><div align="right"><img width="95%" src="Resources/Image_rectification.png" /></div></td>
              </tr>
              <tr>
                <td></td>
                <td><div align="center">The search space before (1) and after (2) rectification <a href="http://en.wikipedia.org/wiki/Image_rectification">[ref]</a></div></td>
              </tr>
            </table></td>
	    </tr>
		<tr>
		  <td class="pageName">&nbsp;</td>
	    </tr>
		<tr>
          <td class="pageName">The process</td>
	    </tr>
		<tr>
          <td class="bodyText"><p align="justify">There are a number of steps to be carried out by the users as stated:</p>
            <ol>
              <li>User prepare a stereo camera, can be <a href="http://www.minoru3d.com/">Minoru webcam</a> or <a href="http://www.fujifilm.com/products/3d/camera/finepix_real3dw1/">Fujifilm W1</a> with manual focus preset.<br />
              </li>
              <li>User download <strong>calibration object </strong>(<a href="http://www.ivs.auckland.ac.nz/calibrate_minoru/StereoCalibration.pdf">downloadable PDF file</a>), print out and stick horizontally to a flat wall or surface.<br />
              </li>
              <li>User takes from 1 to 16 captures of the <strong>calibration object </strong>and uploads them back to the internet.              </li>
              <li>Web-server processes the images and produce intrinsic and extrinsic parameters. <br />
              </li>
              <li>User checks the rectification results displayed on the web and decide to save these parameters for further use.</li>
            </ol>
            <p>After the parameters are saved to the server’s database, it can be reused at any later stage to rectify new images.<br />
              <br align="justify" />
            </p></td>
	    </tr>
		<tr>
		  <td class="bodyText">&nbsp;</td>
	    </tr>
		<tr>
		  <td class="bodyText"><a href="http://www.ivs.auckland.ac.nz/calibrate_minoru/"><img src="Resources/rectification_demo.png" width="100%" /></a></td>
	    </tr>
		<tr>
		  <td class="bodyText"><div align="center">Demostration of camera rectification to improve stereo matching process</div></td>
	    </tr>
		<tr>
		  <td class="bodyText">&nbsp;</td>
	    </tr>
		<tr>
          <td class="pageName">Output sample</td>
	    </tr>
		<tr>
          <td class="bodyText"><p align="justify"><img src="Resources/cali1.png" width="100%" /><br align="justify" />
            </p>          </td>
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
