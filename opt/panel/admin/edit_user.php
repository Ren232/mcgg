<?php
require_once('header_inc.php');
if(is_numeric($_GET['uid'])){
    $user_info=$minecraft->user_get_id($db->escape_string($_GET['uid']));
    if(isset($_GET['save']) && $_GET['save']=="1"){
        $admin=($_POST['admin']=="on"?"1":"0");
        $canmodifyworld=$_POST['canmodifyworld']=="on"?"1":"0";
        $ignoresrestrictions=$_POST['ignoresrestrictions']=="on"?"1":"0";
        $ip=$_POST['ip'];
        $group=explode(":",$_POST['groups']);
	$password=sha1($_POST['pass']);
	if (strlen($_POST['pass']) > 0)
	{
		$db->set("users",Array(
								  "admin"=>$admin,
								  "canmodifyworld"=>$canmodifyworld,
								  "ignoresrestrictions"=>$ignoresrestrictions,
								  "ip"=>$ip,
								  "groups"=>$group[1],
								  "prefix"=>$group[0],
								  
								  ////TODO : Password input box? ...
								  "password"=>$password),
								  Array("id"=>$user_info['id']));
	} else {
		$db->set("users",Array("admin"=>$admin,
								  "canmodifyworld"=>$canmodifyworld,
								  "ignoresrestrictions"=>$ignoresrestrictions,
								  "ip"=>$ip,
								  "groups"=>$group[1],
								  "prefix"=>$group[0]),
								  Array("id"=>$user_info['id']));
								  }
        header("Location: users.php");
        exit;
    }else if(isset($_GET['save']) && $_GET['save']=="2"){
        if($db->delete("users",Array("id"=>$user_info['id']))){
            echo "1";
        }else{
            echo "0";
        }
        exit;
    }
}else{
    echo "fail....";
    exit;
}
?>
<form action="edit_user.php?uid=<?php echo $user_info['id'];?>&save=1" method="post">
    <div style="width:500px;">
        <div class="overlay_title"><h1 class="over_html_h1"><?php echo $user_info['name'];?>'s Settings</h1></div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Password <br><span>(Blank passwords not allowed)</span></span>
                <span class="input_area"><input type="password" class="input_text" name="pass"></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Group <br /><span>Set user to a group</span></span>
                <span class="input_area">
                    <select class="input_text" name="groups">
                        <?php
                            foreach($minecraft->group_list() as $group){
                                ?><option value="<?php echo $group['prefix'];?>:<?php echo $group['name'];?>" <?php echo $group['name']==$user_info['groups']? 'selected="selected"':'';?>><?php echo $group['name'];?></option><?PHP
                            }
                        ?>
                    </select>
                </span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">IP Address <br /><span>IP address used by the user</span></span>
                <span class="input_area"><input type="text" class="input_text" name="ip" maxlength="15" value="<?php echo $user_info['ip'];?>"></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Admin <br /><span>Is this user an admin?</span></span>
                <span class="input_area"><input type="checkbox" name="admin" <?php if($user_info['admin']){ echo 'checked="checked"'; };?>></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Can Modify World <br /><span>Should this user be able to modify the world?</span></span>
                <span class="input_area"><input type="checkbox" name="canmodifyworld" <?php if($user_info['canmodifyworld']){ echo 'checked="checked"'; };?>></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Ignore Restrictions <br /><span>Should this user be able to ignore restrictions?</span></span>
                <span class="input_area"><input type="checkbox" name="ignoresrestrictions" <?php if($user_info['ignoresrestrictions']){ echo 'checked="checked"'; };?>></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row"></span>
                <span class="input_area" style="float:right;"><input class="button" type="submit" value="Save" /><input class="button" type="button" onclick="$.fancybox.close();" value="Close"></span>
            </label>
        </div>
        
    </div>
</form>