<?php

namespace App\Middlewares;
use App\Views\Status as Status;
use App\Classes\SecurityHelper as SecurityHelper;
use App\Classes\OperationLogs as OperationLogs;
use App\Classes\RequestData as RequestData;

class RequestCheck{
  public $ReceivedRequestData;
  public $data;
  
  public function init(){
    if(!isset(app('request')->headers['request'])){
      Status::Error("Header request is required.", 400);
    } else {
      $decode = json_decode(app('request')->headers['request'], true);
      
      if($decode == NULL){
        Status::Error("The header request was denied.", 403);
      } else{
        $this->data = $decode;
        $this->CheckClientID();
      }
    }
  }
  
  private function CheckClientID(){
    if(!$this->data['ClientID']){
      Status::Error("Client ID is required.", 400);
    } else if(!SecurityHelper::decode($this->data['ClientID'])) {
      Status::Error("Non-readable request.", 403);
    } else if(!isset(SYSTEM_CLIENTS[SecurityHelper::decode($this->data['ClientID'])])){
      Status::Error("Invalid Client ID.", 403);
    } else{
      $this->data['ClientID'] = SecurityHelper::decode($this->data['ClientID']);
      RequestData::save($this->data);
    }
  }
 
  
}