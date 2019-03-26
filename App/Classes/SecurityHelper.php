<?php

namespace App\Classes;
use phpseclib\Crypt\RSA;
use phpseclib\Crypt\AES;
use phpseclib\Crypt\Random;
use phpseclib\Crypt\Blowfish;

class SecurityHelper{
  public static function decode($data){
    $rsa = new RSA();
    $rsa->loadKey(file_get_contents(BASE_PATH.'App/keys/private.key'));
    $rsa->setEncryptionMode(RSA::ENCRYPTION_PKCS1);

    return $rsa->decrypt(base64_decode($data));
  }
  
  public static function encode(){}
  
  public static function SymEncDec($text, $method){
    $cipher = new Blowfish();
    $cipher->setKey('9242A49326EBB7DCAF41CF7BB787E');
    
    switch($method){
      case 1:
        $a = base64_encode($cipher->encrypt($text));
        return $a;
        break;
      case 2:
        $b = base64_decode($text);
        return $cipher->decrypt($b);
        break;
    }
  }
}