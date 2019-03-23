<?php

namespace App\Classes;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SendMail{
  private $mail;
  private $email;
  private $subject;
  private $body;
  
  function __construct($email, $subject, $body){
    $this->mail = new PHPMailer(false);
    $this->email = $email;
    $this->subject = $subject;
    $this->body = $body;
    
    $this->Send();
  }
  
  public function send(){
    try {
      $this->mail->SMTPDebug = false;
      $this->mail->isSMTP();                                            // Set mailer to use SMTP
      $this->mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      $this->mail->Username   = 'br.daniellima@gmail.com';                     // SMTP username
      $this->mail->Password   = 'uxrwettopqxzozwz';                               // SMTP password
      $this->mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
      $this->mail->Port       = 587;                                    // TCP port to connect to
    
      $this->mail->setFrom('br.daniellima@gmail.com', 'Hashrepo');
      $this->mail->addAddress($this->email); 
    
      $this->mail->isHTML(false);
      $this->mail->Subject = $this->subject;
      $this->mail->Body    = $this->body;
      $this->mail->AltBody = $this->body;
      $this->mail->CharSet = 'UTF-8';
    
      $this->mail->send();
    } catch (Exception $e) {
      //echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
    }
  }
}