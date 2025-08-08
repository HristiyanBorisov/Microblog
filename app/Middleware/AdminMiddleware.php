<?php

namespace App\Middleware;

use App\Middleware\Middleware;

class AdminMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $user = $this->container->auth->user();

        if (!$user['admin']) {
            $this->container->flash->addMessage('info', 'Only admins can access this page.');
            return $response->withRedirect($this->container->router->pathFor('public.index'));
        }

        return $next($request, $response);
    }
}
