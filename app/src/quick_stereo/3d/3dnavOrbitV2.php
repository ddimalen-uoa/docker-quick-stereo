<?php
session_start();
$location = $_GET["location"];
$MAX_WIDTH = 800;
$MAX_HEIGHT = 600;
if($location=="./")
{
	$resultFile = "../upload_stereo/result.jpg";
	$textureFile = "../upload_stereo/texture.jpg";
}
else
{
	$resultFile = "../upload_stereo/".$location."/result.jpg";
	$textureFile = "../upload_stereo/".$location."/texture.jpg";
}

include('../simpleimage.php');
$image = new SimpleImage();

$image->load($resultFile);
//$image->merge800600();
$image->save("../upload_stereo/result800600.jpg");

$image->load($textureFile);
//$image->merge800600();
$image->save("../upload_stereo/texture800600.jpg");

$resultFile = "../upload_stereo/result800600.jpg";
$textureFile = "../upload_stereo/texture800600.jpg";
/**/
$image->load($resultFile);
//result_segmented.jpg
$width = $image->getWidth();
$height = $image->getHeight();
$middleX = $width/2;
$middleY = $height/2;

if($width < $MAX_WIDTH){
	$margin_left = ($MAX_WIDTH - $width)/2;
}
if($height < $MAX_HEIGHT){
	$margin_top = ($MAX_HEIGHT - $height)/2;
}
?>
<!DOCTYPE HTML>
<html lang="en"><head>
	<meta http-equiv="expires" content="-1">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Cache-Control" content="no-cache">
	<title>3d nav via Canvas</title>
	<!--<link rel="stylesheet" href="../css/global.css">-->
	<style type="text/css" media="screen">
		body { background:#000000;}
		#bg { position:absolute; top:1em; bottom:1em; left:1em; right:1em }
		canvas { text-align:center;  margin-left: <? echo $margin_left; ?>px; margin-top: <? echo $margin_top; ?>px;}
		#fps { position:absolute; top:1.5em; bottom:1em; left:2em; right:1em; color:#FFFFFF; }		
	</style>
    <STYLE TYPE="text/css">
    <!--
    body {overflow-x: hidden;overflow-y: hidden;}
    -->
    </STYLE>
    
</head>
<script language=javascript>
//var int=self.setInterval("clock()",10000);
function clock()
  {
	  var d=new Date();
	  var t=d.toLocaleTimeString();	 
	  if(document.getElementById("fps").innerHTML == 'loading, press Refresh if the 3D scene does not appear after 30s'){	  
	  	document.location='3dnavOrbitV2.php?location=<? echo $location; ?>';
	  }
	  else{
	  	int=window.clearInterval(int);
	  }
  }
</script>
<body>
<p class="fps" id="fps" style="left:700px; top:0px; color:#FF0000";>loading, press Refresh if the 3D scene does not appear after 30s</p>
<!---->
<div id="bg">
  <input class="3DNavRefreshButton" type="button" name="button" id="button" onClick="document.location='3dnavOrbitV2.php?location=<? echo $location; ?>';" style="background:url(../img/Refresh.png); 
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
		canvas.width  = <? echo $width; ?>;
		canvas.height = <? echo $height; ?>;
	};
	window.onresize();
	var img = new Image;
	var imgDepth = new Image;
	var i = 0; var j = 0;	
	var index;
	var pointDistanceD;
	var distance;				

		
	var newH;
	var newW;							
	var red;
	var green;
	var blue;	
	
	var strinRan = randomString();
	img.src = "<? echo $textureFile; ?>";
	imgDepth.src = "<? echo $resultFile; ?>";
	
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
	var temp = 0;
	var temps = 0;
	var tempY = 0;
	var tempsY = 0;
	var rotationX = 0.00;
	var rotationY = 0.00;
	var flipX = 1;
	var flipY = 1;
	
	window.onload = function(){		
		var fps = document.getElementById('fps');
		var w = img.width, h = img.height;
		var avgDelay = 0, x=100, y=100;
		
		var lastDraw = new Date;
		ctx.drawImage(img,0,0);
		var imageDataOriginal=ctx.getImageData(0,0, imgWidth, imgHeight);
		var imageData=ctx.getImageData(0,0, imgWidth, imgHeight);
		var moveValue = 0;
		var jiggle = function(){
			var temp1 = Math.sin(0.04*counter);
			var temp2 = Math.cos(0.04*counter);
			rotationX = (temp1/2+0.5);
			temps =  temp2;		
			if((temps<0 && temp>0)){
				flipX=-1*flipX;
			}
			temp = temps;		
			rotationY = (temp2/2+0.5);
			tempsY =  temp1;		
			if((tempsY>0 && tempY<0)){
				flipY=-1*flipY;
			}
			tempY = tempsY;		
			
			if(counter%1000 < 300){				
				{					
						rotationY = 1;
				}
			}
			else if(counter%1000 < 600)	{				
				{					
						rotationX = 1;
				}
			}
			
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
				distance = 0.8/pointDistanceD;						
				
				newH =parseInt((rotationY*(i-middleY)+middleY) + (1.0-rotationY)*(flipY/distance));
				newW =parseInt((rotationX*(j-middleX)+middleX) + (1.0-rotationX)*(flipX/distance)) ;								
				
				if(newH-1 >= 0 && newH-1 < imgHeight && newW-1 >= 0 && newW-1 < imgWidth){
				  newIndex=(newH*4)*imgWidth+(newW*4);				
				  imageData.data[newIndex]=imageDataOriginal.data[index];				
				  imageData.data[newIndex+1]=imageDataOriginal.data[index+1];				
				  imageData.data[newIndex+2]=imageDataOriginal.data[index+2];
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
		if(fps.innerHTML == 'loading, press Refresh if the 3D scene does not appear after 30s')
			fps.innerHTML = "xxx fps";
		setInterval(function(){
			fps.innerHTML = (1000/avgDelay).toFixed(1) + " fps";
		},2000);
	};	
	
</script>
</body></html>
