<?php
session_start();
unset($_SESSION["reProcessedURL"]);
include "../quick_stereo/dbc.php";
include "menu.php";

$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
//130.216.37.27

$directory = $_GET["d"];
$directory = str_replace("./../", "../",$directory);
//echo $directory; return;
if(strlen($directory)< 3){
	$directory = '../stereogallery';
}
$smallStatistics = "Gallery contains totally ";
$sql = "select count(*) as totalNo from cs_stereo_scene_files where name like '%MPO'";
$query = $dbc->prepare($sql);
$query->execute();
if($rowx = $query->fetch(PDO::FETCH_ASSOC)){
	$smallStatistics=$smallStatistics.$rowx["totalNo"]." stereo images, grouped in ";
}


$sql = "select  count( distinct parent ) as total from cs_stereo_scene_files
where parent <> ''
and  parent <> '../stereogallery'";
$query = $dbc->prepare($sql);
$query->execute();
if($rowx = $query->fetch(PDO::FETCH_ASSOC)){
	$smallStatistics=$smallStatistics.($rowx["total"]-1)." categories.";
}


?>

<html xmlns="https://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Stereo Scene Gallery</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="mm_health_nutr.css" type="text/css" />
<link rel="stylesheet" href="../quick_stereo/css/main.css" type="text/css" />
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
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <?php
  include_once("header.php");
  returnHeader();
  ?>


  <tr>
    <td colspan="7" bgcolor="#444" ><img src="mm_spacer.gif" alt="line decor" width="4" height="3" border="0" /></td>
  </tr>

  <tr class="extra">
    <td colspan="7" bgcolor="#fff"><img src="mm_spacer.gif" alt="line decor" width="4" height="3" border="0" /></td>
  </tr>

  <tr bgcolor="#444" class="extra">
  	<td colspan="7" id="dateformat" height="20"><?php
    include_once("../web/horizontalMenu.php");
    //echo returnMenu();
  ?></td>
  </tr>
  <tr class="extra">
    <td colspan="7" bgcolor="#fff" ><img src="mm_spacer.gif" alt="line decor" width="4" height="3" border="0" /></td>
  </tr>

  <tr class="extra">
    <td colspan="7" bgcolor="#5C743D"><img src="mm_spacer.gif" alt="" width="1" height="2" border="0" /></td>
  </tr>

 <tr>
    <td width="0%" valign="top" bgcolor="#444">
	<table border="0" cellspacing="0" cellpadding="0" width="100%" id="navigation">
        <?php
        //include_once("menu.php");
        //returnMenu();
        ?>
      </table>
 	 <br />
  	&nbsp;<br />
  	&nbsp;<br />
  	&nbsp;<br /> 	</td>
    <td width="50"><img src="mm_spacer.gif" alt="" width="50" height="1" border="0" /></td>
    <td colspan="4" valign="top"><img src="mm_spacer.gif" alt="" width="305" height="1" border="0" />
	<table border="0" cellspacing="0" cellpadding="0" width="100%" onMouseMove="
    if(document.getElementById('tableControl')){
    	if(document.getElementById('tableControl').style.display=='none'){
    		document.location=document.getElementById('nextImage').value;
    	}
    }
    ">
       	<tr>
          <td class="bodyText"><center>
            <table width="90%" border="0">
            <?php
            $aboveDir = substr($directory, 0, strrpos($directory, "/"));

			if(!isset($_SESSION["Cross-eye"])){
				$_SESSION["Cross-eye"] = 'false';
			}
			if(!isset($_SESSION["Download"])){
				$_SESSION["Download"] = 'true';
			}

			if(!isset($_SESSION["Parallel-eye"])){
				$_SESSION["Parallel-eye"] = 'false';
			}
			if(!isset($_SESSION["Anaglyphs"])){
				$_SESSION["Anaglyphs"] = 'false';
			}
			if(!isset($_SESSION["Autostereogram"])){
				$_SESSION["Autostereogram"] = 'false';
			}
			if(!isset($_SESSION["WebGL"])){
				$_SESSION["WebGL"] = 'true';
			}
			if(!isset($_SESSION["Stereo_matching_results"])){
				$_SESSION["Stereo_matching_results"] = 'true';
			}
			if(!isset($_SESSION["Statistical_results"]))
			{
				$_SESSION["Statistical_results"] = 'false';
			}
			if($_SESSION["Control"] != 'false')
			{
				$_SESSION["Control"] = 'true';
			}

			?>
