<?php
require_once('header_inc.php');
$logged=true;
if($_SESSION['user']==""){
	$logged=false;
}
function stop_server() {
		shell_exec("screen -S " . $MCSERVER['SERVICENAME'] . " -p 0 -X stuff `printf 'stop.\r'`; sleep 5");
}

$os_string = php_uname('s');

switch($_POST['act']){
	case "start":
	//(12-6-2010)Emirin: Port check, is the server alive?
		error_reporting(E_ERROR | E_PARSE);
		if($conn=fsockopen($API['ADDRESS'], $MCSERVER['PORT'], $errno, $errstr, 1)) {
			echo "<div class='error' style='display:block;'>Failed to start! Server is already running!</div>";
			fclose($conn);
		} else {
			if (strpos(strtoupper($os_string), 'WIN')!==false)
			{
				//(12-6-2010)Emirin: Windows specific restart
				$WshShell = new COM("WScript.Shell");
				$oExec = $WshShell->Run("net start " . $MCSERVER['SERVICENAME'], 7, false);
			}
			else
			{
				shell_exec('screen -dmS ' . $MCSERVER['SERVICENAME'] . ' java -Xmx'.$GENERAL["memory"].' -Xms'.$GENERAL["memory"].' -jar '.$PATH['minecraft'] .'Minecraft_Mod.jar');
			}
			echo "<div class='success' style='display:block;'>Started server!</div>";
		}
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
	break;
	case "stop":
		error_reporting(E_ERROR | E_PARSE);
		if($conn=fsockopen($API['ADDRESS'], $MCSERVER['PORT'], $errno, $errstr, 1)) 
		{
			if (strpos(strtoupper($os_string), 'WIN')!==false)
			{
				$WshShell = new COM("WScript.Shell");
				$oExec = $WshShell->Run("net stop " . $MCSERVER['SERVICENAME'], 7, false);
			}
			else
			{
				stop_server();
			}
			echo "<div class='success' style='display:block;'>Stopped server!</div>";
			fclose($conn);
		} else {
			echo "<div class='error' style='display:block;'>Failed to stop! Server is not running!</div>";
		}
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
	break;
	case "restart":
			if (strpos(strtoupper($os_string), 'WIN')!==false)
			{
				$WshShell = new COM("WScript.Shell");
				$oExec = $WshShell->Run("cmd /c net stop " . $MCSERVER['SERVICENAME'] . " & net start " . $MCSERVER['SERVICENAME'], 7, false);
			}
			else
			{
				stop_server();
				shell_exec('screen -dmS ' . $MCSERVER['SERVICENAME'] . ' java -Xmx'.$GENERAL["memory"].' -Xms'.$GENERAL["memory"].' -jar '.$PATH['minecraft'] .'Minecraft_Mod.jar');
			}
		echo "<div class='success' style='display:block;'>Restarted server!</div>";
	break;
}
?>