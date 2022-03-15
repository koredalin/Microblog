<?php

// Slim framework
use Slim\Container;

// Repository service interfaces
use App\Services\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Repositories\Interfaces\PostRepositoryInterface;

// Repository service classes
use App\Services\Repositories\UserRepository;
use App\Services\Repositories\PostRepository;

$container[UserRepositoryInterface::class] = function (Container $container) {
    $userRepository = new UserRepository($container[DB_CONNECTION]);
    
    return $userRepository;
};

$container[PostRepositoryInterface::class] = function (Container $container) {
    $userRepository = new PostRepository($container[DB_CONNECTION]);
    
    return $userRepository;
};