<script language="JavaScript" type="text/javascript">
//--------------- LOCALIZEABLE GLOBALS ---------------
function chooseToView(value){
	ajaxLoader('sceneSetSession.php?set='+value+'&value='+document.getElementById(value).checked,'');
	//window.location.reload();
}
</script>
              <tr>
                <td colspan="3">
                  <table width="100%" border="1" class="tableControl">
                    <tr>
                      <td rowspan="3">Stereo Scene Gallery.<br />
                        Choose what you want to see: </td>
                      <td>Optical View</td>
                      <td><input type="checkbox" name="Cross-eye" id="Cross-eye" value="1" onChange="chooseToView('Cross-eye')" <?php if($_SESSION["Cross-eye"] == 'true') echo 'checked="checked"'; ?>/>
                        Cross-eye |
                          <input type="checkbox" name="Parallel-eye" id="Parallel-eye" value="1" onChange="chooseToView('Parallel-eye')"  <?php if($_SESSION["Parallel-eye"] == 'true') echo 'checked="checked"'; ?> />
                          Parallel-eye |
                          <input type="checkbox" name="Anaglyphs" id="Anaglyphs" value="1" onChange="chooseToView('Anaglyphs')"  <?php if($_SESSION["Anaglyphs"] == 'true') echo 'checked="checked"'; ?> />
                          Anaglyphs |
                          <input type="checkbox" name="Autostereogram" id="Autostereogram" value="1" onChange="chooseToView('Autostereogram')"  <?php if($_SESSION["Autostereogram"] == 'true') echo 'checked="checked"'; ?> />
                          Cross Autostereogram |
                          <input type="checkbox" name="P_Autostereogram" id="P_Autostereogram" value="1" onChange="chooseToView('P_Autostereogram')"  <?php if($_SESSION["P_Autostereogram"] == 'true') echo 'checked="checked"'; ?> />
                        Parallel Autostereogram | </td>
                      <td rowspan="3"><input type="button" name="button" id="button" value="  Apply  " onClick="this.value='Yep...';window.location='?d=<?php echo $directory; ?>';" /></td>
                    </tr>
                    <tr>
                      <td>Stereo Vision</td>
                      <td><input type="checkbox" name="Stereo_matching_results" id="Stereo_matching_results" value="1" onChange="chooseToView('Stereo_matching_results')"  <?php if($_SESSION["Stereo_matching_results"] == 'true') echo 'checked="checked"'; ?> />
                        Stereo matching results |
                        <!--
                        <input type="checkbox" name="Statistical_results" id="Statistical_results" value="1" onchange="chooseToView('Statistical_results')"  <?php if($_SESSION["Statistical_results"] == 'true') echo 'checked="checked"'; ?> />
                        Statistical results |

                        <input type="checkbox" name="Download" id="Download" value="1" onchange="chooseToView('Download');"  <?php if($_SESSION["Download"] == 'true') echo 'checked="checked"'; ?> />
Download centre |
						-->
<input type="checkbox" name="Control" id="Control" value="1" onChange="chooseToView('Control');"  <?php if($_SESSION["Control"] == 'true') echo 'checked="checked"'; ?> />
Control Panel | </td>
                      </tr>
                    <tr>
                      <td>3D display</td>
                      <td>
  <input type="checkbox" name="WebGL" id="WebGL" value="1" onChange="chooseToView('WebGL');"  <?php if($_SESSION["WebGL"] == 'true') echo 'checked="checked"'; ?> />
