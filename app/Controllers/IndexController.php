<?php

namespace App\Controllers;

use App\Core\AbstractController;

class IndexController extends AbstractController
{
    public function index($request, $response, $args)
    {
        return $this->container->view->render($response, 'public/index.twig');
    }
}
