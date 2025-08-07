<?php

namespace App\Controllers;

use App\Core\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IndexController extends AbstractController
{
    public function index(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        return $this->container->view->render($response, 'public/index.twig');
    }
}
