<?php
//
// PAGE:		browseAssignedPapers
// DESC:		Show a list of all papers assigned to this PC member...
//

if (!defined('CONFTOOL')) die('Hacking attempt!');
echo "<H1>".ct('S_INDEX_CMD_REVIEW')."</H1>\n";
echo "<p class=\"standard\">".ct('S_INDEX_OPTION_REVIEW')."</p>\n";

if (ct_check_phases("reviewing")) {

    $reviews = $user->get_reviews();
    for ($i=0; $i < sizeof($reviews); $i++) {
    	$paper = $reviews[$i]->get_paper();
	    $paper->show_reviewbox();
    	echo "<br>\n";
    }
}

?>