<?php
if(!file_exists("_index.php")) {
  header("Location: //".$_SERVER['SERVER_NAME']."/panel");
  die();
} else {
  include("_index.php");
}
?>
