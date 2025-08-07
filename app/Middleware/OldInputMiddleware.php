<?php

namespace App\Middleware;

use App\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;

class OldInputMiddleware extends Middleware
{
    public function __invoke($request, $response, $next): ResponseInterface
    {
        $this->container->view->getEnvironment()->addGlobal('old', $_SESSION['old']);
        $_SESSION['old'] = $request->getParams();

        return $next($request, $response);
    }
}
