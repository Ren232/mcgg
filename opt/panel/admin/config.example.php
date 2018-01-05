<?php
    $PATH        = Array();
    $mysql       = Array();
    $flatfile    = Array();
    $API         = Array();
    $GENERAL     = Array();
    $useflatfile = false;

    /* Configuration Settings */
    
    $API['USER']       =   "<apiUser>";
    $API['PASS']       =   "<apiPass>";
    $API['ADDRESS']    =   "<apiAddress>";
    $API['PORT']       =   "<apiPort>";
    $API['SALT']       =   "<apiSalt>";
    
    
    /* MYSQL CONFIGURATION*/
    
    $mysql['HOST']     =   "<dbHost>"; // Mysql Host
    $mysql['USER']     =   "<dbUser>"; // Mysql Username
    $mysql['PASS']     =   "<dbPass>";          // Mysql Password
    $mysql['DB']       =   "<dbName>"; // Mysql Database
   
    /* Paths to files */
    
    $PATH['www']        =   "<pathWWW>"; // path to MineAdmin
    $PATH['minecraft']  =   "<pathMinecraft>"; // Path to minecraft server folder

    $flatfile['HOST']    =   "";
    $flatfile['USER']    =   "";
    $flatfile['PASS']    =   "";
    $flatfile['DB']      =   $PATH['minecraft'];

    /* Edit to use custom tablenames */
    $TABLES = Array(
        "backups"      => "backups",
        "bans"         => "bans",
        "groups"       => "groups",
        "homes"        => "homes",
        "items"        => "items",
        "kits"         => "kits",
        "reservelist"  => "reservelist",
        "users"        => "users",
        "warps"        => "warps",
        "whitelist"    => "whitelist"
    );

    /* Minecraft server speciic settings */
	
	$MCSERVER['PORT']	 = "<mcserverPort>";     //Default minecraft port
	$MCSERVER['SERVICENAME'] = "<mcserverService>"; //Default minecraft service/screen name

    /* Methods for backup */
	$PATH['backup']		=	"<backupPath>";
    $Backup_Method      =   "node.js"; // node.js, backup.plugin
    
?>