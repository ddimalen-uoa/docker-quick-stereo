<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<title><?php echo strip_tags(ctconf_get('conferenceShortName','UNKNOWN')); ?> - ConfTool - <?php echo ucfirst($http['page']); ?>
</title>
<?PHP
// set character-set
if (isset($ctconf['charset']))
	echo "<meta HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=".$ctconf['charset']."\">\n";
else  // default
	echo "<meta HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-1\">\n";

// standard CSS file:
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".ct_getbaseurl()."conftool.css\">\n";

// Standard JavaScript file
echo "<script type=\"text/javascript\" language=\"javascript\" src=\"".ct_getbaseurl()."conftool.js\"></script>\n";

// css and js for "nicetitles", currently only needed for some overview pages...
if (stristr($http['page'],'browse') || stristr($http['page'],'submissions')  ||stristr($http['page'],'frontdesk')  || stristr($http['page'],'admin')) {
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".ct_getbaseurl()."tooltip.css\">\n";
	echo "<script type=\"text/javascript\" src=\"".ct_getbaseurl()."tooltip.js\"></script>\n";
}

// Finally add custom CSS file for some output functions like the abstract preview and to override previous settings...
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".ct_getbaseurl()."conftool-custom.css\">\n";

?>

<link rel="shortcut icon" href="<?php echo ct_getbaseurl(); ?>favicon.ico" />
<meta name="viewport" content="width=800" />
</head>

<body>

<!-- outer div - to center the rest in IE -->
<div id="center_main">

<div class="main mainbg">

<!-- inner div -->
<div class="main_elements">

