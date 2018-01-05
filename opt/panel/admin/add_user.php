<?php
require_once('header_inc.php');
if($_GET['save']=="1"){
    $nick=$db->escape_string($_POST['nick']);
    $admin=($_POST['admin']=="on"?"1":"0");
    $canmodifyworld=$db->escape_string($_POST['canmodifyworld']=="on"?"1":"0");
    $ignoresrestrictions=$db->escape_string($_POST['ignoresrestrictions']=="on"?"1":"0");
    $ip=$db->escape_string($_POST['ip']);
    $group=explode(":",$db->escape_string($_POST['groups']));
	$password=sha1($db->escape_string($_POST['pass']));
	if (strlen($_POST['pass']) > 0)
	{
		$db->insert("users",Array("name"=>$nick,
								  "admin"=>$admin,
								  "canmodifyworld"=>$canmodifyworld,
								  "ignoresrestrictions"=>$ignoresrestrictions,
								  "ip"=>$ip,
								  "groups"=>$group[1],
								  "prefix"=>$group[0],
								  
								  ////TODO : Password input box? ...
								  "password"=>$password
								  ));
	} else {
		$db->insert("users",Array("name"=>$nick,
								  "admin"=>$admin,
								  "canmodifyworld"=>$canmodifyworld,
								  "ignoresrestrictions"=>$ignoresrestrictions,
								  "ip"=>$ip,
								  "groups"=>$group[1],
								  "prefix"=>$group[0]
								  ));
	}
    header("Location: users.php");
    exit();
}
?>
<form action="add_user.php?save=1" method="post">
    <div style="width:500px;">
        <div class="overlay_title"><h1 class="over_html_h1">Add New User</h1></div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Nickname <br><span>Player name ( minecraft name )</span></span>
                <span class="input_area"><input type="text" class="input_text" name="nick"></span>
            </label>
        </div>
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
                                ?><option value="<?php echo $group['prefix'];?>:<?php echo $group['name'];?>" <?php echo $group['defaultgroup']==true? 'selected="selected"':'';?>><?php echo $group['name'];?></option><?PHP
                            }
                        ?>
                    </select>
                </span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">IP Address <br /><span>IP address used by the user</span></span>
                <span class="input_area"><input type="text" class="input_text" name="ip" maxlength="15"></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Admin <br /><span>Is this user an admin?</span></span>
                <span class="input_area"><input type="checkbox" name="admin"></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Can Modify World <br /><span>Should this user be able to modify the world?</span></span>
                <span class="input_area"><input type="checkbox" name="canmodifyworld" checked="checked"></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Ignore Restrictions <br /><span>Should this user be able to ignore restrictions?</span></span>
                <span class="input_area"><input type="checkbox" name="ignoresrestrictions"></span>
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