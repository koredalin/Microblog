<?php

namespace App\Controllers\Input\Forms;

use App\Controllers\Input\Forms\SignInForm;

use App\Models\User;

/**
 * Description of SignUpForm
 *
 * @author Hristo
 */
final class SignUpForm extends SignInForm
{
    private string $firstName;
    private string $lastName;
    
    protected function __construct()
    {
        parent::__construct();
    }
    
    public static function create(
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?string $password
    ): self
    {
        $form = new self();
        $this->firstName = trim($firstName);
        $this->lastName = trim($lastName);
        $form->email = trim($email);
        $form->password = trim($password);
        
        return $form;
    }
    
    
    public function validate(): void
    {
        $this->trimAllData();
        
        if (User::FIRST_NAME_MIN_SYMBOLS > strlen($this->firstName) || User::FIRST_NAME_MAX_SYMBOLS < strlen($this->firstName)) {
            throw new DtoValidationException('User first name should be between '.User::FIRST_NAME_MIN_SYMBOLS.' and '.User::FIRST_NAME_MAX_SYMBOLS.' symbols');
        }
        
        if (User::LAST_NAME_MIN_SYMBOLS > strlen($this->lastName) || User::LAST_NAME_MAX_SYMBOLS < strlen($this->lastName)) {
            throw new DtoValidationException('User last name should be between '.User::LAST_NAME_MIN_SYMBOLS.' and '.User::LAST_NAME_MAX_SYMBOLS.' symbols');
        }
    }
    
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    
    public function getLastName(): string
    {
        return $this->lastName;
    }
}
