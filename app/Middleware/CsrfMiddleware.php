<?php

namespace App\Middleware;

use App\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;

class CsrfMiddleware extends Middleware
{
    public function __invoke($request, $response, $next): ResponseInterface
    {
        $this->container->view->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="' . $this->container->csrf->getTokenNameKey() . '"
                    value="' . $this->container->csrf->getTokenName() . '">
                <input type="hidden" name="' . $this->container->csrf->getTokenValueKey() . '"
                    value="' . $this->container->csrf->getTokenValue() . '">
            '
        ]);
        $_SESSION['old'] = $request->getParams();

        return $next($request, $response);
    }
}
