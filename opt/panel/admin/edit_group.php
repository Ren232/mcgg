<?php
require_once('header_inc.php');
$group_info=$minecraft->group_get_id($db->escape_string($_GET['gid']));
if(isset($_GET['save']) && $_GET['save']=="1"){
    $admin=($_POST['admin']=="on"?"1":"0");
    $canmodifyworld=$db->escape_string($_POST['canmodifyworld']=="on"?"1":"0");
    $ignoresrestrictions=$db->escape_string($_POST['ignoresrestrictions']=="on"?"1":"0");
    $inheritedgroups=$db->escape_string($_POST['inheritedgroups']);
    $db->set("groups",
	        Array("inheritedgroups"=>$inheritedgroups,
				  "admin"=>$admin,
	              "canmodifyworld"=>$canmodifyworld,
	              "ignoresrestrictions"=>$ignoresrestrictions
	              ),
              Array("id"=>$group_info['id']));
    header("Location: groups.php");
}else if(isset($_GET['save']) && $_GET['save']=="2"){
    if($db->delete("groups",Array("id"=>$group_info['id']))){
        echo "1";
    }else{
        echo "0";
    }
    exit;
}else if(isset($_GET['save']) && $_GET['save']=="3"){
    if($db->set("groups",Array("defaultgroup"=>0),"")){
        if($db->set("groups",Array("defaultgroup"=>0),"",Array("id"=>$group_info['id']))){
            echo "1";
        }else{
            echo "0";
        }
    }else{
        echo "0";
    }
    exit;
}
?>
<form action="edit_group.php?save=1&gid=<?php echo $group_info['id']; ?>" method="post">
    <div style="width:500px;">
        <div class="overlay_title"><h1 class="over_html_h1"><?php echo $group_info['name'];?>'s Settings</h1></div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Inherit Group <br /><span>Get group permissions and apply to this group in additition to this groups permissions</span></span>
                <span class="input_area">
                    <select class="input_text" name="inheritedgroups">
                        <option value="">- none -</option>
                        <?php
                            foreach($minecraft->group_list() as $group){
                                ?><option value="<?php echo $group['name'];?>" <?php echo $group['name']==$group_info['inheritedgroups']? 'selected="selected"':'';?>><?php echo $group['name'];?></option><?PHP
                            }
                        ?>
                    </select>
                </span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Admin <br /><span>Users in group are admins?</span></span>
                <span class="input_area"><input type="checkbox" name="admin" <?php echo $group_info['admin']==true? 'checked="checked"':'';?>></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Can Modify World <br /><span>Should users in group be able to modify the world?</span></span>
                <span class="input_area"><input type="checkbox" name="canmodifyworld" <?php echo $group_info['canmodifyworld']==true? 'checked="checked"':'';?>></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Ignore Restrictions <br /><span>Should users in group be able to ignore restrictions?</span></span>
                <span class="input_area"><input type="checkbox" name="ignoresrestrictions" <?php echo $group_info['ignoresrestrictions']==true? 'checked="checked"':'';?>></span>
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