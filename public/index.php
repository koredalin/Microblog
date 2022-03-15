<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__.'/../config/config.php';

require_once APP_ROOT.'/vendor/autoload.php';

use Slim\Container;
use Slim\App;

$container = new Container(require_once CONFIG_ROOT.'/settings.php');
require_once CONFIG_ROOT.'/container/load_all_instances.php';

$app = new App($container);
require_once SOURCE_ROOT.'/routes/load_all_routes.php';

$app->run();