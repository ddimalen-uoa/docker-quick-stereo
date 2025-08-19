<?
$location = $_GET["location"];
$file = "../".$location."_coordinates.obj";

include('simpleimage.php');
$image = new SimpleImage();
$image->load("../".$location."_l_resized.jpg");
$originalHeight = $image->getHeight();
$originalWidth = $image->getWidth();
if($originalHeight > $originalWidth) $offsetValue = 1000; else $offsetValue = 0;
$image->resize(512,512);
$image->save("../../quick_stereo/upload_stereo/temp_gl_texture.jpg");
   
$f = fopen($file, "r");
$outString = "";
$outStringF = "";
$minX = 0; $maxX=0; $minY = 0; $maxY=0;
$countere = 0;
while ( $line = fgets($f, 1000) ) {
	if($countere++ > $offsetValue)
	{
	if(substr($line, 0, 1) == "v"){
		$items = explode(" ",$line);
		$minX = min($minX, $items[1]);
		$maxX = max($maxX, $items[1]);
		$minY = min($minY, $items[2]);
		$maxY = max($maxY, $items[2]);
		$outString.=number_format($items[1], 3, '.', '');
		$outString.=",";
		$outString.=number_format($items[2], 3, '.', '');
		$outString.=",";
		$outString.=number_format($items[3], 3, '.', '');	
		$outString.=",";
	} else if(substr($line, 0, 1) == "f"){
		
		$items = explode(" ",$line);
		$outStringF.=$items[1];
		$outStringF.=",";
		$outStringF.=$items[2];
		$outStringF.=",";
		$outStringF.=$items[3];		
		$outStringF.=",";
	}
	}
}
$outString = str_replace("\r\n","",substr($outString, 0, -1));
$outStringF = str_replace("\r\n","",substr($outStringF, 0, -1));
//$outString = $outString."_End";
//print $outString."<br/>"; return;
?>
<html>

<head>
<title>WebGL display Result - Minh Nguyen 2012</title>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">

<script type="text/javascript" src="glMatrix-0.9.5.min.js"></script>
<script type="text/javascript" src="webgl-utils.js"></script>

<script id="shader-fs" type="x-shader/x-fragment">
    #ifdef GL_ES
    precision highp float;
    #endif

    varying vec2 vTextureCoord;
    varying vec3 vLightWeighting;

    uniform sampler2D uSampler;

    void main(void) {
        vec4 textureColor = texture2D(uSampler, vec2(vTextureCoord.s, vTextureCoord.t));
        gl_FragColor = vec4(textureColor.rgb * vLightWeighting, textureColor.a);
    }
</script>

<script id="shader-vs" type="x-shader/x-vertex">
    attribute vec3 aVertexPosition;
    attribute vec3 aVertexNormal;
    attribute vec2 aTextureCoord;

    uniform mat4 uMVMatrix;
    uniform mat4 uPMatrix;
    uniform mat3 uNMatrix;

    uniform vec3 uAmbientColor;

    uniform vec3 uLightingDirection;
    uniform vec3 uDirectionalColor;

    uniform bool uUseLighting;

    varying vec2 vTextureCoord;
    varying vec3 vLightWeighting;

    void main(void) {
        gl_Position = uPMatrix * uMVMatrix * vec4(aVertexPosition, 1.5);
        vTextureCoord = aTextureCoord;

        if (!uUseLighting) {
            vLightWeighting = vec3(1.0, 1.0, 1.0);
        } else {
            vec3 transformedNormal = uNMatrix * aVertexNormal;
            float directionalLightWeighting = max(dot(transformedNormal, uLightingDirection), 0.0);
            vLightWeighting = uAmbientColor + uDirectionalColor * directionalLightWeighting;
        }
    }
</script>


