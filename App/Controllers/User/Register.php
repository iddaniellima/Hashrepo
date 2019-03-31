<?php

namespace App\Controllers\User;
use App\Views\Status as Status;
use App\Classes\RequestData as RequestData;
use Illuminate\Database\Capsule\Manager as DB;
use App\Classes\SecurityHelper as SecurityHelper;

class Register{
  private $req;
  private $encoded_security_questions;
  private $encoded_security_answers;
  private $user_id;
  private $activation_code_id;
  
  public function init(){
    $this->req = RequestData::$req;
    
    if(!$this->VerifyRequest()[0]){
      Status::Error($this->VerifyRequest()[1], 400);
    } else if(!$this->DecryptData()[0]){
      Status::Error($this->DecryptData()[1], 403);
    } else{
      $this->req['firstname'] = SecurityHelper::decode($this->req['firstname']);
      $this->req['lastname'] = SecurityHelper::decode($this->req['lastname']);
      $this->req['credentials']['email'] = SecurityHelper::decode($this->req['credentials']['email']);
      $this->req['credentials']['password'] = SecurityHelper::decode($this->req['credentials']['password']);
      $this->req['security_questions'][1] = SecurityHelper::decode($this->req['security_questions'][1]);
      $this->req['security_questions'][2] = SecurityHelper::decode($this->req['security_questions'][2]);
      $this->req['security_questions'][3] = SecurityHelper::decode($this->req['security_questions'][3]);
      $this->req['security_answers'][1] = SecurityHelper::decode($this->req['security_answers'][1]);
      $this->req['security_answers'][2] = SecurityHelper::decode($this->req['security_answers'][2]);
      $this->req['security_answers'][3] = SecurityHelper::decode($this->req['security_answers'][3]);
      
      $this->CheckEmail();
    }
  }
  
  private function send_email(){
    $name = DB::table(TABLE_USERS_NAME)->where('id', $this->user_id)->value('firstname');
    $code = DB::table(TABLE_ACTIVATORS_CODES_ACCOUNT_NAME)->where('id', $this->activation_code_id)->value('code');
    
    $SendMail = new \App\Classes\SendMail($this->req['credentials']['email'], "Aqui está o código de ativação para sua conta", 
                                         "Olá ".$name.", aqui está o código de ativação para sua conta. Lembre-se que este serve apenas para ativar a sua conta e não o dispositivo, sendo assim você deverá se logar após isso. Código: <strong>".$code."</strong>");
    
    Status::SuccesAddDevice("Wait for user activation.");
  }
  
  private function create_activation_code(){
    $this->activation_code_id = DB::table(TABLE_ACTIVATORS_CODES_ACCOUNT_NAME)->insertGetId([
      'uid' => $this->user_id,
      'code' => bin2hex(random_bytes(3)),
      'expiresIn' => time()+900]
    );
    
    $this->send_email();
  }
  
  private function register_user(){
    $this->user_id = DB::table(TABLE_USERS_NAME)->insertGetId([
      'firstname' => $this->req['firstname'],
      'surname' => $this->req['lastname'],
      'email' => $this->req['credentials']['email'],
      'password' => $this->req['credentials']['password'],
      'security_questions' => $this->encoded_security_questions,
      'security_answers' => $this->encoded_security_answers,
      'account_status' => "Waiting Confirmation",
      'created' => time()
    ]);
    
    $this->create_activation_code();
  }
  
  private function prepare_user_register(){
    $this->req['security_questions'][1] = SecurityHelper::SymEncDec($this->req['security_questions'][1], 1);
    $this->req['security_questions'][2] = SecurityHelper::SymEncDec($this->req['security_questions'][2], 1);
    $this->req['security_questions'][3] = SecurityHelper::SymEncDec($this->req['security_questions'][3], 1);
    
    $this->req['security_answers'][1] = password_hash($this->req['security_answers'][1], PASSWORD_DEFAULT);
    $this->req['security_answers'][2] = password_hash($this->req['security_answers'][2], PASSWORD_DEFAULT);
    $this->req['security_answers'][3] = password_hash($this->req['security_answers'][3], PASSWORD_DEFAULT);
    
    $this->req['credentials']['password'] = password_hash($this->req['credentials']['password'], PASSWORD_DEFAULT);
    
    $this->encoded_security_questions = json_encode($this->req['security_questions']);
    $this->encoded_security_answers = json_encode($this->req['security_answers']);
    
    $this->register_user();
  }
  
  private function CheckEmail(){
    if(DB::table(TABLE_USERS_NAME)->where('email', $this->req['credentials']['email'])->doesntExist()){
      $this->prepare_user_register();
    } else{
      Status::Error("R0026", 409);
    }
  }
  
  private function DecryptData(){
    if(!SecurityHelper::decode($this->req['firstname'])){
      return array(false, "R0015");
    }
    
    else if(!SecurityHelper::decode($this->req['lastname'])){
      return array(false, "R0016");
    }
    
    else if(!SecurityHelper::decode($this->req['credentials']['email'])){
      return array(false, "R0017");
    }
    
    else if(!SecurityHelper::decode($this->req['security_questions'][1])){
      return array(false, "R0018");
    }
    
    else if(!SecurityHelper::decode($this->req['security_questions'][2])){
      return array(false, "R0019");
    }
    
    else if(!SecurityHelper::decode($this->req['security_questions'][3])){
      return array(false, "R0020");
    }
    
    else if(!SecurityHelper::decode($this->req['security_answers'][1])){
      return array(false, "R0021");
    }
    
    else if(!SecurityHelper::decode($this->req['security_answers'][2])){
      return array(false, "R0022");
    }
    
    else if(!SecurityHelper::decode($this->req['security_answers'][3])){
      return array(false, "R0023");
    }
    
    else if(!SecurityHelper::decode($this->req['credentials']['password'])){
      return array(false, "R0024");
    }
    
    else if(!filter_var(SecurityHelper::decode($this->req['credentials']['email']), FILTER_VALIDATE_EMAIL)){
      return array(false, "R0025");
    }
    
    else {
      return array(true);
    }
  }
  
  private function VerifyRequest(){
    if(!isset($this->req)){
      return array(false, "R0001");
    }
    
    else if(!isset($this->req['firstname'])){
      return array(false, "R0002");
    }
    
    else if(!isset($this->req['lastname'])){
      return array(false, "R0003");
    }
    
    else if(!isset($this->req['credentials'])){
      return array(false, "R0004");
    }
    
    else if(!isset($this->req['credentials']['email'])){
      return array(false, "R0005");
    }
    
    else if(!isset($this->req['security_questions'])){
      return array(false, "R0006");
    }
    
    else if(!isset($this->req['security_questions'][1])){
      return array(false, "R0007");
    }
    
    else if(!isset($this->req['security_questions'][2])){
      return array(false, "R0008");
    }
    
    else if(!isset($this->req['security_questions'][3])){
      return array(false, "R0009");
    }
    
    else if(!isset($this->req['security_answers'])){
      return array(false, "R0010");
    }
    
    else if(!isset($this->req['security_answers'][1])){
      return array(false, "R0011");
    }
    
    else if(!isset($this->req['security_answers'][2])){
      return array(false, "R0012");
    }
    
    else if(!isset($this->req['security_answers'][3])){
      return array(false, "R0013");
    }
    
    else if(!isset($this->req['credentials']['password'])){
      return array(false, "R0014");
    }
    
    else {
      return array(true);
    }
  }
}