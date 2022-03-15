<?php

// Domain
define('DOMAIN', 'http://localhost/microblog/');

// Routes
define('APP_ROOT', __DIR__.'/..');
define('PUBLIC_ROOT', APP_ROOT.'/public');
define('CONFIG_ROOT', __DIR__);
define('SOURCE_ROOT', APP_ROOT.'/src');

// Server environments
define('DEVELOPMENT_SERVER', 'developement');
define('PRODUCTION_SERVER', 'production');
define('SERVER_ENVIRONMENT', DEVELOPMENT_SERVER);

// Testing
define('TESTING_MODE', false);

// Images upload folder
DEVELOPMENT_SERVER === SERVER_ENVIRONMENT && TESTING_MODE
    ? define('IMAGES_UPLOAD_DIR', PUBLIC_ROOT.'/images_test')
    : define('IMAGES_UPLOAD_DIR', PUBLIC_ROOT.'/images');

// Session duration
define('SESSION_DURATION_IN_SECONDS', 3 * 60 * 60);

// Character encodings
define('INTERNAL_ENCODING', 'UTF-8');
mb_internal_encoding(INTERNAL_ENCODING);

// Database
require_once __DIR__.'/db.php';