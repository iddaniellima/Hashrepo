<?php

namespace App\Controllers\Device;
use App\Views\Status as Status;
use App\Classes\RequestData as RequestData;
use App\Classes\OperationLogs as OperationLogs;
use Illuminate\Database\Capsule\Manager as DB;

class Add{
  private $device_id;
  private $password;
  private $ActivationCodeID;
  private $user_id;
  
  public function init(){
    OperationLogs::Register("device/add - Started - ".time());
    $this->req = RequestData::$req;
    $this->CheckForIdentifierExistence();
  }
  
  private function CheckForIdentifierExistence(){
    if(DB::table(TABLE_USERS_NAME)->where('email', $this->req['credentials']['identifier'])->doesntExist()){
      OperationLogs::Register("device/add - Identifier not found ".time());
      Status::Error("Identifier not found.", 403);
    } else{
      $this->password = DB::table(TABLE_USERS_NAME)->where('email', $this->req['credentials']['identifier'])->value('password');
      $this->VerifyPassword();
    }
  }
  
  private function VerifyPassword(){
    if(!password_verify($this->req['credentials']['password'], $this->password)){
      OperationLogs::Register("device/add - Invalid password - ".time());
      Status::Error("Invalid password.", 403);
    } else{
      OperationLogs::Register("device/add - Credentials OK - ".time());
      $this->user_id = DB::table(TABLE_USERS_NAME)->where('email', $this->req['credentials']['identifier'])->value('id');
      $this->checkDevices();
    }
  }
  
  private function checkDevices(){
    if(DB::table(TABLE_DEVICES_NAME)->where('deviceID',$this->req['deviceData']['deviceID'])->doesntExist()){
      $this->InsertDevice();
    } else{
      DB::table(TABLE_DEVICES_NAME)->where('uid', $this->user_id)->where('deviceID', $this->req['deviceData']['deviceID'])->delete();
      $this->InsertDevice();
    }
  }
  
  private function InsertDevice(){
    $this->device_id = DB::table(TABLE_DEVICES_NAME)->insertGetId([
      'uid' => $this->user_id, 
      'hash' => hash('sha512', time().$this->device_id.SYSTEM_GLOBAL_SALT),
      'clientID' => $this->req['ClientID'],
      'deviceID' => $this->req['deviceData']['deviceID'],
      'deviceName' => $this->req['deviceData']['deviceName'],
      'deviceModel' => $this->req['deviceData']['deviceModel'],
      'deviceSystem' => $this->req['deviceData']['deviceSystem'],
      'deviceLastIP' => app('request')->ip(),
      'Status' => "Waiting Confirmation",
      'ExpiresIn' => time()+2592000]
    );
    
    OperationLogs::Register("device/add - Added Device ID ".$this->device_id." - ".time());
    
    $this->CreateActivationCode();
  }
  
  private function CreateActivationCode(){
    $this->ActivationCodeID = DB::table(TABLE_ACTIVATORS_CODE_NAME)->insertGetId([
      'device_id' => $this->device_id,
      'user_id' => $this->user_id,
      'code' => bin2hex(random_bytes(3)),
      'ExpiresIn' => time()+900]
    );
    
    OperationLogs::Register("device/add - Created Activation Code ".$this->ActivationCodeID." For Device ".$this->device_id." - ".time());
    
    $this->PrepareSendEmail();
    
  }
  
  private function PrepareSendEmail(){
    $code = DB::table(TABLE_ACTIVATORS_CODE_NAME)->where('id', $this->ActivationCodeID)->value('code');
    $name = DB::table(TABLE_USERS_NAME)->where('email', $this->req['credentials']['identifier'])->value('firstname');
    $template = new \App\Views\Mail\Device\Add\Add($name, $code);
    $SendMail = new \App\Classes\SendMail($this->req['credentials']['identifier'], "Seu código de ativação", $template->get_mounted_template());
    OperationLogs::Register("device/add - Code sent to user e-mail - ".time());
    Status::SuccesAddDevice("Wait for user activation.");
  }
}