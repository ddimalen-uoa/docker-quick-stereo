<?
include "menu.php";

$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

$sceneArray = array();
$numberSampleArray = array();
$totalAll = 0;
$file = "scene_recognition/indoor_outdoor_small_list.txt";
$f = fopen($file, "r");
$oldLine = "";
$i = 0;
while ( $line = fgets($f, 1000) ) {
	$line = substr($line, 0, strpos($line,"_"));
	if($oldLine != $line){
		$sceneArray[$i] = $line;
		$i++;
	}
	$numberSampleArray[$line]++;
	$oldLine = $line;
	$totalAll++;
}
/*
if($ipaddress =='130.216.37.27'){
	foreach ($numberSampleArray as &$value) {
		  if($value>0) $value = 100* ($value / $totalAll);
	  }
	
	print_r($numberSampleArray);// return;
}
*/
/*
print_r($sceneArray);
print "<br>";
print_r($numberSampleArray);// return;
print "<br>";
print $totalAll;
print "<br>";
*/
//print $i; return;
if($_SERVER['REQUEST_METHOD'] == "POST"){
	if (!($_FILES["file1"]["type"] == "image/jpeg" || $_FILES["file1"]["type"] == "image/png") || $_FILES["file1"]["error"] > 0)
	{
	  	//echo "Error: " . $_FILES["file1"]["error"] . "<br />";
	  	$errorOrNot = true;
	}
	else
	{
	  	move_uploaded_file($_FILES["file1"]["tmp_name"],"images/test.jpg");	  
	  	$fp = fopen('images/startRecoginisedSmall.txt', 'w');
		fclose($fp);	
		unlink('images/test.txt');
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
          <td class="pageName"><div align="center">Intelligent indoor and outdoor scene recognition</div></td>
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
    <strong><? echo sizeof($sceneArray); ?> Indoor/Outdoor Scene Categories Dataset are used to recognised the uploaded images:</strong>
    <?
	$myFile = "images/test.txt";
	$fh = fopen($myFile, 'r');
	$recognized = fread($fh, 50);	
	//echo strpos($recognized, 'coast');
	fclose($fh);	
	
	for($i = 0; $i < sizeof($sceneArray); $i++){
		$sceneArrayPercentage[$sceneArray[$i]] = 0;
	} 
	
	$myFile = "images/testPercentage.txt";
	$fh = fopen($myFile, 'r');
	$recognizedPercentage = fread($fh, 10000);	
	$recognizedPercentageArray = explode(" ",trim($recognizedPercentage));
	$totalPercentage = sizeof($recognizedPercentageArray);
	//echo $totalPercentage; return;
	for($i = 0; $i < $totalPercentage; $i++){		
		//echo $recognizedPercentageArray[$i]; return;
		$sceneArrayPercentage[substr($recognizedPercentageArray[$i], 0, strpos($recognizedPercentageArray[$i],"_"))]++;
	} 
	
	//echo $recognizedPercentage;
	fclose($fh);
	
	//for($i = 0; $i < sizeof($sceneArray); $i++){
	//	echo $sceneArray[$i]."=".$sceneArrayPercentage[$sceneArray[$i]]."<br>";
	//} 
	
	//return;
	?>
    <ul style="text-align:left; width:60%; padding-left:20%">
    	<?
        $indoor = 0; $outdoor = 0;
		for($i = 0; $i < sizeof($sceneArray); $i++){
			// = 100 * 10/20
			
			$calculatedPercentage = 10*($sceneArrayPercentage[$sceneArray[$i]]/$totalPercentage)*($totalAll/$numberSampleArray[$sceneArray[$i]]);
			
			if($calculatedPercentage > 10){
				if($sceneArray[$i] ==  "bar" || $sceneArray[$i] ==  "bedroom" || $sceneArray[$i] ==  "bookstore" || $sceneArray[$i] ==  "classroom" || $sceneArray[$i] ==  "corridor" || $sceneArray[$i] ==  "doorway" || $sceneArray[$i] ==  "library" || $sceneArray[$i] ==  "nightclub" || $sceneArray[$i] ==  "store")
					$indoor++;
				else
					$outdoor++;	
			}
		?>
        	<li <? 
			if($calculatedPercentage > 20)  echo 'class="highlight"'; 
			else if($calculatedPercentage > 10)  echo 'class="highlightSmall"';
			else if($calculatedPercentage >= 0)  echo 'class="highlightVerySmall"'; 
			?> style="width:<? echo min(100, 25+1.5*($calculatedPercentage)); ?>%"><? echo $sceneArray[$i]; ?> (<? echo (int)$calculatedPercentage; ?> scores)</li>
        <? } ?>      
    </ul>
    is recognised on the below image and this image is <h3><? if($indoor > $outdoor) echo "INDOOR"; else echo "OUTDOOR" ?></h3><br />
    <center>
    <table border="1" width="700">
      <tr>
        <td width="50%">Original image</td>
        <td width="50%">Segmented regions (max 3-4 regions)</td>
      </tr>
      <tr>
        <td><img src="images/test.jpg?<? echo rand(); ?>" width="100%" border="2" /></td>
        <td><img src="images/segmentedTex.jpg?<? echo rand(); ?>" width="100%" border="2" /></td>
      </tr>
    </table>
    </center>

    <br /><img src="mm_spacer.gif" alt="" width="50" height="1" border="0" /><br />
    &nbsp;<br />
    <!--
    Is this correct? If it is not, please tell us which scene is it?<br />
    <input type="button" name="button" id="button" value="Yes it's correct" onclick="alert('Thanks for your answer.');"/>
    <input type="button" name="button" id="button" value="No it's not, it is" onclick="alert('Thanks, the database will be updated with your information after verification.');"/>
    <select name="select" id="select">      
      <option value="tall buildings">tall buildings</option>
      <option value="inside city">inside city</option>
      <option value="street">street</option>
      <option value="highway">highway</option>
      <option value="coast">coast</option>
      <option value="open country">open country</option>
      <option value="mountain">mountain</option>
      <option value="forest">forest</option>
    </select>
    -->
<? } else { ?>
    <div id="result_pad" ><img src="http://www.ivs.auckland.ac.nz/images/waiting.gif" width="200" height="200" /> <br />
Loading please wait...
</div>
<script language="JavaScript" type="text/javascript">
var wooYayIntervalId = 0; var counting = 0;
function callMySelf(){
	if(document.getElementById('done')) {
		document.location='<? echo $_SERVER['PHP_SELF']; ?>';
	}	
	ajaxLoader("read_recognise.php", "result_pad");	
}
wooYayIntervalId = setInterval("callMySelf()",1000);

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
