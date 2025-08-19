<?php

	$extension_upload_left = strtolower(  substr(  strrchr($_FILES['leftVideo']['name'], '.')  ,1)  );
	$path_left = "./data/Videos/leftVideo.". $extension_upload_left;

	$extension_upload_right = strtolower(  substr(  strrchr($_FILES['rightVideo']['name'], '.')  ,1)  );
	$path_right = "./data/Videos/rightVideo.". $extension_upload_right;

	$extensions_allowed = array( 'mp4' , 'avi' , 'mkv' , 'wmv' );

	if ( in_array($extension_upload_left,$extensions_allowed) & in_array($extension_upload_right, $extensions_allowed) ) 
	{
		$result1 = move_uploaded_file($_FILES['leftVideo']['tmp_name'],$path_left);
		$result2 = move_uploaded_file($_FILES['rightVideo']['tmp_name'],$path_right);

		if ($result2 && $result1) 
		{
			$location = "Location: ./Oculus_video.php?extension=".$extension_upload_right;
			header($location);
		}
		else
		{
			?>
			<script>
				alert("Error during the upload");
				window.location="./Oculus_upload_video.html";
			</script>
			<?php
		}
	}
	else
	{
		?>
		<script>
			alert("Your files need to be a video with the format mp4, avi, mkv or wmv");
			window.location="./Oculus_upload_video.html";
		</script>
		<?php
	}

	
