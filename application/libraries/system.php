<?php
class SYSTEM
{
    
    
    public function container($data,$view_type='ECHO'){
    
          $this->CI =& get_instance();
          echo $this->CI->load->view('system/header',NULL, TRUE);
          echo $this->CI->load->view('system/title',NULL, TRUE);
          echo $this->CI->load->view('system/navigation',NULL, TRUE);    
          echo $this->CI->load->view('system/main_container',NULL, TRUE);
         
          #  LOAD THE TYPE OF VIEW YOU WANT TO SHOW THE DATA
          if(is_array($data)){
            switch(strtoupper($view_type))
            {
                case 'PRINT_R'  : print_r($data); break;
                case 'PRINT_R!' : echo'<pre>'; print_r($data); echo '</pre>'; break;
                default         : print_r($data);     break;
            }    
          }
          else{
               echo $data; 
          }
            
          echo $this->CI->load->view('system/main_container_close',NULL, TRUE);
          echo  $this->CI->load->view('system/footer',NULL, TRUE);
    } 
}
?>
