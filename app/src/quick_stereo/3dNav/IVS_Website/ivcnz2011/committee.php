<!-- HOME page -->
<?php 
include 'dbc.php'; //for database
require_once("../new_site_d/php/common.php"); //for header and footer
$ivs_location = "../new_site_d/";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<?php title();?>
		<link rel='stylesheet' type='text/css' href='<? echo $ivs_location; ?>css/common.css' />	
		<link rel='stylesheet' type='text/css' href='<? echo $ivs_location; ?>css/home.css' />
		<script type='text/javascript' src='<? echo $ivs_location; ?>js/util.js'></script>
		<script type='text/javascript' src='<? echo $ivs_location; ?>js/main_slideshow.js'></script>
<title>IVCNZ2011</title>
<style type="text/css">
<!--
.style1 {
	color: #0066FF
}
.style2 {color: #FF0000}
-->
</style>
</head>
	<body>
		<div id='container' style="min-height:0px;">
			<div id='wrapper'>
				
				<!-- Content -->
				<div id='mid_content_main'>
					<div id='div_ivcnz_main'>
						<div id='left_pad'>
						  <? require_once("side.php"); echo side(); ?>
				      </div>
						<div id='right_pad'>
                          <div style="text-align:center;">
						    <p align="center" style="margin-top: 0;"><img src="images/ivcnz2010.png" width="100%"></p>
						    <h1 align="center" class="style1">IVCNZ 2011</h1>
						    <h2 align="center">Twenty-sixth International Conference Image and Vision Computing New Zealand<br>
                            </h2>
						    <h1 align="center">November 29 - December 1, 2011, Auckland, New Zealand</h1>
						    <h1 style="margin-top:0px;"><span class="style2">Program Committee</span></h1>
                          </div>                         
                          <table width="100%" border="1" bgcolor="#FFFFFF">
                          <!--
                            <tr>
                              <td bgcolor="#CCCCCC"><strong>Conference Chair</strong></td>
                              <td bgcolor="#CCCCCC"><strong>Organisation</strong></td>
                              <td bgcolor="#CCCCCC"><strong>Country</strong></td>
                            </tr>
                            <tr>
                              <td><a href="mailto:p.delmas@auckland.ac.nz">Patrice Delmas</a></td>
                              <td>University of Auckland</td>
                              <td><div align="center">
                                <div class="flag flag-<? echo strtolower('NZ'); ?>" alt="NZ" style="width:16px; height:11px;" /></div></td>
                            </tr>
                            <tr>
                              <td><a href="mailto:burkhard@cs.auckland.ac.nz">Burkhard Wuensche</a></td>
                              <td>University of Auckland</td>
                              <td><div align="center">
                                <div class="flag flag-<? echo strtolower('NZ'); ?>" alt="NZ" style="width:16px; height:11px;" /></div></td>
                            </tr>                            
                            <tr>
                              <td bgcolor="#CCCCCC"><strong>Scientific program chair</strong></td>
                              <td bgcolor="#CCCCCC"><strong>Organisation</strong></td>
                              <td bgcolor="#CCCCCC"><strong>Country</strong></td>
                            </tr>
                            <tr>
                              <td><a href="mailto:g.gimelfarb@auckland.ac.nz">Assoc-Prof Georgy Gimel'farb</a></td>
                              <td>University of Auckland</td>
                              <td><div align="center">
                                <div class="flag flag-<? echo strtolower('NZ'); ?>" alt="NZ" style="width:16px; height:11px;" /></div></td>
                            </tr>
                            <tr>
                              <td bgcolor="#CCCCCC"><strong>Technical Support</strong></td>
                              <td bgcolor="#CCCCCC"><strong>Organisation</strong></td>
                              <td bgcolor="#CCCCCC"><strong>Country</strong></td>
                            </tr>
                            <tr>
                              <td><a href="mailto:mngu012@aucklanduni.ac.nz">Minh Nguyen</a></td>
                              <td>University of Auckland</td>
                              <td><div align="center">
                                <div class="flag flag-<? echo strtolower('NZ'); ?>" alt="NZ" style="width:16px; height:11px;" /></div></td>
                            </tr>
                            <tr>
                              <td><a href="mailto:ycha171@aucklanduni.ac.nz">Edwin Chan</a></td>
                              <td>University of Auckland</td>
                              <td><div align="center">
                                <div class="flag flag-<? echo strtolower('NZ'); ?>" alt="NZ" style="width:16px; height:11px;" /></div></td>
                            </tr>
                            <tr>
                              <td><a href="mailto:gastelum.strozzi@gmail.com">Alfonso Gastelum Strozzi </a></td>
                              <td>University of Auckland</td>
                              <td><div align="center">
                                <div class="flag flag-<? echo strtolower('MX'); ?>" alt="MX" style="width:16px; height:11px;" /></div></td>
                            </tr>
                            -->
                            <tr>
                              <td bgcolor="#CCCCCC"><strong>Programme Committee</strong></td>
                              <td bgcolor="#CCCCCC"><strong>Organisation</strong></td>
                              <td bgcolor="#CCCCCC"><strong>Country</strong></td>
                            </tr>
                            <?
							$sql = "select distinct username as idName, persons.* from persons where status like '%pc%' and deleted <> '1' order by name";
							$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
							$previoususername = '';
							while($row = mysql_fetch_assoc($result)){
							if($previoususername != $row["username"]){
							?>
                            <tr>
                              <td><? echo $row["name"]; if($row["firstname"] != "") echo ", ".$row["firstname"]; ?></td>
                              <td><? echo $row["organisation2"]." ".$row["organisation"]; ?></td>
                              <td><div align="center"><div class="flag flag-<? echo strtolower($row["country"]); ?>" alt="<? echo $row["country"]; ?>" style="width:16px; height:11px;" />    <? //echo $row["country"]; ?></div></td>
                            </tr>
                            <? 
							$previoususername = $row["username"];
							}
							
							} ?>
                          </table>
                          <p align="center">&nbsp;</p>
						  <? echo importantDate();?>
						  <hr>
						  <h3 align="center">Contact:</h3>
						  <p align="center">IVCNZ 2011 Convenors
						  <br>
						  <a href="mailto:IVCNZ2011@cs.auckland.ac.nz">IVCNZ2011@cs.auckland.ac.nz</a></p>						  
				      </div>                        
                        <div>
                        <p><img src="IVCNZ2011_files/image002.jpg" width="100%"></p>
                        </div>               				
				  </div>
			  </div>	
				
			</div>
		</div>
	</body>
</html>