<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;

class ValidationErrorsMiddleware extends Middleware
{
    public function __invoke($request, $response, $next): ResponseInterface
    {
        $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
        unset($_SESSION['errors']);

        return $next($request, $response);
    }
}
