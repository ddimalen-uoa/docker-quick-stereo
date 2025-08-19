<?
$handle = fopen("dirlist.txt", "r");
$counter = 0;
if ($handle) {
    while (($line = fgets($handle)) !== false) {
		if($counter++%2==0){
			$left_directory = trim("C:\\temp\\TestAlex\\marker_blobs_individuals\\".$line);
			$right_directory = trim(str_replace("_l_", "_r_", $left_directory));
			for($i=100; $i <=112; $i++){
				$surfix = substr("".$i, 1, 3);;
				print "TriangulateBlobTrack.exe ";
				print $left_directory."\\blob-00".$surfix.".csv ";
				print $right_directory."\\blob-00".$surfix.".csv ";
				print $left_directory."\\blob3D-00".$surfix.".csv ";
				print "<br/>";
			}
		}
    }
} else {
    // error opening the file.
}
?>