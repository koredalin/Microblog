<?php

namespace App\Services\Repositories;

use PDO;

/**
 * Description of BaseRepository
 *
 * @author Hristo
 */
class BaseRepository
{
    protected PDO $dbConnection;

    public function __construct(PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }
}
