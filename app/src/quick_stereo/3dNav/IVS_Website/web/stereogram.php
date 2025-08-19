<?
include '../quick_stereo/dbc.php';
include "menu.php";
$directory = $_GET["d"];
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$fp = fopen('shadow/stereogram.txt', 'w');
	fwrite($fp, "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/web/shadow/".$_POST["depthMap"]);
	fwrite($fp, "\n");
	fwrite($fp, "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/web/shadow/".$_POST["patternMap"]);
	fwrite($fp, "\n");
	fclose($fp);
}
else if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["location"])){
	$sql = "select * from pattern order by rand() limit 1";
    $result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	$row = mysql_fetch_assoc($result);
	
	$fp = fopen('shadow/stereogram.txt', 'w');
	fwrite($fp, "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/quick_stereo/upload_stereo/".$_GET["location"]."/result.jpg");
	fwrite($fp, "\n");
	fwrite($fp, "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/web/shadow/".$row["url"]);
	fwrite($fp, "\n");
	fclose($fp);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Stereogram page</title>
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
    <td colspan="4" valign="top"><img src="mm_spacer.gif" alt="" width="305" height="1" border="0" /><br />
	&nbsp;<br />
	&nbsp;<br />
    <form id="form1" name="form1" method="post" action="#result">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="pageName"><div align="center">Stereogram reconstruction page (example page)</div></td>
		</tr>
		<?
		if(!($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["location"]))){
		?>
		<tr>
          <td class="bodyText"><p align="center" class="inputType">Please choose to <a href="../quick_stereo/index.php">process from our Gallery</a> or  from some sample belows:</p>
          <?
		  $sql = "select * from depthmap order by rand() limit 5";
		  $result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
		  ?>
          <table width="100%" border="1">
            <tr>
              <td width="20%" valign="bottom"><div align="center"><img src="shadow/<? if($row = mysql_fetch_assoc($result)) echo $row["url"]; ?>" width="100%" /><br />
                  <input name="depthMap" type="radio" id="radio" value="<? echo $row["url"]; ?>" checked="checked" />
              </div></td>
              <td width="20%" valign="bottom"><div align="center"><img src="shadow/<? if($row = mysql_fetch_assoc($result)) echo $row["url"]; ?>" width="100%" /><br />
                <input type="radio" name="depthMap" id="radio" value="<? echo $row["url"]; ?>" /></div></td>
              <td width="20%" valign="bottom"><div align="center"><img src="shadow/<? if($row = mysql_fetch_assoc($result)) echo $row["url"]; ?>" width="100%" /><br />
                <input type="radio" name="depthMap" id="radio" value="<? echo $row["url"]; ?>" /></div></td>
              <td width="20%" valign="bottom"><div align="center"><img src="shadow/<? if($row = mysql_fetch_assoc($result)) echo $row["url"]; ?>" width="100%" /><br />
                <input type="radio" name="depthMap" id="radio" value="<? echo $row["url"]; ?>" /></div></td>
              <td width="20%" valign="bottom"><div align="center"><img src="shadow/<? if($row = mysql_fetch_assoc($result)) echo $row["url"]; ?>" width="100%" /><br />
                <input type="radio" name="depthMap" id="radio" value="<? echo $row["url"]; ?>" /></div></td>
            </tr>
          </table>          
          <p>Please choose from the following pattern:</p>
          <?
		  $sql = "select * from pattern order by rand() limit 5";
		  $result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
		  ?>
          <table width="100%" border="1">
            <tr>
              <td width="20%" valign="bottom"><div align="center"><img src="shadow/<? if($row = mysql_fetch_assoc($result)) echo $row["url"]; ?>" width="128" /><br />
                  <input name="patternMap" type="radio" id="radio" value="<? echo $row["url"]; ?>" checked="checked" />
              </div></td>
              <td width="20%" valign="bottom"><div align="center"><img src="shadow/<? if($row = mysql_fetch_assoc($result)) echo $row["url"]; ?>" width="128" /><br />
                <input type="radio" name="patternMap" id="radio" value="<? echo $row["url"]; ?>" /></div></td>
              <td width="20%" valign="bottom"><div align="center"><img src="shadow/<? if($row = mysql_fetch_assoc($result)) echo $row["url"]; ?>" width="128" /><br />
                <input type="radio" name="patternMap" id="radio" value="<? echo $row["url"]; ?>" /></div></td>
              <td width="20%" valign="bottom"><div align="center"><img src="shadow/<? if($row = mysql_fetch_assoc($result)) echo $row["url"]; ?>" width="128" /><br />
                <input type="radio" name="patternMap" id="radio" value="<? echo $row["url"]; ?>" /></div></td>
              <td width="20%" valign="bottom"><div align="center"><img src="shadow/<? if($row = mysql_fetch_assoc($result)) echo $row["url"]; ?>" width="128" /><br />
                <input type="radio" name="patternMap" id="radio" value="<? echo $row["url"]; ?>" /></div></td>
            </tr>
          </table>
          <p></p>
          
            <div align="center">
              <p><a name="result" id="result"></a>
                  <input type="submit" name="button" id="button" value="Generate stereogram now" />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" name="button2" id="button2" value="More images" onclick="location='stereogram.php';"/>
              </p>
              <p>&nbsp;  </p>
            </div>
          </td>
        </tr>
        <? } ?>
        <?
		if($_SERVER['REQUEST_METHOD'] == "POST" || ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["location"]))){
		$randomNumber = rand(1,999999);
		?>
		<tr>
		  <td class="bodyText"><div align="center" id="result_pad">
		    <p>&nbsp;</p>
		    <p class="quote">Stereogram is generating, please wait!!!</p>
		    <p><img src="../images/waiting.gif" /></p>
		  </div>
          <script language="JavaScript" type="text/javascript">
        var wooYayIntervalId = 0;
		function callMySelf(){
			ajaxLoader("read_directory.php?number=<? echo $randomNumber; ?>", "result_pad");
			if(document.getElementById('resultImage')){
				/*
				if(confirm('Would you mind if your results are viewable by public?')){
					document.location='share3d.php';
				}
				else{
					if(confirm('Would you mind if we use your results for research purpose only?')){
						
					}
				}
				*/
				clearInterval(wooYayIntervalId);
			}
		}
        wooYayIntervalId = setInterval("callMySelf()",5000);
            </script>
            </td>
	    </tr>
        <? } ?>
      </table></form>
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
