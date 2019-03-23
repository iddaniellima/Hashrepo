<?php

namespace App\Classes;
use phpseclib\Crypt\RSA;

class SecurityHelper{
  public static function decode($data){
    $rsa = new RSA();
    $rsa->loadKey(file_get_contents(BASE_PATH.'App/keys/private.key'));
    $rsa->setEncryptionMode(RSA::ENCRYPTION_PKCS1);

    return $rsa->decrypt(base64_decode($data));
  }
  public static function encode(){}
}