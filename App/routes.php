<?php

$route->before('/', 'App\Middlewares\Request@init');

$route->before('/device/authorize', 'App\Middlewares\Device\Authorize@Init');
$route->before('/device/add', 'App\Middlewares\Device\Add@Init');

use App\Classes\SecurityHelper as SecurityHelper;
$route->group('/device', function(){
  $this->post('/add', 'App\Controllers\Device\Add@init');
  $this->post('/authorize', 'App\Controllers\Device\Authorize@init');
});

$route->group('/user', function(){
 $this->post('/register', 'App\Controllers\User\Register@init');
});