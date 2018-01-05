<?php


	$os_string = php_uname('s');
	if (strpos(strtoupper($os_string), 'WIN')!==false)
	{
		$WshShell = new COM("WScript.Shell");
		$oExec = $WshShell->Run("C:\Minecraft\maps.bat", 7, false);
		echo "<script langauge=\"javascript\">history.go(-1)</script>";
	}
	else
	{
		$pid = shell_exec('pidof java');
	}
?>