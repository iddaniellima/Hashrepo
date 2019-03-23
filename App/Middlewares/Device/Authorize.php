<?php

namespace App\Middlewares\Device;
use App\Views\Status as Status;
use App\Classes\RequestData as RequestData;

class Authorize{
  private $req;
  
  public function init(){
    $this->req = RequestData::$req;
    
    if(!$this->VerifyRequest()[0]){
      Status::Error($this->VerifyRequest()[1], 400);
    }
  }
  
  private function VerifyRequest(){
    if(!isset($this->req['data'])){
      return array(false, "Data for activation was not sent.");
    } 
    
    else if(!isset($this->req['data']['code'])){
      return array(false, "The activation code was not received.");
    } 
    
    else if(!isset($this->req['data']['password'])){
      return array(false, "User password not received.");
    } 
    
    else if(!isset($this->req['data']['deviceID'])){
      return array(false, "Device ID not received..");
    }
    
    else {
      return array(true);
    }
  }
}