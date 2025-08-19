<script type="text/javascript" src="webgl/glMatrix-0.9.5.min.js"></script>
<script type="text/javascript" src="webgl/webgl-utils.js"></script>
<script type="text/javascript">
    var gl;
	var canvas = document.getElementById("webgl_canvas");
    //initGL(canvas);
	try {
            gl = canvas.getContext("experimental-webgl");
            gl.viewportWidth = canvas.width;
            gl.viewportHeight = canvas.height;
        } catch (e) {
    }
	if (!gl) 
	{
        alert("Could not initialise WebGL in your Browser, Please use Google Chrome");
		//document.getElementById('webGLFrame').innerHTML='';        
    }
</script>
<canvas id="webgl_canvas" style="border: none;" width="800" height="600">
</canvas>