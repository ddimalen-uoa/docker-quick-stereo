<?
include '../quick_stereo/dbc.php';
include "menu.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Dynamic Autostereogram Creator</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="mm_health_nutr.css" type="text/css" />
<link rel="stylesheet" href="../quick_stereo/css/main.css" type="text/css" />
<script language="JavaScript" type="text/javascript">
//--------------- LOCALIZEABLE GLOBALS ---------------
var d=new Date();
var monthname=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
//Ensure correct for language. English is "January 1, 2004"
var TODAY = monthname[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear();
//---------------   END LOCALIZEABLE   ---------------
</script>
<script language="JavaScript" src="../js/javascript.js"></script>
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
    <td colspan="4" valign="top"><img src="mm_spacer.gif" alt="" width="305" height="1" border="0" />
    <form id="form1" name="form1" method="post" action="#bottom">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="pageName"><div align="center">Dynamic Autostereogram Creator using Stereo Vision technique</div></td>
		</tr>	
           	
		<tr>
          <td class="bodyText"><div align="center">
            <p>&nbsp;</p>
            <p>View: 
              <select name="select" id="select">
                <option value="left">Reference image</option>
                <option value="disparity">Disparity image</option>
              </select>
              </p>
          </div></td>
		</tr>
        <tr>
		  <td class="pageName"><div align="center">
		    <table width="100%" border="0">
              
              <tr>
                <td width="5%"><img src="shadow/leftBut.png" width="100%" onmouseover="this.style.cursor='pointer';" onclick="lastStereogram();"/></td>
                <td width="90%">
                <img src="../stereogallery/middlebury/Aloe/view1.png" name="sampleStereogram" class="displayAutostereogram" id="sampleStereogram" onmouseover="this.style.cursor='pointer';"/>                </td>
                <td width="5%"><a href="?position=<? echo 10+intval($_GET["position"]); ?>"><img src="shadow/rightBut.png" width="100%" onmouseover="this.style.cursor='pointer';" ;/></a></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><table width="100%" border="0" cellspacing="1" cellpadding="1">
                  <tr>
                    <td width="50%"><div align="right">Original Image size: </div></td>
                    <td width="50%"><div align="left">800 x 600 pixels</div></td>
                  </tr>
                  <tr>
                    <td><div align="right">Repeated pattern width:</div></td>
                    <td><div align="left">
                      <input name="textfield" type="text" id="textfield" value="200" />
px</div></td>
                  </tr>
                  <tr>
                    <td><div align="right">Max disparity allowed:</div></td>
                    <td><div align="left">
                      <input name="textfield2" type="text" id="textfield2" value="50" />
px</div></td>
                  </tr>
                  <tr>
                    <td><div align="right">Pattern 1 x position:</div></td>
                    <td><div align="left">
                      <input name="textfield3" type="text" id="textfield3" value="0" />
px</div></td>
                  </tr>
                  <tr>
                    <td><div align="right">Pattern 2 x position: </div></td>
                    <td><div align="left">
                      <input name="textfield4" type="text" id="textfield4" value="160" />
px</div></td>
                  </tr>
                  <tr>
                    <td colspan="2"><div align="center">
                        <input type="submit" name="button" id="button" value="Generate Autostereogram now" />
                    </div></td>
                    </tr>
                </table>
                  <br />
                  <br />
                  <br />
                  <br />
                  <br />
                  <br /></td>
                <td>&nbsp;</td>
              </tr>
            </table>
		    
		    </div></td>
		</tr>
        
		<tr>        
      </table>
    </form>
	<p>	
	  <img src="mm_spacer.gif" alt="" width="50" height="1" border="0" />
    </p>	
	<a name="bottom" id="bottom"></a>
	<p id="imageExample" style="display:none"><img src="images/viewPen.png" /> </p></td>
    <td valign="top" bgcolor="#5C743D"><table width="190" border="0" align="right" cellpadding="0" cellspacing="0" id="leftcol2">
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
<script language="JavaScript" type="text/javascript">
var wooYayIntervalId = 0;
var counter = 0;
function callMySelf(){		 		
//alert('test');
if(document.getElementById('resultImage')){							  
clearInterval(wooYayIntervalId);	
window.scrollTo(0, 1000);			
}
else{
ajaxLoader("read_directory3.php?number=<? echo $randomNumber; ?>", 'result_pad');
counter++;
}
}
wooYayIntervalId = setInterval("callMySelf()",10000);		  
</script>
            
</body>
</html>
