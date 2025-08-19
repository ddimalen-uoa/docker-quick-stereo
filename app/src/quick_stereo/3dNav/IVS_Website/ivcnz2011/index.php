<!-- HOME page -->
<?php
//if ($_SERVER['SERVER_PORT']!=443)
//{
//$url = "https://". $_SERVER['SERVER_NAME'] . ":443".$_SERVER['REQUEST_URI'];
//header("Location: $url");
//}
?>
<?php
//include("../php_script/password_protect.php");
require_once("../ivs_new_site/php/common.php"); //for header and footer
$ivs_location = "../ivs_new_site/";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<?php title();?>
		<link rel='stylesheet' type='text/css' href='<? echo $ivs_location; ?>css/common.css' />
		<link rel='stylesheet' type='text/css' href='<? echo $ivs_location; ?>css/home.css' />
		<script type='text/javascript' src='<? echo $ivs_location; ?>js/util.js'></script>
		<script type='text/javascript' src='<? echo $ivs_location; ?>js/main_slideshow.js'></script>
<title>IVCNZ2011</title>
<style type="text/css">
<!--
.style2 {color: #FF0000}
.style1 {	color: #0066FF
}
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
                          <div style="text-align:center; margin-top: 0px;">
                            <p align="center" style="margin-top: 0;"><img src="images/ivcnz2010.png" width="100%"></p>
						    <h1 align="center" class="style1">IVCNZ 2011</h1>
						    <h2 align="center">Twenty-sixth International Conference Image and Vision Computing New Zealand<br>
                            </h2>
						    <h1 align="center">November 29 - December 1, 2011, Auckland, New Zealand</h1>
						   <h1 style="margin-top:0px;"><span class="style2">Welcome to IVCNZ2011</span></h1>
						  </div>
                          <hr>
						  <p>Image and Vision Computing New Zealand is New Zealand's premier academic    conference on all aspects of Computer Vision, Image Processing, Visualisation    and Computer Graphics. Held annually, it attracts an international forum of    scientists and researchers. </p>
						  <p>After an exciting 2010 edition in Queenstown, IVCNZ returns to the greater    Auckland region (venue: <a href="http://www.parnell.org.nz/Parnell-Trust-Venue-Profile.htm" target="_blank">Jubilee building, Parnell</a>), home of the city of sails, volcanic islands    and black and white sandy beaches. </p>
						  <hr>
						  <? echo importantDate();?>
						  <hr>
						  <h3 align="center">Contact:</h3>
						  <p align="center">IVCNZ 2011 Convenors
						  <br>
						  <a href="mailto:IVCNZ2011@cs.auckland.ac.nz">IVCNZ2011@cs.auckland.ac.nz</a></p>
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