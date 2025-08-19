<?
include "../quick_stereo/dbc.php";
include "menu.php";

$from = intval($_GET["from"]);

$arrayMPO = array();//print_r($arrayMPO);

if($directory==''){
	$directory = '../stereogallery';
}

foreach(glob($directory.'/*') as $dir) { 		
	  addString($dir);	 
	  foreach(glob($dir.'/*') as $dir2) {		    		
			addString($dir2);			
			foreach(glob($dir2.'/*') as $dir3) { 				
				addString($dir3);					
			}
	  }	  
}
function addString($string){
	GLOBAL $arrayMPO;
	if (strpos($string, '.MPO') !== false) {
		//found MPO
		array_push($arrayMPO, $string);
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Stereo Scene Gallery</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="mm_health_nutr.css" type="text/css" />
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
    <td colspan="4" valign="top"><img src="mm_spacer.gif" alt="" width="305" height="1" border="0" /><br />
	&nbsp;<br />
	&nbsp;<br />
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td bgcolor="#FFFFCC" class="pageName"><div align="center">Please help me select the best stereo matching algorithm. <br /> 
              Please <strong style=" color:#FF0000">choose one only</strong> for each line.<br />
              Refresh or press F5 when you finish this page </div></td>
		</tr>

		<tr>
          <td class="bodyText"><center><p><a href="scene_gallery.php">Back to home directory</a></p>
          </center>
            <span class="Scene_Gallery">
            <ul>
			<?			
            $counter = 0;
            foreach($arrayMPO as $dir) {
                $directory = str_replace(".MPO","", $dir);			
                //if($counter >= 10) break;
				if($counter < 100){
                    ?>
                    
                      <?
					  $sql = "select * FROM `cs_stereo_rectification_evaluation` where image = '$directory'";
					  //echo $sql; return;
					  $result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
					  if($row = mysql_fetch_assoc($result)){
					  	 $rectification_quality = $row["rectification_quality"];
					  }
					  else{
						 $rectification_quality = ''; 
					  }
					  if(mysql_num_rows($result) < 1){	
					  		$sql = "INSERT INTO `cs_ivs`.`cs_stereo_rectification_evaluation` (
								`image` ,
								`rectification_quality` 								
								)
								VALUES (
								'$directory', 'pass'
								)
								ON DUPLICATE KEY UPDATE `image` = '$directory'
								";
						$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());			  
					  ?>
                      <li>
                      <table width="100%" border="1">                      
                      <tr>
                        <td>cross eyed</td>
                        </tr>
                      <tr bgcolor="#000000">
                        <td onclick="this.style.background='#F00'; ajaxLoader('failRect.php?dir=<? echo $directory; ?>','');">
                        <img src="<? if(file_exists($directory."_crossEye.jpg")) echo $directory."_crossEye.jpg"; else echo "../stereogallery/commingsoon.jpg"; ?>" width="100%" />
                        </td>
                        </tr>
                    </table>
                    </li>
                        <?
							
						$counter++;
					  }
					  ?>
                      
    
            <?
			}
			//$counter++;
		}
		?>
            </ul>
            </span>            
            <center>
            <?
			if($counter==0) echo "<h3>Best algorithms have been selected on all images already, thank you for your help!</center>";
			else{
			?>
            <p><a href="<? echo $_SERVER['PHP_SELF']; ?>">Go to next page or press F5</a></p></center>
            <? } ?>
            </td>
        </tr>        
      </table>
	 <br />
	&nbsp;<br />	<img src="mm_spacer.gif" alt="" width="50" height="1" border="0" /><br />
    &nbsp;<br /></td>
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
</body>
</html>
