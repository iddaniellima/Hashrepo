<?php

namespace App\Classes;
use App\Classes\RequestData as RequestData;
use Illuminate\Database\Capsule\Manager as DB;

class OperationLogs{
  public static function Register($Operation){
    if(DEV_MODE){
      $encode = json_encode(RequestData::$req);
      DB::table('SystemOperations')->insert(
        ['OPERATION' => $Operation, 'USER_IP' => app('request')->ip(), 'OPERATION_ADDITIONAL_DATA' => $encode, 'OPERATION_TIME' => time()]
      );
    }
  }
  
  public static function Filter($data){
    unset($data['credentials']['password']);
    return json_encode($data);
  }
}