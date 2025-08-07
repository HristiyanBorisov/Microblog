<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

session_start();

$app->get('/', 'PostController:index')->setName('public.index');
$app->get('/posts/{id}', 'PostController:show');

// Guest
$app->group('', function () {
    $this->group('/auth', function () {
        $this->get('/register', 'AuthController:displayRegisterForm')->setName('auth.register.form');
        $this->post('/register', 'AuthController:register')->setName('auth.register.submit');
        $this->get('/login', 'AuthController:displayLoginForm')->setName('auth.login.form');
        $this->post('/login', 'AuthController:login')->setName('auth.login.submit');
    });
})->add(new GuestMiddleware($container));

//Logged in user
$app->group('', function () {
    $this->get('/auth/logout', 'AuthController:logout')->setName('auth.logout');
})->add(new AuthMiddleware($container));
