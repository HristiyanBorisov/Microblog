<?php

namespace App\Middleware;

use App\Middleware\Middleware;

class HttpMethodOverrideMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (strtoupper($request->getMethod()) === 'POST') {
            $overrideMethod = $request->getParsedBodyParam('X-HTTP-Method-Override');

            if (!$overrideMethod) {
                $parsedBody = $request->getParsedBody();
                if (is_array($parsedBody) && isset($parsedBody['_METHOD'])) {
                    $overrideMethod = $parsedBody['_METHOD'];
                }
            }

            if ($overrideMethod) {
                $request = $request->withMethod(strtolower($overrideMethod));
            }
        }

        return $next($request, $response);
    }
}
