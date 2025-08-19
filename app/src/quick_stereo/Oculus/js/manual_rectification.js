    var leftPoint = null;
    var rightPoint = null;

    //colours for the lines
    var colour=["red", "blue", "green", "black", "white", "yellow"];
    var colourCount = 0;

    var leftImage = document.getElementById("leftImage");
    var rightImage = document.getElementById("rightImage");

    var widthImage = leftImage.width;
    var heightImage = leftImage.height;

    var widthWindow = window.innerWidth;
    var heightWindow = window.innerHeight;

    var canvas = document.getElementById("myCanvas");
    
    window.onload = function(){

        var canvas = document.getElementById("myCanvas");
        context = canvas.getContext("2d");

        //display both images
        context.drawImage(leftImage, 0, 0);
        context.drawImage(rightImage, parseInt(widthImage)+10, 0);

        draw_lines_from_correspondences();
    }

    function draw_point(x,y)
    {
        context.lineWidth=1;
        context.strokeStyle=colour[colourCount%6];
        context.beginPath();
        context.moveTo(x-10,y);
        context.lineTo(x+10,y);
        context.moveTo(x,y-10);
        context.lineTo(x,y+10);
        context.stroke();
    }

    function draw_line(x,y)
    {
        var canvas = document.getElementById("myCanvas");
        //if the click is in the left image
        if((x >= 0 && x <= parseInt(widthImage)) && (y>=0 && y<=parseInt(heightImage)) && leftPoint==null)
        {
            draw_point(x,y);

            leftPoint = [x,y];  
            var leftText = document.getElementById('leftCor').value;
            document.getElementById('leftCor').value = leftText+x+","+y+"\n";             
        }
        //else if it is in the right image
        else if((x>=parseInt(widthImage)+10 && x<=canvas.width) && (y>=0 && y<=parseInt(heightImage)) && rightPoint==null)
        {

            draw_point(x, y);

            x=x-parseInt(widthImage)-10;

            rightPoint = [x,y];
            var rightText = document.getElementById('rightCor').value;
            document.getElementById('rightCor').value = rightText+x+","+y+"\n";
        }

        //if there is one click in both images
        if((leftPoint[0] != null && leftPoint[1] != null) && (rightPoint[0] != null && rightPoint[1] != null))
        {
            var leftX = leftPoint[0];
            var leftY = leftPoint[1];

            var rightX = rightPoint[0]+parseInt(widthImage)+10;
            var rightY = rightPoint[1];

            //display a line from the left click to the right click
            context.lineWidth=0.5;
            context.strokeStyle=colour[colourCount%6];
            context.beginPath();
            context.moveTo(leftX,leftY);
            context.lineTo(rightX,rightY);
            context.stroke();

            leftPoint = null;
            rightPoint = null;

            colourCount++;
        }
    }

    document.onmousemove=getMouseCoordinates;

    function getMouseCoordinates(event){

        ev = event || window.event;

        var canvas = document.getElementById("myCanvas");
        
        var rect = canvas.getBoundingClientRect();
        
        var x = Math.round((ev.clientX-rect.left)/(rect.right-rect.left)*canvas.width);
        var y = Math.round((ev.clientY-rect.top)/(rect.bottom-rect.top)*canvas.height);
       

        if(y>=0 && y<=heightImage)
        {
            if(x >= 0 && x<= widthImage){
                var mousePos = "X:"+ x + " Y:"+ y;
                document.getElementById('leftMouse').value = mousePos;
                document.getElementById('rightMouse').value = '';
            }
            else if (x >= widthImage+10 && x<=canvas.width){
                x=x-widthImage-10;
                var mousePos = "X:"+ x + " Y:"+ y;
                document.getElementById('rightMouse').value = mousePos;
                document.getElementById('leftMouse').value = '';
            } 
        }
                   
    }

    var leftNumber = 0;
    var rightNumber = 0;


    function delete_points(x,y)
    {
        var canvas = document.getElementById("myCanvas");

        var find=false;

        if(y >= 0 && y <= heightImage)
        {
            if(x >= 0 && x < parseInt(widthImage))
            {
                var leftText = document.getElementById('leftCor').value;
                var leftTextArray=leftText.split("\n");
                
                var rightText = document.getElementById('rightCor').value;
                var rightTextArray=rightText.split("\n");   
                
                var newLeft = "";var newRight = "";
                
                for( var i = 0; i < leftTextArray.length; i++){
                    var leftTextXY=leftTextArray[i].split(",");

                    if(Math.abs(parseInt(leftTextXY[0]) - x)<5 && Math.abs(parseInt(leftTextXY[1]) - y)<5 ){
                        find=true;
                        
                    }
                    else if(leftTextArray[i]!=''){
                        newLeft = newLeft+leftTextArray[i]+"\n"
                        newRight = newRight+rightTextArray[i]+"\n"
                    }                       
                }     

                document.getElementById('leftCor').value = newLeft;
                document.getElementById('rightCor').value = newRight;

                if(find == true)
                {
                    context.clearRect(0, 0, canvas.width, canvas.height);

                    context.drawImage(leftImage, 0, 0);
                    context.drawImage(rightImage, parseInt(widthImage)+10, 0);

                    draw_lines_from_correspondences();
                }                      
            }
            else if(x >= parseInt(widthImage)+10 && x <= canvas.width){
                
                var leftText = document.getElementById('leftCor').value;
                var leftTextArray=leftText.split("\n");
                
                var rightText = document.getElementById('rightCor').value;
                var rightTextArray=rightText.split("\n");   
                
                var newLeft = "";var newRight = "";
                
                for( var i = 0; i < rightTextArray.length; i++){
                    var rightTextXY=rightTextArray[i].split(",");
                    if(Math.abs(parseInt(rightTextXY[0]) - parseInt(x) + parseInt(widthImage)+10)<5 && Math.abs(parseInt(rightTextXY[1]) - parseInt(y))<5 ){
                        find=true;                    
                    }
                    else if(rightTextArray[i]!=''){
                        newLeft = newLeft+leftTextArray[i]+"\n"
                        newRight = newRight+rightTextArray[i]+"\n"
                    }                       
                }                   
                
                document.getElementById('leftCor').value = newLeft;
                document.getElementById('rightCor').value = newRight;

                if(find==true)
                {
                    context.clearRect(0, 0, canvas.width, canvas.height);

                    context.drawImage(leftImage, 0, 0);
                    context.drawImage(rightImage, parseInt(widthImage)+10, 0);

                    draw_lines_from_correspondences(); 
                }
            }
        }
    }

    function onclick_page(event)
    {
        var x = event.clientX;
        var y = event.clientY;

        ev = event || window.event;

        var canvas = document.getElementById("myCanvas"); 

        var rect = canvas.getBoundingClientRect();
        
        var mouseX = Math.round((ev.clientX-rect.left)/(rect.right-rect.left)*canvas.width);
        var mouseY = Math.round((ev.clientY-rect.top)/(rect.bottom-rect.top)*canvas.height);

        if(document.getElementById('addDeleteButton').value == 'Delete points')
        {
            delete_points(mouseX,mouseY);
        }
        else if(document.getElementById('addDeleteButton').value == 'Add points')
        {
            draw_line(mouseX,mouseY);
        }

        
        
    }

    function draw_lines_from_correspondences(){         

        var leftText = document.getElementById('leftCor').value;
        var lineLeft=leftText.split("\n");

        var rightText = document.getElementById('rightCor').value;
        var lineRight=rightText.split("\n");

        for(var i=1; i<=lineLeft.length; i++)
        {
            
            var leftPoints=lineLeft[i].split(",");
            var rightPoints = lineRight[i].split(",");

            var leftX = parseInt(leftPoints[0]);
            var leftY = parseInt(leftPoints[1]);

            var rightX = parseInt(rightPoints[0])+parseInt(widthImage)+10;
            var rightY = parseInt(rightPoints[1]);

            context.lineWidth=0.5;
            context.strokeStyle=colour[colourCount%6];
            context.beginPath();
            context.moveTo(leftX,leftY);
            context.lineTo(rightX,rightY);
            context.stroke();

            draw_point(leftX, leftY);
            draw_point(rightX, rightY);

            colourCount++;
        }
      }