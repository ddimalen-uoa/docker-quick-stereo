<?
include "menu.php";
if($_SERVER['REQUEST_METHOD'] == "POST"){
	if (!($_FILES["file1"]["type"] == "image/jpeg" || $_FILES["file1"]["type"] == "image/png") || $_FILES["file1"]["error"] > 0)
	{
	  	//echo "Error: " . $_FILES["file1"]["error"] . "<br />";
	  	$errorOrNot = true;
	}
	else
	{
	  	move_uploaded_file($_FILES["file1"]["tmp_name"],"images/test.jpg");	  
	  	$fp = fopen('images/startRecoginised.txt', 'w');
		fclose($fp);	
	}	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Intelligent scene recognition</title>
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
<style type="text/css">
.bodyText p {
	text-align: center;
}
.bodyText ul {
	text-align: center;
}
.highlight {
	font-weight: bold;
}
</style>
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
          <td class="pageName"><div align="center">Intelligent scene recognition</div></td>
		</tr>

		<tr>
          <td class="bodyText"><p>This work is based on Aude Oliva and Antonio Torralba's work on <a href="http://people.csail.mit.edu/torralba/code/spatialenvelope/">"Modeling the shape of the scene: a holistic representation of the spatial envelope"</a></p>
<p>Please upload an image of JPG, JPEG, or PNG format capturing one of the below categories and I will tell you the name of the scene:</p>		</td>
        </tr>
		<tr>
		  <td>
            <form action="" method="post" enctype="multipart/form-data">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td>      
              <input name="file1" type="file" id="file1" size="70" />
              <input name="number" type="hidden" id="number" value="<? echo $stringNumber; ?>">
              <input type="submit" name="submit" value="Upload" style="height:30px; width:100px;" />            
              </td>
            </tr>
                <tr>
                <td>Files allowed: jpg, jpeg, png</td>
                </tr>
            </table>
        </form>
        </td>
	    </tr>
      </table><br />

	<?
    if($_SERVER['REQUEST_METHOD'] != "POST"){
	?> 
    <strong>8 Outdoor Scene Categories Dataset are used to recognised the uploaded images:</strong>
    <?
	$myFile = "images/test.txt";
	$fh = fopen($myFile, 'r');
	$recognized = fread($fh, 50);	
	echo strpos($recognized, 'coast');
	fclose($fh);	
	?>
    <ul>
      <li <? if(strpos($recognized,'tall') !== false)  echo 'class="highlight"'; ?>>tall buildings</li>
      <li <? if(strpos($recognized,'city') !== false)  echo 'class="highlight"'; ?>> inside city</li>
      <li <? if(strpos($recognized,'street') !== false)  echo 'class="highlight"'; ?>> street</li>
      <li <? if(strpos($recognized,'highway') !== false)  echo 'class="highlight"'; ?>> highway</li>
      <li <? if(strpos($recognized,'coast') !== false)  echo 'class="highlight"'; ?>> coast</li>
      <li <? if(strpos($recognized,'country') !== false)  echo 'class="highlight"'; ?>> open country</li>
      <li <? if(strpos($recognized,'mountain') !== false)  echo 'class="highlight"'; ?>> mountain</li>
      <li <? if(strpos($recognized,'forest') !== false)  echo 'class="highlight"'; ?>> forest</li>
    </ul>
    is recognised on the below image:<br />
    <img src="images/test.jpg?<? echo rand(); ?>" width="50%" border="2" /><br /><img src="mm_spacer.gif" alt="" width="50" height="1" border="0" /><br />
    &nbsp;<br />
    Is this correct? If it is not, please tell us which scene is it?<br />
    <input type="button" name="button" id="button" value="Yes it's correct" onclick="alert('Thanks for your answer.');"/>
    <input type="button" name="button" id="button" value="No it's not, it is" onclick="alert('Thanks, the database will be updated with your information after verification.');"/>
    <select name="select" id="select">
      <!--<option value="">please choose one...</option>-->
      <option value="tall buildings">tall buildings</option>
      <option value="inside city">inside city</option>
      <option value="street">street</option>
      <option value="highway">highway</option>
      <option value="coast">coast</option>
      <option value="open country">open country</option>
      <option value="mountain">mountain</option>
      <option value="forest">forest</option>
    </select>
<? } else { ?>
    <div id="result_pad" ><img src="http://www.ivs.auckland.ac.nz/images/waiting.gif" width="200" height="200" /> <br />
Loading please wait...
</div>
<script language="JavaScript" type="text/javascript">
var wooYayIntervalId = 0; var counting = 0;
function callMySelf(){
	if(document.getElementById('done')) {
		document.location='scene_recognition.php';
	}	
	ajaxLoader("read_recognise.php", "result_pad");	
}
wooYayIntervalId = setInterval("callMySelf()",5000);

</script>
<? } ?>
    </td>
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
