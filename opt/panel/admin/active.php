<?php
$pageid = "active";
require_once('header_inc.php');
require_once('includes/header.php');
?>
	<div id="page_wrap">
		<div id="online_wrap">
			<h1>Who's Online</h1>
			<span id="online"></span>
		</div>
		<div id="inventory_wrap" style="display:none;">
			<h1><span id="user"></span>'s Inventory</h1>
			<div class="give_link"><span style="float:left;text-align:left;margin-top:10px;"><a href="javascript:clear_inv();" class="link_give">&lsaquo; Clear Inventory &rsaquo;</a></span><span style="float:right;text-align:right;"><label><span>Item Name</span><input type="text" id="item_complete" /></label> <label><span>Amount</span><input type="text" id="item_amount" /></label> <a href="javascript:give_item();" class="link_give">&lsaquo; Give Item &rsaquo;</a></span></div>
			<div class="back_link"><a href="javascript:hide_inv();" class="link_hide">&lsaquo;&lsaquo; Go Back</a></div>
			<span id="inventory"></span>
			<div class="back_link"><a href="javascript:hide_inv();" class="link_hide">&lsaquo;&lsaquo; Go Back</a></div>
		</div>
	</div>
	<script type="text/javascript">
		$("body").ready(function(){
			get_player_list();
		});
	</script> 
<?php require_once('includes/footer.php'); ?>