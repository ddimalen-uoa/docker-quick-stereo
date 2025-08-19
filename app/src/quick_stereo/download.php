<?
session_start();
if(isset($_SESSION["postSession"])){
	$sessionLocation = "StoreResults/".$_SESSION["postSession"]."/";
}
else{
	$sessionLocation = "";
}
if($_GET["request"] == "anaglyph"){
	$filename = 'upload_stereo/'.$sessionLocation.'anaglyph.jpg';
}
else if($_GET["request"] == "depthReserved"){
	$filename = 'upload_stereo/'.$sessionLocation.'depthReserved.jpg';
}
else if($_GET["request"] == "coordinates"){
	$filename = 'upload_stereo/'.$sessionLocation.'coordinates.obj';
}
else if($_GET["request"] == "autostereogram"){
	$filename = 'upload_stereo/'.$sessionLocation.'autostereogram.jpg';
}
else if($_GET["request"] == "crossEye"){
	$filename = 'upload_stereo/'.$sessionLocation.'crossEye.jpg';
}
else if($_GET["request"] == "fundamental_matrix"){
	$filename = 'upload_stereo/'.$sessionLocation.'fundamental_matrix.xml';
}
else if($_GET["request"] == "left_raw_resized"){
	$filename = 'upload_stereo/'.$sessionLocation.'left_raw_resized.jpg';
}
else if($_GET["request"] == "left_rect_resized"){
	copy('upload_stereo/'.$sessionLocation.'left_resized.jpg', 'upload_stereo/'.$sessionLocation.'left_rect_resized.jpg');
	$filename = 'upload_stereo/'.$sessionLocation.'left_rect_resized.jpg';
}
else if($_GET["request"] == "right_rect_resized"){
	copy('upload_stereo/'.$sessionLocation.'right_resized.jpg', 'upload_stereo/'.$sessionLocation.'right_rect_resized.jpg');
	$filename = 'upload_stereo/'.$sessionLocation.'right_rect_resized.jpg';
}

else if($_GET["request"] == "depthmap"){
	$filename = 'upload_stereo/'.$sessionLocation.'depthmap.jpg';
}

else if($_GET["request"] == "correspondences"){
	$filename = 'upload_stereo/'.$sessionLocation.'correspondences.csv';
}

else if($_GET["request"] == "colourdepthmap"){
	$filename = 'upload_stereo/'.$sessionLocation.'colourdepthmap.jpg';
}

else if($_GET["request"] == "p_stereogram"){
	$filename = 'upload_stereo/'.$sessionLocation.'p_stereogram.jpg';
}
else if($_GET["request"] == "result"){
	$filename = 'upload_stereo/'.$sessionLocation.'result.jpg';
}
else if($_GET["request"] == "returnParameters"){
	$filename = 'upload_stereo/'.$sessionLocation.'returnParameters.txt';
}
else if($_GET["request"] == "right_raw_resized"){
	$filename = 'upload_stereo/'.$sessionLocation.'right_raw_resized.jpg';
}
else if($_GET["request"] == "shareResults"){
	$filename = 'upload_stereo/'.$sessionLocation.'shareResults.jpg';
}
else if($_GET["request"] == "parallelEye"){
	$filename = 'upload_stereo/'.$sessionLocation.'parallelEye.jpg';
}
else if($_GET["request"] == "modelObj"){
	$filename = 'upload_stereo/'.$sessionLocation.'model.obj';
}
else if($_GET["request"] == "modelMtl"){
	$filename = 'upload_stereo/'.$sessionLocation.'model.mtl';
}
else if($_GET["request"] == "modelJpg"){
	$filename = 'upload_stereo/'.$sessionLocation.'model.jpg';
}
else{
	$filename = "";
}
if($filename != ""){
	header( 'Pragma: public' ); // required
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Cache-Control: private', false ); // required for certain browsers 
	header("Content-Type: application/download");
	//header( 'Content-Type: application/pdf' );
	
	header( 'Content-Disposition: attachment; filename="'. basename($filename) . '";' );
	header( 'Content-Transfer-Encoding: binary' );
	header( 'Content-Length: ' . filesize( $filename ) );
	readfile( $filename );
}
exit;
?>