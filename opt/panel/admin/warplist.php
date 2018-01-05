<?php 
require_once('header_inc.php');
require_once('includes/header.php');
?>
	<div id="page_wrap">
	
		<div class="info">Info message</div>
        <div class="success">Successful operation message</div>
        <div class="warning">Warning message</div>
        <div class="error">Error message</div>
		<div id="warp_wrap">
		<h1>Warp List</h1>
		<table>
			<th>Warp Name</th>
			<th>Warp Group</th>
			<th>Actions</th>
			<?php
			$warps = $minecraft->warp_list();
			foreach ($warps as $warp) {
				echo "<tr>";
				echo "<td>".$warp['name'].'</td>';
				echo "<td>".$warp['group'].'</td>';
				echo "<td><a href='tools.php?action=dlwarp&id=".$warp['id']."'><img src='images/icons/delete.png'></a></td>";
				echo "</tr>";
			}
			?>
		</table>
		</div>
	</div>
<?php require_once('includes/footer.php'); ?>
