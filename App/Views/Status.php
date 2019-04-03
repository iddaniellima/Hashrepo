<?php

namespace App\Views;

class Status{
  public static function render_error($http_code, $class, $message){
    http_response_code($http_code);
    exit('{ "status": "error", "class": "'.$class.'", "message": "'.$message.'"}');
  }
  
  public static function SuccesAddDevice($msg){
    http_response_code(201);
    exit('{ "status": "success", "message": "'.$msg.'"}');
  }
  
  public static function SuccesAuthorizeDevice($token){
    http_response_code(201);
    exit('{ "status": "success", "token": "'.$token.'" }');
  }
}