<?
include "../quick_stereo/dbc.php";
include "menu.php";
deleteOldFiles('../quick_stereo/download/', 7);
deleteOldFiles('../quick_stereo/upload_stereo/StoreResults/', 7);
$directory = $_GET["d"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>About me</title>
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
          <td class="pageName"><div align="center"><img src="http://www.ivs.auckland.ac.nz/new_site_d/images/people/1317518307/min.jpg" /> </div></td>
		</tr>

		<tr>
          <td class="bodyText"><ol>
            <li><strong>Introduction:</strong><br />
              I am a PhD student at <a href="http://www.cs.auckland.ac.nz/">the Computer Science   department</a>, <a href="http://www.auckland.ac.nz/uoa/">the University of Auckland</a>. My work is related to Stereo Vision supervised by <a href="http://www.cs.auckland.ac.nz/our_staff/profile.php?id=pdel016">Dr.   Patrice Delmas</a> and <a href="http://www.cs.auckland.ac.nz/our_staff/profile.php?id=ggim001">Prof. Georgy Gimel'farb</a>.<br />
                <br />
                I have been   raised in a family where education is seen as important. I have always   been taught that hard work is important. I have studied hard and   achieved academic success. I am confident, technologically aware, have   good communication skills and willingly work hard to achieve goals.<br />
                <br />
                I   have really enjoyed living in New Zealand over the last nine years. I   have developed a strong, warm relationship with a Kiwi family, and I   treasure their love and respect. I am forever in their debt because they   have gone the extra distance and taught me so many things about New   Zealand and its many cultures.<br />
                <br />
                Studying in New Zealand has given   me the chance to live independently, and taught me how important hard   work is. And, of all of the work I have done, the most rewarding has   been working as a marker and a lab demonstrator. I enjoy the process of   finding ways to show people how to solve problems, and also to look at   different solutions to problems.<br />
                <br />
                I have been with the IVS groups since 2007 first completing MSc   studies on web-based stereo matching capabilities (winning best poster   award at IVCNZ 2008 the premiere Australasian Conference in Image   Processing and Computer Vision). <br />
                <br />
                I was awarded   a BuildIT PhD scholarship in 2009 and has been undertaking PhD studies   since. My work mainly focuses on web-driven and web-based computer   vision applications using our stereo matching algorithms. <br />
                <br />
                My most   recent research achievements include the design   of an iphone app for depth computation, an autostereogram generator   from stereo-images, a complete automatic web-based application which   allows any user to upload left-right images and retrieve depth map with   with no knowledge required. <br />
              <br />
                I have 8 publications, have been involved in 2 industrial   contracts with Alcatel-France and Ecole Militaire ST Cyr Coetquidan as   well as in a Uniservices project on real-time stereo matching.              <br />
                <br />
            </li>
            <li><strong>Publications: </strong></li>
            </ol>            
            <blockquote>
              <ul>
                <li> M. Nguyen, R. Gong, Y.-H. Chan, P. Delmas, G. Gimel'farb. ``Context-driven Composite Stereo Reconstruction''. In Proceedings of the <em>27th International Conference Image and Vision Computing New Zealand (IVCNZ 2012)</em>, Dunedin, New Zealand, 26-28 November, pages 290--294, 2012.</li>
                <li>E. Chan, P. Delmas, M. Nguyen, A. Gastelum, R. S. Yang, R. Gong, N. Liu, G. Gimel'Farb, S. Bertin and H. Friedrich. ``On Location Experiments on River Bed Stereophotogrammetry''. In Proceedings of the <em>27th International Conference Image and Vision Computing New Zealand (IVCNZ 2012)</em>, Dunedin, New Zealand, 26-28 November, pages 412--417, 2012.</li>
                <li>M. Nguyen, G. Gimel'farb, P. Delmas. ``Recent Advances in Online Stereo Web Application''. In Proceedings of the <em>26th International Conference Image and Vision Computing New Zealand (IVCNZ 2011)</em>, Auckland, New Zealand, 26-28 November, pages 411--416, 2011.</li>
                <li>M. Nguyen, G. Gimel'farb, P. Delmas., R. Yang, A. Woodward. ``Novel Web-based Autostereogram Creation using GPU Stereo Vision''. In Proceedings of the <em>26th International Conference Image and Vision Computing New Zealand (IVCNZ 2011)</em>, Auckland, New Zealand, 26-28 November, pages 137--142, 2011.</li>
                <li>M. Nguyen, G. Gimel'farb, P. Delmas., Y.H. Chan, A. Gastelum Strozzi, A. Woodward. ``Online Rapid Prototyping of 3D Objects using GPU based 3D Cloud Computing: Application to 3D Face Modelling''. In Proceedings of the <em>IAPR Conference on Machine Vision Applications (MVA2011)</em>, Nara, Japan, Jun 13-15, pages 434--437, 2011.</li>
                <li>M. Nguyen, G. Gimel'farb, P. Delmas. ``Stereo Vision: A Java-based Online Platform''. In Proceedings of the <em>IAPR Conference on Machine Vision Applications (MVA2009)</em>, Yokohama, Japan, May 20-22, pages 483--486, 2009.</li>
                <li>M. Nguyen, G. Gimel'farb, P. Delmas. ``Web-based On-line Computational Stereo Vision''. In Proceedings of the <em>23rd International Conference Image and Vision Computing New Zealand (IVCNZ 2008)</em>, Lincoln University, Christchurch, New Zealand, 26-28 November, pages 1--6, 2008.</li>
                <li>M. Nguyen, G. Gimel'farb, P. Delmas. ``A Web Community for Shared Computational Stereo Vision''. In <em>Computer Science Graduate Workshop</em>, The University of Auckland, New Zealand, 31 October, 2008</li>
              </ul>
            </blockquote>            </td>
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
