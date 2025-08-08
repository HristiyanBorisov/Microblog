<?php

use App\Middleware\CsrfMiddleware;
use App\Middleware\OldInputMiddleware;
use App\Middleware\ValidationErrorsMiddleware;
use App\Middleware\HttpMethodOverrideMiddleware;

$app->add(new ValidationErrorsMiddleware($container));
$app->add(new OldInputMiddleware($container));
$app->add(new CSRFMiddleware($container));
$app->add(new HttpMethodOverrideMiddleware($container));
$app->add($container->csrf);

