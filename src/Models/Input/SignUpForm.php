<?php

namespace App\Models\Input;

use App\Models\Input\SignInForm;
use App\Models\User;
use App\Exceptions\DtoValidationException;

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

    public static function createSignUpForm(
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?string $password
    ): self {
        $form = new self();
        $form->firstName = trim($firstName);
        $form->lastName = trim($lastName);
        $form->email = trim($email);
        $form->password = trim($password);

        return $form;
    }


    public function validate(): void
    {
        if (
            User::FIRST_NAME_MIN_SYMBOLS > mb_strlen($this->firstName)
            || User::FIRST_NAME_MAX_SYMBOLS < mb_strlen($this->firstName)
        ) {
            throw new DtoValidationException('User first name should be between ' . User::FIRST_NAME_MIN_SYMBOLS
                . ' and ' . User::FIRST_NAME_MAX_SYMBOLS . ' symbols');
        }

        if (
            User::LAST_NAME_MIN_SYMBOLS > mb_strlen($this->lastName)
            || User::LAST_NAME_MAX_SYMBOLS < mb_strlen($this->lastName)
        ) {
            throw new DtoValidationException('User last name should be between '
                . User::LAST_NAME_MIN_SYMBOLS . ' and ' . User::LAST_NAME_MAX_SYMBOLS . ' symbols');
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
