<?
include "menu.php";
include "../quick_stereo/dbc.php";
$directory = $_GET["d"];
$sql = "select * from cs_survey limit 1";
$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
if($row = mysql_fetch_assoc($result)){
	$survey_name = $row["name"];	
	$thisSurveyID = $row["id"];	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Survey</title>
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
<style>
.bigButton {
	height:40px;
	width:100px;
	color:#FF3300;
}
.bigTextField {
	width:300px;
}
</style>
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
          <td class="pageName">Survey on &quot;<? echo $survey_name; ?>&quot;.<hr /></td>
		</tr>
        <script>
		function selectQuestion(name,value){
			if(value=='YES'){
				document.getElementById('buttonYes'+name).className='bigButton';
				document.getElementById('buttonNo'+name).className='';
				document.getElementById('hidden'+name).value=value;
			}
			else{
				document.getElementById('buttonYes'+name).className='';
				document.getElementById('buttonNo'+name).className='bigButton';
				document.getElementById('hidden'+name).value=value;
			}
		}
		</script>
		<tr>
		  <td class="bodyText"><p>Department of Computer Science, University of Auckland  Private Bag 92019,  Auckland, New Zealand</p>
            <p> PARTICIPANT INFORMATION SHEET</p>
            <p> Title: <strong>Study on <? echo $survey_name; ?></strong></p>
            <p> Researchers: <strong>Minh Nguyen</strong>, Department of Computer Science, University of Auckland</p>
            <p> <strong>To Potential Participants: An Invitation</strong></p>
            <p> We would like you to participate in a study investigating... <br />
              This   information may be useful in developing materials and aids that will   help individuals achieve a more balanced lifestyle. <br />
              This research is   being undertaken by the principal investigator, <strong>Minh Nguyen</strong>, as   fulfilment of the requirements of a Ph.D (Computer Science) degree at   the University of Auckland.</p>
            <p> About the study:  We are inviting students who are interested in 3D imaging in games or cinematic.</p>
            <p> We aim to recruit <strong>70 participants</strong>. The study will run from 31st August to the 31st September 2012. </p>
            <p> As a participant you would be ask to view 3D images using some of the most popular visual methods.</p>
          <p> We believe that the findings of this study will extend the behaviour knowledge of human vision on computer generated 3D images and the information gained may be useful in   developing informational materials and aids that may contribute to a better technique in industry. </p></td>
	    </tr>
		<tr>
		  <td class="bodyText"><hr />
		    <p>PERSONAL DETAILS:</p>
		    <p>Name: 
		      <input name="nameField" type="text" class="bigTextField" id="nameField" />
		      <br />
		      Gender:
		      <select name="genderSelect" id="genderSelect">
		        <option value="">Please select one...</option>
				<option value="Male">Male</option>
		        <option value="Female">Female</option>
	          </select>
		      <br />
		    Age:
			<select name="ageSelect" id="ageSelect">
		        <option value="">Please select one...</option>
				<option value="20">Less than 21</option>
		        <option value="30">21 to 30</option>
				<option value="40">31 to 40</option>
				<option value="50">41 to 50</option>
				<option value="60">51 to 60</option>
				<option value="70">Over 61</option>
	          </select><br />
		    Email:<input name="emailField" type="text" class="bigTextField" id="emailField" /><br />
		    Do you agree to join this survey and allow<strong> Minh Nguyen </strong>to use your supplied information toward his study?<br />
			
		    <input name="buttonYes" type="button" id="buttonYes" value="YES" 
            onclick="document.getElementById('mainSurveyPage').style.display=''; selectQuestion('',this.value);" />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="buttonNo" type="button" id="buttonNo" value="NO" onclick=" selectQuestion('',this.value);" />
            <input id="hidden" name="hidden" type="hidden" value="" />
		    </p></td>
	    </tr>
        <?
		$counter = 0;
		?>
        <tr id="mainSurveyPage" style="display:n one">
		  <td class="bodyText"><hr />
		    <p>MAIN SURVEY PAGE:</p>
            <!-- Question -->
            <?
			$sql = "select * from cs_survey_question where survey_id = '".$thisSurveyID."' order by id";
			$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
			$counterID = 1;
			while($row = mysql_fetch_assoc($result)){
				$counter = $row["id"];	
				?>
                <p>Q<? echo $counterID++; ?>: <? echo $row["question"]; ?><br />
                
                <?
				if($row["image"] != ""){
				?>
                <img src="<? echo $row["image"]; ?>" style="max-width:<? echo $row["image_percentage"]; ?>%" /><br />     
                <? } ?>
                
                <?
                if($row["answer"] == "yesno"){
                ?>
                <input name="buttonYesQ<? echo $counter; ?>" type="button" id="buttonYesQ<? echo $counter; ?>" value="YES" onclick=" selectQuestion('Q<? echo $counter; ?>',this.value);"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="buttonNoQ<? echo $counter; ?>" type="button" id="buttonNoQ<? echo $counter; ?>" value="NO" onclick=" selectQuestion('Q<? echo $counter; ?>',this.value);"/>
                <? } ?>
                
                <input id="hiddenQ<? echo $counter; ?>" name="hiddenQ<? echo $counter; ?>" type="hidden" value="" />
                <br />
                </p>
                <?
			}
			?>
		    </td>
	    </tr>
       
        <tr id="resultPage" style="display:none">
		  <td class="bodyText"><hr /><p>RESULTS</p></td>
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
