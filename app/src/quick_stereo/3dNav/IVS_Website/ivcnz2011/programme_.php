<!-- HOME page -->
<?php 
//include("../php_script/password_protect.php");
require_once("../new_site_d/php/common.php"); //for header and footer
$ivs_location = "../new_site_d/";
include 'dbc.php'; //for database

$sqlSample = "select p.ID as id, p.personID as authorID, author, title, p.filename, greatest(0,(sum(evaluation+significance+originality+relevance+readability+(5*overall))/count(r.paperID))) as score, count(r.paperID) as count, (max((evaluation+significance+originality+relevance+readability+(5*overall)))-min((evaluation+significance+originality+relevance+readability+(5*overall)))) as span, acceptstatus, track, contributiontypeID FROM papers p left join reviews r on p.ID = r.paperID and r.creationdate > 0 where p.withdrawn=0 
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


//poster
$sql = str_replace("THETRACK","15",$sqlSample);
$resultP1 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	
$sql = str_replace("THETRACK","17",$sqlSample);
$resultP2 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	
$sql = str_replace("THETRACK","19",$sqlSample);
$resultP3 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	

$sqlSample = "select p.ID as id, p.personID as authorID, p.filename, author, title, greatest(0,(sum(evaluation+significance+originality+relevance+readability+(5*overall))/count(r.paperID))) as score, count(r.paperID) as count, (max((evaluation+significance+originality+relevance+readability+(5*overall)))-min((evaluation+significance+originality+relevance+readability+(5*overall)))) as span, acceptstatus, track, contributiontypeID FROM papers p left join reviews r on p.ID = r.paperID and r.creationdate > 0 where p.withdrawn=0 
and track = 'THETRACK'
group by p.ID 
ORDER BY acceptstatus desc, track, title desc";
$sql = str_replace("THETRACK","13",$sqlSample);
$resultO7 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());	

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
						    <h1 style="margin-top:0px;"><span class="style2">Programme</span></h1>
						    <table border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                              <col width="160">
                              <col width="289" span="2">
                              <col width="284">
                              <tr height="30">
                                <td width="160" height="50" align="left" valign="middle" bgcolor="#CCCCCC"><h2 align="right">Time</h2></td>
                                <td width="289" valign="middle" bgcolor="#CCCCCC"><h2>Day 1: Tue 29 Nov</h2></td>
                                <td width="289" valign="middle" bgcolor="#CCCCCC"><h2>Day 2: Wed 30 Nov</h2></td>
                                <td width="284" valign="middle" bgcolor="#CCCCCC"><h2>Day 3: Thu 1 Dec</h2></td>
                              </tr>
                              
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">8:00</div></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td rowspan="4">&nbsp;</td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">8:30</div></td>
                                <td bgcolor="#FFFF66"><strong>Registration</strong></td>
                                <td bgcolor="#FFFF66"><strong>Registration</strong></td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">9:00</div></td>
                                <td bgcolor="#BBBBFF"><strong>Welcome speech</strong></td>
                                <td rowspan="2" bgcolor="#BBBBFF"><strong>Keynote: Philippe    Leclercq</strong></td>
                              </tr>
                              <tr height="20">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">9:10</div>                                  <div align="right"></div></td>
                                <td bgcolor="#BBBBFF"><strong>&nbsp;Keynote: Takashi Ikegami</strong></td>
                              </tr>
                              
                              <tr height="20">
                                <td height="20" valign="top" bgcolor="#CCCCCC"><div align="right">9:30</div></td>
                                <td bgcolor="#FFDDFF"><strong>Chair: Rick Millane</strong></td>
                                <td bgcolor="#FFDDFF"><strong>Chair: Alexander    Woodward</strong></td>
                                <td bgcolor="#FFFF66"><strong>Registration</strong></td>
                              </tr>
                              <tr height="20">
                                <td rowspan="2" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">9:50</div>                                  <div align="right"></div>                                  <div align="right"></div>                                  <div align="right"></div></td>
                                <td rowspan="2">&nbsp;</td>
                                <td height="20" bgcolor="#FFFFCC"><strong>Oral session 3<br>
                                Bio-medical    applications<br>
                                Chair: Phil Bones</strong></td>
                                <td rowspan="5" valign="top" bgcolor="#FFDDFF"><table width="100%" border="0" bgcolor="#FFDDFF">
                                  <tr>
                                    <td bgcolor="#BBBBFF"><strong>Keynote: Timo Ropinski</strong><hr></td>
                                  </tr>
                                  <tr>
                                    <td bgcolor="#FFDDFF"><strong>Chair: Burkhard    Wunsche</strong></td>
                                  </tr>
                                </table></td>
                              </tr>
                              
                              <tr height="20">
                                <td rowspan="2" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO3)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">10:00</div>                                  <div align="right"></div>                                  <div align="right"></div></td>
                                <td rowspan="2" bgcolor="#99CCFF"><div align="left"><strong>Cofee break - Poster    session 1<br>
                                Applications<br>
                                Chair: Takashi    Ikegami<br>
                                Poster shout-out</strong></div></td>
                              </tr>
                              
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">10:10</div></td>
                                <td rowspan="2" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO3)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td height="20" valign="top" bgcolor="#CCCCCC"><div align="right"></div></td>
                                <td rowspan="3" bgcolor="#99CCFF">                                
                                <div align="left">
                                <ul>
                                  <? 
								while($row = mysql_fetch_assoc($resultP1)){	
								extract($row);
								echo '<li><a href="../ivcnz2011_temp/uploads/'.addslashes($filename).'"><span onMouseover="ddrivetip(\''.$paperID.'-'.addslashes($title).' - '.addslashes($author).'\',\'yellow\', 400)";
onMouseout="hideddrivetip()">"'.substr($title,0,20).'..."</span></a></li>';								
								//echo $author;
								//echo "<br>";
								}
								?> 
                                </ul>                               
                                </div></td>
                              </tr>
                              <tr height="20">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">10:30</div>                                  <div align="right"></div>                                  <div align="right"></div>                                  <div align="right"></div></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO3)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                                <td rowspan="12" valign="top" bgcolor="#CCFFFF"><table width="100%" border="0" summary="4">
                                  <tr>
                                    <td valign="middle" bgcolor="#CCFFFF"><p>&nbsp;</p>
                                    <p>Coffee Break (10:30 - 11:00)</p>
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p><hr></td>
                                  </tr>
                                  <tr bgcolor="#FFFFFF" height="20">
                                    <td height="20" bgcolor="#FFFFCC"><strong>Oral session 6 (11:00am)<br>
