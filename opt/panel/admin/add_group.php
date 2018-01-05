<?php
require_once('header_inc.php');
if($_GET['save']=="1"){
    $name=$db->escape_string($_POST['name']);
    $admin=($_POST['admin']=="on"?"1":"0");
    $canmodifyworld=$db->escape_string($_POST['canmodifyworld']=="on"?"1":"0");
    $ignoresrestrictions=$db->escape_string($_POST['ignoresrestrictions']=="on"?"1":"0");
    $inheritedgroups=$db->escape_string($_POST['inheritedgroups']);
    $prefix=$db->escape_string($_POST['prefix']);
    $db->insert("groups",Array("prefix"=>$prefix,
							  "inheritedgroups"=>$inheritedgroups,
							  "name"=>$name,
							  "admin"=>$admin,
							  "canmodifyworld"=>$canmodifyworld,
							  "ignoresrestrictions"=>$ignoresrestrictions
							  ));
    header("Location: groups.php");
}
?>
<form action="add_group.php?save=1" method="post">
    <div style="width:500px;">
        <div class="overlay_title"><h1 class="over_html_h1">Add New Group</h1></div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Name <br><span>Group Name</span></span>
                <span class="input_area"><input type="text" class="input_text" name="name"></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Prefix <br /><span>Group prefix</span></span>
                <span class="input_area"><input type="text" class="input_text" name="prefix" style="width:20px;"></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Inherit Group <br /><span>Get group permissions and apply to this group in additition to this groups permissions</span></span>
                <span class="input_area">
                    <select class="input_text" name="inheritedgroups">
                        <?php
                            foreach($minecraft->group_list() as $group){
                                ?><option value="<?php echo $group['name'];?>" <?php echo $group['defaultgroup']==true? 'selected="selected"':'';?>><?php echo $group['name'];?></option><?PHP
                            }
                        ?>
                    </select>
                </span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Admin <br /><span>Users in group are admins?</span></span>
                <span class="input_area"><input type="checkbox" name="admin"></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Can Modify World <br /><span>Should users in group be able to modify the world?</span></span>
                <span class="input_area"><input type="checkbox" name="canmodifyworld" checked="checked"></span>
            </label>
        </div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Ignore Restrictions <br /><span>Should users in group be able to ignore restrictions?</span></span>
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