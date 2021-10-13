<?php

namespace App\Controllers\Input\Forms;

use App\Models\User;

/**
 * Description of SignInForm
 *
 * @author Hristo
 */
class SignInForm
{
    const PASSWORD_MIN_SYMBOLS = 4;
    const PASSWORD_MAX_SYMBOLS = 20;
    
    public string $email;
    public string $password;
    
    
    public function validate(): void
    {
        $this->trimAllData();
        
        if (User::EMAIL_MAX_SYMBOLS < strlen($this->email)) {
            throw new DtoValidationException('User email should has '.User::EMAIL_MAX_SYMBOLS.' symbols max.');
        }
        
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new DtoValidationException('Invalid email format.');
        }
        
        if (self::PASSWORD_MIN_SYMBOLS > strlen($this->password) || self::PASSWORD_MAX_SYMBOLS < strlen($this->password)) {
            throw new DtoValidationException('User password should has '.self::PASSWORD_MIN_SYMBOLS.' symbols min and '.self::PASSWORD_MAX_SYMBOLS.' symbols max.');
        }
    }
    
    private function trimAllData(): void
    {
        $this->email = trim($this->email);
        $this->password = trim($this->password);
    }
}
