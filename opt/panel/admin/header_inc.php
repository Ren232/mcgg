<?php
    session_start();
    if($_SESSION['user']==""){
            header("Location: login.php");
            exit;
    }
    require_once('config.php');

    if($useflatfile){
        require("flatfile.class.php");
        $db = new Flatfile($flatfile['HOST'], $flatfile['USER'], $flatfile['PASS'], $flatfile['DB']);
    }
    else
    {
        require("mysql.class.php");
        $db = new MySQL($mysql['HOST'], $mysql['USER'], $mysql['PASS'], $mysql['DB']);
    }

    if ( !$db->isconnected() ) {
        echo "MySQL Configuration Incorrect.";
        exit();
    }
    require("minecraft.class.php");
    $minecraft = new MineCraft();
?>