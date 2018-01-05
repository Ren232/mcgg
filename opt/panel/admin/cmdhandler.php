<?php
	require_once('header_inc.php');
	
	if ($_GET["cmd"][0] == "/")
	{
		$minecraft->run_console(substr($_GET["cmd"], 1));
	} else {
		$minecraft->run_console( "say [" .strtoupper($_SESSION['user'][0]) . strtolower(substr($_SESSION['user'], 1)) ."]: " . $_GET["cmd"]);
	}
	
?>