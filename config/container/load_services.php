<?php

// Slim framework
use Slim\Container;

// Repository interfaces
use App\Services\Repositories\Interfaces\UserRepositoryInterface;

// Service interfaces
use App\Services\Authentication\Interfaces\AuthenticationInterface;
use App\Services\Authentication\Interfaces\JwtHandlerInterface;

// Service classes
use App\Services\Authentication\AuthenticationService;
use App\Services\Authentication\JwtHandler;

$container[JwtHandlerInterface::class] = function () {
    $userRepository = new JwtHandler();
    
    return $userRepository;
};

$container[AuthenticationInterface::class] = function (Container $container) {
    $userRepository = new AuthenticationService(
        $container[UserRepositoryInterface::class],
        $container[JwtHandlerInterface::class]
    );
    
    return $userRepository;
};

