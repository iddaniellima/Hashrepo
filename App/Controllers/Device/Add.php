<?php

namespace App\Controllers\Device;
use App\Views\Status as Status;
use Illuminate\Database\Capsule\Manager as DB;
use App\Classes\SecurityHelper as SecurityHelper;

class Add{
  private $req;
  private $user_id;
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
  * Retorna true se a senha for válida.
  */
  private function password_match(){
    if(!password_verify($this->dec($this->req['password']), DB::table(TABLE_USERS_NAME)->where('email', $this->dec($this->req['email']))->value('password'))){
      return false;
    } else{
      return true;
    }
  }

  /*
  * Retorna true se o e-mail for válido e estiver registrado.
  */
  private function email_is_valid(){
    if(!filter_var($this->dec($this->req['email']), FILTER_VALIDATE_EMAIL)){
      return false;
    } else (DB::table(TABLE_USERS_NAME)->where('email', $this->dec($this->req['email']))->exists()){
      return true;
    }
  }
  
  public function init(){
    if(!isset(app('request')->body['data'])){
      Status::render_error(400, "fatal", "invalid_request");
    } else{
      $this->req = app('request')->body['data'];
    }
    
    
    $validate = new \Particle\Validator\Validator;
    $validate->required('email')->string();
    $validate->required('password')->string();
    $validate->required('device_mac')->string();
    $validate->required('device_manufacturer')->string();
    $validate->required('device_arch')->string();
    $validate->required('device_model')->string();
    $validate->required('device_platform')->string();
    $validate->required('device_system')->string();
    $result = $validate->validate($this->req);
    
    /*
    * Checa se os dados acima foram recebidos no formato string.
    */
    if(!$result->isValid()){
      Status::render_error(400, "fatal", json_encode($result->getMessages()));
    } 
    
    // Checa se o método de criptografia é válido.
    else if($this->encryption_is_not_valid($this->req)){ 
      Status::render_error(400, "fatal", "invalid_encryption_method");
    } 
    
    // Checa se o e-mail existe.
    else if(!$this->email_is_valid()){
      Status::render_error(403, "user", "invalid_email");
    } 
    
    // Checa se o e-mail e senha d]ao match
    else if(!$this->password_match()){
      Status::render_error(403, "user", "invalid_password");
    }
    
    /* O cliente e usuário seguiu o requisitos iniciais. */
    else{
      $this->user_id = DB::table(TABLE_USERS_NAME)->where('email', $this->dec($this->req['email']))->value('id');
      $this->check_device();
    }
  }
  
  /*
  * Verifica se já existem dispositivos registrados com o mesmo, se sim, exclui e prossegue.
  * Provavelmente vai mudar por questões de segurança.
  */
  private function check_device(){
    if(DB::table(TABLE_DEVICES_NAME)->where('device_mac', $this->dec($this->req['device_mac']))->doesntExist()){
      $this->insert_device();
    } else{
      DB::table(TABLE_DEVICES_NAME)->where('uid', $this->user_id)->where('device_mac', $this->dec($this->req['device_mac']))->delete();
      $this->insert_device();
    }
  }
  
  private function insert_device(){
    $this->device_id = DB::table(TABLE_DEVICES_NAME)->insertGetId([
      'uid' => $this->user_id, 
      'hash' => hash('sha512', time().$this->device_id.SYSTEM_GLOBAL_SALT),
      'client_id' => app('request')->body['client_id'],
      'device_mac' => $this->dec($this->req['device_mac']),
      'device_manufacturer' => $this->dec($this->req['device_manufacturer']),
      'device_model' => $this->dec($this->req['device_model']),
      'device_arch' => $this->dec($this->req['device_arch']),
      'device_platform' => $this->dec($this->req['device_platform']),
      'device_system' => $this->dec($this->req['device_system']),
      'device_last_ip_access' => app('request')->ip(),
      'status' => "Waiting Confirmation",
      'expiry' => time()+2592000]
    );
    
    if(!$this->device_id){
      Status::render_error(503, "fatal", "service_unavailable");
    } else{
      
      // Se não correu nenhum erro, prossegue para o gerador de código de ativação.
      $this->create_activation_code();
    }
  }
  
  private function create_activation_code(){
    $activation_code_id = DB::table(TABLE_ACTIVATORS_CODE_NAME)->insertGetId([
      'device_id' => $this->device_id,
      'user_id' => $this->user_id,
      'code' => bin2hex(random_bytes(3)),
      'ExpiresIn' => time()+900]
    );
    
    // Envia o e-mail com o código de autorização para o usuário
    
    $code = DB::table(TABLE_ACTIVATORS_CODE_NAME)->where('id', $activation_code_id)->value('code');
    $SendMail = new \App\Classes\SendMail($this->dec($this->req['email']), "Ative o dispositivo", "Aqui está o código para ativar seu dispositivo: ".$code);
    
    http_response_code(201);
    exit;
  }
}