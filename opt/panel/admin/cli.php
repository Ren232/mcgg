<?php
require_once('header_inc.php');
switch($argv[0]){
	case 'backup':
	$minecraft->backup("Automatic backup", "Backed up automatically by cron on " . date('Y-m-d-Hi'));
	break;
	case 'mapper':
	$map_run = shell_exec($GENERAL['mapper_cmd']);
	return $map_run;
	break;
	case 'upgrade':
	break;
	default:
	echo "Give me a command!";
	break;
}
?>