<?
/*
copy('../upload_stereo/left_raw_resized.jpg', '../upload_stereo/left_raw_resized_bk.jpg');
unlink('../upload_stereo/left_raw_resized.jpg');
copy('../upload_stereo/left_raw_resized_bk.jpg', '../upload_stereo/left_raw_resized.jpg');

copy('../upload_stereo/depthReserved.jpg', '../upload_stereo/depthReserved_bk.jpg');
unlink('../upload_stereo/depthReserved.jpg');
copy('../upload_stereo/depthReserved_bk.jpg', '../upload_stereo/depthReserved.jpg');

clearstatcache();
*/
?>
<!DOCTYPE html>
<html lang="en" >
<head>
<link href="css/main.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/script_vertical.js?updated=<? echo rand(); ?>"></script>

</head>
<body>
    <div class="example">        
        <div class="column2">
            <canvas id="panel" width="520" height="700"></canvas>
        </div>
        <div style="clear:both;"></div>
    </div>
</body>
</html>
<script type="text/javascript">setTimeout('initialiseDisparityValues();Start();',2000);</script>
<?php
print str_pad('',4096)."\n";
ob_flush();
flush();
set_time_limit(45);
?>