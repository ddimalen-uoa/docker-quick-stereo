
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Oculus Rift - Video</title>
		<meta charset="utf-8">
		<style>
			body {
				margin: 0px;
				background-color: #000000;
				overflow: hidden;
			}

			#info {
				position: absolute;
				top: 0px; width: 100%;
				color: #ffffff;
				padding: 5px;
				font-family:Monospace;
				font-size:13px;
				font-weight: bold;
				text-align:center;
			}

			a {
				color: #ff8800;
			}
		</style>
	</head>
	<body>

		<script src="build/three.min.js"></script>
		<script src="js2/ImprovedNoise.js"></script>
		<script src="js2/effects/OculusRiftEffect.js"></script>
		<script src="js2/controls/FirstPersonControls.js"></script>
		<script src="js2/controls/OculusControls.js"></script>


  		<script src="js2/three.js"></script>

		<script src="js2/loaders/PLYLoader.js"></script>

		<script src="js2/OrbitControls.js"></script>

		<script src="js2/Projector.js"></script>
		<script src="js2/CanvasRenderer.js"></script>

		<script src="js2/Detector.js"></script>
		<script src="js2/stats.min.js"></script>

  		<script src="js2/CanvasTexture.js"></script>
  		<script src="js2/CompressedTexture.js"></script>
  		<script src="js2/CubeTexture.js"></script>
  		<script src="js2/DataTexture.js"></script>
  		<script src="js2/DepthTexture.js"></script>
  		<script src="js2/Texture.js"></script>
  		<script src="js2/VideoTexture.js"></script>

  		<script src="js2/Geometry.js"></script>

  		<script src="js2/UVsDebug.js"></script>
  		<script src="js2/GeometryUtils.js"></script>


		<script src="js2/loaders/DDSLoader.js"></script>
		<script src="js2/loaders/MTLLoader.js"></script>
		<script src="js2/loaders/OBJLoader.js"></script>

		<script src="js2/Detector.js"></script>
		<script src="js2/libs/stats.min.js"></script>

		<script src='js2/threex.videotexture.js'></script>


		<video id="video" autoplay style="display:none">
			<source src="./3D_L.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
		</video>

		<script>

			var camera, scene, renderer;
			var realcamera;
			var guiVisible = true;

			var mesh, effect, controls, oculuscontrol;

			var meshes = [];
			var meshparent = [];
			var meshparent2 = [];

			var cols = 50;
			var rows = 30;
			var tot = cols * rows;
			var clock = new THREE.Clock();
			var perlin;
			init();
			animate();


			function init() {

				renderer = new THREE.WebGLRenderer();
				renderer.setSize( window.innerWidth, window.innerHeight );

				perlin = new ImprovedNoise();

				camera = new THREE.PerspectiveCamera( 90, window.innerWidth / window.innerHeight, 1, 5000 );
				camera.position.x = -20;

				scene = new THREE.Scene();

				effect = new THREE.OculusRiftEffect( renderer );
				effect.setSize( window.innerWidth, window.innerHeight );

				effect.separation = 2000;
				effect.distortion = 10;
		        effect.fov = 1100;

				controls = new THREE.FirstPersonControls( camera );
				controls.lookVertical = true;

				oculuscontrol = new THREE.OculusControls( camera );

				document.body.appendChild( renderer.domElement );

				//Geometry
				var updateFcts	= [];
				var canPlayMp4	= document.createElement('video').canPlayType('video/mp4') !== '' ? true : false
				var canPlayOgg	= document.createElement('video').canPlayType('video/ogg') !== '' ? true : false

				var extension = eval('(<?php echo json_encode($_GET['extension'])?>)');

				var url	= './data/Videos/rightVideo.'+extension;

				// create the videoTexture
				var videoTexture= new THREEx.VideoTexture(url)
				var video	= videoTexture.video
				updateFcts.push(function(delta, now){
					videoTexture.update(delta, now)
				})
				
				// use the texture in a THREE.Mesh
				var geometry	= new THREE.CubeGeometry(0,50,45);
				var material	= new THREE.MeshBasicMaterial({
					map	: videoTexture.texture
				});
				var mesh	= new THREE.Mesh( geometry, material );
				scene.add( mesh );

				var lastTimeMsec= null

				requestAnimationFrame(function animate(nowMsec){
					// keep looping
					requestAnimationFrame( animate );
					// measure time
					lastTimeMsec	= lastTimeMsec || nowMsec-1000/60
					var deltaMsec	= Math.min(200, nowMsec - lastTimeMsec)
					lastTimeMsec	= nowMsec
					// call each update function
					updateFcts.forEach(function(updateFn){
						updateFn(deltaMsec/1000, nowMsec/1000)
					})
				})

				window.addEventListener( 'resize', onWindowResize, false );
				document.addEventListener('keydown', keyPressed, false);

				oculuscontrol.connect();
			}

			function onWindowResize() {
				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();
				realcamera.aspect = window.innerWidth / window.innerHeight;
				realcamera.updateProjectionMatrix();
				effect.setSize( window.innerWidth, window.innerHeight );
				effect.separation = 200;
				effect.distortion = 10;
		        effect.fov = 1100;
				controls.handleResize();
			}

			function keyPressed(event) {
				if (event.keyCode === 72) { // H
					guiVisible = !guiVisible;
					document.getElementById('info').style.display = guiVisible ? "block" : "none";
				}
			}

			function animate() {
				requestAnimationFrame( animate );

				var t = clock.getElapsedTime();

				// for (var i=0; i<meshes.length; i++) {
				// 	var c = Math.floor(i % cols);
				// 	var r = Math.floor(i / cols);
				// 	meshparent2[i].rotation.y = (c * 3.142 * 2 / cols);
				// 	meshparent[i].rotation.x = ((r+10) * 3.142 * 2 / (rows+20));
				// 	meshes[i].position.z = 1400 - 1350 * perlin.noise(c*8/cols,r*8/rows,t/2);
				// }

				controls.update( clock.getDelta() );
				oculuscontrol.update( clock.getDelta() );
				
				effect.render( scene, camera );
			}

		</script>

	</body>
</html>
