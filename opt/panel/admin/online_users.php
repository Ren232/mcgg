<?php
require_once('header_inc.php');
$logged=true;
if($_SESSION['user']==""){
        $logged=false;
}
$list = $minecraft->player_list();
//(12-3-2010)Emirin: Since we return an array now, $list->code isn't going to return anything.  Now we take the count of records and validate it.
if(count($list) == 0) {
        echo "<p>No one is currently online.</p>";
} else {
?>
<ul>
        <li class="name" style="text-decoration:underline;">Nickname</li>
        <li class="group" style="text-decoration:underline;">Group</li>
        <li class="build" style="text-decoration:underline;">Can Build?</li>
        <li class="world" style="text-decoration:underline;">Modify World</li>
        <li class="ip" style="text-decoration:underline;">IP Address</li>
        <li class="hand" style="text-decoration:underline;">Held Item</li>
        <li class="actions" style="text-decoration:underline;"></li>
</ul>
<?php
foreach($list as $player){
?>
<ul>
	<li class="name"><?php echo $player['name'];?></li>
	<li class="group"><?php $grp=$minecraft->group_prefix($player['prefix']);  echo $grp['name'];?></li>
	<li class="build"><?php if($player['canBuild']){ echo "<img src=\"images/icons/accept.png\" />"; }else{ echo "<img src=\"images/toolbar-icon-delete.png\" />"; }?></li>
	<li class="world"><?php if($player['canModifyWorld']){ echo "<img src=\"images/icons/accept.png\" />"; }else{ echo "<img src=\"images/toolbar-icon-delete.png\" />"; };?></li>
	<li class="ip"><?php if($logged){ echo $player['ip']; }else{ echo "HIDDEN"; };?></li>
	<li class="hand"><?php if($player['itemInHand']!="-1"){ echo "<img src=\"images/".$player['itemInHand'].".png\" title=\"".$minecraft->get_item_name($player['itemInHand'])."\" />"; }else{ echo "o"; }?></li>
	<li class="actions"><a href="javascript:kick_player('<?php echo $player['name'];?>');">&lsaquo; kick &rsaquo;</a> <!--<a href="javascript:ban_player('<?php echo $player['name'];?>','<?php echo $player['ip'];?>');">ban</a>//--> <a href="javascript:inv_player('<?php echo $player['name'];?>');">&lsaquo; inventory &rsaquo;</a> <a href="javascript:msg_player('<?php echo $player['name'];?>');">&lsaquo; msg &rsaquo;</a></li>
</ul>
<?php
}
}
?>