<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Client extends CI_Controller {

	public function index(){
            $this->create();
        }
        
        public function listing(){
            $system = new SYSTEM();
            $data = $this->get_client();
            $system->container($data);
        }
        
        
        public function all()
        {
               
        }
        
        
        public function create(){
            
            $data = $this->load->view('client/create_client');
            
            $system = new SYSTEM();
            $system->container($data);
        }
        
       public function validate(){
           
           
         $validate = array('txtName' => array('ALIAS'=>'Business Name','REQUIRED'),
                           'txtOwner'=> array('ALIAS'=>'Business Owner','REQUIRED'),
                           'txtAddress' => array('ALIAS'=>'Address', 'REQUIRED'),
                           'txtPhone' => array('ALIAS'=>'Phone','REQUIRED','NUMERIC'),
                           'txtEmail' => array('ALIAS'=>'Email','REQUIRED','EMAIL')
                           );
         
         $form   = new FORM($validate);
         
         $errors = $form->fetchAll_error();
         
         if(empty($errors))
             {
               $data_input = array('name'=>$_POST['txtName'],
                             'owner'=>$_POST['txtOwner'],
                             'address'=>$_POST['txtAddress'],
                             'phone'=>$_POST['txtPhone'],
                             'email'=>$_POST['txtEmail'],
                             'type'=>'GENERAL',
                             'photo'=>'default_business_photo.jpg',
                             'date_added'=>date('Y-m-d H:i:s'));
              $this->add_client($data_input);
              $data = $this->load->view('client/create_client');
             }
          else
             {
              $data = $this->load->view('client/create_client',$errors);
             }
             
             #SHOW THE PAGE
              $system = new SYSTEM();
              $system->container($data);
             
       }
       
        private function add_client($data){
            $this->load->model('model_client');
            $result = $this->model_client->add_client($data);
            echo $result ? 'ADDED':'ERROR'; 
        }
        
        private function get_client(){
            $this->load->model('model_client');
            $result = $this->model_client->get_all_clients();
            return $result;
        }
        
       
        
        
        
               
        
}