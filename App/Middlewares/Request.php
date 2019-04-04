<?php

namespace App\Middlewares;
use App\Views\Status as Status;
use App\Classes\SecurityHelper as SecurityHelper;
use App\Classes\OperationLogs as OperationLogs;
use App\Classes\RequestData as RequestData;

class Request{
  public $data;
  
  public function init(){
    if(!isset(app('request')->body['client_id'])){
      status::render_error(400, "fatal", "client_id_not_received");
    } else if(!SecurityHelper::decode(app('request')->body['client_id'])){
      status::render_error(400, "fatal", "client_id_encryption_method_is_invalid");
    } else if(!isset(SYSTEM_CLIENTS[SecurityHelper::decode(app('request')->body['client_id'])])){
      status::render_error(400, "fatal", "client_id_not_found");
    } else{
      app('request')->body['client_id'] = SecurityHelper::decode(app('request')->body['client_id']);
    }
  }
}