<?php
require_once('header_inc.php');
require_once('includes/header.php');


?>

	<div id="page_wrap">
	
		<div class="info">Info message</div>
        <div class="success">Successful operation message</div>
        <div class="warning">Warning message</div>
        <div class="error">Error message</div>
        		<div id="logs">
			<h1>Log viewer</h1>
			<textarea>
			<?php
			echo file_get_contents('/opt/server.log');
			?>
			</textarea>
		</div>
	</div>
<?php require_once('includes/footer.php'); ?>
