<?php

namespace App\Filament\Pages\Auth;

// FIX: Point to the accurate nested Filament v5 location
use Filament\Auth\Pages\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset; 

class RequestPasswordReset extends BaseRequestPasswordReset
{
    // Points to your custom blade view: resources/views/filament/pages/auth/request-password-reset.blade.php
    protected string $view = 'filament.pages.auth.request-password-reset';
}

// <?php

// namespace App\Filament\Pages\Auth;

// use Filament\Auth\Pages\Login as BaseAuthLogin; 

// class Login extends BaseAuthLogin
// {
//     // FIX: Remove 'static' so it complies with the parent class property signature
//     protected string $view = 'filament.pages.auth.login';
// }
