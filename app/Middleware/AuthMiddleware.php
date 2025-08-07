<?php

namespace App\Middleware;

class AuthMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!$this->container->auth->check()) {
            $this->container->flash->addMessage('info', 'Please Sign In First');
            return $response->withRedirect($this->container->router->pathFor('auth.login.form'));
        }

        return $next($request, $response);
    }
}
