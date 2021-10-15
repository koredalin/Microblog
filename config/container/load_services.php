<?php

// Slim framework
use Slim\Container;

// Repository interfaces
use App\Services\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Repositories\Interfaces\PostRepositoryInterface;

// Service interfaces
use App\Services\User\Interfaces\UserInterface;
use App\Services\User\Interfaces\JwtHandlerInterface;
use App\Services\Posts\Interfaces\PostInterface;
use App\Services\Files\Interfaces\FileUploadInterface;
use App\Services\Files\Interfaces\FileInterface;

// Service classes
use App\Services\User\UserService;
use App\Services\User\JwtHandler;
use App\Services\Posts\PostService;
use App\Services\Files\FileUploadService;
use App\Services\Files\FileService;

$container[JwtHandlerInterface::class] = function () {
    $userRepository = new JwtHandler();
    
    return $userRepository;
};

$container[UserInterface::class] = function (Container $container) {
    $userRepository = new UserService(
        $container[UserRepositoryInterface::class],
        $container[JwtHandlerInterface::class]
    );
    
    return $userRepository;
};

$container[FileUploadInterface::class] = function () {
    $userRepository = new FileUploadService();
    
    return $userRepository;
};

$container[FileInterface::class] = function () {
    $userRepository = new FileService();
    
    return $userRepository;
};

$container[PostInterface::class] = function (Container $container) {
    $userRepository = new PostService(
        $container[UserRepositoryInterface::class],
        $container[PostRepositoryInterface::class],
        $container[FileUploadInterface::class],
        $container[FileInterface::class]
    );
    
    return $userRepository;
};