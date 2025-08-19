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
        <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAHOTacLyYT2RkUF23YS0w7BQQCh-WgPtMX9vpe3p88t05ZvbZ-xRE8b_qz8omGIDFbEcEj4HvZNuaUg" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
	var address = '545 Parnell Rd, Parnell';
    function load( address) {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
	//map.setCenter(new GLatLng(37.4419, -122.1419), 13);
        map.setUIToDefault();		
		var geocoder = new GClientGeocoder();
		//var address = '11 Union street, Auckland CBD, Auckland';
		//function showAddress(address) {
		  geocoder.getLatLng(
			address,
			function(point) {
			  if (!point) {
				//alert(address + " not found");
			  } else {
			  	map.setCenter(point, 15);
				
				var marker = new GMarker(point);
				map.addOverlay(marker);
				//marker.openInfoWindowHtml(address);
			  }
			}
		  );
		//}
      }
    }
    //]]>
    </script>
<title>IVCNZ2011</title>
<style type="text/css">
<!--
.style1 {
	color: #0066FF
}
.style2 {color: #FF0000}
a:hover {color:#FFFFFF;}  /* mouse over link */
-->
</style>
</head>
	<body onload="load(address)" onunload="GUnload()">
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
						    <h1 align="center" class="style1">IVCNZ 2011</h1>
						    <h2 align="center">Twenty-sixth International Conference Image and Vision Computing New Zealand<br>
                            </h2>
						    <h1 align="center">November 29 - December 1, 2011, Auckland, New Zealand</h1>
						    <h1 style="margin-top:0px;"><span class="style2">Venue: Jubilee Building</span></h1>
                          </div>
                          <table width="10%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <td rowspan="2" width="50%"><div align="right"><img src="images/venue2.png"></div></td>
                              <td width="50%"><img src="images/venue1.png"></td>
                            </tr>
                            <tr>
                              <td><img src="images/venue3.png"></td>
                            </tr>
                          </table> 
                          <hr>                        
                          <table width="100%" border="0" cellspacing="5" cellpadding="5">
                            <tr>
                              <td width="100%" valign="top">
                              <h3>Venue/Cruise:
                              </h3>
                              <ul>                                
                                <li><a href="javascript:load('545 Parnell Rd, Parnell');">IVCNZ Venue: Jubilee Building</a></li>
                                <li><a href="javascript:load('-36.839179,174.765403');">Willian C Daldy Pick up point</a> <strong>(Cruise departs at 7pm sharp, please be there at 6:30pm, boarding 6:45pm)</strong></li>
                              </ul>
                              <h3>Auckland Attractions:
                              </h3>
                              <ul>
                                <li><a href="javascript:load('sky tower auckland');">Auckland City Centre (Sky tower)</a>.</li>
                                <li><a href="javascript:load('24 Omana Avenue Auckland');">Eden Garden</a></li>
                                <li><a href="javascript:load('Auckland Zoo');">Auckland Zoo</a></li>
                                <li><a href="javascript:load('Auckland Western Springs');">Western Springs + Motat Museum</a></li>                               
                                <li><a href="javascript:load('Auckland Town Hall');">Auckland Town Hall</a></li>
                                <li><a href="javascript:load('Auckland War Memorial Museum');">Auckland War Memorial Museum</a></li>
                                <li><a href="javascript:load('Auckland Art Gallery');">Auckland Art Gallery</a></li>
                                <li><a href="javascript:load('Auckland Kelly Tarlton Underwater World');">Kelly Tarlton Underwater World</a></li>
                                <li><a href="javascript:load('Auckland Mission Bay');">Mission Bay</a></li>
                                <li><a href="javascript:load('Auckland Domain');">Auckland Domain </a></li>
                                <li><a href="javascript:load('Auckland Waiheke Island');">Waiheke Island </a></li>
                                <li><a href="javascript:load('Auckland One Tree Hill');">One Tree Hill </a></li>
                                
                                
                                <li><a href="javascript:load('Auckland Botanical Garden');">Auckland Botanical Garden</a></li>
                              </ul>
                              <h3>How to get around by bus: </h3>
                              <ul>
                              <li>The Jubilee building is on the route of both the inner and outler link buses (for bus tinetable and route please go to <a href="http://www.maxx.co.nz/" target="_blank">MAXX</a>)</li>
                              <li>It takes 25 minutes by foot through the <a href="javascript:load('Auckland Domain');">Auckland Domain </a></li>
                              </ul>
                              </td>
                              <td width="500px" bgcolor="#0066FF"><div id="map" style="width: 500px; height: 600px"></div></td>
                            </tr>
                          </table>
                          <!--
                          <p><hr></p>
                          <p>  <? echo importantDate();?>
                          </p>
                          -->
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