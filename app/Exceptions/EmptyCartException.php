<?php

namespace App\Exceptions;

class EmptyCartException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Empty cart');
    }
}
