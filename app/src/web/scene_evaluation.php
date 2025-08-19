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
          <td class="pageName"><div align="center">Please help me select the best stereo matching algorithm. <br />
          <a href="scene_evaluation_v2.php">Click here for version 2 of this page, easier to work with.</a> <br/>
Current page: <? echo ($from/10); ?></div></td>
		</tr>

		<tr>
          <td class="bodyText"><center><p>Page: 
          <?
		  for($i = 0; $i < sizeof($arrayMPO); $i+=10){
		  ?>
          <a href="<? echo $_SERVER['PHP_SELF']."?from=".$i; ?>"><? echo ($i/10); ?></a>&nbsp;
          <? } ?>
          </p></center>
            <span class="Scene_Gallery">
            <ul>
			<?			
            $counter = 0;
            foreach($arrayMPO as $dir) {
                $directory = str_replace(".MPO","", $dir);			
                //if($counter >= 10) break;
				if(file_exists($dir) && $counter >= $from && $counter < ($from+10)){
                    ?>
                    <table width="95%" border="1">                      
                      <tr>
                        <td width="25%">Left referenced image</td>
                        <td width="25%">Block Matching algorithm</td>
                        <td width="25%">Belief Propogation algorithm (1D)</td>
                        <td width="25%">Graph Cut algorithm</td>
                      </tr>
                      <tr bgcolor="#000000">
                      <?
					  $sql = "select * FROM `cs_stereo_matching_evaluation` where image = '$directory'";
					  //echo $sql;
					  $result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
					  if($row = mysql_fetch_assoc($result)){
					  	 $algorithm = $row["algorithm"];
					  }
					  else{
						 $algorithm = ''; 
					  }
					  ?>
                        <td><img src="<? echo $directory."_l.jpg"; ?>" width="100%" /></td>
                        <td <? if($algorithm == 'BM') echo 'style="background:#F00"'; ?> onclick="if(this.style.background=='') {
                        	this.style.background='#F00'; ajaxLoader('addAlgorithm.php?add=add&dir=<? echo $directory; ?>&alg=BM','');// alert('add BM at <? echo $directory; ?>');
                        }
                        else {
                        	this.style.background=''; ajaxLoader('addAlgorithm.php?add=del&dir=<? echo $directory; ?>&alg=BM','');// alert('clear BM at <? echo $directory; ?>');
                        }"><img src="<? if(file_exists($directory."_BM.jpg")) echo $directory."_BM.jpg"; else echo "../stereogallery/commingsoon.jpg"; ?>" width="100%" /></td>
                        
                        
                        <td <? if($algorithm == 'CBP') echo 'style="background:#F00"'; ?> onclick="if(this.style.background=='') {
                        	this.style.background='#F00'; ajaxLoader('addAlgorithm.php?add=add&dir=<? echo $directory; ?>&alg=CBP','');// alert('add CBP at <? echo $directory; ?>');
                        }
                        else {
                        	this.style.background=''; ajaxLoader('addAlgorithm.php?add=del&dir=<? echo $directory; ?>&alg=CBP','');// alert('clear CBP at <? echo $directory; ?>');
                        }"><img src="<? if(file_exists($directory."_CBP.jpg")) echo $directory."_CBP.jpg"; else echo "../stereogallery/commingsoon.jpg"; ?>" width="100%" /></td>
                        
                        
                        <td <? if($algorithm == 'GC') echo 'style="background:#F00"'; ?> onclick="if(this.style.background=='') {
                        	this.style.background='#F00'; ajaxLoader('addAlgorithm.php?add=add&dir=<? echo $directory; ?>&alg=GC','');// alert('add GC at <? echo $directory; ?>');
                        }
                        else {
                        	this.style.background=''; ajaxLoader('addAlgorithm.php?add=del&dir=<? echo $directory; ?>&alg=GC','');// alert('clear GC at <? echo $directory; ?>');
                        }"><img src="<? if(file_exists($directory."_GC.jpg")) echo $directory."_GC.jpg"; else echo "../stereogallery/commingsoon.jpg"; ?>" width="100%" /></td>
                      </tr>
                    </table>
    
            <?
			}
			$counter++;
		}
		?>             
          	</ul>
            </span>
            <center><p>Page: 
          <?
		  for($i = 0; $i < sizeof($arrayMPO); $i+=10){
		  ?>
          <a href="<? echo $_SERVER['PHP_SELF']."?from=".$i; ?>"><? echo ($i/10); ?></a>&nbsp;
          <? } ?>
          </p></center>
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
