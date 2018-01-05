<?php
$pageid = "sysinfo";
require_once('header_inc.php');
require_once('includes/header.php');
$players = count($minecraft->player_list);
$max = count($minecraft->player_limit);
function get_memory() {
  foreach(file('/proc/meminfo') as $ri)
    $m[strtok($ri, ':')] = strtok('');
  return 100 - round(($m['MemFree'] + $m['Buffers'] + $m['Cached']) / $m['MemTotal'] * 100);
}

?>
	<div id="page_wrap">
		<p>Players: <?php echo $players; ?> out of <?php echo $max; ?> allowed are currently online</p><br />
		<p>Hostname: <?php echo shell_exec('hostname -f'); ?></p><br />
		<p>IP Address: <?php echo shell_exec("/sbin/ifconfig venet0:0 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'"); ?></p><br />
		<p>CPU Usage: <?php echo shell_exec("ps aux|awk 'NR > 0 { s +=$3 }; END {print s}'"); ?>%</p><br />
		<p>Memory Usage: <?php echo get_memory(); ?>%</p><br />
		<p>Disk usage: <?php disk_free_space("/")."/".disk_total_space("/");?></p><br />
	</div>
<?php require_once('includes/footer.php'); ?>