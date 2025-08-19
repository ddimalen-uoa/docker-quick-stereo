<?php
#
# PAGE:		logoutReturn
# Logout a Conftool session and return to main website
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

$session->logout_return();

?>