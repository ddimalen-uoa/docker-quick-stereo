<?
ob_start();
$directory = '../stereogallery';
include "../quick_stereo/dbc.php";

$arrayMPO = array();//print_r($arrayMPO);
echo ":: ============================";
echo "<br/>";
echo ":: STATISTICS RESULTS ON <BR>:: ".strtoupper(date('l jS \of F Y h:i:s A'));
echo "<br/>";
echo ":: ============================";
echo "<br/>";
$categoriesArray = array();

$totalCategories = 0;
$categories = "";
foreach(glob($directory.'/*') as $dir) { 		
	  addString($dir);
	  $totalFiles = 0;		 
	  foreach(glob($dir.'/*') as $dir2) {
		    if (strpos($dir2, '.MPO') !== false) {		    		
				addString($dir2);
				$totalFiles++;			
			}
			foreach(glob($dir2.'/*') as $dir3) { 
				if (strpos($dir3, '.MPO') !== false) {		    		
					addString($dir3);
					$totalFiles++;			
				}										
			}			
	  }
	  
	  if(str_replace(".jpg", "", $dir) == $dir){
		  $tempDir = substr($dir, strrpos($dir, "/")+1);
		  $categories=$categories.$tempDir.", ";	  
		  $totalCategories++;
		  array_push($categoriesArray, $tempDir);
		  
		  ${$tempDir."No"} = $totalFiles;
	  }
}
//echo ":: ".substr($categories,0,-2).", ";
/*
echo ":: ============================";
echo "<br/>";
echo ":: Total there are ".$totalCategories." Categories: ".substr($categories,0,-2).".";
echo "<br/>";
echo ":: ============================";
echo "<br/>";
*/
?>
<script src="../quick_stereo/js/psMathStats.js"></script>

<?
echo ":: ============================";
echo "<br/>";
echo ":: STEREO RECTIFICATION";
echo "<br/>";
echo ":: ============================";
echo "<br/>";
?>
<table width="800" border="1">
  <tr>
    <td bgcolor="#CCCCCC">Categories</td>
    <td bgcolor="#CCCCCC">Visually pass</td>
    <td bgcolor="#CCCCCC">Visually fail</td>
    <td bgcolor="#CCCCCC">Pass rate</td>
    <td bgcolor="#CCCCCC">Average Pixel Offset</td>
  </tr>
  <?
  $actualTotal = 0; $totalPass = 0; $totalFail = 0;
  for($i=0; $i < sizeof($categoriesArray);$i++){
	  if($i%2==0) $colour = ' bgcolor="#FFFFCC"'; else $colour = '';
  ?>
  <tr <? echo $colour; ?>>
    <td><? echo $categoriesArray[$i];?></td>
    <?
    $sql = "SELECT count(image) as Pass FROM `cs_stereo_rectification_evaluation` WHERE image like '%".$categoriesArray[$i]."%' and rectification_quality='pass'";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	if($row = mysql_fetch_assoc($result)){
	   extract($row);	   
	}
	$sql = "SELECT count(image) as Fail FROM `cs_stereo_rectification_evaluation` WHERE image like '%".$categoriesArray[$i]."%' and rectification_quality='fail'";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	if($row = mysql_fetch_assoc($result)){
	   extract($row);	   
	}
	 $sql = "SELECT max(offset) as max, min(offset) as min, avg(offset) as avg FROM `cs_stereo_rectification_evaluation` WHERE image like '%".$categoriesArray[$i]."%'";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	if($row = mysql_fetch_assoc($result)){
	   extract($row);	   
	}
	$totalPass+=$Pass;$totalFail+=$Fail;
	?>
    <td><? echo $Pass; ?></td>
    <td><? echo $Fail; ?></td>
    <td><? echo number_format(100*$Pass/($Pass+$Fail), 2, '.', '');?>%</td>
    <td><? echo $avg; ?>&nbsp;</td>
  </tr>
  <? } ?>
  <tr>
    <td>Total</td>
    <td><? echo $totalPass; ?></td>
    <td><? echo $totalFail; ?></td>
    <td><? echo number_format(100*$totalPass/($totalPass+$totalFail), 2, '.', '');?>%</td>
    <td>---</td>
  </tr>
