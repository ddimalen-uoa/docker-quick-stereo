<?php
$location = $_GET["location"];
?>
<!DOCTYPE HTML>
<html lang="en"><head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Moving Image via Canvas</title>
	<!--<link rel="stylesheet" href="../css/global.css">-->
	<style type="text/css" media="screen">
		body { background:#000000;}
		#bg { position:absolute; top:1em; bottom:1em; left:1em; right:1em }
		canvas { text-align:center; }
		#fps { position:absolute; top:1.5em; bottom:1em; left:2em; right:1em; color:#FFFFFF; }		
	</style>
    
</head><body>
<p id="fps" style="left:700px; top:0px;">xxx fps</p>
<!---->
<div id="bg">
  <input class="3DNavRefreshButton" type="button" name="button" id="button" onClick="document.location='3dnav.php?location=<? echo $location; ?>';" style="background:url(../img/Refresh.png); 
		width:50px; 
		height:50px;">
</div>

<canvas></canvas>
<script type="text/javascript" charset="utf-8">
	function randomString() {
		var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
		var string_length = 8;
		var randomstring = '';
		for (var i=0; i<string_length; i++) {
			var rnum = Math.floor(Math.random() * chars.length);
			randomstring += chars.substring(rnum,rnum+1);
		}
		return toString(randomstring);
	}
	
	var counter = 0;
	var canvas = document.getElementsByTagName('canvas')[0];
	var bg     = document.getElementById('bg');
	var ctx    = canvas.getContext('2d');
	var DisparityPixel = [];
	window.onresize = function(){
		canvas.width  = bg.offsetWidth;
		canvas.height = bg.offsetHeight;
	};
	window.onresize();
	var img = new Image;
	var imgDepth = new Image;
	var i = 0; var j = 0;	
	var index;
	var pointDistanceD;
	var distance;				
	var smallRatio;
	var ratio;			
	var newH;
	var newW;							
	var red;
	var green;
	var blue;	
	var theSunX = 100;
	var theSunY = 100;
	var strinRan = randomString();
	img.src = "../upload_stereo/<? echo $location; ?>/texture.jpg";//?"+strinRan;
	imgDepth.src = "../upload_stereo/<? echo $location; ?>/result.jpg";//?"+strinRan;
	
	var newIndex;
	var imgWidth=img.width;
  	var imgHeight=img.height;
	var numBerPixel = imgWidth*imgHeight;
	ctx.drawImage(imgDepth,0,0);
	var imageDataDepth=ctx.getImageData(0,0, imgWidth, imgHeight);
	var count = 0;
	for (i=0; i<imgHeight; i++)
	{
		for (j=0; j<imgWidth; j++)
		{
			var index=(i*4)*imgWidth+(j*4);
			var pointDistanceD=imageDataDepth.data[index];
			DisparityPixel[count++]={depth:pointDistanceD, x:j, y:i};
		}
	}
	DisparityPixel.sort(function(a, b){
	 	return a.depth-b.depth;
	}) ;
	
	var middleX = imgWidth/2;
	var middleY = imgHeight/2;
	window.onload = function(){		
		var fps = document.getElementById('fps');
		var w = img.width, h = img.height;
		var avgDelay = 0, x=100, y=100;
		
		var lastDraw = new Date;
		ctx.drawImage(img,0,0);
		var imageDataOriginal=ctx.getImageData(0,0, imgWidth, imgHeight);
		var imageData=ctx.getImageData(0,0, imgWidth, imgHeight);
		var jiggle = function(){
			var tempMove = Math.abs(Math.sin(0.02*counter));
						
			theSunX = 200;//tempMove*imgWidth;	
					
			theSunY = 300;//tempMove*imgHeight;	
			
			var moveValue = 0;
			middleX = tempMove*imgWidth;
			middleY = tempMove*imgHeight;
			
			for (count=0; count<4*numBerPixel; count+=4)
			{		
				{
					imageData.data[count]=0;
				  	imageData.data[count+1]=0;
				  	imageData.data[count+2]=0;			  		  	  
				}	
			}		
			
			for (count=0; count<numBerPixel; count++)
			{				
				i = DisparityPixel[count].y;
				j = DisparityPixel[count].x;
				index=(i*4)*imageData.width+(j*4);
				pointDistanceD=imageDataDepth.data[index];
				distance = 1.00/pointDistanceD;		
				
				ratio = (1+(moveValue/distance));			
				newH = parseInt(ratio*(i-middleY)+middleY);
				newW = parseInt(ratio*(j-middleX)+middleX);					
				red=imageDataOriginal.data[index];
				green=imageDataOriginal.data[index+1];
				blue=imageDataOriginal.data[index+2];				
				
				if(newH-1 >= 0 && newH-1 < imgHeight && newW-1 >= 0 && newW-1 < imgWidth){
				  newIndex=(newH*4)*imgWidth+(newW*4);
				  imageData.data[newIndex]=red;
				  imageData.data[newIndex+1]=green;
				  imageData.data[newIndex+2]=blue;			  		  	  
				}	
			}
			ctx.putImageData(imageData,0,0,0,0, imageData.width, imageData.height); 
			var now = new Date;	
			var delay = now - lastDraw;
			avgDelay += (delay - avgDelay) / 10;
			lastDraw = now;
			counter++;
			setTimeout(jiggle,0);
		};
		jiggle();
		setInterval(function(){
			fps.innerHTML = (1000/avgDelay).toFixed(1) + " fps";
		},2000);
	};	
	
</script>
</body></html>
