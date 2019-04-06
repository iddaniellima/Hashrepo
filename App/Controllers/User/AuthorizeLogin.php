<?php

namespace App\Controllers\User;
use App\Views\Status as Status;
use Illuminate\Database\Capsule\Manager as DB;
use App\Classes\SecurityHelper as SecurityHelper;

class AuthorizeLogin{
  private $req;
  private $user_id;
  private $user_email;
  private $device_id;
  
  private function dec($p){
    return SecurityHelper::decode($p);
  }
  
  /*
  * Retorna true se o sistema não conseguir decodificar a string. 
  */
  private function encryption_is_not_valid($params){
    foreach($params as $param){
      if(!SecurityHelper::decode($param)){
        return true;
      }
    }
  }
  
  /*
  * Checa se o código existe, se a senha coincide com o do dono do código e se o código já expirou.
  */
  private function check_code(){
    
    // Checa se o código existe
    if(!DB::table(TABLE_ACTIVATORS_CODE_NAME)->where('code', $this->dec($this->req['code']))->exists()){
      return array("status" => false, "message" => "the_code_does_not_exist");
    } 
    
    // Adiciona o id do usuário na variável.
    else {
      $this->user_id = DB::table(TABLE_ACTIVATORS_CODE_NAME)->where('code', $this->dec($this->req['code']))->value('user_id');
      $this->device_id = DB::table(TABLE_ACTIVATORS_CODE_NAME)->where('code', $this->dec($this->req['code']))->value('device_id');
      
      // Checa se a senha coincide com a do dono do código.
      if(!password_verify($this->dec($this->req['password']), DB::table(TABLE_USERS_NAME)->where('id', $this->user_id)->value('password'))){
        return array("status" => false, "message" => "invalid_password");
      } 
      
      // Checa se o código já expirou
      else if(time() > DB::table(TABLE_ACTIVATORS_CODE_NAME)->where('code', $this->dec($this->req['code']))->value('ExpiresIn')){
        return array("status" => false, "message" => "the_code_has_expired");
      } 
      
      // Checa se o MAC registrado e recebido são iguais.
      else if($this->dec($this->req['device_mac']) !== DB::table(TABLE_DEVICES_NAME)->where('id', $this->device_id)->value('device_mac')){
        return array("status" => false, "message" => "ilegal_device");
      }
      
      // Tudo ok.  
      else{
        return array("status" => true);
      }
      
    }
  }
  
  private function update(){
    if(!DB::table(TABLE_DEVICES_NAME)->where('id', $this->device_id)->update(['status' => "Authorized"])){
      return array("status" => false, "message" => "unavailable_service");
    } else {
      
      // Deleta o código após ativar o dispositivo.
      DB::table(TABLE_ACTIVATORS_CODE_NAME)->where('code', $this->dec($this->req['code']))->delete();  
      return array("status" => true);
    }
  }
  
  public function init(){
    if(!isset(app('request')->body['data'])){
      Status::render_error(400, "fatal", "invalid_request");
    } else{
      $this->req = app('request')->body['data'];
    }
    
    $validate = new \Particle\Validator\Validator;
    $validate->required('password')->string();
    $validate->required('code')->string();
    $validate->required('device_mac')->string();
    
    $result = $validate->validate($this->req);
    
    if(!$result->isValid()){
      Status::render_error(400, "fatal", json_encode($result->getMessages()));
    } 
    
    // Checa se o método de criptografia é válido.
    else if($this->encryption_is_not_valid($this->req)){ 
      Status::render_error(400, "fatal", "invalid_encryption_method");
    }
    
    // Checa se o código, senha e endereço de mac são válidos.
    else if(!$this->check_code()["status"]){
      Status::render_error(403, "user", $this->check_code()["message"]);
    } 
    
    // Ativa o dispositivo e deleta o código.
    else if(!$this->update()["status"]){
      Status::render_error(503, "fatal", $this->update()["message"]);
    } 
    
    // Envia o token para o cliente.
    else{
      $c = DB::table(TABLE_DEVICES_NAME)->where('id', $this->device_id)->value('hash');
      
      http_response_code(100);
      exit('{ "token": "'.$c.'" }');
    }
  }
}