</table>

<?
echo ":: ============================";
echo "<br/>";
echo ":: COMPUTATIONAL STEREO MATCHING";
echo "<br/>";
echo ":: ============================";
echo "<br/>";
?>
<table width="800" border="1">
  <tr>
    <td bgcolor="#CCCCCC">Categories</td>
    <td bgcolor="#CCCCCC">MPO images</td>
    <td bgcolor="#CCCCCC">Percentage</td>
    <td bgcolor="#CCCCCC">Chosen Block Matching</td>
    <td bgcolor="#CCCCCC">Chosen Belief Propagation</td>
    <td bgcolor="#CCCCCC">Chosen Graph Cut</td>
    <td bgcolor="#CCCCCC">Best Algorithm</td>
  </tr>
  <?
  $actualTotal = 0;
  for($i=0; $i < sizeof($categoriesArray);$i++){
	  if($i%2==0) $colour = ' bgcolor="#FFFFCC"'; else $colour = '';
  ?>
  <tr <? echo $colour; ?>>
    <td><? echo $categoriesArray[$i];?></td>
    <td><? echo ${$categoriesArray[$i]."No"}; $actualTotal+=${$categoriesArray[$i]."No"};?></td>
    <td><? echo number_format(100*${$categoriesArray[$i]."No"}/sizeof($arrayMPO), 2, '.', '');?>%</td>
    <?
    $sql = "SELECT count(id) as totalBM FROM `cs_stereo_matching_evaluation` WHERE image like '%".$categoriesArray[$i]."%' and algorithm = 'BM'";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	if($row = mysql_fetch_assoc($result)){
	   extract($row);	   
	}
	$sql = "SELECT count(id) as totalCBP FROM `cs_stereo_matching_evaluation` WHERE image like '%".$categoriesArray[$i]."%' and algorithm = 'CBP'";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	if($row = mysql_fetch_assoc($result)){
	   extract($row);	   
	}
	$sql = "SELECT count(id) as totalGC FROM `cs_stereo_matching_evaluation` WHERE image like '%".$categoriesArray[$i]."%' and algorithm = 'GC'";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	if($row = mysql_fetch_assoc($result)){
	   extract($row);	   
	}
	$totalStereoMatching = $totalBM+$totalCBP+$totalGC;
	$bestSM = "";
	
	if($totalCBP>$totalGC && $totalCBP > $totalBM) $bestSM = "CBP";
	if($totalBM>$totalGC && $totalBM > $totalCBP) $bestSM = "BM";
	if($totalGC>$totalCBP && $totalGC > $totalBM) $bestSM = "GC";
	$CBP = "#FF9966";$BM = "#33FF99";$GC = "#99FF66";
	?>
    <td <? if($bestSM == 'BM') echo ' bgcolor="'.${$bestSM}.'"'; ?>><? echo $totalBM; ?> (<? echo number_format(100*$totalBM/$totalStereoMatching, 2, '.', '');?>%)</td>
    <td <? if($bestSM == 'CBP') echo ' bgcolor="'.${$bestSM}.'"'; ?>><? echo $totalCBP; ?> (<? echo number_format(100*$totalCBP/$totalStereoMatching, 2, '.', '');?>%)</td>
    <td <? if($bestSM == 'GC') echo ' bgcolor="'.${$bestSM}.'"'; ?>><? echo $totalGC; ?> (<? echo number_format(100*$totalGC/$totalStereoMatching, 2, '.', '');?>%)</td>
    <td <? echo ' bgcolor="'.${$bestSM}.'"'; ?>><? echo $bestSM; ?>&nbsp;</td>
  </tr>
  <? } ?>
  <tr>
    <td bgcolor="#CCCCCC">Total</td>
    <td bgcolor="#CCCCCC">==</td>
    <td bgcolor="#CCCCCC">==</td>
    <td bgcolor="#CCCCCC">==</td>
    <td bgcolor="#CCCCCC">==</td>
    <td bgcolor="#CCCCCC">==</td>
    <td bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
  <tr>
    <td><? echo $totalCategories; ?></td>
    <td><? echo $actualTotal; ?></td>
    <td>100%</td>
    <?
    $sql = "SELECT count(id) as totalBM FROM `cs_stereo_matching_evaluation` WHERE algorithm = 'BM'";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	if($row = mysql_fetch_assoc($result)){
	   extract($row);	   
	}
	$sql = "SELECT count(id) as totalCBP FROM `cs_stereo_matching_evaluation` WHERE algorithm = 'CBP'";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	if($row = mysql_fetch_assoc($result)){
	   extract($row);	   
	}
	$sql = "SELECT count(id) as totalGC FROM `cs_stereo_matching_evaluation` WHERE algorithm = 'GC'";
	$result = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	if($row = mysql_fetch_assoc($result)){
	   extract($row);	   
	}
	$totalStereoMatching = $totalBM+$totalCBP+$totalGC;
	$bestSM = "";
	
	if($totalCBP>$totalGC && $totalCBP > $totalBM) $bestSM = "CBP";
	if($totalBM>$totalGC && $totalBM > $totalCBP) $bestSM = "BM";
	if($totalGC>$totalCBP && $totalGC > $totalBM) $bestSM = "GC";
	?>
    <td <? if($bestSM == 'BM') echo ' bgcolor="#EEEE00"'; ?>><? echo $totalBM; ?> (<? echo number_format(100*$totalBM/$totalStereoMatching, 2, '.', '');?>%)</td>
    <td <? if($bestSM == 'CBP') echo ' bgcolor="#EEEE00"'; ?>><? echo $totalCBP; ?> (<? echo number_format(100*$totalCBP/$totalStereoMatching, 2, '.', '');?>%)</td>
    <td <? if($bestSM == 'GC') echo ' bgcolor="#EEEE00"'; ?>><? echo $totalGC; ?> (<? echo number_format(100*$totalGC/$totalStereoMatching, 2, '.', '');?>%)</td>
    <td <? if($bestSM == 'GC') echo ' bgcolor="#EEEE00"'; ?>>&nbsp;</td>
  </tr>
