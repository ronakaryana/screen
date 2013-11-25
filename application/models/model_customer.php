<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_customer extends CI_Model{
	

	public function get_all_registration()
	{
	   $this->load->library('QUERY');	
	   $customer = new QUERY(array('TABLE'=>'registration','KEY'=>array('checked'=>'N'),'LIMIT'=>'0,10'));
	   $result   = $customer->fetchAll();
	   return $result; 
	}
}