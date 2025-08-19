<?
include "menu.php";
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$cx = $_POST["cx"];$cy = $_POST["cy"];$dx = $_POST["dx"];$dy = $_POST["dy"];$sx = $_POST["sx"];$pnum = $_POST["pnum"];$testingPoints = $_POST["testingPoints"];
	$string = "Cx\tCy\tdx\tdy\tsx\tpnum\n$cx\t$cy\t$dx\t$dy\t$sx\t$pnum\n\nWx\tWy\tWz\tIx\tIy\n";
	$testingPoints = str_replace(", ","\t",$testingPoints);
	$f = fopen ("../quick_stereo/upload_stereo/tsaiCalib.txt", 'w');
	fputs ($f, $string.$testingPoints);
	fclose ($f); 	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Tsai calibration</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="mm_health_nutr.css" type="text/css" />
<script language="JavaScript" src="../js/javascript.js"></script>
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
    <td colspan="4" valign="top"><img src="mm_spacer.gif" alt="" width="305" height="1" border="0" />
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="pageName"><div align="center">Online Tsai Camera Calibration<br />
            <span class="subHeader">- Alex Li &amp; Minh Nguyen -</span></div></td>
		</tr>

		<tr>
          <td class="bodyText">
            <form id="form1" name="form1" method="post" action="">              
              <table width="100%" border="0" cellspacing="5" cellpadding="5">
                <tr>
                  <td width="30%"><div align="right">Please enter the following properties:</div></td>
                  <td width="70%"><div align="left">Please enter <span id="pointNo" class="subHeader">20</span> correspondence sets of points as followed (separated by ','): <br />
                      <strong>World X coordinate,	World Y coordinate,	<em>[World Z coordinate,]</em> Image X coordinate,	Image Y coordinate:</strong></div></td>
                </tr>
                <tr>
                  <td valign="top"><table width="100%" border="0" cellspacing="5" cellpadding="0">
                    <tr>
                      <td width="64%"><div align="right">Cx: </div></td>
                      <td width="36%">
                        <div align="right">
                          <input name="cx" type="text" id="cx" value="<? if($_POST["cx"]) echo $_POST["cx"]; else echo "1296"; ?>" />
                          </div></td>
                    </tr>
                    <tr>
                      <td><div align="right">Cy: </div></td>
                      <td>
                        <div align="right">
                          <input name="cy" type="text" id="cy" value="<? if($_POST["cy"]) echo $_POST["cy"]; else echo "968"; ?>" />
                          </div></td>
                    </tr>
                    <tr>
                      <td><div align="right">dx: </div></td>
                      <td>
                        <div align="right">
                          <input name="dx" type="text" id="dx" value="<? if($_POST["dx"]) echo $_POST["dx"]; else echo "0.0014"; ?>" />
                          </div></td>
                    </tr>
                    <tr>
                      <td><div align="right">dy: </div></td>
                      <td>
                        <div align="right">
                          <input name="dy" type="text" id="dy" value="<? if($_POST["dy"]) echo $_POST["dy"]; else echo "0.0014"; ?>" />
                          </div></td>
                    </tr>
                    <tr>
                      <td><div align="right">sx: </div></td>
                      <td>
                        <div align="right">
                          <input name="sx" type="text" id="sx" value="<? if($_POST["sx"]) echo $_POST["sx"]; else echo "1"; ?>" />
                          </div></td>
                    </tr>
                    <tr>
                      <td><div align="right">pnum: </div></td>
                      <td>
                        <div align="right">
                          <input name="pnum" type="text" id="pnum" value="<? if($_POST["pnum"]) echo $_POST["pnum"]; else echo "40"; ?>" onchange="
                          document.getElementById('pointNo').innerHTML = this.value;
                          " />
                          </div></td>
                    </tr>
                  </table></td>
                  <td><div align="left">
                    <textarea name="testingPoints" cols="70" rows="10" id="testingPoints"><? if($_POST["testingPoints"]) echo $_POST["testingPoints"]; else echo "23, 0, 26.8, 1490, 1644
72, 0, 26.2, 1624, 1525
121, 0, 26.5, 1747, 1420
170.5, 0, 26.3, 1857, 1326
23, 0, 75.8, 1491, 1460
72, 0, 75.2, 1630, 1347
121, 0, 75.5, 1758, 1249
170.5, 0, 75.3, 1874, 1162
23, 0, 124.8, 1491, 1259
72, 0, 124.2, 1637, 1155
121, 0, 124.5, 1767, 1064
170.5, 0, 124.3, 1890, 984
23, 0, 173.8, 1491, 1041
72, 0, 173.2, 1643, 946
121, 0, 173.5, 1779, 862
170.5, 0, 173.3, 1909, 791
23, 0, 222.8, 1491, 800
72, 0, 222.2, 1650, 715
121, 0, 222.5, 1795, 643
170.5, 0, 222.3, 1929, 579
0, 16, 26.3, 1355, 1672
0, 65, 26.5, 1186, 1570
0, 114.3, 26.8, 1038, 1478
0, 163.3, 26, 895, 1400
0, 16, 75.3, 1348, 1486
0, 65, 75.5, 1175, 1390
0, 114.3, 75.8, 1019, 1305
0, 163.3, 75, 870, 1235
0, 16, 124.3, 1341, 1283
0, 65, 124.5, 1162, 1195
0, 114.3, 124.8, 1001, 1118
0, 163.3, 124, 844, 1056
0, 16, 173.3, 1333, 1063
0, 65, 173.5, 1146, 985
0, 114.3, 173.8, 979, 917
0, 163.3, 173, 817, 861
0, 16, 222.3, 1322, 822
0, 65, 222.5, 1128, 755
0, 114.3, 222.8, 953, 697
0, 163.3, 222, 789, 648"; ?></textarea>
                  </div></td>
                </tr>
              </table>
              <p align="center">
                <input type="submit" name="submitButton" id="submitButton" value="Run TSai Camera Calibration Now" />
              </p>
            </form>          </td>
        </tr>
		<tr>
		  <td class="bodyText"><div align="center">		   
		    <?
			if($_SERVER['REQUEST_METHOD'] == "POST"){
			unlink("../quick_stereo/upload_stereo/tsaiCalibResult.txt");
			?>
            <p id="result_pad"><img class="intermediatedResult" src="../images/waiting.gif"></p>
            <script language="JavaScript" type="text/javascript">
			var wooYayIntervalId = 0; var counting = 0;
			document.getElementById('submitButton').disabled='disabled';
			scroll(0,500);
			function callMySelf(){					
				if(document.getElementById('tsaiCalibResult')){									
					clearInterval(wooYayIntervalId);						
					wooYayIntervalId = null;
					document.getElementById('submitButton').disabled='';					
				}
				else
				{
					ajaxLoader("checkTsaiCalibResultReady.php", "result_pad");							
				}						
				counting++;			
			}
			wooYayIntervalId = setInterval("callMySelf()",5000);
            </script>
            <? } else { ?>			
            <p><img src="tsai.jpg" width="640" height="480"/></p>
            <? } ?>
		  </div></td>
	    </tr>
      </table>
	
  	<img src="mm_spacer.gif" alt="" width="50" height="1" border="0" /><br />
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
