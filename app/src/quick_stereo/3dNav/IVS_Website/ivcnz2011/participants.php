<!-- HOME page -->
<?php 
//include("../php_script/password_protect.php");
require_once("../new_site_d/php/common.php"); //for header and footer
$ivs_location = "../new_site_d/";
include 'dbc.php'; //for database

$sql = "SELECT * FROM `persons`, participants 
WHERE persons.id = participants.personID
and ((participants.total = 0 and participants.payamount=0)
or
(participants.total <> 0 and participants.payamount<>0)
)
order by name
";

//oral
$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<?php title();?>
		<link rel='stylesheet' type='text/css' href='<? echo $ivs_location; ?>css/common.css' />	
		<link rel='stylesheet' type='text/css' href='<? echo $ivs_location; ?>css/home.css' />
		<script type='text/javascript' src='<? echo $ivs_location; ?>js/util.js'></script>
		<script type='text/javascript' src='<? echo $ivs_location; ?>js/main_slideshow.js'></script>
        <style type="text/css">

#dhtmltooltip{
position: absolute;
width: 150px;
border: 2px solid black;
padding: 2px;
background-color: lightyellow;
visibility: hidden;
z-index: 100;
/*Remove below line to remove shadow. Below line should always appear last within this CSS*/
filter: progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135);
}

        </style>
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
    <div id="dhtmltooltip"></div>

<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

document.onmousemove=positiontip

</script>
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
						    <h1 style="margin-top:0px;">Participants</h1>
						    <table width="100%" border="1">
                              <tr>
                                <td bgcolor="#CCCCCC">No</td>
                                <td bgcolor="#CCCCCC">ID</td>
                                <td bgcolor="#CCCCCC">Surname</td>
                                <td bgcolor="#CCCCCC">Firstname</td>
                                <td bgcolor="#CCCCCC">Organisation</td>
                                <td bgcolor="#CCCCCC">Oral/Poster</td>
                                <td bgcolor="#CCCCCC">Country</td>
                                <td bgcolor="#CCCCCC">Photo</td>
                              </tr>
                              <?
							  $i=1;
							  while($row = mysql_fetch_assoc($result)){
							  extract($row);
							  ?>
                              <tr>
                                <td><? echo $i++; ?></td>
                                <td><? echo $personID; ?></td>
                                <td><? echo $name; ?></td>
                                <td><? echo $firstname; ?></td>
                                <td><? echo $organisation; ?></td>
                                <td><? 
								$newSql = "select * from papers where personID = '$personID'";
								$newResult = mysql_query($newSql);
								$paperID = "";
								while($newRow = mysql_fetch_assoc($newResult)) {
									if($newRow["acceptstatus"] == -3) echo "P/";
									else if($newRow["acceptstatus"] == 1) echo "O/";									
									//else echo $newRow["acceptstatus"]."/";
									if(file_exists("upload/photo_".$newRow["ID"].".jpg")){
										$paperID = "photo_".$newRow["ID"].".jpg";
										break;
									}	
									else if(file_exists("upload/photo_".$newRow["ID"].".png")){
										$paperID = "photo_".$newRow["ID"].".png";
										break;
									}
									else if(file_exists("upload/photo_".$newRow["ID"].".gif")){
										$paperID = "photo_".$newRow["ID"].".gif";
										break;
									}								
								
								}
								 ?></td>
                                <td <? if($paperID != '' && file_exists("tag_no_photo/".$personID.".ppt")) echo 'bgcolor="#FF0000"'; ?>><? echo $country; ?></td>
                                <td <? if($paperID != '' && file_exists("tag_no_photo/".$personID.".ppt")) echo 'bgcolor="#FF0000"'; ?>><img src="http://www.ivs.auckland.ac.nz/ivcnz2011/upload/<? echo $paperID; ?>" width="150" height="200"></td>
                              </tr>
                              <? } ?>
                            </table>
						    <p style="margin-top:0px;">&nbsp;</p>
						    <p style="margin-top:0px;">&nbsp;</p>
                          </div>                         
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