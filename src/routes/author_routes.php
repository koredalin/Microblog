<?php

use App\Controllers\UserController;

// Api Routes
$app->group('/api/v1',
    function () {
        // Auth Routes
        $this->post('/user/register', UserController::class.':register');
        $this->post('/user/login', UserController::class.':login');
        $this->post('/user/logout', UserController::class.':logout');
    }
);