<?php
require('inc/lib.php');
if(file_exists('.installed')) {
  $users = scandir('data/users');
  foreach($users as $user) {
    $array = explode('.', $user);
    $ext = end($array);
    if($ext == 'json') {
      $json = json_decode('data/users/'.$user);
      // Start the server
      echo "-----> Starting server for user".$json->user."...";
      server_start($json->user);
    }
  }
} else {
  echo "-----> Warning: '.installed' not found! Shutting down... "
  exit();
}
?>
    
      
