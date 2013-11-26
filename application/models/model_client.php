<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Model_client extends CI_Model{
    
    public function get_all_clients(){
        $clause = 'SELECT * FROM Client LIMIT 0, 10';
        $query  = new QUERY();
        $result = $query->run($clause)->fetchAll();
        
        return $result;
    }
    
    public function add_client($data){
        $query  = new QUERY(array('TABLE'=>'client'));
        $result = $query->save($data);
        
        return $result;
    }
    
}

?>