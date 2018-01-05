<?php
$pageid = "users";
require_once('header_inc.php');
require_once('includes/header.php');
?>
	<div id="page_wrap">
			<h1>User List</h1>
                        <div class="give_link"><span style="height:19px;float:left;text-align:left;margin-top:10px;"><a href="add_user.php" class="link_give fancy">Add New User</a></span></div>
			<span id="online">
                            <ul>
                                    <li class="build" style="width:70px;">UserID</li>
                                    <li class="name" style="text-align:left;">Name</li>
                                    <li class="hand" style="width:70px;">Group</li>
                                    <li class="ip">IP Address</li>
                                    <li class="build" style="width:65px;">Access</li>
                                    <li class="build" style="width:65px;">Admin</li>
                                    <li class="world" style="width:65px;">Modify</li>
                                    <li class="build" style="width:65px;">Ignore</li>
                                    <li class="actions" style="width:180px;"></li>
                            </ul>
                            <?php
                            foreach($minecraft->user_list() as $user){
                                ?>
                                <div>
                                    <ul>
                                            <li class="build" style="width:70px;"><?php echo $user['id'];?></li>
                                            <li class="name" style="text-align:left;"><?php echo $user['name'];?></li>
                                            <li class="hand" style="width:70px;"><?php echo $user['groups'];?></li>
                                            <li class="ip"><?php echo ($user['ip']==""? "<u>Not Set</u>": $user['ip']);?></li>
                                            <li class="hand" style="width:65px"><?php if($user['password']){ echo "<img src=\"images/icon-checkmark.png\" />"; }else{ echo "<img src=\"images/toolbar-icon-delete.png\" />"; };?></li>
                                            <li class="hand" style="width:65px;"><?php if($user['admin']){ echo "<img src=\"images/icon-checkmark.png\" />"; }else{ echo "<img src=\"images/toolbar-icon-delete.png\" />"; };?></li>
                                            <li class="world" style="width:65px;"><?php if($user['canmodifyworld']){ echo "<img src=\"images/icon-checkmark.png\" />"; }else{ echo "<img src=\"images/toolbar-icon-delete.png\" />"; };?></li>
                                            <li class="hand" style="width:65px;"><?php if($user['ignoresrestrictions']){ echo "<img src=\"images/icon-checkmark.png\" title=\"Ignoring restriction\" />"; }else{ echo "<img src=\"images/toolbar-icon-delete.png\" title=\"restriction in place for this user\" />"; };?></li>
                                            <li class="actions" style="width:180px;"><a class="fancy" href="edit_user.php?uid=<?php echo $user['id'];?>">&lsaquo; edit &rsaquo;</a> <a class="fancy" href="user_commands.php?uid=<?php echo $user['id'];?>">&lsaquo; clist &rsaquo;</a> <a href="javascript:delete_user('<?php echo $user['name'];?>','<?php echo $user['id'];?>');">&lsaquo; delete &rsaquo;</a></li>
                                    </ul>
                                </div>
                                <?PHP
                            }
                            ?>
                        </span>

		</div>
	</div>
        <script type="text/javascript">
		$("body").ready(function(){
			$(".fancy").fancybox();
		});
		</script> 
<?php require_once('includes/footer.php'); ?>