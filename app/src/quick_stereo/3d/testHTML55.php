<!DOCTYPE HTML>
<html lang="en"><head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Moving Image via Canvas</title>
	<style type="text/css" media="screen">
		body { background:#000000;}
		#bg { position:absolute; top:1em; bottom:1em; left:1em; right:1em }
		canvas { text-align:center; }
		#fps { position:absolute; top:1.5em; bottom:1em; left:2em; right:1em; color:#FFFFFF; }
	</style>
</head><body>
<p id="fps">xxx fps</p>
<!---->
<div id="bg">
  <input type="button" name="button" id="button" value="Refresh if it's blank" onClick="document.location='testHTML55.php';">
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
	
	//imgDepth.cahe = "no-cache";
	//for (i=0; i<14000; i++){}
	var strinRan = randomString();
	img.src = "../upload_stereo/texture.jpg";//?"+strinRan;
	imgDepth.src = "../upload_stereo/result.jpg";//?"+strinRan;
	//img.src = "../upload_stereo/texture.jpg?"+randomString();
	//imgDepth.src = "../upload_stereo/result.jpg?"+randomString();
	//img.cahe = "no-cache";
	//imgDepth.src = "disp1.png";
	//img.src = "view1.png";
	var newIndex;
	var imgWidth=img.width;
  	var imgHeight=img.height;
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
		var jiggle = function(){	
			var moveValue = 0.0015*Math.abs(Math.sin(0.03*counter)%1);
			middleX = Math.abs(Math.cos(0.012*counter)*imgWidth);
			middleY = Math.abs(Math.sin(0.01*counter)*imgHeight);			
			var imageData=ctx.getImageData(0,0, imgWidth, imgHeight);
			for (count=0; count<imageData.height*imageData.width; count++)
			{				
				var i = DisparityPixel[count].y;
				var j = DisparityPixel[count].x;
				var index=(i*4)*imageData.width+(j*4);
				var pointDistanceD=imageDataDepth.data[index];
				var distance = 1.00/pointDistanceD;
				
				var smallRatio = moveValue/distance;
				var ratio = (1+(moveValue/distance));			
				var newH = parseInt(ratio*(i-middleY)+middleY);
				var newW = parseInt(ratio*(j-middleX)+middleX);							
				
				var red=imageDataOriginal.data[index];
				var green=imageDataOriginal.data[index+1];
				var blue=imageDataOriginal.data[index+2];
				//var alpha=imageDataOriginal.data[index+3];
				
				if(newH-1 >= 0 && newH-1 < imgHeight && newW-1 >= 0 && newW-1 < imgWidth){
				  newIndex=(newH*4)*imgWidth+(newW*4);
				  imageData.data[newIndex]=red;
				  imageData.data[newIndex+1]=green;
				  imageData.data[newIndex+2]=blue;
				  //imageData.data[newIndex+3]=alpha;
				  /*
				  if(smallRatio>0.13){
					  newIndex=((newH-1)*4)*imgWidth+(newW*4);
					  imageData.data[newIndex]=red;
					  imageData.data[newIndex+1]=green;
					  imageData.data[newIndex+2]=blue;
					  //imageData.data[newIndex+3]=alpha;
					  
					  newIndex=((newH+1)*4)*imgWidth+(newW*4);
					  imageData.data[newIndex]=red;
					  imageData.data[newIndex+1]=green;
					  imageData.data[newIndex+2]=blue;
					  //imageData.data[newIndex+3]=alpha;
					  
					  newIndex=((newH)*4)*imgWidth+((newW-1)*4);
					  imageData.data[newIndex]=red;
					  imageData.data[newIndex+1]=green;
					  imageData.data[newIndex+2]=blue;
					  //imageData.data[newIndex+3]=alpha;
					  
					  newIndex=((newH)*4)*imgWidth+((newW+1)*4);
					  imageData.data[newIndex]=red;
					  imageData.data[newIndex+1]=green;
					  imageData.data[newIndex+2]=blue;
					  //imageData.data[newIndex+3]=alpha;
				  }	
				  if(smallRatio>0.43){
					  newIndex=((newH-1)*4)*imgWidth+((newW-1)*4);
					  imageData.data[newIndex]=red;
					  imageData.data[newIndex+1]=green;
					  imageData.data[newIndex+2]=blue;
					  //imageData.data[newIndex+3]=alpha;
					  
					  newIndex=((newH-1)*4)*imgWidth+((newW+1)*4);
					  imageData.data[newIndex]=red;
					  imageData.data[newIndex+1]=green;
					  imageData.data[newIndex+2]=blue;
					  //imageData.data[newIndex+3]=alpha;
					  
					  newIndex=((newH+1)*4)*imgWidth+((newW+1)*4);
					  imageData.data[newIndex]=red;
					  imageData.data[newIndex+1]=green;
					  imageData.data[newIndex+2]=blue;
					  //imageData.data[newIndex+3]=alpha;
					  
					  newIndex=((newH+1)*4)*imgWidth+((newW-1)*4);
					  imageData.data[newIndex]=red;
					  imageData.data[newIndex+1]=green;
					  imageData.data[newIndex+2]=blue;
					  //imageData.data[newIndex+3]=alpha;
				  }	
				  */			  
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
