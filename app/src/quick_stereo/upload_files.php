<?
$uploaddir = './upload/';
$file = basename($_FILES['userfile']['name']);
$uploadfile = $uploaddir . $file;

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        echo "http://www.ivs.auckland.ac.nz/quick_stereo/uploads/{$file}";
}
?>
