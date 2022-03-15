<?php

// Slim framework
use Slim\Container;

// Service interfaces
use App\Services\User\Interfaces\UserInterface;
use App\Services\Posts\Interfaces\PostInterface;

// Controllers
use App\Controllers\UserController;
use App\Controllers\PostController;

$container[UserController::class] = function (Container $container) {
    $controller = new UserController(
        $container[UserInterface::class]
    );
    
    return $controller;
};

$container[PostController::class] = function (Container $container) {
    $controller = new PostController(
        $container[UserInterface::class],
        $container[PostInterface::class]
    );
    
    return $controller;
};