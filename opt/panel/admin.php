<?php
require_once 'inc/lib.php';
session_start();
if ((!$user = user_info($_SESSION['user'])) && !$_SESSION['user']) {
	// Not logged in, redirect to login page
	header('Location: .');
	exit('Not Authorized');
} elseif (!$_SESSION['is_admin'] && $user['role'] != 'admin') {
	// Not an admin, redirect to login page
	header('Location: .');
	exit('Not Authorized');
}
// Switch users
if(isset($_POST['action'])) {
	if ($_POST['action'] == 'user-switch' && $_POST['user']) {
		$_SESSION['is_admin'] = true;
		$_SESSION['user'] = $_POST['user'];
		header('Location: .');
		exit('Switching Users');
	}
	//Manage a backup cron job
	if($_POST['action'] == 'backup-manage' && $_POST['user']) {
		//Determine which button (create or delete) was pressed and pass it as an action
		$action = (isset($_POST['create']) ? "create" : (isset($_POST['delete']) ? "delete" : exit("Action error")));
		server_manage_backup($_POST['user'], $action, intvaL($_POST["hrFreq"]), intval($_POST["hrDeleteAfter"]));
	}
	// Add new user
	if ($_POST['action'] == 'user-add') 
		user_add($_POST['user'], $_POST['pass'], $_POST['role'], $_POST['dir'], $_POST['ram'], $_POST['port'], $_POST['version']);
	// Delete user
	if ($_POST['action'] == 'user-delete' && $_POST['user']) {
		$stu = user_info($_POST['user']);
		if (!$_SESSION['user'] == $_POST['user'])
			user_delete($_POST['user'], $stu['dir']);
	}
	// Start a server
	if ($_POST['action'] == 'server-start') {
		$stu = user_info($_POST['user']);
		if (!server_running($stu['user']))
			server_start($stu['user']);
	}
	// Kill a server
	if ($_POST['action'] == 'server-stop') 
		if ($_POST['user'] == 'ALL')
			server_kill_all();
		else
			server_kill($_POST['user']);		
}
?><!doctype html>
<html>
<head>
	<title>Administration | MCHostPanel</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="css/smooth.css" id="smooth-css">
	<link rel="stylesheet" href="css/style.css">
	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			check_cron();
			window.setTimeout(function () {
				$('.alert-success,.alert-error').fadeOut();
			}, 3000);
			$('#frm-killall').submit(function () {
				return confirm('Are you sure you want to KILL EVERY SERVER?\nServers will not save any new data, and all connected players will be disconnected!');
			});
			function check_cron() {
				$.post('ajax.php', {
					req: 'get_cron',
					user: $('#backup-user').val()
				}, function (data) {
					var enabled = !!data.hrFreq; //check the existence of a property
					if(enabled) {
						$("#backup-create").prop("disabled",true);
						$("#backup-delete").removeAttr("disabled");
						$("#hrDeleteAfter").prop("disabled",true);
						$("#hrFreq").prop("disabled",true);
						$("#hrDeleteAfter").val(data.hrDeleteAfter);
						$("#hrFreq").val(data.hrFreq);
					} else {
						$("#backup-create").removeAttr("disabled");
						$("#backup-delete").prop("disabled",true);
						$("#hrDeleteAfter").removeAttr("disabled");
						$("#hrFreq").removeAttr("disabled");
						$("#hrDeleteAfter").val(0);
						$("#hrFreq").val(1);
					}
				});
			}
			$("#backup-user").change(check_cron);
		});
	</script>
