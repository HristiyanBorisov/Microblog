<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\IndexController;
use App\Controllers\PostController;
use App\Database;
use App\Services\AuthService;
use App\Services\ImageUploadService;
use App\Validation\Rules\AvailableUsername;
use App\Validation\Validator;
use Slim\Flash\Messages;
use Slim\Csrf\Guard;

// Database
$container['db'] = fn () => Database::connect();

// Services
$container['auth'] = fn ($c) => new AuthService($c->db);
$container['imageUploadService'] = fn () => new ImageUploadService();
$container['flash'] = fn () => new Messages();

// View
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__ .'/../resources/views', [
        'cache' => false
    ]);

    $view->addExtension(new Slim\Views\TwigExtension(
        $c->router,
        $c->request->getUri()
    ));

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $c->auth->check(),
        'user' => $c->auth->user()
    ]);

    $view->getEnvironment()->addGlobal('flash', $c->flash);

    return $view;
};

// Validation
$container['validator'] = fn () => new Validator();
$container['availableUsername'] = fn ($c) => new AvailableUsername($c);

// Controllers
$container['IndexController'] = fn ($c) => new IndexController($c);
$container['PostController'] = fn ($c) => new PostController($c);
$container['AuthController'] = fn ($c) => new AuthController($c);
$container['AdminController'] = fn ($c) => new AdminController($c);

// CSRF
$container['csrf'] = fn () => new Guard;
