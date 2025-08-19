<?
session_start();
$view = $_GET["set"];
$_SESSION[$view] = $_GET["value"];
?>