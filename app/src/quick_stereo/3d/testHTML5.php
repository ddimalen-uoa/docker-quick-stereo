<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Keywords" content="web development, html 5, html5, javascript canvas example, javascript canvas, tutorial, javascript image manipulation" />
<meta name="Description" content="html 5, html5, javascript canvas tutorial for web development" />
<link rel="stylesheet" type="text/css" href="../../style/main2008.css">
<meta http-equiv="Content-Language" content="en-us">
<title>Using HTML5 Canvas To Turn Image Into Grayscale</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
var counting = 0;
function prepareMouseOverImage(image, originalURL)
{
	//image.mouseOverImage=originalURL;
	image.normalImage=originalURL;
	
	image.onload=function(){return true;};
	
	image.mouseOverImage=grayscale(image);
	
	image.onmouseover=function()
	{	
		this.src=this.mouseOverImage;
		counting++;
		document.getElementById('test').innerHTML=counting;
		//alert(counting);
	}
	
	image.onmousemove=function()
	{	
		this.src=this.mouseOverImage;
		counting++;
		document.getElementById('test').innerHTML=counting;
		//alert(counting);
	}
	
	image.onmouseout=function()
	{
		this.src=this.normalImage;
	}
	
	image.src=image.normalImage;
}


function grayscale(image)
{
  var myCanvas=document.createElement("canvas");
  var myCanvasContext=myCanvas.getContext("2d");

  var imgWidth=image.width;
  var imgHeight=image.height;
  // You'll get some string error if you fail to specify the dimensions
  myCanvas.width= imgWidth;
  myCanvas.height=imgHeight;
//  alert(imgWidth);
  myCanvasContext.drawImage(image,0,0);

  // The getImageData() function cannot be called if the image is not from the same domain.
  // You'll get security error
  var imageData=myCanvasContext.getImageData(0,0, imgWidth, imgHeight);
  for (i=0; i<imageData.height; i++)
  {
    for (j=0; j<imageData.width; j++)
    {
	  var index=(i*4)*imageData.width+(j*4);
	  var red=imageData.data[index];
	  var green=imageData.data[index+1];
	  var blue=imageData.data[index+2];
	  var alpha=imageData.data[index+3];
	  var average=(red+green+blue)/3;
   	  imageData.data[index]=average;
   	  imageData.data[index+1]=average;
   	  imageData.data[index+2]=average;
   	  imageData.data[index+3]=alpha;
	}
  }
  myCanvasContext.putImageData(imageData,0,0,0,0, imageData.width, imageData.height);   
  return myCanvas.toDataURL();
}

</script>
</head>
<body id="body">
<div class="contentShadow">
<div class="content">
  <div class="subcontent"><p><img src="images/image3.jpg" width="250" height="250" id="myImage" onload="javascript:prepareMouseOverImage(this, 'images/image3.jpg');"></img></p>
  </div>
</div>
</div>
<div id="test">1</div>




</body>
</html>