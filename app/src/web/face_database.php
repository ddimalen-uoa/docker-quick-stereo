<?
header('Location: http://www.ivs.auckland.ac.nz/quick_stereo/index.php');
include "menu.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Minh Nguyen s new project</title>
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
        <td class="pageName">Overall project idea</td>
	    </tr>
	  <tr>
        <td class="bodyText"><p>1, Using a number of Image processing techniques to detect human faces' emotions</p>
            <ol>
              <li>Dataset are collected at <a href="http://www.cs.auckland.ac.nz">The University of Auckland - Computer Science department</a>.</li>
            </ol>
          <p>2, Steps, <a href="face_database.php#database">click here</a> to view examples and data of the steps on real images:</p>
          <ol>
              <li>Detect and crop faces from a collection of photos</li>
            <li>Align facial images by horizontalise the eyes
              <ol>
                    <li>Detect left eye</li>
                <li>Detect right eye</li>
                <li>Forming angle </li>
                <li>Rotate full image</li>
              </ol>
            </li>
            <li>Normalise image throughout a dataset
              <ol>
                    <li>Position of left eye and right eye stay at the same coordinates</li>
                <li>Others to be scaled correspondingly to be 100x100 pixels</li>
              </ol>
            </li>
          </ol></td>
	    </tr>
        <tr>
          <td class="pageName">&nbsp;</td>
        </tr>
        <tr>
          <td class="pageName"><div align="left">Face Database<a name="database"></a></div></td>
		</tr>

		<tr>
          <td class="bodyText"><p>The followings are the database collected and processed for the project. Click on the link to see the detected results.</p>
            <!--
			<?
			// open this directory 
			$myDirectory = opendir("773_related");			
			// get each entry
			while($entryName = readdir($myDirectory)) {
				$dirArray[] = $entryName;
			}			
			// close directory
			closedir($myDirectory);			
			$indexCount	= count($dirArray);			
			// sort 'em
			sort($dirArray);
			
			?>
            -->
            <ul>
            <?			
			for($index=0; $index < $indexCount; $index++) {
				if (substr("$dirArray[$index]", 0, 1) != "."){ // don't list hidden files
					?>                    	
                    <li><a href="show_face.php?d=<? echo $dirArray[$index]; ?>"><? echo $dirArray[$index]; ?> - click here to view</a></li>
                    <?
				}
			}		
			?>              
          </ul>            
          <p>&nbsp;</p>		</td>
        </tr>
		<tr>
		  <td class="bodyText">&nbsp;</td>
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