<?php require 'inc/top.php'; ?>
</head>
<body>
<div class="container-fluid">
	<h1 class="pull-left">Administration</h1>
	<?php if (isset($_POST['action']) && $_POST['action'] == 'user-add') { ?>
		<p class="alert alert-success pull-right"><i class="icon-ok"></i> User added successfully.</p>
	<?php } elseif (isset($_POST['action']) && $_POST['action'] == 'server-start') { ?>
		<p class="alert alert-success pull-right"><i class="icon-ok"></i> Server started.</p>
	<?php } elseif (isset($_POST['action']) && $_POST['action'] == 'server-stop') { ?>
		<p class="alert alert-success pull-right"><i class="icon-ok"></i> Server killed.</p>
	<?php } elseif (isset($_POST['action']) && $_POST['action'] == 'user-delete') {
		if (!$_SESSION['user'] == $_POST['user']) { ?>
			<p class="alert alert-success pull-right"><i class="icon-ok"></i> User deleted successfully.</p>
		<?php } else { ?>
			<p class="alert alert-danger pull-right">You can't delete your own account!</p>
		<?php } ?>
	<?php } ?>
	<div class="clearfix"></div>
	<div class="row-fluid">
		<div class="span8">
			<legend>Capacity</legend>
			<pre><?php echo `df -h` ?></pre>
			<legend>Ram (MB)</legend>
			<pre><?php echo `free -mtl` ?></pre>
			<form action="admin.php" method="post">
				<legend>Running Servers</legend>
				<pre>Running as user: <?php echo `whoami` . "\n" . `screen -ls`; ?></pre>
				<input type="hidden" name="action" value="server-start">
				<select name="user" style="vertical-align: top;">
					<optgroup label="Users">
						<?php
						$ul = user_list();
						foreach ($ul as $u)
							if($u != "empty")
								echo '<option value="' . $u . '">' . $u . '</option>';
						?>
					</optgroup>
				</select>
				<button type="submit" class="btn btn-success">Start Server</button>
			</form>

			<form action="admin.php" method="post">
				<input type="hidden" name="action" value="server-stop">
				<select name="user" style="vertical-align: top;">
					<option value="ALL">All Servers</option>
					<optgroup label="Users">
						<?php
						$ul = user_list();
						foreach ($ul as $u)
							if($u != "empty")
								echo '<option value="' . $u . '">' . $u . '</option>';
						?>
					</optgroup>
				</select>
				<button type="submit" class="btn btn-danger">Kill Server</button>
			</form>

			<form action="admin.php" method="post">
				<input type="hidden" name="action" value="backup-manage">
				<legend>Scheduled Backups</legend>
				<pre><?php echo shell_exec('crontab -l'); ?></pre>
				<label class="control-label" for="user">Server</label>
				<div class="controls">
					<select name="user" style="vertical-align: top;" id="backup-user">
						<?php
						$ul = user_list();
						foreach ($ul as $u)
							if($u != "empty")
								echo '<option value="' . $u . '">' . $u . '</option>';
						?>
					</select>
				</div>

				<label class="control-label" for="ram">Backup frequency</label>
				<div class="controls">
					<div class="input-append">
						<input class="span3" type="number" name="hrFreq" id="hrFreq" value="1">
						<span class="add-on">Hours</span>
					</div>
					<span class="text-info">4 = Every 4 Hours</span>
				</div>

				<label class="control-label" for="ram">Delete backups older than</label>
				<div class="controls">
					<div class="input-append">
						<input class="span3" type="number" name="hrDeleteAfter" id="hrDeleteAfter" value="0">
						<span class="add-on">Hours</span>
					</div>
					<span class="text-info">0 = Never delete, 4 = Delete after 4 hours</span>
				</div>


				<button type="submit" name="create" id="backup-create" class="btn btn-success">Enable</button>
				<button type="submit" name="delete" id="backup-delete" class="btn btn-danger">Disable</button>
			</form>
		</div>
		<div class="span4">
			<form action="admin.php" method="post">
				<legend>Switch to a User</legend>
				<input type="hidden" name="action" value="user-switch">
				<select name="user" style="vertical-align: top;">
					<?php
					$ul = user_list();
					foreach ($ul as $u)
						if($u != "empty")
							echo '<option value="' . $u . '">' . $u . '</option>';
					?>
				</select>
				<button type="submit" class="btn btn-danger">Log In</button>
			</form>

			<form action="admin.php" method="post" autocomplete="off">
				<input type="hidden" name="action" value="user-add">
				<legend>Add New User</legend>
				<div class="control-group">
					<label class="control-label" for="user">Username</label>

					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span>
							<input class="span4" type="text" name="user" id="user">
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="pass">Password</label>

					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-lock"></i></span>
							<input class="span4" type="password" name="pass" id="pass">
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="dir">Home Directory</label>

					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-folder-open"></i></span>
							<input class="span10" type="text" name="dir" id="dir" value="/app/server/">
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="ram">Server Memory</label>

					<div class="controls">
						<div class="input-append">
							<input class="span3" type="number" name="ram" id="ram" value="512">
							<span class="add-on">MB</span>
						</div>
						<span class="text-info">0 MB = No Server</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="port">Server Port</label>

					<div class="controls">
						<input class="span3" type="number" name="port" id="port" value="<?php echo rand(1000,65535)?>">
						<span class="text-info">0 = No Server</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="role">User Role</label>

					<div class="controls">
						<select name="role" id="role" class="span4">
							<option value="user" selected>User</option>
							<option value="premium">Premium</option>
							<option value="admin">Administrator</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="version">Server Version</label>
					
					<div class="controls">
						<select name="version" id="version" class="span4">
							<option value="1.14.4">Spigot 1.14.4</option>
							<option value="1.12.2">Spigot 1.12.2</option>
							<option value="1.11.2">Spigot 1.11.2</option>
							<option value="1.10.2">Spigot 1.10.2</option>
							<option value="1.9.4">Spigot 1.9.4</option>
							<option value="1.8.8">Spigot 1.8.8</option>
							<option value="1.7.10">Spigot 1.7.10</option>
							<option value="BC">Bungeecord</option>
							<option value="NONE">None</option>
						</select>
					</div>
				</div>
				<button type="submit" class="btn btn-primary">Add User</button>
			</form>
			<form action="admin.php" method="post">
				<legend>Delete a User</legend>
				<input type="hidden" name="action" value="user-delete">
				<select name="user" style="vertical-align: top;">
					<?php
					$ull = user_list();
					foreach ($ull as $u)
						if($u != "empty")
							echo '<option value="' . $u . '">' . $u . '</option>';
					?>
				</select>
				<button type="submit" class="btn btn-danger">Delete user</button>
			</form>
		</div>
	</div>
</div>
</body>
</html>
