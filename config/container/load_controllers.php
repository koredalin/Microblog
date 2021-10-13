<?php

// Slim framework
use Slim\Container;

// Service interfaces
use App\Services\Authentication\Interfaces\AuthenticationInterface;

// Controllers
use App\Controllers\UserController;

$container[UserController::class] = function (Container $container) {
    $controller = new UserController(
        $container[AuthenticationInterface::class]
    );
    
    return $controller;
};