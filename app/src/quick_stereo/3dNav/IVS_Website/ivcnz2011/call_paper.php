<!-- HOME page -->
<?php 
//include("../php_script/password_protect.php");
require_once("../new_site_d/php/common.php"); //for header and footer
$ivs_location = "../new_site_d/";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<?php title();?>
		<link rel='stylesheet' type='text/css' href='<? echo $ivs_location; ?>css/common.css' />	
		<link rel='stylesheet' type='text/css' href='<? echo $ivs_location; ?>css/home.css' />
		<script type='text/javascript' src='<? echo $ivs_location; ?>js/util.js'></script>
		<script type='text/javascript' src='<? echo $ivs_location; ?>js/main_slideshow.js'></script>
<title>call for paper</title>
<style type="text/css">
<!--
.style1 {
	color: #0066FF
}
.style2 {color: #FF0000}
.style4 {color: #FF0000; font-weight: bold; }
-->
</style>
</head>
	<body>
		<div id='container' style="min-height:0px;">
			<div id='wrapper'>
				
				<!-- Content -->
				<div id='mid_content_main'>
					<div id='div_ivcnz_main'>
						<div id='left_pad'>
						  <? require_once("side.php"); echo side(); ?>
				      </div>
						<div id='right_pad'>						  
						  <p align="center" style="margin-top: 0;"><img src="images/ivcnz2010.png" width="100%"></p>
						  <h1 align="center" class="style1">IVCNZ 2011</h1>
						  <h2 align="center">Twenty-sixth International Conference Image and Vision Computing New Zealand<br>
					      </h2>
						  <h1 align="center">November 29 - December 1, 2011, Auckland, New Zealand<br>
					        </h1>
						  <h1 align="center"><span class="style2">Call for Papers</span></h1>
						  <p align="justify">Image and Vision Computing New Zealand is New Zealand's premiere academic conference on all aspects of Computer 
						    Vision, Image Processing, Visualisation, Computer Graphics, VR/AR and HCI applications related to these field. 
					          <br>
			              Homepage: <a href="http://www.ivs.auckland.ac.nz/ivcnz2011">http://www.ivs.auckland.ac.nz/ivcnz2011</a></p>
						  <p align="justify">						    In 2011, IVCNZ will be held in Auckland, New Zealand&rsquo;s largest and most diverse city. Auckland is build on top of 50 
						    extinct volcanoes and is situated on a narrow isthmus between the Pacific Ocean and Tasman Sea. Auckland has 
						    beautiful swimming and surf beaches, breathtaking scenery, great hiking &amp; fishing opportunities, a diverse culture, and 
						    the largest Polynesian population of any city worldwide. Auckland is a major tourist attraction in itself 
						    (<a href="http://www.aucklandnz.com/">http://www.aucklandnz.com/</a>). Other top attractions easily reached by car include the famous glow worm caves and 
						    black water rafting in Waitomo (1.5h), geothermal areas and hot pools around Rotorua (2.5h), and Tongariro National 
						    Park (3.5h) with NZ&rsquo;s highest volcano, world famous hikes and breathtaking scenery (seen in &ldquo;Lord of the Rings&rdquo;).</p>
						  <p align="justify">						    If you have a new idea or some interesting results in any area related to vision and computer graphics, then consider 
						    submitting a paper to IVCNZ 2011!</p>
						  <h3 align="justify">Relevant topics include, but are not limited to:</h3>
						  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="50%">Augmented and Virtual Reality<br>
                                Automated visual surveillance<br>
                                Biomedical Imaging and Visualisation<br>
                                Feature detection and feature extraction<br>
                                Geometric algebra in vision and graphics<br>
                                Graph matching<br>
                                Human Computer and Human Robot Interfaces<br>
                                Image based rendering<br>
                              Image processing for environmental sciences</td>
                              <td>Image analysis and understanding<br>
                                Modelling, rendering and animation techniques<br>
                                Motion tracking and analysis<br>
                                Object recognition<br>
                                Pattern recognition, clustering, and classification<br>
                                Reconstruction techniques<br>
                                Scientific visualization<br>
                                Stereo analysis<br>
                              Shape recovery from multiple images</td>
                            </tr>
                          </table>
						  <p>Submissions must be complete manuscripts of 4-6 pages in double column format, and should be submitted as PDF 
						    files. LaTeX and Word templates may be downloaded from: <a href="http://www.ivs.auckland.ac.nz/ivcnz2011/submissions.php">http://www.ivs.auckland.ac.nz/ivcnz2011/submissions.php</a></p>
						  <p>The conference will consist of a mix of oral and poster sessions. No distinction will be made between the two types of 
						    presentation in the proceedings.</p>
						  <h3 align="center" class="style2"><a href="http://www.ivs.auckland.ac.nz/ivcnz2011_temp/htdocs/">Click here to register and submit papers</a></h3>
						  <? echo importantDate();?>
					      <p><a href="call_paper/CFP_IVCNZ2011_v6.pdf">Click here to download full pdf</a></p>
					      <hr>
					  </div>
						<div>
                        <p><img src="IVCNZ2011_files/image002.jpg" width="100%"></p>
                        </div>               				
				  </div>
			  </div>	
				
			</div>
		</div>
	</body>
</html>