<?php

use App\Controllers\AdminController;
use App\Middleware\HttpMethodOverrideMiddleware;
use App\Services\AuthService;
use App\Services\ImageUploadService;
use App\Validation\Rules\AvailableUsername;
use Slim\App;
use App\Validation\Validator;
use App\Controllers\IndexController;
use App\Controllers\PostController;
use App\Controllers\AuthController;
use App\Middleware\ValidationErrorsMiddleware;
use App\Middleware\OldInputMiddleware;
use App\Middleware\CsrfMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->load();

$app = new App();

$app = new App([
        'settings' => [
            'displayErrorDetails' => true,
            'debug' => true,
        ]
    ]
);

$container = $app->getContainer();

require_once __DIR__ . '/../app/Database.php';

$container['auth'] = function ($container) {
    return new AuthService($container->db);
};

$container['imageUploadService'] = function () {
    return new ImageUploadService();
};

$container['flash'] = function ($container) {
    return new Slim\Flash\Messages();
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ .'/../resources/views', [
        'cache' => false
    ]);

    $view->addExtension(new Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user()
    ]);

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
};

$container['validator'] = function ($container) {
    return new Validator();
};

// Rules
$container['availableUsername'] = function ($container) {
    return new AvailableUsername($container);
};

// Controllers
$container['IndexController'] = function ($container) {
    return new IndexController($container);
};
$container['PostController'] = function ($container) {
    return new PostController($container);
};
$container['AuthController'] = function ($container) {
    return new AuthController($container);
};
$container['AdminController'] = function ($container) {
    return new AdminController($container);
};

$container['csrf'] = function ($container) {
    return new \Slim\Csrf\Guard;
};

// Middleware
$app->add(new ValidationErrorsMiddleware($container));
$app->add(new OldInputMiddleware($container));
$app->add(new CsrfMiddleware($container));
$app->add(new HttpMethodOverrideMiddleware($container));

$app->add($container->csrf);

require_once __DIR__ . '/../app/routes.php';
