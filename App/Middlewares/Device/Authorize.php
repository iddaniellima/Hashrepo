<?php

namespace App\Middlewares\Device;
use App\Views\Status as Status;
use App\Classes\RequestData as RequestData;
use App\Classes\SecurityHelper as SecurityHelper;

class Authorize{
  private $req;
  
  public function init(){
    $this->req = RequestData::$req;
    
    if(!$this->VerifyRequest()[0]){
      Status::Error($this->VerifyRequest()[1], 400);
    } else if(!$this->DecryptData()[0]){
      Status::Error($this->DecryptData()[1], 403);
    } else{
      $this->req['data']['code'] = SecurityHelper::decode($this->req['data']['code']);
      $this->req['data']['password'] = SecurityHelper::decode($this->req['data']['password']);
      $this->req['data']['deviceID'] = SecurityHelper::decode($this->req['data']['deviceID']);
      
      RequestData::save($this->req);
    }
  }
  
  private function DecryptData(){
    if(!SecurityHelper::decode($this->req['data']['code'])){
      return array(false, "Unreadable code.");
    } 
    
    else if(!SecurityHelper::decode($this->req['data']['password'])){
      return array(false, "Password unreadable.");
    } 
    
    else if(!SecurityHelper::decode($this->req['data']['deviceID'])){
      return array(false, "Device id unreadable.");
    }
    
    else {
      return array(true);
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