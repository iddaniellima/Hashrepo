<?php

$route->before('/', 'App\Middlewares\Request@init');

$route->group('/user', function(){
 $this->post('/register', 'App\Controllers\User\Register@init');
  
 $this->group('/login', function(){
   $this->post('/', 'App\Controllers\User\Login@init');
   $this->post('/authorize', 'App\Controllers\User\AuthorizeLogin@init');
 });
});