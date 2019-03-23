<?php

namespace App\Classes;

class RequestData{
  public static $req;
  
  public static function save($data){
    self::$req = $data;
  }
}