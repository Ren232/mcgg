<?php
/*
	$os_string = php_uname('s');
	if (strpos(strtoupper($os_string), 'WIN')!==false)
	{
		$pid = shell_exec('wmic process where ExecutablePath=\'C:\\windows\\system32\\java.exe\' get ProcessId');
	}
	else
	{
		$pid = shell_exec('pidof java');
	}
*/

/*
if(count($pid)==1) {
	$status = '<font color="green">Status: Online</font>';
} else {
	$status = '<font color="red">Status: Offline</font>';
}
*/

error_reporting(E_ERROR | E_PARSE);

if($conn=fsockopen($API['ADDRESS'], $MCSERVER['PORT'], $errno, $errstr, 1)) {
	$status = '<font color="green">Status: Online</font>';
	fclose($conn);
} else {
	$status = '<font color="red">Status: Offline</font>';
}
error_reporting(E_ERROR | E_WARNING | E_PARSE);

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php if((preg_match("/iPad/i",$_SERVER['HTTP_USER_AGENT'])) || (preg_match("/iPhone/i",$_SERVER['HTTP_USER_AGENT'])) || (preg_match("/iPod/i",$_SERVER['HTTP_USER_AGENT']))) {
		echo "<title>MineAdmin</title>";
		echo "<meta name=\"viewport\" content=\"initial-scale = 1.0, user-scalable = no\" />";
		echo "<link rel=\"apple-touch-startup-image\" href=\"images/ipad-startup.png\" />";
	} else {
		echo "<title>MineAdmin</title>";
	}
	?>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/jquery.alerts.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/jquery.autocomplete.css" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.2.css" media="screen" />
	<link rel="stylesheet" href="css/jquery.jgrowl.css" type="text/css" media="screen" />
	<link rel="apple-touch-icon" href="images/apple-icon.png"/>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-touch-fullscreen" content="yes" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery-css-transform.js"></script>
	<script type="text/javascript" src="js/jquery.autocomplete.pack.js"></script>
	<script type="text/javascript" src="js/jquery.bgiframe.min.js"></script>
	<script type="text/javascript" src="js/jquery.alerts.js"></script>
    <script type="text/javascript" src="fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.2.js"></script>
	<script type="text/javascript" src="js/jquery.jgrowl_minimized.js"></script>
	<script type="text/javascript" src="js/jquery.ui.all.js"></script>
	<script type="text/javascript" src="js/system.js"></script> 
	<link rel="stylesheet" media="all and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)" href="css/ipad.portrait.css"> 

</head>
<body>
	<div id="menu-wrap">
		<h2>MineAdmin</h2>
		<ul style="z-index:10;">
			<li><a href="logout_process.php">Logout</a></li>
			<li class="dropdown"><a href="javascript:void(0)" id="droplink" <?php if ($pageid=="groups" or $pageid=="users" or $pageid=="items" or $pageid=="kits" or $pageid=="plugins" or $pageid=="settings" or $pageid=="sysinfo") {echo " class=\"menu_active\"";} else { echo ""; }?>>Settings &or;</a>
				<ul id="droplist">
					<li><a href="items.php" <?php if ($pageid=="items") { echo " class=\"menu_active\""; } else { echo ""; }?>>Items</a></li>
					<li><a href="kits.php" <?php if ($pageid=="kits") { echo " class=\"menu_active\""; } else { echo ""; }?>>Kits</a></li>			
					<li><a href="users.php" <?php if ($pageid=="users") { echo " class=\"menu_active\""; } else { echo ""; }?>>Users</a></li>	
					<li><a href="groups.php" <?php if ($pageid=="groups") { echo " class=\"menu_active\""; } else { echo ""; }?>>Groups</a></li>
					<li><a href="plugins.php" <?php if ($pageid=="plugins") { echo " class=\"menu_active\""; } else { echo ""; }?>>Plugins</a></li>
					<li><a href="landing.php" <?php if ($pageid=="landing") { echo " class=\"menu_active\""; } else { echo ""; }?>>Landing</a></li>
					<li><a href="settings.php" <?php if ($pageid=="settings") { echo " class=\"menu_active\""; } else { echo ""; }?>>Configuration</a></li>	
					<li><a href="sysinfo.php" <?php if ($pageid=="sysinfo") { echo " class=\"menu_active\""; } else { echo ""; }?>>System Info</a></li>	
				</ul>
			</li>
			<li class="dropdown"><a href="javascript:void(0)" id="droplink" <?php if ($pageid=="tools" or $pageid=="chat") {echo " class=\"menu_active\"";} else { echo ""; }?>>Tools &or;</a>
				<ul id="droplist">
					<li><a href="chat.php" <?php if ($pageid=="chat") { echo " class=\"menu_active\""; } else { echo ""; }?>>Console</a></li>			
					<li><a href="backup.php" <?php if ($pageid=="backups") { echo " class=\"menu_active\""; } else { echo ""; }?>>Backups</a></li>	
					<li><a href="reservelist.php" <?php if ($pageid=="reservelist") { echo " class=\"menu_active\""; } else { echo ""; }?>>Reserve</a></li>
					<li><a href="whitelist.php" <?php if ($pageid=="whitelist") { echo " class=\"menu_active\""; } else { echo ""; }?>>White List</a></li>
					<li><a href="warplist.php" <?php if ($pageid=="warplist") { echo " class=\"menu_active\""; } else { echo ""; }?>>Warp List</a></li>
					<li><a href="logs.php" <?php if ($pageid=="logs") { echo " class=\"menu_active\""; } else { echo ""; }?>>Logs</a></li>	
				</ul>
			</li>
			<li><a href="start.php" <?php if ($pageid=="start") { echo " class=\"menu_active\""; } else { echo ""; }?>>Home</a></li>
		</ul>
		<div id="status">
			<p><?php echo $status; ?>&nbsp;<a href="javascript:power_control('start');"><img src="images/icons/asterisk_yellow.png">Start</a>&nbsp;<a href="javascript:power_control('stop');"><img src="images/icons/stop.png">Stop</a>&nbsp;<a href="javascript:power_control('restart');"><img src="images/icons/arrow_refresh.png">Restart</a></p>
		</div>
	</div>
	<div id="output">
		
	</div>

	<div id="ipad_warning">
		<p>Please rotate your iPad to the landscape orientation.</p>
	</div>