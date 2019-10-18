<?php
require_once 'inc/lib.php';

session_start();
if (!empty($_SESSION['user'])) {

	if (!$user = user_info($_SESSION['user'])) {
		if (ini_get('session.use_cookies')) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		}
		// User does not exist, redirect to login page
		header('Location: .');
		exit('Not logged in!');
	}

} elseif (!empty($_POST['user']) && !empty($_POST['pass'])) {

	// Get user data
	$user = user_info($_POST['user']);

	$_SESSION['is_admin'] = $user['role'] == 'admin';

	// Check user exists and password is good
	if (!$user || ($_POST['pass'] != $user['pass'])) {
		// Login failure, redirect to login page
		header('Location: ./?error=badlogin');
		exit('Not logged in!');
	}

	// Current user is valid
	$_SESSION['user'] = $user['user'];

} else {

	// Not logged in, redirect to login page
	header('Location: .');
	exit('Not logged in!');

}

if(isset($_POST['key'])) { 
	set_key($user['user'],$user['home'].'/ngrok.yml',$user['key'],$_POST['key']);
	user_modify($user['user'],$user['pass'],$user['role'],$user['home'],$user['ram'],$user['port'],$user['jar'],$_POST['key']);
	set_key($user['user'],$user['home'].'/ngrok.yml',$user['key'],$_POST['key']);
	user_modify($user['user'],$user['pass'],$user['role'],$user['home'],$user['ram'],$user['port'],$user['jar'],$_POST['key']);
}
?><!doctype html>
<html>
<head>
	<title>Dashboard | MCHostPanel</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="css/smooth.css" id="smooth-css">
	<link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/TimeCircles.css" />
	<style type="text/css">
        #DateCountdown {
            height:calc(100vh);
        }
		#cmd {
			height: 30px;
		}
		form {
			margin: 0;
		}
		.hidden {
		    display: none;
		}
	</style>
	<script src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/TimeCircles.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript">
	    var getHTML = function ( url, callback ) {
        
            // Feature detection
            if ( !window.XMLHttpRequest ) return;
        
            // Create new request
            var xhr = new XMLHttpRequest();
        
            // Setup callback
            xhr.onload = function() {
                if ( callback && typeof( callback ) === 'function' ) {
                    callback( this.responseXML );
                }
            }
        
            // Get the HTML
            xhr.open( 'GET', url );
            xhr.responseType = 'document';
            xhr.send();
        
        };
		function modify(key) {
    		jQuery(document).ready(function($){
			var $key = key
			alert("The ngrok key has been set to "+$key)
	          $.ajax({
	                url: 'ajax.php', //window.location points to the current url. change is needed.
	                type: 'POST',
	                data: {
	                	key: $key
	                },
	          });
	
	      });
		}
		function updateStatus(once) {
			$.post('ajax.php', {
				req: 'server_running'
			}, function (data) {
				if (data) {
				    $('#DateCountdown').removeClass('hidden');
				    $('#DateCountdownTxt').text('');
					$('#lbl-status').text('Online').addClass('label-success').removeClass('label-important');
					$('#btn-srv-start').prop('disabled', true);
					$('#btn-srv-stop,#btn-srv-restart').prop('disabled', false);
					$('#cmd').prop('disabled', false);
					$('#ngrok_stat').text("<?=ngrok_stat($user['user'])?>");
				} else {
				    $('#DateCountdown').addClass('hidden');
				    $('#DateCountdownTxt').text('Server Offline');
					$('#lbl-status').text('Offline').addClass('label-important').removeClass('label-success');
					$('#btn-srv-start').prop('disabled', false);
					$('#btn-srv-stop,#btn-srv-restart').prop('disabled', true);
					$('#cmd').prop('disabled', true);
					$('#ngrok_stat').text('Server Offline');
				}
			}, 'json');
			if (!once)
				window.setTimeout(updateStatus, 1000);
		}
		function updatePlayers() {
			$.post('ajax.php', {
				req: 'players'
			}, function (data) {
				if (data.error) {
					$('#lbl-players').text('Server Offline').attr('title', 'Please allow the query in server.properties to receive server information.').tooltip();
				} else {
					try{
						console.log(data);
					} catch(ex) {}

					if(data.players === false) {
						$('#lbl-players').text(0 + '/' + data.info.MaxPlayers);
					} else {
						$('#lbl-players').text(data.players.length + '/' + data.info.MaxPlayers);
						$('#lbl-players').append('<br/><br/>');
						$('#lbl-players').append('<legend>Players online:</legend>');
					}
					$.each(data.players, function (i, val) {
						console.log(val);
						$('#lbl-players').append('<img src="//minotar.net/avatar/' + val + '/24"> ' + val + '<br>');
					});
				}
			}, 'json').error(function(){
				$('#lbl-players').text('Error');
			});
		}
		function server_start() {
			$.post('ajax.php', {
				req: 'server_start'
			}, function () {
				updateStatus(true);
			});
		}
		function server_stop(callback) {
			$.post('ajax.php', {
				req: 'server_stop'
			}, function () {
				updateStatus(true);
				if (callback)
					callback();
			});
		}
		
		function set_jar() {
			$.post('ajax.php', {
				req: 'set_jar',
				jar: $('#server-jar').val()
			});
		}
		function refreshLog() {
			updateStatus();
			$.post('ajax.php', {
				req: 'server_log'
			}, function (data) {
				if ($('#log').scrollTop() == $('#log')[0].scrollHeight) {
					$('#log').html(data).scrollTop($('#log')[0].scrollHeight);
				} else {
					$('#log').html(data);
				}
				window.setTimeout(refreshLog, 0);
			});
		}
		function refreshLogOnce() {
			$.post('ajax.php', {
				req: 'server_log'
			}, function (data) {
				$('#log').html(data).scrollTop($('#log')[0].scrollHeight);
			});
		}
		$(document).ready(function () {
			updateStatus();
			updatePlayers();
			$('button.ht').tooltip();
			$('#btn-srv-start').click(function () {
				server_start();
				timer_start();
				$(this).prop('disabled', true).tooltip('hide');
			});
			$('#btn-srv-stop').click(function () {
				server_stop();
				$(this).prop('disabled', true).tooltip('hide');
			});
			$('#btn-srv-restart').click(function () {
				server_stop(server_start);
				$('').prop('disabled', true).tooltip('hide');
			});

			// Send commands with form onSubmit
			$('#frm-cmd').submit(function () {
				$.post('ajax.php', {
					req: 'server_cmd',
					cmd: $('#cmd').val()
				}, function () {
					$('#cmd').val('').prop('disabled', false).focus();
					refreshLogOnce();
				});
				$('#cmd').prop('disabled', true);
				return false;
			});

			// Handle JAR change
			$('#server-jar').change(set_jar);
			

			// Fix sizing
			$('#log').css('height', $(window).height() - 200 + 'px');

			// Initialize log
			$.post('ajax.php', {
				req: 'server_log'
			}, function (data) {
				$('#log').html(data).scrollTop($('#log')[0].scrollHeight);
				window.setTimeout(refreshLog, 0);
			});

			// Keep sizing correct
			$(document).resize(function () {
				$('#log').css('height', $(window).height() - 200 + 'px');
			});
		});
	</script>
