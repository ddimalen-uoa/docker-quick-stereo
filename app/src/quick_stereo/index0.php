<?php
session_start();

include 'dbc.php';
include 'checkForTerminationRequest.php';
include('simpleimage.php');

if($_GET["terminate"]==1){
	unlink("upload/upload.txt");
}

$URI_LOCATION = "\\\\webshare.fos.auckland.ac.nz\\web\\www.ivs.auckland.ac.nz\\quick_stereo\\upload_stereo\\";

if(isset($_GET["login"])) include("../php_script/password_protect.php");
if($_REQUEST["algorithm"] == "") $_REQUEST["algorithm"] = "BMDPP";
$leftFile = "upload_stereo/left";
$image = new SimpleImage();
$image->load($leftFile);
$width = $image->getWidth();
$height = $image->getHeight();
$serverOn = true;

//server 1 status check
$file = "upload_stereo/server_life.txt";
$f = fopen($file, "r");
$line = fgets($f, 1000);
fclose($file);

$file = "upload_stereo/returnParameters.txt";
$f = fopen($file, "r");
$parameterReturned = fgets($f, 1000);
fclose($file);
$interestString = substr($parameterReturned, strpos($parameterReturned, "min disparity ")+strlen("min disparity "));
$interestString = substr($interestString, 0, strpos($interestString, " px, disparity"));
$interestParameters = explode(" px, max disparity ",$interestString);

$sql = "SELECT NOW() AS current_now, TIME_TO_SEC(TIMEDIFF(NOW(), :line)) AS different";
$stmt = $dbc->prepare($sql);
$stmt->execute(array(':line' => $line));
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row){
	extract($row);
}

#echo "hello"; return;

if($different != "" && intval($different) < 100){
	$serverOn = true;
}
else{
	$serverOn = false;
}

#$IPDetail=countryCityFromIP($ipaddress);
#$country = $IPDetail['country'];
#$city = $IPDetail['city'];
/*
$sql = "SELECT *
FROM `quick_stereo_log`
WHERE ipAddress = '".$ipaddress."' AND
timeAccessed > date_sub( now( ) , INTERVAL 30 MINUTE )";
$result = mysql_query($sql);
if(mysql_num_rows($result) == 0)
{
	$sql =
	"INSERT INTO `cs_ivs`.`quick_stereo_log` (
	`id` ,
	`ipAddress` ,
	`country`,
	`city`,
	`userName` ,
	`timeAccessed`
	)
	VALUES (
	NULL , '$ipaddress', '$country ', '$city ', '".$_SERVER['HTTP_REFERER']."', NOW()
	);";
	$result = mysql_query($sql);
}
$sql = "select count(*) as totalCount from quick_stereo_log where ipaddress not in ($spamIP)";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
extract($row);

$sql = "select country as lastCountry, timeAccessed as lastTimeAccessed from quick_stereo_log where country <> '(Unknown Country?)' and country <> '(Private Address)' and country <> '' and country <> 'NEW ZEALAND' and ipaddress not in ($spamIP)
order by timeAccessed desc limit 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
extract($row);

$sql = "select distinct ipAddress from quick_stereo_log where ipaddress not in ($spamIP)";
$result = mysql_query($sql);
$totalDifferentAddresses = mysql_num_rows($result);

$sql = "select distinct country from quick_stereo_log where country <> '(Unknown Country?)' and country <> '(Private Address)' and country <> '' and ipaddress not in ($spamIP)
order by country";
$result = mysql_query($sql);
$totalDifferentCountry = mysql_num_rows($result);
$stringCountry = "";
while($row = mysql_fetch_assoc($result)){
	$stringCountry = $stringCountry.$row["country"].", ";
}
$stringCountry = substr($stringCountry, 0, -2);
*/
define ("MAX_SIZE","27000");
$errors=0;
$from = 0;
$thisdir = "upload_stereo";
$ranno = rand(0,999999);
$path= $thisdir."/left";
$path2= $thisdir."/right";
$pathMPO= $thisdir."/mpo";
$pathLeftRight= $thisdir."/leftRight";
$pathStereogram= $thisdir."/stereogram";
$pathAnaglyph= $thisdir."/anaglyph";
$path3= $thisdir."/result.jpg";
$pathSegment= $thisdir."/shareResults.jpg";
$source = $_REQUEST["source"];
$stereogram = "0";
$algorithm = $_REQUEST["algorithm"];
$upload_success = false;

