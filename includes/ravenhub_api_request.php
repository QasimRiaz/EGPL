<?php
class Revenhubapi {
    
    
    
    
    
    public function __construct() {
    
        
        
    }
    
    
    public function sendnotifaciton($tempalte_url,$template_parameters){
        
        try {
            
                                                                                        

            $ch = curl_init($tempalte_url);                                                                      
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $template_parameters);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($template_parameters))                                                                       
            );                                                                                                                   

            $result = curl_exec($ch);
            
            return $result;
            
        } catch (Exception $e) {
       
         return $e;
         
        }
    
        die();
        
        
    }
    
   

}