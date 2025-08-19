<?
include('simpleimage.php');
$leftFile = "upload_stereo/left_raw_resized.jpg";
$image = new SimpleImage();
$image->load($leftFile);
//result_segmented.jpg
$width = $image->getWidth();
$height = $image->getHeight();
//echo "size: ".$width;
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST["testRectification"]=='1'){
	$fp = fopen('upload_stereo/leftCor.txt', 'w');
	fwrite($fp, $_POST["leftCor"]);
	fclose($fp);
	$fp = fopen('upload_stereo/rightCor.txt', 'w');
	fwrite($fp, $_POST["rightCor"]);
	fclose($fp);
	?>    
    <script language="javascript">	
	window.opener.document.getElementById('submitButton').click();
	alert('Thanks these new selected points are sent over to our server for recitifcation, please wait.');
	//window.close();	
	</script>
    <?
	/*
	$fpL = fopen('upload_stereo/leftCor.txt', 'r');
	$theLeftData = fread($fpL, 100000);
	$fpR = fopen('upload_stereo/rightCor.txt', 'r');
	$theRightData = fread($fpR, 100000);
	fclose($fhL);
	fclose($fhR);
	*/
}
else if($_SERVER['REQUEST_METHOD'] == "POST"){
	$fp = fopen('upload_stereo/leftCor.txt', 'w');
	fwrite($fp, $_POST["leftCor"]);
	fclose($fp);
	$fp = fopen('upload_stereo/rightCor.txt', 'w');
	fwrite($fp, $_POST["rightCor"]);
	fclose($fp);
	?>    
    <script language="javascript">
	alert('Thanks we have received your data! Now the image rectification is carried out again with your data.');
	window.opener.document.getElementById('submitButton').click();
	window.close();	
	</script>
    <?
}
else{
	if($_GET["action"]=="reset"){
		$fp = fopen('upload_stereo/leftCor.txt', 'w');
		fwrite($fp, "");
		fclose($fp);
		$fp = fopen('upload_stereo/rightCor.txt', 'w');
		fwrite($fp, "");
		fclose($fp);
	}
	else{
		$fpL = fopen('upload_stereo/leftCor.txt', 'r');
		$theLeftData = fread($fpL, 100000);
		$fpR = fopen('upload_stereo/rightCor.txt', 'r');
		$theRightData = fread($fpR, 100000);
		fclose($fhL);
		fclose($fhR);
	}
	//echo $theData;	
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
    <script src="./js/psMathStats.js"></script>
    <script>
	var leftNumber = 0; var rightNumber = 0;
	var   context;
	var imageObj1;
	var imageObj2;
	var tenPercent = 0;
	//Math.floor((Math.random()*10)+1); 
	//alert(tenPercent);
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
		context.drawImage(imageObj2, <? echo $width; ?> , 0);
	}
	
      window.onload = function() {
        var canvas = document.getElementById("myCanvas");
           context = canvas.getContext("2d");
        imageObj1 = new Image();

        imageObj1.onload = function() {
          context.drawImage(imageObj1, 0, 0);
        };
        imageObj1.src = "upload_stereo/left_raw_resized.jpg";
		
		imageObj2 = new Image();

        imageObj2.onload = function() {
          context.drawImage(imageObj2, <? echo $width; ?> , 0);
        };
        imageObj2.src = "upload_stereo/right_raw_resized.jpg";		
		
		resizeTo(<? echo 2*$width+15; ?>,<? if(900 < 3*$height) echo '900'; else echo 3*$height; ?>);		
      };
	  
	  document.onmousemove=getMouseCoordinates;
	  document.onmouseup =getMouseClick;
	  function drawLines(){		  	
		  	  var colourCount = 0;
		 	  var leftText = document.getElementById('leftCor').value;
			  var leftTextArray=leftText.split("\n");
			   
			  var rightText = document.getElementById('rightCor').value;
			  var rightTextArray=rightText.split("\n");
			  if(leftTextArray.length == rightTextArray.length){					  
					redraw();
					if(tenPercent == 0){
						var act = 1;
					}
					else{
						var act = 10;
					}
					for( var i = tenPercent; i < leftTextArray.length; i+=act){
						
						var leftTextXY=leftTextArray[i].split(",");
						var rightTextXY=rightTextArray[i].split(",");
						
						context.lineWidth=0.5;
						context.strokeStyle=myColour[colourCount%6];
						context.beginPath();
						context.moveTo(leftTextXY[0],leftTextXY[1]);
						context.lineTo(parseInt(rightTextXY[0])+parseInt('<? echo $width; ?>'),rightTextXY[1]);	
						context.stroke();
						
						context.beginPath();
						context.lineWidth=1;
						context.strokeStyle=myColour[colourCount++%6];
						context.moveTo(parseInt(leftTextXY[0])-10,leftTextXY[1]);
						context.lineTo(parseInt(leftTextXY[0])+10,leftTextXY[1]);
						context.moveTo(leftTextXY[0],parseInt(leftTextXY[1])-10);
						context.lineTo(leftTextXY[0],parseInt(leftTextXY[1])+10);
						
						context.moveTo(parseInt(rightTextXY[0])+parseInt('<? echo $width; ?>')-10,rightTextXY[1]);
						context.lineTo(parseInt(rightTextXY[0])+parseInt('<? echo $width; ?>')+10,rightTextXY[1]);
						context.moveTo(parseInt(rightTextXY[0])+parseInt('<? echo $width; ?>'),parseInt(rightTextXY[1])-10);
						context.lineTo(parseInt(rightTextXY[0])+parseInt('<? echo $width; ?>'),parseInt(rightTextXY[1])+10);
								
						context.stroke();
					}
			  }
	  }
	  function getMouseCoordinates(event){
		  //redraw();
		  ev = event || window.event;
		  var mousePos = "X:"+ev.pageX + " Y:"+ev.pageY;
		  if(ev.pageX >= 0 && ev.pageY<<? echo $height; ?>){
			  if(ev.pageX < <? echo $width; ?>){
				document.getElementById('leftMouse').value = mousePos;
				document.getElementById('rightMouse').value = '';
			  }
			  else{
				document.getElementById('rightMouse').value = mousePos;
				document.getElementById('leftMouse').value = '';
			  }	
		  }
		  drawLines();  		  	
	  }
	  
	  function getMouseClick(event){		  
			ev = event || window.event;	
			var mouseX = ev.pageX;
			var mouseY = ev.pageY;				
			
			if(ev.pageY<<? echo $height; ?> && document.getElementById('addDeleteButton').value == 'Add points'){
				if(ev.pageX >= 0 && ev.pageX < <? echo $width; ?> && leftNumber == rightNumber){
					context.lineWidth=1;
					context.strokeStyle=myColour[leftNumber%5];
					context.beginPath();
					context.moveTo(mouseX-10,mouseY);
					context.lineTo(mouseX+10,mouseY);
					context.moveTo(mouseX,mouseY-10);
					context.lineTo(mouseX,mouseY+10);
					context.stroke();
					leftNumber++;
					
					var leftText = document.getElementById('leftCor').value;
					document.getElementById('leftCor').value = leftText+mouseX+","+mouseY+"\n";
				}
				else if(ev.pageX >= 0 && ev.pageX >= <? echo $width; ?> && leftNumber == (rightNumber+1)){
					context.lineWidth=1;
					context.strokeStyle=myColour[rightNumber%5];
					context.beginPath();
					context.moveTo(mouseX-10,mouseY);
					context.lineTo(mouseX+10,mouseY);
					context.moveTo(mouseX,mouseY-10);
					context.lineTo(mouseX,mouseY+10);
					context.stroke();
					rightNumber++;
					
					var rightText = document.getElementById('rightCor').value;
					document.getElementById('rightCor').value = rightText+(mouseX-<? echo $width; ?>)+","+mouseY+"\n";
				}
			}
			//if delete points
			else if(ev.pageY<<? echo $height; ?> && document.getElementById('addDeleteButton').value == 'Delete points'){
				if(ev.pageX >= 0 && ev.pageX < <? echo $width; ?> && leftNumber == rightNumber){
					var leftText = document.getElementById('leftCor').value;
					var leftTextArray=leftText.split("\n");
					
					var rightText = document.getElementById('rightCor').value;
					var rightTextArray=rightText.split("\n");	
					
					var newLeft = "";var newRight = "";
					
					for( var i = 0; i < leftTextArray.length; i++){
						var leftTextXY=leftTextArray[i].split(",");
						if(Math.abs(parseInt(leftTextXY[0]) - parseInt(ev.pageX))<5 && Math.abs(parseInt(leftTextXY[1]) - parseInt(ev.pageY))<5 ){
							
						}
						else if(leftTextArray[i]!=''){
							newLeft = newLeft+leftTextArray[i]+"\n"
							newRight = newRight+rightTextArray[i]+"\n"
						}						
					}					
					
					document.getElementById('leftCor').value = newLeft;
					document.getElementById('rightCor').value = newRight;					
					document.getElementById('leftMouse').value = "Delete point";
					drawLines();
				}
				else if(ev.pageX >= 0 && ev.pageX >= <? echo $width; ?>){
					
					var leftText = document.getElementById('leftCor').value;
					var leftTextArray=leftText.split("\n");
					
					var rightText = document.getElementById('rightCor').value;
					var rightTextArray=rightText.split("\n");	
					
					var newLeft = "";var newRight = "";
					
					for( var i = 0; i < rightTextArray.length; i++){
						var rightTextXY=rightTextArray[i].split(",");
						//alert(Math.abs(parseInt(rightTextXY[0]) - parseInt(ev.pageX) + parseInt('<? echo $width; ?>')));
						//alert(Math.abs(parseInt(rightTextXY[1]) - parseInt(ev.pageY) + parseInt('<? echo $width; ?>')));
						if(Math.abs(parseInt(rightTextXY[0]) - parseInt(ev.pageX) + parseInt('<? echo $width; ?>'))<5 && Math.abs(parseInt(rightTextXY[1]) - parseInt(ev.pageY))<5 ){
							
						}
						else if(rightTextArray[i]!=''){
							newLeft = newLeft+leftTextArray[i]+"\n"
							newRight = newRight+rightTextArray[i]+"\n"
						}						
					}					
					
					document.getElementById('leftCor').value = newLeft;
					document.getElementById('rightCor').value = newRight;					
					document.getElementById('rightMouse').value = "Delete point";
					drawLines();
				}
			}
	  }
	  
	  function showStatistics(){
			var statisticString = '';
			statisticString+="<hr><h2>Statistics</h2><hr><br/>";
			var leftArrayX = new Array();
			var leftArrayY = new Array();
			var rightArrayX = new Array();
			var rightArrayY = new Array();
			var offsetArrayX = new Array();
			var offsetArrayY = new Array();
			
            var leftText = document.getElementById('leftCor').value;
			var leftTextArray=leftText.split("\n");
			 
			var rightText = document.getElementById('rightCor').value;
			var rightTextArray=rightText.split("\n");
			//if(leftTextArray.length == rightTextArray.length)
			{					  
				  for( var i = 0; i < leftTextArray.length-1; i++){					  
					  var leftTextXY=leftTextArray[i].split(",");
					  var rightTextXY=rightTextArray[i].split(",");					  
					  leftArrayX.push(parseFloat(leftTextXY[0])); //if(i==0) alert(leftTextXY[0]);
					  leftArrayY.push(parseFloat(leftTextXY[1]));
					  rightArrayX.push(parseFloat(rightTextArray[0]));
					  rightArrayY.push(parseFloat(rightTextArray[1]));
					  offsetArrayX.push(parseFloat(leftTextXY[0])-parseFloat(rightTextXY[0]));
					  offsetArrayY.push(parseFloat(leftTextXY[1])-parseFloat(rightTextXY[1]));					  
				  }				  
			}
			statisticString+="Total correspondence points: "+leftArrayX.length+"<br><hr>";
			
			statisticString+="Offset min Y: "+offsetArrayY.min()+"<br>";
			statisticString+="Offset max Y: "+offsetArrayY.max()+"<br>";
			statisticString+="Offset mean Y: "+offsetArrayY.mean()+"<br>";
			statisticString+="Offset median Y: "+offsetArrayY.median()+"<br>";
			statisticString+="Offset variance Y: "+offsetArrayY.variance()+"<br>";
			statisticString+="Offset stdDev Y: "+offsetArrayY.stdDev()+"<br>";			
			statisticString+="<br><hr>";
			
			statisticString+="Offset min X: "+offsetArrayX.min()+"<br>";
			statisticString+="Offset max X: "+offsetArrayX.max()+"<br>";
			statisticString+="Offset mean X: "+offsetArrayX.mean()+"<br>";
			statisticString+="Offset median X: "+offsetArrayX.median()+"<br>";
			statisticString+="Offset variance X: "+offsetArrayX.variance()+"<br>";
			statisticString+="Offset stdDev X: "+offsetArrayX.stdDev()+"<br>";			
			statisticString+="<br><hr>";
			
			document.getElementById('statisticsPage').innerHTML = statisticString;
			window.scrollBy(0,1500);			
	  }
	  function showTutorial(){
		    var statisticString = '';
			statisticString+="<hr><h2>Quick Tutorial</h2><hr>";
			statisticString+="<p><strong>The idea is in order to align these images, we need you to select between two images pixels which are projecting the same 3D points.</strong></p><p>  Initially, auto-detected correspondence set is displayed on the left and right images. <br />  Edit   mode is first set to <strong>Add</strong> new points. <br />  Change it to be <strong>Delete</strong> to remove   points from the set by clicking on them (either on left or right image).<br />  Click on <strong>Reset all points</strong> to clear them all.<br />  Click <strong>Display random 10%</strong> to reduce the number of correspondence drawn on the images.<br />  <strong>Statistics</strong> display will tell you the overall max, min, standard deviation of the rectification quality. <br />  After you have finished editing, please click <strong>Apply all points</strong> to return the previous page. <br />Thanks!!!<br /></p>";
			
		  	document.getElementById('statisticsPage').innerHTML = statisticString;
			window.scrollBy(0,1500);
	  }
	  
	  function testRectifying(){
		  	context.clearRect ( 0 , 0 , 2000 , 2000 );
		  	//document.getElementById('testRectification').value = '1';
		  	//document.manualForm.submit();
			imageObj1.src = "upload_stereo/left_resized.jpg";
			imageObj2.src = "upload_stereo/right_resized.jpg";
			//redrawRect();
			//imageObj1.src = "upload_stereo/left_raw_resized.jpg";
		  	//imageObj2.src = "upload_stereo/right_raw_resized.jpg";
			//document.getElementById('myCanvas').innerHTML='';			
			//drawHorizontalLines();			
	  }
	  
	  function drawHorizontalLines(){
		  	colourCount = 0;			
			context.lineWidth=1;
			context.strokeStyle=myColour[colourCount%6];			
			context.beginPath();
			context.moveTo(0,50);
			context.lineTo(800,50);	
			context.stroke();
	  }

    </script>
  </head>
