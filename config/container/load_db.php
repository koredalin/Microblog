<?php

define('DB_CONNECTION', 'db_connection');

// Slim framework
use Slim\Container;

// Services
use App\Services\Database\Database;

$container[Database::class] = function() {
    return new App\Services\Database\Database();
};

$container[DB_CONNECTION] = function(Container $container) {
    return $container[Database::class]->dbConnection();
};