<?php
# AUTHOR      : ELIZAR M. FLORES
# CLASS NAME  : FORM
# DESCRIPTION : AUTO FORM VALIDATION CLASS AND ERROR REPORT GENERATOR
# INITIALIZE  : INCLUDE THE CLASS IN YOUR FILES AND MAKE AN INSTANCE OF THE CLASS
# PARAMETERS  : THE CLASS REQUIRES AN ARRAY OF YOUR FORM OBJECTS, SPECIFYING THE TYPES OF VALIDATION TO CHECK
#               THE MAIN INDEX ON THE ARRAY ARE THE OBJECT NAMES, THEIR VALUES ARE AN ARRAY OF THEIR SPECIFIC TYPES OF VALIDATION
#               EXAMPLE: 
#               
#                    array('txtName'=>array('REQUIRED'));
#                    
#                    WHERE: 
#                           'txtName'         = The name of the object on the form (or on the script)
#                           array('REQUIRED') = Is the type of validation it will check on this object 
#                 
#               THE FOLLOWING ARE EXAMPLES OF KEYS AND ACCEPTED FORMATS:
#                  
#                   'ALIAS'    : Will append the value of this to the error message. Must have a value  
#                               EG:
#                               array('txtName'=>array('ALIAS'=>'Name','REQUIRED'));                       
#                                    - will append the value to the error message, 'Name is required'
#                                    
#                               array('txtName'=>array('ALIAS'=>'Name','REQUIRED'=>'must have a value'));
#                                    - will produce an error of 'Name must have a value'
#                     
#                   'REQUIRED' : If the object requires a value. Assign a string to customize the error message. Value is optional
#                               EG: 
#                               array('txtName'=>array('REQUIRED')); 
#                                    -  will check for this type of error and produce default error message
#                               
#                               array('txtName'=>)array('REQUIRED'=>'must have a value');  
#                                    - will check for this type of error and say 'txtName must have a value'
#                                   
#                   'NUMERIC'  : Checks if the object is numeric
#                               EG: 
#                               array('txtPhone'=>array('NUMERIC'));
#                                    - will produce the error 'txtPhone should be a number'
#                               array('txtPhone'=>array('ALIAS'=>'Phone','NUMERIC'=>'is invalid'))
#                                    - will produce the error 'Phone is invalid';
#                                    
#                   'COMPARE'  : Compares an object to another object in terms of value
#                               EG:
#                               array('txtPassword1'=>array('COMPARE'=>array('WITH'=>'txtPassword2')));
#                                    - will produce the error 'txtPassword1 and txtPassword2 must be the same'
#                               array('txtPassword1'=>array('COMPARE'=>array('WITH'=>'txtPassword2','ERROR'=>'should be equal')));
#                                    - will produce the error 'txtPassword1 and txtPassword2 should be equal' 
#                                    
#                   'EMAIL'    : Checks for valid emal
#                               EG: 
#                               array('txtEmail'=>array('EMAIL'));                                                                                                                                                                                                                                
#                               array('txtEmail'=>array('EMAIL'=>'is not accepted'));
#                               
#                   'LENGTH'   : Checks the length of the object
#                              EG:
#                                 OPTIONS : 
#                                           EQUAL = Checks to see if the value is equal to the specified length
#                                           array('txtUsername'=>array('ALIAS'=>'Username','REQUIRED','LENGTH'=>array('EQUAL'=>5)))
#                                               - checks if the value of txtUsername is equal to 5        
#                                               - you can add a second param to the LENGTH index to customize the error text
#                                           array('txtUsername'=>array('ALIAS'=>'Username','LENGTH'=>array('EQUAL'=>5,'ERROR'=>'Limit is 5')))      
#
#                                           GREAT  = Checks to see if the value is greater than the specified length
#                                           array('txtUsername'=>array('ALIAS'=>'Username','REQUIRED','LENGTH'=>array('GREAT'=>5)))
#                                           
#                                           GREAT_E = Checks to see if the value is greater than or equal to the value specified
#                                           array('txtUsername'=>array('ALIAS'=>'Username','REQUIRED','LENGTH'=>array('EQUAL_E'=>5))) 
#
#
#                                           LESS = Checks to see if the value is less than the value specified
#                                           array('txtUsername'=>array('ALIAS'=>'Username','REQUIRED','LENGTH'=>array('LESS'=>5)))
#                                           
#                                           LESS_E = Checks to see if the value is less than or equal to the value specified
#                                           array('txtUsername'=>array('ALIAS'=>'Username','REQUIRED','LENGTH'=>array('LESS_E'=>5)))
#
#

class FORM
{
    # HOLDS THE RAW FIELDS PASSED BY THE USER
    private $_fields         = array();
    
    # HOLDS THE FIELDS AFTER THEY HAVE BEEN READ AND PROCESSED
    private $_ready_fields   = array();
    
    # HOLDS THE VALUE OF THE PASSED VARIABLE
    private $_object  = array(); 
    
