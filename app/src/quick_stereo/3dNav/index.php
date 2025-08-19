<?
session_start();
?>
<!DOCTYPE html>
<html lang="en" >
<head>
<link href="css/main.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
var canvas;
var context;
var p1 = 0.99;
var p2 = 0.99;
var p3 = 0.99;
var er = 0; // extra red
var eg = 0; // extra green
var eb = 0; // extra blue
var func = 'colored'; // used function
var imgDisp;
var WIDTH = 500;
var HEIGHT = 333;

var alpha = 0;
var counter = 0;
var originalImage;

var disparityValues;
//var data;
var originalData;
var imgd;
var data;

window.onload = function() {
    canvas = document.getElementById('panel');
    context = canvas.getContext('2d');

    context.fillStyle = '#888888';
    context.fillRect(0, 0, 520, 700);

    var imgObj = new Image();
    imgObj.onload = function () {
        context.drawImage(imgObj, 10, 10, WIDTH, HEIGHT); // Draw the image on the canvas
		originalImage = context.getImageData(10, 10, WIDTH, HEIGHT);
    }
    imgObj.src = '../upload_stereo/<? if(isset($_SESSION["postSession"])) echo "StoreResults/".$_SESSION["postSession"]."/"; ?>left_raw_resized.jpg?' + (new Date()).getTime();
	
	imgDisp = new Image();  
	imgDisp.onload = function () {
        context.drawImage(imgDisp, 10, 353, WIDTH, HEIGHT); // Draw the image on the canvas
		imgDisp = context.getImageData(10, 353, WIDTH, HEIGHT);
    }
    imgDisp.src = '../upload_stereo/<? if(isset($_SESSION["postSession"])) echo "StoreResults/".$_SESSION["postSession"]."/"; ?>depthReserved.jpg?' + (new Date()).getTime();
	
};

function initialiseDisparityValues(){
	
	originalData = originalImage.data;
	imgd = context.getImageData(10, 353, WIDTH, HEIGHT);	
    data = imgd.data
	
	disparityValues = new Array();
	var imgDisparity = imgDisp;
	var dataDisparity = imgDisparity.data;	
	var i = 0;
    for (var h = 0; h < HEIGHT; h++) {
		for (var w = 0; w < WIDTH; w++) {
			var value = 4*(h*WIDTH+w);
			var disparity = dataDisparity[value]/4;
			disparityValues[i++] = (new Array(disparity, w, h));			
		}		
    }		
	
	disparityValues.sort( function(a,b) { return a[0] - b[0]; } ); //alert("here");
}

function Start(){
	var jiggle = function(){
		Colored();		
		setTimeout(jiggle,5);
	}
	jiggle();
}


function Colored() {
    //func = 'colored';   
	
    for (var i = 0; i < disparityValues.length; i++) {
		var disparity = disparityValues[i][0];
		var w = disparityValues[i][1];
		var h = disparityValues[i][2];
		
		var value = 4*(h*WIDTH+w);
		
		if((w+disparity*alpha) < WIDTH && (w+disparity*alpha) >= 0)
		{
			var newValue = value+4*parseInt(disparity*alpha);
			data[newValue]   = originalData[value]; // red
			data[newValue+1] = originalData[value+1]; // green
			data[newValue+2] = originalData[value+2]; // blue			
		}				
    }
	alpha = Math.sin(counter);
	counter = counter + 0.05;	
    context.putImageData(imgd, 10, 353);
	//if(parseInt(counter/10)%2==0) context.putImageData(imgDisp, 10, 10);
	if(parseInt(counter)>3) context.putImageData(imgDisp, 10, 10);
	else context.putImageData(originalImage, 10, 10);
}
</script>
</head>
<body>
    <div class="example">        
        <div class="column2">
            <canvas id="panel" width="520" height="700"></canvas>
        </div>
        <div style="clear:both;"></div>
    </div>
</body>
</html>
<script type="text/javascript">setTimeout('initialiseDisparityValues();Start();',2000);</script>
<?php
print str_pad('',4096)."\n";
ob_flush();
flush();
set_time_limit(45);
?>