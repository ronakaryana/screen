<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_agent extends CI_Model{
	
	public function get_active_agents()
	{
	   $customer = new QUERY(array('TABLE'=>'agent','KEY'=>array('status'=>'ACTIVE'),'LIMIT'=>'0,10'));
	   $result   = $customer->fetchAll();
	   return $result; 
	}
}