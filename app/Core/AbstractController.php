<?php

namespace App\Core;

abstract class AbstractController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
}
