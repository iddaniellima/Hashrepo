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
    
    if($this->verify_request()[0]){
      
    }
    
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
      Status::Error("Desculpe, já existe uma conta com esse e-mail.", 409);
    }
  }
  
  private function decrypt_data(){
    if(!SecurityHelper::decode($this->req['firstname']) || !SecurityHelper::decode($this->req['lastname']) || !SecurityHelper::decode($this->req['credentials']['email']) || !SecurityHelper::decode($this->req['security_questions'][1]) || !SecurityHelper::decode($this->req['security_questions'][2]) || !SecurityHelper::decode($this->req['security_questions'][3]) || !SecurityHelper::decode($this->req['security_answers'][1]) || !SecurityHelper::decode($this->req['security_answers'][2]) || !SecurityHelper::decode($this->req['security_answers'][3]) || !SecurityHelper::decode($this->req['credentials']['password']) || !filter_var(SecurityHelper::decode($this->req['credentials']['email']), FILTER_VALIDATE_EMAIL)){
      return array(false, "{ 'class': 'internal', 'message': 'invalid_crypto_method' }");
    } else {
      return array(true);
    }
  }
  
  private function verify_request(){
    if(isset($this->req) || isset($this->req['firstname']) || isset($this->req['lastname']) || isset($this->req['credentials']) || isset($this->req['credentials']['email']) || isset($this->req['credentials']['password']) || isset($this->req['security_questions']) || isset($this->req['security_questions'][1]) || isset($this->req['security_questions'][2]) || isset($this->req['security_questions'][3]) || isset($this->req['security_answers']) || isset($this->req['security_answers'][1]) || isset($this->req['security_answers'][2]) || isset($this->req['security_answers'][3]) ){
      return array(true);
    } else {
      return array(false, "{ 'class': 'internal', 'message': 'invalid_request' }");
    }
  }
}