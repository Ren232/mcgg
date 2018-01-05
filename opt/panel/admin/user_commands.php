<?php
require_once('header_inc.php');
$user_info=$minecraft->user_get_id($db->escape_string($_GET['uid']));
if(isset($_GET['save']) && $_GET['save']=="1"){
    $commands=$_POST['commands'];
    $clist="";
    preg_match_all('%/([0-9a-zA-Z]+)%simx', $commands, $result, PREG_PATTERN_ORDER);
    for ($i = 0; $i < count($result[0]); $i++) {
        if($clist==""){
            $clist="/".$result[1][$i];
        }else{
            $clist.=",/".$result[1][$i];
        }
    }
    $db->set("users",Array("commands"=>$clist),Array("id"=>$user_info['id']));

    header("Location: users.php");
}
?>
<form action="user_commands.php?save=1&uid=<?php echo $user_info['id']; ?>" method="post">
    <div style="width:500px;">
        <div class="overlay_title"><h1 class="over_html_h1"><?PHP echo $user_info['name'];?>'s Commands</h1></div>
        <div class="over_html_row_wrap">
            <label>
                <span class="over_html_row">Command List <br /><span>Commands allowed by this group.</span></span>
                <span class="input_area"><textarea name="commands" style="width:200px;height:500px;"><?PHP echo str_replace(",","\n",$user_info['commands']);?></textarea></span>
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