    # HOLDS THE RESULT OF THE ERRORS FILTERED ACCORDING TO THE RULES PASSED
    private $_results = array();
    
    # TYPE OF VARIABLE TO READ AND VALIDATE (eg: POST, GET, SESSION)
    private $_type;
    
    # THE DEFAULT STYLING FOR THE ERROR MESSAGES
    private $_raw_style = array('list-style-type'=>'none','text-indent'=>'-2em','color'=>'#F00');
            
   # STYLE THAT WAS PROCESSED
    private $_style;
            
    
    # ERROR DEFAULT MESSAGES UNLESS DEFINED AND PASSED OTHERWISE BY THE USER
    private $_error = array('REQUIRED'=>'is required',
                            'NUMERIC'=>'must be a number',
                            'COMPARE'=>'must be the same',
                            'EMAIL'=>'is not valid',
                            'LENGTH'=>array('EQUAL'=>'must be equal to',
                                            'GREAT'=>'must be greater than',
                                            'GREAT_E'=>'must be greater than or equal to',
                                            'LESS'=>'must be less than',
                                            'LESS_E'=>'must be less than or equal to'
                                            )
                            );
    
    
   function __construct($fields=array(),$style=false,$type="POST"){
        if(!empty($fields))
          {
            $this->_fields = $fields; 
            $this->_type   = strtoupper($type);
            
            $this->_raw_style  = !$style || !is_array($style) ? $this->_raw_style : $style; 
            $this->PREPARE_STYLE();
          }
      }
   
   private function PREPARE_STYLE()
     {
       foreach($this->_raw_style as $key => $style)
           {
              $this->_style .= $key .':'.$style.';'; 
           }
           $this->_style .='list-style-type:none;text-indent:-2em';
     }   
      
   # THIS FUNCTION WILL READ THE OBJECT VARIABLES AND ASSIGN THEIR VALUES ON THE _OBJECT VARIABLE   
   private function PREPARE_OBJECT_VARIABLE_METHOD()
     {
       if($this->_type === 'POST')
           {
              foreach($this->_fields as $key=>$field)
                  {
                    $this->_object[$key]['VALUE'] = isset($_POST[$key])?$_POST[$key]:false;
                  }         
           }  
       else if($this->_type === 'GET')
           {
               foreach($this->_fields as $key=>$field)
                  {
                    $this->_object[$key]['VALUE'] = isset($_GET[$key])?$_GET[$key]:false;
                  }  
           }    
     }   
   
   # THIS FUNCTION WILL READ EACH FIELDS ASSOCIATED WITH THE OBJECT   
   private function READ_FIELDS()
    {
      foreach($this->_fields as $key => $attributes)
          {
            foreach($attributes as $attrib_key => $attribute)
                {
                      if(is_numeric($attrib_key))
                          {
                          $this->_ready_fields[$key][$attribute] = true;
                          }
                       else
                          {
                              $this->_ready_fields[$key][$attrib_key] = $attribute;    
                          }                    
                }
          }   
    }   
    
    private function checkEmail($email) {
        $flag = true;
               if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                   $flag = false;
                }

