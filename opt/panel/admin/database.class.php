<?php
abstract class database {
	abstract protected function __construct($server,$username,$password,$database);
	abstract protected function isconnected();
	abstract protected function escape_string($string);
	abstract protected function checkall($exists);

	abstract protected function fetch($table,$data_array,$return="",$multi=false,$order_array="");
	abstract protected function fetch_by($table,$data_array,$return,$multi=false,$order_array="");
	abstract protected function fetch_search($table,$data_array,$search,$return,$multi=false,$order_array="");
	
	abstract protected function insert($table,$data_array);
	abstract protected function set($table,$data_array,$where_array);
	abstract protected function delete($table,$where_array);
	
	abstract protected function check_exists($table,$where_array,$multi=false);
	abstract protected function check_exists_i($table,$where_array);
}
?>