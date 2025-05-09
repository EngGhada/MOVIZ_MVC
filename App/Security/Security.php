<?php

namespace App\Security;

class Security
{
    public static function isLogged(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function isUser(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user';
    }

    public static function isAdmin(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    public static function getCurrentUserId(): int|bool
    {
        return (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) ? $_SESSION['user']['id'] : false;
    }

    public static function getCurrentUserName(): string|bool
    {
        return (isset($_SESSION['user']) && isset($_SESSION['user']['first_name'])) ? $_SESSION['user']['first_name'] : false;
    }
}