</head>
<body>
<?php require 'inc/top.php'; ?>
<div class="tab-content">
	<div class="tab-pane active">
		<?php if (!empty($user['ram'])) { ?>
			<div class="row-fluid">
				<div class="span5">
					<div class="well">
						<legend>Dashboard</legend>
						<div class="btn-toolbar">
							<div class="btn-group">
								<button class="btn btn-large btn-primary ht" id="btn-srv-start" title="Start" disabled><i class="icon-play"></i></button>
								<button class="btn btn-large btn-danger ht" id="btn-srv-stop" title="Stop" disabled><i class="icon-stop"></i></button>
							</div>
							<div class="btn-group">
								<button class="btn btn-large btn-warning ht" id="btn-srv-restart" title="Restart" disabled><i class="icon-refresh"></i></button>
							</div>
						</div>
						<br>Up-time:
						<?php if(isset($user['active']) && $user['active'] !== "null") { ?>
						<div id="DateCountdown" data-date="<?=date('Y-m-d H:i:s',$user['active'])?>" style="height: 100%; padding: 0px; box-sizing: border-box; "></div>
						<?php } ?>
						<text id="DateCountdownTxt"></text>
						<p>JAR File</p>
						<select id="server-jar">
							<?php
								$jars = scandir($user['home']);
								foreach($jars as $file) {
									if(substr($file, -4) == '.jar') {
										if((!empty($user['jar']) && $user['jar'] == $file) || (empty($user['jar']) && $file == 'craftbukkit.jar')) {
											echo "<option value=\"$file\" selected>$file</option>";
										} else {
											echo "<option value=\"$file\">$file</option>";
										}
									} else echo 'No jar file detected.';
								}
							?>
						</select>
				<div class="control-group">
					<label class="control-label" for="ram">ngrok key:<?php if(empty($user['key']) || $user['key']==1234567890) { echo ' - To make the server work you need a ngrok key.'; } ?></label>

					<div class="controls">
						<div class="input-append">
							<input class="span6" type="text" name="ngrok" id="ngrok" onchange="modify(this.value)" placeholder="Enter your ngrok key..." value="<?=$user['key']?>">
						</div>
						<span class="text-info">Get a ngrok key: <a href="//dashboard.ngrok.com/">Ngrok Dashboard</a></span>
					</div>
				</div>
					</div>
					<div class="well">
						<legend>Server information</legend>
						<p><b>Status:</b> <span class="label" id="lbl-status">Checking&hellip;</span><br>
							<b>IP:</b> <?php echo KT_LOCAL_IP . ':' . $user['port']; ?><br>
							<b>Ngrok IP: </b>
							<span id="ngrok_stat"></span><br>
							<b>Machine IP:</b> <?php echo shell_exec('curl ipinfo.io/ip')?><br>
							<b>RAM:</b> <?php echo $user['ram'] . 'MB'; ?><br>
							<b>Players:</b> <span id="lbl-players">Checking&hellip;</span><br>
							<b>Banner:</b><br>
							<?php
						 		$banner_Get = ngrok_stat($user['user']);
						 		$banner_RemoveSpace = str_replace(" ","",$banner_Get);
						 		$banner_RemoveSlash = str_replace(":","/",$banner_RemoveSpace);
						 		$banner_Url = $banner_RemoveSlash;
						 		echo '<img src="http://status.mclive.eu/Minecraft%20Server/'.$banner_Url.'/banner.png">';?><br>
						</p>
						<div class="player-list"></div>
					</div>
				</div>
				<div class="span7">
					<pre id="log" class="well well-small"></pre>
					<form id="frm-cmd">
						<input type="text" id="cmd" name="cmd" maxlength="250" placeholder="Enter command..." autofocus>
					</form>
				</div>
			</div>
		<?php
		} else if ($user['suspended'] == 'true') {
			echo '
			<p class="alert alert-danger">Your server got suspended.</p>
';
		} else 
			echo '
			<p class="alert alert-info">Your account does not have a server.</p>
';
		?>
	</div>
</div>
<script>
$("#DateCountdown").TimeCircles();
</script>

</body>
</html>
