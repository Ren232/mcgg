<?PHP
class PrettyXMLRPCFault 
{ 
    const ConnectionError = 8002; 
    
    public $code = 0; 
    public $string = " "; 
    
    public function __construct($code, $string) 
    { 
        $this->code = $code; 
        $this->string = $string; 
    } 
    
    public function __toString() 
    { 
        return "XML-RPC Fault<".$this->code.": ".$this->string.">"; 
    } 
} 

class PrettyXMLRPCCrumb 
{ 
    public $parent = null; 
    protected $name = ""; 
    
    public function __construct($parent, $name) 
    { 
        $this->parent = $parent; 
        $this->name = $name; 
    } 
    
    public function __get($name) 
    { 
        return new PrettyXMLRPCCrumb($this, $name); 
    } 
    
    public function __call($name, $arguments) 
    { 
        return $this->parent->__call($this->name.".".$name, $arguments); 
    } 
} 

class PrettyXMLRPC extends PrettyXMLRPCCrumb 
{ 
    public $url = ""; 
    
    public function __construct($url, $backend) { 
        $this->url = $url; 
        $this->backend = $backend; 
        $this->backend->setUrl($url); 
    } 
    
    public function __call($name, $arguments) { 
        return $this->backend->call($name, $arguments); 
    } 
} 

class PrettyXMLRPCEpiBackend 
{ 
    private $server; 
    private $url; 
    private $user; 
    private $password; 
    
    public function __construct() 
    { 
        $this->server = xmlrpc_server_create(); 
    } 
    
    public function __destruct() 
    { 
        xmlrpc_server_destroy($this->server); 
    } 
    
    public function setUrl($url) 
    { 
        $url = parse_url($url); 
        $scheme = array_key_exists("scheme", $url) ? $url["scheme"] : "http"; 
        $this->user = array_key_exists("user", $url) ? $url["user"] : ""; 
        $this->password = array_key_exists("pass", $url) ? $url["pass"] : ""; 
        $port = array_key_exists("port", $url) ? $url["port"] 
                                    : ($this->scheme == 'https'?443:80); 
        
        $this->url = $scheme."://".$url["host"].":".$port 
            .(array_key_exists("path", $url) ? $url["path"] : "") 
            .(array_key_exists("query", $url) ? '?'.$url["query"] : "") 
            .(array_key_exists("fragment", $url) ? "#".$url["fragment"] : ""); 
    } 
    
    public function call($name, $arguments) 
    { 
        $request = xmlrpc_encode_request($name, $arguments); 
        $headers = array("Content-Type: text/xml"); 
        if ($this->user) { 
            array_push($headers, "Authorization: Basic " 
                    .base64_encode($this->user.":".$this->password)); 
        } 
        
        $header = (version_compare(phpversion(), '5.2.8')) > 0 
            ? $headers : implode("\r\n", $headers); 
            
        // allow_self_signed is for my own use. remove it for more safety 
        $context = stream_context_create(array('http' => array( 
            'method' => "POST", 
            'header' => $header, 
            'content' => $request, 
            'user_agent' => 'PrettyXMLRPC', 
            'allow_self_signed' => true 
        ))); 
        $file = @file_get_contents($this->url, false, $context); 
        if ($file === false) { 
            $le = error_get_last(); 
            return new PrettyXMLRPCFault(PrettyXMLRPCFault::ConnectionError, $le["message"]); 
        } else { 
            $response = xmlrpc_decode($file); 
            if (is_array($response) && xmlrpc_is_fault($response)) { 
                return new PrettyXMLRPCFault($response["faultCode"], $response["faultString"]); 
            } else { 
                return $response; 
            } 
        } 
    } 
} 
?>