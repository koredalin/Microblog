<?php

namespace App\Models\Input;

use App\Models\User;
use App\Exceptions\DtoValidationException;

/**
 * Description of SignInForm
 *
 * @author Hristo
 */
class SignInForm
{
    public const PASSWORD_MIN_SYMBOLS = 4;
    public const PASSWORD_MAX_SYMBOLS = 20;

    protected string $email;
    protected string $password;

    protected function __construct()
    {
    }

    public static function createSignInForm(
        ?string $email,
        ?string $password
    ): self {
        $form = new self();
        $form->email = trim($email);
        $form->password = trim($password);

        return $form;
    }


    public function validate(): void
    {
        if (User::EMAIL_MAX_SYMBOLS < mb_strlen($this->email)) {
            throw new DtoValidationException('User email should has ' . User::EMAIL_MAX_SYMBOLS . ' symbols max.');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new DtoValidationException('Invalid email format.');
        }

        if (
            self::PASSWORD_MIN_SYMBOLS > mb_strlen($this->password)
            || self::PASSWORD_MAX_SYMBOLS < mb_strlen($this->password)
        ) {
            throw new DtoValidationException('User password should has ' . self::PASSWORD_MIN_SYMBOLS
                . ' symbols min and ' . self::PASSWORD_MAX_SYMBOLS . ' symbols max.');
        }
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
