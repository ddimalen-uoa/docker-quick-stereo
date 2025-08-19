<?php
//
// PAGE:		downloadDocument
// DESC:     Download a contribution (e.g. for the reviews)
// HINT:		This page may interrupt the normal page construction by throwing away all
//			content written to the outbut buffer and send a document instead.
//

if (!defined('CONFTOOL')) die('Hacking attempt!');

if (!in_http('form_id')) {
	ct_error_log('Tried to download file without ID');
	ct_redirect(ct_pageurl('index'));
}


$accept_range=false; // Enable "Accept-Range" so file downloads can be interrupted
					 // and resumed. Usually NOT required!

$paper = new CTPaper();
if (!$paper->load_by_id($http['form_id'])) {
	$session->put_errorbox(ct('S_ERROR_PAPER_DOWNLOAD'), ct('S_ERROR_PAPER_DOWNLOAD_NOACCESS'));
	ct_redirect(ct_pageurl('error'));
}

$author =& $paper->get_author();

# check user rights
if (! ($user->is_chair() || $user->is_admin() || ($user->get('ID') == $author->get('ID')) || $paper->is_reviewed_by($user->get('ID')) )) {
	$session->put_errorbox(ct('S_ERROR_PAPER_DOWNLOAD'), ct('S_ERROR_PAPER_DOWNLOAD_NOACCESS'));
	ct_redirect(ct_pageurl('error'));
}


# Name on Harddisk
$filename = $paper->get('filename');

// check if filename is ok.
if ($filename == "") {
	$session->put_errorbox(ct('S_ERROR_PAPER_DOWNLOAD'), ct('S_ERROR_PAPER_DOWNLOAD_NOACCESS'));
	ct_redirect(ct_pageurl('error'));
}

// Add upload path if it is a relative file name, e.g. there is only 1 or less "/" or "\"
if (substr_count($filename,"/")<=1 && substr_count($filename,"\\")<=1)
	$filename = ct_get_path('uploads').$filename;

// Get extension from filename on Disk
$extension="bin";
eregi(".*\.(.{2,4})$",$filename,$regs);
if ($regs[1]!="") {
	$extension =  $regs[1];
}

// Create download filename
$downloadname = ct('S_INDEX_PAPER_PAPER').$paper->get_id().".".$extension;
if (isset($http['filename']) && $http['filename']!="") {
	$downloadname = $http['filename'];
}
// Replace + by _ to avoid problems.
$downloadname = str_replace("+","_",$downloadname);
// Replace . by %2e for IE bug.
#$downloadname = preg_replace('/\./', '%2e', $downloadname, substr_count($downloadname, '.') - 1);


$size = -1;
// if not in save mode check if file exists and get size.
if( !ini_get('safe_mode') ){ 	// Does _not_ work in SAFE MODE, so skip
	if (!file_exists($filename)) {
		$session->put_errorbox(ct('S_ERROR_PAPER_DOWNLOAD'), ct('S_ERROR_PAPER_DOWNLOAD_NOTFOUND'));
		ct_redirect(ct_pageurl('error'));
	} else {
		$size=filesize($filename);
	}
}

// Remark in review table that review was started (at least the download done...)
if ($session->loggedin() && is_object($user)) {
	ct_load_class('CTReview');
	$review = new CTReview();
	if ($review->load($paper->get('ID'), $user->get('ID') )) {
		$review->set('downloaddate',ct_timestamp_2_datetime(ct_time()));
		$review->persist();
	}
}

// 0. To start sending file, remove all old output.
ob_end_clean();

// Close session to allow several downloads at a time.
session_write_close();


// 1. first determine mime-type from filename
$header = "Content-Type: ";

switch (ct_strtolower($extension)) {
	case "pdf":  $header .= "application/pdf"; break;
	case "ps":   $header .= "application/postscript"; break;

	case "doc":  $header .= "application/msword"; break;
	case "docx": $header .= "application/vnd.openxmlformats-officedocument.wordprocessingml.document"; break;
	case "odt":  $header .= "application/vnd.oasis.opendocument.text"; break;
	case "rtf":  $header .= "application/rtf"; break;

	case "xls":  $header .= "application/vnd.ms-excel"; break;
	case "xlsx": $header .= "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; break;
	case "ods":  $header .= "application/vnd.oasis.opendocument.spreadsheet"; break;

	case "ppt":  $header .= "application/vnd.ms-powerpoint"; break;
	case "pptx": $header .= "application/vnd.openxmlformats-officedocument.presentationml.presentation"; break;
	case "odp":  $header .= "application/vnd.oasis.opendocument.presentation"; break;

	case "zip":  $header .= "application/zip"; break;
	case "rar":  $header .= "application/x-rar-compressed"; break;
	case "gz": case "tgz":
				 $header .= "application/x-gzip"; break;

	case "gif":  $header .= "image/gif"; break;
	case "jpg":  $header .= "image/jpeg"; break;
	case "png":  $header .= "image/png"; break;
	case "html": case "htm":
				 $header .= "text/html"; break;
	case "txt":  $header .= "text/plain"; break;
	case "tex":  $header .= "application/x-tex"; break;
	default:
				 $header .= "application/octet-stream";
}
// 2. Send header
header($header);
header("Content-Description: File Transfer");
header("Content-Transfer-Encoding: binary");

if ($accept_range) header("Accept-Ranges: bytes");

header('Content-Disposition: attachment; filename="' . $downloadname .'"');	// Download
#header('Content-Disposition: inline; filename="' . $downloadname .'"');	// Show in browser window

$start=0;
// Size unknown, range cannot be be used!
if ($size<=0) {
	# Do nothing special as file size could not be read by PHP

//if range was given, download missing part
} elseif ($accept_range && isset($_SERVER['HTTP_RANGE'])) {
	// Works, if the HTTP request looks like:
	// Range: bytes=7618560-	or
	// Range: bytes=7618560-9999999
	$range = $_SERVER['HTTP_RANGE']; // "bytes=123-987"
	preg_match ('/^bytes=(\d+)-(\d*)/i',$range, $matches);
	$start=(int)$matches[1];
	if ($start<0 || $start>=$size-1)
		$start=0; // Fix start of file.

	// Now $range has the starting byte of the file to be downloaded...
	header("HTTP/1.1 206 Partial Content");

	// Content-Range: bytes 7618560-9169694/9169695
	$end=$size-1;
	header("Content-Range: bytes $start-$end/$size");

	// Print also content length
	$range_size=$size-$start; // The start and ending byte are counted as well
	header("Content-Length: $range_size");

// Start at beginning...
} else {
    $end=$size-1;
    header("Content-Range: bytes 0-$end/$size");
    header("Content-Length: ".$size);
}


// 3. Send file.
#readfile($filename); ### This is buggy in some PHP versions, especially for bigger files > 2000000 Byte
$fp = fopen($filename, "rb");

//go to start of missing part
if ($start>0) fseek($fp,$start);

if ($fp) {
	while (!feof($fp)) {
		echo fread($fp, 256*1024);
		// sleep(1); usleep(10*1000); // Wait a bit for debugging purposes...
		ob_flush(); flush();
	}
	fclose($fp);
}

if (is_object($db)) $db->disconnect();

exit();

?>