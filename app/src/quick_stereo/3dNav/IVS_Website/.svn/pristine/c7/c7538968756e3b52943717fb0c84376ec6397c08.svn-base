<?
$directory = $_GET["url"];
if($_GET["request"] == "anaglyph"){
	$filename = $directory.'_anaglyph.jpg';
}
else if($_GET["request"] == "depthReserved"){
	$filename = $directory.'_depthReserved.jpg';
}
else if($_GET["request"] == "coordinates"){
	$filename = $directory.'_coordinates.obj';
}
else if($_GET["request"] == "autostereogram"){
	$filename = $directory.'_autostereogram.jpg';
}
else if($_GET["request"] == "crossEye"){
	$filename = $directory.'_crossEye.jpg';
}
else if($_GET["request"] == "fundamental_matrix"){
	$filename = $directory.'_fundamental_matrix.xml';
}
else if($_GET["request"] == "left_raw_resized"){
	$filename = $directory.'_left_raw_resized.jpg';
}
else if($_GET["request"] == "p_stereogram"){
	$filename = $directory.'_p_stereogram.jpg';
}
else if($_GET["request"] == "result"){
	$filename = $directory.'_result.jpg';
}
else if($_GET["request"] == "returnParameters"){
	$filename = $directory.'_returnParameters.txt';
}
else if($_GET["request"] == "right_raw_resized"){
	$filename = $directory.'_right_raw_resized.jpg';
}
else if($_GET["request"] == "shareResults"){
	$filename = $directory.'_shareResults.jpg';
}
else if($_GET["request"] == "parallelEye"){
	$filename = $directory.'_parallelEye.jpg';
}
else if($_GET["request"] == "modelObj"){
	$filename = $directory.'_model.obj';
}
else if($_GET["request"] == "modelMtl"){
	$filename = $directory.'_model.mtl';
}
else if($_GET["request"] == "modelJpg"){
	$filename = $directory.'_model.jpg';
}
else{
	$filename = "";
}
//echo $filename;

if($filename != ""){
	header( 'Pragma: public' ); // required
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Cache-Control: private', false ); // required for certain browsers 
	header( 'Content-Type: application/pdf' );
	
	header( 'Content-Disposition: attachment; filename="'. basename($filename) . '";' );
	header( 'Content-Transfer-Encoding: binary' );
	header( 'Content-Length: ' . filesize( $filename ) );
	readfile( $filename );
}
exit;

?>