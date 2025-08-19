<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
 <?php if (!defined('CONFTOOL')) die('Hacking attempt!'); ?>

<html>
<head>
<title><?php echo strip_tags(ctconf_get('conferenceShortName','UNKNOWN')); ?> - ConfTool, Print Page: <?php echo ucfirst($http['page']); ?>
</title>
<link rel="stylesheet" type="text/css" href="<?php echo ct_getbaseurl(); ?>conftool.css">

<script type="text/javascript" language="javascript" src="<?php echo ct_getbaseurl(); ?>conftool.js"></script>

<script type="text/javascript">
<!--
function printWindow(){
    window.print();
}
// -->
</script>
<?PHP
if (isset($http['doprint']) && $http['doprint']=='yes') {
	echo "<script type=\"text/javascript\">\n";
	echo "<!--\n";
	echo "if (is_opera || is_safari || is_chrome) {\n";
	echo " addEvent(window, 'load', window.setTimeout('window.print()', 500) );\n";
	echo "} else {\n";
	echo " window.print();\n";
	echo "}\n";
	echo "// -->\n";
	echo "</script>\n";
}?>

</head>

<body marginheight=0 topmargin=0 leftmargin=0 style="margin: 0px; padding:0px; background:white">

<table align=left bgcolor="white" width=640><tr><td>