if($_SERVER['REQUEST_METHOD'] == "POST" && $_REQUEST["smooth"] != ''){
	//Create a session postSession for this particular post
	if(!isset($_SESSION["postSession"])){
		//get the last used session folder
		$sql = "SELECT * FROM `cs_available_session_folder` order by `time_created`";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$_SESSION["postSession"] = $row["session_folder"];
			mysql_query("update `cs_available_session_folder` set `time_created` = NOW() where `session_folder` = '".$_SESSION["postSession"]."'");
		}
		else{
			$_SESSION["postSession"] = md5(rand()%100);
		}
	}



	mysql_query("Delete FROM `cs_processing_queue` WHERE (queued_time < DATE_SUB( NOW(), INTERVAL 5 MINUTE) OR `ip_address` = '".$_SESSION["postSession"]."')");
	mysql_query("INSERT INTO `cs_ivs`.`cs_processing_queue` (`id` ,`ip_address`,`queued_time`) VALUES (NULL , '".$_SESSION["postSession"]."', NOW());");

	$haveWaited = false;
	for ($i=0; $i <= 30; $i++) {
		$sql = "SELECT * FROM `cs_processing_queue` order by `id`";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			if($row["ip_address"] == $_SESSION["postSession"] && !file_exists('upload/upload.txt')){
				break;
			}
			else{
				$haveWaited = true;
			}
		}
		else{
			$haveWaited = true;
		}
		sleep(5);
	}
	//if($haveWaited)
	{
		sleep(8);
	}

	if(!isset($_SESSION["reProcessedURL"])) {
		copy("upload_stereo/"."StoreResults/".$_SESSION["postSession"]."/"."left", "upload_stereo/left");
		copy("upload_stereo/"."StoreResults/".$_SESSION["postSession"]."/"."right", "upload_stereo/right");
	}

	$fp = fopen('upload_stereo/session.bat', 'w');
	fwrite($fp, "mkdir ".$URI_LOCATION."StoreResults\\".$_SESSION["postSession"]."\n");
	fwrite($fp, "copy ".$URI_LOCATION."*.* ".$URI_LOCATION."StoreResults\\".$_SESSION["postSession"]."\\");
	fclose($fp);

	unlink($pathSegment);
	unlink($thisdir."/left_raw_resized.jpg");unlink($thisdir."/depthReserved.jpg");
	unlink($thisdir."/1.jpg");
	unlink($thisdir."/2.jpg");
	unlink($thisdir."/3.jpg");
	unlink($thisdir."/4.jpg");
	unlink($thisdir."/5.jpg");
	unlink($thisdir."/6.jpg");
	unlink($thisdir."/resultPNG.png");

	$_SESSION["counteringText"] = 0;
	if($_REQUEST["minDisp"] == "") $_REQUEST["minDisp"] = '-999';
	if($_REQUEST["maxDisp"] == "") $_REQUEST["maxDisp"] = '+999';
	$minDisp = $_REQUEST["minDisp"];
	$maxDisp = $_REQUEST["maxDisp"];
	$smooth = $_REQUEST["smooth"];
	$coordinates = $_REQUEST["coordinates"];
	$rectification = $_REQUEST["rectification"];
	$crossEye = $_REQUEST["crossEye"];
	$full_disparity = $_REQUEST["full_disparity"];
	$resizeTo = $_REQUEST["resizeTo"];
	$disparityScale = $_REQUEST["disparityScale"];


	$_SESSION["minDisp"] = $_REQUEST["minDisp"];
	$_SESSION["maxDisp"] = $_REQUEST["maxDisp"];
	$_SESSION["smooth"] = $_REQUEST["smooth"];
	$_SESSION["coordinates"] = $_REQUEST["coordinates"];

    if($_REQUEST["ufile"] != ""){
		save_image($_REQUEST["ufile"],$path);
		if(getimagesize($path) < 1000){
			echo "<script language=javascript>alert(' We detected something wrong.'); document.location='index.php';</script>";
					$errors=1;unlink($path);
					return false;

		}
		$extension = getExtension($_REQUEST["ufile"]);
		if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "bmp") && ($extension != "png") && ($extension != "ppm") && ($extension != "pgm") && ($extension != ""))
		{
			echo "<script language=javascript>alert(' Wrong image extension.'); document.location='index.php';</script>";
					$errors=1;unlink($path);
					return false;

		}
	}
	if($_REQUEST["ufile2"] != ""){
		save_image($_REQUEST["ufile2"],$path2);
		if(getimagesize($path2) < 1000){
			echo "<script language=javascript>alert(' We detected something wrong.'); document.location='index.php';</script>";
					$errors=1;unlink($path2);
					return false;
		}
		$extension = getExtension($_REQUEST["ufile2"]);
		if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "bmp") && ($extension != "png") && ($extension != "ppm") && ($extension != "pgm") && ($extension != ""))
		{
			echo "<script language=javascript>alert(' Wrong image extension.'); document.location='index.php';</script>";
					$errors=1;unlink($path2);
					return false;

		}
	}
	if($_REQUEST["ufileLeftRight"] != ""){
		save_image($_REQUEST["ufileLeftRight"],$pathLeftRight);
		if(getimagesize($pathLeftRight) < 1000){
			echo "<script language=javascript>alert(' We detected something wrong.'); document.location='index.php';</script>";
					$errors=1;unlink($pathLeftRight);
					return false;
		}
		$extension = getExtension($_REQUEST["ufileLeftRight"]);
		if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "ppm") && ($extension != "")) {
			echo "<script language=javascript>alert(' Wrong image extension.'); document.location='index.php';</script>";
					$errors=1;unlink($pathLeftRight);
					return false;

		}
	}
	if($_REQUEST["ufileStereogram"] != ""){
		save_image($_REQUEST["ufileStereogram"],$pathStereogram);
		save_image($_REQUEST["ufileStereogram"],$path);
		save_image($_REQUEST["ufileStereogram"],$path2);
		$minDisp = 1;
		$maxDisp = 200;
		$rectification = 1;
		$stereogram = "1";
		$_REQUEST["rectification"] = 1;
		$_REQUEST["minDisp"] = 1;
		$_REQUEST["maxDisp"] = 200;

		$extension = getExtension($_REQUEST["ufileStereogram"]);
		if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "ppm") && ($extension != "")) {
			echo "<script language=javascript>alert('Wrong image extension.'); document.location='index.php';</script>";
					$errors=1;unlink($pathStereogram);
					return false;

		}
	}

	if($_REQUEST["ufileAnaglyph"] != ""){
		save_image($_REQUEST["ufileAnaglyph"],$pathAnaglyph);
		if(getimagesize($pathAnaglyph) < 1000){
			echo "<script language=javascript>alert(' We detected something wrong.'); document.location='index.php';</script>";
					$errors=1;unlink($pathAnaglyph);
					return false;
		}
		$extension = getExtension($_REQUEST["ufileAnaglyph"]);
		if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "ppm") && ($extension != "")) {
			echo "<script language=javascript>alert(' Wrong image extension.'); document.location='index.php';</script>";
					$errors=1;unlink($pathAnaglyph);
					return false;

		}
	}


	if(($ufile !=none && $ufile2 !=none) || $ufileMPO !=none || $ufileLeftRight !=none || $ufileSteregram !=none)
	{
		$upload_success = true;
		{
			$filename = stripslashes($_FILES['ufile']['name']);
			$extension = getExtension($filename);
			$filename2 = stripslashes($_FILES['ufile2']['name']);
			$extension2 = getExtension($filename2);
			$filename3 = stripslashes($_FILES['ufileMPO']['name']);
			$extension3 = getExtension($filename3);
			$filename4 = stripslashes($_FILES['ufileLeftRight']['name']);
			$extension4 = getExtension($filename4);
			$filename5 = stripslashes($_FILES['ufileStereogram']['name']);
			$extension5 = getExtension($filename5);
			$filename6 = stripslashes($_FILES['ufileAnaglyph']['name']);
			$extension6 = getExtension($filename6);

			{
				$size=filesize($_FILES['ufile']['tmp_name']);

				if ($size > MAX_SIZE*1024)
				{
					echo "<script language=javascript>alert(' You have exceeded the size limit.'); document.location='index.php';</script>";
					$errors=1;
					return;
				}
			}
		}

		if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "bmp") && ($extension != "png") && ($extension != "ppm") && ($extension != "")) {
			echo "<script language=javascript>alert(' Wrong image extension.'); document.location='index.php';</script>";
			$errors=1;
			return;
		}
		else if (($extension2 != "jpg") && ($extension2 != "jpeg") && ($extension2 != "bmp") && ($extension2 != "png") && ($extension2 != "ppm") && ($extension2 != "")) {
			echo "<script language=javascript>alert(' Wrong image extension.'); document.location='index.php';</script>";
			$errors=1;
			return;
		}

		else if ((strtolower($extension3) != "mpo")  && (strtolower($extension3) != "pns") && (strtolower($extension3) != "jps") && ($extension3 != "")) {
			echo "<script language=javascript>alert(' Wrong image extension for MPO input file.'); document.location='index.php';</script>";
			$errors=1;
			return;
		}
		else if (($extension4 != "jpg") && ($extension4 != "jpeg") && ($extension4 != "png") && ($extension4 != "ppm") && ($extension4 != "")) {
			echo "<script language=javascript>alert(' Wrong image extension.'); document.location='index.php';</script>";
			$errors=1;
			return;
		}
		else if (($extension5 != "jpg") && ($extension5 != "jpeg") && ($extension5 != "png") && ($extension5 != "ppm") && ($extension5 != "")) {
			echo "<script language=javascript>alert(' Wrong image extension.'); document.location='index.php';</script>";
			$errors=1;
			return;
		}
		else if (($extension6 != "jpg") && ($extension6 != "jpeg") && ($extension6 != "png") && ($extension6 != "ppm") && ($extension6 != "")) {
			echo "<script language=javascript>alert(' Wrong image extension.'); document.location='index.php';</script>";
			$errors=1;
			return;
		}

		else{
			if((strtolower($extension3) == "mpo")){
				copy($HTTP_POST_FILES['ufileMPO']['tmp_name'], $pathMPO);
			}
			else if((strtolower($extension3) == "jps") || (strtolower($extension3) == "pns")){
				copy($HTTP_POST_FILES['ufileMPO']['tmp_name'], $pathLeftRight);
				$crossEye = 1;
			}
			copy($HTTP_POST_FILES['ufileLeftRight']['tmp_name'], $pathLeftRight);
			if(copy($HTTP_POST_FILES['ufileStereogram']['tmp_name'], $pathStereogram)){
				copy($HTTP_POST_FILES['ufileStereogram']['tmp_name'], $path);
				copy($HTTP_POST_FILES['ufileStereogram']['tmp_name'], $path2);

				$minDisp = 1;
				$maxDisp = 200;
				$rectification = 1;
				$stereogram = "1";
				$_REQUEST["rectification"] = 1;
				$_REQUEST["minDisp"] = 1;
				$_REQUEST["maxDisp"] = 200;
			}
			copy($HTTP_POST_FILES['ufileAnaglyph']['tmp_name'], $pathAnaglyph);
			copy($HTTP_POST_FILES['ufile']['tmp_name'], $path);
			copy($HTTP_POST_FILES['ufile2']['tmp_name'], $path2);
			
			//echo $algorithm; return;
			
			
			
			if($algorithm == "CUDA") {
				$fp = fopen('upload_stereo/uploadedCUDA.txt', 'w');
			}
			else{
				$fp = fopen('upload_stereo/uploaded.txt', 'w');
			}
			
			fwrite($fp, $minDisp);
			fwrite($fp, "\n");
			fwrite($fp, $maxDisp);
			fwrite($fp, "\n");
			fwrite($fp, $smooth);
			fwrite($fp, "\n");
			fwrite($fp, $coordinates);
			fwrite($fp, "\n");
			fwrite($fp, $rectification);
			fwrite($fp, "\n");
			fwrite($fp, $stereogram);
			fwrite($fp, "\n");
			fwrite($fp, $algorithm);
			fwrite($fp, "\n");
			fwrite($fp, $crossEye);
			fwrite($fp, "\n");
			fwrite($fp, $full_disparity);
			fwrite($fp, "\n");
			fwrite($fp, $resizeTo);
			fwrite($fp, "\n");
			fwrite($fp, $disparityScale);
			fwrite($fp, "\n");
			fclose($fp);
		}
	}
}
?>
<?php
include "../web/menu.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

	<!-- DW6 -->
	<head>
	<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
		<title>Online Computational Stereo Vision</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<?php
			if($_SESSION["smartPhoneDevice"] == "iPod")
			//if(true)
			{
		?>
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
		<link rel="stylesheet" href="../web/iphone.css" type="text/css" />
		<?php
		}
		else{
		?>
		<link rel="stylesheet" href="../web/mm_health_nutr.css" type="text/css" />
		<?php
		}
		?>
		<link rel="stylesheet" href="css/main.css" type="text/css" />
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-49645914-1', 'auckland.ac.nz');
			ga('send', 'pageview');

		</script>
		<script language="JavaScript" type="text/javascript">
			var showAutostereogram = true;
			//--------------- LOCALIZEABLE GLOBALS ---------------
			var d=new Date();
			var monthname=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
			//Ensure correct for language. English is "January 1, 2004"
			var TODAY = monthname[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear();
			//---------------   END LOCALIZEABLE   ---------------

			//processing time
			var algorithmProcessingTime = new Array();
			<?php
			$sql = "SELECT `algorithm`, AVG(resolution) AS averageResolution, AVG(processingTime) AS average,
			   AVG(processingTime*processingTime) - AVG(processingTime)*AVG(processingTime) AS variance,
			   SQRT(AVG(processingTime*processingTime) - AVG(processingTime)*AVG(processingTime)) AS stdev,
			   MIN(processingTime) AS minTime,
			   MAX(processingTime) AS maxTime
			FROM `cs_stereo_processing_time`
			GROUP BY `algorithm`";
			$result = mysql_query($sql);

			while($row = mysql_fetch_assoc($result)){
				extract($row);
				?>
				algorithmProcessingTime["<?php echo $algorithm; ?>"]='Average waiting time: <?php echo intval($average); ?> &#177; <?php echo intval($stdev); ?> seconds excluding uploading and downloading time!';
				<?php
			}
			?>

			function updateProcessingTime(algorithmName){
				document.getElementById('expectedTime').innerHTML = algorithmProcessingTime[algorithmName];
			}


			function hideAll(){
				if(<?php if($_SESSION["smartPhoneDevice"] == "iPod") echo "false"; else echo "true"; ?>){
					document.getElementById('leftUpload').style.display = 'none';
					document.getElementById('rightUpload').style.display = 'none';
				}
				document.getElementById('mpoUpload').style.display = 'none';
				document.getElementById('leftRightUpload').style.display = 'none';
				document.getElementById('stereogramUpload').style.display = 'none';
				document.getElementById('anaglyphUpload').style.display = 'none';
			}
			function display(element){
				document.getElementById(element).style.display = '';
			}
		</script>
		<script language="JavaScript" src="../js/javascript.js"></script>
		<script src="../Scripts/AC_ActiveX.js" type="text/javascript"></script>
		<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
		<style type="text/css">
			<!--
			.style1 {color: #000000}
			-->
		</style>
<!-- DEBUT AJOUT SCRIPT POPUP -->
<!-- DEBUT POPUP "PROCESS/SUBMIT" BUTTON -->
		<!-- <script type = "text/javascript">
			function show_confirm()
			{
				var r=confirm ("Do you agree to let us use your images for research and publications purposes only ?");
				if (r==true)
				{
					this.value='Uploading files... please wait...';
						if(document.getElementById('result_pad'))
						{
							document.getElementById('result_pad').style.display='none';
						}
						if(document.getElementById('cached_images'))
						{
							document.getElementById('cached_images').style.display='none';
						}
						document.getElementById('tempoWaiting').style.display='';
						scroll(0,500);
						document.form1.submit();
														  
					<?php if($serverOn == false/* && $serverOn2 == false*/) echo "disabled='disabled';";?>  
				}
				else
				{
					window.location="index.php";
				}
			}
		</script> -->
<!-- FIN POPUP "PROCESS/SUBMIT" BUTTON -->
<!-- DEBUT POPUP "DOWNLOADS" LINKS -->		
		<!-- script type = "text/javascript">
			function show_warning(downloadLink)
			{
				var p=confirm ("Do not use for commercial purpose !");
				if (p==true)
				{
					window.open(downloadLink,"_self", null);
				}
				else
				{
					window.location="http://www.google.com/";
				}
			}
		</script -->
<!-- FIN POPUP "DOWNLOADS" LINKS -->
<!-- FIN AJOUT SCRIPT POPUP -->
	</head>


	<body onLoad="hideAll();display('leftUpload');display('rightUpload');updateProcessingTime(document.getElementById('algorithm').value);">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<?php
				include_once("../web/header.php");
				returnHeader();
			?>
			<tr class="extra">
				<td colspan="7" bgcolor="#5C743D"><img src="mm_spacer.gif" alt="" width="1" height="2" border="0" /></td>
			</tr>

			<tr class="extra">
				<td colspan="7" bgcolor="#fff">
					<img src="mm_spacer.gif" alt="line decor" width="4" height="3" border="0" />
				</td>
			</tr>
			<tr bgcolor="#444" class="extra">
				<td colspan="7" id="dateformat" height="20">
					<?php
						include_once("../web/horizontalMenu.php");
						//echo returnMenu();
					?>
				</td>
			</tr>
			<tr class="extra">
				<td colspan="7" bgcolor="#fff" ><img src="mm_spacer.gif" alt="line decor" width="4" height="3" border="0" /></td>
			</tr>
			<tr class="extra">
				<td colspan="7" bgcolor="#5C743D"><img src="mm_spacer.gif" alt="" width="1" height="2" border="0" /></td>
			</tr>
			<tr class="extra">
				<td colspan="7" bgcolor="#fff">
					[Server is <?php if($serverOn) echo "ACTIVE"; else echo "INACTIVE"; ?>, reported at <?php echo $line; ?>] [Session:
					<?php
						if(isset($_SESSION["postSession"]))
						echo $_SESSION["postSession"];
						else
						echo "None";
					?>]
				</td>
			</tr>
			<tr>
				<td width="0%" valign="top" bgcolor="#444" class="extra">
					<table border="0" cellspacing="0" cellpadding="0" width="100%" id="navigation">
						<?php
							//include_once("../web/menu.php");
							//returnMenu();
						?>
					</table>
					<br />
				&nbsp;<br />
				&nbsp;<br />
				&nbsp;<br />
				</td>
				<td width="10" class="extra">
					<img src="mm_spacer.gif" alt="" width="10" height="1" border="0" />
				</td>
				<td colspan="4" valign="top" class="mainForIphone">
					<img src="mm_spacer.gif" alt="" width="305" height="1" border="0" />
					<table border="0" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td class="pageName">
							Online Computational Stereo Vision - last updated 18/12/2015<br />
								<?php
									if($serverOn == false/* && $serverOn2 == false*/)
									{
								?>
								<span class="highlight" id="serverBusy">Server is either BUSY or OFFLINE, please come back later!!!<br />
									<a href="index.php?terminate=1">Click here to try forcing server to terminate all current tasks</a>
								</span><br />
								<?php } ?>
								<!-- AddThis Button BEGIN -->
								<div class="addthis_toolbox addthis_default_style ">
									<a class="addthis_button_preferred_1"></a>
									<a class="addthis_button_preferred_2"></a>
									<a class="addthis_button_preferred_3"></a>
									<a class="addthis_button_preferred_4"></a>
									<a class="addthis_button_google_plusone"></a>
									<a class="addthis_button_compact"></a>
									<a class="addthis_counter addthis_bubble_style"></a>
								</div>
								<!--<script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e35e74032ba14e4"></script>-->
								<!-- AddThis Button END -->
							</td>
						</tr>

						<tr>
							<td class="bodyText" >
								<table width="100%" border="0" style="vertical-align:top; text-align:center">
									<tr>
										<td width="100%" id="bodyText">
										<h2 align="center"><span class="extra">Choose types and </span>upload stereo images<span class="extra">, <a href="javascript:alert('Please select the follow image types.');">select</a> from the following input types:</span></h2>
<!-- FORM -->								<form action="index.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
												<table width="100%" border="0" cellspacing="1" cellpadding="1">
													<tr>
														<td colspan="2">
															<table width="95%" border="0" cellspacing="1" cellpadding="1" >
																<tr class="extra">
																	<td colspan="2">
																		<div align="left">
																			<table width="100%" border="1" bgcolor="#fff" onClick="document.getElementById('rectification').selectedIndex=0;" class="tableControl">
																				<tr>
																					<td width="16.66%" class="inputType" id="inputTable1" onClick="
																							hideAll();
																							display('leftUpload');
																							display('rightUpload'); ">
																						<div align="center">
																							<img src="leftPlusRight.jpg" width="100%" alt="Need help ? Go to 'Instructions' tab" title="Need help ? Go to 'Instructions' tab"/><br />
																						Upload left/right images
																						</div>
																					</td>
																					<td width="16.66%" class="inputType" onClick="
																							hideAll();
																							display('leftRightUpload'); ">
																						<div align="center">
																							<img src="leftRight.jpg" width="90%" alt="Need help ? Go to 'Instructions' tab" title="Need help ? Go to 'Instructions' tab"/><br />
																						Upload combined image
																						</div>
																					</td>
																					<td width="16.66%" class="inputType" onClick="
																							hideAll();
																							display('anaglyphUpload'); ">
																						<div align="center">
																							<img src="anaglyphs.jpg" width="75%" alt="Need help ? Go to 'Instructions' tab" title="Need help ? Go to 'Instructions' tab"/><br />
																						Upload anaglyph image
																						</div>
																					</td>
																					<td width="16.66%" class="inputType" onClick="
																							hideAll();
																							display('mpoUpload');
																							//alert('temporary disabled'); ">
																						<div align="center">
																							<img src="w1.jpg" width="70%" alt="Need help ? Go to 'Instructions' tab" title="Need help ? Go to 'Instructions' tab"/><br />
																						Upload .MPO .PNS .JPS
																						</div>
																					</td>
																					<td width="16.66%" class="inputType" onClick="
																							hideAll();
																							display('stereogramUpload'); ">
																						<div align="center">
																							<img src="stereoGram.jpg" width="80%" alt="Need help ? Go to 'Instructions' tab" title="Need help ? Go to 'Instructions' tab"/><br />
																						Upload stereogram image
																						</div>
																					</td>
																					<!-- <td width="16.66%" class="inputType" onClick="
																							hideAll();
																							//if(confirm('Please make sure that you have dual sensor web-cam set up properly, contact me for more information.')){
																							//	alert('Temporarily disabled!');
																							document.location='../calibrate_minoru/index.php';
																							//}	">
																						<div align="center">
																							<img src="sidebyside.png" width="80%" alt="Need help ? Go to 'Instructions' tab" title="Need help ? Go to 'Instructions' tab"/><br />
																						Process side-by-side webcams
																						</div> -->
																					</td>
																				</tr>
																			</table>
																		</div>
																	</td>
																</tr>
																<tr>
																	<td width="25%">
																		<div align="left"></div>
																	</td>
																	<td width="75%">
																		<div align="left"></div>
																	</td>
																</tr>
		<!-- Upload area for left/right image -->
																<tr id="leftUpload" <?php if($_SESSION["smartPhoneDevice"] != "iPod") echo 'style="display:none"'; ?>>
																	<td width="25%">
																		<div align="left">
																			<span class="extra">Select</span>
																				<select name="leftImage" id="leftImage" onChange="
																						if(this.value == 'left image') 
																						{
																							document.getElementById('ufile').type = 'file';
																						}
																						else if(this.value == 'left URL') 
																						{
																							document.getElementById('ufile').type = 'text';
																						}
																						">
																					<option value="left image">left image</option>
																					<option value="left URL">left URL</option>
																				</select>
																			<span class="extra">:</span>
																		</div>
																	</td>
																	<td width="75%">
																		<div align="left">
																			<input name="ufile" type="file" id="ufile" size="50" />
																		</div>
																	</td>
																</tr>
																<tr id="rightUpload" <?php if($_SESSION["smartPhoneDevice"] != "iPod") echo 'style="display:none"'; ?>>
																	<td>
																		<div align="left">
																			<span class="extra">Select</span>
																				<select name="rightImage" id="rightImage" onChange="
																						if(this.value == 'right image') 
																						{
																							document.getElementById('ufile2').type = 'file';
																						}
																						else if(this.value == 'right URL') 
																						{
																							document.getElementById('ufile2').type = 'text';
																						}">
																					<option value="right image">right image</option>
																					<option value="right URL">right URL</option>
																				</select>
																			<span class="extra">:</span>
																		</div>
																	</td>
																	<td>
																		<div align="left">
																			<input name="ufile2" type="file" id="ufile2" size="50" />
																		</div>
																	</td>
																</tr>
																<tr id="mpoUpload" style="display:none">
																	<td width="25%">
																		<div align="left">Select <strong>.MPO .JPS .PNS image</strong>:</div>
																	</td>
																	<td width="75%">
																		<div align="left">
																			<input name="ufileMPO" type="file" id="ufileMPO" size="50" />
																		</div>
																	</td>
																</tr>
																<tr id="leftRightUpload" style="display:none">
																	<td width="25%">
																		<div align="left">Select a <strong>combined
																			<select name="leftRightImage" id="leftRightImage" onChange="
																					if(this.value == 'left/right image') 
																					{
																						document.getElementById('ufileLeftRight').type = 'file';
																					}
																					else if(this.value == 'left/right URL') 
																					{
																						document.getElementById('ufileLeftRight').type = 'text';
																					}">
																				<option value="left/right image">left/right image</option>
																				<option value="left/right URL">left/right URL</option>
																			</select>
																			</strong>:
																		</div>
																	</td>
																	<td width="75%">
																		<div align="left">
																			<input name="ufileLeftRight" type="file" id="ufileLeftRight" size="50%" />
																			<input name="crossEye" id="crossEye0" type="radio" value="0" checked="checked" />
																		Crossed eye
																			<input type="radio" name="crossEye" id="crossEye1" value="1" />
																		Paralleled eye
																		</div>
																	</td>
																</tr>

																<tr id="stereogramUpload" style="display:none">
																	<td width="25%">
																		<div align="left">Select a<strong>
																			<select name="stereogramImage" id="stereogramImage" onChange="
																				if(this.value == 'stereogram image') 
																				{
																					document.getElementById('ufileStereogram').type = 'file';
																				}
																				else if(this.value == 'stereogram URL') 
																				{
																					document.getElementById('ufileStereogram').type = 'text';
																				}">
																				<option value="stereogram image">stereogram image</option>
																				<option value="stereogram URL">stereogram URL</option>
																			</select></strong>:
																		</div>
																	</td>
																	<td width="75%">
																		<div align="left">
																			<input name="ufileStereogram" type="file" id="ufileStereogram" size="50" />
																		</div>
																	</td>
																</tr>
																<tr id="anaglyphUpload" style="display:none">
																	<td width="25%">
																		<div align="left">Select an</strong>
																			<select name="anaglyphImage" id="anaglyphImage" onChange="
																					if(this.value == 'anaglyph image') 
																					{
																						document.getElementById('ufileAnaglyph').type = 'file';
																					}
																					else if(this.value == 'anaglyph URL') 
																					{
																						document.getElementById('ufileAnaglyph').type = 'text';
																					}">
																				<option value="anaglyph image">anaglyph image</option>
																				<option value="anaglyph URL">anaglyph URL</option>
																			</select>:
																		</div>
																	</td>
																	<td width="75%">
																		<div align="left">
																			<input name="ufileAnaglyph" type="file" id="ufileAnaglyph" size="50" />
																		</div>
																	</td>
																</tr>
																<tr class="extra">
																	<td>
																		<div align="left"><hr></div>
																	</td>
																	<td>
																		<div align="left"><hr></div>
																	</td>
																</tr>

																<tr class="extra">
																	<td>
																		<div align="left">Required image rectification:</div>
																	</td>
																	<td>
																		<div align="left">
																			<select name="rectification" id="rectification" onChange="
																					if(this.value == '2') 
																					{
																						alert('Please remember you have to run automatic rectification first.');
																						window.open( 'manualRect.php', 'Please select left and right correspondence points', 'status = 1, height = 800, width = 800, resizable = 1, scrollbars=1' );
																					}">
																				<option value="0" <?php  if(isset($_REQUEST["rectification"]) && $_REQUEST["rectification"] == '0') echo "selected"; ?>>Automatic</option>
																				<option value="1" <?php  if(isset($_REQUEST["rectification"]) && $_REQUEST["rectification"] == '1') echo "selected"; ?>>No, they already aligned</option>
																				<option value="2">Manually selected</option>
																			</select>
																		(*) Horizontal alignment of the image pair
																		</div>
																	</td>
																</tr>
																<!--
																<tr class="extra">
																	<td>
																		<div align="left">Tracker used:</div>
																	</td>
																	<td>
																		<div align="left">
																			<select name="tracker" id="tracker" onchange="">
																				<option value="GFT" <?php  if(isset($_REQUEST["tracker"]) && $_REQUEST["tracker"] == 'GFT') echo "selected"; ?>>Kanade-Lucas-Tomasi feature tracker (KLT)</option>
																				<option value="SURF" <?php  if(isset($_REQUEST["tracker"]) && $_REQUEST["tracker"] == 'SURF') echo "selected"; ?>>Speeded Up Robust Feature tracker (SURF)</option>
																				<option value="SIFT" <?php  if(isset($_REQUEST["tracker"]) && $_REQUEST["tracker"] == 'SIFT') echo "selected"; ?>>Scale-Invariant Feature transform (SIFT)</option>
																				</select>
																		</div>
																	</td>
																</tr>
																-->
																<tr class="extra">
																	<td>
																		<div align="left">Stereo matching algorithm:</div>
																	</td>
																	<td>
																		<div align="left">
																			<?php include('algorithmList.php');?>
																		Choose one
																		</div>
																	</td>
																</tr>
																<tr class="extra">
																	<td>
																		<div align="left">Force Dispary Range:</div>
																	</td>
																	<td>
																		<div align="left">
																			<select name="full_disparity" id="full_disparity" onChange="//alert('This change might lead to longer processing time, please be patient');">
																			<option value="1" <?php  if(isset($_REQUEST["full_disparity"]) && $_REQUEST["full_disparity"] == '1') echo "selected"; ?>>No</option>
																			<option value="16" <?php  if(isset($_REQUEST["full_disparity"]) && $_REQUEST["full_disparity"] == '16') echo "selected"; ?>>16 levels</option>
																			<option value="32" <?php  if(isset($_REQUEST["full_disparity"]) && $_REQUEST["full_disparity"] == '32') echo "selected"; ?>>32 levels</option>
																			<option value="48" <?php  if(isset($_REQUEST["full_disparity"]) && $_REQUEST["full_disparity"] == '48') echo "selected"; ?>>48 levels</option>
																			<option value="64" <?php  if(isset($_REQUEST["full_disparity"]) && $_REQUEST["full_disparity"] == '64') echo "selected"; ?>>64 levels</option>
																			<option value="72" <?php  if(isset($_REQUEST["full_disparity"]) && $_REQUEST["full_disparity"] == '72') echo "selected"; ?>>72 levels</option>
																			<option value="96" <?php  if(isset($_REQUEST["full_disparity"]) && $_REQUEST["full_disparity"] == '96') echo "selected"; ?>>96 levels</option>
																			<option value="128" <?php  if(isset($_REQUEST["full_disparity"]) && $_REQUEST["full_disparity"] == '128') echo "selected"; ?>>128 levels</option>
																			<option value="256" <?php  if(isset($_REQUEST["full_disparity"]) && $_REQUEST["full_disparity"] == '256') echo "selected"; ?>>256 levels</option>
																			</select>
																		(*) Force depthmap to result N depth levels
																		</div>
																	</td>
																</tr>
																	<?php
																		$dMin = 0;$dMax = 50;
																		if(isset($_REQUEST["minDisp"]) && isset($_REQUEST["minDisp"]) && ($_REQUEST["minDisp"] != "-999" && $_REQUEST["maxDisp"] != "+999"))
																		{
																		$dMin = intval($_REQUEST["minDisp"]);
																		$dMax = intval($_REQUEST["maxDisp"]);
																		
																		if($dMax < 0) $dMax = 0;
																		if(($dMax - $dMin) < 0) $dMin = 0;
																		if(($dMax - $dMin) > 160 ) $dMax = $dMin + 160;
																		}
																	?>
																<tr class="extra">
																	<td>
																		<div align="left">
																			<label>
																				<select name="autoMinDisp" id="autoMinDisp" onChange="
																						if(document.getElementById('autoMinDisp').value == '0')
																						{
																							//document.getElementById('minDisp').value = '<?php echo $interestParameters[0]; ?>';
																							document.getElementById('minDisp').disabled='';
																						}
																						else
																						{
																							document.getElementById('minDisp').value = '-999';
																							document.getElementById('minDisp').disabled='disabled';
																						}">
																					<option value="1" <?php if($_REQUEST["autoMinDisp"] == '1') echo "selected"; ?>>Auto detected</option>
																					<option value="0" <?php if($_REQUEST["autoMinDisp"] == '0') echo "selected"; ?>>Manual entered</option>
																				</select>
																			</label>
																		minimum disparity:
																		</div>
																	</td>
																	<td>
																		<div align="left">
																			<input name="minDisp" type="text" id="minDisp" value="<?php  if(isset($_REQUEST["minDisp"])) echo $_REQUEST["minDisp"]; else echo $interestParameters[0]; ?>" size="40" <?php if($_SERVER['REQUEST_METHOD'] != 'POST' || $_REQUEST["autoMinDisp"] == '1') echo "disabled=\"disabled\""; ?> />
																		(*) Negative value is allowed
																		</div>
																	</td>
																</tr>
																<tr class="extra">
																	<td>
																		<div align="left">
																			<select name="autoMaxDisp" id="autoMaxDisp" onChange="
																					if(document.getElementById('autoMaxDisp').value == '0')
																					{
																						//document.getElementById('maxDisp').value = '<?php echo $interestParameters[1]; ?>';
																						document.getElementById('maxDisp').disabled='';
																					}
																					else
																					{
																						document.getElementById('maxDisp').value = '+999';
																						document.getElementById('maxDisp').disabled='disabled';
																					}">
																				<option value="1" <?php if($_REQUEST["autoMaxDisp"] == '1') echo "selected"; ?>>Auto detected</option>
																				<option value="0" <?php if($_REQUEST["autoMaxDisp"] == '0') echo "selected"; ?>>Manual entered</option>
																			</select>
																		maximum disparity:
																		</div>
																	</td>
																	<td>
																		<div align="left">
																			<input name="maxDisp" type="text" id="maxDisp" value="<?php if(isset($_REQUEST["maxDisp"])) echo $_REQUEST["maxDisp"]; else echo $interestParameters[1]; ?>" size="40" <?php if($_SERVER['REQUEST_METHOD'] != 'POST' || $_REQUEST["autoMaxDisp"] == '1') echo "disabled=\"disabled\""; ?> />
																		(*) Positive value only
																		</div>
																	</td>
																</tr>
																<tr class="extra">
																	<td>
																		<div align="left">Disparity scale:</div>
																	</td>
																	<td>
																		<div align="left">
																			<input name="disparityScale" type="text" id="disparityScale" value="<?php if(isset($_REQUEST["disparityScale"])) echo $_REQUEST["disparityScale"]; else echo '1'; ?>" size="40" />
																		(*) Positive value only and 1 is for auto scale
																		</div>
																	</td>
																</tr>
																<tr class="extra">
																	<td>
																		<div align="left">Smoothing value:</div>
																	</td>
																	<td>
																		<div align="left">
																			<input name="smooth" type="text" id="smooth" value="<?php if(isset($_REQUEST["smooth"])) echo $_REQUEST["smooth"]; else echo '0'; ?>" size="40" />
																		(*) Positive value only and less than 50, ie: 1 2 3...
																		</div>
																	</td>
																</tr>
																<tr class="extra" style="display:none">
																	<td>
																		<div align="left">Number of vertices per line:</div>
																	</td>
																	<td>
																		<div align="left">
																			<input name="coordinates" type="text" id="coordinates" value="<?php if(isset($_REQUEST["coordinates"])) echo $_REQUEST["coordinates"]; else echo '35'; ?>" size="40" readonly />
																			(*) Positive value only, ie: 25, 50, 75, 100...
																		</div>
																	</td>
																</tr>
																<tr class="extra">
																	<td>
																		<div align="left">Resize large images to:</div>
																	</td>
																	<td>
																		<div align="left">
																			<select name="resizeTo" id="resizeTo">
																				<option value="600">600</option>
																				<option value="700">700</option>
																				<option value="800">800</option>
																				<option value="900">900</option>
																				<option value="1024" selected="selected">1024</option>
																				<option value="1280">1280</option>
																				<option value="1400">1400</option>
																				<option value="1600">1600</option>
																			</select>
																		pixels (the larger, the slower)
																		</div>
																	</td>
																</tr>
																<tr>
																	<td colspan="2">
																		<div align="center">
																			<table width="100%" border="0" cellspacing="0" cellpadding="0">
																				<tr>
																					<td>
																						<div align="center">
																							<strong id="expectedTime">Average waiting time: ?? &#177; ?? seconds excluding upload and download time!</strong><br />
																							<input type="hidden" name="MAX_FILE_SIZE" value="10000" />
<!-- Lien vers ligne de code ligne 510-540 -->
<!-- Process/Submit button -->																
																							
																							<script type="text/javascript">

																							function f_agree_process(id)
																							{

																								$check = confirm("Do you let us use your images for research and publications purposes only ?");
																								if($check==false)
																								{
																									//alert("You have to agree to let us use your images.");
																									width = 300;
																	                                height = 100;
																	                                if(window.innerWidth)
																	                                {
																	                                        var left = (window.innerWidth-width)/2;
																	                                        var top = (window.innerHeight-height)/2;
																	                                }
																	                                else
																	                                {
																	                                        var left = (document.body.clientWidth-width)/2;
																	                                        var top = (document.body.clientHeight-height)/2;
																	                                }
																									window.open('popup_process.html','Agree','menubar=no, scrollbars=no, top='+top+', left='+left+', width='+width+', height='+height+', location=no');
																								}
																								else
																								{
																									document.getElementById(id).type = "submit";
																								}
																							}

																							</script>
																							<input onClick="f_agree_process(this.id)" name="submitButton" type="button" class="submitButton" id="submitButton" value="Process/Submit" /> 
																							or <a href="index.php">Reset</a><br />
																						</div>
																						<!--
																						<div align="center" class="extra">
																							(<strong><?php echo $totalCount;?> visits</strong> so far from <strong><?php echo $totalDifferentAddresses;?> different IP addresses</strong> and <strong>
																							<?php echo $totalDifferentCountry;?> countries</strong>, <br />
																							last international visit was from <strong><?php echo $lastCountry;?></strong> on <strong><?php echo date('l d F Y h:m:s',strtotime($lastTimeAccessed));?> NZ time</strong>.<br />
																							<a href="statistics.php">Click here</a> to access full <strong>statistics page</strong>)
																						</div>
																						-->
																					</td>
																				</tr>
																			</table>
																		</div>
																	</td>
																</tr>
																<tr class="extra">
																	<td colspan="2">
																		<div align="left">
																			<ul>
																				<li>File allowed:<strong> JPG, JPEG, PNG, PPM, MPO</strong>, large images will be automatically <strong>resized</strong> to the <strong>above width</strong>.</li>
																				<li>If you only change parameters, <strong>click Submit </strong>again to re-calculate the result.</li>
																				<li><a href="../web/scene_gallery.php"><strong>Click here</strong></a> to access the gallery page, or check <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/web/demo.php"><strong>our demo</strong></a> page.</li>
																			</ul>
																			<p id="tempoWaiting" style=" text-align:center;display:none;background:url(upload_stereo/processGoal.jpg); background-repeat:no-repeat; background-position:center; text-align:center;"><img src="../images/waiting.gif" width="640" height="480"></p>
																		</div>
																	</td>
																</tr>
																	<?php if($_SERVER['REQUEST_METHOD']!="POST")
																	{ 
																	?>
																<tr>
																	<td colspan="4"><br />
																		Below is the previously processed dataset, <strong>hover over to see its 3D scene</strong>.<br />
																		
																		<?php echo $parameterReturned; ?><br />
																		<a href="3dNav/" target="_blank">Click to view it in an external page?</a>
																	</td>
																</tr>
																<a name="results" id="results"></a>
<!-- Debut fenetre image 3D ---------------------------------------->	
																<tr id="cached_images" class="extra">
																	<td colspan="2" align="center" valign="top">
																		<table width="100%" border="1" cellspacing="1" cellpadding="1">
																			<tr> 
																				<td colspan="4" height="810">
																					<?php
																						clearstatcache();
																						if(file_exists("upload_stereo/shareResults.jpg")){
																					?>
																					
																					<iframe id="reversedLeftImage" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/quick_stereo/3dNav" height="800" width="550" class="displayResult"></iframe>
																					
																					

																					<iframe id="WebGL" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/web/webgl/sceneGLTrans.php" height="650" width="600" class="displayResult" style="border:hidden;"></iframe>
																					
																				</td>
																				<?php } else { ?>
																						<h2 class="style1">No cached images found, <br />
																						server is maybe busy processing some images.<br />
																						You can upload new images to the queue.</h2>
																					<?php } ?>
																			</tr>
<!-- Fin fenetre image 3D -->	
<!-- Download files at the bottom of the page -->
																			<tr>
																				<td colspan="4">
																					<!-- Function in quick_stereo/dbc.php -->
																					<?php echo returnDownloadMaterials(); ?>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
																	<?php
																	}
																	clearstatcache();
																	?>
															</table>
														</td>
													</tr>
												</table>
											</form>
										</td>
									</tr>
									<?php
									  if($upload_success){
									  $randomNumber = rand(1,999999);
									?>
									<tr style="height:100%;">
										<td bgcolor="<?php echo $background; ?>">
										<span id="3dNavPanel"></span>
											<div align="center" id="result_pad" style="height:100%">
												  <p>&nbsp;</p>
												<h1 align="center" style="color:#000; background:#CCCCCC; border:medium; border-color:#000">Images received, stereo reconstruction is processing, <br />
												  estimating total waiting time...&nbsp;</h1>
												</p>
												  <p id="tempoWaiting" style=" text-align:center;background:url(upload_stereo/processGoal.jpg); background-repeat:no-repeat; background-position:center; text-align:center;"><img class="intermediatedResult" src="../images/waiting.gif"></p>
											</div>
											<script language="JavaScript" type="text/javascript">
												var wooYayIntervalId = 0; var counting = 0;
												document.getElementById('submitButton').disabled='disabled';
												scroll(0,500);
												var maxTime = 44; // 69
												var algorithmChosen = document.getElementById('algorithm').value;
												var imageResizeTo = document.getElementById('resizeTo').value;

												function callMySelf(){
													if(document.getElementById('ShareButton')){
														clearInterval(wooYayIntervalId);
														wooYayIntervalId = null;
														document.getElementById('submitButton').disabled='';
														document.getElementById('minDisp').value=document.getElementById('minDispHidden').value;
														document.getElementById('maxDisp').value=document.getElementById('maxDispHidden').value;
													}
													else
													{
														ajaxLoader("read_directory.php?imageResizeTo="+imageResizeTo+"&algorithm="+algorithmChosen+"&maxTime="+maxTime+"&number=<?php echo $randomNumber; ?>&counter="+counting, "result_pad");
													}
													counting++;
												}
												wooYayIntervalId = setInterval("callMySelf()",5000);
											</script>
										</td>
									</tr>
									<tr>
									<?php
										}
										else{
									?>
									</tr>

									<tr id="extraQuickStereoPart">
										<td>
											<table width="100%" border="0" cellspacing="2" cellpadding="0">
												<tr>
													<td valign="top">
														<!--
														<iframe src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/imageProcessing" height="800" width="600"></iframe>
														-->
													</td>
												</tr>
											</table>
										</td>
									</tr>
								<?php } ?>
								</table>
							</td>
						</tr>
						<tr>
							<td class="bodyText"></td>
						</tr>
					</table><br />
				&nbsp;<br />	
				<img src="mm_spacer.gif" alt="" width="50" height="1" border="0" /><br />
				&nbsp;<br />
				</td>
				<td valign="top" bgcolor="#444" class="extra">
					<table width="0" border="0" align="right" cellpadding="0" cellspacing="0" id="leftcol2">
						<?php
							include_once("../web/right_panel.php");
							echo returnRightPanel();
						?>
					</table>
				</td>
			</tr>
			<?php
				include_once("../web/footer.php");
				echo footerControl();
			?>
		</table>
		<div align="center">
			<?php
				include_once("../web/footer.php");
				echo returnFooter();
			?>
		</div>
	</body>


	<script>
		<?php
			if($_GET["buttonReprocess"]!="" && $_GET["rerunLocation"]!="" && $_GET["algorithm"]!="" ){
				$_SESSION["reProcessedURL"] = $_GET["rerunLocation"];
				copy($_GET["rerunLocation"]."_l.jpg", "upload_stereo/left");
				copy($_GET["rerunLocation"]."_r.jpg", "upload_stereo/right");
		?>
		
			var val = "<?php echo $_GET["algorithm"]; ?>", sel = document.getElementById('algorithm');
			for(var i, j = 0; i = sel.options[j]; j++) {
				if(i.value == val) {
					sel.selectedIndex = j;
					break;
				}
			}
			document.getElementById('submitButton').click();
			
		<?php
		}
		?>

		<?php
			if($_GET["buttonReprocess"]!="" && $_GET["autoSideBySide"]!=""){
				if($_SESSION["uncalibrated"] == "true") {

					copy("upload_stereo/right", "upload_stereo/temp");
					copy("upload_stereo/left", "upload_stereo/right");
					copy("upload_stereo/temp", "upload_stereo/left");
					/**/
				}
				else{
					copy("../calibrate_minoru/save_data/".$_SESSION["username"]."_".$_SESSION["randome_generated_number"]."_left.jpg", "upload_stereo/left");
					copy("../calibrate_minoru/save_data/".$_SESSION["username"]."_".$_SESSION["randome_generated_number"]."_right.jpg", "upload_stereo/right");
					?>
					document.getElementById('rectification').selectedIndex = 1;
					<?php
				}
		?>
		
			document.getElementById('submitButton').click();
			
		<?php
		}
		?>
	</script>
</html>
