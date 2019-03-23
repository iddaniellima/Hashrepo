<?php

namespace App\Middlewares;
use App\Views\Status as Status;
use App\Classes\SecurityHelper as SecurityHelper;
use App\Classes\OperationLogs as OperationLogs;
use App\Classes\RequestData as RequestData;

class RequestCheck{
  public $ReceivedRequestData;
  
  public function Init(){
    if(!isset(app('request')->headers['request'])){
      Status::Error("Header request is required.", 400);
    } else if(!SecurityHelper::decode(app('request')->headers['request'])){
      Status::Error("The header request was denied.", 403);
    } else{
      $this->ReceivedRequestData = json_decode(SecurityHelper::decode(app('request')->headers['request']), true);
      
      if($this->ReceivedRequestData == NULL){
        Status::Error("The header request was denied.", 403);
      } else{
        return $this->CheckRequestClientID();
      }
    }
  }
  
  private function CheckRequestClientID(){
    if(!isset($this->ReceivedRequestData['ClientID'])){
      Status::Error("Client ID is required.", 400);
    } else if(!isset(SYSTEM_CLIENTS[$this->ReceivedRequestData['ClientID']])){
      Status::Error("Client ID Not Found", 403);
    } else{
      RequestData::save($this->ReceivedRequestData);
    }
  }
  
}