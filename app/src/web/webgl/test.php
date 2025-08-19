<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <title>CH5EX10: Moving In A Simple Geometric Spiral </title>
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
      <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
      <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
      <style>
          .wrapper {
              width: 700px;
              height:350px;
              border:2px solid green;
              padding:15px;
          }
          .uppleft{
              display: inline;
              margin-left: 30px;
          }
          canvas{
              border:1px solid red;
          }
          #sliderwrapper{
              display: inline-block;
              position:relative; 
              width:37px; height:300px; 
              border:1px solid blue;
          }
          #amount{ 
              position:absolute; 
              left:5px; top:5px; 
              margin-bottom:15px; 
              width:23px; 
              border:0; color:#f6931f; 
              font-weight:bold; 
          }
          #slider-vertical{ 
              position:absolute; 
              left:10px; top:40px; 
              width:15px; height:225px; 
              border:0px; color:#f6931f; 
              font-weight:bold; 
          }
      </style>
    </head>
    <body>
        <div class="wrapper">
            <div id="sliderwrapper">
                <input type="text" id="amount" />
                <div id="slider-vertical"></div>
            </div>
            <div class="uppleft">
                <canvas id="canvasOne" width="300" height="300">
                    Your browser does not support HTML5 Canvas.
                </canvas>
                <canvas id="canvasTwo" width="300" height="300">
                    Your browser does not support HTML5 Canvas.
                </canvas>
            </div>
        </div>
    </body>

    <script type="text/javascript">

        var startingValue=20;

        // handles user moving the slider
        $( "#slider-vertical" ).slider({
          orientation: "vertical",
          range: "min",
          min: 0,
          max: 100,
          value: startingValue,
          slide: function( event, ui ) {
            $( "#amount" ).val( ui.value );
            counterSliderNew('slide1', '100', ui.value);
          }
        });

        // get an array of 100 points between start and end of line
        var points=linePoints(16, 170, 200, 80,100);

        // draw the initial point based on the beginning slider value
        counterSliderNew('slide1', '100', startingValue);


        function counterSliderNew(sID, maxValue,theSliderValue) {

            var slideVal = theSliderValue; // document.getElementById(sID).value;

            // get the slider value and get the point at points[slideVal]
            var point=points[slideVal];
            erase('canvasTwo');
            drawSlopeCurve2('canvasTwo',16,170,point.x,point.y);

            if (maxValue == 100) {

                slideVal = slideVal / 100;
            }

            if (slideVal == 0) {

            } else if (slideVal > 0 && slideVal <= 34) {
                erase('canvasOne');
                drawBezier2('canvasOne', new Array({
                    x : 18.8,
                    y : 75
                }, {
                    x : 28,
                    y : 160
                }, {
                    x : 228,
                    y : 165
                }), slideVal);

            } else if (slideVal > 34 && slideVal <= 67) {
                //alert(slideVal);
                erase('canvasOne');
                drawBezier2('canvasOne', new Array({
                    x : 18.8,
                    y : 75
                }, {
                    x : 28,
                    y : 160
                }, {
                    x : 228,
                    y : 165
                }), slideVal);
                staticGraph5('canvasTwo');
            } else if (slideVal > 67 && slideVal <= 100) {
                erase('canvasOne');
                drawBezier2('canvasOne', new Array({
                    x : 18.8,
                    y : 75
                }, {
                    x : 28,
                    y : 160
                }, {
                    x : 228,
                    y : 165
                }), slideVal);

            }
        }

        function drawBezier2(canId, points, slideVal) {

            var canvas = document.getElementById(canId);

            var context = canvas.getContext("2d");
            // Draw guides
            context.lineWidth = 2;
            context.strokeStyle = "rgb(113, 113, 213)";
            context.beginPath();
            // Label end points
            context.fillStyle = "rgb(0, 0, 0)";
            // Draw spline segemnts
            context.moveTo(points[0].x, points[0].y);
            for (var t = 0; t <= slideVal; t += 0.1) {
                context.lineTo(Math.pow(1 - t, 2) * points[0].x + 2 * (1 - t) * t * points[1].x + Math.pow(t, 2) * points[2].x, Math.pow(1 - t, 2) * points[0].y + 2 * (1 - t) * t * points[1].y + Math.pow(t, 2) * points[2].y);
            }

            // Stroke path
            context.stroke();
        }

        function erase(canvasId) {

            var canvas = document.getElementById(canvasId);
            var context = canvas.getContext("2d");
            context.beginPath();
            context.clearRect(0, 0, canvas.width, canvas.height);
            canvas.width = canvas.width;

        }

        function drawSlopeCurve2(canId, mvx, mvy, lnx, lny) {
            var canvas = document.getElementById(canId);
            var context = canvas.getContext('2d');

            context.beginPath();
            context.moveTo(mvx, mvy);
            context.lineTo(lnx, lny);
            context.lineWidth = 0.6;
            context.stroke();
        }


        function linePoints(x1, y1, x2, y2, frames) {
            var dx = x2 - x1;
            var dy = y2 - y1;
            var length = Math.sqrt(dx * dx + dy * dy);
            var incrementX = dx / frames;
            var incrementY = dy / frames;
            var a = new Array();

            a.push({ x: x1, y: y1 });
            for (var frame = 0; frame < frames - 1; frame++) {
                a.push({
                    x: x1 + (incrementX * frame),
                    y: y1 + (incrementY * frame)
                });
            }
            a.push({ x: x2, y: y2 });
            return (a);
        }

    </script>

</html>