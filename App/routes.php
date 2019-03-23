<?php

// Descriptografa a requisição e checa o ID do cliente
$route->before('/', 'App\Middlewares\RequestCheck@Init');

$route->group('/device', function(){
  $this->before('/device/add', 'App\Middlewares\Device\Add@Init');
  $this->post('/add', 'App\Controllers\Device\Add@init');
  
  $this->post('/teste', function(){
    $template = new \App\Views\Mail\Device\Add\Add("Daniel", "CFGER");
    echo $template->get_mounted_template();
  });
  
  $this->before('/device/authorize', 'App\Middlewares\Device\Authorize@Init');
  $this->post('/authorize', 'App\Controllers\Device\Authorize@init');
});

$route->group('/user', function(){
  $this->post('/register', 'App\Controllers\User\Register@init');
  $this->post('/recovery', 'App\Controllers\User\Recovery@init');
});