</table>
<?

printSMTiming();

print3DStatistics();

$page = ob_get_contents();
ob_end_flush();

if(!$run){
	$fp = fopen("../web/images/batch_statistics.html","w");
	fwrite($fp,$page);
	fclose($fp);
}

?>

<?

function addString($string){
	GLOBAL $arrayMPO;GLOBAL $run;
	if (strpos($string, '.MPO') !== false) {
		//found MPO
		array_push($arrayMPO, $string);
	}
}

function printSMTiming(){
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
	echo ":: STEREO MATCHING PERFORMANCES";
	echo "<br/>";
	echo ":: ON";
	echo "<br/>";
	echo ":: DELL OPTIPLEX 990, Intel Core i5-2500 @ 3.30 GHz with 8.0 GB RAM";
	echo "<br/>";
	echo "<span id = 'statisticsPart'></span>";
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
	
}

function print3DStatistics(){
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
	echo ":: CURRENT PROCESSING STAGE    ";
	echo "<br/>";
	echo ":: ============================";
	echo "<br/>";
	GLOBAL $arrayMPO;
	$total = sizeof($arrayMPO);
	$finishedCross = 0;
	$finishedParallel = 0;
	$finishedAnaglyphs = 0;
	$finishedStereogram = 0;
	$finishedBM = 0;
	$finishedCBP = 0;
	$finishedGC = 0;
	
	foreach ($arrayMPO as $mpo){
		$crossEye = str_replace(".MPO", "_crossEye.jpg", $mpo);
		$parallelEye = str_replace(".MPO", "_parallelEye.jpg", $mpo);
		$anaglyphs = str_replace(".MPO", "_anaglyph.jpg", $mpo);
		$stereograms = str_replace(".MPO", "_stereogram.jpg", $mpo);
		$depthBM = str_replace(".MPO", "_BM.jpg", $mpo);
		$depthCBP = str_replace(".MPO", "_CBP.jpg", $mpo);
		$depthGC = str_replace(".MPO", "_GC.jpg", $mpo);
		
		if (file_exists($crossEye)) {
			$finishedCross++;
		}
		if (file_exists($parallelEye)) {
			$finishedParallel++;
		}
		if (file_exists($anaglyphs)) {
			$finishedAnaglyphs++;
		}
		if (file_exists($stereograms)) {
			$finishedStereogram++;
		}
		if (file_exists($depthBM)) {
			$finishedBM++;
		}
		if (file_exists($depthCBP)) {
			$finishedCBP++;
		}
		if (file_exists($depthGC)) {
			$finishedGC++;
		}		
	}
	?>
    <table width="800" border="1">
      <tr>
        <td bgcolor="#CCCCCC">Results</td>
        <td bgcolor="#CCCCCC">Block Matching</td>
        <td bgcolor="#CCCCCC">Colour Belief Propagation</td>
        <td bgcolor="#CCCCCC">Graph Cut</td>
        <td bgcolor="#CCCCCC">Cross-eyed</td>
        <td bgcolor="#CCCCCC">Parallel-eyed</td>
        <td bgcolor="#CCCCCC">Anaglyphs</td>
        <td bgcolor="#CCCCCC">Auto-stereogram</td>
      </tr>
      <tr>
        <td>Processed images</td>
        <td><? echo $finishedBM; ?></td>
        <td><? echo $finishedCBP; ?></td>
        <td><? echo $finishedGC; ?></td>
        <td><? echo $finishedCross; ?></td>
        <td><? echo $finishedParallel; ?></td>
        <td><? echo $finishedAnaglyphs; ?></td>
        <td><? echo $finishedStereogram; ?></td>
      </tr>
      <tr>
        <td>Processed %</td>
        <td><? echo number_format(100*$finishedBM/$total, 2, '.', '');?>%</td>
        <td><? echo number_format(100*$finishedCBP/$total, 2, '.', '');?>%</td>
        <td><? echo number_format(100*$finishedGC/$total, 2, '.', '');?>%</td>
        <td><? echo number_format(100*$finishedCross/$total, 2, '.', '');?>%</td>
        <td><? echo number_format(100*$finishedParallel/$total, 2, '.', '');?>%</td>
        <td><? echo number_format(100*$finishedAnaglyphs/$total, 2, '.', '');?>%</td>
        <td><? echo number_format(100*$finishedStereogram/$total, 2, '.', '');?>%</td>
      </tr>
      <tr>
        <td bgcolor="#CCCCCC">Total</td>
        <td bgcolor="#CCCCCC"><? echo $total; ?></td>
        <td bgcolor="#CCCCCC"><? echo $total; ?></td>
        <td bgcolor="#CCCCCC"><? echo $total; ?></td>
        <td bgcolor="#CCCCCC"><? echo $total; ?></td>
        <td bgcolor="#CCCCCC"><? echo $total; ?></td>
        <td bgcolor="#CCCCCC"><? echo $total; ?></td>
        <td bgcolor="#CCCCCC"><? echo $total; ?></td>
      </tr>     
    </table>

    <?	
}
?>
<script>
var statisticString = '';
var width = new Array();
var height = new Array();
var resolution = new Array();
var disparity = new Array();
var bm = new Array();
var cbp = new Array();
var gc = new Array();
<?
$file = "./sceneTiming.txt";
$f = fopen($file, "r");
while ( $line = fgets($f, 1000) ) {	
	$linearray = explode(" ", $line);	
	if($linearray[0] == 'BM'){
		echo "bm.push(parseFloat(".$linearray[4]."));";
		/*
		echo "width.push(parseFloat(".$linearray[1]."));";
		echo "height.push(parseFloat(".$linearray[2]."));";
		echo "resolution.push(parseFloat(".$linearray[1].")*parseFloat(".$linearray[2]."));";
		echo "disparity.push(parseFloat(".$linearray[3]."));";
		*/
	}
	else if($linearray[0] == 'CBP'){
		echo "cbp.push(parseFloat(".$linearray[4]."));";
		
		echo "width.push(parseFloat(".$linearray[1]."));";
		echo "height.push(parseFloat(".$linearray[2]."));";
		echo "resolution.push(parseFloat(".$linearray[1].")*parseFloat(".$linearray[2]."));";
		echo "disparity.push(parseFloat(".$linearray[3]."));";
	}
	else if($linearray[0] == 'GC'){
		echo "gc.push(parseFloat(".$linearray[4].")); ";
		/*
		echo "width.push(parseFloat(".$linearray[1]."));";
		echo "height.push(parseFloat(".$linearray[2]."));";
		echo "resolution.push(parseFloat(".$linearray[1].")*parseFloat(".$linearray[2]."));";
		echo "disparity.push(parseFloat(".$linearray[3]."));";
		*/
	}	
}
?>
statisticString+="<table width=\"800\" border=\"1\">  <tr bgcolor=\"#CCCCCC\">    <td>Parameters</td>    <td>Min</td>    <td>Mean</td>    <td>Median</td>    <td>Max</td>    <td>Variance</td>    <td>Standard Deviation </td> <td>Sample Size </td></tr>";

