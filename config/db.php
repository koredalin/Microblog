<?php

// CHANGE THE DB INFO ACCORDING TO YOUR DATABASE
define('DB_HOST', 'localhost:3306');
DEVELOPMENT_SERVER === SERVER_ENVIRONMENT && TESTING_MODE
    ? define('DB_NAME', 'microblog_test')
    : define('DB_NAME', 'microblog');
define('DB_USERNAME', 'microblog');
define('DB_PASSWORD', 'microblog_pass');