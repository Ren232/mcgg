<?php
session_start();
if($_SESSION['user']==""){
	header("Location: login.php");
	exit;
}
unset($_SESSION['user']);
@session_destroy();
header("Location: index.php");
?>