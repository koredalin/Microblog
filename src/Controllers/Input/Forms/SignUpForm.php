<?php

namespace App\Controllers\Input\Forms;

use App\Controllers\Input\Forms\SignInForm;

/**
 * Description of SignUpForm
 *
 * @author Hristo
 */
class SignUpForm extends SignInForm
{
    public string $firstName;
    public string $lastName;
}
