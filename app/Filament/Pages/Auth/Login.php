<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseAuthLogin; 

class Login extends BaseAuthLogin
{
    // FIX: Remove 'static' so it complies with the parent class property signature
    protected string $view = 'filament.pages.auth.login';
}
