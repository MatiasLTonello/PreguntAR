<?php
class LogoutController
{
    public function __construct()
    {
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('location: /login');
    }
}