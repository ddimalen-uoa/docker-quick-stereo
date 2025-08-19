<!-- HOME page -->
<?php 
//include("../php_script/password_protect.php");
require_once("../new_site_d/php/common.php"); //for header and footer
$ivs_location = "../new_site_d/";
include 'dbc.php'; //for database

$sqlSample = "select p.ID as id, p.personID as authorID, author, title, greatest(0,(sum(evaluation+significance+originality+relevance+readability+(5*overall))/count(r.paperID))) as score, count(r.paperID) as count, (max((evaluation+significance+originality+relevance+readability+(5*overall)))-min((evaluation+significance+originality+relevance+readability+(5*overall)))) as span, acceptstatus, track, contributiontypeID FROM papers p left join reviews r on p.ID = r.paperID and r.creationdate > 0 where p.withdrawn=0 
and track = 'THETRACK'
group by p.ID 
ORDER BY acceptstatus desc, track, title";

//oral
$sql = str_replace("THETRACK","1",$sqlSample);
$resultO1 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	
$sql = str_replace("THETRACK","3",$sqlSample);
$resultO2 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	
$sql = str_replace("THETRACK","5",$sqlSample);
$resultO3 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	
$sql = str_replace("THETRACK","7",$sqlSample);
$resultO4 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	
$sql = str_replace("THETRACK","9",$sqlSample);
$resultO5 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	
$sql = str_replace("THETRACK","11",$sqlSample);
$resultO6 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	
$sql = str_replace("THETRACK","13",$sqlSample);
$resultO7 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	

//poster
$sql = str_replace("THETRACK","15",$sqlSample);
$resultP1 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	
$sql = str_replace("THETRACK","17",$sqlSample);
$resultP2 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	
$sql = str_replace("THETRACK","19",$sqlSample);
$resultP3 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	

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
						    
						    <h1 align="center" class="style1">IVCNZ 2011 Workshop day</h1>
						    <h1 align="center">November 28, Jubilee building<br>
						      Parnell, New Zealand</h1>
						    <h1 style="margin-top:0px;"><span class="style2">Programme</span></h1>
						    <table width="100%" border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                              <tr height="30">
                                <td width="158" height="50" align="left" valign="middle" bgcolor="#CCCCCC"><h2 align="right">Time</h2></td>
                                <td width="1076" valign="middle" bgcolor="#CCCCCC"><h2>Day 1: Mon 28 Nov</h2></td>
                              </tr>
                              
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">10:00</div></td>
                                <td bgcolor="#FFFF66"><strong>Registration</strong></td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">10:30</div></td>
                                <td bgcolor="#BBBBFF"><strong>Welcome speech</strong></td>
                              </tr>
                              <tr height="150">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">10:40</div></td>
                                <td bgcolor="#BBBBFF"><strong>Keynote: Philippe Leclercq <br>
                                  Chair: Patrice Delmas<br>
                                  Topic: Patenting activities in Image Processing</strong></td>
                              </tr>
                              <tr height="20">
                                <td height="50" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">12:40</div></td>
                                <td bgcolor="#CCFFFF">Lunch Break</td>
                              </tr>
                              

                              
                              <tr height="150">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">13:40</div>    
                              <div align="right"></div></td>
                                <td bgcolor="#BBBBFF"><strong>Keynote: Georgy Gimel'farb<br>
                                Chair: Philippe Leclercq<br>Topic: Computational Stereo Vision:
Solving an Ill-posed Problem</strong></td>
                              </tr>                              
                              
                              <tr height="20">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">15:30</div>                                  
						<div align="right"></div>                                  <div align="right"></div>                                  
						<div align="right"></div></td>
                                <td bgcolor="#99CCFF"><strong>Afternoon Tea -    Stereo-vision applications<br>Interactive session<br>
                                Chair: Georgy Gimel'farb</strong></td>
                              </tr>
					<tr height="20">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">15:50</div>    
                              <div align="right"></div></td>
                                <td bgcolor="#BBBBFF"><strong>Edwin Chan<br>Camera Calibration (demo)<br> </strong></td>
                              </tr>
						<tr height="20">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">16:10</div>    
                              <div align="right"></div></td>
                                <td bgcolor="#BBBBFF"><strong>Alfonso Gastelum Strozzi<br>Stereo vision and CUDA programming (demo)<br> </strong></td>
                              </tr>
						<tr height="20">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">16:30</div>    
                              <div align="right"></div></td>
                                <td bgcolor="#BBBBFF"><strong>Minh Nguyen<br>Online Stereo Vision (demo)<br> </strong></td>
                              </tr>
                            </table>
                          </div>                         
						  <hr>
						  <h3 align="center">Contact:</h3>
						  <p align="center">IVCNZ 2011 Convenors
						  <br>
						  <a href="mailto:IVCNZ2011@cs.auckland.ac.nz">IVCNZ2011@cs.auckland.ac.nz</a></p>						  
				      </div>                        
                        <div>
                                                </div>               				
				  </div>
			  </div>	
				
			</div>
		</div>
	</body>
</html>