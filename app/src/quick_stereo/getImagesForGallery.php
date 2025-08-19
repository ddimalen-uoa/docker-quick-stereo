<?

function getImage(){
	include_once 'dbc.php';		
	$stmt = $dbc->prepare("SELECT * FROM `stereo_gallery_category`");
	$stmt->execute();
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach($rows as $row){
	$categoryNames[$row["id"]]=$row["categoryName"];
}
//echo $categoryName[1]; return;

$result = mysql_query("SELECT *
FROM `stereo_gallery_category` where id = '".$_SESSION["selectedCategory"]."'") or die(mysql_error()); 
$numberTotal = mysql_num_rows($result);
if($row = mysql_fetch_assoc($result)) extract($row);

$resultArray = returnFullResults($id);

$totalArray = 0; $currentPointer = 0;

$arrayShow[$totalArray++] = "crossEye";
$arrayShow[$totalArray++] = "stereogram";
$arrayShow[$totalArray++] = "parallelEye";
$arrayShow[$totalArray++] = "anaglyph";
$arrayShow[$totalArray++] = "result";

$upButtonString = "result";
$downButtonString = "texture";
for($i=0; $i < $totalArray; $i++){
	if($arrayShow[$i] == $show){
		$upButtonString = $arrayShow[($i+$totalArray-1)%$totalArray];
		$downButtonString = $arrayShow[($i+1)%$totalArray];
		if($i < $totalArray-1)
		 	$currentPointer = $i+1;
	}
}
	$countTotalResult = 0;
	for($i=0; $i<sizeof($resultArray); $i++){
		$result = $resultArray[$i];
		while($row = mysql_fetch_assoc($result)){
		$countTotalResult++;
		if($countTotalResult >10) {break;}
		?>
		  <div class="slide" onMouseMove="showAllArrows();" id="<? echo $row["location"]; ?>" name="slide">
			<input  name ="imageIP" id="imageIpaddress_<? echo $row["location"]; ?>" type="hidden" value="<? echo $row["ipAddress"]; ?>">
			<a href="javascript:clickImageView('<? echo $row["location"]; ?>');" 
			title="Stereo Vision Gallery - Minh Nguyen" 
			target="_parent">
			<img class="mainImage" src="upload_stereo/<? echo $row["location"]; ?>/<? echo $show; ?>.jpg" width="100%" alt="Slide 1"  <? if($show=="crossEye" || $show=="parallelEye") echo 'style="margin-top: 200px;"'; ?>  onMouseover="ddrivetip('Click here to view full 3D results of this image sample','black', 200);" onMouseout="hideddrivetip();" onMouseUp="showAllArrows();"> 
			</a>
			<div class="caption" style="bottom:0">
				<p>Viewtype: <strong class="redS"><? echo $show; ?></strong> in category: <strong><? echo $categoryNames[$row["category"]]; ?></strong>, uploaded on <strong><? echo str_replace(" ", " ",date("d/m/Y", strtotime($row["uploadedTime"]))); ?></strong></p>
			</div>
		</div>	
		<? 
		
		}
	} 
}
?>
