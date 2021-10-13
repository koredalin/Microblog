<?php

namespace App\Controllers\Input\Forms;

use App\Controllers\Input\Forms\SignInForm;

use App\Models\User;

/**
 * Description of SignUpForm
 *
 * @author Hristo
 */
class SignUpForm extends SignInForm
{
    public string $firstName;
    public string $lastName;
    
    
    public function validate(): void
    {
        parent::validate();
        
        $this->trimAllData();
        
        if (User::FIRST_NAME_MIN_SYMBOLS > strlen($this->firstName) || User::FIRST_NAME_MAX_SYMBOLS < strlen($this->firstName)) {
            throw new DtoValidationException('User first name should be between '.User::FIRST_NAME_MIN_SYMBOLS.' and '.User::FIRST_NAME_MAX_SYMBOLS.' symbols');
        }
        
        if (User::LAST_NAME_MIN_SYMBOLS > strlen($this->lastName) || User::LAST_NAME_MAX_SYMBOLS < strlen($this->lastName)) {
            throw new DtoValidationException('User last name should be between '.User::LAST_NAME_MIN_SYMBOLS.' and '.User::LAST_NAME_MAX_SYMBOLS.' symbols');
        }
    }
    
    private function trimAllData(): void
    {
        $this->firstName = trim($this->firstName);
        $this->lastName = trim($this->lastName);
    }
}
