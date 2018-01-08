<?php
require('inc/lib.php');
$dir = scandir(dirname(__FILE__));
foreach($dir as $f) {
  echo $f;
}
