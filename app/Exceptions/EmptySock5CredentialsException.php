<?php

namespace App\Exceptions;

class EmptySock5CredentialsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Please, sets up socks5 credentials!');
    }
}