<body style="overflow:auto;" id="bodyPage" bgcolor="#F4FFE4">
<div>    
<canvas id="myCanvas" width="<? echo 2*$width+15; ?>" height="<? echo $height; ?>"></canvas>
<form name="manualForm" id="manualForm" method="post" action="">
    <table width="<? echo 2*$width+15; ?>" border="0">
      <tr>
        <td colspan="2">
          <strong>You can
          <select name="addDeleteButton" id="addDeleteButton" style="color:#FF0000">            
            <option value="Add points">Add</option>
            <option value="Delete points">Delete</option>
          </select> 
           left and right correspondence points from the above images -
           <input type="button" name="testRect" id="testRect" value="Show rectified images" onClick="testRectifying();"> <!-- onMouseOut="document.location='manualRect.php';"  the thing that gave the bug -->
          </strong></td>
      </tr>
      
      <tr>
        <td>Left mouse position: 
        <input type="leftMouse" name="leftMouse" id="leftMouse"></td>
        <td>Right mouse position: 
        <input type="rightMouse" name="textfield" id="rightMouse"></td>
      </tr>
      <tr>
        <td>Left correspondences list:</td>
        <td>Right correspondences list:</td>
      </tr>
      <tr>
        <td><textarea name="leftCor" cols="50" rows="6" readonly id="leftCor"><? echo $theLeftData; ?></textarea></td>
        <td><textarea name="rightCor" cols="50" rows="6" readonly id="rightCor"><? echo $theRightData; ?></textarea></td>
      </tr>
    </table>    
     
      <p>        
        <input type="submit" name="button" id="button" value="Apply all points">
        <input type="button" name="button2" id="button2" value="Reset all points" onClick="document.location='manualRect.php?action=reset';">
        <input type="button" name="buttonShow100" id="buttonShow100" value="Display 100%" onClick="tenPercent = 0; drawLines();">
        <input type="button" name="buttonShow10" id="buttonShow10" value="Display random 10%" onClick="tenPercent = Math.floor((Math.random()*10)+1); drawLines();">
        <input type="button" name="buttonStatistics" id="buttonStatistics" value="Display Statistics" onClick="showStatistics();">
        <input type="button" name="buttonRefresh" id="buttonRefresh" value="Refresh" onClick="document.location='manualRect.php';">
        <input type="button" name="buttonTutorial" id="buttonTutorial" value="Quick Tutorial" onClick="showTutorial();">
        <input type="button" name="button3" id="button3" value="Close window" onClick="window.close();">
        <input name="testRectification" type="hidden" id="testRectification" value="0">
      </p>
      <p>(*) Selected points' locations will be automatically refined at sub-pixel level within a 3x3 window.</p>    
      
	  </form>
      <p id="statisticsPage">&nbsp; </p>
</div>
</body>
</html>