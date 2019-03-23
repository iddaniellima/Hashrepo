<?php

namespace App\Controllers\Device;
use App\Views\Status as Status;
use App\Classes\RequestData as RequestData;
use App\Classes\OperationLogs as OperationLogs;
use Illuminate\Database\Capsule\Manager as DB;

class Authorize{
  private $req;
  private $code_userID;
  private $code_deviceID;
  private $code_expires;
  private $user_email;
  
  public function init(){
    $this->req = RequestData::$req;
    
    $this->CheckCodeExistence();
  }
  
  private function CheckCodeExistence(){
    if(DB::table('hashrepo_activators')->where('code', $this->req['data']['code'])->doesntExist()){
      Status::Error("Code not found.", 403);
    } else{
      $this->code_userID = DB::table(TABLE_ACTIVATORS_CODE_NAME)->where('code', $this->req['data']['code'])->value('user_id');
      $this->code_deviceID = DB::table(TABLE_ACTIVATORS_CODE_NAME)->where('code', $this->req['data']['code'])->value('device_id');
      $this->code_expires = DB::table(TABLE_ACTIVATORS_CODE_NAME)->where('code', $this->req['data']['code'])->value('ExpiresIn');
      $this->user_email = DB::table(TABLE_USERS_NAME)->where('id', $this->code_userID)->value('email');
      $this->CheckCodeExpires();
    }
  }
  
  private function CheckCodeExpires(){
    if(time() > $this->code_expires){
      Status::Error("This code has expired.", 403);
    } else{
      $this->checkPasswordMatch();
    }
  }
  
  private function checkPasswordMatch(){
    if(!password_verify($this->req['data']['password'], DB::table(TABLE_USERS_NAME)->where('id', $this->code_userID)->value('password'))){
      Status::Error("Invalid Password.", 403);
    } else{
      $this->checkDeviceMatch();
    }
  }
  
  private function checkDeviceMatch(){
    if(DB::table(TABLE_DEVICES_NAME)->where('id', $this->code_deviceID)->value('deviceID') !== $this->req['data']['deviceID']){
      Status::Error("Device id do not match.", 403);
    } else{
      $this->ReturnHash();
    }
  }
  
  private function ReturnHash(){
    if(DB::table(TABLE_DEVICES_NAME)->where('id', $this->code_deviceID)->update(['Status' => "Authorized"])){
      $token = DB::table(TABLE_DEVICES_NAME)->where('id', $this->code_deviceID)->value('hash');
      $SendMail = new \App\Classes\SendMail($this->user_email, 
                                            "Um novo dispositivo foi autorizado na sua conta", "Olá, um novo foi autorizado na sua conta, se não foi você bloqueie imediatamente a conta.");
      Status::SuccesAuthorizeDevice($token);
    } else{
      Status::Error("Unexpected error", 409);
    }
  }
}