Image enhancement and    processing<br>
Chair: Donal Bailey</strong><hr></td>
                                  </tr>
                                  <tr bgcolor="#FFFFFF" height="20">
                                    <td height="20" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO6)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?><hr></td>
                                  </tr>
                                  <tr bgcolor="#FFFFFF" height="20">
                                    <td height="20" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO6)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?>
                                    <hr></td>
                                  </tr>
                                  <tr bgcolor="#FFFFFF" height="20">
                                    <td height="20" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO6)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?>
                                    <hr></td>
                                  </tr>
                                  <tr bgcolor="#FFFFFF" height="20">
                                    <td height="20" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO6)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?>
                                    <hr></td>
                                  </tr>
                                  <tr bgcolor="#FFFFFF" height="20">
                                    <td height="20" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO6)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?>
                                    <hr></td>
                                  </tr>
                                  
                                  <tr>
                                    <td bgcolor="#CCFFFF"><p>&nbsp;</p>
                                      <p>&nbsp;</p>
                                      <p>Lunch Break (12:30pm)</p>
                                      <p>&nbsp;</p>
                                    <p>&nbsp;</p></td>
                                  </tr>
                                </table></td>
                              </tr>
                              
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">10:50</div></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO3)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td rowspan="2" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">11:10</div>                                  <div align="right"></div></td>
                                <td height="20" bgcolor="#FFFFCC"><strong>Oral session 1&nbsp;<br>
                                Accelerated    Processing<br>
                                Chair: Robert    Valkenburg</strong></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO3)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              
                              <tr height="20">
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO1)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                                <td rowspan="2" bgcolor="#99CCFF"><strong>Morning break -    Poster session 3<br>
                                Methods<br>
                                Chair: Richard Green<br>
                                Poster shout-out</strong></td>
                              </tr>
                              
                              <tr height="20">
                                <td rowspan="2" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">11:30</div>                                  <div align="right"></div>                                  <div align="right"></div>                                  <div align="right"></div>                                  <div align="right"></div></td>
                                <td rowspan="2" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO1)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              
                              <tr height="20">
                                <td rowspan="3" bgcolor="#99CCFF"><div align="left">
                                  <ul><? 
								while($row = mysql_fetch_assoc($resultP3)){	
								extract($row);
								echo '<li><a href="../ivcnz2011_temp/uploads/'.$filename.'"><span onMouseover="ddrivetip(\''.$paperID.'-'.addslashes($title).' - '.addslashes($author).'\',\'yellow\', 400)";
onMouseout="hideddrivetip()">"'.substr($title,0,20).'..."</span></a></li>';							
								//echo $author;
								//echo "<br>";
								}
								?></ul>
                                </div></td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">11:50</div></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO1)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">12:10</div></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO1)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td rowspan="2" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">12:30</div>                                  <div align="right"></div>                                  <div align="right"></div>                                  <div align="right"></div></td>
                                <td rowspan="2" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO1)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                                <td height="20" bgcolor="#FFFFCC"><strong>Oral session 4 (best paper session)<br>
                                Chair: Georgy    Gimel'farb<br>
                                Authors have 20    minutes+5minutes questions</strong></td>
                              </tr>
                              
                              <tr height="20">
                                <td height="20" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO4)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">12:50</div></td>
                                <td rowspan="5" bgcolor="#CCFFFF">Lunch Break</td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO4)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">13:10</div>                                  <div align="right"></div></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO4)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              
                              <tr height="20">
                                <td rowspan="2" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">13:30</div>                                  <div align="right"></div>                                  <div align="right"></div>                                  <div align="right"></div></td>
                                <td rowspan="2" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO4)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                                <td height="20" bgcolor="#FFFFCC"><strong>Oral session 7<br>
                                Recognition/registration/tracking<br>
                                Chair: P. Leclercq</strong></td>
                              </tr>
                              
                              <tr height="20">
                                <td height="20" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO7)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">13:50</div>                                  <div align="right"></div>                                  <div align="right"></div></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO4)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO7)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              
                              <tr height="20">
                                <td rowspan="2" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">14:10</div>                                  <div align="right"></div>                                  <div align="right"></div>                                  <div align="right"></div>                                  <div align="right"></div></td>
                                <td height="20" bgcolor="#FFFFCC"><strong>Oral session 2<br>
                                Augmented reality -    CG applications<br>
                                Chair: Timo Ropinski</strong></td>
                                <td rowspan="2" bgcolor="#FFFFCC">Extra...</td>
                                <td rowspan="17" valign="top" bgcolor="#FFFFCC"><table width="100%" border="0">
                                  <tr>
                                    <td><? 
								if($row = mysql_fetch_assoc($resultO7)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?><hr></td>
                                  </tr>
                                  <tr>
                                    <td><? 
								if($row = mysql_fetch_assoc($resultO7)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?>
                                    <hr></td>
                                  </tr>
                                  <tr>
                                    <td><? 
								if($row = mysql_fetch_assoc($resultO7)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?>
                                    <hr></td>
                                  </tr>
                                  <tr>
                                    <td><? 
								if($row = mysql_fetch_assoc($resultO7)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?>
                                    <hr></td>
                                  </tr>
                                  <tr>
                                    <td><? 
								if($row = mysql_fetch_assoc($resultO7)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?>
                                    <hr></td>
                                  </tr>
                                  <tr>
                                    <td><? 
								if($row = mysql_fetch_assoc($resultO7)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?>
                                    <hr></td>
                                  </tr>
                                  <tr>
                                    <td height="82"><? 
								if($row = mysql_fetch_assoc($resultO7)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?>
                                    <hr></td>
                                  </tr>
                                  
                                  <tr>
                                    <td bgcolor="#FFCCCC"><p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                    <p>Conference Closing and Awards</p>
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p></td>
                                  </tr>
                                </table>
                                  <p>&nbsp;</p>
                                <p>&nbsp;</p></td>
                              </tr>
                              
                              <tr height="20">
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO2)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">14:30</div></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO2)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                                <td rowspan="2" bgcolor="#CCFFFF">Afternoon break</td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">14:50</div></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO2)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td rowspan="2" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">15:10</div>                                  <div align="right"></div>                                  <div align="right"></div>                                  <div align="right"></div></td>
                                <td rowspan="2" bgcolor="#FFFFCC">
								<? 
								if($row = mysql_fetch_assoc($resultO2)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?>                                </td>
                                <td height="20" bgcolor="#FFFFCC"><strong>Oral session 5<br>
                                Maths and Methods<br>
                                Chair: Brendan McCane</strong></td>
                              </tr>
                              
                              <tr height="20">
                                <td height="20" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO5)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">15:30</div>                                  <div align="right"></div>                                  <div align="right"></div>                                  <div align="right"></div></td>
                                <td bgcolor="#99CCFF"><strong>Afternoon Tea -    Poster session 2<br>
                                CG-GPU-Stereo<br>
                                Poster shout-out<br>
                                Chair: Mengjie Zhang</strong></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO5)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">15:50</div></td>
                                <td rowspan="5" bgcolor="#99CCFF"><div align="left">
                                  <ul><? 
								while($row = mysql_fetch_assoc($resultP2)){	
								extract($row);
								echo '<li><a href="../ivcnz2011_temp/uploads/'.$filename.'"><span onMouseover="ddrivetip(\''.$paperID.'-'.addslashes($title).' - '.addslashes($author).'\',\'yellow\', 400)";
onMouseout="hideddrivetip()">"'.substr($title,0,20).'..."</span></a></li>';							
								//echo $author;
								//echo "<br>";
								}
								?></ul>
                                </div></td>
                                <td rowspan="2" bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO5)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">16:00</div></td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">16:10</div></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO5)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">16:30</div></td>
                                <td bgcolor="#FFFFCC"><? 
								if($row = mysql_fetch_assoc($resultO5)){	
								extract($row);
								echo '<a href="../ivcnz2011_temp/uploads/'.$filename.'"><span>"'.$title.'"</span></a>';
								echo "<br>";
								echo $author;
								}
								?></td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">16:50</div></td>
                                <td rowspan="3">&nbsp;</td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">17:00</div></td>
                                <td bgcolor="#BBBBFF"><strong>Convenors speech</strong></td>
                              </tr>
                              <tr height="20">
                                <td align="right" valign="top" bgcolor="#CCCCCC"><div align="right">17:10</div>                                  <div align="right"></div></td>
                                <td rowspan="4">&nbsp;</td>
                              </tr>
                              
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">19:00</div></td>
                                <td bgcolor="#FFCC99"><strong>Cruise starts    (boarding 6:45 strictly enforced)</strong></td>
                              </tr>
                              <tr height="20">
                                <td height="20" valign="top" bgcolor="#CCCCCC"><div align="right"></div></td>
                                <td bgcolor="#FFCC99">...</td>
                              </tr>
                              <tr height="20">
                                <td height="20" align="right" valign="top" bgcolor="#CCCCCC"><div align="right">22:00</div></td>
                                <td bgcolor="#FFCC99"><strong>Cruise ends</strong></td>
                              </tr>
                            </table>
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