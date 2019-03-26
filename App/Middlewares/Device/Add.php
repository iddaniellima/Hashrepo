<?php

namespace App\Middlewares\Device;
use App\Classes\RequestData as RequestData;
use App\Views\Status as Status;
use App\Classes\SecurityHelper as SecurityHelper;

class Add{
  public function Init(){
    $this->req = RequestData::$req;
    
    if(!$this->VerifyRequestParams()[0]){
      Status::Error($this->VerifyRequestParams()[1], 400);
    } else if(!$this->DecryptData()[0]){
      Status::Error($this->DecryptData()[1], 403);
    } else {
      $this->req['credentials']['identifier'] = SecurityHelper::decode($this->req['credentials']['identifier']);
      $this->req['credentials']['password'] = SecurityHelper::decode($this->req['credentials']['password']);
      $this->req['deviceData']['deviceID'] = SecurityHelper::decode($this->req['deviceData']['deviceID']);
      $this->req['deviceData']['deviceName'] = SecurityHelper::decode($this->req['deviceData']['deviceName']);
      $this->req['deviceData']['deviceModel'] = SecurityHelper::decode($this->req['deviceData']['deviceModel']);
      $this->req['deviceData']['deviceSystem'] = SecurityHelper::decode($this->req['deviceData']['deviceSystem']);
      
      RequestData::save($this->req);
    }
  }

  
  private function DecryptData(){
    if(!SecurityHelper::decode($this->req['credentials']['identifier'])){
      return array(false, "Illegible identifier.");
    } else if(!SecurityHelper::decode($this->req['credentials']['password'])){
      return array(false, "Illegible password.");
    } else if(!SecurityHelper::decode($this->req['deviceData']['deviceID'])){
      return array(false, "Illegible device id.");
    } else if(!SecurityHelper::decode($this->req['deviceData']['deviceName'])){
      return array(false, "Illegible device name.");
    } else if(!SecurityHelper::decode($this->req['deviceData']['deviceModel'])){
      return array(false, "Illegible device model.");
    } else if(!SecurityHelper::decode($this->req['deviceData']['deviceSystem'])){
      return array(false, "Illegible device system..");
    } else{
      return array(true);
    }
  }
  
  private function VerifyRequestParams(){
    if(!isset($this->req['credentials'])){
      return array(false, "User credentials were not sent.");
    } else if(!isset($this->req['credentials']['identifier'])){
      return array(false, "The user identifier was not submitted.");
    } else if(!isset($this->req['credentials']['password'])){
      return array(false, "The user's password was not sent.");
    } else if(!isset($this->req['deviceData'])){
      return array(false, "Device information was not sent.");
    } else if(!isset($this->req['deviceData']['deviceID'])){
      return array(false, "Device ID was not sent.");
    } else if(!isset($this->req['deviceData']['deviceName'])){
      return array(false, "Device name was not sent.");
    } else if(!isset($this->req['deviceData']['deviceModel'])){
      return array(false, "Device model was not sent.");
    } else if(!isset($this->req['deviceData']['deviceSystem'])){
      return array(false, "Device system was not sent.");
    } else{
      return array(true);
    }
  }
}