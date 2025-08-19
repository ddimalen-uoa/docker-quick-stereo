var showAutostereogram = true;
//--------------- LOCALIZEABLE GLOBALS ---------------
var d=new Date();
var monthname=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
//Ensure correct for language. English is "January 1, 2004"
var TODAY = monthname[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear();
//---------------   END LOCALIZEABLE   ---------------

function updateProcessingTime(algorithmName){
	document.getElementById('expectedTime').innerHTML = algorithmProcessingTime[algorithmName];
}


function hideAll(){
	if(<?php if($_SESSION["smartPhoneDevice"] == "iPod") echo "false"; else echo "true"; ?>)
	{
		document.getElementById('leftUpload').style.display = 'none';
		document.getElementById('rightUpload').style.display = 'none';
	}
	document.getElementById('mpoUpload').style.display = 'none';
	document.getElementById('leftRightUpload').style.display = 'none';
	document.getElementById('stereogramUpload').style.display = 'none';
	document.getElementById('anaglyphUpload').style.display = 'none';
}
		
function display(element)
{
	document.getElementById(element).style.display = '';
}