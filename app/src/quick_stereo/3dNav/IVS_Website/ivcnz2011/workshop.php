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
<title>IVCNZ2011</title>
<style type="text/css">
<!--
.style1 {
	color: #0066FF
}
.style2 {color: #FF0000}
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
                          <div style="text-align:center;">
						    <p align="center" style="margin-top: 0;"><img src="images/ivcnz2010.png" width="100%"></p>
						    <h1 align="center" class="style1">IVCNZ 2011 Workshop day</h1>
                            </h2>
						    <h1 align="center">November 28, Jubilee building, Parnell, Auckland, New Zealand</h1>
						    <h1 style="margin-top:0px;"><span class="style2"><a href="programme_workshop.php">Programme</a></span></h1>
                          </div>                         
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