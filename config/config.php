<?php

define('DOMAIN', 'http://localhost/microblog/');
define('APP_ROOT', __DIR__.'/..');
define('PUBLIC_ROOT', APP_ROOT.'/public');
define('DEVELOPMENT_SERVER', 'developement');
define('PRODUCTION_SERVER', 'production');
define('SERVER_ENVIRONMENT', DEVELOPMENT_SERVER);
define('TESTING_MODE', false);
DEVELOPMENT_SERVER === SERVER_ENVIRONMENT && TESTING_MODE
    ? define('IMAGES_UPLOAD_DIR', PUBLIC_ROOT.'/images_test')
    : define('IMAGES_UPLOAD_DIR', PUBLIC_ROOT.'/images');
define('CONFIG_ROOT', __DIR__);
define('SOURCE_ROOT', APP_ROOT.'/src');
define('SESSION_DURATION_IN_SECONDS', 3600 * 3);

require_once __DIR__.'/db.php';