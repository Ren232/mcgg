<html lang="en"> 
<head>
	<title>MineAdmin Installer - MineAdmin.com</title>
	<link rel="stylesheet" type="text/css" href="css/installer.css" />
	<script type="text/javascript">
	function ajaxPostForm(url, id, form)
	{
		var i, postvar =""
		document.body.cursor = "wait";
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.open("POST",url,false);

		for (i=0; i < form.elements.length; i++)
		{
			object = form.elements[i];
			if(object.name != "")
			{
				if(postvar == "")
				{
					postvar = object.name +"=" + object.value;
				} else {
					postvar += "&" + object.name +"=" + object.value;
				}
			}
		}
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send(postvar);
		document.getElementById(id).innerHTML=xmlhttp.responseText;
		eval();
		document.body.cursor = "default";
	}
</script>
</head>
<body>
	<div id="installer">
		<h1 id="logo">MineAdmin</h1>
		<div id="steps">
			<ul>
				<li>
					<div id="errors"></div>
					<div id="ajaxRunner"></div>
					
					<?php
					if(!file_exists('config.php')) {
					?>
					<h2>Welcome to MineAdmin.</h2>
					<p>Before you can use MineAdmin, there are a few things that need to be set up.</p>
					<?php
					} else {
					?>
					<h2>Hey!</h2>
					<p>You already installed MineAdmin! You can't do it again!</p>
					<p><a href="<?php echo $_SERVER['HTTP_HOST']; ?>/admin/index.php">Go Login</a></p>
					<?php
					}
					?>

					<form name="mrT" id="mrT" method="post">
						 <?php /* thats right mother fuckers, I pitty da foo who can't config!  Best learn before you wreck yo self!  Thats right, I also added this php tag just to comment, suck it!*/ ?>
						<h2>General Server Configuration</h2>
						<label for="conf_mapath">
							Path to MineAdmin: 
							<input type="text" class="input_text" name="conf_mapath" id="conf_mapath" value="<?php echo str_replace('install.php','',$_SERVER['SCRIPT_FILENAME']); ?>">
						</label><br /><br />
						<label for="conf_srvpath">
							Path to Minecraft Server: 
							<input type="text" class="input_text" name="conf_srvpath" id="conf_srvpath">
						</label><br /><br />
						<label for="conf_backuppath">
							Path to Backup Directory: 
							<input class="input_text" name="conf_backuppath" id="conf_backuppath" type="text">
						</label><br><br>
						<label for="conf_srvport">
							Minecraft Server Port: 
							<input type="text" class="input_text" name="conf_srvport" id="conf_srvport" value="25565">
						</label><br /><br />
						<label for="conf_service">
							Minecraft Server/Screen Name: 
							<input type="text" class="input_text" name="conf_service" id="conf_service" value="Minecraft">
						</label>
						<h2>MySQL</h2>
						<label for="mysql_host">
							Host: 
							<input type="text" class="input_text" name="mysql_host" id="mysql_host">
						</label><br /><br />
						<label for="mysql_database">
							Database Name: 
							<input type="text" class="input_text" name="mysql_database" id="mysql_database">
						</label><br /><br />
						<label for="mysql_username">
							Username: 
							<input type="text" class="input_text" name="mysql_username" id="mysql_username">
						</label><br /><br />
						<label for="mysql_password">
							Password: 
							<input type="password" class="input_text" name="mysql_password" id="mysql_password">
						</label>
						<h2>API</h2>
						<label for="api_username">
							Username: 
							<input type="text" class="input_text" name="api_username" id="api_username">
						</label><br /><br />
						<label for="api_password">
							Password: 
							<input type="password" class="input_text" name="api_password" id="api_password">
						</label><br /><br />
						<label for="api_address">
							Address: 
							<input type="text" class="input_text" name="api_address" id="api_address">
						</label><br /><br />
						<label for="api_port">
							Port: 
							<input type="text" class="input_text" name="api_port" id="api_port">
						</label><br /><br />
						<label for="api_salt">
							Salt: 
							<input type="text" class="input_text" name="api_salt" id="api_salt">
						</label>
						<h2>Ready to install.</h2>
						<p>We have everything we need, all you need to do is click 'Install'!</p>
						<p style="color:#ff0000;font-weight:bold;text-transform:uppercase;">Do not close this page while installing.</p>
						<input type="button" style="background:#144564;color:#fff;font-size:24px;width:92%;height:48px;margin-left:20px;margin-top:60px;" value="Install" onclick="document.getElementById('errors').innerHTML='';ajaxPostForm('buildconfig.php','ajaxRunner', document.getElementById('mrT'))"/>
					</form>
		 </div>
	</div>
</body>
</html>
