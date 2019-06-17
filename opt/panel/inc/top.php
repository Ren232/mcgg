<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<a class="brand" href="dashboard.php"><strong>MCGG</strong> Web Panel</a>
		<ul class="nav pull-right">
			<?php if(!empty($_SESSION['is_admin']) || $user['role']=='admin') { ?>
				<li><a href="admin.php">Admin Panel</a></li>
			<?php } ?>

			<li><a href='profile.php'>User <strong><?php echo $user['user']; ?></strong></a></li>

			<?php if($user['user']) { ?>
				<li><img width="40" height="40" src="//minotar.net/avatar/<?php echo urlencode($user['user']); ?>" alt="<?php echo $user['user']; ?>"></li>
			<?php } ?>

			<li><a href="./?logout"><i class="icon-off icon-white"></i></a></li>
		</ul>
	</div>
</div>
<ul class="nav nav-tabs" id="myTab">
	<li <?php echo basename($_SERVER["SCRIPT_NAME"]) == "dashboard.php" ? 'class="active"' : ""; ?>>
		<a href="dashboard.php">Dashboard</a>
	</li>
	<li <?php echo basename($_SERVER["SCRIPT_NAME"]) == "files.php" ? 'class="active"' : ""; ?>>
		<a href="files.php">File Manager</a>
	</li>
	<li <?php echo basename($_SERVER["SCRIPT_NAME"]) == "console.php" ? 'class="active"' : ""; ?>>
		<a href="console.php">Console</a>
	</li>
	<?php if(is_file($user["home"] . "/plugins/dynmap/configuration.txt")) { ?>
		<?php
			require_once 'inc/Spyc.php';
			$config = spyc_load_file($user["home"] . "/plugins/dynmap/configuration.txt");
			$port = $config["webserver-port"];
		?>
		<li <?php echo basename($_SERVER["SCRIPT_NAME"]) == "map.php" ? 'class="active"' : ""; ?>>
			<a href="//<?php echo $_SERVER['HTTP_HOST']; ?>:<?php echo $port; ?>">Map&ensp;</a>
		</li>
	<?php } else { ?>
		<li <?php echo basename($_SERVER["SCRIPT_NAME"]) == "map.php" ? 'class="active"' : ""; ?>>
			<a href="map.php">Map</a>
		</li>
	<?php } ?>
</ul>