        $regexp = "/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/";
                if(!preg_match($regexp, $email)){
                    $flag = false;
                }        
         return $flag;       
	}
                  
    
        
   # THIS FUNCTION WILL PROCESS THE ERROR MESSAGES IF ANY 
    private function PREPARE_VALIDATION_RESULTS()
    {
      $this->PREPARE_OBJECT_VARIABLE_METHOD(); 
      $this->READ_FIELDS();
      
      foreach($this->_ready_fields as $key => $fields)
          {
            # SET THE ALIAS FOR EACH FIELD
            $this->_object[$key]['ALIAS']    = (isset($fields['ALIAS']))   ? $fields['ALIAS'] : $key; 
            
             # IF VALUE IS NOT NULL
            if(isset($fields['REQUIRED']) && $this->_object[$key]['VALUE']===''){
               $this->_results[$key]['REQUIRED'] = (strlen($fields['REQUIRED']) > 1 ) ? $fields['REQUIRED'] : $this->_object[$key]['ALIAS'].' '.$this->_error['REQUIRED'];
            }
                    
            # IF VALUE IS NOT NUMERIC
            if( isset($fields['NUMERIC']) && !is_numeric($this->_object[$key]['VALUE']) ){
              $this->_results[$key]['NUMERIC']  =  (strlen($fields['NUMERIC'])>1  ? $fields['NUMERIC'] :$this->_object[$key]['ALIAS'].' '.$this->_error['NUMERIC']);
            }
            
            # IF VALUE IS A VALID EMAIL
            if(isset($fields['EMAIL']) && !$this->checkEmail($this->_object[$key]['VALUE'])){
                $this->_results[$key]['EMAIL']  =  (strlen($fields['EMAIL'])>1  ? $fields['EMAIL'] :$this->_object[$key]['ALIAS'].' '.$this->_error['EMAIL']);
            }
                
            # IF VALUE HAS A SPECIFIC LENGTH
            if(isset($fields['LENGTH'])){
               
                if(array_key_exists('EQUAL', $fields['LENGTH']) && !(($fields['LENGTH']['EQUAL']) == strlen($this->_object[$key]['VALUE'])))
                {
                    $this->_results[$key]['LENGTH'] =isset($fields['LENGTH']['ERROR'])? $fields['LENGTH']['ERROR']:$this->_object[$key]['ALIAS'].' '.$this->_error['LENGTH']['EQUAL'].' '.$fields['LENGTH']['EQUAL'].' characters';
                }
                # GREATER THAN 
                else if(array_key_exists('GREAT', $fields['LENGTH']) && !(strlen($this->_object[$key]['VALUE']) > ($fields['LENGTH']['GREAT'])))
                {
                     $this->_results[$key]['LENGTH'] =isset($fields['LENGTH']['ERROR'])? $fields['LENGTH']['ERROR'] :$this->_object[$key]['ALIAS'].' '.$this->_error['LENGTH']['GREAT'].' '.$fields['LENGTH']['GREAT'].' characters';
                }
                # GREATER THAN EQUAL TO 
                else if(array_key_exists('GREAT_E', $fields['LENGTH']) && !(strlen($this->_object[$key]['VALUE'])>=($fields['LENGTH']['GREAT_E'])))
                {
                     $this->_results[$key]['LENGTH'] =isset($fields['LENGTH']['ERROR'])? $fields['LENGTH']['ERROR'] :$this->_object[$key]['ALIAS'].' '.$this->_error['LENGTH']['GREAT_E'].' '.$fields['LENGTH']['GREAT_E'].' characters';
                }
                # LESS THAN 
                else if(array_key_exists('LESS', $fields['LENGTH']) && !(strlen($this->_object[$key]['VALUE'])<($fields['LENGTH']['LESS'])))
                {
                     $this->_results[$key]['LENGTH'] =isset($fields['LENGTH']['ERROR'])? $fields['LENGTH']['ERROR'] :$this->_object[$key]['ALIAS'].' '.$this->_error['LENGTH']['LESS'].' '.$fields['LENGTH']['LESS'].' characters';
                }
                # LESS THAN EQUAL TO 
                else if(array_key_exists('LESS_E', $fields['LENGTH']) && !(strlen($this->_object[$key]['VALUE'])<=($fields['LENGTH']['LESS_E'])))
                {
                     $this->_results[$key]['LENGTH'] =isset($fields['LENGTH']['ERROR'])? $fields['LENGTH']['ERROR'] :$this->_object[$key]['ALIAS'].' '.$this->_error['LENGTH']['LESS_E'].' '.$fields['LENGTH']['LESS_E'].' characters';
                }
             }
             
            # IF A FIELD IS EQUAL TO ANOTHER FIELD 
             if(isset($fields['COMPARE'])){
             
                 if(is_array($fields['COMPARE']))
                     {
                      if($this->_object[$key]['VALUE']!==$this->_object[$fields['COMPARE']['WITH']]['VALUE']){ 
                           $this->_results[$key]['COMPARE'] = isset($fields['COMPARE']['ERROR']) ? $fields['COMPARE']['ERROR'] : $this->_object[$key]['ALIAS'] .' and '. $this->_ready_fields[$fields['COMPARE']['WITH']]['ALIAS'].' '. $this->_error['COMPARE'];
                          }
                      } 
                  else
                     {
                      if($this->_object[$key]['VALUE']!==$this->_object[$fields['COMPARE']]['VALUE']){
                           $this->_results[$key]['COMPARE'] = $this->_object[$key]['ALIAS'] .' and '. $this->_ready_fields[$fields['COMPARE']]['ALIAS'].' '. $this->_error['COMPARE'];
                          }
                     }
             }
             
          }      
    }
    ########################### PUBLIC FUNCTIONS STARTS HERE #############################################################################
    function validate()
    {
        $this->PREPARE_VALIDATION_RESULTS();
        
        if(!empty($this->_results))
            {
                $retVal='<ul style="'.$this->_style.'">';
                foreach($this->_results as $errors){
                   foreach($errors as $error){
                         $retVal.='<li>'.$error.'</li>';
                       }
                }
                $retVal.='</ul>';
            }
         else{ 
             $retVal = false; 
         }
         return $retVal;
    }
    
    function sanitize($object)
    {
        $returnValue = strip_tags(trim($object));
        return $returnValue;
    }
    
    function fetch_error($objectName,$validationName)
    {
       $this->PREPARE_VALIDATION_RESULTS();
       $returnValue = isset($this->_results[$objectName][$validationName])?$this->_results[$objectName][$validationName]:false;
       return $returnValue;
    }
    
    function fetchAll_error()
    {
        return $this->_results;
    }
}
?>