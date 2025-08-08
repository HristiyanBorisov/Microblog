<?php
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->load();

$settings = require __DIR__ . '/settings.php';

$app = new App($settings);

$container = $app->getContainer();

require_once __DIR__ . '/dependencies.php';
require_once __DIR__ . '/middleware.php';

require_once __DIR__ . '/../app/routes/routes-web.php';
require_once __DIR__ . '/../app/routes/routes-admin.php';
