<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

session_start();

$app->get('/', 'PostController:index')->setName('public.index');
$app->get('/posts/{id}', 'PostController:show')->setName('public.show');

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

// Admin
$app->group('', function () {
    $this->group('/admin', function () {
        $this->get('/', 'AdminController:index')->setName('admin.index');
        $this->get('/post', 'AdminController:displayCreatePostForm')->setName('admin.post.create.form');
        $this->post('/post', 'AdminController:createPost')->setName('admin.post.create');
        $this->get('/post/{id}', 'AdminController:displayUpdatePostForm')->setName('admin.post.update.form');
        $this->put('/post/{id}', 'AdminController:updatePost')->setName('admin.post.update.submit');
        $this->delete('/post/{id}', 'AdminController:deletePost')->setName('admin.post.delete');
    });
});
