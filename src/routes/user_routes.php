<?php

use App\Controllers\UserController;

// Api Routes
$app->group('/api/v1',
    function () {
        // Auth Routes
        $this->post('/user/register', UserController::class.':register');
        $this->post('/user/login', UserController::class.':login');
        $this->get('/user', UserController::class.':index');
        $this->get('/user/{id}', UserController::class.':view');
        $this->delete('/user/{id}', UserController::class.':delete');
    }
);