statisticString+="<tr>    <td>Width</td>    <td>"+width.min().toPrecision(3)+"</td>    <td>"+width.mean().toPrecision(3)+"</td>    <td>"+width.median().toPrecision(3)+"</td>    <td>"+width.max().toPrecision(3)+"</td>    <td>"+width.variance().toPrecision(3)+"</td>    <td>"+width.stdDev().toPrecision(3)+"</td> <td>"+width.length+"</td>  </tr>";

statisticString+="<tr>    <td>Height</td>    <td>"+height.min().toPrecision(3)+"</td>    <td>"+height.mean().toPrecision(3)+"</td>    <td>"+height.median().toPrecision(3)+"</td>    <td>"+height.max().toPrecision(3)+"</td>    <td>"+height.variance().toPrecision(3)+"</td>    <td>"+height.stdDev().toPrecision(3)+"</td> <td>"+height.length+"</td>  </tr>";

statisticString+="<tr>    <td>resolution</td>    <td>"+resolution.min().toPrecision(3)+"</td>    <td>"+resolution.mean().toPrecision(3)+"</td>    <td>"+resolution.median().toPrecision(3)+"</td>    <td>"+resolution.max().toPrecision(3)+"</td>    <td>"+resolution.variance().toPrecision(3)+"</td>    <td>"+resolution.stdDev().toPrecision(3)+"</td> <td>"+resolution.length+"</td>  </tr>";

