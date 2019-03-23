<?php

namespace App\Views;

class Status{
  public static function Error($MSG, $HTTPCode){
    http_response_code($HTTPCode);
    exit('{ "status": "error", "message": "'.$MSG.'"}');
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