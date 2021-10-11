<?php

namespace App\Services\Database;

use PDO;

/**
 * Description of Database
 *
 * @author Hristo
 */
class Database
{
    // CHANGE THE DB INFO ACCORDING TO YOUR DATABASE
    private $db_host = DB_HOST;
    private $db_name = DB_NAME;
    private $db_username = DB_USERNAME;
    private $db_password = DB_PASSWORD;
    
    public function dbConnection(): PDO
    {
        
        try{
            $conn = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name,$this->db_username,$this->db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e){
            echo "Connection error ".$e->getMessage(); 
            exit;
        }
          
    }
}