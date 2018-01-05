<?php
require("database.class.php");
class Flatfile extends database{
	var $folder;
	var $queries_stats;
	var $overall_stats;
	public static $sortname;
	
	var $table_columns = Array("users" =>Array("name","groups","admin","color","commands","ip","password"),
							   "items" =>Array("itemid","name"),
							   "kits"  =>Array("name","items","delay","group"),
							   "groups"=>Array("name","color","commands","inheritedgroups","admin"),
							   "whitelist"=>Array("name"),
							   "reservelist"=>Array("name"),
							   "backups"=>Array("id","name","date","time","size","comment","filename"),
							   "warps"=>Array("name","x","y","z","rotX","rotY","group"));
	var $column_idx    = Array("users.name"=>0,
						  "users.groups"=>1,
						  "users.admin"=>2,
						  "users.color"=>3,
                          "users.commands"=>4,
						  "users.ip"=>5,
						  "users.password"=>6,
						  
						  "items.name"=>0,
						  "items.itemid"=>1,
						  
						  "kits.name"=>0,
						  "kits.items"=>1,
						  "kits.delay"=>2,
						  "kits.group"=>3,
						  
						  "groups.name"=>0,
						  "groups.color"=>1,
						  "groups.commands"=>2,
						  "groups.inheritedgroups"=>3,
						  "groups.admin"=>4,
						  
						  "whitelist.name"=>0,
						  
						  "backups.id"=>0,
						  "backups.name"=>1,
						  "backups.date"=>2,
						  "backups.time"=>3,
						  "backups.size"=>4,
						  "backups.comment"=>5,
                          "backups.filename"=>6,
                          
                          "warps.name"=>0,
						  "warps.x"=>1,
						  "warps.y"=>2,
						  "warps.z"=>3,
				          "warps.rotX"=>4,
						  "warps.rotY"=>5,
						  "warps.group"=>6,
						  
						  "reservelist.name"=>0
						  );
	var $tablefiles    = Array("users"=>"users.txt",
							   "items"=>"items.txt",
							   "kits" =>"kits.txt",
							   "groups"=>"groups.txt",
							   "backups"=>"backups.txt",
							   "reservelist"=>"reservelist.txt",
							   "whitelist"=>"whitelist.txt",
							   "warps"=>"warps.txt");
	function __construct($server,$username,$password,$database){
		$this->folder = $database;
		
		if(substr($this->folder,-1,1)!='\\')
		if(substr($this->folder,-1,1)!='/')$this->folder=$this->folder.'/';
	}
	function isconnected(){
		if(is_dir($this->folder))
			return true;
		return false;
	}
	function escape_string($string){
		$string=str_replace(":","_/@\_",$string);
		$string=str_replace("\r","_/r\_",$string);
		$string=str_replace("\n","_/n\_",$string);
		return $string;
	}
	function unescape_string($string)
	{
		$string=str_replace("_/@\_",":",$string);
		$string=str_replace("_/r\_","\r",$string);
		$string=str_replace("_/n\_","\n",$string);
		return $string;
	}
	function checkall($exists){
		foreach($exists as $one){
			if($one){
			}else{
				return false;
			}
		}
		return true;
	}
	
	
	public static function cmp2($a, $b)
	{
		$sortname=Flatfile::$sortname;
	    if ($a[$sortname] == $b[$sortname]) {
	        return 0;
	    }
	    return ($a[$sortname] < $b[$sortname]) ? -1 : 1;
	}
	public static function cmp1($a, $b)
	{
		$sortname=Flatfile::$sortname;
		if ($a[$sortname] == $b[$sortname]) {
	        return 0;
	    }
	    return ($a[$sortname] < $b[$sortname]) ? 1 : -1;
	}
	function fetch($table,$data_array,$return="",$multi=false,$order_array=""){
		if(!isset($this->tablefiles[$table]))
			die($table. " does not exist!");

		$starttime = time() + microtime();
		$id=0;
		$result=Array();
		$f=fopen($this->folder.$this->tablefiles[$table],"r");
		while($l=fgets($f))
		{
			if($l[0]=='#')continue;
			if(substr($l,-1,1)=="\n")$l=substr($l,0,-1);
			if(substr($l,-1,1)=="\r")$l=substr($l,0,-1);
			$c = split(":",$l);
			$columngroup = Array();
			foreach($this->table_columns[$table] as $columnname)
				$columngroup[$columnname]=$this->unescape_string($c[$this->column_idx[$table.".".$columnname]]);
			$columngroup["id"]=$id++;
			$result[]=$columngroup;
		}
		fclose($f);

		$stoptime = time() + microtime();
		
		$total = round($stoptime - $starttime,4);
		$this->overall_stats += $total;

		
		if(is_array($order_array))
		{
			reset($order_array);
			for($a=0;$a<count($order_array);$a++)
			{	
				Flatfile::$sortname=key($order_array);
				if($order_array[Flatfile::$sortname]!="DESC")
					usort($result,"Flatfile::cmp1");
				else
					usort($result,"Flatfile::cmp2");
				next($order_array);
			}
		}
		
		if($multi)
			return $result;
		else
			if(isset($result[0]))
				return $result[0];
			else
				return Array();
	}
	function fetch_by($table,$data_array,$return,$multi=false,$order_array=""){
		if(!isset($this->tablefiles[$table]))
			die($table. " does not exist!");
		
		$starttime = time() + microtime();
		$result=Array();
		$f=fopen($this->folder.$this->tablefiles[$table],"r");
		$id=0;
		while($l=fgets($f))
		{
			if($l[0]=='#')continue;
			if(substr($l,-1,1)=="\n")$l=substr($l,0,-1);
			if(substr($l,-1,1)=="\r")$l=substr($l,0,-1);
			
			$c = split(":",$l);
			$columngroup = Array();
			foreach($this->table_columns[$table] as $columnname)
				$columngroup[$columnname]=$this->unescape_string($c[$this->column_idx[$table.".".$columnname]]);
			$columngroup["id"]=$id++;
			
			if(is_array($data_array))
			{
				$matches=true;
				reset($data_array);
				for($a=0;$a<count($data_array);$a++)
				{
					if($columngroup[key($data_array)] != $data_array[key($data_array)]){
						$matches=false;
						break;
					}
					next($data_array);
				}
								
				if($matches)$result[]=$columngroup;
			}
			else
				$result[]=$columngroup;
		}
		fclose($f);
		$stoptime = time() + microtime();
		
		if(is_array($order_array))
		{
			reset($order_array);
			for($a=0;$a<count($order_array);$a++)
			{	
				Flatfile::$sortname=key($order_array);
				if($order_array[Flatfile::$sortname]!="DESC")
					usort($result,"Flatfile::cmp1");
				else
					usort($result,"Flatfile::cmp2");
				next($order_array);
			}
		}

		$total = round($stoptime - $starttime,4);
		$this->overall_stats=$this->overall_stats+$total;
		
		if($multi)
			return $result;
		else
			if(isset($result[0]))
				return $result[0];
			else
				return Array();

	}
	function fetch_search($table,$data_array,$search,$return,$multi=false,$order_array){
		$starttime = time() + microtime();
//TODO: implement flatfile fetch_search
		$stoptime = time() + microtime();

		$total = round($stoptime - $starttime,4);
		$this->overall_stats=$this->overall_stats+$total;
	}
	function insert($table,$data_array){
		if(!isset($this->tablefiles[$table]))
			die($table. " does not exist!");
		
		$starttime = time() + microtime();
		$stoptime = time() + microtime();

		$f=fopen($this->folder.$this->tablefiles[$table],"a");
		$columns=Array();
		foreach($this->table_columns[$table] as $c)
			$columns[$this->column_idx[$table.".".$c]]=$this->escape_string($data_array[$c]);

		fwrite($f,implode(":",$columns)."\n");
		fclose($f);

		$total = round($stoptime - $starttime,4);
		$this->overall_stats=$this->overall_stats+$total;
	}
	function set($table,$data_array,$where_array){
		if(!isset($this->tablefiles[$table]))
			die($table. " does not exist!");


		$f=fopen($this->folder.$this->tablefiles[$table],"r");
		$comments="";
		while($l=fgets($f))
		{
			if($l[0]!="#")break;
			$comments.=$l;
		}
		fclose($f);
		
		$starttime = time() + microtime();
		$result=$this->fetch($table,"","",true);
		$f=fopen($this->folder.$this->tablefiles[$table],"w+");
		fwrite($f,$comments);
		fclose($f);
		for($a=0;$a<count($result);$a++)
		{
			$matches=true;
			reset($where_array);
			for($b=0;$b<count($where_array);$b++)
			{
				if($result[$a][key($where_array)] != $where_array[key($where_array)]){
					$matches=false;
					break;
				}
				next($where_array);
			}
			if($matches)
			{
				reset($data_array);
				for($b=0;$b<count($data_array);$b++)
				{
					$result[$a][key($data_array)]=$this->escape_string($data_array[key($data_array)]);
					next($data_array);
				}
			}
			$this->insert($table,$result[$a]);
		}
		
//TODO: better implementation of flatfile set
		$stoptime = time() + microtime();
		$total = round($stoptime - $starttime,4);
		$this->overall_stats=$this->overall_stats+$total;
	}
	function delete($table,$where_array){
		if(!isset($this->tablefiles[$table]))
			die($table. " does not exist!");

		$starttime = time() + microtime();
		$f=fopen($this->folder.$this->tablefiles[$table],"r+");
		$id=0;
		$lastpos=0;
		$newpos=0;
		while($l=fgets($f))
		{
			if($l[0]=='#')
			{
				$lastpos=ftell($f);
				continue;
			}
			$newpos=ftell($f);

			$c = split(":",$l);
			$columngroup = Array();
			foreach($this->table_columns[$table] as $columnname)
				$columngroup[$columnname]=$c[$this->column_idx[$table.".".$columnname]];
			$columngroup["id"]=$id++;
			
			reset($where_array);
			for($a=0;$a<count($where_array);$a++)
			{
				if($columngroup[key($where_array)] == $where_array[key($where_array)]){
					fseek($f,$lastpos,SEEK_SET);
					fwrite($f,'#DEL ');
					fseek($f,$newpos,SEEK_SET);
				}
				
				next($where_array);
			}
			
			$lastpos=ftell($f);
		}
		fclose($f);		
		$stoptime = time() + microtime();

		$total = round($stoptime - $starttime,4);
		$this->overall_stats=$this->overall_stats+$total;
		return 1;
	}
	function check_exists($table,$where_array,$multi=false){
		$starttime = time() + microtime();
//TODO: implement flatfile check_exists
		$stoptime = time() + microtime();
		$total = round($stoptime - $starttime,4);
		$this->overall_stats=$this->overall_stats+$total;
	}
	function check_exists_i($table,$where_array){
		$starttime = time() + microtime();
//TODO: implement flatfile check_exists_i
		$stoptime = time() + microtime();

		$total = round($stoptime - $starttime,4);
		$this->overall_stats=$this->overall_stats+$total;
	}
}
?>