<?
session_start();
$uploadType = $_GET["upload"];
$randomNumber = rand();
//view
$errorOrNot = false;
//$_SESSION["number"] = $number;
if($_SERVER['REQUEST_METHOD'] == "POST"){
	if (!($_FILES["file1"]["type"] == "image/jpeg" || $_FILES["file1"]["type"] == "image/png") || $_FILES["file1"]["error"] > 0)
	{
	  //echo "Error: " . $_FILES["file1"]["error"] . "<br />";
	  $errorOrNot = true;
	}
	else
	{
	  if($uploadType=="disp"){
		  	move_uploaded_file($_FILES["file1"]["tmp_name"],"images/disp1.png");		  
	  }else{
	  		move_uploaded_file($_FILES["file1"]["tmp_name"],"images/view1.png");
	  }
	}	
	if($errorOrNot == false){
		if($uploadType=="disp"){
		?>
		<script language="javascript">
		window.close();
		if (window.opener && !window.opener.closed) {
			window.opener.document.getElementById('imageDepth').src='images/disp1.png?<? echo $randomNumber; ?>';
			window.opener.document.getElementById('postDepthImage').value='images/disp1.png';
		} 
		</script>
		<?
		} else {
		?>
		 <script language="javascript">
		window.close();
		if (window.opener && !window.opener.closed) {
			window.opener.document.getElementById('imageTexture').src='images/view1.png?<? echo $randomNumber; ?>';
			window.opener.document.getElementById('postTextureImage').value='images/view1.png';
		} 
		</script>
		<? } ?>
	<?
	}
}
?>
<html>
<body style="font-size:20px" bgcolor="#F4FFE4">
<span style="text-align: center"></span>
<span style="text-align: center"></span>
<span style="text-align: center"></span>
<center>
<h1><? if($errorOrNot) echo "Error! ";?>Please upload an image.</h1>

<form action="upload.php?upload=<? echo $uploadType; ?>" method="post" enctype="multipart/form-data">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><label for="file">
      <div align="right">Browse <? echo $number; ?>:</div>
    </label></td>
    <td><input name="file1" type="file" id="file1" size="40" />
      <input name="number" type="hidden" id="number" value="<? echo $stringNumber; ?>">
      <input type="submit" name="submit" value="Upload" style="height:30px; width:100px;" /></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Files allowed: jpg, jpeg, png</td>
  </tr>
</table>
</form>
</center>
</body>
</html> 