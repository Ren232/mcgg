<?php
$pageid = "plugins";
require_once('header_inc.php');
require_once('includes/header.php');
if($_GET['action'] == "enable") {
	$minecraft->enable_plugin($_GET['id']);
	echo "<div class='success' style='display:block;'>Enabled ".$_GET['id']."</div>";
} elseif($_GET['action'] == "disable") {
	if($_GET['id'] == "CraftAPI") {
		die("You can NOT disable CraftAPI. Try again!");
	}
	$minecraft->disable_plugin($_GET['id']);
	echo "<div class='success' style='display:block;'>Disabled ".$_GET['id']."</div>";
}
?>
	<div id="page_wrap" style="text-align: center;">
		<div class='info' style='display:block;'>You can add plugins for hey0 by contacting support. We hope to fix this in the *very* near future.</div>
		<table style="border-collapse:collapse;width: 100%;">
			<th>Name</th>
			<th>Status</th>
			<th>Actions</th>
		<?php foreach ($minecraft->get_plugins() as $plugin) {
			if($plugin['enabled'] == "1") {
				$img = "<img src='images/icons/accept.png'>";
				if($plugin['id'] == "CraftAPI") {
					$url = "<p>You cannot disable CraftAPI</p>";
				} else {
				$url = "<a href='plugins.php?action=disable&id=".$plugin['id']."'>Disable Plugin</a>";
			}
			} else {
				$img = "<img src='images/icons/cross.png'>";
				$url = "<a href='plugins.php?action=enable&id=".$plugin['id']."'>Enable Plugin</a>";
			}
			echo "<tr>";
			echo "<td>".$plugin['id']."</td>";
			echo "<td>".$img."</td>";
			echo "<td>".$url."</td>";
			echo "</tr>";
		} 
		?>
		</table>
	</div>
<?php require_once('includes/footer.php'); ?>