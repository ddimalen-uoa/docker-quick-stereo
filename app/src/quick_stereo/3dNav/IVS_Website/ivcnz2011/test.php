<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>My Locations</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAHOTacLyYT2RkUF23YS0w7BQQCh-WgPtMX9vpe3p88t05ZvbZ-xRE8b_qz8omGIDFbEcEj4HvZNuaUg" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
    function load() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
	//map.setCenter(new GLatLng(37.4419, -122.1419), 13);
        map.setUIToDefault();		
		var geocoder = new GClientGeocoder();
		var address = '11 Union street, Auckland CBD, Auckland';
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
  </head>
  <body onload="load()" onunload="GUnload()">
    <div id="map" style="width: 500px; height: 300px"></div>
  </body>
</html>