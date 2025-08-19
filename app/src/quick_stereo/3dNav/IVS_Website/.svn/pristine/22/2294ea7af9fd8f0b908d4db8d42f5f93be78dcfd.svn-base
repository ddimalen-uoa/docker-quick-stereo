<?
include("../../php_script/password_protect.php");
include "../menu.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Digital image certification</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../mm_health_nutr.css" type="text/css" />
<script language="JavaScript" type="text/javascript">
//--------------- LOCALIZEABLE GLOBALS ---------------
var d=new Date();
var monthname=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
//Ensure correct for language. English is "January 1, 2004"
var TODAY = monthname[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear();
//---------------   END LOCALIZEABLE   ---------------
</script>
<script language="JavaScript" src="../../js/javascript.js"></script>

</head>
<body bgcolor="#F4FFE4">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <?
  include_once("../header.php");
  returnHeader();
  ?>
  <tr>
    <td colspan="7" bgcolor="#5C743D"><img src="../mm_spacer.gif" alt="" width="1" height="2" border="0" /></td>
  </tr>

  <tr>
    <td colspan="7" bgcolor="#99CC66" background="mm_dashed_line.gif"><img src="../mm_dashed_line.gif" alt="line decor" width="4" height="3" border="0" /></td>
  </tr>

  <tr bgcolor="#99CC66">
  	<td colspan="7" id="dateformat" height="20">&nbsp;&nbsp;<script language="JavaScript" type="text/javascript">
      document.write(TODAY);	</script>	</td>
  </tr>
  <tr>
    <td colspan="7" bgcolor="#99CC66" background="mm_dashed_line.gif"><img src="../mm_dashed_line.gif" alt="line decor" width="4" height="3" border="0" /></td>
  </tr>

  <tr>
    <td colspan="7" bgcolor="#5C743D"><img src="../mm_spacer.gif" alt="" width="1" height="2" border="0" /></td>
  </tr>

 <tr>
    <td width="10%" valign="top" bgcolor="#5C743D">
	<table border="0" cellspacing="0" cellpadding="0" width="165" id="navigation">
        <?
        include_once("../menu.php");
        returnMenu();
        ?>
      </table>
 	 <br />
  	&nbsp;<br />
  	&nbsp;<br />
  	&nbsp;<br /> 	</td>
    <td width="50"><img src="../mm_spacer.gif" alt="" width="50" height="1" border="0" /></td>
    <td colspan="4" valign="top"><img src="../mm_spacer.gif" alt="" width="305" height="1" border="0" /><br />
	&nbsp;<br />
	&nbsp;<br />
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="pageName"><div align="center">Digital image certification </div></td>
		</tr>

		<tr>
          <td class="bodyText"><p>
          <center>
          <?
		  	$error = 0;
			if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST["action"] != ''){				
				echo "<p><hr></p>";
				//echo "post action: ".$_POST["action"]."<br>";
				if ((($_FILES["file"]["type"] == "image/gif")
				|| ($_FILES["file"]["type"] == "image/jpeg")
				|| ($_FILES["file"]["type"] == "image/png")
				|| ($_FILES["file"]["type"] == "image/pjpeg"))
				&& ($_FILES["file"]["size"] < 10000000))
				{
					if ($_FILES["file"]["error"] > 0)
					{
						echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
					}
					else
					{
						echo "Upload image name: " . $_FILES["file"]["name"] . "<br />";
						echo "Type: " . $_FILES["file"]["type"] . "<br />";
						echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb <br />";
						//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";		
						{
							move_uploaded_file($_FILES["file"]["tmp_name"],
							"../upload/temp");
							echo "Temporary stored in: " . "../upload/temp";
							
							$fp = fopen('../upload/certificate.txt', 'w');
							fwrite($fp, $_FILES["file"]["name"]);
							fwrite($fp, "\n");
							fwrite($fp, $_POST["action"]);
							fwrite($fp, "\n");					
							fclose($fp);
							$error = 1;
						}
					}
				}
				else
				{
					if($_POST["action"] = ''){
						echo "No action is selected";
					}
					else{
						echo "Invalid file";
					}
				} 
				?>
                <?
				if($error){
				?>
                <div align="center"  id="result_pad">
                  <p>&nbsp;</p>
                <div align="center">Waiting for image key to be returned...&nbsp;</div>
                </p>
                  <p align="center"><img src="http://www.ivs.auckland.ac.nz/images/waiting.gif" width="50" height="50" /></p>
              </div>
              <script language="JavaScript" type="text/javascript">
				var wooYayIntervalId = 0;
				function callMySelf(){					
					if(document.getElementById('tableKey')){							
						clearInterval(wooYayIntervalId);
						wooYayIntervalId = null;
					}
					else
					{
						ajaxLoader("read_directory.php?number=<? echo $randomNumber; ?>", "result_pad");	
					}				
				}
				wooYayIntervalId = setInterval("callMySelf()",1000);
            </script>
            	<? } ?>
                <?
				echo "<p><hr></p>";
			} 
			?>
			</center>
			<?
			{
			?>
          <form action="<? echo $_SERVER['PHP_SELF']; ?>" method="post"
enctype="multipart/form-data">
            <table width="60%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><label for="label">Filename:</label></td>
    <td><input name="file" type="file" id="file" size="50"/></td>
  </tr>
  <tr>
    <td>Action:</td>
    <td><input type="radio" name="action" id="action_reg" value="1" <? if($_POST["action"] == '1') echo "checked"; ?> />
      Register 
        <input type="radio" name="action" id="radio_cer" value="2" <? if($_POST["action"] == '2') echo "checked"; ?> />
        Certify</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="submit" value="Submit" /></td>
  </tr>
</table>
<label for="file"><br />
</label>
<br />
          </form>
          <? } ?>
          </p>		</td>
        </tr>
		<tr>
		  <td class="bodyText">&nbsp;</td>
	    </tr>
      </table>
	 <br />
	&nbsp;<br />	<img src="../mm_spacer.gif" alt="" width="50" height="1" border="0" /><br />
    &nbsp;<br /></td>
    <td valign="top"><table width="190" border="0" align="right" cellpadding="0" cellspacing="0" id="leftcol2">
      <?
	 include_once("../right_panel.php");
	 echo returnRightPanel();
	 ?>
    </table></td>
  </tr>
  <?
	include_once("../footer.php");
	echo footerControl();
  ?>
</table>
<?
include_once("../footer.php");
echo returnFooter();
?>
</body>
</html>
<!--
INSERT INTO `cs_ivs`.`certified_images` (
`id` ,
`name` ,
`key` ,
`registered_date`
)
VALUES (
NULL , 'test', 'test_key', '2011-11-10 15:19:37'
);
-->