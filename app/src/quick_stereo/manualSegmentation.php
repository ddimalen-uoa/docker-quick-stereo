<?
include('simpleimage.php');
$leftFile = "upload_stereo/left_raw_resized.jpg";
$image = new SimpleImage();
$image->load($leftFile);
//result_segmented.jpg
$width = $image->getWidth();
$height = $image->getHeight();
//echo "size: ".$width;
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$fp = fopen('upload_stereo/segmentation_boundary.txt', 'w');
	fwrite($fp, str_replace(" ","\n",$_POST["innerArray"]));
	fclose($fp);
}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <style>
      body {
        margin: 0px;
        padding: 0px;
      }
      #myCanvas {
        border: 0px solid #9C9898;
		border: 0px solid black;
      }
    </style>
    <script src="http://ajax.microsoft.com/ajax/jquery/jquery-1.4.2.min.js"></script>
    <script language="JavaScript" src="../js/javascript.js"></script>    
    <script>
	var leftNumber = 0; var rightNumber = 0;
	var   context;
	var imageObj1;	
	
	var myColour=new Array(); // regular array (add an optional integer
	myColour[0]="red";       // argument to control array's size)
	myColour[1]="blue";
	myColour[2]="green";
	myColour[3]="black";
	myColour[4]="white";
	myColour[5]="yellow";
	function trim(str) {
        return str.replace(/^\s+|\s+$/g,"");
	}
	
	

	function redraw(){
		context.drawImage(imageObj1, 0, 0);		
	}
	
      window.onload = function() {
        var canvas = document.getElementById("myCanvas");
        context = canvas.getContext("2d");
        imageObj1 = new Image();

        imageObj1.onload = function() {
          context.drawImage(imageObj1, 0, 0);
        };
        imageObj1.src = "upload_stereo/left_raw_resized.jpg?<? echo rand(); ?>";			
		
		resizeTo(<? echo $width; ?>,<? if(900 < 3*$height) echo '900'; else echo 3*$height; ?>);		
      };
	  
	  document.onmousemove=getMouseCoordinates;
	  document.onmouseup =getMouseClick;
	  var myPointX=new Array();
	  var myPointY=new Array();
	  var myOutterPointX=new Array();
	  var myOutterPointY=new Array();
	  
	  function getMouseClick(event){		  
			ev = event || window.event;	
			if(ev.pageX >= 0 && ev.pageX<<? echo $width; ?> && ev.pageY >= 0 && ev.pageY<<? echo $height; ?>){
				var mouseX = ev.pageX;
				var mouseY = ev.pageY;
				if(document.getElementById('Draw_boundary').value=='DrawInner'){	
					myPointX.push(mouseX);
					myPointY.push(mouseY);						
				}				
				drawLines();
			}
	  }
	  function drawLines(){		
	  		context.drawImage(imageObj1, 0, 0);  	
		  	context.lineWidth=3;
			context.strokeStyle="red";	
			
			
			if(myPointX.length>0){				
				context.beginPath();
				context.moveTo(myPointX[0],myPointY[0]);
				context.lineTo(myPointX[0]+2,myPointY[0]+2);	
				context.stroke();
				document.getElementById('innerArray').value=myPointX[0]+","+myPointY[0]+" ";
			}
				
			
			
			for(var i = 1; i < myPointX.length; i++){
				context.beginPath();
				context.moveTo(myPointX[i-1],myPointY[i-1]);
				context.lineTo(myPointX[i],myPointY[i]);	
				context.stroke();
				document.getElementById('innerArray').value+=myPointX[i]+","+myPointY[i]+" ";
			}			
			
	  }
	  function getMouseCoordinates(event){		  	  	
	  }
	  
	  function undo(){		  
		  if(document.getElementById('Draw_boundary').value=='DrawInner')
		  {	
				myPointX.pop();
				myPointY.pop();				
		  }		 
		  drawLines();//alert('undo');
	  }

    </script>
  </head>
<body style="overflow:auto;" id="bodyPage" bgcolor="#F4FFE4">
<div>    
<canvas id="myCanvas" width="<? echo $width; ?>" height="<? echo $height; ?>"></canvas>
<form name="manualForm" id="manualForm" method="post" action="#bottomPage">
  <p>
    <label>Segmentation tool: </label>
    <select name="Draw_boundary" id="Draw_boundary">
      <option value="DrawInner" style="color:#F00">Outline your interested object</option>      
    </select>
    <input type="submit" name="button" id="button" value="Apply">
    <input type="button" name="button" id="button" value="Undo" onClick="undo();">
    <input type="button" name="button" id="button" value="Reset all" onClick="document.location='manualSegmentation.php';">
    <input type="button" name="button2" id="button2" value="Back to homepage" onClick="document.location='index.php';">
  </p>
  <p>
    <input name="innerArray" type="hidden" id="innerArray" value="<? echo $_POST["innerArray"]; ?>" size="50">
  </p>
</form>   

<? if($_SERVER['REQUEST_METHOD'] == "POST") { ?>
<div id="result_pad" style="height:100%">
<h1>Segmentation is carried out, please wait...</h1>
<p><img src="http://www.ivs.auckland.ac.nz/images/waiting.gif"/></p>
<p>
</div>
  <script language="JavaScript" type="text/javascript">
var wooYayIntervalId = 0; var counting = 0;
function callMySelf(){					
if(document.getElementById('result_segmented_file')){									
clearInterval(wooYayIntervalId);						
wooYayIntervalId = null;
}
else
{
ajaxLoader("read_segmentation.php?number=<? echo $randomNumber; ?>&counter="+counting, "result_pad");							
}				

counting++;			
}
wooYayIntervalId = setInterval("callMySelf()",3000);
</script>
  
  <? } else { echo '<img src="upload_stereo/depthReserved.jpg?'.rand().'"/>'; } ?>
  
</p>
<p><a name="bottomPage" id="bottom"></a></p>
</div>
</body>
</html>