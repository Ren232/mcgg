<?PHP
class JsonRPCChild
{
    public $parent = null; 
    protected $name = ""; 
    
    public function __construct($parent, $name) 
    { 
        $this->parent = $parent; 
        $this->name = $name; 
    } 
    public function __call($name, $arguments) 
    { 
        return $this->parent->__call($this->name.".".$name, $arguments); 
    } 
    public function __get($name) 
    { 
        return new JsonRPCChild($this, $name); 
    } 
    
}
class JsonRPC extends JsonRPCChild
{ 
    private $server; 
    private $port;
	private $user;
	private $password;
	private $debug;
	public  $lasterror;
	public	$salt;
    public function __construct($server,$port=20059,$user="",$password="", $salt="",$debug=true)
    {
		$this->server=$server;
		$this->port=$port;
		$this->user=$user;
		$this->password=$password;
		$this->debug=$debug;
		$this->salt=$salt;
    } 
    
    public function __destruct() 
    { 
    } 
    
    public function __call($name, $arguments) 
    { 
		error_reporting(E_ERROR | E_PARSE);
//(12-6-2010)Emirin: Added socket check to json parse to speed up the load of the page if there is no server available.
		if($conn=fsockopen($this->server, $this->port, $errno, $errstr, 1))
		{
			$url="http://".$this->server.":".$this->port."/api/call?method=".$name."&key=" . hash('sha256', $this->user.$name.$this->password.$this->salt);
			$request = "args=".urlencode(json_encode($arguments));
			
			$headers = array('Connection: close');
			$header = (version_compare(phpversion(), '5.2.8')) > 0 
				? $headers : implode("\r\n", $headers); 

			$context = stream_context_create(array('http' => array( 
				'method'     => "POST", 
				'header'     => $header, 
				'content'    => $request, 
				'user_agent' => 'JsonRPC',
				'timeout'    => 5,
			)));
			$file = @file_get_contents($url, false, $context); 
			if ($file === false) {
				$error           = error_get_last();
				$this->lasterror = $error["message"]; 
				return null;
			} else {
				$j=json_decode(utf8_encode($file),true);
				
				if($j["result"]=="success")
				{
					return $j["success"];
				}
				else
				{
					$this->lasterror=$r->error;
					if($this->debug)die($this->lasterror);
					return null;
				}
			} 
			fclose($conn);
		}
		else{
		}
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
    } 
} 
?>