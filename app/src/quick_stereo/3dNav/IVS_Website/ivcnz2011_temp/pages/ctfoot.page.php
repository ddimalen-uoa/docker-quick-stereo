<?PHP // Obligatory foot of conftool - better do not edit.
if (!defined('CONFTOOL')) die('Hacking attempt!');
?>
</div>
</div></div>
<?PHP
// Output of debugging information: Show all queries.
if ($ctconf['display/debug']) {
	global $ct_starttime;
	# Close main table - improved presentation of data.
	echo "\n\n<div style='clear:both;'>\n<H2>Debugging Information</h2>\n\n";
	echo "<div class=\"fontbold font10\">Total processing time: ". sprintf("%0.3f", (ct_get_microtime()-(float)$ct_starttime)*1000)."ms</div>\n\n";
	echo "<div class=\"fontbold font10\">".$session->get('querycount')." database queries:</div>";
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"90%\">\n".$session->get('queries')."</table>";
	echo "<div><span class=\"fontbold font10\">These were ".$session->get('querycount')." queries</span></div>\n</div>";
}

?>
</body>
</html>