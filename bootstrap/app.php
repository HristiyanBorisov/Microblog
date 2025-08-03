<?php

use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new App();

$app = new App([
        'settings' => [
            'displayErrorDetails' => true
        ]
    ]
);

$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ .'/../resources/views', [
        'cache' => false
    ]);

    $view->addExtension(new Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    return $view;
};

$container['IndexController'] = function ($container) {
    return new \App\Controllers\IndexController($container);
};


$container['PostController'] = function ($container) {
    return new \App\Controllers\PostController($container);
};


$container['AuthController'] = function ($container) {
    return new \App\Controllers\AuthController($container);
};

require_once __DIR__ . '/../app/routes.php';
