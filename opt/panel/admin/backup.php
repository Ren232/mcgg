<?php
require_once('header_inc.php');
require_once('includes/header.php');

if($_GET['action'] == "backup") {
	$minecraft->backup($_POST['backup_name'],$_POST['backup_comment']);
} elseif($_GET['action'] == "dl") {
    $result=$db->fetch("backups",Array("id"=>$_GET['id']),"",true,"");
	if (file_exists($result[0]['filename'])) {
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename='.basename($result[0]['filename']));
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($result[0]['filename']));
	    ob_clean();
	    flush();
	    readfile($result[0]['filename']);
	    exit;
	}
} elseif($_GET['action'] == "delete") {
	$minecraft->backup_delete($_GET['id']);
} elseif($_GET['action'] == "restore") {
	$result=$db->fetch("backups",Array("id"=>$_GET['id']),"filename",true,"");
	stop_server();
	$restore = shell_exec('tar xvfz -C '.$PATH['minecraft'].'.. '.$result[0]['filename']);
	shell_exec('screen -dmS Minecraft java -Xmx'.$GENERAL["memory"].' -Xms'.$GENERAL["memory"].' -jar /opt/Minecraft_Mod.jar');
	echo "<div class='success' style='display:block;'>Restored backup!</div>";
} elseif($_GET['action'] == "dlrl") {
	$result=$db->delete("reservelist", array("name"=>$_GET['name']));
	$minecraft->reload_reservelist();
	echo "<div class='success' style='display:block;'>Removed ".$_GET['name']." from the reservelist</div>";
} elseif($_GET['action'] == "dlwl") {
	$result=$db->delete("reservelist", array("name"=>$_GET['name']));
	$minecraft->reload_whitelist();
	echo "<div class='success' style='display:block;'>Removed ".$_GET['name']." from the reservelist</div>";
} elseif($_GET['action'] == "dlwarp") {
	$result=$db->delete("warps", array("id"=>$_GET['id']));
	$minecraft->reload_warps();
	echo "<div class='success' style='display:block;'>Removed ".$_GET['id']." from warps</div>";
}
?>
	<div id="page_wrap">
	
		<div class="info">Info message</div>
        <div class="success">Successful operation message</div>
        <div class="warning">Warning message</div>
        <div class="error">Error message</div>
        
		<div id="backup_wrap">
			<h1>Backups</h1>
			<a href="javascript:void(0)" id="addnew"><img src="images/icons/database_add.png" alt="New Backup">&nbsp;New Backup</a>
		
			<div id="newbackup" style="display:none;margin: 15px 10px;">
				<form action="tools.php?action=backup" method="post">
					<label>Backup Name
					<input class="input_text" type="text" name="backup_name" style="width:200px;margin-left:10px" />
					</label>
					<label>Comment
					<input class="input_text" type="text" name="backup_comment" style="width:200px;margin-left:10px" />				
					</label>
					<span style="float:right;"><input class="button" type="submit" value="Save" /><input class="button" id="canceladd" type="button" value="Cancel" /></span>
				</form>
			</div>
			
			<table>
				<tr>
					<th>Name</th>
					<th>Date</th>
					<th>Time</th>
					<th>Size</th>
					<th>Comment</th>
					<th>Actions</th>
				</tr>
				<?php
	            foreach($minecraft->backup_list() as $backup){
	            ?>
				<tr>
					<td><?php echo $backup['name']; ?></td>
					<td><?php echo $backup['date']; ?></td>
					<td><?php echo $backup['time']; ?></td>
					<td><?php echo $backup['size']; ?></td>
					<td><?php echo $backup['comment']; ?></td>
					<td><a href="tools.php?action=dl&id=<?php echo $backup['id']; ?>"><img src="images/icons/database_save.png" alt="Download"></a>&nbsp;<a href="tools.php?action=delete&id=<?php echo $backup['id']; ?>"><img src="images/icons/database_delete.png" alt="Delete"></a>&nbsp;<a href="tools.php?action=restore&id=<?php echo $backup['id']; ?>"><img src="images/icons/database_go.png" alt="Restore"></a>&nbsp;</td>
				</tr>
				<?php
			}
				?>
			</table>
		</div>
	</div>
		<script type="text/javascript">
		$(function(){
			$('#addnew').click(function(){
				$('#newbackup').slideDown();
			});
			$('#canceladd').click(function(){
				$('#newbackup').slideUp();
			});
		});
	</script>
<?php require_once('includes/footer.php'); ?>
