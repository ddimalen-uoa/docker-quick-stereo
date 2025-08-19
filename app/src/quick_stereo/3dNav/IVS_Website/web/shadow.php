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
<title>Shadow generation on 2D images</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="mm_health_nutr.css" type="text/css" />
<script type="text/javascript" src="./jquery/jquery.min.js"></script>
<script type="text/javascript" src="./jquery/jquery.json.js"></script>
<script language="JavaScript" type="text/javascript">
//--------------- LOCALIZEABLE GLOBALS ---------------
var d=new Date();
var monthname=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
//Ensure correct for language. English is "January 1, 2004"
var TODAY = monthname[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear();
//---------------   END LOCALIZEABLE   ---------------
</script>

<script language="JavaScript">
function point_it(event){
	pos_x = event.offsetX?(event.offsetX):event.pageX-document.getElementById("pointer_div").offsetLeft - 166 - 50;
	pos_y = event.offsetY?(event.offsetY):event.pageY-document.getElementById("pointer_div").offsetTop - 360;
	//document.getElementById("cross").style.left = (pos_x-1) ;
	//document.getElementById("cross").style.top = (pos_y-15) ;
	//document.getElementById("cross").style.visibility = "visible" ;
	document.pointform.form_x.value = pos_x;
	document.pointform.form_y.value = pos_y;
		
	//alert('here');
	showPackage();
	
	//document.getElementById("theImage").src = 'http://www.ivs.auckland.ac.nz/web/shadow/result.png';
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
			setTimeout ( 'jQuery("#pointer_div").html(jsonObj["result"]);', 2500 );
		}
	);
}

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
          <td class="pageName"><div align="left">Shadow generation on 2D images: </div></td>
		</tr>

		<tr>
          <td class="bodyText"><ol>
            <li>Capture 2 stereo images (left and right) using different methods</li>
            <li>Stereo matching - upload <a href="../quick_stereo/index.php">online here</a> to generate depth map and texture images</li>
            <li>Shadow generation - upload onlineto generate 3D images viewable by any existing software</li>
            <li>View dynamically generated shadow below<a href="#"></a></li>
          </ol>          </td>
        </tr>
         <tr>
           <td class="pageName">&nbsp;</td>
         </tr>
         <tr>
          <td class="pageName"><div align="left">Demonstration (click on image to view how shadow generated after 2 seconds): </div></td>
		</tr>
		<tr>
		  <td class="bodyText"><form name="pointform" method="post">
<div id="pointer_div" onclick="point_it(event)" onmouseover="this.style.cursor='all-scroll'" style=" width: 450px;">  
<img src="http://www.ivs.auckland.ac.nz/web/shadow/result.png?<? echo rand(); ?>" />
</div>
<div align="left">
  <p>Lightsource at x = 
    <input type="text" name="form_x" id="form_x" size="4" /> 
    - y = 
    <input type="text" name="form_y" id="form_y" size="4" /> 
    - z = 500</p>
  </div>
		  </form> </td>
	    </tr>
        <tr>
          <td class="pageName"><div align="left">If you wish to change the above image, click images below: </div></td>
		</tr>
        <tr>
          <td class="bodyText"><table width="100%" border="1" cellspacing="0" cellpadding="2">
            <tr>
              <td width="20%"><div align="center"><a href="change.php?name=Aloe"><img src="shadow/MidlleBurryImages/Aloe/view1small.png" width="100" height="87" /></a></div></td>
              <td width="20%"><div align="center"><a href="change.php?name=Art"><img src="shadow/MidlleBurryImages/Art/view1small.png" width="100" height="87" /></a></div></td>
              <td width="20%"><div align="center"><a href="change.php?name=Baby1"><img src="shadow/MidlleBurryImages/Baby1/view1small.png" width="100" height="87" /></a></div></td>
              <td width="20%"><div align="center"><a href="change.php?name=Books"><img src="shadow/MidlleBurryImages/Books/view1small.png" width="100" height="87" /></a></div></td>
              <td width="20%"><div align="center"><a href="change.php?name=Bowling1"><img src="shadow/MidlleBurryImages/Bowling1/view1small.png" width="100" height="87" /></a></div></td>
            </tr>
            <tr>
              <td width="20%"><div align="center"><a href="change.php?name=Cloth3"><img src="shadow/MidlleBurryImages/Cloth3/view1small.png" width="100" height="87" /></a></div></td>
              <td width="20%"><div align="center"><a href="change.php?name=Dolls"><img src="shadow/MidlleBurryImages/Dolls/view1small.png" width="100" height="87" /></a></div></td>
              <td width="20%"><div align="center"><a href="change.php?name=Lampshade2"><img src="shadow/MidlleBurryImages/Lampshade2/view1small.png" width="100" height="87" /></a></div></td>
              <td width="20%"><div align="center"><a href="change.php?name=Laundry"><img src="shadow/MidlleBurryImages/Laundry/view1small.png" width="100" height="87" /></a></div></td>
              <td width="20%"><div align="center"><a href="change.php?name=Moebius"><img src="shadow/MidlleBurryImages/Moebius/view1small.png" width="100" height="87" /></a></div></td>
            </tr>
          </table></td>
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
