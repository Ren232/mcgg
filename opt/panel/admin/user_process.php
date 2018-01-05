<?php
require_once('header_inc.php');
switch($_POST['act']){
	case "kick":
		$nick=$_POST['nick'];
		$reason=$_POST['reason'];
		$minecraft->player_kick($nick,$reason);
	break;
	case "ban":
		$nick=$_POST['nick'];
		$reason=$_POST['reason'];
		$ip=$_POST['ip'];
		$minecraft->player_ban($nick,$reason);
	break;
	case "inventory":
		$nick=$_POST['nick'];
		?>
		<ul>
			<li>Slot</li>
			<li>Item ID</li>
			<li>Amount</li>
			<li>Item Icon</li>
			<li class="actions_i"></li>
		</ul>
		<?php
		$items=$minecraft->get_inventory($nick);
		foreach($items as $slotID=>$slot){
			$type=$minecraft->get_item_name($slot['itemID']);
			?>
			<div style="width:840px;">
				<ul>
					<li><?php echo $slotID;?></li>
					<li><?php echo $slot['itemID'];?></li>
					<li><?php echo $slot['amount'];?></li>
					<li><img src="images/<?php echo $slot['itemID'];?>.png" title="<?php echo $type;?>" /></li>
					<li class="actions_i"><a href="javascript:remove_slot('<?php echo $nick;?>','<?php echo $slotID;?>','<?php echo $slot['amount'];?>','<?php echo $type;?>');">&lsaquo; Delete Slot &rsaquo;</a></li>
				</ul>
			</div>
			<?php
		}
	break;
	case "rem_slot":
		$nick=$_POST['nick'];
		$slot=$_POST['slot'];
		echo $minecraft->remove_slot($nick,$slot);
	break;
	case "clear_inv":
		$nick=$_POST['nick'];
		echo $minecraft->clear_inv($nick);
	break;
	case "give":
		$nick=$_POST['nick'];
		$amount=$_POST['amount'];
		$item_name=$_POST['item'];
		$item=$minecraft->get_item_id($item_name);
		echo $minecraft->give_item($nick,$item,$amount);
		$minecraft->msg($nick,"[ADMIN] Here is ".$amount." ".$item_name);
	break;
	case "msg":
		$nick=$_POST['nick'];
		$msg=$_POST['message'];
		$minecraft->msg($nick,"[ADMIN] ".$msg);
	break;
	case "items":
		$ru=array();
		foreach($minecraft->item_list() as $item){
			$ru[]=$item['name'];
		}
		echo json_encode($ru);
	break;
}
?>