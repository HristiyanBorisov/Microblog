<?php

namespace App\Middleware;

use Psr\Container\ContainerInterface;

class Middleware
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
}