<script type="text/javascript">

    var gl;

    function initGL(canvas) {
        try {
            gl = canvas.getContext("experimental-webgl");
            gl.viewportWidth = canvas.width;
            gl.viewportHeight = canvas.height;
        } catch (e) {
        }
        if (!gl) 
		{
            //alert("Could not initialise WebGL in your Browser, Please use Google Chrome");
			//document.getElementById('webGLFrame').innerHTML='';
        }
    }


    function getShader(gl, id) {
        var shaderScript = document.getElementById(id);
        if (!shaderScript) {
            return null;
        }

        var str = "";
        var k = shaderScript.firstChild;
        while (k) {
            if (k.nodeType == 3) {
                str += k.textContent;
            }
            k = k.nextSibling;
        }

        var shader;
        if (shaderScript.type == "x-shader/x-fragment") {
            shader = gl.createShader(gl.FRAGMENT_SHADER);
        } else if (shaderScript.type == "x-shader/x-vertex") {
            shader = gl.createShader(gl.VERTEX_SHADER);
        } else {
            return null;

        }

        gl.shaderSource(shader, str);
        gl.compileShader(shader);

        if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
            alert(gl.getShaderInfoLog(shader));
            return null;
        }

        return shader;
    }


    var shaderProgram;
	var degree = 45;

    function initShaders() {
        var fragmentShader = getShader(gl, "shader-fs");
        var vertexShader = getShader(gl, "shader-vs");

        shaderProgram = gl.createProgram();
        gl.attachShader(shaderProgram, vertexShader);
        gl.attachShader(shaderProgram, fragmentShader);
        gl.linkProgram(shaderProgram);

        if (!gl.getProgramParameter(shaderProgram, gl.LINK_STATUS)) {
            alert("Could not initialise shaders");
        }

        gl.useProgram(shaderProgram);

        shaderProgram.vertexPositionAttribute = gl.getAttribLocation(shaderProgram, "aVertexPosition");
        gl.enableVertexAttribArray(shaderProgram.vertexPositionAttribute);

        shaderProgram.textureCoordAttribute = gl.getAttribLocation(shaderProgram, "aTextureCoord");
        gl.enableVertexAttribArray(shaderProgram.textureCoordAttribute);

        shaderProgram.vertexNormalAttribute = gl.getAttribLocation(shaderProgram, "aVertexNormal");
        gl.enableVertexAttribArray(shaderProgram.vertexNormalAttribute);

        shaderProgram.pMatrixUniform = gl.getUniformLocation(shaderProgram, "uPMatrix");
        shaderProgram.mvMatrixUniform = gl.getUniformLocation(shaderProgram, "uMVMatrix");
        shaderProgram.nMatrixUniform = gl.getUniformLocation(shaderProgram, "uNMatrix");
        shaderProgram.samplerUniform = gl.getUniformLocation(shaderProgram, "uSampler");
        shaderProgram.useLightingUniform = gl.getUniformLocation(shaderProgram, "uUseLighting");
        shaderProgram.ambientColorUniform = gl.getUniformLocation(shaderProgram, "uAmbientColor");
        shaderProgram.lightingDirectionUniform = gl.getUniformLocation(shaderProgram, "uLightingDirection");
        shaderProgram.directionalColorUniform = gl.getUniformLocation(shaderProgram, "uDirectionalColor");
    }


    function handleLoadedTexture(texture) {
        gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
        gl.bindTexture(gl.TEXTURE_2D, texture);
        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, texture.image);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR_MIPMAP_NEAREST);
        gl.generateMipmap(gl.TEXTURE_2D);

        gl.bindTexture(gl.TEXTURE_2D, null);
    }


    var objectItTexture;
	
	
	function randomString() {
		var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
		var string_length = 8;
		var randomstring = '';
		for (var i=0; i<string_length; i++) {
			var rnum = Math.floor(Math.random() * chars.length);
			randomstring += chars.substring(rnum,rnum+1);
		}
	return randomstring;
	}

    function initTexture() {
        objectItTexture = gl.createTexture();
        objectItTexture.image = new Image();
        objectItTexture.image.onload = function () {
            handleLoadedTexture(objectItTexture)
        }

        objectItTexture.image.src = "../../quick_stereo/upload_stereo/temp_gl_texture.jpg?"+randomString();
    }

    var mvMatrix = mat4.create();
    var mvMatrixStack = [];
    var pMatrix = mat4.create();

    function mvPushMatrix() {
        var copy = mat4.create();
        mat4.set(mvMatrix, copy);
        mvMatrixStack.push(copy);
    }

    function mvPopMatrix() {
        if (mvMatrixStack.length == 0) {
            throw "Invalid popMatrix!";
        }
        mvMatrix = mvMatrixStack.pop();
    }

    function setMatrixUniforms() {
        gl.uniformMatrix4fv(shaderProgram.pMatrixUniform, false, pMatrix);
        gl.uniformMatrix4fv(shaderProgram.mvMatrixUniform, false, mvMatrix);

        var normalMatrix = mat3.create();
        mat4.toInverseMat3(mvMatrix, normalMatrix);
        mat3.transpose(normalMatrix);
        gl.uniformMatrix3fv(shaderProgram.nMatrixUniform, false, normalMatrix);
    }


    function degToRad(degrees) {
        return degrees * Math.PI / 180;
    }


    var mouseDown = false;
    var lastMouseX = null;
    var lastMouseY = null;

    var objectItRotationMatrix = mat4.create();
    mat4.identity(objectItRotationMatrix);

    function handleMouseDown(event) {
        mouseDown = true;
        lastMouseX = event.clientX;
        lastMouseY = event.clientY;
    }


    function handleMouseUp(event) {
        mouseDown = false;
    }


    function handleMouseMove(event) {
        if (!mouseDown) {
            return;
        }
        var newX = event.clientX;
        var newY = event.clientY;

        var deltaX = newX - lastMouseX
        var newRotationMatrix = mat4.create();
        mat4.identity(newRotationMatrix);
        mat4.rotate(newRotationMatrix, degToRad(deltaX / 10), [0, 1, 0]);

        var deltaY = newY - lastMouseY;
        mat4.rotate(newRotationMatrix, degToRad(deltaY / 10), [1, 0, 0]);

        mat4.multiply(newRotationMatrix, objectItRotationMatrix, objectItRotationMatrix);

        lastMouseX = newX
        lastMouseY = newY;
    }
	
	/** This is high-level function.
 * It must react to delta being more/less than zero.
 */
	function handle(delta) {
			if (delta < 0){
				degree++;
			}				
			else{
				degree--;
			}
	}
	
	/** Event handler for mouse wheel event.
	 */
	function wheel(event){
			var delta = 0;
			if (!event) /* For IE. */
					event = window.event;
			if (event.wheelDelta) { /* IE/Opera. */
					delta = event.wheelDelta/120;
			} else if (event.detail) { /** Mozilla case. */
					/** In Mozilla, sign of delta is different than in IE.
					 * Also, delta is multiple of 3.
					 */
					delta = -event.detail/3;
			}
			/** If delta is nonzero, handle it.
			 * Basically, delta is now positive if wheel was scrolled up,
			 * and negative, if wheel was scrolled down.
			 */
			if (delta)
					handle(delta);
			/** Prevent default actions caused by mouse wheel.
			 * That might be ugly, but we handle scrolls somehow
			 * anyway, so don't bother here..
			 */
			if (event.preventDefault)
					event.preventDefault();
		event.returnValue = false;
	}
	
	/** Initialization code. 
	 * If you use your own event management code, change it as required.
	 */
	if (window.addEventListener)
			/** DOMMouseScroll is for mozilla. */
			window.addEventListener('DOMMouseScroll', wheel, false);
	/** IE/Opera. */
	window.onmousewheel = document.onmousewheel = wheel;



    var objectItVertexPositionBuffer;
    var objectItVertexNormalBuffer;
    var objectItVertexTextureCoordBuffer;
    var objectItVertexIndexBuffer;
	
	var coordinatess=[<? echo $outString; ?>];
	var facess=[<? echo $outStringF; ?>];

    function initBuffers() {
        var latitudeBands = 256;
        var longitudeBands = 256;
        var radius = 4.5/(<? echo ($maxY - $minY) ?>);

        var vertexPositionData = [];
        var normalData = [];
        var textureCoordData = [];
		var counter = 0;
		var offSetY = (<? echo ($maxY + $minY) ?>)/2.0;		
		var face = parseInt(facess[0]);		
		var face, x, y, z, u, v;	
        for (var i=0; i < facess.length; i=i+1) {  			
			face = parseInt(facess[i]) - 1;
			x = -coordinatess[3*face];//cosPhi * sinTheta;
			y = coordinatess[3*face+1];//cosTheta;
			z = -coordinatess[3*face+2];//(sinPhi * sinTheta); if(z < 0) z*= -1;
			u = (x+(<? echo $maxX ?>))/(<? echo ($maxX - $minX) ?>);
			v = (y-(<? echo $minY ?>))/(<? echo ($maxY - $minY) ?>);			
			normalData.push(x);
			normalData.push(y);
			normalData.push(z);
			textureCoordData.push(u);
			textureCoordData.push(v);
			vertexPositionData.push(radius * x);
			vertexPositionData.push(radius * (y-offSetY));
			vertexPositionData.push(radius*z);				
        }
        var indexData = [];
       	for (var i=0; i < facess.length; i++) {  		
			indexData.push(i);  
        }		
		counter = 0;		
        objectItVertexNormalBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, objectItVertexNormalBuffer);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(normalData), gl.STATIC_DRAW);
        objectItVertexNormalBuffer.itemSize = 3;
        objectItVertexNormalBuffer.numItems = normalData.length / 3;

        objectItVertexTextureCoordBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, objectItVertexTextureCoordBuffer);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoordData), gl.STATIC_DRAW);
        objectItVertexTextureCoordBuffer.itemSize = 2;
        objectItVertexTextureCoordBuffer.numItems = textureCoordData.length / 2;

        objectItVertexPositionBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, objectItVertexPositionBuffer);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexPositionData), gl.STATIC_DRAW);
        objectItVertexPositionBuffer.itemSize = 3;
        objectItVertexPositionBuffer.numItems = vertexPositionData.length / 3;

        objectItVertexIndexBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, objectItVertexIndexBuffer);
        gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(indexData), gl.STATIC_DRAW);
        objectItVertexIndexBuffer.itemSize = 1;
        objectItVertexIndexBuffer.numItems = indexData.length;
    }


    function drawScene(counter) {
		var controlValue = Math.sin(0.005*counter);
        gl.viewport(0, 0, gl.viewportWidth, gl.viewportHeight);
        gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);

        mat4.perspective(degree, gl.viewportWidth / gl.viewportHeight, 0.1, 20.0, pMatrix);

        //var lighting = document.getElementById("lighting").checked;
        gl.uniform1i(shaderProgram.useLightingUniform, 1);
        //if (lighting) 
		{
            gl.uniform3f(
                shaderProgram.ambientColorUniform,
                (Math.abs(controlValue))+0.4,
                (Math.abs(controlValue))+0.4,
                (Math.abs(controlValue)+0.4)
            );

            var lightingDirection = [
                parseFloat(1),
                parseFloat(1),
                parseFloat(-1)
            ];
            var adjustedLD = vec3.create();
            vec3.normalize(lightingDirection, adjustedLD);
            vec3.scale(adjustedLD, -1);
            gl.uniform3fv(shaderProgram.lightingDirectionUniform, adjustedLD);

            gl.uniform3f(
                shaderProgram.directionalColorUniform,
                parseFloat(1),
                parseFloat(1),
                parseFloat(1)
            );
        }

        mat4.identity(mvMatrix);

        mat4.translate(mvMatrix, [controlValue, 0, -7+(Math.sin(0.0025*counter))]);

        mat4.multiply(mvMatrix, objectItRotationMatrix);

        gl.activeTexture(gl.TEXTURE0);
        gl.bindTexture(gl.TEXTURE_2D, objectItTexture);
        gl.uniform1i(shaderProgram.samplerUniform, 0);

        gl.bindBuffer(gl.ARRAY_BUFFER, objectItVertexPositionBuffer);
        gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, objectItVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);

        gl.bindBuffer(gl.ARRAY_BUFFER, objectItVertexTextureCoordBuffer);
        gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, objectItVertexTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);

        gl.bindBuffer(gl.ARRAY_BUFFER, objectItVertexNormalBuffer);
        gl.vertexAttribPointer(shaderProgram.vertexNormalAttribute, objectItVertexNormalBuffer.itemSize, gl.FLOAT, false, 0, 0);

        gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, objectItVertexIndexBuffer);
        setMatrixUniforms();
		gl.drawElements(gl.TRIANGLES, objectItVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);		
    }

	var counter = 0;
    function tick() {
        requestAnimFrame(tick);
        drawScene(counter);
		counter++;
    }


    function webGLStart() {
        var canvas = document.getElementById("webgl_canvas");
        initGL(canvas);
        initShaders();
        initBuffers();
        initTexture();		
        gl.enable(gl.DEPTH_TEST);
        canvas.onmousedown = handleMouseDown;
        document.onmouseup = handleMouseUp;
        document.onmousemove = handleMouseMove;
        tick();
    }	

</script>

<meta http-equiv="refresh" content="10000; url=webGL.php">
</head>

<body onLoad="webGLStart();" bgcolor="#000000">
<canvas id="webgl_canvas" style="border: none;" width="380" height="380"></canvas>
</body>

</html>