WebGL display |
<input type="checkbox" name="3DNav" id="3DNav" value="1" onChange="chooseToView('3DNav')"  <?php if($_SESSION["3DNav"] == 'true') echo 'checked="checked"'; ?> />
HTML5 3D Navigation|
<input type="checkbox" name="Java3D" id="Java3D" value="1" onChange="chooseToView('Java3D');"  <?php if($_SESSION["Java3D"] == 'true') echo 'checked="checked"'; ?> />
Java3D display |
<input type="checkbox" name="Shadow" id="Shadow" value="1" onChange="alert('Coming soon!');//chooseToView('Shadow');"  <?php if($_SESSION["Shadow"] == 'true') echo 'checked="checked"'; ?> />
Shadow display | </td>
                      </tr>
                  </table></td>
                </tr>
<?php
//getting parent, previous and last dataset

$sql = "select * from cs_stereo_scene_files where name like '%MPO'
and parent = (select parent from cs_stereo_scene_files where name like '%$directory%' limit 1)
and id > (select max(id) from cs_stereo_scene_files where name like '%$directory%')
order by id limit 1";
$query = $dbc->prepare($sql);
$query->execute();
if($row = $query->fetch(PDO::FETCH_ASSOC)){
	$lastDirectory = str_replace(".MPO", "",$row["name"]);
}


$sql = "select * from cs_stereo_scene_files where name like '%MPO'
and parent = (select parent from cs_stereo_scene_files where name like '%$directory%' limit 1)
and id < (select min(id) from cs_stereo_scene_files where name like '%$directory%')
order by id desc limit 1";
$query = $dbc->prepare($sql);
$query->execute();
if($row = $query->fetch(PDO::FETCH_ASSOC)){
	$nextDirectory = str_replace(".MPO", "",$row["name"]);
}

