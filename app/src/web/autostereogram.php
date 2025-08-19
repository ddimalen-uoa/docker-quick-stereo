<?
header('Location: http://www.ivs.auckland.ac.nz/quick_stereo/index.php');
include '../quick_stereo/dbc.php';
include "menu.php";
$directory = $_GET["d"];
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["postDepthImage"]) && isset($_POST["postTextureImage"])){
	if (file_exists("./".$_POST["postDepthImage"]) && file_exists("./".$_POST["postTextureImage"])) 
	{
		$fp = fopen('shadow/stereogram3.txt', 'w');
		fwrite($fp, "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/web/".$_POST["postDepthImage"]);
		fwrite($fp, "\n");
		fwrite($fp, "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/web/".$_POST["postTextureImage"]);
		fwrite($fp, "\n");
		fwrite($fp, "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/web/".$_POST["postMaskImage"]);
		fwrite($fp, "\n");
		fwrite($fp, intval(2-$_POST["stereoType"]));
		fwrite($fp, "\n");
		fwrite($fp, intval(2-$_POST["edges"]));
		fwrite($fp, "\n");
		fclose($fp);
	}	
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
var sampleStereograms=new Array();
<?
$dirSample    = './shadow/stereograms/';
$filesSample = scandir($dirSample);
shuffle($filesSample);

//print_r($filesSample1);
$randSelect = (rand()%(sizeof($filesSample)-2));
$countering = 0;
//echo sizeof($filesSample); return;
for($i = 0; $i < sizeof($filesSample); $i++){
	if($filesSample[$i]!="." && $filesSample[$i]!=".."){
		?>
		sampleStereograms.push("<? echo $dirSample.$filesSample[$i]; ?>");
		<? 
		if($countering==$randSelect){
			$sampleStereogramHere=$dirSample.$filesSample[$i];
		}
		$countering++;
	} ?>
<? } 
//$randSelect-=2;
?>  
var totalSample = <? echo (sizeof($filesSample)-2); ?>;	
var currentSample = <? echo $randSelect; ?>;
//alert(currentSample);
function nextStereogram(){
	if(currentSample>=totalSample- 1) currentSample = 0;
	else currentSample = currentSample +1;
	document.getElementById('sampleStereogram').src = sampleStereograms[currentSample];
	document.getElementById('currentItem').innerHTML = currentSample+1;
	
}

function lastStereogram(){
	if(currentSample<=0) currentSample = totalSample - 1;
	else currentSample = currentSample -1;
	document.getElementById('sampleStereogram').src = sampleStereograms[currentSample];
	document.getElementById('currentItem').innerHTML = currentSample+1;
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
    <form id="form1" name="form1" method="post" action="#bottom">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="pageName"><div align="center">Auto-Stereogram creator - beta version</div></td>
		</tr>
		<?
		if(!($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["location"]))){
		?>
		<tr>
          <td class="bodyText"><p align="center">Auto-stereogram is a flat image designed to create the visual illusion of a 3D scene, it is created from at least one depth image and one texture image, please choose below:<br />
            To get more depth and texture images please visit <a href="http://www.ivs.auckland.ac.nz/quick_stereo/gallery_ivs.php">our gallery page</a>, to create your own depth image from a pair of photos, <a href="http://www.ivs.auckland.ac.nz/quick_stereo">click here</a>. </p>
            <table width="100%" border="1" bgcolor="#000000">
              <tr>
                <td><strong>Depth image</strong></td>
                <td>&nbsp;</td>
                <td><strong>Texture image</strong></td>
                <td>&nbsp;</td>
                <td><strong>Mask image</strong></td>
              </tr>
              <tr>
                <td width="30%"><img id="imageDepth" src="<? if(isset($_POST["postDepthImage"])) echo $_POST["postDepthImage"]."?".rand(); else echo "images/disp1.png?".rand(); ?>"  width="100%" /></td>
                <td><h1>+</h1></td>
                <td width="30%"><img id="imageTexture" src="<? if(isset($_POST["postTextureImage"])) echo $_POST["postTextureImage"]."?".rand(); else echo "images/view1.png?".rand(); ?>"  width="100%"/></td>
                <td><h1>+</h1></td>
                <td width="30%"><img id="imageMask" src="<? if(isset($_POST["postMaskImage"])) echo $_POST["postMaskImage"]."?".rand(); else echo "images/mask/brokenGlassSmall.png?".rand(); ?>"  width="100%"/></td>
              </tr>
              <tr>
                <td>
                <label for="depthCollection"></label>
                  <select name="depthCollection" id="depthCollection" onchange="                  
                  if(this.value != '' && this.value !='Browse...' && this.value.indexOf('randomDepths') == -1) {
                  	document.getElementById('imageDepth').src='images/stereograms/'+this.value+'/disp1.png';
                    document.getElementById('postDepthImage').value='images/stereograms/'+this.value+'/disp1.png';
                    document.getElementById('imageTexture').src='images/stereograms/'+this.value+'/view1.png';
                    document.getElementById('postTextureImage').value='images/stereograms/'+this.value+'/view1.png';
                  }
                  else if(this.value == 'Browse...') {
                   	window.open( 'upload.php?upload=disp', 'pop up window upload', 'status = 0, height = 200, width = 600, resizable = 0' );
                  }  
                  else{
                    document.getElementById('imageDepth').src=this.value;
                    //alert('Here');
                    document.getElementById('postDepthImage').value=this.value;
                  }              
                  ">
                    <option>Select a depth image...</option>
                    <option value="Browse..." onclick="window.open( 'upload.php?upload=disp', 'pop up window upload', 'status = 0, height = 200, width = 600, resizable = 0' );">Browse...</option>
                    <option value="images/randomDepths/<? echo rand()%10; ?>.jpg">Random depths</option>
                    <?
					$dirSample1    = './images/stereograms/';
					$filesSample1 = scandir($dirSample1);
					//print_r($filesSample1);
					for($i = 0; $i < sizeof($filesSample1); $i++){
						if($filesSample1[$i]!="." && $filesSample1[$i]!=".."){
						?>
						<option value="<? echo $filesSample1[$i]; ?>" <? if(strstr($_POST["postDepthImage"],$filesSample1[$i])) echo "selected"; ?>><? echo $filesSample1[$i]; ?></option>
                        <? } ?>
                    <? } ?>                    
                  </select> 
                  or 
                  <input type="button" name="browse1" id="browse1" value="Browse a file" onclick="window.open( 'upload.php?upload=disp', 'pop up window upload', 'status = 0, height = 200, width = 600, resizable = 0' );" />
<input type="hidden" name="postDepthImage" id="postDepthImage" value="<? if($_POST["postDepthImage"] != '') echo $_POST["postDepthImage"]; else echo 'images/disp1.png';?>" /></td>
                <td>&nbsp;</td>
                <td><select name="textureCollection" id="textureCollection"  onchange="
                 if(this.value != '' && this.value !='Browse...') {                  	
                    document.getElementById('imageTexture').src=this.value;
                    document.getElementById('postTextureImage').value=this.value;
                  }
                 
                ">
                  <option>Select a texture image...</option>  
                  <option value="images/randomDots/<? echo rand()%10; ?>.jpg">Random dots</option>
                </select>
                 or <input type="button" name="browse2" id="browse2" value="Browse a file" onclick="window.open( 'upload.php?upload=view', 'pop up window upload', 'status = 0, height = 200, width = 600, resizable = 0' );" />
                 <input type="hidden" name="postTextureImage" id="postTextureImage" value="<? if($_POST["postTextureImage"] != '') echo $_POST["postTextureImage"]; else echo 'images/view1.png';?>" /></td>
                <td>&nbsp;</td>
                <td><select name="maskCollection" id="maskCollection" onchange="
                 if(this.value != '' && this.value !='Browse...') {                  	
                    document.getElementById('imageMask').src=this.value;
                    document.getElementById('postMaskImage').value=this.value;
                  }
                  else if(this.value == 'Browse...') {
                	window.open( 'upload.php?upload=view', 'pop up window upload', 'status = 0, height = 200, width = 600, resizable = 0' );
                }
                ">
                  <option>Select a mask image...</option>
                  <option value="images/mask/brokenGlassSmall.png">Broken glass</option>
                  <option value="images/mask/web1.jpg">Spider web</option>
                  <option value="images/mask/rainDrop.jpg">Rain Drops</option>
                  <option value="images/mask/green-fireworks-at-night.jpg">Fireworks</option>
                  <option value="images/mask/starryNight.png">Starry night</option>
                  <option value="images/mask/glass.jpg">Glass</option>
                  <!--<option value="Browse...">Browse...</option> -->
                </select>
                <input type="hidden" name="postMaskImage" id="postMaskImage" value="<? if($_POST["postMaskImage"] != '') echo $_POST["postMaskImage"]; else echo 'images/mask/brokenGlassSmall.png';?>" />                
                </td>
              </tr>
          </table>
            <div align="center">
              <p><a name="result" id="result"></a>
                  Auto-Stereogram type: 
                    <select name="stereoType" id="stereoType">
                      <option value="1" <? if($_POST["stereoType"]==1) echo "selected"; ?> >Cross-eyed</option>
                      <option value="2" <? if($_POST["stereoType"]==2) echo "selected"; ?> >Parallel-eyed</option>
                    </select> 
                    <select name="edges" id="edges">
                      <option value="1" <? if($_POST["edges"]==1) echo "selected"; ?> >with</option>
                      <option value="2" <? if($_POST["edges"]==2) echo "selected"; ?> >without</option>
                    </select>
                  edges 
                    <input type="submit" name="button" id="button" value="Generate now" />
  </p>              
        </div>
          </td>
        </tr>
        <?
		if($_SERVER['REQUEST_METHOD'] != "POST"){
		?>
        <tr>
		  <td class="pageName"><div align="center">
		    <p>Public Gallery (image <span id="currentItem"><? echo ($randSelect+1); ?></span> of total <? echo (sizeof($filesSample)-2); ?>)</p>
		    <p>
            <table width="100%" border="0">
              <tr>
                <td width="5%"><img src="shadow/leftBut.png" width="100%" onmouseover="this.style.cursor='pointer';" onclick="lastStereogram();"/></td>
                <td width="90%"><img id="sampleStereogram" src="<? echo $sampleStereogramHere; ?>" height="500" onmouseover="this.style.cursor='pointer';"/></td>
                <td width="5%"><img src="shadow/rightBut.png" width="100%" onmouseover="this.style.cursor='pointer';" onclick="nextStereogram()";/></td>
              </tr>
            </table>
</p>
		  </div></td>
		</tr>
		<tr>
        <? } ?>
        <? } ?>
        
        <?
		if($_SERVER['REQUEST_METHOD'] == "POST" || ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["location"]))){
		$randomNumber = rand(1,999999);
		?>
		
		  <td class="bodyText">
          <div align="center" id="result_pad">
		    <p>&nbsp;</p>
		    <p class="quote">Stereogram is generating, please wait!!!</p>
		    <p><img src="../images/waiting.gif" /></p>
              </div>              
            </td>
	    </tr>
        <? } ?>
      </table></form>
	 
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
