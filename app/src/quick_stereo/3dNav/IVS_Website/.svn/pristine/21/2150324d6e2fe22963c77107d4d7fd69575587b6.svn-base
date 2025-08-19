<?
session_start();
include "../quick_stereo/dbc.php";
/*
INSERT INTO `cs_ivs`.`ivs_autostereogram_query` (
`id` ,
`ipadress` ,
`dateEntered` ,
`comments` ,
`answerAutostereo` ,
`answerEdge` ,
`answerTexture` ,
`answerCrossParallel`
)
VALUES (
'', 'testIP', '2012-06-11 16:33:44', 'tesComment', 'testAuto', 'testEdge', 'testTexture', 'testCros'
);
*/
$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
if($_POST["textfield"] <> ''){
	$sql = "INSERT INTO `cs_ivs`.`ivs_autostereogram_query` (
`id` ,
`ipadress` ,
`dateEntered` ,
`comments`
)
VALUES (
'', '$ipaddress', NOW(), '".addslashes($_POST["textfield"])."');";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	?>
    <script language="javascript">
	alert('Thanks again!');
	window.close();	
	</script>
    <?
}
/*
$questionList[0]="autoStereogram";
$questionList[1]="edge";
$questionList[2]="texture";
$questionList[3]="crossParallel";
$questionList[4]="mask";
*/
else if($_GET["question"] != '' && ($_GET["answer"]=='yes' || $_GET["question"]=='no')){	
	$sql = "INSERT INTO `cs_ivs`.`ivs_autostereogram_query` (
	`id` ,
	`ipadress` ,
	`dateEntered` ,	
	`".$_GET["question"]."`
	)
	VALUES (
	'', '$ipaddress', NOW(), '".$_GET["answer"]."'
	);";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	
}

?>
<html>
<body style="font-size:24px" bgcolor="#F4FFE4">
<span style="text-align: center"></span>
<span style="text-align: center"></span>
<span style="text-align: center"></span>
<center>
Many thanks, is there any other comments/suggestions?<br>
<form name="form1" method="post" action="answer.php">
  <p>
    <textarea name="textfield" cols="60" id="textfield" onMouseUp="document.getElementById('button').value='Send suggestion'"></textarea>
  </p>
  <p>
    <input type="submit" name="button" id="button" value="Close" onClick="
    if(this.value=='Close'){
   		window.close();
    }
    ">
  </p>
</form>
<h1>&nbsp; </h1>
</center>
</body>
</html> 