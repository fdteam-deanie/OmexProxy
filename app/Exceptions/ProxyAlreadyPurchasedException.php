<?php

namespace App\Exceptions;

class ProxyAlreadyPurchasedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Proxy already purchased');
    }
}
