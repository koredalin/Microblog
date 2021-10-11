<?php

define('APP_ROOT', __DIR__);
define('DB_CONNECTION', 'db_connection');

require_once './../vendor/autoload.php';

use Slim\Container;
use App\Services\Database\Database;

$container = new Container(require_once APP_ROOT.'/config/settings.php');

$container[Database::class] = function() {
    return new App\Services\Database\Database();
};

$container[DB_CONNECTION] = function(Container $container) {
    return $container[Database::class]->dbConnection();
};