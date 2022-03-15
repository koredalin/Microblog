<?php

use App\Controllers\PostController;

// Api Routes
$app->group('/api/v1',
    function () {
        // Auth Routes
        $this->post('/post/create', PostController::class.':create');
        $this->get('/post', PostController::class.':index');
        $this->get('/post/{id}', PostController::class.':view');
        $this->post('/post/update/{id}', PostController::class.':update');
        $this->delete('/post/{id}', PostController::class.':delete');
    }
);