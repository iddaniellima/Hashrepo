<?php

namespace App\Middlewares\Device;
use App\Classes\RequestData as RequestData;
use App\Views\Status as Status;

class Add{
  public function Init(){
    $this->req = RequestData::$req;
    
    if(!$this->VerifyRequest()[0]){
      Status::Error($this->VerifyRequest()[1], 400);
    }
  }
  
  private function VerifyRequest(){
    if(!isset($this->req['credentials'])){
      return array(false, "User credentials were not sent.");
    } 
    
    else if(!isset($this->req['credentials']['identifier'])){
      return array(false, "The user identifier was not submitted.");
    } 
    
    else if(!isset($this->req['credentials']['password'])){
      return array(false, "The user's password was not sent.");
    } 
    
    else if(!isset($this->req['deviceData'])){
      return array(false, "Device information was not sent.");
    } 
    
    else if(!isset($this->req['deviceData']['deviceID'])){
      return array(false, "Device ID was not sent.");
    } 
    
    else if(!isset($this->req['deviceData']['deviceName'])){
      return array(false, "Device name was not sent.");
    } 
    
    else if(!isset($this->req['deviceData']['deviceModel'])){
      return array(false, "Device model was not sent.");
    } 
    
    else if(!isset($this->req['deviceData']['deviceSystem'])){
      return array(false, "Device system was not sent.");
    } 
    
    else{
      return array(true);
    }
  }
}