?>

              <tr>
                <td width="33%"><?php if($lastDirectory != $directory) { ?><a href="scene_gallery.php?d=<?php echo $lastDirectory; ?>">&lt;&lt;&lt; Last image:</a> <?php } ?></td>
                <td><a href="scene_gallery.php?d=<?php echo $aboveDir; ?>">Back to parent directory</a></td>
                <td width="33%"><?php if($nextDirectory != $directory && file_exists($nextDirectory.".MPO")) { ?><a href="scene_gallery.php?d=<?php echo $nextDirectory; ?>">Next image &gt;&gt;&gt;
                  <input name="nextImage" type="hidden" id="nextImage" value="scene_gallery.php?d=<?php echo $nextDirectory; ?>" />
                </a> <?php } ?></td>
              </tr>
              </table>
            </center>
            <span class="Scene_Gallery">
            <ul>
            <?php
			// show outside categories
			$sql = "select distinct name, parent from cs_stereo_scene_files where parent = '".$directory."'  order by name";
			//echo $sql;
			$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
			while($row = mysql_fetch_assoc($result)) {
				$dir = $row["name"];
				//var_dump($nextDirectory);
//return;
				if(str_replace(".", "", str_replace("../stereogallery/","",$dir)) == str_replace("../stereogallery/","",$dir))
				{
					$dir = str_replace(".MPO", "", $dir);
					//echo '<li><a href="?d='.$dir.'"><img src="'.str_replace(".MPO", "_thumb.jpg", $dir).'" />'.$dir.'</img></a></li>';
					?>
                    <li>
                    <a href="?d=<?php echo $dir; ?>">
                    <table width="200" border="1" onMouseOver="this.style.background='#CF9'" onMouseOut="this.style.background=''">
                      <tr>
                        <td><img src="<?php echo $dir; ?>_thumb.jpg" /></td>
                      </tr>
                      <tr>
                      <?php
					  	$sqlx = "select count(*) as totalNo from cs_stereo_scene_files where name like '%MPO' and parent like '%$dir%'";
						$query = $dbc->prepare($sqlx);
						$query->execute();
						if($rowx = $query->fetch(PDO::FETCH_ASSOC)){
							$totalNo=$rowx["totalNo"];
						}
					  ?>
                        <td><?php echo str_replace("../stereogallery/","",$dir); ?> (<?php echo $totalNo; ?> images)</td>
                      </tr>
                    </table>
                    </a>					</li>
                    <?php
				}
			}
			$sql = "select * from cs_stereo_scene_files where parent = '".$directory."' and name like '%.MPO' order by id desc";
			//echo $sql;
			$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
			while($row = mysql_fetch_assoc($result)) {
				$dir = $row["name"];	$dir = str_replace(".MPO", "", $dir);
				if(!file_exists($dir.".MPO")){
					//echo "missing";
					mysql_query("delete FROM `cs_stereo_scene_files` WHERE name like '%".$dir."%'");
				}
				else
				{
					$row["private"] = 0;	//Minh temporarily modified to clear the private mode
					?>
                  <li id="list_<?php echo $dir; ?>">
                    <table width="200" border="1" onMouseOver="this.style.background='#CF9'" onMouseOut="this.style.background=''">
                      <tr>
                        <td bgcolor="black"><a href="<?php if(!$row["private"] || $_COOKIE["user_name"]<>'') echo "?d=".$dir; else echo "javascript:alert('The image is in private mode, please contact administrator for more detail.');"; ?>">
                         <div style="position: relative; left: 0; top: 0;">
                         <?php
						 if($row["private"] && $_COOKIE["user_name"]==''){
						 ?>
                         <img src="<?php echo $dir; ?>_thumb.jpg" style="position: relative; top: 0; left: 0; opacity:0.2;"/>
  						 <img src="https://www.ivs.auckland.ac.nz/web/private.png" style="position: absolute; top: -10px; left:-10px;"/>
                         <?php } else {?>
                         <img src="<?php echo $dir; ?>_thumb.jpg" style="position: relative; top: 0; left: 0;"/>
                         <?php } ?>
                         </div>
                        </a></td>
                      </tr>
                      <tr>
                        <td><?php echo str_replace("../stereogallery/","",$dir).$showText; ?>
                        	<!--
                            <br />
                            <input type="button" name="deleteButton" id="deleteButton" value="Delete" onclick="
                            document.getElementById('list_<?php echo $dir; ?>').style.display='none';
                            -->                            </td>
                      </tr>
                    </table>
              </li>
                    <?php
				}
			}
			?>

            <?php
			if(file_exists($directory.".MPO")){
				$sql = "select * from cs_stereo_scene_files where name = '".$directory.".MPO' and private = 0";
				//echo $sql;
				$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
				if(mysql_num_rows($result) == 0 && $_COOKIE["user_name"]=='') {
				//header( 'Location: https://www.ivs.auckland.ac.nz/quick_stereo' ) ;
				}
				?>
                <table width="95%" border="1" class="tableControl" id="tableControl">
                <?php
					if($_SESSION["Control"] == 'true'){
				  ?>
                  <tr>
                    <td colspan="2"><strong>Control Centre </strong></td>
                  </tr>
                  <tr>
                    <td colspan="2" bgcolor="#FFFF66">
                    Please help us selecting the correct category
                      <select name="moveCategories" id="moveCategories">
                    <?php
					  $sql = "select * from cs_stereo_scene_categories order by name";
					  $result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
					  while($row = mysql_fetch_assoc($result)){
						    ?>
                            <option value="<?php echo $row["name"]; ?>"><?php echo $row["name"]; ?></option>
                            <?php
					  }
					?>
                    <!--<option value="../stereogallery/delete">../stereogallery/delete</option>-->
                    </select>
                    <input type="button" name="controlbutton" id="controlbutton" value="Apply control" onClick="
                    //if(confirm('Are you sure to move this dataset to '+document.getElementById('moveCategories').value)){
                    	ajaxLoader('moveDataset.php?from='+'<?php echo $directory; ?>'+'&to='+document.getElementById('moveCategories').value,'highlightSmall');
                        if(document.getElementById('highlightSmall'))
                        	document.getElementById('highlightSmall').innerHTML='Sending your request to server, please wait...';
                        if(document.getElementById('tableControl'))
                        	document.getElementById('tableControl').style.display='none';
                    //}
                    " />
                    or
                    <input type="button" name="deleteButton2" id="deleteButton2" value="Delete this" onClick="

                    if('<?php echo $_COOKIE["user_name"]; ?>'==''){
                    	if(confirm('Sorry, this function is for Administrator only, do you want to login now?')){
                        	document.location='login.php';
                        }
                    }
                    else
                    {
                        if(confirm('Are you sure to DELETE this dataset?')){
                            ajaxLoader('moveDataset.php?from='+'<?php echo $directory; ?>'+'&to=../stereogallery/delete','highlightSmall');
                            if(document.getElementById('highlightSmall'))
                                document.getElementById('highlightSmall').innerHTML='Sending your request to server, please wait...';
                            if(document.getElementById('tableControl'))
                                document.getElementById('tableControl').style.display='none';
                        }
                    }
                    " /></td>
                  </tr>
                  <?php } ?>

                  <?php
					if($_SESSION["Cross-eye"] == 'true'){
				  ?>
                  <tr>
                    <td colspan="2">Cross-eyed Image</td>
                  </tr>
                  <tr bgcolor="#000000">
                    <td colspan="2"><img id="fullDisplay" src="<?php echo $directory."_crossEye.jpg"; ?>" width="100%" /></td>
                  </tr>
                  <?php } ?>
                  <?php
					if($_SESSION["Parallel-eye"] == 'true'){
				  ?>
                  <tr>
                    <td colspan="2">Parallel-eyed Image</td>
                  </tr>
                  <tr bgcolor="#000000">
                    <td colspan="2"><img id="fullDisplay" src="<?php echo $directory."_parallelEye.jpg"; ?>" width="100%" /></td>
                  </tr>
                  <?php } ?>
                  <?php
					if($_SESSION["Anaglyphs"] == 'true' && $_SESSION["Autostereogram"] == 'true'){
				  ?>
                  <tr>
                    <td width="50%">Anaglyphic image</td>
                    <td width="50%">Auto-stereogram image</td>
                  </tr>
                  <tr bgcolor="#000000">
                    <td width="50%"><img src="<?php if(file_exists($directory."_anaglyph.jpg")) echo $directory."_anaglyph.jpg"; else echo "../stereogallery/commingsoon.jpg"; ?>" width="100%" /></td>
                    <td width="50%"><img src="<?php if(file_exists($directory."_stereogram.jpg")) echo $directory."_stereogram.jpg"; else echo "../stereogallery/commingsoon.jpg"; ?>" width="100%" /></td>
                  </tr>
                  <?php } ?>
                  <?php
					if($_SESSION["Anaglyphs"] == 'true' && $_SESSION["Autostereogram"] != 'true'){
				  ?>
                  <tr>
                    <td colspan="2">Anaglyphic Image</td>
                  </tr>
                  <tr bgcolor="#000000">
                    <td colspan="2"><img id="fullDisplay" src="<?php echo $directory."_anaglyph.jpg"; ?>" width="100%" /></td>
                  </tr>
                  <?php } ?>

                  <?php
					if($_SESSION["WebGL"] == 'true'){
				  ?>
                  <tr>
                    <td colspan="2"><strong>WebGL 3D Navigation (Firefox/Chrome recommended)</strong></td>
                  </tr>
                  <tr bgcolor="#000000">
                    <td colspan="2"><iframe id="WebGL" src="http://www.ivs.auckland.ac.nz/web/webgl/sceneGLTrans.php?location=<?php echo $directory; ?>" height="650" width="870"></iframe></td>
                  </tr>
                  <?php } ?>

                  <?php
					if($_SESSION["3DNav"] == 'true'){
				  ?>
                  <tr>
                    <td colspan="2"><strong>HTML5 3D Navigation (Firefox recommended)</strong></td>
                  </tr>
                  <tr bgcolor="#000000">
                    <td colspan="2"><iframe id="3dNavPanel" src="http://www.ivs.auckland.ac.nz/quick_stereo/3d/3dSceneOrbit.php?location=<?php echo $directory; ?>" height="600" width="800"></iframe></td>
                  </tr>
                  <?php } ?>
                  <?php
					if($_SESSION["Shadow"] == 'true'){
				  ?>
                  <tr>
                    <td colspan="2"><strong>Shadow on 2D Image (Click anywhere on the picture)</strong></td>
                  </tr>
                  <tr bgcolor="#000000">
                    <td colspan="2"><iframe id="shadow" src="http://www.ivs.auckland.ac.nz/web/scene_shadow.php?d=<?php echo $directory; ?>" height="650" width="820"></iframe></td>
                  </tr>
                  <?php } ?>

                  <?php
					if($_SESSION["Java3D"] == 'true'){
				  ?>
                  <tr>
                    <td colspan="2"><strong>Java 3D</strong></td>
                  </tr>
                  <tr bgcolor="#000000">
                    <td colspan="2"><iframe id="java3D" src="http://www.ivs.auckland.ac.nz/quick_stereo/viewSceneJava3D.php?location=<?php echo $directory; ?>" height="650" width="820"></iframe></td>
                  </tr>
                  <?php } ?>

                  <?php
					if($_SESSION["Anaglyphs"] != 'true' && $_SESSION["Autostereogram"] == 'true'){
				  ?>
                  <tr>
                    <td colspan="2">Autostereogram Image</td>
                  </tr>
                  <tr bgcolor="#000000">
                    <td colspan="2"><img id="fullDisplay" src="<?php echo $directory."_stereogram.jpg"; ?>" /></td>
                  </tr>
                  <?php } ?>
                  <?php
					if($_SESSION["P_Autostereogram"] == 'true'){
				  ?>
                  <tr>
                    <td colspan="2">Autostereogram Image</td>
                  </tr>
                  <tr bgcolor="#000000">
                    <td colspan="2"><img id="fullDisplay" src="<?php echo $directory."_p_stereogram.jpg"; ?>" /></td>
                  </tr>
                  <?php } ?>


                  <?php
					if($_SESSION["Stereo_matching_results"] == 'true'){
					$arrayToDisplay = array();$arrayToDisplayValue = array();
					$counterX = 0;

					$arrayToDisplay[$counterX] = "Reference Image";
					$arrayToDisplayValue[$arrayToDisplay[$counterX]] = "l_resized";
					$counterX++;

					/*$arrayToDisplay[$counterX] = "Colour Disparity map";
					$arrayToDisplayValue[$arrayToDisplay[$counterX]] = "Colour_CD";
					$counterX++;

					$arrayToDisplay[$counterX] = "Default Disparity map";
					$arrayToDisplayValue[$arrayToDisplay[$counterX]] = "CD";
					$counterX++;*/

					$arrayToDisplay[$counterX] = "BM+DP (2 iterations) Disparity map";
					$arrayToDisplayValue[$arrayToDisplay[$counterX]] = "BMDP";
					$counterX++;

					$arrayToDisplay[$counterX] = "BM+DP (1 iteration) Disparity map";
					$arrayToDisplayValue[$arrayToDisplay[$counterX]] = "BMDP1";
					$counterX++;

					$arrayToDisplay[$counterX] = "GC+DP Disparity map";
					$arrayToDisplayValue[$arrayToDisplay[$counterX]] = "GCDP";
					$counterX++;

					$arrayToDisplay[$counterX] = "Graph Cuts Disparity map";
					$arrayToDisplayValue[$arrayToDisplay[$counterX]] = "GC";
					$counterX++;

					$arrayToDisplay[$counterX] = "Belief Propagation (1D) Disparity map";
					$arrayToDisplayValue[$arrayToDisplay[$counterX]] = "CBP";
					$counterX++;

					$arrayToDisplay[$counterX] = "Block Matching Disparity map";
					$arrayToDisplayValue[$arrayToDisplay[$counterX]] = "BM";
					$counterX++;

					$arrayToDisplay[$counterX] = "SAD Disparity map";
					$arrayToDisplayValue[$arrayToDisplay[$counterX]] = "SAD";
					$counterX++;

					$arrayToDisplay[$counterX] = "Dynamic Programming Disparity map";
					$arrayToDisplayValue[$arrayToDisplay[$counterX]] = "DP";
					$counterX++;

					$arrayToDisplay[$counterX] = "Coloured Symmetric Dynamic Programming Disparity map";
					$arrayToDisplayValue[$arrayToDisplay[$counterX]] = "CSDPS";
					$counterX++;

				  ?>
                  <tr>
                    <td colspan="2">3D Stereo Reconstruction</td>
                  </tr>
                  <tr>
                    <td colspan="2" class="highlightImage">
                    <?php
					for($i = 0; $i < sizeof($arrayToDisplay); $i++){
					if(file_exists($directory."_".$arrayToDisplayValue[$arrayToDisplay[$i]].".jpg")){
					?>
                    <div class="floatDepthMap">
                    <a href=""><?php echo $arrayToDisplay[$i]; ?></a>
                    <br/>
                    <img src="<?php echo $directory."_".$arrayToDisplayValue[$arrayToDisplay[$i]].".jpg"; ?>" width="100%" /><hr/> </div>
                    <?php } } ?>

                    <div class="floatDepthMap">
                      <a href="">Request reprocess Stereo Matching</a>
                      <p>
                   	  <form action="../quick_stereo/index.php" method="get">
                        <img src="<?php echo $directory."_thumb.jpg"; ?>" />
                        <br/>
                        re-process with
                        <br/>
                        <?php
						include('../quick_stereo/algorithmList.php');
						?>
                        <input type="hidden" name="rerunLocation" id="rerunLocation" value="<?php echo $directory; ?>" />
                      	<br />
                      	<input type="submit" name="buttonReprocess" id="buttonReprocess" value="Run this sample now" />
                   	  </form>
                        </p>
                    </div>                    </td>
                  </tr>
                  <?php } ?>


                  <?php
					         if($_SESSION["Download"] == 'true'){
				          ?>

                  <script>

                    var already_agree = false;

                    function agree()
                    {
                      if(already_agree == false)
                      {
                        var agree = confirm("By downloading you have to agree to cite the paper 'Recent Advances in Online Stereo Web Application'. Latex citation available by clicking on 'Bibtex'");

                        if(agree == true)
                        {

                          var directory = eval('(<?php echo json_encode($directory)?>)');

                          alert("You can now download");

                          document.getElementById("resizedLeft").href = "download.php?url="+directory+"&request=left_raw_resized";
                          document.getElementById("resizedRight").href = "download.php?url="+directory+"&request=right_raw_resized";

                          document.getElementById("anaglyph").href = "download.php?url="+directory+"&request=anaglyph";
                          document.getElementById("crossEyed").href = "download.php?url="+directory+"&request=crossEye";
                          document.getElementById("parallelEyed").href = "download.php?url="+directory+"&request=parallelEye";
                          document.getElementById("paralleled").href = "download.php?url="+directory+"&request=p_stereogram";
                          document.getElementById("autoStereogram").href = "download.php?url="+directory+"&request=autostereogram";

                          document.getElementById("matrix").href = "download.php?url="+directory+"&request=fundamental_matrix";
                          document.getElementById("disparityParameters").href = "download.php?url="+directory+"&request=returnParameters";

                          already_agree = true;
                        }
                        else if (agree == false)
                        {
                            alert("You have to agree.")
                        }
                      }
                    }

                    function text()
                    {
                      if(document.getElementById("text").style.display == 'none') document.getElementById("text").style.display = 'block';
                      else document.getElementById("text").style.display = 'none';
                    }
                  </script>

                  <tr>
                    <td colspan="4">
                    	<strong>Downloads: </strong><br/>
                      <ol>
                        
                          <p1 onclick="text()" id="bibtex">[Bibtex]</p1>
                          <code id="text" style="display:none"> @INPROCEEDINGS{webIvcnz11, title={Recent Advances in Online Stereo Web Application}, author={M. Nguyen and G. Gimel'farb and P. Delmas}, booktitle={Proceedings of the International Conference Image and Vision Computing New Zealand}, pages={411--416}, external={http://www.wpcentral.com/ie9-windows-phone-7-adobe-flash-demos-and-development-videos}, address = {Auckland, New Zealand}, keywords={3D object;shadow generation;stereo image;two-dimensional geometry;stereo image processing;}, month={Nov}, year={2011} }</code>
                        
                        <li id="download1" > <strong>Raw inputs:</strong> 
                          <a id="resizedLeft" onclick="agree()" >Left image resized</a>, 
                          <a id="resizedRight" onclick="agree()" >Right image resized</a> 
                        </li>
                        <li id="download2" > <strong>Optical views: </strong>
                          <a id="anaglyph" onclick="agree()" >Anaglyph</a>, 
                          <a id="crossEyed" onclick="agree()" >Cross Eyed pair</a>, 
                          <a id="parallelEyed" onclick="agree()" >Parallel Eyed pair</a>, 
                          <a id="paralleled" onclick="agree()" >paralleled_stereogram</a>, 
                          <a id="autoStereogram" onclick="agree()" >auto-stereogram</a>
                        </li>
                        <li id="download3" ><strong>Other Parameters: </strong>
                          <a id="matrix" onclick="agree()" >Fundamental matrix</a>, 
                          <a id="disparityParameters" onclick="agree()" >Disparity range parameters</a>
                        </li>
                      </ol>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="4"></td>
                  </tr>
                  <?php } ?>


                </table>
                <?php
			}
			?>
			<center><hr/><p><?php echo $smallStatistics; ?><br/><?php if(0 && $_SESSION["Stereo_matching_results"] == 'true') echo "Please help us decide which algorithm performs the best by clicking the best depthmap, thanks.";?></p><hr/></center>
          	</ul>
            </span>
          </td>
        </tr>

        <?php
		if($_SESSION["Statistical_results"] == 'true'){
		?>
        <tr>
          <td class="pageName"><div align="center">Statistics below, <a href="#" onClick="window.open( 'sceneStatistics.php', 'pop up Statistics', 'status = 0, height = 800, width = 850, resizable = 1, scrollbars=1' );">click here for live statistics</a>.</div></td>
		</tr>
		<tr>
		  <td class="bodyText"><center><?php include_once("../web/images/batch_statistics.html");?></center></td>
	    </tr>
        <?php } ?>
      </table>
	�
	<p><br />
	  &nbsp;<br />
	  <img src="mm_spacer.gif" alt="" width="50" height="1" border="0" /><br />
	  &nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p><br />
    </p></td>
    <td valign="top" bgcolor="#444"><table width="0" border="0" align="right" cellpadding="0" cellspacing="0" id="leftcol2">
      <?php
	 include_once("right_panel.php");
	 echo returnRightPanel();
	 ?>
    </table></td>
  </tr>
  <?php
	include_once("footer.php");
	echo footerControl();
  ?>
</table>
<?php
include_once("footer.php");
echo returnFooter();
?>
</body>
</html>
<?
deleteOldFiles('../quick_stereo/download/', 7);
//deleteOldFiles('../quick_stereo/upload_stereo/StoreResults/', 7);
?>