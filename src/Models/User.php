<?php

namespace App\Models;

use App\Exceptions\DtoValidationException;
use App\Services\Helpers\DateTimeManager;

/**
 * Description of User
 *
 * @author Hristo
 */
class User
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $password_hash;
    public string $created_at;
    public string $updated_at;
    
    
    public function validate(): void
    {
        $this->trimAllData();
        
        if (3 > strlen($this->first_name) || 60 < strlen($this->first_name)) {
            throw new DtoValidationException('User first name should be between 3 and 60 symbols');
        }
        
        if (3 > strlen($this->last_name) || 60 < strlen($this->last_name)) {
            throw new DtoValidationException('User lasst name should be between 3 and 60 symbols');
        }
        
        if (255 < strlen($this->email)) {
            throw new DtoValidationException('User email should has 255 symbols max.');
        }
        
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new DtoValidationException('Invalid email format.');
        }
        
        if (255 < strlen($this->password_hash)) {
            throw new DtoValidationException('User password hash should has 255 symbols max.');
        }
        
        if (DateTimeManager::validateDateTime($this->created_at)) {
            throw new DtoValidationException('Not valid user creation date format.');
        }
        
        if (DateTimeManager::validateDateTime($this->updated_at)) {
            throw new DtoValidationException('Not valid user update date format.');
        }
    }
    
    public function validateDbRecord(): void
    {
        $this->validate();
        
        if (!isset($this->id) || 1 > $this->id) {
            throw new DtoValidationException('User id is not set.');
        }
    }
    
    private function trimAllData(): void
    {
        $this->first_name = trim($this->first_name);
        $this->last_name = trim($this->last_name);
        $this->email = trim($this->email);
        $this->password_hash = trim($this->password_hash);
        $this->created_at = trim($this->created_at);
        $this->updated_at = trim($this->updated_at);
    }
}
