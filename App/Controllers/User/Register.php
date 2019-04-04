<?php

namespace App\Controllers\User;
use App\Views\Status as Status;
use App\Classes\RequestData as RequestData;
use Illuminate\Database\Capsule\Manager as DB;
use App\Classes\SecurityHelper as SecurityHelper;

class Register{
  private $req;
  private $user_id;
  private $activation_code_id;
  private $security_questions;
  private $security_answers;
  
  public function init(){
    
    if(!isset(app('request')->body['data'])){
      Status::render_error(400, "fatal", "invalid_request");
    } else{
      $this->req = app('request')->body['data'];
    }
    

    $validate = new \Particle\Validator\Validator;
    $validate->required('firstname')->string();
    $validate->required('lastname')->string();
    $validate->required('email')->string();
    $validate->required('password')->string();
    $validate->required('sec_quest_1')->string();
    $validate->required('sec_quest_2')->string();
    $validate->required('sec_quest_3')->string();
    $validate->required('sec_quest_1_reply')->string();
    $validate->required('sec_quest_2_reply')->string();
    $validate->required('sec_quest_3_reply')->string();
    $result = $validate->validate($this->req);
    
    
    /*
    * Checa se os dados acima foram recebidos no formato string.
    */
    if(!$result->isValid()){
      Status::render_error(400, "fatal", json_encode($result->getMessages()));
    } 
    
    /*
    * Checa se o método de criptografia é válido.
    */
    else if($this->encryption_is_not_valid($this->req)){ 
      Status::render_error(400, "fatal", "invalid_encryption_method");
    } 
    
    /*
    * Checa se o e-mail é válida e se já está registrado.
    */
    else if(!$this->email_is_valid()){
      Status::render_error(422, "user", "email_already_registered");
    } else{
      
      /*
      * Se tudo ocorrer bem, aqui é montado os array com as perguntas e respostas de segurança, isso no formato JSON.
      */
      
      $this->security_answers = json_encode(array(
        "1" => password_hash($this->dec($this->req['sec_quest_1_reply']), PASSWORD_DEFAULT),
        "2" => password_hash($this->dec($this->req['sec_quest_1_reply']), PASSWORD_DEFAULT),
        "3" => password_hash($this->dec($this->req['sec_quest_1_reply']), PASSWORD_DEFAULT)
      ));
      
      $this->security_questions = json_encode(array(
        "1" => SecurityHelper::SymEncDec($this->dec($this->req['sec_quest_1']), 1),
        "2" => SecurityHelper::SymEncDec($this->dec($this->req['sec_quest_1']), 1),
        "3" => SecurityHelper::SymEncDec($this->dec($this->req['sec_quest_1']), 1)
      ));
      
      /*
      * Prossegue para o registro
      */
      $this->register();
    }
  }

  private function dec($p){
    return SecurityHelper::decode($p);
  }
  
  private function email_is_valid(){
    if(!filter_var($this->dec($this->req['email']), FILTER_VALIDATE_EMAIL)){
      return false;
    } else if(DB::table(TABLE_USERS_NAME)->where('email', $this->dec($this->req['email']))->exists()){
      return false;
    } else{
      return true;
    }
  }
  
  private function encryption_is_not_valid($params){
    foreach($params as $param){
      if(!SecurityHelper::decode($param)){
        return true;
      }
    }
  }
  
  private function register(){
    $this->user_id = DB::table(TABLE_USERS_NAME)->insertGetId([
      'firstname' => $this->dec($this->req['firstname']),
      'surname' => $this->dec($this->req['lastname']),
      'email' => $this->dec($this->req['email']),
      'password' => password_hash($this->dec($this->req['password']), PASSWORD_DEFAULT),
      'security_questions' => $this->security_questions,
      'security_answers' => $this->security_answers,
      'account_status' => "Waiting Confirmation",
      'created' => time()
    ]);
    
    if(!$this->user_id){
      Status::render_error(503, "fatal", "service_register_unavailable");
    } else{
      $this->create_confirmation_code();
    }
  }
  
  private function create_confirmation_code(){
    $this->activation_code_id = DB::table(TABLE_ACTIVATORS_CODES_ACCOUNT_NAME)->insertGetId([
      'uid' => $this->user_id,
      'code' => bin2hex(random_bytes(3)),
      'expiresIn' => time()+900]
    );
    
    if(!$this->activation_code_id){
      
      /* 
      *  Mesmo se o código de ativação não ser criado por conta de uma falha o usuário ainda pode solicitar um novo através do cliente. 
      *  Eu poderia reiniciar a função mas não sei se isso seria o correto, por enquanto irei deixar assim.
      */
      
      http_response_code(201);
      exit;
    } else{
      
      $mail_data = array("name" => DB::table(TABLE_USERS_NAME)->where('id', $this->user_id)->value('firstname'), 
                         "code" => DB::table(TABLE_ACTIVATORS_CODES_ACCOUNT_NAME)->where('id', $this->activation_code_id)->value('code'));
      
      
      /* Eu poderia usar um template, porém como o sistema ainda está em desenvolvimento vou deixar em texto simples. */
      $SendMail = new \App\Classes\SendMail($this->dec($this->req['email']), "Ative sua conta", "Código de ativação: <strong>".$mail_data['code']."</strong>");
      
      http_response_code(201);
      exit;
    }
  }
}