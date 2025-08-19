<?
$handleDir = fopen("dirlist.txt", "r");
$counter = 0;
$TotalArray = array();
$IndividualArrays = array();
$EntireFail = 0;
$EntireFrame = 0;
for($i=0; $i <=12; $i++){
	$IndividualArrays{$i} = array();
}

//array_push($IndividualArrays{0}, 10); 
//print_r($IndividualArrays{0}); return;

if ($handleDir) {
    while (($line = fgets($handleDir)) !== false) {
		//if($counter++%2==0)
		{
			
			$left_directory = trim("".$line);	
			$overallFail = 0;	
			$totalRow = 0;
			$flagSuccess = array();
			for($i=0; $i <=1000; $i++){
				$flagSuccess[$i] = 1;
			}	
			print $left_directory." fail:<br/>";
			for($i=100; $i <=112; $i++){
				$totalRow = 0;
				$surfix = substr("".$i, 1, 3);		
				$csvFile = $left_directory."/blob-00".$surfix.".csv";		
				//print $csvFile." ";
				//print "<hr/>";
				
				
				if (($handle = fopen($csvFile, "r")) !== FALSE) {
    				$success = 0; 
					$fail = 0;
					$arrayCounter = 0;
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {						
						if($data[2] == '0' || $data[2] == '1'){
							//echo $data[3] ."<br />\n";
							if($data[2] == '0') {
								$fail++; $overallFail++; $flagSuccess[$arrayCounter++] = 0;
							}
							else if($data[2] == '1') {
								$success++;
							}
							$totalRow++;
						}
					}
					$FailPercetage = (100.0)*$fail/($success+$fail);
					//print " $fail=".number_format($FailPercetage, 2, '.', '')."%; ";
					print " $fail/$totalRow; ";
					//print ":Fail percentage = ".number_format($FailPercetage, 2, '.', '')."%<br/>";
					//print ": Success = $success, Fail = $fail, Fail percentage = ".number_format($FailPercetage, 2, '.', '')."% in Total = ".($success+$fail)."<br/>";
					//array_push($TotalArray, $FailPercetage);
					//array_push($IndividualArrays{$i-100}, $FailPercetage);
					
				}
				
				/**/
			}
		}
		//print "<br/>Total Row = ".$totalRow." ";
		$finalSuccess = 0;
		for($i=0; $i <$totalRow; $i++){
			if($flagSuccess[$i] == 1){
				$finalSuccess++;
			}
		}
		$finalFail = max(0,(100.0)*($totalRow-$finalSuccess)/$totalRow);
		print "<br/>All sequence Fail = ".($totalRow-$finalSuccess)."/".$totalRow." = ".number_format($finalFail, 2, '.', '')."%<br/>";
		array_push($TotalArray, $finalFail);
		$EntireFail+=($totalRow-$finalSuccess);
		$EntireFrame+=$totalRow;
		print "<hr/>";
    }
} else {
    // error opening the file.
}

/*
for($i = 0; $i <= 12; $i++){	
	$mean = array_sum($IndividualArrays{$i}) / count($IndividualArrays{$i});
	$standard_deviation = standard_deviation($IndividualArrays{$i});
	print "Blob $i failing mean = ".number_format($mean, 2, '.', '')."%, and its failing standard_deviation = ".number_format($standard_deviation, 2, '.', '')."%<br/>";
}
*/

print "<hr/>";
$totalMean = array_sum($TotalArray) / count($TotalArray);
$totalstandard_deviation = standard_deviation($TotalArray);
//print "Finally: Total failing mean = ".number_format($totalMean, 2, '.', '')."%, and total failing standard_deviation = ".number_format($totalstandard_deviation, 2, '.', '')."%<br/>";
print "Finally: Total failing = ".$EntireFail."/".$EntireFrame."<br/>";

function standard_deviation($aValues, $bSample = false)
{
    $fMean = array_sum($aValues) / count($aValues);
    $fVariance = 0.0;
    foreach ($aValues as $i)
    {
        $fVariance += pow($i - $fMean, 2);
    }
    $fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
    return (float) sqrt($fVariance);
}
?>