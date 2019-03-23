<?php

namespace App\Views\Mail\Device\Add;

class Add{
  private $name;
  private $code;
  
  function __construct($name, $code){
    $this->name = $name;
    $this->code = $code;
  }
  
  public function get_mounted_template(){
    return $this->load_template();
  }
  
  private function load_template(){
    $template = file_get_contents(BASE_PATH.'App/Views/Mail/Device/Add/template.html');
    
    $template = str_replace('%%name%%', $this->name, $template); 
    $template = str_replace('%%code%%', $this->code, $template); 
    
    return $template;
  }
}