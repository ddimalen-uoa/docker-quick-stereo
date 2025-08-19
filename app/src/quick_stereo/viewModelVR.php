<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>WebVR Model</title>
    <script src="https://aframe.io/releases/0.8.2/aframe.min.js"></script>
</head>

<?php
    copyModel("upload_stereo", "webVR");
    rectifyMtl("webVR/model.mtl");

    function copyModel($src, $dst) {
        $files = array("model.obj", "model.mtl", "model.jpg");
        foreach ($files as $file) {
            copy($src . "/" . $file, "$dst" . "/" . $file);
        }
    }

    function rectifyMtl($mtlPath) {
        $tmp = "webVR/model.tmp";
        $reading = fopen($mtlPath, 'r');
        $writing = fopen($tmp, 'w');

        $replaced = false;

        while (!feof($reading)) {
            $line = fgets($reading);
            if (stristr($line,'map_Kd model.jpg.')) {
                $line = "map_Kd model.jpg\n";
                $replaced = true;
            }
            fputs($writing, $line);
        }
        fclose($reading); fclose($writing);
// might as well not overwrite the file if we didn't replace anything
        if ($replaced)
        {
            rename($tmp, $mtlPath);
        } else {
            unlink($tmp);
        }
    }
?>

<body>
<a-scene>
    <a-assets>
        <a-asset-item id="test-obj" src="webVR/model.obj"></a-asset-item>
        <a-asset-item id="test-mtl" src="webVR/model.mtl"></a-asset-item>
    </a-assets>

    <a-entity obj-model="obj: #test-obj; mtl: #test-mtl;" scale="0.1 0.1 0.1" position="60 30 -90" rotation="0 30 180"></a-entity>
</a-scene>
</body>
</html>
