<?php
require_once('header_inc.php');
$group_info=$minecraft->group_get_id($db->escape_string($_GET['gid']));
if(isset($_GET['save']) && $_GET['save']=="1"){
    $commands=$_POST['commands'];
    $clist="";
	//(12/27/2010)Emirin: Simple if statement to filter out * for admin stuff
	if ($commands == "*")
	{
		$clist="*";
	} else {
		preg_match_all('%/([0-9a-zA-Z]+)%simx', $commands, $result, PREG_PATTERN_ORDER);
		for ($i = 0; $i < count($result[0]); $i++) {
			if($clist==""){
				$clist="/".addslashes($result[1][$i]);
			}else{
				$clist.=",/".addslashes($result[1][$i]);
			}
		}
	}
    $db->set("groups",Array("commands"=>$clist),Array("id"=>$group_info['id']));
    
    header("Location: groups.php");
}
?>
<form action="group_commands.php?save=1&gid=<?PHP echo $group_info['id']; ?>" method="post">
    <div style="width:500px;">
        <div class="overlay_title"><h1 class="over_html_h1"><?PHP echo $group_info['name'];?>'s Commands</h1></div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Command List <br /><span>commands allowed by this group.</span></span>
                <span class="input_area"><textarea name="commands" style="width:200px;height:500px;"><?PHP echo str_replace(",","\n",$group_info['commands']);?></textarea></span>
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