<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agent extends CI_Controller {
    
    
	public function index()
        {
            $this->dashboard();
        }
        
        public function dashboard()
        {
          echo $this->load->view('system/header',NULL, TRUE);
          echo $this->load->view('system/title',NULL, TRUE);
          echo $this->load->view('system/navigation',NULL, TRUE);
        
           
          echo $this->load->view('system/main_container',NULL, TRUE);
          
          echo '<pre>';
          print_r($this->get_active_agents());
          echo'</pre>';
          
          echo $this->load->view('system/main_container_close',NULL, TRUE);
        
                    
           
          echo  $this->load->view('system/footer',NULL, TRUE);
        }
        
        private function get_active_agents()
        {
           $this->load->model('model_agent');
           $active = $this->model_agent->get_active_agents();
         
           
           
           return $active;
       
        }

}