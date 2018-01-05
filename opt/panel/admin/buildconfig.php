<?php
	error_reporting(E_ERROR | E_PARSE);
	/*Alright kids, its time to build your config files!
	
		We are going to do this the easy way.
	*/
	
	$config = file_get_contents("config.example.php");
	
	/* General Server Configuration */

	if ($_POST['conf_mapath'] != '')
	{
		$config = str_replace("<pathWWW>", $_POST['conf_mapath'], $config);
	} else {
		$config = str_replace("<pathWWW>", "/var/adminpanel/", $config);
	}

	if ($_POST['conf_srvpath'] != '')
	{
		$config = str_replace("<pathMinecraft>", $_POST['conf_srvpath'], $config);
	} else {
		$config = str_replace("<pathMinecraft>", "/var/minecraftserver/", $config);
	}

	if ($_POST['conf_srvport'] != '')
	{
		$config = str_replace("<mcserverPort>", $_POST['conf_srvport'], $config);
	} else {
		$config = str_replace("<mcserverPort>", "25565", $config);
	}

	if ($_POST['conf_service'] != '')
	{
		$config = str_replace("<mcserverService>", $_POST['conf_service'], $config);
	} else {
		$config = str_replace("<mcserverService>", "Minecraft", $config);
	}

	if ($_POST['conf_backuppath'] != '')
	{
		if ($_POST['conf_backuppath'][strlen($_POST['conf_backuppath']) - 1] == "/")
		{
			$_POST['conf_backuppath'][strlen($_POST['conf_backuppath']) - 1] = " ";
		}
		$config = str_replace("<backupPath>", trim($_POST['conf_backuppath']), $config);
	} else {
		$config = str_replace("<backupPath>", "/backups", $config);
	}
	
	
	/* Data Storage (MYSQL for now but maybe if your lucky TheCrazyT will make ya some options!  You have to ask him really nice though.) */

	if ($_POST['mysql_host'] != '')
	{
		$config = str_replace("<dbHost>", $_POST['mysql_host'], $config);
	} else {
		$config = str_replace("<dbHost>", "localhost", $config);
	}

	if ($_POST['mysql_database'] != '')
	{
		$config = str_replace("<dbName>", $_POST['mysql_database'], $config);
	} else {
		$config = str_replace("<dbName>", "minecraft", $config);
	}

	if ($_POST['mysql_username'] != '')
	{
		$config = str_replace("<dbUser>", $_POST['mysql_username'], $config);
	} else {
		$config = str_replace("<dbUser>", "root", $config);
	}

	if ($_POST['mysql_password'] != '')
	{
		$config = str_replace("<dbPass>", $_POST['mysql_password'], $config);
	} else {
		$config = str_replace("<dbPass>", "root", $config);
	}


	/* API */

	if ($_POST['api_username'] != '')
	{
		$config = str_replace("<apiUser>", $_POST['api_username'], $config);
	} else {
		$config = str_replace("<apiUser>", "admin", $config);
	}

	if ($_POST['api_password'] != '')
	{
		$config = str_replace("<apiPass>", $_POST['api_password'], $config);
	} else {
		$config = str_replace("<apiPass>", "test", $config);
	}

	if ($_POST['api_address'] != '')
	{
		$config = str_replace("<apiAddress>", $_POST['api_address'], $config);
	} else {
		$config = str_replace("<apiAddress>", "localhost", $config);
	}

	if ($_POST['api_port'] != '')
	{
		$config = str_replace("<apiPort>", $_POST['api_port'], $config);
	} else {
		$config = str_replace("<apiPort>", "20059", $config);
	}
	
	if ($_POST['api_salt'] != '')
	{
		$config = str_replace("<apiSalt>", $_POST['api_salt'], $config);
	} else {
		$config = str_replace("<apiSalt>", "wib32ib$(TH\$g42y42bv42G#@G*(", $config);
	}

	/* Validation that we can actualy do everything */
	ini_set('mysql.connect_timeout', 2);
	flush();
	$conn = mysql_connect($_POST['mysql_host'], $_POST['mysql_username'],  $_POST['mysql_password']);
	if (!$conn)
	{
		$err .= "Cannot find your database server.  Please make sure its up and running.<br />";
	} else {
		if (!mysql_select_db($_POST['mysql_database'], $conn))
		{
			$err .= "Cannot find your database.  Your server is up, but your database ". $_POST['mysql_database'] ." cannot be found.<br />";
		} else {
			$rs = mysql_query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "users" and table_schema = "'.$_POST['mysql_database'].'"', $conn);
			if (mysql_num_rows($rs) == 0)
			{
				mysql_query("CREATE TABLE users (
  id int NOT NULL AUTO_INCREMENT,
  name varchar(100) NULL,
  password varchar(100) NULL,
  PRIMARY KEY (id)
)");
			}
			$rs = mysql_query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "users" AND COLUMN_NAME = "password" and table_schema = "'.$_POST['mysql_database'].'"', $conn);
			if (mysql_num_rows($rs) == 0)
			{
				mysql_query('ALTER TABLE users ADD password varchar(255)');
			}
			mysql_query('insert into users (name, password) values ("admin", "a94a8fe5ccb19ba61c4c0873d391e987982fbbd3");', $conn);

			$rs = mysql_query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "items" and table_schema = "'.$_POST['mysql_database'].'"', $conn);
			if (mysql_num_rows($rs) == 0)
			{
				mysql_query('CREATE TABLE items (name varchar(64) NOT NULL, itemid int(10) unsigned NOT NULL, PRIMARY KEY (name))');
				mysql_query("insert into items VALUES ('adminium', '7')", $conn);
				mysql_query("insert into items VALUES ('air', '0')", $conn);
				mysql_query("insert into items VALUES ('apple', '260')", $conn);
				mysql_query("insert into items VALUES ('arrow', '262')", $conn);
				mysql_query("insert into items VALUES ('bedrock', '7')", $conn);
				mysql_query("insert into items VALUES ('boat', '333')", $conn);
				mysql_query("insert into items VALUES ('bone', '352')", $conn);
				mysql_query("insert into items VALUES ('book', '340')", $conn);
				mysql_query("insert into items VALUES ('bookshelf', '47')", $conn);
				mysql_query("insert into items VALUES ('bow', '261')", $conn);
				mysql_query("insert into items VALUES ('bowl', '281')", $conn);
				mysql_query("insert into items VALUES ('bowlwithsoup', '282')", $conn);
				mysql_query("insert into items VALUES ('bread', '297')", $conn);
				mysql_query("insert into items VALUES ('brick', '336')", $conn);
				mysql_query("insert into items VALUES ('brickblock', '45')", $conn);
				mysql_query("insert into items VALUES ('brickwall', '45')", $conn);
				mysql_query("insert into items VALUES ('brownmushroom', '39')", $conn);
				mysql_query("insert into items VALUES ('bucket', '325')", $conn);
				mysql_query("insert into items VALUES ('button', '77')", $conn);
				mysql_query("insert into items VALUES ('cactus', '81')", $conn);
				mysql_query("insert into items VALUES ('Cake', '354')", $conn);
				mysql_query("insert into items VALUES ('Cake Block', '92')", $conn);
				mysql_query("insert into items VALUES ('chainmailboots', '305')", $conn);
				mysql_query("insert into items VALUES ('chainmailchestplate', '303')", $conn);
				mysql_query("insert into items VALUES ('chainmailhelmet', '302')", $conn);
				mysql_query("insert into items VALUES ('chainmailpants', '304')", $conn);
				mysql_query("insert into items VALUES ('chest', '54')", $conn);
				mysql_query("insert into items VALUES ('clay', '337')", $conn);
				mysql_query("insert into items VALUES ('clayblock', '82')", $conn);
				mysql_query("insert into items VALUES ('cloth', '35')", $conn);
				mysql_query("insert into items VALUES ('coal', '263')", $conn);
				mysql_query("insert into items VALUES ('coalore', '16')", $conn);
				mysql_query("insert into items VALUES ('cobble', '4')", $conn);
				mysql_query("insert into items VALUES ('cobblestone', '4')", $conn);
				mysql_query("insert into items VALUES ('cobblestonestairs', '67')", $conn);
				mysql_query("insert into items VALUES ('compass', '345')", $conn);
				mysql_query("insert into items VALUES ('cookedfish', '350')", $conn);
				mysql_query("insert into items VALUES ('cookedmeat', '320')", $conn);
				mysql_query("insert into items VALUES ('cookedpork', '320')", $conn);
				mysql_query("insert into items VALUES ('crop', '59')", $conn);
				mysql_query("insert into items VALUES ('crops', '59')", $conn);
				mysql_query("insert into items VALUES ('diamond', '264')", $conn);
				mysql_query("insert into items VALUES ('diamondaxe', '279')", $conn);
				mysql_query("insert into items VALUES ('diamondblock', '57')", $conn);
				mysql_query("insert into items VALUES ('diamondboots', '313')", $conn);
				mysql_query("insert into items VALUES ('diamondchestplate', '311')", $conn);
				mysql_query("insert into items VALUES ('diamondhelmets', '310')", $conn);
				mysql_query("insert into items VALUES ('diamondhoe', '293')", $conn);
				mysql_query("insert into items VALUES ('diamondore', '56')", $conn);
				mysql_query("insert into items VALUES ('diamondpants', '312')", $conn);
				mysql_query("insert into items VALUES ('diamondpick', '278')", $conn);
				mysql_query("insert into items VALUES ('diamondpickaxe', '278')", $conn);
				mysql_query("insert into items VALUES ('diamondshovel', '277')", $conn);
				mysql_query("insert into items VALUES ('diamondspade', '277')", $conn);
				mysql_query("insert into items VALUES ('diamondsword', '276')", $conn);
				mysql_query("insert into items VALUES ('dirt', '3')", $conn);
				mysql_query("insert into items VALUES ('Dispenser', '23')", $conn);
				mysql_query("insert into items VALUES ('doublestair', '43')", $conn);
				mysql_query("insert into items VALUES ('eggs', '344')", $conn);
				mysql_query("insert into items VALUES ('feather', '288')", $conn);
				mysql_query("insert into items VALUES ('fence', '85')", $conn);
				mysql_query("insert into items VALUES ('fire', '51')", $conn);
				mysql_query("insert into items VALUES ('fish', '349')", $conn);
				mysql_query("insert into items VALUES ('fishingrod', '346')", $conn);
				mysql_query("insert into items VALUES ('flint', '318')", $conn);
				mysql_query("insert into items VALUES ('flintandsteel', '259')", $conn);
				mysql_query("insert into items VALUES ('flower', '37')", $conn);
				mysql_query("insert into items VALUES ('furnace', '61')", $conn);
				mysql_query("insert into items VALUES ('glass', '20')", $conn);
				mysql_query("insert into items VALUES ('gold', '41')", $conn);
				mysql_query("insert into items VALUES ('goldaxe', '286')", $conn);
				mysql_query("insert into items VALUES ('goldbar', '266')", $conn);
				mysql_query("insert into items VALUES ('goldblock', '41')", $conn);
				mysql_query("insert into items VALUES ('goldboots', '317')", $conn);
				mysql_query("insert into items VALUES ('goldchestplate', '315')", $conn);
				mysql_query("insert into items VALUES ('goldenapple', '322')", $conn);
				mysql_query("insert into items VALUES ('goldhelmet', '314')", $conn);
				mysql_query("insert into items VALUES ('goldhoe', '294')", $conn);
				mysql_query("insert into items VALUES ('goldore', '14')", $conn);
				mysql_query("insert into items VALUES ('goldpants', '316')", $conn);
				mysql_query("insert into items VALUES ('goldpick', '285')", $conn);
				mysql_query("insert into items VALUES ('goldpickaxe', '285')", $conn);
				mysql_query("insert into items VALUES ('goldrecord', '2256')", $conn);
				mysql_query("insert into items VALUES ('goldshovel', '284')", $conn);
				mysql_query("insert into items VALUES ('goldspade', '284')", $conn);
				mysql_query("insert into items VALUES ('goldsword', '283')", $conn);
				mysql_query("insert into items VALUES ('grass', '2')", $conn);
				mysql_query("insert into items VALUES ('gravel', '13')", $conn);
				mysql_query("insert into items VALUES ('greenrecord', '2257')", $conn);
				mysql_query("insert into items VALUES ('gunpowder', '289')", $conn);
				mysql_query("insert into items VALUES ('ice', '79')", $conn);
				mysql_query("insert into items VALUES ('ink sac', '351')", $conn);
				mysql_query("insert into items VALUES ('iron', '42')", $conn);
				mysql_query("insert into items VALUES ('ironaxe', '258')", $conn);
				mysql_query("insert into items VALUES ('ironbar', '265')", $conn);
				mysql_query("insert into items VALUES ('ironblock', '42')", $conn);
				mysql_query("insert into items VALUES ('ironboots', '309')", $conn);
				mysql_query("insert into items VALUES ('ironchestplate', '307')", $conn);
				mysql_query("insert into items VALUES ('irondoor', '330')", $conn);
				mysql_query("insert into items VALUES ('irondoorblock', '71')", $conn);
				mysql_query("insert into items VALUES ('ironhelmet', '306')", $conn);
				mysql_query("insert into items VALUES ('ironhore', '292')", $conn);
				mysql_query("insert into items VALUES ('ironore', '15')", $conn);
				mysql_query("insert into items VALUES ('ironpants', '308')", $conn);
				mysql_query("insert into items VALUES ('ironpick', '257')", $conn);
				mysql_query("insert into items VALUES ('ironpickaxe', '257')", $conn);
				mysql_query("insert into items VALUES ('ironshovel', '256')", $conn);
				mysql_query("insert into items VALUES ('ironspade', '256')", $conn);
				mysql_query("insert into items VALUES ('ironsword', '267')", $conn);
				mysql_query("insert into items VALUES ('jacko', '91')", $conn);
				mysql_query("insert into items VALUES ('jackolantern', '91')", $conn);
				mysql_query("insert into items VALUES ('jukebox', '84')", $conn);
				mysql_query("insert into items VALUES ('ladder', '65')", $conn);
				mysql_query("insert into items VALUES ('Lapis Lazuli Block', '22')", $conn);
				mysql_query("insert into items VALUES ('Lapis Lazuli Ore', '21')", $conn);
				mysql_query("insert into items VALUES ('lava', '10')", $conn);
				mysql_query("insert into items VALUES ('lavabucket', '327')", $conn);
				mysql_query("insert into items VALUES ('leather', '334')", $conn);
				mysql_query("insert into items VALUES ('leatherboots', '301')", $conn);
				mysql_query("insert into items VALUES ('leatherchestplate', '299')", $conn);
				mysql_query("insert into items VALUES ('leatherhelmet', '298')", $conn);
				mysql_query("insert into items VALUES ('leatherpants', '300')", $conn);
				mysql_query("insert into items VALUES ('leaves', '18')", $conn);
				mysql_query("insert into items VALUES ('lever', '69')", $conn);
				mysql_query("insert into items VALUES ('lightdust', '348')", $conn);
				mysql_query("insert into items VALUES ('lighter', '259')", $conn);
				mysql_query("insert into items VALUES ('lightstone', '89')", $conn);
				mysql_query("insert into items VALUES ('lightstonedust', '348')", $conn);
				mysql_query("insert into items VALUES ('litfurnace', '62')", $conn);
				mysql_query("insert into items VALUES ('log', '17')", $conn);
				mysql_query("insert into items VALUES ('meat', '319')", $conn);
				mysql_query("insert into items VALUES ('milkbucket', '335')", $conn);
				mysql_query("insert into items VALUES ('minecart', '328')", $conn);
				mysql_query("insert into items VALUES ('mobspawner', '52')", $conn);
				mysql_query("insert into items VALUES ('mossy', '48')", $conn);
				mysql_query("insert into items VALUES ('mossycobblestone', '48')", $conn);
				mysql_query("insert into items VALUES ('netherstone', '87')", $conn);
				mysql_query("insert into items VALUES ('note block', '25')", $conn);
				mysql_query("insert into items VALUES ('obsidian', '49')", $conn);
				mysql_query("insert into items VALUES ('painting', '321')", $conn);
				mysql_query("insert into items VALUES ('paintings', '321')", $conn);
				mysql_query("insert into items VALUES ('paper', '339')", $conn);
				mysql_query("insert into items VALUES ('pork', '319')", $conn);
				mysql_query("insert into items VALUES ('portal', '90')", $conn);
				mysql_query("insert into items VALUES ('poweredminecart', '343')", $conn);
				mysql_query("insert into items VALUES ('pumpkin', '86')", $conn);
				mysql_query("insert into items VALUES ('rail', '66')", $conn);
				mysql_query("insert into items VALUES ('rails', '66')", $conn);
				mysql_query("insert into items VALUES ('rawfish', '349')", $conn);
				mysql_query("insert into items VALUES ('redmushroom', '40')", $conn);
				mysql_query("insert into items VALUES ('redstonedust', '331')", $conn);
				mysql_query("insert into items VALUES ('redstoneore', '73')", $conn);
				mysql_query("insert into items VALUES ('redstoneorealt', '74')", $conn);
				mysql_query("insert into items VALUES ('redstonetorchoff', '75')", $conn);
				mysql_query("insert into items VALUES ('redstonetorchon', '76')", $conn);
				mysql_query("insert into items VALUES ('redstonewire', '55')", $conn);
				mysql_query("insert into items VALUES ('reedblock', '83')", $conn);
				mysql_query("insert into items VALUES ('rock', '1')", $conn);
				mysql_query("insert into items VALUES ('rockplate', '70')", $conn);
				mysql_query("insert into items VALUES ('rose', '38')", $conn);
				mysql_query("insert into items VALUES ('saddle', '329')", $conn);
				mysql_query("insert into items VALUES ('sand', '12')", $conn);
				mysql_query("insert into items VALUES ('sandstone', '24')", $conn);
				mysql_query("insert into items VALUES ('sapling', '6')", $conn);
				mysql_query("insert into items VALUES ('seeds', '295')", $conn);
				mysql_query("insert into items VALUES ('sign', '323')", $conn);
				mysql_query("insert into items VALUES ('signblock', '63')", $conn);
				mysql_query("insert into items VALUES ('signblocktop', '68')", $conn);
				mysql_query("insert into items VALUES ('slava', '11')", $conn);
				mysql_query("insert into items VALUES ('slimeorb', '341')", $conn);
				mysql_query("insert into items VALUES ('slowsand', '88')", $conn);
				mysql_query("insert into items VALUES ('snow', '78')", $conn);
				mysql_query("insert into items VALUES ('snowball', '332')", $conn);
				mysql_query("insert into items VALUES ('snowblock', '80')", $conn);
				mysql_query("insert into items VALUES ('soil', '60')", $conn);
				mysql_query("insert into items VALUES ('soup', '282')", $conn);
				mysql_query("insert into items VALUES ('soupbowl', '282')", $conn);
				mysql_query("insert into items VALUES ('sponge', '19')", $conn);
				mysql_query("insert into items VALUES ('stair', '44')", $conn);
				mysql_query("insert into items VALUES ('stairs', '67')", $conn);
				mysql_query("insert into items VALUES ('step', '44')", $conn);
				mysql_query("insert into items VALUES ('stick', '280')", $conn);
				mysql_query("insert into items VALUES ('stilllava', '11')", $conn);
				mysql_query("insert into items VALUES ('stillwater', '9')", $conn);
				mysql_query("insert into items VALUES ('stone', '1')", $conn);
				mysql_query("insert into items VALUES ('stoneaxe', '275')", $conn);
				mysql_query("insert into items VALUES ('stonehoe', '291')", $conn);
				mysql_query("insert into items VALUES ('stonepick', '274')", $conn);
				mysql_query("insert into items VALUES ('stonepickaxe', '274')", $conn);
				mysql_query("insert into items VALUES ('stoneplate', '70')", $conn);
				mysql_query("insert into items VALUES ('stoneshovel', '273')", $conn);
				mysql_query("insert into items VALUES ('stonespade', '273')", $conn);
				mysql_query("insert into items VALUES ('stonesword', '272')", $conn);
				mysql_query("insert into items VALUES ('storageminecart', '342')", $conn);
				mysql_query("insert into items VALUES ('string', '287')", $conn);
				mysql_query("insert into items VALUES ('Sugar', '353')", $conn);
				mysql_query("insert into items VALUES ('sugar cane', '338')", $conn);
				mysql_query("insert into items VALUES ('tnt', '46')", $conn);
				mysql_query("insert into items VALUES ('torch', '50')", $conn);
				mysql_query("insert into items VALUES ('track', '66')", $conn);
				mysql_query("insert into items VALUES ('tracks', '66')", $conn);
				mysql_query("insert into items VALUES ('tree', '17')", $conn);
				mysql_query("insert into items VALUES ('wallsign', '68')", $conn);
				mysql_query("insert into items VALUES ('watch', '347')", $conn);
				mysql_query("insert into items VALUES ('water', '8')", $conn);
				mysql_query("insert into items VALUES ('waterbucket', '326')", $conn);
				mysql_query("insert into items VALUES ('wheat', '296')", $conn);
				mysql_query("insert into items VALUES ('wodpick', '270')", $conn);
				mysql_query("insert into items VALUES ('wood', '5')", $conn);
				mysql_query("insert into items VALUES ('woodaxe', '271')", $conn);
				mysql_query("insert into items VALUES ('wooddoor', '324')", $conn);
				mysql_query("insert into items VALUES ('wooddoorblock', '64')", $conn);
				mysql_query("insert into items VALUES ('woodhoe', '290')", $conn);
				mysql_query("insert into items VALUES ('woodpickaxe', '270')", $conn);
				mysql_query("insert into items VALUES ('woodplate', '72')", $conn);
				mysql_query("insert into items VALUES ('woodshovel', '269')", $conn);
				mysql_query("insert into items VALUES ('woodspade', '269')", $conn);
				mysql_query("insert into items VALUES ('woodstairs', '53')", $conn);
				mysql_query("insert into items VALUES ('woodsword', '268')", $conn);
				mysql_query("insert into items VALUES ('workbench', '58')", $conn);
			}

			if(mysql_errno($conn))
			{
				$err .= "Could not set up a default user for you, most likely cause there is no password field due to user rights to the information_schema table.<br />";
			}
		}
	}
	
	foreach(get_loaded_extensions() as $ext)
	{
		if (strpos(" " . $ext, 	"json") > 0)
		{
			$jsontest = true;
		}
	}
	if (!$jsontest)
	{
		$err .= "You do not have Json installed as an extension in php.<br />";
	}

	if (strlen($err) > 0)
	{
		echo '<img src="images/321.png" style="display:none;" onload="document.getElementById(\'errors\').innerHTML = \''.$err.'\'">';
	}else {
		$newconfig = fopen("config.php", "w");
		if ($newconfig)
		{
			fwrite($newconfig, str_replace("\\", "\\\\", $config));
			fclose($newconfig);
			echo '<img src="images/321.png" style="display:none;" onload="document.location.href=\'index.php\'">';
		} else
		{
			$err .= "Cannot write the config.php.  Please make sure your web server user (usualy apache) has access to the directory.<br />";
			echo '<img src="images/321.png" style="display:none;" onload="document.getElementById(\'errors\').innerHTML = \''.$err.'\'">';
		}
	}
	
?>