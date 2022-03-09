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
    public const FIRST_NAME_MIN_SYMBOLS = 3;
    public const FIRST_NAME_MAX_SYMBOLS = 60;
    public const LAST_NAME_MIN_SYMBOLS = 3;
    public const LAST_NAME_MAX_SYMBOLS = 60;
    public const EMAIL_MAX_SYMBOLS = 255;
    public const PASSWORD_HASH_MAX_SYMBOLS = 255;

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
        $this->validateFirstName();
        $this->validateLastName();
        $this->validateEmail();
        $this->validatePassword();
        $this->validateDates();
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

    private function validateFirstName(): void
    {
        if (
            self::FIRST_NAME_MIN_SYMBOLS > mb_strlen($this->first_name)
            || self::FIRST_NAME_MAX_SYMBOLS < mb_strlen($this->first_name)
        ) {
            throw new DtoValidationException('User first name should be between ' . self::FIRST_NAME_MIN_SYMBOLS
                . ' and ' . self::FIRST_NAME_MAX_SYMBOLS . ' symbols');
        }
    }

    private function validateLastName(): void
    {
        if (
            self::LAST_NAME_MIN_SYMBOLS > mb_strlen($this->last_name)
            || self::LAST_NAME_MAX_SYMBOLS < mb_strlen($this->last_name)
        ) {
            throw new DtoValidationException('User lasst name should be between ' . self::LAST_NAME_MIN_SYMBOLS
                . ' and ' . self::LAST_NAME_MAX_SYMBOLS . ' symbols');
        }
    }

    private function validateEmail(): void
    {
        if (self::EMAIL_MAX_SYMBOLS < mb_strlen($this->email)) {
            throw new DtoValidationException('User email should has ' . self::EMAIL_MAX_SYMBOLS . ' symbols max.');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new DtoValidationException('Invalid email format.');
        }
    }

    private function validatePassword(): void
    {
        if (self::PASSWORD_HASH_MAX_SYMBOLS < mb_strlen($this->password_hash)) {
            throw new DtoValidationException('User password hash should has ' . self::PASSWORD_HASH_MAX_SYMBOLS
                . ' symbols max.');
        }
    }

    private function validateDates(): void
    {
        if (!DateTimeManager::isValidDateTime($this->created_at)) {
            throw new DtoValidationException('Not valid user creation date format.');
        }

        if (!DateTimeManager::isValidDateTime($this->updated_at)) {
            throw new DtoValidationException('Not valid user update date format.');
        }
    }
}
