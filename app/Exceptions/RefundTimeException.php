<?php

namespace App\Exceptions;

use Exception;

class RefundTimeException extends Exception
{
    public function __construct()
    {
        parent::__construct('Refund time expired');
    }
}
