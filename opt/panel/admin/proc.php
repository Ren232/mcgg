<?php
$WshShell = new COM("WScript.Shell");
$oExec = $WshShell->Run("net stop Minecraft", 7, false);
$oExec = $WshShell->Run("net start Minecraft", 7, false);
/*

$proc=system("cmd C:/Minecraft/Hell/bin/lauchmc.bat");
  
var_dump($proc);
*/
//print stream_get_contents($pipes[1]);
/*


	$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("file", "tmp\\error-output.txt", "a") // stderr is a file to write to
);

$cwd = '/tmp';
$env = array('some_option' => 'aeiou');

$process = proc_open('cmd C:\\Minecraft\\bin\\lauchmc.bat', $descriptorspec, $pipes, $cwd, $env);
if (is_resource($process)) {
    // $pipes now looks like this:
    // 0 => writeable handle connected to child stdin
    // 1 => readable handle connected to child stdout
    // Any error output will be appended to /tmp/error-output.txt

    fwrite($pipes[0], '<?php print_r($_ENV); ?>');
    fclose($pipes[0]);

    echo stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    // It is important that you close any pipes before calling
    // proc_close in order to avoid a deadlock
    $return_value = proc_close($process);

    echo "command returned $return_value\n";
}*/
?>