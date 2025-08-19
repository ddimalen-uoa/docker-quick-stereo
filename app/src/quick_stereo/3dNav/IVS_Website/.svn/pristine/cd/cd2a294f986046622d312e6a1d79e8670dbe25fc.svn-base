<?
include "menu.php";
$directory = $_GET["d"];
copy($directory."_l_resized.jpg", "shadow/result.png");
copy($directory."_l_resized.jpg", "shadow/view1.png");
copy($directory."_GC.jpg", "shadow/disp1.png");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Shadow generation on 2D images</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="mm_health_nutr.css" type="text/css" />
<script type="text/javascript" src="./jquery/jquery.min.js"></script>
<script type="text/javascript" src="./jquery/jquery.json.js"></script>

<script language="JavaScript">
function point_it(event){
	pos_x = event.offsetX?(event.offsetX):event.pageX-document.getElementById("pointer_div").offsetLeft - 150 ;
	pos_y = event.offsetY?(event.offsetY):event.pageY-document.getElementById("pointer_div").offsetTop - 150;	
	document.pointform.form_x.value = pos_x;
	document.pointform.form_y.value = pos_y;		
	showPackage();	
}

function showPackage() {	
	var mouseX = jQuery("#form_x").val();
	var mouseY = jQuery("#form_y").val();
	jQuery.post("shadow_post.php", 
		{ 		
			mouseX:mouseX,
			mouseY:mouseY	
		},
		function(data) {
			//alert(data);
			var empty_value = '...';
			jsonObj = jQuery.evalJSON(data);			
			setTimeout ( 'jQuery("#pointer_div").html(jsonObj["result"]);', 7000 );
		}
	);
}
</script>

</head>
<body bgcolor="#000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  
  

	<table border="0" cellspacing="0" cellpadding="0" width="100%">
         <tr>
          <td class="pageName"><div align="left"></div></td>
		</tr>
		<tr>
		  <td class="bodyText"><form name="pointform" method="post">
<div id="pointer_div" onclick="point_it(event)" onmouseover="this.style.cursor='all-scroll'" style=" width: 800;">  
<img src="http://www.ivs.auckland.ac.nz/web/shadow/result.png?<? echo rand(); ?>" />
Click anywhere on the image to generate its shadow, it may takes up to 10 seconds, please wait...
</div>
<div align="left" style="display:none">
  <p>Lightsource at x = 
    <input type="text" name="form_x" id="form_x" size="4" /> 
    - y = 
    <input type="text" name="form_y" id="form_y" size="4" /> 
    - z = 500</p>
  </div>
		  </form> </td>
	    </tr>
      </table>
	 
</table>
</body>
</html>