statisticString+="<tr>    <td>disparity</td>    <td>"+disparity.min().toPrecision(3)+"</td>    <td>"+disparity.mean().toPrecision(3)+"</td>    <td>"+disparity.median().toPrecision(3)+"</td>    <td>"+disparity.max().toPrecision(3)+"</td>    <td>"+disparity.variance().toPrecision(3)+"</td>    <td>"+disparity.stdDev().toPrecision(3)+"</td> <td>"+disparity.length+"</td>  </tr>";

statisticString+="<tr>    <td>Block Matching time</td>    <td>"+bm.min().toPrecision(3)+"</td>    <td>"+bm.mean().toPrecision(3)+"</td>    <td>"+bm.median().toPrecision(3)+"</td>    <td>"+bm.max().toPrecision(3)+"</td>    <td>"+bm.variance().toPrecision(3)+"</td>    <td>"+bm.stdDev().toPrecision(3)+"</td> <td>"+bm.length+"</td>  </tr>";

statisticString+="<tr>    <td>Belief Propagation time</td>    <td>"+cbp.min().toPrecision(3)+"</td>    <td>"+cbp.mean().toPrecision(3)+"</td>    <td>"+cbp.median().toPrecision(3)+"</td>    <td>"+cbp.max().toPrecision(3)+"</td>    <td>"+cbp.variance().toPrecision(3)+"</td>    <td>"+cbp.stdDev().toPrecision(3)+"</td> <td>"+cbp.length+"</td>  </tr>";

statisticString+="<tr>    <td>Graph Cut time</td>    <td>"+gc.min().toPrecision(3)+"</td>    <td>"+gc.mean().toPrecision(3)+"</td>    <td>"+gc.median().toPrecision(3)+"</td>    <td>"+gc.max().toPrecision(3)+"</td>    <td>"+gc.variance().toPrecision(3)+"</td>    <td>"+gc.stdDev().toPrecision(3)+"</td> <td>"+gc.length+"</td>  </tr>";
  
statisticString+="</table>";


document.getElementById('statisticsPart').innerHTML=statisticString;
</script>