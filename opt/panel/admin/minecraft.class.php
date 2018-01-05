<?php
include_once("jsonrpc.class.php");
class minecraft{
    var $r;
    function __construct(){
        global $API;
        $this->r = new JsonRPC($API['ADDRESS'],$API['PORT'],$API['USER'],$API['PASS'],$API['SALT']);
    }
    function send_message($nick,$message){
        return $this->r->player->sendMessage($nick, $message);
    }
    function get_inventory($nick){
        return $this->r->player->getInventory($nick);
    }
    function remove_inventory($nick,$itemid,$amount){
        return $this->r->player->removeInventoryItem( $nick, $itemid, $amount );
    }
    function get_item_name($id){
        global $db;
       
        $result=$db->fetch("items",Array("itemid"=>$id),"",false,"");
        return $result['name'];
    }
    function get_item_id($name){
        global $db;
        $result=$db->fetch("items",Array("name"=>$name),"",false,"");
        return $result['itemid'];
    }
    function group_prefix($pref){
        global $db;
        $result=$db->fetch("groups",Array("prefix"=>$pref),"",false,"");
        return $result;
    }
    function clear_inv($nick){
        for($x=0;$x<36;$x++){
            $ret=$this->r->player->removeInventorySlot($nick, $x);
        }
        return 1;
    }
    function msg($nick,$message){
        return $this->r->player->sendMessage($nick,$message);
    }
    function give_item($nick,$item,$amount){
        return $this->r->player->giveItem($nick,intval($item),intval($amount));
    }
    function player_list(){
        return $this->r->player->getPlayers();
    }
	function player_limit(){
		return $this->r->server->getPlayerLimit();
	}
    function remove_slot($nick,$slot){
        return $this->r->player->removeInventorySlot($nick,intval($slot));
    }
    function player_info($nick){
        return $this->r->player->getPlayerInfo($nick);
    }
    function group_list(){
        global $db;
        $result=$db->fetch("groups","","",true,Array("name"=>"DESC"));
        return $result;
    }
    function user_list(){
        global $db;
        $result=$db->fetch("users","","",true,Array("id"=>"DESC"));
        return $result;
    }
    function get_plugin($plugin){
        return $this->r->server->getPlugin($plugin);
    }
    function get_plugins(){
        return $this->r->server->getPlugins();
    }
	function enable_plugin($plugin){
		return $this->r->server->enablePlugin($plugin);
	}
	function disable_plugin($plugin){
		return $this->r->server->disablePlugin($plugin);
	}
    function user_get_id($id){
        global $db;
        return $db->fetch("users",Array("id"=>$id),"",false,"");
    }
    function group_get_id($id){
        global $db;
        return $db->fetch("groups",Array("id"=>$id),"",false,"");
    }
    function player_kick($nick,$reason){
        return $this->r->player->kick($nick,$reason);
    }
    function player_ban($nick,$reason){
        return $this->r->server->runConsoleCommand("/ban $nick","Banned by MineAdmin");
    }
//added by robbiet480, 11/06/10
	function save_all(){
        return $this->r->server->runConsoleCommand("save-all");
    }
	function save_off(){
        return $this->r->server->runConsoleCommand("save-off");
    }
	function save_on(){
        return $this->r->server->runConsoleCommand("save-on");
    }
    function backup_list(){
	        global $db;
	        return $db->fetch("backups","","",true,Array("id"=>"DESC"));
	}
	function backup($name,$comment){
		global $PATH;
		global $db;
		function ByteSize($bytes)  
		    { 
		    $size = $bytes / 1024; 
		    if($size < 1024) 
		        { 
		        $size = number_format($size, 2); 
		        $size .= ' KB'; 
		        }  
		    else  
		        { 
		        if($size / 1024 < 1024)  
		            { 
		            $size = number_format($size / 1024, 2); 
		            $size .= ' MB'; 
		            }  
		        else if ($size / 1024 / 1024 < 1024)   
		            { 
		            $size = number_format($size / 1024 / 1024, 2); 
		            $size .= ' GB'; 
		            }  
		        } 
		    return $size; 
		    }
		$this->r->player->broadcastMessage("Map backup now starting");
		$this->r->player->broadcastMessage("Issuing save-all command");
		$this->r->server->runConsoleCommand("save-all");
		$this->r->player->broadcastMessage("Issuing save-off command");
		$this->r->server->runConsoleCommand("save-off");
		$date = date('Y-m-d-Hi');
		$output = $PATH['backups'].$date.".tgz";
		if(!file_exists($PATH['backups'])) {
			mkdir($PATH['backups'], 0777);
		}
		shell_exec("tar -czf ".$output." ".$PATH['minecraft']."world");
		$size = ByteSize(filesize($output));
		$result=$db->insert("backups", array("id"=>"","name"=>$name,"date"=>date('Y-m-d'),"time"=>date('Hi'),"size"=>$size,"comment"=>$comment,"filename"=>$output));
		$this->r->player->broadcastMessage("Issuing save-on command");
		$this->r->server->runConsoleCommand("save-on");
		return $result;
	}
	function backup_delete($id){
		global $db;
		$result=$db->fetch("backups",Array("id"=>"DESC"),"",true);
		unlink($result[0]['filename']);
		$result=$db->delete("backups", array("id"=>$id));
		return $result;
	}
	function server_stop(){
		$this->r->server->runConsoleCommand("stop");
	}
	function start_mapping(){
		$this->r->player->broadcastMessage("Map generation now starting");
		$this->r->player->broadcastMessage("Issuing save-all command");
		$this->r->server->runConsoleCommand("save-all");
		$this->r->player->broadcastMessage("Issuing save-off command");
		$this->r->server->runConsoleCommand("save-off");
	}
	
