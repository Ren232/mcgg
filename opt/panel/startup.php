<?php
require dirname(__FILE__).'/inc/lib.php';
if(file_exists(dirname(__FILE__)."/.installed")) {
  $users = scandir(dirname(__FILE__).'/data/users');
  foreach($users as $user) {
    $array = explode('.', $user);
    $ext = end($array);
    if($ext == 'json') {
      $json = json_decode(dirname(__FILE__).'/data/users/'.$user);
      // Start the server
      error_log("-----> Starting server for user".$json->user."...");
      server_start($json->user);
    }
  }
} else {
  exit("-----> Warning: '.installed' not found! Shutting down... ");
}
// Exit screen
exit("-----> Completed startup ");
      
