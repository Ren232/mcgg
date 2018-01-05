<?php
if(!file_exists('config.php')) {
	header("Location: install.php");
} else {
	if(file_exists('install.php')) {
		unlink('install.php');
	}
	session_start();
	if($_SESSION['user']==""){
		header("Location: login.php");
		exit;
	}else{
		header("Location: start.php");
	}
}
?>