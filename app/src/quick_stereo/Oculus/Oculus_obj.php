<?php
	session_start();
	if(isset($_SESSION["postSession"])){
		$sessionLocation = "StoreResults/".$_SESSION["postSession"]."/";
	}
	else{
		$sessionLocation = "";
	}

	$path = '../upload_stereo/'.$sessionLocation;

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Oculus Rift - OBJ model</title>
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

		<div id="info">
			(left click: forward, a/s/w/d/r/f: move, h: hide text)
		</div>

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
				camera.position.x = -1;
				camera.position.y = 0.3;
				camera.position.z = 0.4;

				renderer.setClearColor( 0xffffff, 0);

				scene = new THREE.Scene();

				effect = new THREE.OculusRiftEffect( renderer );
				effect.setSize( window.innerWidth, window.innerHeight );

				effect.separation = 2000;
				effect.distortion = 10;
		        effect.fov = 1100;

				controls = new THREE.FirstPersonControls( camera );
				controls.movementSpeed = 10000;
				controls.lookSpeed = 0;
				controls.lookVertical = true;

				oculuscontrol = new THREE.OculusControls( camera );

				document.body.appendChild( renderer.domElement );


				//Light
				var	hemiLight = new THREE.HemisphereLight( 0xffffff, 0xffffff, 0.6 );
				hemiLight.color.setHSL( 0.6, 1, 0.6 );
				hemiLight.groundColor.setHSL( 0.095, 1, 0.75 );
				hemiLight.position.set( 0, 500, 0 );
				scene.add( hemiLight );


				//Geometry
				var onProgress = function ( xhr ) {
					if ( xhr.lengthComputable ) {
						var percentComplete = xhr.loaded / xhr.total * 100;
						console.log( Math.round(percentComplete, 2) + '% downloaded' );
					}
				};
				var onError = function ( xhr ) { };
				THREE.Loader.Handlers.add( /\.dds$/i, new THREE.DDSLoader() );
				var mtlLoader = new THREE.MTLLoader();

				var path = eval('(<?php echo json_encode($path)?>)');

				mtlLoader.setPath( path );
				mtlLoader.load( 'model.mtl', function( materials ) {
					materials.preload();
					var objLoader = new THREE.OBJLoader();
					objLoader.setMaterials( materials );
					objLoader.setPath( path );
					objLoader.load( 'model.obj', function ( object ) {

						object.scale.multiplyScalar( 0.001 );

						object.rotation.y = -90*Math.PI/180;

						scene.add( object );
					}, onProgress, onError );
				});

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

				controls.update( clock.getDelta() );
				oculuscontrol.update( clock.getDelta() );
				
				effect.render( scene, camera );
			}

		</script>

	</body>
</html>

