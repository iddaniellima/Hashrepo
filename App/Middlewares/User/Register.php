<?php

namespace App\Middlewares\User;
use App\Classes\RequestData as RequestData;
use App\Views\Status as Status;

class Register{
  private $req;
  
  public function init(){
    $this->req = RequestData::$req;
  }
  
  private function verifyRequest(){
     
  }
}