     function configuration_files(){
        global $PATH;
        $file_array=array();
        if ($handle = opendir($PATH['minecraft'])) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && $file != "mysql.properties" && $file != "craftapi.properties" ) {
                    $file_out=preg_split('/\./si', $file);
                    if($file_out[1]=="properties"){
                        $filename = $PATH['minecraft']."/".$file_out[0].".properties";
                        $handle2 = fopen($filename, "r");
                        $contents = fread($handle2, filesize($filename));
                        fclose($handle2);
                        $props=array();
                        preg_match_all('/^([a-zA-Z0-9\-]+)=(.*)/im', $contents, $resulti, PREG_PATTERN_ORDER);
                        for ($i = 0; $i < count($resulti[0]); $i++) {
                            $props[]=array($resulti[1][$i],$resulti[2][$i]);
                        }
                        $file_array[]=array(
                            "file"=>$file_out[0],
                            "properties"=>$props
                        );
                    }
                }
            }
            closedir($handle);
        }
        return $file_array;
    }
    function configuration($array){
        global $PATH;
        foreach($array as $key=>$val){
            if (preg_match('/\A([a-zA-Z0-9\-]+)_([a-zA-Z0-9\-]+)\Z/sim', $key)) {
                if (preg_match('/([a-zA-Z0-9\-]+)_([a-zA-Z0-9\-]+)/sim', $key, $regs)) {
                    $file_name = $regs[1];
                    $prop_var = $regs[2];
                }
                $filename = $PATH['minecraft'].$file_name.".properties";
                $handle2 = fopen($filename, "r");
                $contents = fread($handle2, filesize($filename));
                fclose($handle2);
                $contents = preg_replace('/'.$prop_var.'=([a-zA-Z0-9\-\t\. \/\\\\:,]+)*/sim', $prop_var.'='.$val, $contents);
                $fh=fopen($filename,"w"); 
                fwrite($fh,$contents);
                fclose($fh);
            }
        }
    }
    
    function item_list(){
        global $db;
        $result=$db->fetch("items","","",true,Array("name"=>"DESC"));
        return $result;
    }
	function reserve_list(){
        global $db;
        $result=$db->fetch("reservelist","","",true);
        return $result;
    }
    function server_test(){
        return $this->r->player->getInventory("Firestarthe");
    }
	function white_list(){
		global $db;
        $result=$db->fetch("whitelist","","",true);
        return $result;
	}
	function kit_list(){
		global $db;
        $result=$db->fetch("kits","","",true,Array("id"=>"DESC"));
        return $result;
	}
	function warp_list(){
		global $db;
        $result=$db->fetch("warps","","",true,Array("id"=>"DESC"));
        return $result;
	}
	function reload_bans(){
		return $this->r->server->reloadBanList();
	}
	function reload_groups(){
		return $this->r->server->reloadGroups();
	}
	function reload_homes(){
		return $this->r->server->reloadHomes();
	}
	function reload_kits(){
		return $this->r->server->reloadKits();
	}
	function reload_reservelist(){
		return $this->r->server->reloadReserveList();
	}
	function reload_warps(){
		return $this->r->server->reloadWarps();
	}
	function reload_whitelist(){
		return $this->r->server->reloadWhitelist();
	}
	function run_console($command){
		return $this->r->server->runConsoleCommand($command);
	}
}
?>
